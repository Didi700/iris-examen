<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            if (Schema::hasColumn('banque_questions', 'type_question_id')) {
                $table->dropForeign(['type_question_id']);
                $table->dropColumn('type_question_id');
            }
            
            if (Schema::hasColumn('banque_questions', 'question')) {
                $table->dropColumn('question');
            }
            
            if (Schema::hasColumn('banque_questions', 'options')) {
                $table->dropColumn('options');
            }
            
            if (Schema::hasColumn('banque_questions', 'reponse_correcte')) {
                $table->dropColumn('reponse_correcte');
            }
            
            if (Schema::hasColumn('banque_questions', 'statut')) {
                $table->dropColumn('statut');
            }

            // Ajouter les nouvelles colonnes
            if (!Schema::hasColumn('banque_questions', 'type')) {
                $table->enum('type', ['qcm_simple', 'qcm_multiple', 'vrai_faux', 'texte_libre'])->after('matiere_id');
            }
            
            if (!Schema::hasColumn('banque_questions', 'enonce')) {
                $table->text('enonce')->after('type');
            }
            
            if (!Schema::hasColumn('banque_questions', 'tags')) {
                $table->string('tags')->nullable()->after('explication');
            }
            
            if (!Schema::hasColumn('banque_questions', 'est_active')) {
                $table->boolean('est_active')->default(true)->after('tags');
            }
            
            if (!Schema::hasColumn('banque_questions', 'nb_utilisations')) {
                $table->integer('nb_utilisations')->default(0)->after('est_active');
            }
            
            if (!Schema::hasColumn('banque_questions', 'taux_reussite')) {
                $table->decimal('taux_reussite', 5, 2)->nullable()->after('nb_utilisations');
            }

            // Ajouter soft deletes si pas présent
            if (!Schema::hasColumn('banque_questions', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            // Restaurer l'ancienne structure
            $table->dropColumn(['type', 'enonce', 'tags', 'est_active', 'nb_utilisations', 'taux_reussite', 'deleted_at']);
            
            $table->foreignId('type_question_id')->nullable()->constrained('type_questions');
            $table->text('question');
            $table->json('options')->nullable();
            $table->text('reponse_correcte')->nullable();
            $table->enum('statut', ['active', 'inactive'])->default('active');
        });
    }
};