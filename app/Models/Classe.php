<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    //use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'nom',
        'code',
        'niveau',
        'annee_scolaire',
        'description',
        'effectif_max',
        'effectif_actuel',
        'accepte_alternance',
        'accepte_initial',
        'accepte_formation_continue',
        'nb_etudiants_initial',
        'nb_etudiants_alternance',
        'nb_etudiants_formation_continue',
        'date_debut',
        'date_fin',
        'statut',
        'cree_par',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'accepte_alternance' => 'boolean',
        'accepte_initial' => 'boolean',
        'accepte_formation_continue' => 'boolean',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    /**
     * Créateur de la classe
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    /**
     * ✅ CORRECTION : Une classe a plusieurs étudiants (via la table etudiants)
     */
    public function etudiants(): BelongsToMany 
    {
        /*return $this->hasMany(Etudiant::class, 'classe_id');*/
        return $this->belongsToMany(Utilisateur::class, 'classe_etudiant', 'classe_id', 'etudiant_id');
    }

    /**
     * ✅ AJOUT : Relation pour récupérer les utilisateurs étudiants directement
     */
    public function utilisateursEtudiants(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, 'etudiants', 'classe_id', 'utilisateur_id')
            ->withTimestamps();
    }

    /**
     * Une classe a plusieurs enseignants
     */
    public function enseignants(): BelongsToMany
    {
        return $this->belongsToMany(Utilisateur::class, 'enseignant_classe', 'classe_id', 'enseignant_id')
            ->withPivot('matiere_id')
            ->withTimestamps();
    }

    /**
     * Une classe a plusieurs examens
     */
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'classe_id');
    }

    // ============================================
    // MÉTHODES UTILES
    // ============================================

    /**
     * Vérifier si la classe est complète
     */
    public function estComplete(): bool
    {
        return $this->effectif_actuel >= $this->effectif_max;
    }

    /**
     * Nombre de places restantes
     */
    public function placesRestantes(): int
    {
        return max(0, $this->effectif_max - $this->effectif_actuel);
    }

    /**
     * Pourcentage de remplissage
     */
    public function pourcentageRemplissage(): float
    {
        if ($this->effectif_max == 0) return 0;
        return round(($this->effectif_actuel / $this->effectif_max) * 100, 2);
    }

    /**
     * Obtenir le nombre réel d'étudiants
     */
    public function getNombreEtudiantsAttribute(): int
    {
        return $this->etudiants()->count();
    }

    /**
     * Obtenir le nom complet de la classe
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' - ' . $this->annee_scolaire;
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope pour les classes actives
     */
    public function scopeActives($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour rechercher une classe
     */
    public function scopeRecherche($query, string $recherche)
    {
        return $query->where('nom', 'LIKE', "%{$recherche}%")
            ->orWhere('code', 'LIKE', "%{$recherche}%")
            ->orWhere('niveau', 'LIKE', "%{$recherche}%");
    }
}