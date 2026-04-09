<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            // Ajouter le type d'examen
            $table->enum('type_examen', ['en_ligne', 'document'])
                ->default('en_ligne')
                ->after('classe_id');
            
            // Chemin du fichier PDF pour les examens de type "document"
            $table->string('fichier_sujet_path')->nullable()->after('type_examen');
        });
    }

    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropColumn(['type_examen', 'fichier_sujet_path']);
        });
    }
};