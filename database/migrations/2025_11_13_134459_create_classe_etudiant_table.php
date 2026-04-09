<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classe_etudiant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained('utilisateurs')->onDelete('cascade');
            
            // 🆕 Régime de formation
            $table->enum('regime', ['initial', 'alternance', 'formation_continue'])->default('initial');
            
            // Dates importantes
            $table->date('date_inscription');
            $table->date('date_desinscription')->nullable();
            
            // 🆕 Informations spécifiques à l'alternance
            $table->string('entreprise')->nullable(); // Nom de l'entreprise (si alternance)
            $table->text('adresse_entreprise')->nullable();
            $table->string('ville_entreprise')->nullable();
            $table->string('code_postal_entreprise')->nullable();
            
            // Tuteur en entreprise
            $table->string('tuteur_entreprise')->nullable(); // Nom du tuteur
            $table->string('poste_tuteur')->nullable(); // Poste du tuteur
            $table->string('email_tuteur')->nullable();
            $table->string('telephone_tuteur')->nullable();
            
            // Rythme d'alternance (si applicable)
            $table->string('rythme_alternance')->nullable(); // Ex: "1 semaine / 3 semaines", "2 jours / 3 jours"
            
            // Statut de l'inscription
            $table->enum('statut', ['inscrit', 'desinscrit', 'diplome', 'abandonne', 'en_attente'])->default('inscrit');
            
            // Traçabilité
            $table->foreignId('inscrit_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->foreignId('desinscrit_par')->nullable()->constrained('utilisateurs')->onDelete('set null');
            
            // Remarques
            $table->text('commentaire')->nullable(); // Remarques particulières sur l'étudiant
            
            // Documents
            $table->string('contrat_alternance')->nullable(); // Chemin vers le contrat (si alternance)
            $table->string('convention_stage')->nullable(); // Chemin vers la convention
            
            $table->timestamps();

            // Un étudiant ne peut être inscrit qu'une seule fois dans une classe avec le même régime
            $table->unique(['classe_id', 'etudiant_id']);
            
            // Index pour recherche rapide
            $table->index(['classe_id', 'regime', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classe_etudiant');
    }
};