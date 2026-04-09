<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Examen;
use App\Notifications\RappelExamen;
use Carbon\Carbon;

class EnvoyerRappelsExamens extends Command
{
    protected $signature = 'examens:rappels';
    protected $description = 'Envoyer des rappels pour les examens à venir';

    public function handle()
    {
        $maintenant = Carbon::now();
        
        // Examens dans 24 heures
        $examens24h = Examen::where('statut', 'publie')
            ->whereBetween('date_debut', [
                $maintenant->copy()->addHours(23),
                $maintenant->copy()->addHours(25)
            ])
            ->get();

        foreach ($examens24h as $examen) {
            $etudiants = $examen->classe->etudiants;
            
            foreach ($etudiants as $etudiant) {
                $etudiant->notify(new RappelExamen($examen, 24));
            }
            
            $this->info("Rappels envoyés pour : {$examen->titre}");
        }

        // Examens dans 1 heure
        $examens1h = Examen::where('statut', 'publie')
            ->whereBetween('date_debut', [
                $maintenant->copy()->addMinutes(55),
                $maintenant->copy()->addMinutes(65)
            ])
            ->get();

        foreach ($examens1h as $examen) {
            $etudiants = $examen->classe->etudiants;
            
            foreach ($etudiants as $etudiant) {
                $etudiant->notify(new RappelExamen($examen, 1));
            }
            
            $this->info("Rappels urgents envoyés pour : {$examen->titre}");
        }

        $this->info('Tous les rappels ont été envoyés !');
        return 0;
    }
}