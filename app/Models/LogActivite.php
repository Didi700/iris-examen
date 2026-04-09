<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogActivite extends Model
{
    protected $table = 'logs_activite';

    protected $fillable = [
        'utilisateur_id',
        'action',
        'module',
        'description',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    // Relations

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    // Méthode statique pour logger facilement
    public static function log(string $action, string $module, string $description, array $details = []): void
    {
        self::create([
            'utilisateur_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}