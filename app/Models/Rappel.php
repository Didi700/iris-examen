<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'examen_id',
        'type',
        'date_rappel',
        'envoye',
        'date_envoi',
    ];

    protected $casts = [
        'date_rappel' => 'datetime',
        'date_envoi' => 'datetime',
        'envoye' => 'boolean',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant()
    {
        return $this->belongsTo(Utilisateur::class, 'etudiant_id');
    }

    /**
     * Relation avec l'examen
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    /**
     * Scope pour les rappels non envoyés
     */
    public function scopeNonEnvoyes($query)
    {
        return $query->where('envoye', false);
    }

    /**
     * Scope pour les rappels à envoyer
     */
    public function scopeAEnvoyer($query)
    {
        return $query->nonEnvoyes()
            ->where('date_rappel', '<=', now());
    }
}