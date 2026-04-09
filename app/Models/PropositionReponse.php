<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropositionReponse extends Model
{
    use HasFactory;

    protected $table = 'reponses';
    
    public $timestamps = false;

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
     * Relation avec la question (banque_questions)
     */
    public function question()
    {
        return $this->belongsTo(BanqueQuestion::class, 'question_id');
    }
}