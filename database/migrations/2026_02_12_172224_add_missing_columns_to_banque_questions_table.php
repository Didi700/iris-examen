<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            // Points par défaut
            if (!Schema::hasColumn('banque_questions', 'points_par_defaut')) {
                $table->integer('points_par_defaut')->default(1)->after('enonce');
            }
            
            // Niveau de difficulté
            if (!Schema::hasColumn('banque_questions', 'niveau_difficulte')) {
                $table->enum('niveau_difficulte', ['facile', 'moyen', 'difficile'])->default('moyen')->after('points_par_defaut');
            }
            
            // Réponse correcte (pour questions à réponse unique)
            if (!Schema::hasColumn('banque_questions', 'reponse_correcte')) {
                $table->text('reponse_correcte')->nullable()->after('niveau_difficulte');
            }
            
            // Explication
            if (!Schema::hasColumn('banque_questions', 'explication')) {
                $table->text('explication')->nullable()->after('reponse_correcte');
            }
            
            // Tags
            if (!Schema::hasColumn('banque_questions', 'tags')) {
                $table->json('tags')->nullable()->after('explication');
            }
            
            // Est active
            if (!Schema::hasColumn('banque_questions', 'est_active')) {
                $table->boolean('est_active')->default(true)->after('tags');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banque_questions', function (Blueprint $table) {
            $table->dropColumn([
                'points_par_defaut',
                'niveau_difficulte',
                'reponse_correcte',
                'explication',
                'tags',
                'est_active'
            ]);
        });
    }
};