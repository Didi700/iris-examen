<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'message',
        'lien',
        'icone',
        'est_lue',
        'lue_at',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'est_lue' => 'boolean',
        'lue_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['read_at'];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function getReadAtAttribute()
    {
        return $this->lue_at;
    }

    public function setReadAtAttribute($value)
    {
        $this->attributes['lue_at'] = $value;
        $this->attributes['est_lue'] = !is_null($value);
    }

    public function marquerCommeLue(): void
    {
        if (is_null($this->lue_at)) {
            $this->update([
                'est_lue' => true,
                'lue_at' => now(),
            ]);
        }
    }

    public function estLue(): bool
    {
        return $this->est_lue === true || !is_null($this->lue_at);
    }

    public function estNonLue(): bool
    {
        return !$this->estLue();
    }

    public function scopeNonLues($query)
    {
        return $query->where(function($q) {
            $q->where('est_lue', false)->orWhereNull('lue_at');
        });
    }

    public function scopeLues($query)
    {
        return $query->where('est_lue', true)->whereNotNull('lue_at');
    }

    public function scopePourUtilisateur($query, $utilisateurId)
    {
        return $query->where('utilisateur_id', $utilisateurId);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecentes($query, $jours = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }
}