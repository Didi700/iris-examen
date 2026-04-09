<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('createur_id')->constrained('utilisateurs')->onDelete('cascade');
            
            // Type de question
            $table->enum('type', ['qcm_simple', 'qcm_multiple', 'vrai_faux', 'texte_libre'])->default('qcm_simple');
            
            // Contenu de la question
            $table->text('enonce');
            $table->text('explication')->nullable(); // Explication de la réponse
            
            // Niveau et points
            $table->enum('difficulte', ['facile', 'moyen', 'difficile'])->default('moyen');
            $table->decimal('points', 5, 2)->default(1.00);
            
            // Métadonnées
            $table->string('tags')->nullable(); // Tags séparés par des virgules
            $table->boolean('est_active')->default(true);
            $table->integer('nb_utilisations')->default(0); // Nombre de fois utilisée dans des examens
            
            // Statistiques
            $table->decimal('taux_reussite', 5, 2)->nullable(); // Pourcentage de réussite
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('matiere_id');
            $table->index('type');
            $table->index('difficulte');
            $table->index('est_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};