<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('copies_etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_examen_id')->constrained('sessions_examen')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            
            // Fichier de la copie rendue
            $table->string('fichier_copie_path');
            $table->string('fichier_copie_nom_original');
            $table->bigInteger('fichier_copie_taille'); // en octets
            
            // Correction
            $table->decimal('note_obtenue', 5, 2)->nullable();
            $table->text('commentaire_correcteur')->nullable();
            $table->timestamp('date_soumission');
            $table->timestamp('date_correction')->nullable();
            $table->foreignId('correcteur_id')->nullable()->constrained('utilisateurs')->onDelete('set null');
            
            $table->enum('statut', ['soumis', 'en_correction', 'corrige'])->default('soumis');
            
            $table->timestamps();
            
            // Index
            $table->index(['examen_id', 'etudiant_id']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('copies_etudiants');
    }
};