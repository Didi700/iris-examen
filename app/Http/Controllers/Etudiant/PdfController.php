<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\SessionExamen;
use App\Models\Etudiant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    /**
     * Télécharger le relevé de notes complet
     */
    public function releve()
    {
        $user = auth()->user();
        $etudiant = Etudiant::where('utilisateur_id', $user->id)->first();

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Profil étudiant non trouvé.');
        }

        // Récupérer toutes les sessions corrigées
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->where('statut', 'corrige')
            ->whereNotNull('note_obtenue')
            ->with(['examen.matiere', 'examen.classe'])
            ->orderBy('date_soumission', 'desc')
            ->get();

        // Calculer les statistiques
        $stats = $this->calculerStatistiques($sessions);

        // Générer le PDF
        $pdf = Pdf::loadView('etudiant.pdf.releve', [
            'etudiant' => $etudiant,
            'sessions' => $sessions,
            'stats' => $stats,
            'date_generation' => now(),
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('releve-notes-' . $etudiant->matricule . '.pdf');
    }

    /**
     * Télécharger un certificat de réussite
     */
    public function certificat(SessionExamen $session)
    {
        $user = auth()->user();
        $etudiant = Etudiant::where('utilisateur_id', $user->id)->first();

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Profil étudiant non trouvé.');
        }

        // ✅ VÉRIFICATION CORRIGÉE
        if ($session->etudiant_id != $etudiant->id) {
            \Log::warning('Tentative accès certificat non autorisée', [
                'user_id' => $user->id,
                'etudiant_id' => $etudiant->id,
                'session_etudiant_id' => $session->etudiant_id,
                'session_id' => $session->id,
            ]);
            
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à ce certificat.');
        }

        // Vérifier que l'examen est réussi
        $seuil = $session->examen->seuil_reussite ?? 50;
        if ($session->pourcentage < $seuil) {
            return redirect()->back()->with('error', 'Certificat disponible uniquement pour les examens réussis.');
        }

        $session->load(['examen.matiere', 'examen.classe', 'examen.enseignant']);

        try {
            // Générer le PDF
            $pdf = Pdf::loadView('etudiant.pdf.certificat', [
                'etudiant' => $etudiant,
                'session' => $session,
                'date_generation' => now(),
            ]);

            $pdf->setPaper('a4', 'landscape');

            $filename = 'certificat-' . Str::slug($session->examen->titre) . '-' . $etudiant->matricule . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération certificat PDF', [
                'error' => $e->getMessage(),
                'session_id' => $session->id,
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération du certificat : ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le bulletin détaillé
     */
    public function bulletin()
    {
        $user = auth()->user();
        $etudiant = Etudiant::where('utilisateur_id', $user->id)->first();

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Profil étudiant non trouvé.');
        }

        // Récupérer toutes les sessions corrigées
        $sessions = SessionExamen::where('etudiant_id', $etudiant->id)
            ->where('statut', 'corrige')
            ->whereNotNull('note_obtenue')
            ->with(['examen.matiere', 'examen.classe'])
            ->orderBy('date_soumission', 'desc')
            ->get();

        // Statistiques globales
        $stats = $this->calculerStatistiques($sessions);

        // Progression par matière
        $progressionMatieres = $this->calculerProgressionMatieres($sessions);

        // Évolution des notes
        $evolutionNotes = $this->calculerEvolutionNotes($sessions);

        try {
            // Générer le PDF
            $pdf = Pdf::loadView('etudiant.pdf.bulletin', [
                'etudiant' => $etudiant,
                'sessions' => $sessions,
                'stats' => $stats,
                'progressionMatieres' => $progressionMatieres,
                'evolutionNotes' => $evolutionNotes,
                'date_generation' => now(),
            ]);

            $pdf->setPaper('a4', 'portrait');

            return $pdf->download('bulletin-' . $etudiant->matricule . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération bulletin PDF', [
                'error' => $e->getMessage(),
                'etudiant_id' => $etudiant->id,
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération du bulletin : ' . $e->getMessage());
        }
    }

    /**
     * ✅ CORRIGÉ : Télécharger la correction détaillée d'un examen
     */
    public function correction(SessionExamen $session)
    {
        $user = auth()->user();
        $etudiant = Etudiant::where('utilisateur_id', $user->id)->first();

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Profil étudiant non trouvé.');
        }

        // ✅ VÉRIFICATION CORRIGÉE avec ==
        if ($session->etudiant_id != $etudiant->id) {
            \Log::warning('Tentative accès correction non autorisée', [
                'user_id' => $user->id,
                'etudiant_id' => $etudiant->id,
                'session_etudiant_id' => $session->etudiant_id,
                'session_id' => $session->id,
            ]);
            
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette correction.');
        }

        // Vérifier que la session est corrigée
        if ($session->statut !== 'corrige') {
            return redirect()->back()->with('error', 'La correction n\'est pas encore disponible.');
        }

        try {
            // ✅ CHARGER TOUTES LES RELATIONS NÉCESSAIRES
            $session->load([
                'examen' => function($query) {
                    $query->with(['matiere', 'classe', 'enseignant']);
                },
                'reponses' => function($query) {
                    $query->with(['question']);
                },
            ]);

            // Vérifier qu'il y a des réponses
            if ($session->reponses->isEmpty()) {
                \Log::warning('Aucune réponse trouvée pour la session', [
                    'session_id' => $session->id,
                ]);
            }

            // Générer le PDF
            $pdf = Pdf::loadView('etudiant.pdf.correction', [
                'etudiant' => $etudiant,
                'session' => $session,
                'date_generation' => now(),
            ]);

            $pdf->setPaper('a4', 'portrait');

            $filename = 'correction-' . Str::slug($session->examen->titre) . '-' . $etudiant->matricule . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération correction PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_id' => $session->id,
                'etudiant_id' => $etudiant->id,
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la génération de la correction : ' . $e->getMessage());
        }
    }

    /**
     * Calculer les statistiques globales
     */
    private function calculerStatistiques($sessions)
    {
        if ($sessions->isEmpty()) {
            return [
                'moyenne_generale' => 0,
                'note_moyenne' => 0,
                'nb_examens' => 0,
                'nb_reussis' => 0,
                'taux_reussite' => 0,
                'meilleure_note' => 0,
                'pire_note' => 0,
            ];
        }

        $reussis = $sessions->filter(function ($session) {
            $seuil = $session->examen->seuil_reussite ?? 50;
            return $session->pourcentage >= $seuil;
        })->count();

        return [
            'moyenne_generale' => round($sessions->avg('pourcentage'), 2),
            'note_moyenne' => round($sessions->avg('note_obtenue'), 2),
            'nb_examens' => $sessions->count(),
            'nb_reussis' => $reussis,
            'taux_reussite' => $sessions->count() > 0 ? round(($reussis / $sessions->count()) * 100, 2) : 0,
            'meilleure_note' => round($sessions->max('pourcentage'), 2),
            'pire_note' => round($sessions->min('pourcentage'), 2),
        ];
    }

    /**
     * Calculer la progression par matière
     */
    private function calculerProgressionMatieres($sessions)
    {
        return $sessions->groupBy('examen.matiere.nom')
            ->map(function ($groupeSessions, $matiere) {
                $moyenne = $groupeSessions->avg('pourcentage');
                $reussis = $groupeSessions->filter(function ($session) {
                    $seuil = $session->examen->seuil_reussite ?? 50;
                    return $session->pourcentage >= $seuil;
                })->count();

                return [
                    'matiere' => $matiere,
                    'moyenne' => round($moyenne, 2),
                    'nb_examens' => $groupeSessions->count(),
                    'nb_reussis' => $reussis,
                    'taux_reussite' => round(($reussis / $groupeSessions->count()) * 100, 2),
                ];
            })
            ->sortByDesc('moyenne');
    }

    /**
     * Calculer l'évolution des notes
     */
    private function calculerEvolutionNotes($sessions)
    {
        return $sessions->sortBy('date_soumission')
            ->take(10)
            ->map(function ($session) {
                return [
                    'date' => $session->date_soumission->format('d/m/Y'),
                    'examen' => $session->examen->titre,
                    'note' => round($session->pourcentage, 2),
                ];
            });
    }
}