<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            // Ajouter la colonne points_par_defaut si elle n'existe pas
            if (!Schema::hasColumn('banque_questions', 'points_par_defaut')) {
                $table->integer('points_par_defaut')->default(1)->after('enonce');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            $table->dropColumn('points_par_defaut');
        });
    }
};