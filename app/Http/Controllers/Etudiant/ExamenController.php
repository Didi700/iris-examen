<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\ReponseEtudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamenController extends Controller
{
    /**
     * Liste des examens
     */
    public function index(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        $classe = $etudiant->classe;

        // Récupérer les matières de la classe
        $matieres = DB::table('examens')
            ->where('classe_id', $classe->id)
            ->join('matieres', 'examens.matiere_id', '=', 'matieres.id')
            ->select('matieres.*')
            ->distinct()
            ->get();

        // Query de base
        $query = Examen::where('classe_id', $classe->id)
            ->where('statut', 'publie')
            ->with(['matiere', 'classe'])
            ->withCount('questions');

        // Filtres
        if ($request->filled('search')) {
            $query->where('titre', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'disponible':
                    $query->where('date_debut', '<=', now())
                          ->where('date_fin', '>=', now());
                    break;
                case 'a_venir':
                    $query->where('date_debut', '>', now());
                    break;
                case 'termine':
                    $query->where('date_fin', '<', now());
                    break;
            }
        }

        $examens = $query->orderBy('date_debut', 'desc')->paginate(10);

        // Charger les sessions pour chaque examen
        foreach ($examens as $examen) {
            $examen->sessions = SessionExamen::where('examen_id', $examen->id)
                ->where('etudiant_id', $etudiant->id)
                ->get();
        }

        return view('etudiant.examens.index', compact('examens', 'matieres'));
    }

    /**
     * Détail d'un examen
     */
    public function show(Examen $examen)
    {
        $etudiant = auth()->user()->etudiant;

        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        // Vérifier que l'examen est pour la classe de l'étudiant
        if ($examen->classe_id !== $etudiant->classe_id) {
            abort(403, 'Vous n\'avez pas accès à cet examen.');
        }

        $examen->load(['matiere', 'classe', 'questions']);

        // Récupérer les sessions de l'étudiant pour cet examen
        $sessions = SessionExamen::where('examen_id', $examen->id)
            ->where('etudiant_id', $etudiant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Session en cours
        $sessionEnCours = $sessions->where('statut', 'en_cours')->first();

        // Vérifier si l'étudiant peut passer l'examen
        $maintenant = now();
        $estDisponible = $maintenant->gte($examen->date_debut) && $maintenant->lte($examen->date_fin);
        
        $nombreTentatives = $sessions->whereIn('statut', ['soumis', 'corrige', 'termine'])->count();
        $peutCommencer = $estDisponible && ($nombreTentatives < $examen->nombre_tentatives_max);

        return view('etudiant.examens.show', compact(
            'examen',
            'sessions',
            'sessionEnCours',
            'peutCommencer',
            'nombreTentatives'
        ));
    }

    /**
     * Démarrer un examen
     */
    public function demarrer(Request $request, Examen $examen)
    {
        // Vérifier que l'utilisateur a un profil étudiant
        if (!auth()->user()->etudiant) {
            return redirect()->back()->withErrors(['error' => 'Profil étudiant introuvable. Contactez l\'administrateur.']);
        }

        $etudiant = auth()->user()->etudiant;

        // Vérifications
        if ($examen->classe_id !== $etudiant->classe_id) {
            return redirect()->back()->withErrors(['error' => 'Vous n\'avez pas accès à cet examen.']);
        }

        $maintenant = now();
        if ($maintenant->lt($examen->date_debut)) {
            return redirect()->back()->withErrors(['error' => 'Cet examen n\'a pas encore commencé.']);
        }

        if ($maintenant->gt($examen->date_fin)) {
            return redirect()->back()->withErrors(['error' => 'Cet examen est terminé.']);
        }

        // Vérifier le nombre de tentatives
        $nombreTentatives = SessionExamen::where('examen_id', $examen->id)
            ->where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige', 'termine'])
            ->count();

        if ($nombreTentatives >= $examen->nombre_tentatives_max) {
            return redirect()->back()->withErrors(['error' => 'Vous avez atteint le nombre maximum de tentatives pour cet examen.']);
        }

        // Vérifier s'il y a déjà une session en cours
        $sessionEnCours = SessionExamen::where('examen_id', $examen->id)
            ->where('etudiant_id', $etudiant->id)
            ->where('statut', 'en_cours')
            ->first();

        if ($sessionEnCours) {
            return redirect()->route('etudiant.examens.passer', $sessionEnCours->id);
        }

        try {
            DB::beginTransaction();

            // Créer une nouvelle session
            $session = SessionExamen::create([
                'examen_id' => $examen->id,
                'etudiant_id' => $etudiant->id,
                'numero_tentative' => $nombreTentatives + 1,
                'date_debut' => $examen->date_debut,
                'date_fin' => $examen->date_fin,
                'statut' => 'en_cours',
                'note_maximale' => $examen->note_totale,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('etudiant.examens.passer', $session->id)
                ->with('success', 'Examen démarré avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors du démarrage de l\'examen', [
                'examen_id' => $examen->id,
                'etudiant_id' => $etudiant->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withErrors(['error' => 'Erreur lors du démarrage de l\'examen : ' . $e->getMessage()]);
        }
    }

    /**
     * Passer un examen (AVEC RANDOMISATION)
     */
    public function passer(SessionExamen $session)
    {
        $etudiant = auth()->user()->etudiant;

        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        // Vérifications
        if ($session->etudiant_id !== $etudiant->id) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        if ($session->statut !== 'en_cours') {
            return redirect()->route('etudiant.examens.resultat', $session->id);
        }

        // Vérifier si le temps est écoulé
        if (now()->gt($session->date_fin)) {
            $session->update(['statut' => 'temps_ecoule']);
            return redirect()->route('etudiant.examens.resultat', $session->id)
                ->with('warning', 'Le temps imparti pour cet examen est écoulé.');
        }

        $examen = $session->examen;
        $examen->load(['questions.reponses']);

        // ============================================
        // RÉCUPÉRER LES QUESTIONS
        // ============================================
        $questions = $examen->questions;

        // ============================================
        // RANDOMISATION DES QUESTIONS
        // ============================================
        if ($examen->ordre_questions_aleatoire || $examen->melanger_questions) {
            if ($session->ordre_questions) {
                // Utiliser l'ordre déjà sauvegardé
                $ordreQuestions = $session->ordre_questions;
                $questions = $questions->sortBy(function($question) use ($ordreQuestions) {
                    $position = array_search($question->id, $ordreQuestions);
                    return $position !== false ? $position : 9999;
                })->values();
            } else {
                // Créer un nouvel ordre aléatoire
                $questions = $questions->shuffle();
                $session->ordre_questions = $questions->pluck('id')->toArray();
                $session->save();
            }
        }

        // ============================================
        // RANDOMISATION DES RÉPONSES
        // ============================================
        if ($examen->ordre_reponses_aleatoire || $examen->melanger_reponses) {
            $ordreReponses = $session->ordre_reponses ?? [];
            
            $questions = $questions->map(function($question) use (&$ordreReponses) {
                // Seulement pour les QCM, choix multiple et vrai/faux
                if (in_array($question->type, ['choix_unique', 'choix_multiple', 'vrai_faux'])) {
                    $questionId = $question->id;
                    
                    if (isset($ordreReponses[$questionId])) {
                        // Utiliser l'ordre déjà sauvegardé
                        $question->reponses = $question->reponses->sortBy(function($reponse) use ($ordreReponses, $questionId) {
                            $position = array_search($reponse->id, $ordreReponses[$questionId]);
                            return $position !== false ? $position : 9999;
                        })->values();
                    } else {
                        // Créer un nouvel ordre aléatoire
                        $question->reponses = $question->reponses->shuffle();
                        $ordreReponses[$questionId] = $question->reponses->pluck('id')->toArray();
                    }
                }
                return $question;
            });
            
            // Sauvegarder l'ordre des réponses
            if ($session->ordre_reponses !== $ordreReponses) {
                $session->ordre_reponses = $ordreReponses;
                $session->save();
            }
        }

        // Récupérer les réponses déjà données
        $reponsesEtudiant = ReponseEtudiant::where('session_examen_id', $session->id)
            ->get()
            ->keyBy('question_id');

        return view('etudiant.examens.passer', compact('session', 'examen', 'questions', 'reponsesEtudiant'));
    }

    /**
     * Soumettre un examen
     */
    public function soumettre(Request $request, SessionExamen $session)
    {
        $etudiant = auth()->user()->etudiant;

        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        // Vérifications
        if ($session->etudiant_id !== $etudiant->id) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        if ($session->statut !== 'en_cours') {
            return redirect()->route('etudiant.examens.resultat', $session->id);
        }

        // 🔒 Vérification du temps (sécurité serveur)
        $examen = $session->examen;
        // date_debut + durée_examen (en minutes)
        $finExamen = \Carbon\Carbon::parse($session->date_debut)
            ->addMinutes($examen->duree_minutes);
        if (now()->greaterThan($finExamen)) {
            // Temps écoulé → on force la soumission
            return redirect()
                ->route('etudiant.examens.resultat', $session->id)
                ->with('warning', '⏰ Le temps de l’examen est écoulé.');
        }

        try {
            DB::beginTransaction();

            $examen = $session->examen;
            $examen->load('questions.reponses');

            $noteObtenue = 0;
            $questionsRepondues = 0;

            Log::info('🔍 Début traitement des réponses', [
                'session_id' => $session->id,
                'nombre_questions' => $examen->questions->count(),
                'reponses_recues' => $request->input('reponses')
            ]);

            // Traiter les réponses
            foreach ($examen->questions as $question) {
                $reponseData = $request->input("reponses.{$question->id}");

                Log::info("Question {$question->id}", [
                    'type' => $question->type,
                    'reponse_data' => $reponseData
                ]);

                if ($reponseData !== null && $reponseData !== '') {
                    $questionsRepondues++;

                    // Supprimer l'ancienne réponse si elle existe
                    ReponseEtudiant::where('session_examen_id', $session->id)
                        ->where('question_id', $question->id)
                        ->delete();

                    if ($question->type === 'choix_unique' || $question->type === 'vrai_faux') {
                        // Une seule réponse
                        $reponseSelectionnee = $question->reponses->find($reponseData);
                        
                        $estCorrecte = $reponseSelectionnee && $reponseSelectionnee->est_correcte;
                        $pointsObtenus = $estCorrecte ? $question->pivot->points : 0;

                        ReponseEtudiant::create([
                            'session_examen_id' => $session->id,
                            'question_id' => $question->id,
                            'reponse_donnee' => (string)$reponseData,
                            'est_correcte' => $estCorrecte,
                            'points_obtenus' => $pointsObtenus,
                            'temps_passe_secondes' => 0,
                        ]);

                        $noteObtenue += $pointsObtenus;

                        Log::info("✅ Choix unique traité", [
                            'question_id' => $question->id,
                            'est_correcte' => $estCorrecte,
                            'points' => $pointsObtenus
                        ]);

                    } elseif ($question->type === 'choix_multiple') {
                        // Plusieurs réponses possibles
                        $reponsesSelectionnees = is_array($reponseData) ? $reponseData : [$reponseData];
                        $reponsesCorrectes = $question->reponses->where('est_correcte', true)->pluck('id')->toArray();

                        // Vérifier si toutes les bonnes réponses sont cochées et aucune mauvaise
                        $estToutCorrect = empty(array_diff($reponsesCorrectes, $reponsesSelectionnees)) 
                                       && empty(array_diff($reponsesSelectionnees, $reponsesCorrectes));

                        $pointsObtenus = $estToutCorrect ? $question->pivot->points : 0;

                        ReponseEtudiant::create([
                            'session_examen_id' => $session->id,
                            'question_id' => $question->id,
                            'reponse_donnee' => json_encode($reponsesSelectionnees),
                            'est_correcte' => $estToutCorrect,
                            'points_obtenus' => $pointsObtenus,
                            'temps_passe_secondes' => 0,
                        ]);

                        $noteObtenue += $pointsObtenus;

                        Log::info("✅ Choix multiple traité", [
                            'question_id' => $question->id,
                            'est_correcte' => $estToutCorrect,
                            'points' => $pointsObtenus
                        ]);

                    } elseif ($question->type === 'texte_libre' || $question->type === 'reponse_courte') {
                        // Réponse courte - nécessite correction manuelle
                        ReponseEtudiant::create([
                            'session_examen_id' => $session->id,
                            'question_id' => $question->id,
                            'reponse_donnee' => $reponseData,
                            'est_correcte' => null,
                            'points_obtenus' => null,
                            'temps_passe_secondes' => 0,
                        ]);

                        Log::info("✅ Texte libre enregistré", [
                            'question_id' => $question->id,
                            'longueur_reponse' => strlen($reponseData)
                        ]);
                    }
                } else {
                    Log::warning("⚠️ Pas de réponse pour question {$question->id}");
                }
            }

            // Calculer le temps passé
            $tempsPasseSecondes = now()->diffInSeconds($session->date_debut);

            // Mettre à jour la session
            $pourcentage = $examen->note_totale > 0 ? ($noteObtenue / $examen->note_totale) * 100 : 0;

            // Déterminer le statut
            $aDesQuestionsACorriger = $examen->questions->whereIn('type', ['texte_libre', 'reponse_courte'])->count() > 0;
            $nouveauStatut = ($examen->type_examen === 'en_ligne' && !$aDesQuestionsACorriger) ? 'corrige' : 'soumis';

            Log::info('📊 Calculs finaux', [
                'note_obtenue' => $noteObtenue,
                'note_totale' => $examen->note_totale,
                'pourcentage' => $pourcentage,
                'statut' => $nouveauStatut,
                'questions_repondues' => $questionsRepondues,
                'a_corriger' => $aDesQuestionsACorriger
            ]);

            $session->update([
                'date_soumission' => now(),
                'statut' => $nouveauStatut,
                'note_obtenue' => $noteObtenue,
                'pourcentage' => $pourcentage,
                'temps_passe_secondes' => $tempsPasseSecondes,
                'questions_repondues' => $questionsRepondues,
            ]);
            //AJOUTER CE CODE :

    // ✅ ENREGISTRER LES ALERTES DE TRICHE
            if ($request->filled('changements_onglet') && $request->changements_onglet > 0) {       
                for ($i = 0; $i < $request->changements_onglet; $i++) {
                    $session->ajouterAlerte('changement_onglet', [
                        'message' => 'Changement d\'onglet détecté'
                    ]);
                }    
            }
            if ($request->filled('tentatives_copier') && $request->tentatives_copier > 0) {
                for ($i = 0; $i < $request->tentatives_copier; $i++) {
                    $session->ajouterAlerte('tentative_copier', [
                        'message' => 'Tentative de copier détectée'
                    ]);
                }
            }
            Log::info('✅ Alertes anti-triche enregistrées', [
                'changements_onglet' => $request->changements_onglet ?? 0,
                'tentatives_copier' => $request->tentatives_copier ?? 0    
            ]);


            Log::info('✅ Session mise à jour avec succès', [
                'session_id' => $session->id,
                'nouveau_statut' => $session->statut
            ]);

            DB::commit();

            Log::info('✅ Transaction commit réussie - Redirection');

            // Rediriger selon l'affichage des résultats
            if ($examen->afficher_resultats_immediatement) {
                return redirect()->route('etudiant.examens.resultat', $session->id)
                    ->with('success', 'Examen soumis avec succès !');
            } else {
                return redirect()->route('etudiant.examens.index')
                    ->with('success', 'Examen soumis avec succès ! Les résultats seront disponibles après correction.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('❌ Erreur lors de la soumission', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withErrors(['error' => 'Erreur lors de la soumission : ' . $e->getMessage()]);
        }
    }

    /**
     * Voir le résultat d'un examen
     */
    public function resultat(SessionExamen $session)
    {
        $etudiant = auth()->user()->etudiant;

        if (!$etudiant) {
            return redirect()->route('etudiant.dashboard')->withErrors(['error' => 'Profil étudiant introuvable.']);
        }

        // Vérifications
        if ($session->etudiant_id !== $etudiant->id) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        if ($session->statut === 'en_cours') {
            return redirect()->route('etudiant.examens.passer', $session->id);
        }

        $examen = $session->examen;
        $examen->load('questions.reponses');

        // Récupérer les réponses de l'étudiant
        $reponses = ReponseEtudiant::where('session_examen_id', $session->id)
            ->get()
            ->keyBy('question_id');

        return view('etudiant.examens.resultat', compact('session', 'examen', 'reponses'));
    }
}