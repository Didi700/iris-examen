<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->enum('regime', ['initial', 'alternance', 'formation_continue'])
                  ->default('initial')
                  ->after('statut');
            $table->string('entreprise')->nullable()->after('regime');
        });
    }

    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropColumn(['regime', 'entreprise']);
        });
    }
};