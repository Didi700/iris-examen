<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Ex: BTS SIO 1, Licence Pro Dev 2
            $table->string('code')->unique(); // Ex: BTS-SIO1-2025
            $table->string('niveau'); // Bac+2, Bac+3, Bac+5
            $table->string('annee_scolaire'); // 2024-2025
            $table->text('description')->nullable();
            
            // Effectifs
            $table->integer('effectif_max')->default(30);
            $table->integer('effectif_actuel')->default(0); // Calculé automatiquement
            
            // 🆕 Gestion des régimes acceptés
            $table->boolean('accepte_alternance')->default(true);
            $table->boolean('accepte_initial')->default(true);
            $table->boolean('accepte_formation_continue')->default(false);
            
            // 🆕 Statistiques en temps réel (calculées automatiquement)
            $table->integer('nb_etudiants_initial')->default(0);
            $table->integer('nb_etudiants_alternance')->default(0);
            $table->integer('nb_etudiants_formation_continue')->default(0);
            
            // Planning
            $table->date('date_debut')->nullable(); // Date de début de l'année
            $table->date('date_fin')->nullable(); // Date de fin de l'année
            
            $table->enum('statut', ['active', 'inactive', 'archivee'])->default('active');
            $table->foreignId('cree_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};