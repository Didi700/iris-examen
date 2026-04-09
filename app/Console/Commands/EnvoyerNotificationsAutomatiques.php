<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Examen;
use App\Models\SessionExamen;
use App\Models\Notification;
use App\Models\Etudiant;
use Carbon\Carbon;

class EnvoyerNotificationsAutomatiques extends Command
{
    protected $signature = 'notifications:envoyer';
    protected $description = 'Envoyer les notifications automatiques aux étudiants';

    public function handle()
    {
        $this->info('🔔 Démarrage de l\'envoi des notifications automatiques...');
        
        $count = 0;
        
        try {
            // 1. Notifications pour examens dans 24h
            $count += $this->notifierExamensDans24h();
            
            // 2. Notifications pour examens qui expirent bientôt (2h)
            $count += $this->notifierExamensExpirentBientot();
            
            // 3. Notifications pour résultats disponibles
            $count += $this->notifierResultatsDisponibles();
            
            $this->info("✅ {$count} notification(s) envoyée(s) avec succès !");
        } catch (\Exception $e) {
            $this->error("❌ Erreur globale: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    /**
     * Notifier les étudiants des examens qui commencent dans 24h
     */
    private function notifierExamensDans24h()
    {
        $count = 0;
        
        try {
            $this->line('📅 Recherche des examens dans 24h...');
            
            // Examens qui commencent entre maintenant+23h et maintenant+25h
            $examens = Examen::where('statut', 'publie')
                ->whereBetween('date_debut', [
                    now()->addHours(23),
                    now()->addHours(25)
                ])
                ->with(['matiere'])
                ->get();

            $this->line("  → {$examens->count()} examen(s) trouvé(s)");

            foreach ($examens as $examen) {
                if (!$examen->classe_id) {
                    $this->warn("  ⚠️ Examen '{$examen->titre}' sans classe_id");
                    continue;
                }

                // Récupérer tous les étudiants de cette classe
                $etudiants = Etudiant::where('classe_id', $examen->classe_id)
                    ->where('statut', 'actif')
                    ->get();

                $this->line("    → {$etudiants->count()} étudiant(s) dans la classe");

                foreach ($etudiants as $etudiant) {
                    if (!$etudiant->utilisateur_id) {
                        $this->warn("    ⚠️ Etudiant ID {$etudiant->id} sans utilisateur_id");
                        continue;
                    }

                    // Vérifier si la notification n'a pas déjà été envoyée
                    $existe = Notification::where('utilisateur_id', $etudiant->utilisateur_id)
                        ->where('data->examen_id', $examen->id)
                        ->where('data->type', 'examen_dans_24h')
                        ->where('created_at', '>=', now()->subHours(24))
                        ->exists();

                    if ($existe) {
                        continue;
                    }

                    Notification::create([
                        'utilisateur_id' => $etudiant->utilisateur_id,
                        'type' => 'info',
                        'data' => [
                            'type' => 'examen_dans_24h',
                            'examen_id' => $examen->id,
                            'titre' => '⏰ Examen dans 24h !',
                            'message' => "L'examen \"{$examen->titre}\" commence demain à {$examen->date_debut->format('H:i')}. Préparez-vous !",
                            'matiere' => $examen->matiere->nom ?? 'N/A',
                            'date_debut' => $examen->date_debut->format('d/m/Y à H:i'),
                            'duree' => $examen->duree,
                            'url' => route('etudiant.examens.show', $examen->id),
                            'icon' => '⏰',
                            'color' => 'orange',
                        ],
                    ]);
                    
                    $count++;
                }
            }

            if ($count > 0) {
                $this->line("  ✅ {$count} notification(s) envoyée(s) pour examens dans 24h");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erreur examens 24h: " . $e->getMessage());
        }

        return $count;
    }

    /**
     * Notifier les étudiants des examens qui expirent dans 2h
     */
    private function notifierExamensExpirentBientot()
    {
        $count = 0;
        
        try {
            $this->line('⏰ Recherche des examens qui expirent bientôt...');
            
            // Examens qui se terminent entre maintenant+1h30 et maintenant+2h30
            $examens = Examen::where('statut', 'publie')
                ->where('date_debut', '<=', now())
                ->whereBetween('date_fin', [
                    now()->addMinutes(90),
                    now()->addMinutes(150)
                ])
                ->with(['matiere'])
                ->get();

            $this->line("  → {$examens->count()} examen(s) trouvé(s)");

            foreach ($examens as $examen) {
                if (!$examen->classe_id) continue;

                // Récupérer tous les étudiants de cette classe
                $etudiants = Etudiant::where('classe_id', $examen->classe_id)
                    ->where('statut', 'actif')
                    ->get();

                foreach ($etudiants as $etudiant) {
                    if (!$etudiant->utilisateur_id) continue;

                    // Vérifier si l'étudiant a déjà soumis l'examen
                    $session = SessionExamen::where('examen_id', $examen->id)
                        ->where('etudiant_id', $etudiant->id)
                        ->whereIn('statut', ['soumis', 'corrige'])
                        ->first();

                    if ($session) continue; // Déjà soumis

                    // Vérifier si la notification n'a pas déjà été envoyée
                    $existe = Notification::where('utilisateur_id', $etudiant->utilisateur_id)
                        ->where('data->examen_id', $examen->id)
                        ->where('data->type', 'examen_expire_bientot')
                        ->where('created_at', '>=', now()->subHours(2))
                        ->exists();

                    if ($existe) continue;

                    $heuresRestantes = round(now()->diffInMinutes($examen->date_fin) / 60, 1);
                    
                    Notification::create([
                        'utilisateur_id' => $etudiant->utilisateur_id,
                        'type' => 'warning',
                        'data' => [
                            'type' => 'examen_expire_bientot',
                            'examen_id' => $examen->id,
                            'titre' => '⚠️ Examen expire bientôt !',
                            'message' => "L'examen \"{$examen->titre}\" se termine dans {$heuresRestantes}h. Dépêchez-vous !",
                            'matiere' => $examen->matiere->nom ?? 'N/A',
                            'date_fin' => $examen->date_fin->format('d/m/Y à H:i'),
                            'heures_restantes' => $heuresRestantes,
                            'url' => route('etudiant.examens.show', $examen->id),
                            'icon' => '⚠️',
                            'color' => 'red',
                        ],
                    ]);
                    
                    $count++;
                }
            }

            if ($count > 0) {
                $this->line("  ✅ {$count} notification(s) envoyée(s) pour examens expirant bientôt");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erreur examens expiration: " . $e->getMessage());
        }

        return $count;
    }

    /**
     * Notifier les étudiants des résultats disponibles
     */
    private function notifierResultatsDisponibles()
    {
        $count = 0;
        
        try {
            $this->line('📊 Recherche des résultats disponibles...');
            
            // Sessions qui viennent d'être corrigées (dans les 2 dernières heures)
            $sessions = SessionExamen::where('statut', 'corrige')
                ->whereNotNull('note_obtenue')
                ->where('updated_at', '>=', now()->subHours(2))
                ->with(['etudiant', 'examen.matiere'])
                ->get();

            $this->line("  → {$sessions->count()} session(s) trouvée(s)");

            foreach ($sessions as $session) {
                if (!$session->etudiant || !$session->etudiant->utilisateur_id) continue;

                // Vérifier si la notification n'a pas déjà été envoyée
                $existe = Notification::where('utilisateur_id', $session->etudiant->utilisateur_id)
                    ->where('data->session_id', $session->id)
                    ->where('data->type', 'resultat_disponible')
                    ->exists();

                if ($existe) continue;

                $reussi = $session->pourcentage >= ($session->examen->seuil_reussite ?? 50);
                
                Notification::create([
                    'utilisateur_id' => $session->etudiant->utilisateur_id,
                    'type' => $reussi ? 'success' : 'info',
                    'data' => [
                        'type' => 'resultat_disponible',
                        'session_id' => $session->id,
                        'examen_id' => $session->examen->id,
                        'titre' => $reussi ? '✅ Résultat disponible - Réussi !' : '📊 Résultat disponible',
                        'message' => "Votre résultat pour l'examen \"{$session->examen->titre}\" est disponible.",
                        'matiere' => $session->examen->matiere->nom ?? 'N/A',
                        'note_obtenue' => $session->note_obtenue,
                        'note_maximale' => $session->note_maximale ?? $session->examen->note_totale,
                        'pourcentage' => round($session->pourcentage, 2),
                        'reussi' => $reussi,
                        'url' => route('etudiant.resultats.show', $session->id),
                        'icon' => $reussi ? '🎉' : '📊',
                        'color' => $reussi ? 'green' : 'blue',
                    ],
                ]);
                
                $count++;
            }

            if ($count > 0) {
                $this->line("  ✅ {$count} notification(s) envoyée(s) pour résultats");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erreur résultats: " . $e->getMessage());
        }

        return $count;
    }
}