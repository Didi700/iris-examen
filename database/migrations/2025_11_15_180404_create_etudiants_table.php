<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('classe_id')->nullable()->constrained('classes')->onDelete('restrict');
            
            // Informations académiques
            $table->string('matricule')->unique();
            $table->date('date_inscription')->default(now());
            $table->enum('statut', ['actif', 'suspendu', 'diplome', 'abandonne'])->default('actif');
            
            // Informations personnelles
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->default('Française');
            
            // Contact
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->default('France');
            
            // Contact d'urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_telephone')->nullable();
            $table->string('contact_urgence_relation')->nullable();
            
            // Parents/Tuteurs
            $table->string('nom_pere')->nullable();
            $table->string('profession_pere')->nullable();
            $table->string('telephone_pere')->nullable();
            $table->string('nom_mere')->nullable();
            $table->string('profession_mere')->nullable();
            $table->string('telephone_mere')->nullable();
            
            // Informations supplémentaires
            $table->text('observations')->nullable();
            $table->string('photo')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('matricule');
            $table->index('classe_id');
            $table->index('statut');
            $table->index('utilisateur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};