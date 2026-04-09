<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('type'); // examen_publie, note_disponible, correction_requise, etc.
            $table->string('titre');
            $table->text('message');
            $table->string('lien')->nullable(); // URL de redirection
            $table->string('icone')->nullable(); // Emoji ou classe d'icône
            $table->boolean('est_lue')->default(false);
            $table->timestamp('lue_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};