<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('critere_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('banque_questions')->onDelete('cascade');
            $table->string('nom'); // Ex: Orthographe, Syntaxe, Contenu, etc.
            $table->text('description')->nullable();
            $table->decimal('points_max', 5, 2); // Points maximum pour ce critère
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // Table pivot pour les évaluations par critère
        Schema::create('reponse_critere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reponse_etudiant_id')->constrained('reponses_etudiant')->onDelete('cascade');
            $table->foreignId('critere_id')->constrained('critere_corrections')->onDelete('cascade');
            $table->decimal('points_obtenus', 5, 2);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reponse_critere');
        Schema::dropIfExists('critere_corrections');
    }
};