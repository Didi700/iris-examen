<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mettre à jour toutes les sessions "soumis" en "termine"
        DB::table('sessions_examen')
            ->where('statut', 'soumis')
            ->update(['statut' => 'termine']);
    }

    public function down(): void
    {
        DB::table('sessions_examen')
            ->where('statut', 'termine')
            ->update(['statut' => 'soumis']);
    }
};