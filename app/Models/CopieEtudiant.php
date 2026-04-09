<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CopieEtudiant extends Model
{
    use HasFactory;

    protected $table = 'copies_etudiants';

    protected $fillable = [
        'session_examen_id',
        'etudiant_id',
        'examen_id',
        'fichier_copie_path',
        'fichier_copie_nom_original',
        'fichier_copie_taille',
        'note_obtenue',
        'commentaire_correcteur',
        'date_soumission',
        'date_correction',
        'correcteur_id',
        'statut',
    ];

    protected $casts = [
        'note_obtenue' => 'decimal:2',
        'date_soumission' => 'datetime',
        'date_correction' => 'datetime',
        'fichier_copie_taille' => 'integer',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function session()
    {
        return $this->belongsTo(SessionExamen::class, 'session_examen_id');
    }

    public function etudiant()
    {
        return $this->belongsTo(Utilisateur::class, 'etudiant_id');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function correcteur()
    {
        return $this->belongsTo(Utilisateur::class, 'correcteur_id');
    }

    // ============================================
    // ACCESSEURS
    // ============================================

    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'soumis' => 'Soumis',
            'en_correction' => 'En correction',
            'corrige' => 'Corrigé',
            default => $this->statut,
        };
    }

    public function getFichierUrlAttribute()
    {
        return Storage::url($this->fichier_copie_path);
    }

    public function getFichierTailleFormateeAttribute()
    {
        $taille = $this->fichier_copie_taille;
        
        if ($taille < 1024) {
            return $taille . ' octets';
        } elseif ($taille < 1048576) {
            return round($taille / 1024, 2) . ' Ko';
        } else {
            return round($taille / 1048576, 2) . ' Mo';
        }
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    public function estCorrige()
    {
        return $this->statut === 'corrige';
    }

    public function peutEtreCorrige()
    {
        return in_array($this->statut, ['soumis', 'en_correction']);
    }

    public function supprimerFichier()
    {
        if ($this->fichier_copie_path && Storage::exists($this->fichier_copie_path)) {
            Storage::delete($this->fichier_copie_path);
        }
    }

    // ============================================
    // ÉVÉNEMENTS
    // ============================================

    protected static function boot()
    {
        parent::boot();

        // Supprimer le fichier lors de la suppression du modèle
        static::deleting(function ($copie) {
            $copie->supprimerFichier();
        });
    }
}