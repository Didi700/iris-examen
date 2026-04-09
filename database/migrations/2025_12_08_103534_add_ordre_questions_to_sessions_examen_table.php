<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->json('ordre_questions')->nullable()->after('statut');
            $table->json('ordre_reponses')->nullable()->after('ordre_questions');
        });
    }

    public function down(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->dropColumn(['ordre_questions', 'ordre_reponses']);
        });
    }
};