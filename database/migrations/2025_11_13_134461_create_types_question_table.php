<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_question', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // QCM, Vrai/Faux, Texte libre, etc.
            $table->string('code')->unique(); // qcm, vrai_faux, texte_libre
            $table->text('description')->nullable();
            $table->boolean('correction_automatique')->default(false); // QCM = true, Texte libre = false
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_question');
    }
};