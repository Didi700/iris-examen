<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable(); // Instructions pour les étudiants
            $table->foreignId('enseignant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->integer('duree_minutes'); // Durée en minutes
            $table->decimal('note_totale', 5, 2); // Note maximale
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->boolean('melanger_questions')->default(false);
            $table->boolean('melanger_reponses')->default(false);
            $table->boolean('afficher_resultats_immediatement')->default(false);
            $table->integer('nombre_tentatives_max')->default(1);
            $table->boolean('autoriser_retour_arriere')->default(true); // Permettre de revenir aux questions précédentes
            $table->integer('seuil_reussite')->default(50); // Pourcentage minimum pour réussir
            $table->enum('statut', ['brouillon', 'publie', 'en_cours', 'termine', 'archive'])->default('brouillon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};