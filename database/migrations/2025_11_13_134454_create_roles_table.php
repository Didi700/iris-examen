<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique(); // super_admin, admin, enseignant, etudiant
            $table->string('description')->nullable();
            $table->integer('niveau_hierarchie')->default(0); // 0=étudiant, 1=enseignant, 2=admin, 3=super_admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};