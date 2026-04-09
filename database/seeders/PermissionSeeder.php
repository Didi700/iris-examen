<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Gestion des utilisateurs
            ['nom' => 'creer_super_admin', 'module' => 'utilisateurs', 'description' => 'Créer un super administrateur'],
            ['nom' => 'creer_admin', 'module' => 'utilisateurs', 'description' => 'Créer un administrateur'],
            ['nom' => 'creer_enseignant', 'module' => 'utilisateurs', 'description' => 'Créer un enseignant'],
            ['nom' => 'creer_etudiant', 'module' => 'utilisateurs', 'description' => 'Créer un étudiant'],
            ['nom' => 'modifier_utilisateur', 'module' => 'utilisateurs', 'description' => 'Modifier un utilisateur'],
            ['nom' => 'supprimer_utilisateur', 'module' => 'utilisateurs', 'description' => 'Supprimer un utilisateur'],
            ['nom' => 'voir_tous_utilisateurs', 'module' => 'utilisateurs', 'description' => 'Voir tous les utilisateurs'],
            ['nom' => 'activer_desactiver_utilisateur', 'module' => 'utilisateurs', 'description' => 'Activer/Désactiver un utilisateur'],
            
            // Gestion des classes
            ['nom' => 'creer_classe', 'module' => 'classes', 'description' => 'Créer une classe'],
            ['nom' => 'modifier_classe', 'module' => 'classes', 'description' => 'Modifier une classe'],
            ['nom' => 'supprimer_classe', 'module' => 'classes', 'description' => 'Supprimer une classe'],
            ['nom' => 'affecter_etudiants', 'module' => 'classes', 'description' => 'Affecter des étudiants aux classes'],
            ['nom' => 'affecter_enseignants', 'module' => 'classes', 'description' => 'Affecter des enseignants aux classes'],
            ['nom' => 'voir_toutes_classes', 'module' => 'classes', 'description' => 'Voir toutes les classes'],
            
            // Gestion des matières
            ['nom' => 'creer_matiere', 'module' => 'matieres', 'description' => 'Créer une matière'],
            ['nom' => 'modifier_matiere', 'module' => 'matieres', 'description' => 'Modifier une matière'],
            ['nom' => 'supprimer_matiere', 'module' => 'matieres', 'description' => 'Supprimer une matière'],
            
            // Gestion des examens
            ['nom' => 'creer_examen', 'module' => 'examens', 'description' => 'Créer un examen'],
            ['nom' => 'modifier_examen', 'module' => 'examens', 'description' => 'Modifier un examen'],
            ['nom' => 'supprimer_examen', 'module' => 'examens', 'description' => 'Supprimer un examen'],
            ['nom' => 'publier_examen', 'module' => 'examens', 'description' => 'Publier un examen'],
            ['nom' => 'voir_tous_examens', 'module' => 'examens', 'description' => 'Voir tous les examens'],
            ['nom' => 'corriger_examen', 'module' => 'examens', 'description' => 'Corriger un examen'],
            
            // Gestion des questions
            ['nom' => 'creer_question', 'module' => 'questions', 'description' => 'Créer une question'],
            ['nom' => 'modifier_question', 'module' => 'questions', 'description' => 'Modifier une question'],
            ['nom' => 'supprimer_question', 'module' => 'questions', 'description' => 'Supprimer une question'],
            ['nom' => 'voir_banque_questions', 'module' => 'questions', 'description' => 'Voir la banque de questions'],
            
            // Statistiques et rapports
            ['nom' => 'voir_statistiques_globales', 'module' => 'statistiques', 'description' => 'Voir les statistiques globales'],
            ['nom' => 'voir_statistiques_classe', 'module' => 'statistiques', 'description' => 'Voir les statistiques d\'une classe'],
            ['nom' => 'exporter_resultats', 'module' => 'statistiques', 'description' => 'Exporter les résultats'],
            ['nom' => 'consulter_logs', 'module' => 'statistiques', 'description' => 'Consulter les logs d\'activité'],
            
            // Paramètres système
            ['nom' => 'modifier_parametres', 'module' => 'systeme', 'description' => 'Modifier les paramètres du système'],
            ['nom' => 'sauvegarder_base', 'module' => 'systeme', 'description' => 'Sauvegarder la base de données'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'nom' => $permission['nom'],
                'module' => $permission['module'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attribuer toutes les permissions au super_admin
        $superAdminRole = DB::table('roles')->where('nom', 'super_admin')->first();
        $allPermissions = DB::table('permissions')->get();

        foreach ($allPermissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $superAdminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attribuer les permissions à l'admin (toutes sauf créer super_admin)
        $adminRole = DB::table('roles')->where('nom', 'admin')->first();
        $adminPermissions = DB::table('permissions')
            ->whereNotIn('nom', ['creer_super_admin', 'modifier_parametres', 'sauvegarder_base'])
            ->get();

        foreach ($adminPermissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $adminRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attribuer les permissions à l'enseignant
        $enseignantRole = DB::table('roles')->where('nom', 'enseignant')->first();
        $enseignantPermissions = DB::table('permissions')
            ->whereIn('module', ['examens', 'questions', 'statistiques'])
            ->whereNotIn('nom', ['voir_tous_examens', 'voir_statistiques_globales', 'consulter_logs'])
            ->get();

        foreach ($enseignantPermissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $enseignantRole->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}