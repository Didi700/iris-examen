<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $enseignantId = auth()->id();

        // Statistiques principales
        $stats = [
            'nb_classes' => DB::table('enseignant_classe')
                ->where('enseignant_id', $enseignantId)
                ->distinct('classe_id')
                ->count('classe_id'),
            
            'nb_matieres' => DB::table('enseignant_classe')
                ->where('enseignant_id', $enseignantId)
                ->distinct('matiere_id')
                ->count('matiere_id'),
            
            'total_examens' => Examen::where('enseignant_id', $enseignantId)->count(),
            
            'examens_publies' => Examen::where('enseignant_id', $enseignantId)
                ->where('statut', 'publie')
                ->count(),
            
            'examens_brouillons' => Examen::where('enseignant_id', $enseignantId)
                ->where('statut', 'brouillon')
                ->count(),
            
            'total_questions' => DB::table('banque_questions')
                ->where('enseignant_id', $enseignantId)
                ->whereNull('deleted_at')
                ->count(),
            
            'questions_actives' => DB::table('banque_questions')
                ->where('enseignant_id', $enseignantId)
                ->where('est_active', true)
                ->whereNull('deleted_at')
                ->count(),
            
            // ✅ CORRIGÉ : Accepter les deux statuts ET vérifier statut_correction
            'sessions_a_corriger' => SessionExamen::whereHas('examen', function($q) use ($enseignantId) {
                    $q->where('enseignant_id', $enseignantId);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->where('statut_correction', 'en_attente')
                ->count(),
            
            // ✅ CORRIGÉ : Compter les sessions corrigées
            'sessions_terminees' => SessionExamen::whereHas('examen', function($q) use ($enseignantId) {
                    $q->where('enseignant_id', $enseignantId);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->whereIn('statut_correction', ['corrige', 'publie'])
                ->count(),
        ];

        // Examens récents
        $examensRecents = Examen::where('enseignant_id', $enseignantId)
            ->with(['matiere', 'classe'])
            ->withCount(['questions', 'sessions'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ✅ CORRIGÉ : Sessions à corriger
        $sessionsACorriger = SessionExamen::whereHas('examen', function($q) use ($enseignantId) {
                $q->where('enseignant_id', $enseignantId);
            })
            ->with(['etudiant', 'examen'])
            ->whereIn('statut', ['soumis', 'termine'])
            ->where('statut_correction', 'en_attente')
            ->orderBy('date_soumission', 'desc')
            ->limit(5)
            ->get();

        // Examens en cours
        $examensEnCours = Examen::where('enseignant_id', $enseignantId)
            ->with('matiere')
            ->withCount('sessions')
            ->where('statut', 'publie')
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now())
            ->orderBy('date_fin')
            ->get();

        // Examens à venir
        $examensAVenir = Examen::where('enseignant_id', $enseignantId)
            ->with('matiere')
            ->where('statut', 'publie')
            ->where('date_debut', '>', now())
            ->orderBy('date_debut')
            ->limit(5)
            ->get();

        // Classes
        $classeIds = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignantId)
            ->pluck('classe_id');
        
        $classes = Classe::whereIn('id', $classeIds)->get();

        // Matières
        $matiereIds = DB::table('enseignant_classe')
            ->where('enseignant_id', $enseignantId)
            ->pluck('matiere_id');
        
        $matieres = Matiere::whereIn('id', $matiereIds)->get();

        return view('enseignant.dashboard', compact(
            'stats',
            'examensRecents',
            'sessionsACorriger',
            'examensEnCours',
            'examensAVenir',
            'classes',
            'matieres'
        ));
    }
}