<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            // Alertes anti-triche détaillées
            $table->json('alertes_triche')->nullable()->after('changements_onglet');
            
            // Décision enseignant
            $table->enum('decision_enseignant', ['aucune', 'ignore', 'avertissement', 'annulation', 'sanction'])
                  ->default('aucune')
                  ->after('alertes_triche');
            
            // Commentaire enseignant
            $table->text('commentaire_enseignant')->nullable()->after('decision_enseignant');
            
            // Date de la décision
            $table->timestamp('date_decision')->nullable()->after('commentaire_enseignant');
            
            // ID de l'enseignant qui a pris la décision
            $table->foreignId('decision_par')->nullable()
                  ->after('date_decision')
                  ->constrained('utilisateurs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sessions_examen', function (Blueprint $table) {
            $table->dropForeign(['decision_par']);
            $table->dropColumn([
                'alertes_triche',
                'decision_enseignant',
                'commentaire_enseignant',
                'date_decision',
                'decision_par'
            ]);
        });
    }
};