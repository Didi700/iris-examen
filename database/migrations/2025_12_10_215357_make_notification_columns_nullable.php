<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('titre')->nullable()->change();
            $table->text('message')->nullable()->change();
            $table->string('lien')->nullable()->change();
            $table->string('icone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('titre')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
            $table->string('lien')->nullable(false)->change();
            $table->string('icone')->nullable(false)->change();
        });
    }
};