<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ CORRECTION : Nom de table correct
        $tableName = 'reponses_etudiant';

        // Ajouter session_id si n'existe pas
        if (!Schema::hasColumn($tableName, 'session_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('session_id')->nullable()->after('id');
                $table->index('session_id');
            });

            // Copier les valeurs de session_examen_id vers session_id
            DB::statement("UPDATE {$tableName} SET session_id = session_examen_id WHERE session_id IS NULL");
        }

        // Ajouter est_correct si n'existe pas
        if (!Schema::hasColumn($tableName, 'est_correct')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('est_correct')->default(false)->after('reponse_donnee');
            });

            // Copier les valeurs de est_correcte vers est_correct
            if (Schema::hasColumn($tableName, 'est_correcte')) {
                DB::statement("UPDATE {$tableName} SET est_correct = est_correcte WHERE est_correcte IS NOT NULL");
            }
        }

        // Ajouter commentaire si n'existe pas
        if (!Schema::hasColumn($tableName, 'commentaire')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->text('commentaire')->nullable()->after('points_obtenus');
            });

            // Copier les valeurs
            if (Schema::hasColumn($tableName, 'commentaire_correcteur')) {
                DB::statement("UPDATE {$tableName} SET commentaire = commentaire_correcteur WHERE commentaire_correcteur IS NOT NULL");
            }
        }

        // Ajouter points_max si n'existe pas
        if (!Schema::hasColumn($tableName, 'points_max')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->decimal('points_max', 5, 2)->nullable()->after('points_obtenus');
            });
        }
    }

    public function down(): void
    {
        $tableName = 'reponses_etudiant';

        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) {
                if (Schema::hasColumn('reponses_etudiant', 'session_id')) {
                    $table->dropIndex(['session_id']);
                    $table->dropColumn('session_id');
                }
                if (Schema::hasColumn('reponses_etudiant', 'est_correct')) {
                    $table->dropColumn('est_correct');
                }
                if (Schema::hasColumn('reponses_etudiant', 'commentaire')) {
                    $table->dropColumn('commentaire');
                }
                if (Schema::hasColumn('reponses_etudiant', 'points_max')) {
                    $table->dropColumn('points_max');
                }
            });
        }
    }
};