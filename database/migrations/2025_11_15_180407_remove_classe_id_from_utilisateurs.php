<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            // Vérifier si la contrainte existe avant de la supprimer
            if (Schema::hasColumn('utilisateurs', 'classe_id')) {
                $table->dropForeign(['classe_id']);
                $table->dropColumn('classe_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->foreignId('classe_id')->nullable()->constrained('classes');
        });
    }
};