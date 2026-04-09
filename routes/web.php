<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Enseignant\ExamenController as EnseignantExamenController;
use App\Http\Controllers\Enseignant\QuestionController as EnseignantQuestionController;
use App\Http\Controllers\Enseignant\ClasseController as EnseignantClasseController;
use App\Http\Controllers\Enseignant\DashboardController as EnseignantDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->estAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->estEnseignant()) {
            return redirect()->route('enseignant.dashboard');
        } elseif ($user->estEtudiant()) {
            return redirect()->route('etudiant.dashboard');
        }
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Routes d'authentification (pour les invités)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Inscription
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Mot de passe oublié
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Routes de réinitialisation mot de passe (SANS middleware)
|--------------------------------------------------------------------------
*/

// ✅ PREMIÈRE DÉFINITION DU MOT DE PASSE (après création compte)
Route::get('/definir-mot-de-passe/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.first-time');
Route::post('/definir-mot-de-passe', [ResetPasswordController::class, 'reset'])->name('password.first-time.update');

// ✅ RÉINITIALISATION DU MOT DE PASSE (mot de passe oublié)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Routes pour utilisateurs authentifiés (SANS vérification mot de passe)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Déconnexion
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Vérification email
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    // Changement de mot de passe (ACCESSIBLE SANS VÉRIFICATION)
    Route::get('/profil/changer-mot-de-passe', [App\Http\Controllers\ProfilController::class, 'changerMotDePasseForm'])
        ->name('profil.changer-mot-de-passe');
    Route::post('/profil/changer-mot-de-passe', [App\Http\Controllers\ProfilController::class, 'changerMotDePasse'])
        ->name('profil.changer-mot-de-passe.post');
});

/*
|--------------------------------------------------------------------------
| Routes protégées avec vérification du mot de passe
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verifier.mot.de.passe'])->group(function () {
    
    // Dashboard général (redirige selon le rôle)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // NOTIFICATIONS (pour tous les utilisateurs)
    // ============================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes ADMIN (Super Admin et Admin)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::resource('utilisateurs', \App\Http\Controllers\Admin\UtilisateurController::class);
        Route::post('utilisateurs/{utilisateur}/toggle-status', [\App\Http\Controllers\Admin\UtilisateurController::class, 'toggleStatus'])->name('utilisateurs.toggle-status');
        
        // Gestion des classes
        Route::resource('classes', \App\Http\Controllers\Admin\ClasseController::class)->parameters([
            'classes' => 'classe'
        ]);
        
        // Gestion des matières
        Route::resource('matieres', \App\Http\Controllers\Admin\MatiereController::class);
        
        // Affectation des enseignants aux classes/matières
        Route::get('enseignants/{enseignant}/affecter', [\App\Http\Controllers\Admin\EnseignantClasseController::class, 'create'])->name('enseignants.affecter');
        Route::post('enseignants/{enseignant}/affecter', [\App\Http\Controllers\Admin\EnseignantClasseController::class, 'store'])->name('enseignants.affecter.store');
        Route::delete('enseignants/{enseignant}/affectations/{affectation}', [\App\Http\Controllers\Admin\EnseignantClasseController::class, 'destroy'])->name('enseignants.affecter.destroy');

        // Affectation individuelle d'étudiants
        Route::get('classes/{classe}/affecter', [\App\Http\Controllers\Admin\AffectationController::class, 'showAffectationForm'])->name('affectations.create');
        Route::post('classes/{classe}/affecter', [\App\Http\Controllers\Admin\AffectationController::class, 'affecterEtudiant'])->name('affectations.store');
        Route::get('classes/{classe}/affectations/{etudiant}/edit', [\App\Http\Controllers\Admin\AffectationController::class, 'editAffectation'])->name('affectations.edit');
        Route::put('classes/{classe}/affectations/{etudiant}', [\App\Http\Controllers\Admin\AffectationController::class, 'updateAffectation'])->name('affectations.update');
        Route::delete('classes/{classe}/affectations/{etudiant}', [\App\Http\Controllers\Admin\AffectationController::class, 'retirerEtudiant'])->name('affectations.destroy');
        
        // Affectation en masse d'étudiants
        Route::get('classes/{classe}/affecter-masse', [\App\Http\Controllers\Admin\AffectationController::class, 'affectationMasse'])->name('affectations.masse');
        Route::post('classes/{classe}/affecter-masse', [\App\Http\Controllers\Admin\AffectationController::class, 'storeAffectationMasse'])->name('affectations.masse.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes ENSEIGNANT
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
        
        // ============================================
        // DASHBOARD
        // ============================================
        Route::get('/dashboard', [EnseignantDashboardController::class, 'index'])->name('dashboard');

        // ============================================
        // MES CLASSES
        // ============================================
        Route::get('/mes-classes', [EnseignantClasseController::class, 'mesClasses'])->name('classes.index');
        Route::get('/mes-classes/{classe}', [EnseignantClasseController::class, 'show'])->name('classes.show');
        Route::get('/mes-classes/{classe}/matiere/{matiere}', [EnseignantClasseController::class, 'showMatiere'])->name('classes.matiere');

        // ============================================
        // QUESTIONS
        // ============================================
        Route::resource('questions', EnseignantQuestionController::class);
        Route::post('questions/{question}/duplicate', [EnseignantQuestionController::class, 'duplicate'])->name('questions.duplicate');
        Route::post('questions/{question}/toggle-active', [EnseignantQuestionController::class, 'toggleActive'])->name('questions.toggle-active');

        // ============================================
        // EXAMENS
        // ============================================
        
        // Routes de gestion des questions de l'examen (AVANT le resource)
        Route::get('examens/{examen}/questions', [EnseignantExamenController::class, 'gererQuestions'])->name('examens.questions');
        Route::post('examens/{examen}/ajouter-questions', [EnseignantExamenController::class, 'ajouterQuestions'])->name('examens.ajouter-questions');
        Route::delete('examens/{examen}/retirer-question/{question}', [EnseignantExamenController::class, 'retirerQuestion'])->name('examens.retirer-question');
        Route::post('examens/{examen}/reordonner-questions', [EnseignantExamenController::class, 'reordonnerQuestions'])->name('examens.reordonner-questions');
        Route::patch('examens/{examen}/modifier-points/{question}', [EnseignantExamenController::class, 'modifierPoints'])->name('examens.modifier-points');
        
            // 🚨 ALERTES ANTI-TRICHE
    
        Route::prefix('alertes')->name('alertes.')->group(function () {
            Route::get('/', [App\Http\Controllers\Enseignant\AlertesController::class, 'index'])->name('index');
            Route::get('/{session}', [App\Http\Controllers\Enseignant\AlertesController::class, 'show'])->name('show');
            Route::post('/{session}/decider', [App\Http\Controllers\Enseignant\AlertesController::class, 'decider'])->name('decider');
            Route::post('/ignorer-masse', [App\Http\Controllers\Enseignant\AlertesController::class, 'ignorerMasse'])->name('ignorer-masse');
        });

        // Actions sur les examens (AVANT le resource)
        Route::post('examens/{examen}/duplicate', [EnseignantExamenController::class, 'duplicate'])->name('examens.duplicate');
        Route::post('examens/{examen}/publier', [EnseignantExamenController::class, 'publier'])->name('examens.publier');
        Route::post('examens/{examen}/archiver', [EnseignantExamenController::class, 'archiver'])->name('examens.archiver');
        
        // CRUD des examens
        Route::resource('examens', EnseignantExamenController::class);

        // ============================================
        // CORRECTIONS
        // ============================================
        Route::prefix('corrections')->name('corrections.')->group(function () {
            // Liste des corrections
            Route::get('/', [App\Http\Controllers\Enseignant\CorrectionController::class, 'index'])
                ->name('index');
            
            // Afficher une correction
            Route::get('/{session}', [App\Http\Controllers\Enseignant\CorrectionController::class, 'show'])
                ->name('show');
            
            // Enregistrer/Corriger une session
            Route::post('/{session}/corriger', [App\Http\Controllers\Enseignant\CorrectionController::class, 'corriger'])
                ->name('corriger');
            
            // Publier les notes d'une session
            Route::post('/{session}/publier', [App\Http\Controllers\Enseignant\CorrectionController::class, 'publier'])
                ->name('publier');
            
            // Dépublier les notes d'une session
            Route::post('/{session}/depublier', [App\Http\Controllers\Enseignant\CorrectionController::class, 'depublier'])
                ->name('depublier');
            
            // Correction automatique d'une session
            Route::post('/{session}/auto-corriger', [App\Http\Controllers\Enseignant\CorrectionController::class, 'corrigerAutomatiquement'])
                ->name('auto-corriger');
            
            // Corriger toutes les sessions d'un examen
            Route::post('/examen/{examen}/corriger-tout', [App\Http\Controllers\Enseignant\CorrectionController::class, 'corrigerToutesSessionsExamen'])
                ->name('corriger-tout');
            
            // Publier toutes les corrections d'un examen
            Route::post('/examen/{examen}/publier-tout', [App\Http\Controllers\Enseignant\CorrectionController::class, 'publierTout'])
                ->name('publier-tout');
        });

        // ============================================
        // EXPORT PDF
        // ============================================
        Route::get('/examens/{examen}/export-pdf', [App\Http\Controllers\Enseignant\ExportController::class, 'exportResultatsExamen'])
            ->name('examens.export-pdf');
        
        Route::get('/sessions/{session}/export-pdf', [App\Http\Controllers\Enseignant\ExportController::class, 'exportResultatEtudiant'])
            ->name('sessions.export-pdf');
        
        // ============================================
        // STATISTIQUES
        // ============================================
        Route::get('/statistiques', [App\Http\Controllers\Enseignant\StatistiquesController::class, 'index'])
            ->name('statistiques.index');
        Route::get('/statistiques/examen/{examen}', [App\Http\Controllers\Enseignant\StatistiquesController::class, 'examen'])
            ->name('statistiques.examen');
        Route::get('/statistiques/question/{question}', [App\Http\Controllers\Enseignant\StatistiquesController::class, 'question'])
            ->name('statistiques.question');
        
        // ============================================
        // IMPORT/EXPORT
        // ============================================
        Route::prefix('import-export')->name('import-export.')->group(function () {
            Route::get('/', [App\Http\Controllers\Enseignant\ImportExportController::class, 'index'])
                ->name('index');
            Route::get('/export', [App\Http\Controllers\Enseignant\ImportExportController::class, 'export'])
                ->name('export');
            Route::get('/export-pdf', [App\Http\Controllers\Enseignant\ImportExportController::class, 'exportPdf'])
                ->name('export-pdf');
            Route::get('/template', [App\Http\Controllers\Enseignant\ImportExportController::class, 'downloadTemplate'])
                ->name('template');
            Route::post('/import', [App\Http\Controllers\Enseignant\ImportExportController::class, 'import'])
                ->name('import');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Routes ÉTUDIANT
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
        
        // ============================================
        // DASHBOARD
        // ============================================
        Route::get('/dashboard', [App\Http\Controllers\Etudiant\DashboardController::class, 'index'])
            ->name('dashboard');

        // ============================================
        // CALENDRIER
        // ============================================
        Route::get('/calendrier', [App\Http\Controllers\Etudiant\CalendrierController::class, 'index'])
            ->name('calendrier');
        Route::get('/calendrier/jour', [App\Http\Controllers\Etudiant\CalendrierController::class, 'examensJour'])
            ->name('calendrier.jour');

        // ============================================
        // EXAMENS
        // ============================================
        Route::prefix('examens')->name('examens.')->group(function () {
            Route::get('/', [App\Http\Controllers\Etudiant\ExamenController::class, 'index'])
                ->name('index');
            
            Route::get('/{examen}', [App\Http\Controllers\Etudiant\ExamenController::class, 'show'])
                ->name('show');
            
            // Démarrer un examen
            Route::post('/{examen}/demarrer', [App\Http\Controllers\Etudiant\ExamenController::class, 'demarrer'])
                ->name('demarrer');
            
            Route::get('/{session}/passer', [App\Http\Controllers\Etudiant\ExamenController::class, 'passer'])
                ->name('passer');
            
            // Soumettre un examen
            Route::post('/{session}/soumettre', [App\Http\Controllers\Etudiant\ExamenController::class, 'soumettre'])
                ->name('soumettre');
            
            Route::get('/{session}/resultat', [App\Http\Controllers\Etudiant\ExamenController::class, 'resultat'])
                ->name('resultat');
        });

        // ============================================
        // RÉSULTATS
        // ============================================
        Route::get('/resultats', [App\Http\Controllers\Etudiant\ResultatController::class, 'index'])
            ->name('resultats.index');
        Route::get('/resultats/{session}', [App\Http\Controllers\Etudiant\ResultatController::class, 'show'])
            ->name('resultats.show');
        
        // ============================================
        // EXPORT PDF ÉTUDIANT
        // ============================================
        Route::prefix('pdf')->name('pdf.')->group(function () {
            // Relevé de notes complet
            Route::get('/releve', [App\Http\Controllers\Etudiant\PdfController::class, 'releve'])
                ->name('releve');
            
            // Bulletin détaillé avec graphiques
            Route::get('/bulletin', [App\Http\Controllers\Etudiant\PdfController::class, 'bulletin'])
                ->name('bulletin');
            
            // Certificat de réussite pour un examen
            Route::get('/certificat/{session}', [App\Http\Controllers\Etudiant\PdfController::class, 'certificat'])
                ->name('certificat');
            
            // Correction détaillée d'un examen
            Route::get('/correction/{session}', [App\Http\Controllers\Etudiant\PdfController::class, 'correction'])
                ->name('correction');
        });
    });
});