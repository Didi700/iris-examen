<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            
            // Informations professionnelles
            $table->string('matricule')->unique();
            $table->date('date_embauche')->default(now());
            $table->enum('statut', ['actif', 'conge', 'suspendu', 'retraite'])->default('actif');
            $table->string('grade')->nullable();
            $table->string('specialite')->nullable();
            
            // Informations personnelles
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite')->default('Française');
            
            // Contact
            $table->string('telephone')->nullable();
            $table->string('telephone_bureau')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->default('France');
            
            // Lieu de travail
            $table->string('bureau')->nullable();
            $table->string('departement')->nullable();
            
            // Diplômes et qualifications
            $table->string('diplome_plus_eleve')->nullable();
            $table->string('etablissement_diplome')->nullable();
            $table->year('annee_diplome')->nullable();
            
            // Informations supplémentaires
            $table->text('biographie')->nullable();
            $table->text('domaines_expertise')->nullable();
            $table->text('publications')->nullable();
            $table->string('photo')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index('matricule');
            $table->index('statut');
            $table->index('utilisateur_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};