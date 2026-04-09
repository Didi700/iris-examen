<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $table = 'matieres';

    protected $fillable = [
        'nom',
        'code',
        'description',
        'coefficient',
        'statut',
        'cree_par',
    ];

    // Relations

    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(BanqueQuestion::class);
    }

    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class);
    }
}