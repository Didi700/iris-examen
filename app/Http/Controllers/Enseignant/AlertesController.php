<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\SessionExamen;
use App\Models\LogActivite;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertesController extends Controller
{
    /**
     * Liste des alertes anti-triche
     */
    public function index(Request $request)
    {
        $enseignant = auth()->user()->enseignant;

        if (!$enseignant) {
            return redirect()->back()->withErrors(['error' => 'Profil enseignant introuvable.']);
        }

        // Query de base : sessions avec alertes des examens de l'enseignant
        $query = SessionExamen::whereHas('examen', function($q) use ($enseignant) {
                $q->where('enseignant_id', auth()->id());
            })
            ->with(['etudiant.utilisateur', 'examen', 'decisionPar'])
            ->avecAlertes()
            ->whereIn('statut', ['soumis', 'termine', 'corrige']);

        // Filtres
        if ($request->filled('gravite')) {
            $gravite = $request->gravite;
            $query->get()->filter(function($session) use ($gravite) {
                return $session->niveau_gravite == $gravite;
            });
        }

        if ($request->filled('decision')) {
            $query->where('decision_enseignant', $request->decision);
        }

        if ($request->filled('examen_id')) {
            $query->where('examen_id', $request->examen_id);
        }

        // Statistiques
        $stats = [
            'total' => SessionExamen::whereHas('examen', function($q) {
                    $q->where('enseignant_id', auth()->id());
                })
                ->avecAlertes()
                ->count(),
            
            'sans_decision' => SessionExamen::whereHas('examen', function($q) {
                    $q->where('enseignant_id', auth()->id());
                })
                ->avecAlertes()
                ->sansDecision()
                ->count(),
            
            'gravite_elevee' => SessionExamen::whereHas('examen', function($q) {
                    $q->where('enseignant_id', auth()->id());
                })
                ->avecAlertes()
                ->get()
                ->filter(fn($s) => $s->niveau_gravite >= 2)
                ->count(),
        ];

        $sessions = $query->orderBy('created_at', 'desc')->paginate(15);

        // Examens pour le filtre
        $examens = DB::table('examens')
            ->where('enseignant_id', auth()->id())
            ->select('id', 'titre')
            ->get();

        return view('enseignant.alertes.index', compact('sessions', 'stats', 'examens'));
    }

    /**
     * Détail d'une alerte
     */
    public function show(SessionExamen $session)
    {
        $enseignant = auth()->user()->enseignant;

        if (!$enseignant) {
            return redirect()->back()->withErrors(['error' => 'Profil enseignant introuvable.']);
        }

        // Vérifier que l'examen appartient à l'enseignant
        if ($session->examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        $session->load(['etudiant.utilisateur', 'examen', 'decisionPar', 'reponsesEtudiants.question']);

        return view('enseignant.alertes.show', compact('session'));
    }

    /**
     * Prendre une décision sur une alerte
     */
    public function decider(Request $request, SessionExamen $session)
    {
        $enseignant = auth()->user()->enseignant;

        if (!$enseignant) {
            return redirect()->back()->withErrors(['error' => 'Profil enseignant introuvable.']);
        }

        // Vérifier que l'examen appartient à l'enseignant
        if ($session->examen->enseignant_id !== auth()->id()) {
            abort(403, 'Vous n\'avez pas accès à cette session.');
        }

        $request->validate([
            'decision' => ['required', 'in:ignore,avertissement,annulation,sanction'],
            'commentaire' => ['nullable', 'string', 'max:1000'],
        ], [
            'decision.required' => 'Veuillez sélectionner une décision.',
            'decision.in' => 'Décision invalide.',
        ]);

        DB::beginTransaction();
        try {
            $ancienneDecision = $session->decision_enseignant;

            // Mettre à jour la session
            $session->update([
                'decision_enseignant' => $request->decision,
                'commentaire_enseignant' => $request->commentaire,
                'date_decision' => now(),
                'decision_par' => auth()->id(),
            ]);

            // Actions selon la décision
            if ($request->decision === 'annulation') {
                // Annuler la note
                $session->update([
                    'note_obtenue' => 0,
                    'pourcentage' => 0,
                ]);
            }

            // Logger l'action
            LogActivite::create([
                'utilisateur_id' => auth()->id(),
                'action' => 'decision_alerte_triche',
                'module' => 'alertes',
                'modele' => 'SessionExamen',
                'modele_id' => $session->id,
                'description' => "Décision sur alerte : {$request->decision} - Session #{$session->id} - Étudiant: {$session->utilisateur->nomComplet()}",
                'donnees' => [
                    'ancienne_decision' => $ancienneDecision,
                    'nouvelle_decision' => $request->decision,
                    'commentaire' => $request->commentaire,
                ]
            ]);

            // ✅ NOTIFICATION CORRIGÉE
            if ($request->decision !== 'ignore') {
                $this->notifierEtudiant($session, $request->decision, $request->commentaire);
            }

            DB::commit();

            $messages = [
                'ignore' => '✅ Alerte ignorée avec succès.',
                'avertissement' => '⚠️ Avertissement envoyé à l\'étudiant.',
                'annulation' => '❌ Examen annulé. Note mise à 0.',
                'sanction' => '🚫 Sanction enregistrée.',
            ];

            return redirect()
                ->route('enseignant.alertes.index')
                ->with('success', $messages[$request->decision]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur décision alerte', [
                'session_id' => $session->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withErrors(['error' => 'Erreur lors de la prise de décision : ' . $e->getMessage()]);
        }
    }

    /**
     * ✅ MÉTHODE CORRIGÉE : Notifier l'étudiant selon la décision
     */
    private function notifierEtudiant(SessionExamen $session, string $decision, ?string $commentaire)
    {
        $utilisateur = $session->utilisateur;
        
        if (!$utilisateur) {
            return;
        }

        $messages = [
            'avertissement' => [
                'icon' => '⚠️',
                'titre' => 'Avertissement - Comportement suspect',
                'message' => "Des comportements suspects ont été détectés lors de votre examen \"{$session->examen->titre}\". " . ($commentaire ?? ''),
            ],
            'annulation' => [
                'icon' => '❌',
                'titre' => 'Examen annulé',
                'message' => "Votre examen \"{$session->examen->titre}\" a été annulé en raison de comportements suspects détectés. " . ($commentaire ?? ''),
            ],
            'sanction' => [
                'icon' => '🚫',
                'titre' => 'Sanction disciplinaire',
                'message' => "Une sanction a été appliquée concernant l'examen \"{$session->examen->titre}\". " . ($commentaire ?? 'Veuillez contacter votre enseignant.'),
            ],
        ];

        if (isset($messages[$decision])) {
            try {
                // ✅ CRÉER LA NOTIFICATION DIRECTEMENT
                Notification::create([
                    'type' => 'App\\Notifications\\InfoNotification',
                    'notifiable_type' => 'App\\Models\\Utilisateur',
                    'notifiable_id' => $utilisateur->id,
                    'data' => json_encode([
                        'icon' => $messages[$decision]['icon'],
                        'titre' => $messages[$decision]['titre'],
                        'message' => $messages[$decision]['message'],
                    ]),
                    'read_at' => null,
                ]);

                \Log::info('✅ Notification créée', [
                    'utilisateur_id' => $utilisateur->id,
                    'decision' => $decision
                ]);

            } catch (\Exception $e) {
                \Log::warning('Erreur notification étudiant', [
                    'utilisateur_id' => $utilisateur->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Ignorer en masse
     */
    public function ignorerMasse(Request $request)
    {
        $request->validate([
            'sessions' => ['required', 'array'],
            'sessions.*' => ['exists:sessions_examen,id'],
        ]);

        $enseignant = auth()->user()->enseignant;

        if (!$enseignant) {
            return redirect()->back()->withErrors(['error' => 'Profil enseignant introuvable.']);
        }

        $count = 0;

        foreach ($request->sessions as $sessionId) {
            $session = SessionExamen::find($sessionId);
            
            if ($session && $session->examen->enseignant_id === auth()->id()) {
                $session->update([
                    'decision_enseignant' => 'ignore',
                    'date_decision' => now(),
                    'decision_par' => auth()->id(),
                ]);
                $count++;
            }
        }

        return redirect()
            ->route('enseignant.alertes.index')
            ->with('success', "✅ {$count} alerte(s) ignorée(s).");
    }
}