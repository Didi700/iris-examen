<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'super_admin',
                'description' => 'Super Administrateur - Accès complet au système',
                'niveau_hierarchie' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'admin',
                'description' => 'Administrateur - Gestion de l\'établissement',
                'niveau_hierarchie' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'enseignant',
                'description' => 'Enseignant - Création et gestion des examens',
                'niveau_hierarchie' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'etudiant',
                'description' => 'Étudiant - Passage des examens',
                'niveau_hierarchie' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}