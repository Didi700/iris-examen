<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeQuestion extends Model
{
    protected $table = 'types_question';

    protected $fillable = [
        'nom',
        'code',
        'description',
        'correction_automatique',
    ];

    protected $casts = [
        'correction_automatique' => 'boolean',
    ];

    // Relations

    public function questions(): HasMany
    {
        return $this->hasMany(BanqueQuestion::class);
    }
}