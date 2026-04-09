<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_activite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('action'); // creation_examen, modification_note, connexion, etc.
            $table->string('module'); // examens, utilisateurs, classes, etc.
            $table->text('description');
            $table->json('details')->nullable(); // Données supplémentaires en JSON
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Index pour recherche rapide
            $table->index(['utilisateur_id', 'created_at']);
            $table->index(['module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_activite');
    }
};