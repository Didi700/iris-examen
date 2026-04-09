<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->string('couleur', 7)->default('#10B981')->after('nom');
        });
    }

    public function down()
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropColumn('couleur');
        });
    }
};