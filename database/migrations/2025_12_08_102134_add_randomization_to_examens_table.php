<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            // Utiliser 'duree_minutes' au lieu de 'duree'
            $table->boolean('ordre_questions_aleatoire')->default(false)->after('duree_minutes');
            $table->boolean('ordre_reponses_aleatoire')->default(false)->after('ordre_questions_aleatoire');
        });
    }

    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropColumn(['ordre_questions_aleatoire', 'ordre_reponses_aleatoire']);
        });
    }
};