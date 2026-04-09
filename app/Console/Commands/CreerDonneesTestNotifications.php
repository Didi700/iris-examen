<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Examen;
use App\Models\SessionExamen;

class CreerDonneesTestNotifications extends Command
{
    protected $signature = 'test:notifications-data';
    protected $description = 'Créer des données de test pour les notifications';

    public function handle()
    {
        $this->info('📝 Création des données de test...');
        
        // 1. Créer un examen qui commence dans 24h
        $examen24h = Examen::where('statut', 'publie')->first();
        if ($examen24h) {
            $examen24h->date_debut = now()->addHours(24);
            $examen24h->date_fin = now()->addHours(26);
            $examen24h->statut = 'publie';
            $examen24h->save();
            $this->line("✅ Examen '{$examen24h->titre}' commence dans 24h");
        } else {
            $this->warn("⚠️ Aucun examen trouvé pour le test 24h");
        }
        
        // 2. Créer un examen qui expire dans 2h
        $examen2h = Examen::where('statut', 'publie')->skip(1)->first();
        if ($examen2h) {
            $examen2h->date_debut = now()->subHours(2);
            $examen2h->date_fin = now()->addHours(2);
            $examen2h->statut = 'publie';
            $examen2h->save();
            $this->line("✅ Examen '{$examen2h->titre}' expire dans 2h");
        } else {
            $this->warn("⚠️ Aucun examen trouvé pour le test 2h");
        }
        
        // 3. Créer une session corrigée récemment
        $sessionTest = SessionExamen::where('statut', 'soumis')->first();
        if ($sessionTest) {
            $sessionTest->statut = 'corrige';
            $sessionTest->note_obtenue = 15;
            $sessionTest->note_maximale = 20;
            $sessionTest->pourcentage = 75;
            $sessionTest->updated_at = now();
            $sessionTest->save();
            $this->line("✅ Session corrigée créée");
        } else {
            $this->warn("⚠️ Aucune session trouvée pour le test");
        }
        
        $this->info('✅ Données de test créées ! Maintenant exécute : php artisan notifications:envoyer');
        
        return 0;
    }
}