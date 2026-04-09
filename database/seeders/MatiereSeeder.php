<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminId = DB::table('utilisateurs')->where('email', 'superadmin@iris.fr')->first()->id;

        $matieres = [
            ['nom' => 'Programmation Orientée Objet', 'code' => 'INFO101', 'coefficient' => 3],
            ['nom' => 'Base de Données', 'code' => 'INFO102', 'coefficient' => 3],
            ['nom' => 'Développement Web', 'code' => 'INFO103', 'coefficient' => 4],
            ['nom' => 'Algorithmique', 'code' => 'INFO104', 'coefficient' => 3],
            ['nom' => 'Réseaux Informatiques', 'code' => 'INFO105', 'coefficient' => 2],
            ['nom' => 'Mathématiques Appliquées', 'code' => 'MATH201', 'coefficient' => 2],
            ['nom' => 'Anglais Technique', 'code' => 'LANG301', 'coefficient' => 2],
            ['nom' => 'Gestion de Projet', 'code' => 'MGMT401', 'coefficient' => 2],
        ];

        foreach ($matieres as $matiere) {
            DB::table('matieres')->insert([
                'nom' => $matiere['nom'],
                'code' => $matiere['code'],
                'description' => 'Matière du programme IRIS',
                'coefficient' => $matiere['coefficient'],
                'statut' => 'active',
                'cree_par' => $superAdminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}