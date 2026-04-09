<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            // Ajouter la colonne statut_correction
            $table->enum('statut_correction', ['en_attente', 'corrige', 'publie'])
                  ->default('en_attente')
                  ->after('statut')
                  ->comment('Statut de correction de la session');
            
            // Index pour améliorer les performances des requêtes
            $table->index('statut_correction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->dropIndex(['statut_correction']);
            $table->dropColumn('statut_correction');
        });
    }
};