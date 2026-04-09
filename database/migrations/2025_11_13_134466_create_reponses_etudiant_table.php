<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reponses_etudiant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_examen_id')->constrained('sessions_examen')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('banque_questions')->onDelete('cascade');
            $table->text('reponse_donnee')->nullable(); // null si pas encore répondu
            $table->boolean('est_correcte')->nullable(); // Null = pas encore corrigé
            $table->decimal('points_obtenus', 5, 2)->nullable();
            $table->text('commentaire_correcteur')->nullable();
            $table->foreignId('corrige_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamp('corrige_le')->nullable();
            $table->integer('temps_passe_secondes')->default(0); // Temps passé sur cette question
            $table->timestamps();

            // Une seule réponse par question par session
            $table->unique(['session_examen_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses_etudiant');
    }
};