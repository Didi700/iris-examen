<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examen_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('banque_questions')->onDelete('cascade');
            $table->integer('ordre')->default(0); // Ordre d'affichage
            $table->decimal('points', 5, 2); // Points pour cette question dans cet examen
            $table->boolean('obligatoire')->default(true); // Question obligatoire ou optionnelle
            $table->timestamps();

            // Une question ne peut être ajoutée qu'une seule fois dans un examen
            $table->unique(['examen_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_question');
    }
};