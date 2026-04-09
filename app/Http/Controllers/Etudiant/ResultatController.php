<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\SessionExamen;
use Illuminate\Http\Request;

class ResultatController extends Controller
{
    /**
     * Liste de tous les résultats de l'étudiant
     */
    public function index()
    {
        $user = auth()->user();
        $etudiant = $user->etudiant;

        // Vérifier que l'étudiant existe et a une classe
        if (!$etudiant || !$etudiant->classe_id) {
            return redirect()->route('etudiant.dashboard')
                ->with('warning', 'Vous devez être assigné à une classe pour voir vos résultats.');
        }

        // Récupérer toutes les sessions terminées/corrigées
        $sessions = SessionExamen::where('etudiant_id', $user->id)
            ->whereIn('statut', ['termine', 'corrige', 'soumis'])
            ->with(['examen.matiere', 'examen.classe'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $stats = $this->calculerStatistiques($sessions);

        // Grouper par matière
        $sessionsParMatiere = $sessions->groupBy(function ($session) {
            return $session->examen->matiere->nom;
        });

        return view('etudiant.resultats.index', compact(
            'sessions',
            'stats',
            'sessionsParMatiere',
            'etudiant'
        ));
    }

    /**
     * Afficher le détail d'un résultat
     */
    public function show(SessionExamen $session)
    {
        $user = auth()->user();

        // Vérifier que c'est bien la session de cet étudiant
        if ($session->etudiant_id !== $user->id) {
            abort(403, 'Vous ne pouvez pas accéder à cette copie.');
        }

        // Vérifier que la session est terminée/corrigée
        if (!in_array($session->statut, ['termine', 'corrige', 'soumis'])) {
            return redirect()->route('etudiant.resultats.index')
                ->with('warning', 'Cette copie n\'est pas encore disponible.');
        }

        // Charger les relations
        $session->load([
            'examen.questions',
            'examen.matiere',
            'examen.classe',
            'reponses.question'
        ]);

        // Récupérer les réponses groupées par question
        $reponses = $session->reponses->keyBy('question_id');

        // Calculer les détails
        $details = [
            'note_obtenue' => $session->note_obtenue,
            'note_maximale' => $session->note_maximale ?? $session->examen->note_totale,
            'pourcentage' => $session->pourcentage,
            'note_sur_20' => $this->calculerNoteSur20($session),
            'est_reussi' => $session->pourcentage >= $session->examen->seuil_reussite,
            'temps_passe' => $this->formatTemps($session->temps_passe_secondes),
            'questions_correctes' => $reponses->where('est_correcte', true)->count(),
            'questions_totales' => $session->examen->questions->count(),
        ];

        return view('etudiant.resultats.show', compact(
            'session',
            'reponses',
            'details'
        ));
    }

    /**
     * Calculer les statistiques globales
     */
    private function calculerStatistiques($sessions)
    {
        $sessionsCorrigees = $sessions->whereIn('statut', ['corrige', 'termine']);
        
        if ($sessionsCorrigees->isEmpty()) {
            return [
                'nb_examens' => 0,
                'moyenne_generale' => 0,
                'taux_reussite' => 0,
                'nb_reussis' => 0,
                'nb_echoues' => 0,
                'meilleure_note' => 0,
                'moins_bonne_note' => 0,
            ];
        }

        $notesSur20 = $sessionsCorrigees->map(function ($session) {
            return $this->calculerNoteSur20($session);
        });

        $moyenneGenerale = $notesSur20->avg();

        $nbReussis = $sessionsCorrigees->filter(function ($session) {
            return $session->pourcentage >= $session->examen->seuil_reussite;
        })->count();

        $nbEchoues = $sessionsCorrigees->count() - $nbReussis;

        return [
            'nb_examens' => $sessionsCorrigees->count(),
            'moyenne_generale' => round($moyenneGenerale, 2),
            'taux_reussite' => $sessionsCorrigees->count() > 0 
                ? round(($nbReussis / $sessionsCorrigees->count()) * 100) 
                : 0,
            'nb_reussis' => $nbReussis,
            'nb_echoues' => $nbEchoues,
            'meilleure_note' => round($notesSur20->max(), 2),
            'moins_bonne_note' => round($notesSur20->min(), 2),
        ];
    }

    /**
     * Calculer la note sur 20
     */
    private function calculerNoteSur20($session)
    {
        $noteMax = $session->note_maximale ?? $session->examen->note_totale;
        
        if ($noteMax == 0) {
            return 0;
        }

        return ($session->note_obtenue / $noteMax) * 20;
    }

    /**
     * Formater le temps en format lisible
     */
    private function formatTemps($secondes)
    {
        if (!$secondes) {
            return 'N/A';
        }

        $heures = floor($secondes / 3600);
        $minutes = floor(($secondes % 3600) / 60);
        $sec = $secondes % 60;

        if ($heures > 0) {
            return sprintf('%dh %02dmin', $heures, $minutes);
        } elseif ($minutes > 0) {
            return sprintf('%dmin %02ds', $minutes, $sec);
        } else {
            return sprintf('%ds', $sec);
        }
    }
}