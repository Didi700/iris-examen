<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'nom',
        'description',
        'niveau_hierarchie',
    ];

    // Un rôle a plusieurs permissions
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    // Un rôle a plusieurs utilisateurs
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class);
    }
}