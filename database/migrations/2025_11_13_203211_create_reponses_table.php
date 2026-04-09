<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            
            $table->text('texte'); // Texte de la réponse
            $table->boolean('est_correcte')->default(false); // Si c'est la bonne réponse
            $table->integer('ordre')->default(0); // Ordre d'affichage
            
            $table->timestamps();
            
            // Index
            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses');
    }
};