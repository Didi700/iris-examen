<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->integer('tentatives_triche')->default(0)->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->dropColumn('tentatives_triche');
        });
    }
};