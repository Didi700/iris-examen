<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropColumn('matricule'); // Supprime la colonne problématique
        });
    }

    public function down(): void
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->string('matricule')->unique(); // Remet la colonne si rollback
        });
    }
};