<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banque_questions'; // ✅ Utilise votre table existante

    protected $fillable = [
        'matiere_id',
        'enseignant_id',
        'type',
        'enonce',
        'explication',
        'difficulte',
        'points',
        'tags',
        'est_active',
        'nb_utilisations',
        'taux_reussite',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'est_active' => 'boolean',
        'nb_utilisations' => 'integer',
        'taux_reussite' => 'decimal:2',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'enseignant_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(Utilisateur::class, 'enseignant_id');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'question_id');
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'examen_question')
            ->withPivot('ordre', 'points', 'obligatoire')
            ->withTimestamps()
            ->orderBy('examen_question.ordre');
    }

    // ============================================
    // ACCESSEURS
    // ============================================

    public function getTypeLibelleAttribute()
    {
        return match($this->type) {
            'qcm_simple' => 'QCM (réponse unique)',
            'qcm_multiple' => 'QCM (réponses multiples)',
            'vrai_faux' => 'Vrai ou Faux',
            'texte_libre' => 'Texte libre',
            default => $this->type,
        };
    }

    public function getDifficulteLibelleAttribute()
    {
        return match($this->difficulte) {
            'facile' => 'Facile',
            'moyen' => 'Moyen',
            'difficile' => 'Difficile',
            default => $this->difficulte,
        };
    }

    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    public function getNbReponsesCorrectesAttribute()
    {
        return $this->reponses()->where('est_correcte', true)->count();
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    public function estQCM()
    {
        return in_array($this->type, ['qcm_simple', 'qcm_multiple', 'vrai_faux']);
    }

    public function estTexteLibre()
    {
        return $this->type === 'texte_libre';
    }

    public function incrementerUtilisation()
    {
        $this->increment('nb_utilisations');
    }

    public function updateTauxReussite($taux)
    {
        $this->update(['taux_reussite' => $taux]);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActives($query)
    {
        return $query->where('est_active', true);
    }

    public function scopeParMatiere($query, $matiereId)
    {
        return $query->where('matiere_id', $matiereId);
    }

    public function scopeParCreateur($query, $enseignantId)
    {
        return $query->where('enseignant_id', $enseignantId);
    }

    public function scopeParEnseignant($query, $enseignantId)
    {
        return $query->where('enseignant_id', $enseignantId);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParDifficulte($query, $difficulte)
    {
        return $query->where('difficulte', $difficulte);
    }
}