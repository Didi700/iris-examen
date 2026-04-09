<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ⚠️ IMPORTANT : Cette migration suppose que classe_id existe encore
        // Si classe_id a déjà été supprimée, on assigne classe_id = NULL
        
        // Vérifier si la colonne classe_id existe
        $colonneExiste = Schema::hasColumn('utilisateurs', 'classe_id');
        
        // Migrer les étudiants
        $etudiants = DB::table('utilisateurs')
            ->where('role_id', 4)
            ->get();

        foreach ($etudiants as $utilisateur) {
            // Si la colonne existe, on récupère la valeur, sinon NULL
            $classeId = $colonneExiste && isset($utilisateur->classe_id) 
                ? $utilisateur->classe_id 
                : null;

            DB::table('etudiants')->insert([
                'utilisateur_id' => $utilisateur->id,
                'classe_id' => $classeId,
                'matricule' => 'ETU' . str_pad($utilisateur->id, 6, '0', STR_PAD_LEFT),
                'date_inscription' => $utilisateur->created_at ?? now(),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrer les enseignants
        $enseignants = DB::table('utilisateurs')
            ->where('role_id', 3)
            ->get();

        foreach ($enseignants as $utilisateur) {
            DB::table('enseignants')->insert([
                'utilisateur_id' => $utilisateur->id,
                'matricule' => 'ENS' . str_pad($utilisateur->id, 6, '0', STR_PAD_LEFT),
                'date_embauche' => $utilisateur->created_at ?? now(),
                'statut' => 'actif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('etudiants')->truncate();
        DB::table('enseignants')->truncate();
    }
};