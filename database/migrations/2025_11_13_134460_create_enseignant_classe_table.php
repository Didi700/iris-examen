<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_classe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('affecte_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();

            // Un enseignant peut enseigner plusieurs matières dans plusieurs classes
            $table->unique(['enseignant_id', 'classe_id', 'matiere_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_classe');
    }
};