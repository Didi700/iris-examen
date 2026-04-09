<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->string('telephone')->nullable();
            $table->string('matricule')->unique(); // Numéro étudiant/enseignant
            
            // 🆕 Informations supplémentaires pour étudiants
            $table->date('date_naissance')->nullable();
            $table->enum('genre', ['homme', 'femme', 'autre'])->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->default('France');
            
            // Contact d'urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_lien')->nullable(); // père, mère, tuteur, etc.
            $table->string('contact_urgence_telephone')->nullable();
            
            // Photo de profil
            $table->string('photo')->nullable(); // Chemin vers la photo
            
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            
            // Traçabilité : qui a créé cet utilisateur
            $table->foreignId('cree_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Suppression douce
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};