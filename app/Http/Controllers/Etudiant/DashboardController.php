<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\Etudiant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Récupérer l'étudiant
        $etudiant = Etudiant::where('utilisateur_id', $user->id)->first();
        
        if (!$etudiant) {
            return redirect()->route('home')->with('error', 'Profil étudiant non trouvé.');
        }

        // Classe de l'étudiant
        $classe = $etudiant->classe;

        // ============================================
        // STATISTIQUES GÉNÉRALES
        // ============================================
        $stats = [
            'examens_a_venir' => Examen::where('statut', 'publie')
                ->where('classe_id', $classe->id ?? null)
                ->where('date_debut', '>', now())
                ->count(),

            'examens_termines' => SessionExamen::where('etudiant_id', $etudiant->id)
                ->whereIn('statut', ['soumis', 'corrige'])
                ->count(),

            'moyenne_generale' => $this->calculerMoyenneGenerale($etudiant),
            'taux_reussite' => $this->calculerTauxReussite($etudiant),
        ];

        // ============================================
        // PROCHAINS EXAMENS
        // ============================================
        $prochains_examens = Examen::where('statut', 'publie')
            ->where('classe_id', $classe->id ?? null)
            ->where('date_debut', '>', now())
            ->with(['matiere', 'classe'])
            ->orderBy('date_debut', 'asc')
            ->limit(5)
            ->get();

        // ============================================
        // DERNIERS RÉSULTATS
        // ============================================
        $derniers_resultats = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with(['examen.matiere'])
            ->orderBy('date_soumission', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // PROGRESSION PAR MATIÈRE
        // ============================================
        $progressionMatieres = $this->getProgressionMatieres($etudiant);

        // ============================================
        // DONNÉES POUR GRAPHIQUES
        // ============================================
        $graphique_evolution = $this->getGraphiqueEvolution($etudiant);
        $graphique_matieres = $this->getGraphiqueMatieres($etudiant);
        $graphique_comparaison = $this->getGraphiqueComparaison($etudiant, $classe);

        return view('etudiant.dashboard', compact(
            'stats',
            'prochains_examens',
            'derniers_resultats',
            'progressionMatieres',
            'graphique_evolution',
            'graphique_matieres',
            'graphique_comparaison'
        ));
    }

    /**
     * Calculer la moyenne générale
     */
    private function calculerMoyenneGenerale($etudiant)
    {
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with('examen')
            ->get();

        if ($sessions->isEmpty()) {
            return 0;
        }

        $total = 0;
        foreach ($sessions as $session) {
            $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
            if ($noteMax > 0) {
                $total += ($session->note_obtenue / $noteMax) * 20;
            }
        }

        return round($total / $sessions->count(), 2);
    }

    /**
     * Calculer le taux de réussite
     */
    private function calculerTauxReussite($etudiant)
    {
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with('examen')
            ->get();

        if ($sessions->isEmpty()) {
            return 0;
        }

        $reussis = 0;
        foreach ($sessions as $session) {
            $seuil = $session->examen->seuil_reussite ?? 50;
            if ($session->pourcentage >= $seuil) {
                $reussis++;
            }
        }

        return round(($reussis / $sessions->count()) * 100);
    }

    /**
     * Obtenir la progression par matière
     */
    private function getProgressionMatieres($etudiant)
    {
        $resultats = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with('examen.matiere')
            ->get()
            ->groupBy('examen.matiere.nom');

        $progression = [];
        foreach ($resultats as $matiere => $sessions) {
            $total = 0;
            foreach ($sessions as $session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                if ($noteMax > 0) {
                    $total += ($session->note_obtenue / $noteMax) * 20;
                }
            }

            $moyenne = $sessions->count() > 0 ? $total / $sessions->count() : 0;

            $progression[$matiere] = [
                'moyenne' => round($moyenne, 2),
                'nombre_examens' => $sessions->count(),
            ];
        }

        return $progression;
    }

    /**
     * Données pour le graphique d'évolution (courbe)
     */
    private function getGraphiqueEvolution($etudiant)
    {
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with('examen')
            ->orderBy('date_soumission', 'asc')
            ->get();

        $labels = [];
        $notes = [];
        $moyennes = [];
        $somme = 0;
        $count = 0;

        foreach ($sessions as $session) {
            $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
            $noteSur20 = $noteMax > 0 ? round(($session->note_obtenue / $noteMax) * 20, 2) : 0;

            $somme += $noteSur20;
            $count++;

            $labels[] = $session->date_soumission->format('d/m');
            $notes[] = $noteSur20;
            $moyennes[] = round($somme / $count, 2);
        }

        return [
            'labels' => $labels,
            'notes' => $notes,
            'moyennes' => $moyennes,
        ];
    }

    /**
     * Données pour le graphique par matière (radar)
     */
    private function getGraphiqueMatieres($etudiant)
    {
        $resultats = SessionExamen::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['soumis', 'corrige'])
            ->whereNotNull('note_obtenue')
            ->with('examen.matiere')
            ->get()
            ->groupBy('examen.matiere.nom');

        $labels = [];
        $notes = [];

        foreach ($resultats as $matiere => $sessions) {
            $total = 0;
            foreach ($sessions as $session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                if ($noteMax > 0) {
                    $total += ($session->note_obtenue / $noteMax) * 20;
                }
            }

            $moyenne = $sessions->count() > 0 ? $total / $sessions->count() : 0;

            $labels[] = $matiere;
            $notes[] = round($moyenne, 2);
        }

        return [
            'labels' => $labels,
            'notes' => $notes,
        ];
    }

    /**
     * Données pour le graphique de comparaison (barres)
     */
    private function getGraphiqueComparaison($etudiant, $classe)
    {
        if (!$classe) {
            return [
                'labels' => [],
                'mes_moyennes' => [],
                'moyennes_classe' => [],
            ];
        }

        // Récupérer toutes les matières de la classe
        $examensClasse = Examen::where('classe_id', $classe->id)
            ->with('matiere')
            ->get()
            ->groupBy('matiere.nom');

        $labels = [];
        $mesMoyennes = [];
        $moyennesClasse = [];

        foreach ($examensClasse as $matiere => $examens) {
            $examenIds = $examens->pluck('id')->toArray();

            // MA moyenne pour cette matière
            $mesSessions = SessionExamen::where('etudiant_id', $etudiant->id)
                ->whereIn('examen_id', $examenIds)
                ->whereIn('statut', ['soumis', 'corrige'])
                ->whereNotNull('note_obtenue')
                ->with('examen')
                ->get();

            $maTotal = 0;
            $maCount = 0;
            foreach ($mesSessions as $session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                if ($noteMax > 0) {
                    $maTotal += ($session->note_obtenue / $noteMax) * 20;
                    $maCount++;
                }
            }
            $maMoyenne = $maCount > 0 ? round($maTotal / $maCount, 2) : 0;

            // Moyenne de la classe pour cette matière
            $toutesSessionsClasse = SessionExamen::whereIn('examen_id', $examenIds)
                ->whereIn('statut', ['soumis', 'corrige'])
                ->whereNotNull('note_obtenue')
                ->with('examen')
                ->get();

            $classeTotal = 0;
            $classeCount = 0;
            foreach ($toutesSessionsClasse as $session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                if ($noteMax > 0) {
                    $classeTotal += ($session->note_obtenue / $noteMax) * 20;
                    $classeCount++;
                }
            }
            $moyenneClasse = $classeCount > 0 ? round($classeTotal / $classeCount, 2) : 0;

            if ($maCount > 0) { // Seulement si j'ai passé au moins un examen dans cette matière
                $labels[] = $matiere;
                $mesMoyennes[] = $maMoyenne;
                $moyennesClasse[] = $moyenneClasse;
            }
        }

        return [
            'labels' => $labels,
            'mes_moyennes' => $mesMoyennes,
            'moyennes_classe' => $moyennesClasse,
        ];
    }
}