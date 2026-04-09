<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banque_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('type_question_id')->constrained('types_question')->onDelete('cascade');
            $table->text('question');
            $table->json('options')->nullable(); // Pour QCM: ["Option A", "Option B", "Option C", "Option D"]
            $table->text('reponse_correcte'); // Réponse attendue
            $table->decimal('points', 5, 2)->default(1.00); // Points attribués
            $table->text('explication')->nullable(); // Explication de la réponse
            $table->enum('difficulte', ['facile', 'moyen', 'difficile'])->default('moyen');
            $table->enum('statut', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banque_questions');
    }
};