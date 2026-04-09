<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SessionExamen;
use App\Models\ReponseEtudiant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamenApiController extends Controller
{
    /**
     * Sauvegarde automatique des réponses
     */
    public function autosave(Request $request, SessionExamen $session)
    {
        try {
            // Vérifier que la session appartient à l'utilisateur
            if ($session->etudiant_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé'
                ], 403);
            }

            // Vérifier que la session est en cours
            if ($session->statut !== 'en_cours') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette session n\'est pas en cours'
                ], 400);
            }

            // Vérifier que le temps n'est pas écoulé
            if ($session->date_fin && $session->date_fin < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le temps est écoulé',
                    'time_expired' => true
                ], 400);
            }

            $validated = $request->validate([
                'reponses' => 'required|array',
                'reponses.*' => 'nullable',
            ]);

            DB::beginTransaction();

            $savedCount = 0;

            foreach ($validated['reponses'] as $questionId => $reponse) {
                // Trouver ou créer la réponse
                $reponseEtudiant = ReponseEtudiant::firstOrNew([
                    'session_id' => $session->id,
                    'question_id' => $questionId,
                ]);

                // Sauvegarder selon le type de réponse
                if (is_array($reponse)) {
                    // Choix multiple
                    $reponseEtudiant->reponses_multiples = json_encode($reponse);
                    $reponseEtudiant->reponse_id = null;
                    $reponseEtudiant->reponse_texte = null;
                } elseif (is_numeric($reponse)) {
                    // Choix unique / Vrai-Faux
                    $reponseEtudiant->reponse_id = $reponse;
                    $reponseEtudiant->reponses_multiples = null;
                    $reponseEtudiant->reponse_texte = null;
                } else {
                    // Texte libre
                    $reponseEtudiant->reponse_texte = $reponse;
                    $reponseEtudiant->reponse_id = null;
                    $reponseEtudiant->reponses_multiples = null;
                }

                $reponseEtudiant->save();
                $savedCount++;
            }

            // Mettre à jour le timestamp de dernière modification
            $session->touch();

            DB::commit();

            Log::info('Auto-save réussi', [
                'session_id' => $session->id,
                'user_id' => auth()->id(),
                'responses_saved' => $savedCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sauvegarde réussie',
                'saved_count' => $savedCount,
                'timestamp' => now()->toIso8601String()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur auto-save', [
                'session_id' => $session->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}