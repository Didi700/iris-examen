<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->enum('type', ['24h', '1h', 'personnalise'])->default('24h');
            $table->timestamp('date_rappel');
            $table->boolean('envoye')->default(false);
            $table->timestamp('date_envoi')->nullable();
            $table->timestamps();

            $table->index(['etudiant_id', 'examen_id', 'envoye']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rappels');
    }
};