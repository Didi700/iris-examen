<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class NettoyerNotifications extends Command
{
    protected $signature = 'notifications:nettoyer {--days=30 : Nombre de jours à conserver}';
    protected $description = 'Supprimer les anciennes notifications';

    public function handle()
    {
        $days = $this->option('days');
        
        $count = Notification::where('created_at', '<', now()->subDays($days))
            ->delete();
        
        $this->info("✅ {$count} notification(s) supprimée(s) (plus vieilles que {$days} jours)");
        
        return 0;
    }
}