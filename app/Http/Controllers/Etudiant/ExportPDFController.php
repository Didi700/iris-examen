<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\SessionExamen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportPDFController extends Controller
{
    /**
     * Exporter un relevé de notes individuel
     */
    public function exportRelevé(SessionExamen $session)
    {
        $user = auth()->user();

        // Vérifier que c'est bien la session de cet étudiant
        if ($session->etudiant_id !== $user->id) {
            abort(403);
        }

        // Charger les relations nécessaires
        $session->load([
            'examen.questions',
            'examen.matiere',
            'examen.classe',
            'examen.enseignant',
            'reponses.question'
        ]);

        // Calculer les détails
        $noteMax = $session->note_maximale ?? $session->examen->note_totale;
        $noteSur20 = $noteMax > 0 ? ($session->note_obtenue / $noteMax) * 20 : 0;
        $estReussi = $session->pourcentage >= $session->examen->seuil_reussite;

        $details = [
            'note_obtenue' => $session->note_obtenue ?? 0,
            'note_maximale' => $noteMax,
            'pourcentage' => $session->pourcentage ?? 0,
            'note_sur_20' => $noteSur20,
            'est_reussi' => $estReussi,
            'temps_passe' => $this->formatTemps($session->temps_passe_secondes),
            'questions_correctes' => $session->reponses->where('est_correcte', true)->count(),
            'questions_totales' => $session->examen->questions->count(),
        ];

        // Grouper les réponses
        $reponses = $session->reponses->keyBy('question_id');

        // Générer le PDF
        $pdf = Pdf::loadView('etudiant.exports.releve-notes', compact('session', 'details', 'reponses', 'user'));
        
        // Configuration du PDF
        $pdf->setPaper('a4', 'portrait');
        
        // Nom du fichier
        $filename = 'Releve_' . str_replace(' ', '_', $session->examen->titre) . '_' . $user->nom . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Exporter un bulletin complet (tous les examens)
     */
    public function exportBulletin()
    {
        $user = auth()->user();
        $etudiant = $user->etudiant;

        if (!$etudiant || !$etudiant->classe_id) {
            return redirect()->back()->with('error', 'Vous devez être assigné à une classe.');
        }

        // Charger la classe
        $etudiant->load('classe');

        // Récupérer toutes les sessions
        $sessions = SessionExamen::where('etudiant_id', $user->id)
            ->whereIn('statut', ['termine', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with(['examen.matiere', 'examen.classe'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($sessions->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun résultat disponible pour générer un bulletin.');
        }

        // Calculer les statistiques
        $stats = $this->calculerStatistiques($sessions);

        // Grouper par matière
        $sessionsParMatiere = $sessions->groupBy(function ($session) {
            return $session->examen->matiere->nom;
        });

        // Calculer moyennes par matière
        $moyennesParMatiere = [];
        foreach ($sessionsParMatiere as $matiere => $sessionsMat) {
            $notesSur20 = $sessionsMat->map(function ($s) {
                $noteMax = $s->note_maximale ?? $s->examen->note_totale;
                return $noteMax > 0 ? ($s->note_obtenue / $noteMax) * 20 : 0;
            });
            
            $moyennesParMatiere[$matiere] = [
                'moyenne' => round($notesSur20->avg(), 2),
                'nb_examens' => $sessionsMat->count(),
            ];
        }

        // Générer le PDF
        $pdf = Pdf::loadView('etudiant.exports.bulletin', compact(
            'etudiant',
            'user',
            'sessions',
            'stats',
            'moyennesParMatiere'
        ));

        // Configuration du PDF
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Bulletin_' . $user->nom . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Calculer les statistiques
     */
    private function calculerStatistiques($sessions)
    {
        if ($sessions->isEmpty()) {
            return [
                'nb_examens' => 0,
                'moyenne_generale' => 0,
                'taux_reussite' => 0,
                'nb_reussis' => 0,
                'nb_echoues' => 0,
            ];
        }

        $notesSur20 = $sessions->map(function ($session) {
            $noteMax = $session->note_maximale ?? $session->examen->note_totale;
            return $noteMax > 0 ? ($session->note_obtenue / $noteMax) * 20 : 0;
        });

        $moyenneGenerale = $notesSur20->avg();

        $nbReussis = $sessions->filter(function ($session) {
            return $session->pourcentage >= $session->examen->seuil_reussite;
        })->count();

        return [
            'nb_examens' => $sessions->count(),
            'moyenne_generale' => round($moyenneGenerale, 2),
            'taux_reussite' => round(($nbReussis / $sessions->count()) * 100),
            'nb_reussis' => $nbReussis,
            'nb_echoues' => $sessions->count() - $nbReussis,
        ];
    }

    /**
     * Formater le temps
     */
    private function formatTemps($secondes)
    {
        if (!$secondes) {
            return 'N/A';
        }

        $heures = floor($secondes / 3600);
        $minutes = floor(($secondes % 3600) / 60);

        if ($heures > 0) {
            return sprintf('%dh %02dmin', $heures, $minutes);
        } else {
            return sprintf('%dmin', $minutes);
        }
    }
}