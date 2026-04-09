<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $admin1Id = DB::table('utilisateurs')->where('email', 'marie.dupont@iris.fr')->first()->id;

        $classes = [
            [
                'nom' => 'BTS SIO SLAM 1',
                'code' => 'BTS-SIO1-2025',
                'niveau' => 'Bac+2',
                'annee_scolaire' => '2024-2025',
                'effectif_max' => 30,
            ],
            [
                'nom' => 'BTS SIO SLAM 2',
                'code' => 'BTS-SIO2-2025',
                'niveau' => 'Bac+2',
                'annee_scolaire' => '2024-2025',
                'effectif_max' => 30,
            ],
            [
                'nom' => 'Licence Pro Dev Web',
                'code' => 'LP-DEV-2025',
                'niveau' => 'Bac+3',
                'annee_scolaire' => '2024-2025',
                'effectif_max' => 25,
            ],
        ];

        foreach ($classes as $classe) {
            DB::table('classes')->insert([
                'nom' => $classe['nom'],
                'code' => $classe['code'],
                'niveau' => $classe['niveau'],
                'annee_scolaire' => $classe['annee_scolaire'],
                'description' => 'Classe de formation informatique IRIS',
                'effectif_max' => $classe['effectif_max'],
                'effectif_actuel' => 0,
                'accepte_alternance' => true,
                'accepte_initial' => true,
                'accepte_formation_continue' => false,
                'nb_etudiants_initial' => 0,
                'nb_etudiants_alternance' => 0,
                'nb_etudiants_formation_continue' => 0,
                'date_debut' => '2024-09-02',
                'date_fin' => '2025-06-30',
                'statut' => 'active',
                'cree_par' => $admin1Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Affecter les étudiants aux classes
        $this->affecterEtudiants($admin1Id);
    }

    private function affecterEtudiants($admin1Id): void
    {
        $classe1 = DB::table('classes')->where('code', 'BTS-SIO1-2025')->first();
        
        // 10 étudiants en Initial (ETU-001 à ETU-010)
        for ($i = 1; $i <= 10; $i++) {
            $matricule = 'ETU-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $etudiant = DB::table('utilisateurs')->where('matricule', $matricule)->first();
            
            if ($etudiant) {
                DB::table('classe_etudiant')->insert([
                    'classe_id' => $classe1->id,
                    'etudiant_id' => $etudiant->id,
                    'regime' => 'initial',
                    'date_inscription' => '2024-09-02',
                    'statut' => 'inscrit',
                    'inscrit_par' => $admin1Id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 10 étudiants en Alternance (ETU-011 à ETU-020)
        $entreprises = [
            ['nom' => 'Orange', 'tuteur' => 'Michel DURAND', 'email' => 'm.durand@orange.fr'],
            ['nom' => 'Capgemini', 'tuteur' => 'Sophie LAURENT', 'email' => 's.laurent@capgemini.com'],
            ['nom' => 'Atos', 'tuteur' => 'Pierre BLANC', 'email' => 'p.blanc@atos.net'],
            ['nom' => 'Sopra Steria', 'tuteur' => 'Marie PETIT', 'email' => 'm.petit@soprasteria.com'],
            ['nom' => 'Thales', 'tuteur' => 'Jean THOMAS', 'email' => 'j.thomas@thalesgroup.com'],
            ['nom' => 'IBM', 'tuteur' => 'Anne ROBERT', 'email' => 'a.robert@ibm.com'],
            ['nom' => 'Accenture', 'tuteur' => 'Luc RICHARD', 'email' => 'l.richard@accenture.com'],
            ['nom' => 'Deloitte', 'tuteur' => 'Claire DUBOIS', 'email' => 'c.dubois@deloitte.fr'],
            ['nom' => 'CGI', 'tuteur' => 'Paul MARTIN', 'email' => 'p.martin@cgi.com'],
            ['nom' => 'Altran', 'tuteur' => 'Nathalie BERNARD', 'email' => 'n.bernard@altran.com'],
        ];

        for ($i = 11; $i <= 20; $i++) {
            $matricule = 'ETU-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $etudiant = DB::table('utilisateurs')->where('matricule', $matricule)->first();
            $entreprise = $entreprises[$i - 11];
            
            if ($etudiant) {
                DB::table('classe_etudiant')->insert([
                    'classe_id' => $classe1->id,
                    'etudiant_id' => $etudiant->id,
                    'regime' => 'alternance',
                    'date_inscription' => '2024-09-02',
                    'entreprise' => $entreprise['nom'],
                    'tuteur_entreprise' => $entreprise['tuteur'],
                    'email_tuteur' => $entreprise['email'],
                    'telephone_tuteur' => '+33 1 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'rythme_alternance' => '3 semaines / 1 semaine',
                    'statut' => 'inscrit',
                    'inscrit_par' => $admin1Id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Mettre à jour les statistiques de la classe
        DB::table('classes')->where('id', $classe1->id)->update([
            'effectif_actuel' => 20,
            'nb_etudiants_initial' => 10,
            'nb_etudiants_alternance' => 10,
        ]);
    }
}