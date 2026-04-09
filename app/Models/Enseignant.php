<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enseignant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'utilisateur_id',
        'matricule',
        'date_embauche',
        'statut',
        'grade',
        'specialite',
        'date_naissance',
        'sexe',
        'lieu_naissance',
        'nationalite',
        'telephone',
        'telephone_bureau',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'bureau',
        'departement',
        'diplome_plus_eleve',
        'etablissement_diplome',
        'annee_diplome',
        'biographie',
        'domaines_expertise',
        'publications',
        'photo',
    ];

    protected $casts = [
        'date_embauche' => 'date',
        'date_naissance' => 'date',
        'annee_diplome' => 'integer',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_classe', 'enseignant_id', 'matiere_id')
            ->withPivot('classe_id')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'enseignant_classe', 'enseignant_id', 'classe_id')
            ->withPivot('matiere_id')
            ->withTimestamps();
    }

    public function examens()
    {
        return $this->hasMany(Examen::class, 'enseignant_id', 'utilisateur_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'enseignant_id', 'utilisateur_id');
    }

    // ============================================
    // ACCESSEURS
    // ============================================

    public function getNomCompletAttribute()
    {
        return $this->utilisateur->nom_complet;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'actif' => 'Actif',
            'conge' => 'En congé',
            'suspendu' => 'Suspendu',
            'retraite' => 'Retraité',
            default => $this->statut,
        };
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}