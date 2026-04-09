<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BanqueQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banque_questions';

    protected $fillable = [
        'enseignant_id',
        'matiere_id',
        'type',
        'enonce',
        'points_par_defaut',
        'niveau_difficulte',
        'reponse_correcte',
        'explication',
        'tags',
        'est_active',
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'enseignant
     */
    public function enseignant()
    {
        return $this->belongsTo(Utilisateur::class, 'enseignant_id');
    }

    /**
     * Relation avec la matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * ✅ Relation avec les propositions de réponse
     * La table reponses a question_id qui fait référence à banque_questions.id
     */
    public function propositions()
    {
        return $this->hasMany(PropositionReponse::class, 'question_id')->orderBy('ordre');
    }

    /**
     * Relation many-to-many avec les examens
     */
    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'examen_question', 'question_id', 'examen_id')
            ->withPivot('ordre', 'points', 'obligatoire')
            ->withTimestamps();
    }

    /**
     * Scope pour les questions actives
     */
    public function scopeActive($query)
    {
        return $query->where('est_active', true);
    }

    /**
     * Scope par matière
     */
    public function scopeParMatiere($query, $matiereId)
    {
        return $query->where('matiere_id', $matiereId);
    }

    /**
     * Scope par enseignant
     */
    public function scopeParEnseignant($query, $enseignantId)
    {
        return $query->where('enseignant_id', $enseignantId);
    }

    /**
     * Scope par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope par difficulté
     */
    public function scopeParDifficulte($query, $difficulte)
    {
        return $query->where('niveau_difficulte', $difficulte);
    }
}