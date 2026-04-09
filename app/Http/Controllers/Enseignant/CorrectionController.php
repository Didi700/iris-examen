<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\ReponseEtudiant;
use App\Models\LogActivite;
use App\Models\Classe;
use App\Notifications\ResultatsDisponibles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CorrectionController extends Controller
{
    public function index(Request $request)
    {
        $enseignant = auth()->user();
        
        $query = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', $enseignant->id);
            })
            ->whereIn('statut', ['soumis', 'termine'])
            ->with(['examen.matiere', 'examen.classe', 'etudiant']);

        if ($request->filled('statut_correction')) {
            $query->where('statut_correction', $request->statut_correction);
        }

        if ($request->filled('examen_id')) {
            $query->where('examen_id', $request->examen_id);
        }

        if ($request->filled('classe_id')) {
            $query->whereHas('examen', function($q) use ($request) {
                $q->where('classe_id', $request->classe_id);
            });
        }

        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->whereHas('etudiant', function($q) use ($recherche) {
                $q->where('nom', 'LIKE', "%{$recherche}%")
                  ->orWhere('prenom', 'LIKE', "%{$recherche}%")
                  ->orWhere('matricule', 'LIKE', "%{$recherche}%");
            });
        }

        $query->orderBy('date_fin', 'desc');
        
        $sessions = $query->paginate(20)->appends($request->all());

        $examens = Examen::where('enseignant_id', $enseignant->id)
            ->with('matiere')
            ->orderBy('titre')
            ->get();

        $classes = Classe::whereHas('enseignants', function($q) use ($enseignant) {
                $q->where('enseignant_classe.enseignant_id', $enseignant->id);
            })
            ->orderBy('nom')
            ->get();

        $stats = [
            'en_attente' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->where('statut_correction', 'en_attente')
                ->count(),
            
            'corrigees' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->where('statut_correction', 'corrige')
                ->count(),
            
            'publiees' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->where('statut_correction', 'publie')
                ->count(),
            
            'total' => SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                    $q->where('enseignant_id', $enseignant->id);
                })
                ->whereIn('statut', ['soumis', 'termine'])
                ->count(),
        ];

        return view('enseignant.corrections.index', compact('sessions', 'examens', 'classes', 'stats'));
    }
    
    public function show(SessionExamen $session)
    {
        $enseignant = auth()->user();
        
        if ($session->examen->enseignant_id !== $enseignant->id) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        $session->load([
            'examen.questions.reponses',
            'examen.matiere',
            'examen.classe',
            'etudiant',
        ]);

        $examen = $session->examen;
        $etudiant = $session->etudiant;
        
        $reponses = $session->reponsesEtudiants()->with('question.reponses')->get()->keyBy('question_id');

        $nbQuestionsAuto = $examen->questions()
            ->whereIn('type', ['choix_unique', 'choix_multiple', 'vrai_faux'])
            ->count();

        $nbQuestionsOuvertesCorrigees = $session->reponsesEtudiants()
            ->whereHas('question', function($q) {
                $q->whereIn('type', ['ouverte', 'reponse_courte', 'texte_libre']);
            })
            ->whereNotNull('points_obtenus')
            ->count();

        $nbQuestionsOuvertesACorreiger = $session->reponsesEtudiants()
            ->whereHas('question', function($q) {
                $q->whereIn('type', ['ouverte', 'reponse_courte', 'texte_libre']);
            })
            ->whereNull('points_obtenus')
            ->count();

        $stats = [
            'questions_total' => $examen->questions->count(),
            'questions_auto' => $nbQuestionsAuto,
            'questions_corrigees' => $nbQuestionsOuvertesCorrigees,
            'questions_a_corriger' => $nbQuestionsOuvertesACorreiger,
            'note_actuelle' => $session->note_obtenue ?? 0,
            'note_maximale' => $examen->note_totale,
        ];

        return view('enseignant.corrections.show', compact('session', 'examen', 'reponses', 'etudiant', 'stats'));
    }

    public function corriger(Request $request, SessionExamen $session)
    {
        $enseignant = auth()->user();
        
        if ($session->examen->enseignant_id !== $enseignant->id) {
            abort(403, 'Vous n\'avez pas accès à cette correction.');
        }

        if (!in_array($session->statut, ['termine', 'soumis'])) {
            return back()->with('error', 'Cette session ne peut pas être corrigée.');
        }

        $validated = $request->validate([
            'points' => 'nullable|array',
            'points.*' => 'nullable|numeric|min:0',
            'commentaire' => 'nullable|array',
            'commentaire.*' => 'nullable|string|max:1000',
            'publier' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $noteObtenue = 0;

            // ✅ CORRECTION DES QUESTIONS OUVERTES
            if ($request->has('points')) {
                foreach ($request->points as $questionId => $points) {
                    $reponse = ReponseEtudiant::where(function($q) use ($session) {
                            $q->where('session_id', $session->id)
                              ->orWhere('session_examen_id', $session->id);
                        })
                        ->where('question_id', $questionId)
                        ->first();

                    if (!$reponse) {
                        Log::warning("Réponse non trouvée", [
                            'session_id' => $session->id,
                            'question_id' => $questionId
                        ]);
                        continue;
                    }

                    // ✅ TROUVER LA QUESTION AVEC find() AU LIEU DE where()
                    $question = $session->examen->questions()->find($questionId);

                    if (!$question) {
                        continue;
                    }

                    $pointsMax = $question->pivot->points ?? 0;
                    $pointsOctroyes = min(floatval($points), $pointsMax);
                    
                    $reponse->update([
                        'points_obtenus' => $pointsOctroyes,
                        'est_correct' => $pointsOctroyes > 0,
                        'est_correcte' => $pointsOctroyes > 0,
                        'commentaire' => $request->commentaire[$questionId] ?? null,
                        'commentaire_correcteur' => $request->commentaire[$questionId] ?? null,
                    ]);

                    $noteObtenue += $pointsOctroyes;

                    Log::info("Question corrigée", [
                        'question_id' => $questionId,
                        'points' => $pointsOctroyes,
                        'max' => $pointsMax
                    ]);
                }
            }

            // Ajouter les points auto-corrigés
            $questionsCorigees = $request->has('points') ? array_keys($request->points) : [];
            
            $pointsAutoCorrects = ReponseEtudiant::where(function($q) use ($session) {
                    $q->where('session_id', $session->id)
                      ->orWhere('session_examen_id', $session->id);
                })
                ->whereNotNull('points_obtenus')
                ->when(!empty($questionsCorigees), function($q) use ($questionsCorigees) {
                    return $q->whereNotIn('question_id', $questionsCorigees);
                })
                ->sum('points_obtenus');

            $noteObtenue += $pointsAutoCorrects;

            $noteMaximale = $session->examen->note_totale ?? 20;
            $pourcentage = $noteMaximale > 0 
                ? round(($noteObtenue / $noteMaximale) * 100, 2)
                : 0;

            $nbQuestionsNonCorrigees = ReponseEtudiant::where(function($q) use ($session) {
                    $q->where('session_id', $session->id)
                      ->orWhere('session_examen_id', $session->id);
                })
                ->whereNull('points_obtenus')
                ->count();

            $nouveauStatut = 'en_attente';
            
            if ($nbQuestionsNonCorrigees === 0) {
                $nouveauStatut = $request->boolean('publier') ? 'publie' : 'corrige';
            }

            $session->update([
                'note_obtenue' => $noteObtenue,
                'note_maximale' => $noteMaximale,
                'pourcentage' => $pourcentage,
                'statut_correction' => $nouveauStatut,
            ]);

            Log::info("Session mise à jour", [
                'session_id' => $session->id,
                'note' => $noteObtenue,
                'statut' => $nouveauStatut
            ]);

            if ($nouveauStatut === 'publie') {
                try {
                    $session->etudiant->notify(new ResultatsDisponibles($session));
                    
                    Log::info("✉️ Notification envoyée", [
                        'session_id' => $session->id,
                        'etudiant_email' => $session->etudiant->email,
                        'note' => $noteObtenue
                    ]);
                } catch (\Exception $e) {
                    Log::error("❌ Erreur notification", [
                        'session_id' => $session->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            LogActivite::create([
                'utilisateur_id' => $enseignant->id,
                'action' => $nouveauStatut === 'publie' ? 'correction_publiee' : 'correction_sauvegardee',
                'module' => 'corrections',
                'modele' => 'SessionExamen',
                'modele_id' => $session->id,
                'description' => "Correction de '{$session->examen->titre}' pour {$session->etudiant->prenom} {$session->etudiant->nom} - Note: {$noteObtenue}/{$noteMaximale}",
            ]);

            DB::commit();

            $message = $nouveauStatut === 'publie' 
                ? '✅ Correction enregistrée et publiée ! L\'étudiant a été notifié.'
                : '💾 Correction enregistrée avec succès !';

            return redirect()
                ->route('enseignant.corrections.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('❌ ERREUR CORRECTION', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la correction : ' . $e->getMessage());
        }
    }

    public function publier(SessionExamen $session)
    {
        $enseignant = auth()->user();
        
        if ($session->examen->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        $nbNonCorrigees = ReponseEtudiant::where(function($q) use ($session) {
                $q->where('session_id', $session->id)
                  ->orWhere('session_examen_id', $session->id);
            })
            ->whereNull('points_obtenus')
            ->count();

        if ($nbNonCorrigees > 0) {
            return back()->with('error', "⚠️ {$nbNonCorrigees} question(s) restent à corriger.");
        }

        DB::beginTransaction();
        try {
            $session->update(['statut_correction' => 'publie']);

            try {
                $session->etudiant->notify(new ResultatsDisponibles($session));
                Log::info("✉️ Publication - Notification envoyée", [
                    'session_id' => $session->id,
                    'etudiant_email' => $session->etudiant->email
                ]);
            } catch (\Exception $e) {
                Log::error("❌ Erreur notification publication", [
                    'error' => $e->getMessage()
                ]);
            }

            LogActivite::create([
                'utilisateur_id' => $enseignant->id,
                'action' => 'publication_correction',
                'module' => 'corrections',
                'modele' => 'SessionExamen',
                'modele_id' => $session->id,
                'description' => "Publication de '{$session->examen->titre}' pour {$session->etudiant->prenom} {$session->etudiant->nom}",
            ]);

            DB::commit();
            return back()->with('success', '✅ Résultats publiés ! Notification envoyée.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur publication', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function depublier(SessionExamen $session)
    {
        $enseignant = auth()->user();
        
        if ($session->examen->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        if ($session->statut_correction !== 'publie') {
            return back()->with('error', 'Cette session n\'est pas publiée.');
        }

        $session->update(['statut_correction' => 'corrige']);

        LogActivite::create([
            'utilisateur_id' => $enseignant->id,
            'action' => 'depublication_correction',
            'module' => 'corrections',
            'modele' => 'SessionExamen',
            'modele_id' => $session->id,
            'description' => "Dépublication de '{$session->examen->titre}'",
        ]);

        return back()->with('success', '🔒 Résultats dépubliés.');
    }

    public function corrigerAutomatiquement(SessionExamen $session)
    {
        $enseignant = auth()->user();
        
        if ($session->examen->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        if (!in_array($session->statut, ['termine', 'soumis'])) {
            return back()->with('error', 'Session non terminée.');
        }

        DB::beginTransaction();
        try {
            $noteObtenue = $this->appliquerCorrectionAutomatique($session);

            LogActivite::create([
                'utilisateur_id' => $enseignant->id,
                'action' => 'correction_automatique',
                'module' => 'corrections',
                'modele' => 'SessionExamen',
                'modele_id' => $session->id,
                'description' => "Correction auto de '{$session->examen->titre}' - Note: {$noteObtenue}",
            ]);

            DB::commit();
            return back()->with('success', '✅ Correction automatique effectuée !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur correction auto', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function corrigerToutesSessionsExamen(Examen $examen)
    {
        $enseignant = auth()->user();
        
        if ($examen->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $sessions = SessionExamen::where('examen_id', $examen->id)
                ->whereIn('statut', ['termine', 'soumis'])
                ->where('statut_correction', 'en_attente')
                ->get();

            if ($sessions->isEmpty()) {
                return back()->with('info', 'Aucune session à corriger.');
            }

            $nbCorrigees = 0;

            foreach ($sessions as $session) {
                try {
                    $this->appliquerCorrectionAutomatique($session);
                    $nbCorrigees++;
                } catch (\Exception $e) {
                    Log::error('Erreur session', [
                        'session_id' => $session->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            LogActivite::create([
                'utilisateur_id' => $enseignant->id,
                'action' => 'correction_massive',
                'module' => 'corrections',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Correction de {$nbCorrigees} session(s)",
            ]);

            DB::commit();
            return back()->with('success', "✅ {$nbCorrigees} session(s) corrigée(s) !");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function publierTout(Examen $examen)
    {
        $enseignant = auth()->user();
        
        if ($examen->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $sessions = SessionExamen::where('examen_id', $examen->id)
                ->whereIn('statut', ['termine', 'soumis'])
                ->where('statut_correction', 'corrige')
                ->get();

            if ($sessions->isEmpty()) {
                return back()->with('info', 'Aucune session à publier.');
            }

            $nbPubliees = 0;

            foreach ($sessions as $session) {
                $nbNonCorrigees = ReponseEtudiant::where(function($q) use ($session) {
                        $q->where('session_id', $session->id)
                          ->orWhere('session_examen_id', $session->id);
                    })
                    ->whereNull('points_obtenus')
                    ->count();

                if ($nbNonCorrigees === 0) {
                    $session->update(['statut_correction' => 'publie']);
                    
                    try {
                        $session->etudiant->notify(new ResultatsDisponibles($session));
                    } catch (\Exception $e) {
                        Log::error("❌ Erreur notification massive", [
                            'session_id' => $session->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    $nbPubliees++;
                }
            }

            LogActivite::create([
                'utilisateur_id' => $enseignant->id,
                'action' => 'publication_massive',
                'module' => 'corrections',
                'modele' => 'Examen',
                'modele_id' => $examen->id,
                'description' => "Publication de {$nbPubliees} correction(s)",
            ]);

            DB::commit();
            return back()->with('success', "✅ {$nbPubliees} correction(s) publiée(s) !");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function appliquerCorrectionAutomatique(SessionExamen $session)
    {
        $examen = $session->examen;
        $noteObtenue = 0;

        $questions = $examen->questions()->with('reponses')->get();

        foreach ($questions as $question) {
            $pointsMax = $question->pivot->points ?? 0;

            $reponseEtudiant = ReponseEtudiant::where(function($q) use ($session) {
                    $q->where('session_id', $session->id)
                      ->orWhere('session_examen_id', $session->id);
                })
                ->where('question_id', $question->id)
                ->first();

            if (!$reponseEtudiant) {
                continue;
            }

            if (!in_array($question->type, ['choix_unique', 'choix_multiple', 'vrai_faux'])) {
                continue;
            }

            $pointsObtenus = 0;
            $estCorrect = false;

            $reponseDonnee = $reponseEtudiant->reponse_donnee;
            
            if (is_string($reponseDonnee)) {
                $reponseDonnee = json_decode($reponseDonnee, true);
            }

            switch ($question->type) {
                case 'choix_unique':
                case 'vrai_faux':
                    $reponseId = is_array($reponseDonnee) 
                        ? ($reponseDonnee['reponse_id'] ?? $reponseDonnee[0] ?? null)
                        : $reponseDonnee;
                    
                    if ($reponseId) {
                        $reponseBonne = $question->reponses
                            ->where('id', $reponseId)
                            ->where('est_correcte', true)
                            ->first();

                        if ($reponseBonne) {
                            $pointsObtenus = $pointsMax;
                            $estCorrect = true;
                        }
                    }
                    break;

                case 'choix_multiple':
                    $reponsesBonnes = $question->reponses
                        ->where('est_correcte', true)
                        ->pluck('id')
                        ->sort()
                        ->values()
                        ->toArray();
                    
                    $reponsesEtudiantIds = is_array($reponseDonnee) 
                        ? collect($reponseDonnee)->sort()->values()->toArray()
                        : [];

                    if ($reponsesBonnes === $reponsesEtudiantIds) {
                        $pointsObtenus = $pointsMax;
                        $estCorrect = true;
                    }
                    break;
            }

            $reponseEtudiant->update([
                'points_obtenus' => $pointsObtenus,
                'est_correct' => $estCorrect,
                'est_correcte' => $estCorrect,
            ]);

            $noteObtenue += $pointsObtenus;
        }

        $noteMaximale = $examen->note_totale ?? 20;
        $pourcentage = $noteMaximale > 0 
            ? round(($noteObtenue / $noteMaximale) * 100, 2) 
            : 0;

        $nbNonCorrigees = ReponseEtudiant::where(function($q) use ($session) {
                $q->where('session_id', $session->id)
                  ->orWhere('session_examen_id', $session->id);
            })
            ->whereNull('points_obtenus')
            ->count();
        
        $statutCorrection = $nbNonCorrigees === 0 ? 'corrige' : 'en_attente';

        $session->update([
            'statut_correction' => $statutCorrection,
            'note_obtenue' => $noteObtenue,
            'note_maximale' => $noteMaximale,
            'pourcentage' => $pourcentage,
        ]);

        return $noteObtenue;
    }
}