<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    /**
     * Statistiques d'un examen
     */
    public function examen(Examen $examen)
    {
        // Vérifier que l'enseignant est propriétaire
        if ($examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à ces statistiques.');
        }

        $examen->load(['matiere', 'classe', 'questions']);

        // Sessions de l'examen
        $sessions = SessionExamen::where('examen_id', $examen->id)
            ->with(['etudiant.utilisateur'])
            ->get();

        // Sessions terminées (soumis + corrigé)
        $sessionsTerminees = $sessions->whereIn('statut', ['soumis', 'corrige']);

        // Statistiques générales
        $stats = [
            'total_sessions' => $sessions->count(),
            'sessions_en_cours' => $sessions->where('statut', 'en_cours')->count(),
            'sessions_soumises' => $sessions->where('statut', 'soumis')->count(),
            'sessions_corrigees' => $sessions->where('statut', 'corrige')->count(),
            'taux_completion' => $sessions->count() > 0 
                ? round(($sessionsTerminees->count() / $sessions->count()) * 100, 1) 
                : 0,
        ];

        // Statistiques de notes (seulement sessions corrigées)
        $sessionsCorrigees = $sessions->where('statut', 'corrige')->whereNotNull('note_obtenue');

        if ($sessionsCorrigees->count() > 0) {
            $notes = $sessionsCorrigees->pluck('note_obtenue');
            
            $stats['moyenne'] = round($notes->avg(), 2);
            $stats['note_min'] = round($notes->min(), 2);
            $stats['note_max'] = round($notes->max(), 2);
            $stats['mediane'] = $this->calculerMediane($notes->toArray());
            $stats['ecart_type'] = $this->calculerEcartType($notes->toArray());
            
            // Taux de réussite
            $reussis = $sessionsCorrigees->where('pourcentage', '>=', $examen->seuil_reussite)->count();
            $stats['taux_reussite'] = round(($reussis / $sessionsCorrigees->count()) * 100, 1);
            $stats['nb_reussis'] = $reussis;
            $stats['nb_echoues'] = $sessionsCorrigees->count() - $reussis;
        } else {
            $stats['moyenne'] = null;
            $stats['note_min'] = null;
            $stats['note_max'] = null;
            $stats['mediane'] = null;
            $stats['ecart_type'] = null;
            $stats['taux_reussite'] = null;
            $stats['nb_reussis'] = 0;
            $stats['nb_echoues'] = 0;
        }

        // Distribution des notes (par tranches de 5 points)
        $distribution = [];
        if ($sessionsCorrigees->count() > 0) {
            $noteMax = $examen->note_totale;
            $nbTranches = ceil($noteMax / 5);
            
            for ($i = 0; $i < $nbTranches; $i++) {
                $min = $i * 5;
                $max = min(($i + 1) * 5, $noteMax);
                
                $count = $sessionsCorrigees->filter(function($session) use ($min, $max) {
                    return $session->note_obtenue >= $min && $session->note_obtenue < $max;
                })->count();
                
                // Gérer la dernière tranche (inclure la note max)
                if ($i == $nbTranches - 1) {
                    $count = $sessionsCorrigees->filter(function($session) use ($min, $noteMax) {
                        return $session->note_obtenue >= $min && $session->note_obtenue <= $noteMax;
                    })->count();
                }
                
                $distribution[] = [
                    'tranche' => $min . '-' . $max,
                    'count' => $count,
                ];
            }
        }

        // Temps moyen de passage
        if ($sessionsTerminees->count() > 0) {
            $tempsMoyen = $sessionsTerminees->avg('temps_passe_secondes');
            $stats['temps_moyen'] = gmdate('H:i:s', $tempsMoyen);
            $stats['temps_moyen_minutes'] = round($tempsMoyen / 60, 1);
        } else {
            $stats['temps_moyen'] = null;
            $stats['temps_moyen_minutes'] = null;
        }

        // Statistiques par question
        $statsQuestions = [];
        foreach ($examen->questions as $question) {
            $reponses = DB::table('reponses_etudiant')
                ->where('question_id', $question->id)
                ->whereIn('session_examen_id', $sessionsCorrigees->pluck('id'))
                ->get();

            if ($reponses->count() > 0) {
                $correctes = $reponses->where('est_correcte', true)->count();
                $tauxReussite = round(($correctes / $reponses->count()) * 100, 1);
                
                $statsQuestions[] = [
                    'question' => $question->enonce,
                    'type' => $question->type,
                    'points' => $question->pivot->points,
                    'nb_reponses' => $reponses->count(),
                    'nb_correctes' => $correctes,
                    'nb_incorrectes' => $reponses->count() - $correctes,
                    'taux_reussite' => $tauxReussite,
                    'moyenne_points' => round($reponses->avg('points_obtenus'), 2),
                ];
            } else {
                $statsQuestions[] = [
                    'question' => $question->enonce,
                    'type' => $question->type,
                    'points' => $question->pivot->points,
                    'nb_reponses' => 0,
                    'nb_correctes' => 0,
                    'nb_incorrectes' => 0,
                    'taux_reussite' => 0,
                    'moyenne_points' => 0,
                ];
            }
        }

        // Top 5 meilleurs étudiants
        $topEtudiants = $sessionsCorrigees
            ->sortByDesc('note_obtenue')
            ->take(5)
            ->values();

        // Évolution des notes au fil des tentatives (si plusieurs tentatives autorisées)
        $evolutionNotes = [];
        if ($examen->nombre_tentatives_max > 1) {
            $etudiants = $sessionsCorrigees->groupBy('etudiant_id');
            
            foreach ($etudiants as $etudiantId => $sessionsEtudiant) {
                if ($sessionsEtudiant->count() > 1) {
                    $evolutionNotes[] = [
                        'etudiant' => $sessionsEtudiant->first()->etudiant->utilisateur->prenom . ' ' . $sessionsEtudiant->first()->etudiant->utilisateur->nom,
                        'tentatives' => $sessionsEtudiant->sortBy('numero_tentative')->map(function($session) {
                            return [
                                'numero' => $session->numero_tentative,
                                'note' => $session->note_obtenue,
                            ];
                        })->values()->toArray(),
                    ];
                }
            }
        }

        return view('enseignant.statistiques.examen', compact(
            'examen',
            'sessions',
            'stats',
            'distribution',
            'statsQuestions',
            'topEtudiants',
            'evolutionNotes'
        ));
    }

    /**
     * Calculer la médiane
     */
    private function calculerMediane($values)
    {
        sort($values);
        $count = count($values);
        
        if ($count == 0) {
            return 0;
        }
        
        $middle = floor(($count - 1) / 2);
        
        if ($count % 2) {
            // Impair
            return round($values[$middle], 2);
        } else {
            // Pair
            return round(($values[$middle] + $values[$middle + 1]) / 2, 2);
        }
    }

    /**
     * Calculer l'écart-type
     */
    private function calculerEcartType($values)
    {
        $count = count($values);
        
        if ($count == 0) {
            return 0;
        }
        
        $moyenne = array_sum($values) / $count;
        $variance = array_sum(array_map(function($val) use ($moyenne) {
            return pow($val - $moyenne, 2);
        }, $values)) / $count;
        
        return round(sqrt($variance), 2);
    }
}