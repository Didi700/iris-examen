<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique(); // Ex: INFO101, MATH202
            $table->text('description')->nullable();
            $table->integer('coefficient')->default(1);
            $table->enum('statut', ['active', 'inactive'])->default('active');
            $table->foreignId('cree_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};