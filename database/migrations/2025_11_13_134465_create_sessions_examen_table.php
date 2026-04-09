<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions_examen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->integer('numero_tentative')->default(1);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->dateTime('date_soumission')->nullable();
            $table->decimal('note_obtenue', 5, 2)->nullable();
            $table->decimal('pourcentage', 5, 2)->nullable(); // Pourcentage de réussite
            $table->integer('temps_passe_secondes')->default(0); // Temps réel passé
            $table->integer('changements_onglet')->default(0); // Détection anti-triche
            $table->integer('questions_repondues')->default(0); // Nombre de questions répondues
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable(); // Navigateur utilisé
            $table->enum('statut', ['en_cours', 'soumis', 'corrige', 'abandonne', 'temps_ecoule'])->default('en_cours');
            $table->timestamps();

            // Un étudiant ne peut avoir qu'une tentative en cours par examen
            $table->unique(['examen_id', 'etudiant_id', 'numero_tentative']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions_examen');
    }
};