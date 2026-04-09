<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Examen;
use App\Models\Rappel;
use Carbon\Carbon;

class GenererRappelsExamens extends Command
{
    protected $signature = 'rappels:generer';
    
    protected $description = 'Générer les rappels pour les examens à venir';

    public function handle()
    {
        $this->info('Génération des rappels pour les examens à venir...');

        // Récupérer les examens publiés à venir (dans les 7 prochains jours)
        $examens = Examen::where('statut', 'publie')
            ->where('date_debut', '>', now())
            ->where('date_debut', '<=', now()->addDays(7))
            ->with(['classe.etudiants'])
            ->get();

        if ($examens->isEmpty()) {
            $this->info('Aucun examen à venir dans les 7 prochains jours.');
            return 0;
        }

        $generes = 0;

        foreach ($examens as $examen) {
            // Vérifier que l'examen a une classe avec des étudiants
            if (!$examen->classe || !$examen->classe->etudiants) {
                continue;
            }

            foreach ($examen->classe->etudiants as $etudiant) {
                // Rappel 24h avant
                $date24h = Carbon::parse($examen->date_debut)->subDay();
                
                // Vérifier que la date de rappel est dans le futur
                if ($date24h > now()) {
                    $rappel24h = Rappel::firstOrCreate([
                        'etudiant_id' => $etudiant->id,
                        'examen_id' => $examen->id,
                        'type' => '24h',
                    ], [
                        'date_rappel' => $date24h,
                        'envoye' => false,
                    ]);

                    if ($rappel24h->wasRecentlyCreated) {
                        $generes++;
                    }
                }

                // Rappel 1h avant
                $date1h = Carbon::parse($examen->date_debut)->subHour();
                
                // Vérifier que la date de rappel est dans le futur
                if ($date1h > now()) {
                    $rappel1h = Rappel::firstOrCreate([
                        'etudiant_id' => $etudiant->id,
                        'examen_id' => $examen->id,
                        'type' => '1h',
                    ], [
                        'date_rappel' => $date1h,
                        'envoye' => false,
                    ]);

                    if ($rappel1h->wasRecentlyCreated) {
                        $generes++;
                    }
                }
            }
        }

        $this->info($generes . ' rappel(s) généré(s) avec succès !');
        return 0;
    }
}