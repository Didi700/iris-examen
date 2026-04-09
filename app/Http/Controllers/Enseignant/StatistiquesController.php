<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\Question;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiquesController extends Controller
{
    /**
     * Vue d'ensemble des statistiques
     */
    public function index(Request $request)
    {
        $enseignant = auth()->user();
        
        $kpis = $this->getKPIs();
        $statsBase = $this->getStatsBase();
        $moyennesParMatiere = $this->getMoyennesParMatiere();
        $evolutionExamens = $this->getEvolutionExamens();
        
        $examensRecents = Examen::where('enseignant_id', $enseignant->id)
            ->with(['matiere', 'classe'])
            ->withCount(['sessions'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // ✅ SOLUTION : UTILISER UN JOIN DIRECT POUR ÉVITER LES ERREURS DE RELATION
        $sessionsACorreiger = DB::table('sessions_examen')
            ->join('examens', 'sessions_examen.examen_id', '=', 'examens.id')
            ->join('etudiants', 'sessions_examen.etudiant_id', '=', 'etudiants.id')
            ->join('utilisateurs', 'etudiants.utilisateur_id', '=', 'utilisateurs.id')
            ->where('examens.enseignant_id', $enseignant->id)
            ->where('sessions_examen.statut', 'soumis')
            ->orderBy('sessions_examen.date_soumission', 'desc')
            ->select(
                'sessions_examen.id',
                'sessions_examen.date_soumission',
                'utilisateurs.prenom',
                'utilisateurs.nom',
                'examens.titre as examen_titre'
            )
            ->take(5)
            ->get();
        
        return view('enseignant.statistiques.index', compact(
            'kpis',
            'statsBase',
            'moyennesParMatiere',
            'evolutionExamens',
            'examensRecents',
            'sessionsACorreiger'
        ));
    }
    
    /**
     * ✅ NOUVEAU : Statistiques détaillées d'un examen spécifique
     */
    public function examen(Examen $examen)
    {
        $enseignant = auth()->user();

        // Vérifier que l'enseignant est propriétaire
        if ($examen->enseignant_id != $enseignant->id) {
            abort(403, 'Accès non autorisé');
        }

        // Charger les sessions corrigées avec relations
        $sessions = SessionExamen::where('examen_id', $examen->id)
            ->where('statut', 'corrige')
            ->with(['etudiant.utilisateur'])
            ->orderBy('note_obtenue', 'desc')
            ->get();

        // Statistiques générales
        $stats = [
            'nb_participants' => $sessions->count(),
            'moyenne' => round($sessions->avg('pourcentage'), 2),
            'mediane' => $this->calculerMediane($sessions->pluck('pourcentage')),
            'note_min' => round($sessions->min('pourcentage') ?? 0, 2),
            'note_max' => round($sessions->max('pourcentage') ?? 0, 2),
            'ecart_type' => $this->calculerEcartType($sessions->pluck('pourcentage')),
            'taux_reussite' => $this->calculerTauxReussiteExamen($examen, $sessions),
            'temps_moyen' => round($sessions->avg('temps_passe_secondes') / 60, 1),
        ];

        // Répartition des notes par tranches
        $repartitionNotes = $this->calculerRepartitionNotes($sessions);

        // Questions les plus difficiles
        $questionsDifficiles = $this->analyserQuestionsDifficiles($examen);

        // Top 10 étudiants
        $topEtudiants = $sessions->take(10);

        // Étudiants en difficulté (< seuil de réussite)
        $seuil = $examen->seuil_reussite ?? 50;
        $etudiantsDifficulte = $sessions->filter(function($session) use ($seuil) {
            return $session->pourcentage < $seuil;
        });

        // Performance par type de question
        $performanceParType = $this->analyserPerformanceParType($examen);

        return view('enseignant.statistiques.examen', compact(
            'examen',
            'sessions',
            'stats',
            'repartitionNotes',
            'questionsDifficiles',
            'topEtudiants',
            'etudiantsDifficulte',
            'performanceParType'
        ));
    }

    /**
     * ✅ NOUVEAU : Statistiques détaillées d'une question spécifique
     */
    public function question(Question $question)
    {
        $enseignant = auth()->user();

        // Vérifier que l'enseignant est propriétaire
        if ($question->enseignant_id != $enseignant->id) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer toutes les réponses à cette question
        $reponses = DB::table('reponses_etudiant')
            ->join('sessions_examen', 'reponses_etudiant.session_examen_id', '=', 'sessions_examen.id')
            ->join('examens', 'sessions_examen.examen_id', '=', 'examens.id')
            ->join('etudiants', 'sessions_examen.etudiant_id', '=', 'etudiants.id')
            ->join('utilisateurs', 'etudiants.utilisateur_id', '=', 'utilisateurs.id')
            ->where('reponses_etudiant.question_id', $question->id)
            ->where('sessions_examen.statut', 'corrige')
            ->select(
                'reponses_etudiant.*',
                'utilisateurs.prenom',
                'utilisateurs.nom',
                'examens.titre as examen_titre',
                'sessions_examen.date_soumission'
            )
            ->get();

        // Statistiques
        $stats = [
            'nb_reponses' => $reponses->count(),
            'nb_correctes' => $reponses->where('est_correcte', true)->count(),
            'taux_reussite' => $reponses->count() > 0 
                ? round(($reponses->where('est_correcte', true)->count() / $reponses->count()) * 100, 2)
                : 0,
            'points_moyens' => round($reponses->avg('points_obtenus'), 2),
        ];

        // Répartition des réponses (pour QCM)
        $repartitionReponses = [];
        if (in_array($question->type, ['qcm', 'qcm_unique', 'qcm_multiple', 'vrai_faux'])) {
            $repartitionReponses = $this->analyserRepartitionReponses($question, $reponses);
        }

        // Examens utilisant cette question
        $examensUtilisant = DB::table('examen_question')
            ->join('examens', 'examen_question.examen_id', '=', 'examens.id')
            ->leftJoin('matieres', 'examens.matiere_id', '=', 'matieres.id')
            ->leftJoin('classes', 'examens.classe_id', '=', 'classes.id')
            ->where('examen_question.question_id', $question->id)
            ->select(
                'examens.id',
                'examens.titre',
                'matieres.nom as matiere',
                'classes.nom as classe',
                'examen_question.points',
                'examens.created_at'
            )
            ->orderBy('examens.created_at', 'desc')
            ->get();

        return view('enseignant.statistiques.question', compact(
            'question',
            'reponses',
            'stats',
            'repartitionReponses',
            'examensUtilisant'
        ));
    }

    // ============================================
    // MÉTHODES PRIVÉES - CALCUL DES KPIs
    // ============================================
    
    private function getKPIs()
    {
        $enseignant = auth()->user();
        
        $sessionsCorrigees = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->where('statut', 'corrige')
            ->whereNotNull('note_obtenue')
            ->get();
        
        $tauxReussite = 0;
        if ($sessionsCorrigees->count() > 0) {
            $nbReussis = $sessionsCorrigees->filter(function($session) {
                $noteMax = $session->note_maximale ?? $session->examen->note_totale ?? 20;
                $noteSur20 = $noteMax > 0 ? ($session->note_obtenue / $noteMax) * 20 : 0;
                return $noteSur20 >= 10;
            })->count();
            
            $tauxReussite = round(($nbReussis / $sessionsCorrigees->count()) * 100, 1);
        }
        
        $tempsEnSecondes = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->whereIn('statut', ['soumis', 'corrige'])
            ->where('temps_passe_secondes', '>', 0)
            ->avg('temps_passe_secondes');
        
        $tempsMoyen = round(($tempsEnSecondes ?? 0) / 60);
        
        $aCorreiger = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->where('statut', 'soumis')
            ->count();
        
        $debutMoisActuel = Carbon::now()->startOfMonth();
        $debutMoisDernier = Carbon::now()->subMonth()->startOfMonth();
        $finMoisDernier = Carbon::now()->subMonth()->endOfMonth();
        
        $sessionsMoisActuel = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->where('created_at', '>=', $debutMoisActuel)
            ->count();
        
        $sessionsMoisDernier = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->whereBetween('created_at', [$debutMoisDernier, $finMoisDernier])
            ->count();
        
        $tendance = 0;
        $tendanceType = 'stable';
        
        if ($sessionsMoisDernier > 0) {
            $tendance = round((($sessionsMoisActuel - $sessionsMoisDernier) / $sessionsMoisDernier) * 100, 1);
            
            if ($tendance > 5) {
                $tendanceType = 'hausse';
            } elseif ($tendance < -5) {
                $tendanceType = 'baisse';
            }
        }
        
        return [
            'taux_reussite' => $tauxReussite,
            'temps_moyen' => $tempsMoyen,
            'a_corriger' => $aCorreiger,
            'tendance' => abs($tendance),
            'tendance_type' => $tendanceType,
        ];
    }
    
    private function getStatsBase()
    {
        $enseignant = auth()->user();
        
        return [
            'examens' => [
                'total' => Examen::where('enseignant_id', $enseignant->id)->count(),
                'publies' => Examen::where('enseignant_id', $enseignant->id)->where('statut', 'publie')->count(),
                'brouillons' => Examen::where('enseignant_id', $enseignant->id)->where('statut', 'brouillon')->count(),
            ],
            'sessions' => [
                'total' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })->count(),
                'a_corriger' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })->where('statut', 'soumis')->count(),
                'terminees' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })->where('statut', 'corrige')->count(),
            ],
            'questions' => [
                'total' => Question::where('enseignant_id', $enseignant->id)->count(),
                'actives' => Question::where('enseignant_id', $enseignant->id)->where('est_active', true)->count(),
                'inactives' => Question::where('enseignant_id', $enseignant->id)->where('est_active', false)->count(),
            ],
            'classes' => Classe::whereHas('enseignants', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })->count(),
        ];
    }
    
    private function getMoyennesParMatiere()
    {
        $enseignant = auth()->user();
        
        $couleurs = [
            '#10B981', '#3B82F6', '#F59E0B', '#8B5CF6',
            '#EF4444', '#06B6D4', '#EC4899', '#F97316',
        ];
        
        $moyennes = DB::table('sessions_examen')
            ->join('examens', 'sessions_examen.examen_id', '=', 'examens.id')
            ->join('matieres', 'examens.matiere_id', '=', 'matieres.id')
            ->where('examens.enseignant_id', $enseignant->id)
            ->where('sessions_examen.statut', 'corrige')
            ->whereNotNull('sessions_examen.note_obtenue')
            ->select(
                'matieres.id as matiere_id',
                'matieres.nom as matiere',
                DB::raw('COUNT(*) as nb_sessions'),
                DB::raw('AVG(sessions_examen.note_obtenue) as moyenne_obtenue'),
                DB::raw('AVG(COALESCE(sessions_examen.note_maximale, examens.note_totale, 20)) as moyenne_maximale')
            )
            ->groupBy('matieres.id', 'matieres.nom')
            ->orderBy('moyenne_obtenue', 'desc')
            ->get()
            ->map(function($item, $index) use ($couleurs) {
                $noteSur20 = $item->moyenne_maximale > 0 
                    ? round(($item->moyenne_obtenue / $item->moyenne_maximale) * 20, 2) 
                    : 0;
                
                $couleur = $couleurs[$index % count($couleurs)];
                
                return [
                    'matiere' => $item->matiere,
                    'couleur' => $couleur,
                    'note_sur_20' => $noteSur20,
                    'nb_sessions' => $item->nb_sessions,
                    'pourcentage' => round(($noteSur20 / 20) * 100, 1),
                ];
            });
        
        return $moyennes;
    }
    
    private function getEvolutionExamens()
    {
        $enseignant = auth()->user();
        
        $mois = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois[] = $date->locale('fr')->isoFormat('MMM YYYY');
            
            $nbSessions = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereIn('statut', ['soumis', 'corrige'])
                ->count();
            
            $data[] = $nbSessions;
        }
        
        return [
            'labels' => $mois,
            'data' => $data,
        ];
    }

    // ============================================
    // ✅ NOUVELLES MÉTHODES PRIVÉES DE CALCUL
    // ============================================

    private function calculerMediane($values)
    {
        $sorted = $values->sort()->values();
        $count = $sorted->count();

        if ($count === 0) {
            return 0;
        }

        if ($count % 2 === 0) {
            return round(($sorted[$count / 2 - 1] + $sorted[$count / 2]) / 2, 2);
        }

        return round($sorted[floor($count / 2)], 2);
    }

    private function calculerEcartType($values)
    {
        if ($values->isEmpty()) {
            return 0;
        }

        $moyenne = $values->avg();
        $variance = $values->map(function($value) use ($moyenne) {
            return pow($value - $moyenne, 2);
        })->avg();

        return round(sqrt($variance), 2);
    }

    private function calculerTauxReussiteExamen($examen, $sessions)
    {
        if ($sessions->isEmpty()) {
            return 0;
        }

        $seuil = $examen->seuil_reussite ?? 50;
        $reussis = $sessions->filter(function($session) use ($seuil) {
            return $session->pourcentage >= $seuil;
        })->count();

        return round(($reussis / $sessions->count()) * 100, 2);
    }

    private function calculerRepartitionNotes($sessions)
    {
        $tranches = [
            '0-20' => 0,
            '20-40' => 0,
            '40-50' => 0,
            '50-60' => 0,
            '60-70' => 0,
            '70-80' => 0,
            '80-90' => 0,
            '90-100' => 0,
        ];

        foreach ($sessions as $session) {
            $note = $session->pourcentage;
            
            if ($note < 20) $tranches['0-20']++;
            elseif ($note < 40) $tranches['20-40']++;
            elseif ($note < 50) $tranches['40-50']++;
            elseif ($note < 60) $tranches['50-60']++;
            elseif ($note < 70) $tranches['60-70']++;
            elseif ($note < 80) $tranches['70-80']++;
            elseif ($note < 90) $tranches['80-90']++;
            else $tranches['90-100']++;
        }

        return $tranches;
    }

    private function analyserQuestionsDifficiles($examen)
    {
        $questions = DB::table('examen_question')
            ->join('questions', 'examen_question.question_id', '=', 'questions.id')
            ->leftJoin('reponses_etudiant', function($join) use ($examen) {
                $join->on('questions.id', '=', 'reponses_etudiant.question_id')
                     ->join('sessions_examen', function($j) use ($examen) {
                         $j->on('reponses_etudiant.session_examen_id', '=', 'sessions_examen.id')
                           ->where('sessions_examen.examen_id', '=', $examen->id)
                           ->where('sessions_examen.statut', '=', 'corrige');
                     });
            })
            ->where('examen_question.examen_id', $examen->id)
            ->select(
                'questions.id',
                'questions.enonce',
                'questions.type',
                'examen_question.points',
                DB::raw('COUNT(reponses_etudiant.id) as nb_reponses'),
                DB::raw('SUM(CASE WHEN reponses_etudiant.est_correcte = 1 THEN 1 ELSE 0 END) as nb_correctes')
            )
            ->groupBy('questions.id', 'questions.enonce', 'questions.type', 'examen_question.points')
            ->get()
            ->map(function($question) {
                $tauxReussite = $question->nb_reponses > 0 
                    ? round(($question->nb_correctes / $question->nb_reponses) * 100, 2)
                    : 0;
                
                return [
                    'id' => $question->id,
                    'enonce' => $question->enonce,
                    'type' => $question->type,
                    'points' => $question->points,
                    'nb_reponses' => $question->nb_reponses,
                    'taux_reussite' => $tauxReussite,
                ];
            })
            ->sortBy('taux_reussite')
            ->take(5);

        return $questions;
    }

    private function analyserPerformanceParType($examen)
    {
        return DB::table('examen_question')
            ->join('questions', 'examen_question.question_id', '=', 'questions.id')
            ->leftJoin('reponses_etudiant', function($join) use ($examen) {
                $join->on('questions.id', '=', 'reponses_etudiant.question_id')
                     ->join('sessions_examen', function($j) use ($examen) {
                         $j->on('reponses_etudiant.session_examen_id', '=', 'sessions_examen.id')
                           ->where('sessions_examen.examen_id', '=', $examen->id)
                           ->where('sessions_examen.statut', '=', 'corrige');
                     });
            })
            ->where('examen_question.examen_id', $examen->id)
            ->select(
                'questions.type',
                DB::raw('COUNT(DISTINCT questions.id) as nb_questions'),
                DB::raw('COUNT(reponses_etudiant.id) as nb_reponses'),
                DB::raw('SUM(CASE WHEN reponses_etudiant.est_correcte = 1 THEN 1 ELSE 0 END) as nb_correctes')
            )
            ->groupBy('questions.type')
            ->get()
            ->map(function($item) {
                $tauxReussite = $item->nb_reponses > 0 
                    ? round(($item->nb_correctes / $item->nb_reponses) * 100, 2)
                    : 0;
                
                return [
                    'type' => $item->type,
                    'nb_questions' => $item->nb_questions,
                    'nb_reponses' => $item->nb_reponses,
                    'taux_reussite' => $tauxReussite,
                ];
            });
    }

    private function analyserRepartitionReponses($question, $reponses)
    {
        $repartition = [];

        if ($question->type === 'vrai_faux') {
            $repartition = [
                'Vrai' => $reponses->where('reponse_donnee', 'vrai')->count(),
                'Faux' => $reponses->where('reponse_donnee', 'faux')->count(),
            ];
        } else {
            // Pour les QCM, analyser les propositions
            $propositions = DB::table('propositions_reponse')
                ->where('question_id', $question->id)
                ->get();
            
            foreach ($propositions as $prop) {
                $count = $reponses->filter(function($reponse) use ($prop) {
                    $reponseDonnee = is_string($reponse->reponse_donnee) 
                        ? json_decode($reponse->reponse_donnee, true) 
                        : $reponse->reponse_donnee;
                    
                    if (is_array($reponseDonnee)) {
                        return in_array($prop->id, $reponseDonnee);
                    }
                    
                    return $reponseDonnee == $prop->id;
                })->count();
                
                $repartition[$prop->texte] = $count;
            }
        }

        return $repartition;
    }
}