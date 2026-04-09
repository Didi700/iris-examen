<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'instructions',
        'enseignant_id',
        'matiere_id',
        'classe_id',
        'type_examen', // ✅ AJOUTÉ
        'fichier_sujet_path', // ✅ AJOUTÉ
        'duree_minutes',
        'note_totale',
        'date_debut',
        'date_fin',
        'melanger_questions',
        'melanger_reponses',
        'afficher_resultats_immediatement',
        'nombre_tentatives_max',
        'autoriser_retour_arriere',
        'seuil_reussite',
        'statut',
        'ordre_questions_aleatoire',  // ✅ AJOUTEZ CECI
        'ordre_reponses_aleatoire',   // ✅ AJOUTEZ CECI
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'duree_minutes' => 'integer',
        'note_totale' => 'decimal:2',
        'melanger_questions' => 'boolean',
        'melanger_reponses' => 'boolean',
        'afficher_resultats_immediatement' => 'boolean',
        'nombre_tentatives_max' => 'integer',
        'autoriser_retour_arriere' => 'boolean',
        'seuil_reussite' => 'integer',
        'ordre_questions_aleatoire' => 'boolean',  // ✅ AJOUTEZ CECI
        'ordre_reponses_aleatoire' => 'boolean',   // ✅ AJOUTEZ CECI
    ];

    // ============================================
    // RELATIONS
    // ============================================

    /**
     * Relation : Un examen a été créé par un enseignant
     */
    public function enseignant()
    {
        return $this->belongsTo(Utilisateur::class, 'enseignant_id');
    }

    // ✅ AJOUTEZ CETTE NOUVELLE RELATION

    public function copies()

    {

        return $this->hasMany(CopieEtudiant::class, 'examen_id');

    }

    // ✅ AJOUTEZ CES MÉTHODES UTILITAIRES
    public function estEnLigne()
    {
        return $this->type_examen === 'en_ligne';
    }

    public function estDocument()
    {
        return $this->type_examen === 'document';
    }

    public function getFichierSujetUrlAttribute()
    {
        if ($this->fichier_sujet_path) {
            return Storage::url($this->fichier_sujet_path);
        }
        return null;
    }

    public function supprimerFichierSujet()
    {
        if ($this->fichier_sujet_path && Storage::exists($this->fichier_sujet_path)) {
            Storage::delete($this->fichier_sujet_path);
        }

    }

    /**
     * Alias pour createur
     */
    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'enseignant_id');
    }

    /**
     * Relation : Un examen appartient à une matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation : Un examen est destiné à une classe
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Relation : Un examen contient plusieurs questions
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'examen_question')
            ->withPivot('ordre', 'points','obligatoire')
            ->withTimestamps()
            ->orderBy('examen_question.ordre');
    }

     // ✅ AJOUTEZ CETTE RELATION
    public function sessions()
    {
        return $this->hasMany(SessionExamen::class, 'examen_id');
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Vérifier si l'examen est en cours
     */

    public function estEnCours()
    {
        if ($this->statut !== 'publie') {
            return false;
        }
        return now()->greaterThanOrEqualTo($this->date_debut);
    }
    
    /**
     * Vérifier si l'examen est terminé
     */
    public function estTermine()
    {
        if ($this->statut !== 'publie') {
            return false;
        }
        return now()->isAfter(
            $this->date_debut->copy()->addMinutes($this->duree_minutes)
        );
    }


    /**
     * Vérifier si l'examen est publié
     */
    public function estPublie()
    {
        return $this->statut === 'publie';
    }

    /**
     * Vérifier si l'examen est un brouillon
     */
    public function estBrouillon()
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Obtenir le nombre de questions
     */
    public function getNbQuestionsAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Obtenir le statut avec libellé
     */
    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'publie' => 'Publié',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'archive' => 'Archivé',
            default => $this->statut,
        };
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatutCouleurAttribute()
    {
        return match($this->statut) {
            'brouillon' => 'gray',
            'publie' => 'blue',
            'en_cours' => 'green',
            'termine' => 'red',
            'archive' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Scope : Examens publiés
     */
    public function scopePublies($query)
    {
        return $query->where('statut', 'publie');
    }

    /**
     * Scope : Examens de l'enseignant
     */
    public function scopeParEnseignant($query, $enseignantId)
    {
        return $query->where('enseignant_id', $enseignantId);
    }

    /**
     * Scope : Examens de la classe
     */
    public function scopeParClasse($query, $classeId)
    {
        return $query->where('classe_id', $classeId);
    }
}