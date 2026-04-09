<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'texte',
        'est_correcte',
        'ordre',
    ];

    protected $casts = [
        'est_correcte' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Relation : Une réponse appartient à une question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}