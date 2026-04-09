<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = DB::table('roles')->where('nom', 'super_admin')->first();
        $adminRole = DB::table('roles')->where('nom', 'admin')->first();
        $enseignantRole = DB::table('roles')->where('nom', 'enseignant')->first();
        $etudiantRole = DB::table('roles')->where('nom', 'etudiant')->first();

        // 1. Créer le Super Admin
        $superAdminId = DB::table('utilisateurs')->insertGetId([
            'nom' => 'SUPER',
            'prenom' => 'Admin',
            'email' => 'superadmin@iris.fr',
            'email_verified_at' => now(),
            'password' => Hash::make('SuperAdmin2025!'),
            'role_id' => $superAdminRole->id,
            'telephone' => '+33 1 23 45 67 89',
            'matricule' => 'SA-001',
            'statut' => 'actif',
            'cree_par' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Créer 2 Administrateurs
        $admin1Id = DB::table('utilisateurs')->insertGetId([
            'nom' => 'DUPONT',
            'prenom' => 'Marie',
            'email' => 'marie.dupont@iris.fr',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin2025!'),
            'role_id' => $adminRole->id,
            'telephone' => '+33 6 12 34 56 78',
            'matricule' => 'AD-001',
            'statut' => 'actif',
            'cree_par' => $superAdminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('utilisateurs')->insert([
            'nom' => 'MARTIN',
            'prenom' => 'Pierre',
            'email' => 'pierre.martin@iris.fr',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin2025!'),
            'role_id' => $adminRole->id,
            'telephone' => '+33 6 98 76 54 32',
            'matricule' => 'AD-002',
            'statut' => 'actif',
            'cree_par' => $superAdminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Créer 5 Enseignants
        $enseignants = [
            ['nom' => 'BERNARD', 'prenom' => 'Sophie', 'email' => 'sophie.bernard@iris.fr', 'matricule' => 'ENS-001'],
            ['nom' => 'DUBOIS', 'prenom' => 'Jean', 'email' => 'jean.dubois@iris.fr', 'matricule' => 'ENS-002'],
            ['nom' => 'LEROY', 'prenom' => 'Catherine', 'email' => 'catherine.leroy@iris.fr', 'matricule' => 'ENS-003'],
            ['nom' => 'MOREAU', 'prenom' => 'François', 'email' => 'francois.moreau@iris.fr', 'matricule' => 'ENS-004'],
            ['nom' => 'SIMON', 'prenom' => 'Nathalie', 'email' => 'nathalie.simon@iris.fr', 'matricule' => 'ENS-005'],
        ];

        foreach ($enseignants as $enseignant) {
            DB::table('utilisateurs')->insert([
                'nom' => $enseignant['nom'],
                'prenom' => $enseignant['prenom'],
                'email' => $enseignant['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('Enseignant2025!'),
                'role_id' => $enseignantRole->id,
                'telephone' => '+33 6 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'matricule' => $enseignant['matricule'],
                'statut' => 'actif',
                'cree_par' => $admin1Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Créer 20 Étudiants (10 Initial + 10 Alternance)
        $etudiants = [
            // Étudiants en Initial
            ['nom' => 'LEFEBVRE', 'prenom' => 'Lucas', 'email' => 'lucas.lefebvre@etudiant.iris.fr', 'matricule' => 'ETU-001'],
            ['nom' => 'ROUX', 'prenom' => 'Emma', 'email' => 'emma.roux@etudiant.iris.fr', 'matricule' => 'ETU-002'],
            ['nom' => 'FOURNIER', 'prenom' => 'Hugo', 'email' => 'hugo.fournier@etudiant.iris.fr', 'matricule' => 'ETU-003'],
            ['nom' => 'GIRARD', 'prenom' => 'Léa', 'email' => 'lea.girard@etudiant.iris.fr', 'matricule' => 'ETU-004'],
            ['nom' => 'BONNET', 'prenom' => 'Tom', 'email' => 'tom.bonnet@etudiant.iris.fr', 'matricule' => 'ETU-005'],
            ['nom' => 'DUPUIS', 'prenom' => 'Chloé', 'email' => 'chloe.dupuis@etudiant.iris.fr', 'matricule' => 'ETU-006'],
            ['nom' => 'LAMBERT', 'prenom' => 'Nathan', 'email' => 'nathan.lambert@etudiant.iris.fr', 'matricule' => 'ETU-007'],
            ['nom' => 'FONTAINE', 'prenom' => 'Sarah', 'email' => 'sarah.fontaine@etudiant.iris.fr', 'matricule' => 'ETU-008'],
            ['nom' => 'ROUSSEAU', 'prenom' => 'Alexandre', 'email' => 'alexandre.rousseau@etudiant.iris.fr', 'matricule' => 'ETU-009'],
            ['nom' => 'VINCENT', 'prenom' => 'Manon', 'email' => 'manon.vincent@etudiant.iris.fr', 'matricule' => 'ETU-010'],
            
            // Étudiants en Alternance
            ['nom' => 'GAUTHIER', 'prenom' => 'Antoine', 'email' => 'antoine.gauthier@etudiant.iris.fr', 'matricule' => 'ETU-011'],
            ['nom' => 'PERRIN', 'prenom' => 'Julie', 'email' => 'julie.perrin@etudiant.iris.fr', 'matricule' => 'ETU-012'],
            ['nom' => 'MOREL', 'prenom' => 'Maxime', 'email' => 'maxime.morel@etudiant.iris.fr', 'matricule' => 'ETU-013'],
            ['nom' => 'GARCIA', 'prenom' => 'Laura', 'email' => 'laura.garcia@etudiant.iris.fr', 'matricule' => 'ETU-014'],
            ['nom' => 'DAVID', 'prenom' => 'Thomas', 'email' => 'thomas.david@etudiant.iris.fr', 'matricule' => 'ETU-015'],
            ['nom' => 'BERTRAND', 'prenom' => 'Clara', 'email' => 'clara.bertrand@etudiant.iris.fr', 'matricule' => 'ETU-016'],
            ['nom' => 'CHEVALIER', 'prenom' => 'Julien', 'email' => 'julien.chevalier@etudiant.iris.fr', 'matricule' => 'ETU-017'],
            ['nom' => 'ROBIN', 'prenom' => 'Camille', 'email' => 'camille.robin@etudiant.iris.fr', 'matricule' => 'ETU-018'],
            ['nom' => 'CLEMENT', 'prenom' => 'Nicolas', 'email' => 'nicolas.clement@etudiant.iris.fr', 'matricule' => 'ETU-019'],
            ['nom' => 'GUILLAUME', 'prenom' => 'Alice', 'email' => 'alice.guillaume@etudiant.iris.fr', 'matricule' => 'ETU-020'],
        ];

        foreach ($etudiants as $etudiant) {
            DB::table('utilisateurs')->insert([
                'nom' => $etudiant['nom'],
                'prenom' => $etudiant['prenom'],
                'email' => $etudiant['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('Etudiant2025!'),
                'role_id' => $etudiantRole->id,
                'telephone' => '+33 6 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'matricule' => $etudiant['matricule'],
                'date_naissance' => now()->subYears(rand(18, 25))->format('Y-m-d'),
                'adresse' => rand(1, 200) . ' rue de ' . ['Paris', 'Lyon', 'Marseille', 'Toulouse'][rand(0, 3)],
                'ville' => ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nantes'][rand(0, 4)],
                'code_postal' => rand(10000, 99999),
                'statut' => 'actif',
                'cree_par' => $admin1Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "\n✅ Super Admin créé : superadmin@iris.fr / SuperAdmin2025!\n";
        echo "✅ Admin créé : marie.dupont@iris.fr / Admin2025!\n";
        echo "✅ 5 Enseignants créés (mot de passe : Enseignant2025!)\n";
        echo "✅ 20 Étudiants créés (mot de passe : Etudiant2025!)\n\n";
    }
}