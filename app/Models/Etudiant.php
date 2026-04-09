<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etudiant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'etudiants';

    protected $fillable = [
        'utilisateur_id',
        'classe_id',
        'matricule',
        'date_inscription',
        'statut',
        'regime',              // ← AJOUTE CETTE LIGNE
        'entreprise',          // ← AJOUTE CETTE LIGNE
        'date_naissance',
        'sexe',
        'lieu_naissance',
        'nationalite',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'contact_urgence_nom',
        'contact_urgence_telephone',
        'contact_urgence_relation',
        'nom_pere',
        'profession_pere',
        'telephone_pere',
        'nom_mere',
        'profession_mere',
        'telephone_mere',
        'observations',
        'photo',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'date_naissance' => 'date',
    ];

    protected $dates = [
        'date_inscription',
        'date_naissance',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    /**
     * Un étudiant appartient à un utilisateur
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    /**
     * Un étudiant appartient à une classe
     */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    /**
     * Un étudiant peut passer plusieurs sessions d'examen
     */
    public function sessionsExamen(): HasMany
    {
        return $this->hasMany(SessionExamen::class, 'etudiant_id');
    }

    /**
     * Alias pour les sessions
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(SessionExamen::class, 'etudiant_id');
    }

    // ============================================
    // ACCESSEURS (GETTERS)
    // ============================================

    /**
     * Obtenir le nom complet de l'étudiant
     */
    public function getNomCompletAttribute(): string
    {
        return $this->utilisateur ? $this->utilisateur->nom_complet : '';
    }

    /**
     * Obtenir l'âge de l'étudiant
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        return match($this->statut) {
            'actif' => 'Actif',
            'suspendu' => 'Suspendu',
            'diplome' => 'Diplômé',
            'abandonne' => 'Abandon',
            default => ucfirst($this->statut),
        };
    }

    /**
     * Obtenir le nom complet avec matricule
     */
    public function getNomCompletAvecMatriculeAttribute(): string
    {
        return $this->nom_complet . ' (' . $this->matricule . ')';
    }

    /**
     * Obtenir les initiales
     */
    public function getInitialesAttribute(): string
    {
        if (!$this->utilisateur) {
            return '';
        }
        return strtoupper(
            substr($this->utilisateur->prenom, 0, 1) . 
            substr($this->utilisateur->nom, 0, 1)
        );
    }

    /**
     * Obtenir le nom de la classe
     */
    public function getNomClasseAttribute(): string
    {
        return $this->classe ? $this->classe->nom : 'Aucune classe';
    }

    /**
     * Obtenir le contact d'urgence complet
     */
    public function getContactUrgenceCompletAttribute(): string
    {
        if (!$this->contact_urgence_nom) {
            return 'Non renseigné';
        }
        
        $contact = $this->contact_urgence_nom;
        if ($this->contact_urgence_relation) {
            $contact .= ' (' . $this->contact_urgence_relation . ')';
        }
        if ($this->contact_urgence_telephone) {
            $contact .= ' - ' . $this->contact_urgence_telephone;
        }
        
        return $contact;
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Vérifier si l'étudiant est actif
     */
    public function estActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifier si l'étudiant est suspendu
     */
    public function estSuspendu(): bool
    {
        return $this->statut === 'suspendu';
    }

    /**
     * Vérifier si l'étudiant est diplômé
     */
    public function estDiplome(): bool
    {
        return $this->statut === 'diplome';
    }

    /**
     * Vérifier si l'étudiant a abandonné
     */
    public function aAbandonne(): bool
    {
        return $this->statut === 'abandonne';
    }

    /**
     * Obtenir la moyenne générale de l'étudiant
     */
    public function getMoyenneGenerale(): ?float
    {
        return $this->sessionsExamen()
            ->whereNotNull('note_obtenue')
            ->avg('note_obtenue');
    }

    /**
     * Obtenir le nombre total d'examens passés
     */
    public function getNombreExamensPasses(): int
    {
        return $this->sessionsExamen()
            ->whereIn('statut', ['soumis', 'corrige'])
            ->count();
    }

    /**
     * Obtenir le nombre d'examens réussis
     */
    public function getNombreExamensReussis(): int
    {
        return $this->sessionsExamen()
            ->where('statut', 'corrige')
            ->whereRaw('pourcentage >= (SELECT seuil_reussite FROM examens WHERE id = examen_id)')
            ->count();
    }

    /**
     * Obtenir le taux de réussite en pourcentage
     */
    public function getTauxReussite(): ?float
    {
        $total = $this->getNombreExamensPasses();
        if ($total === 0) {
            return null;
        }
        
        $reussis = $this->getNombreExamensReussis();
        return round(($reussis / $total) * 100, 2);
    }

    // ============================================
    // SCOPES (REQUÊTES RÉUTILISABLES)
    // ============================================

    /**
     * Scope pour récupérer uniquement les étudiants actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour récupérer les étudiants suspendus
     */
    public function scopeSuspendus($query)
    {
        return $query->where('statut', 'suspendu');
    }

    /**
     * Scope pour récupérer les étudiants diplômés
     */
    public function scopeDiplomes($query)
    {
        return $query->where('statut', 'diplome');
    }

    /**
     * Scope pour récupérer les étudiants d'une classe spécifique
     */
    public function scopeDeClasse($query, $classeId)
    {
        return $query->where('classe_id', $classeId);
    }

    /**
     * Scope pour rechercher un étudiant par nom, prénom ou matricule
     */
    public function scopeRecherche($query, string $recherche)
    {
        return $query->whereHas('utilisateur', function($q) use ($recherche) {
            $q->where('nom', 'LIKE', "%{$recherche}%")
              ->orWhere('prenom', 'LIKE', "%{$recherche}%")
              ->orWhere('email', 'LIKE', "%{$recherche}%");
        })->orWhere('matricule', 'LIKE', "%{$recherche}%");
    }

    /**
     * Scope pour trier par nom
     */
    public function scopeOrdonnerParNom($query, $direction = 'asc')
    {
        return $query->join('utilisateurs', 'etudiants.utilisateur_id', '=', 'utilisateurs.id')
            ->orderBy('utilisateurs.nom', $direction)
            ->orderBy('utilisateurs.prenom', $direction)
            ->select('etudiants.*');
    }

    /**
     * Scope pour récupérer les étudiants avec leurs notes
     */
    public function scopeAvecNotes($query)
    {
        return $query->with([
            'sessionsExamen' => function($q) {
                $q->whereNotNull('note_obtenue')
                  ->with('examen.matiere')
                  ->orderBy('created_at', 'desc');
            }
        ]);
    }

    // ============================================
    // ÉVÉNEMENTS (EVENTS)
    // ============================================

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Avant la création, générer automatiquement un matricule si absent
        static::creating(function ($etudiant) {
            if (empty($etudiant->matricule)) {
                $etudiant->matricule = static::genererMatricule();
            }
            
            if (empty($etudiant->date_inscription)) {
                $etudiant->date_inscription = now();
            }
            
            if (empty($etudiant->statut)) {
                $etudiant->statut = 'actif';
            }
        });
    }

    /**
     * Générer un matricule unique
     */
    public static function genererMatricule(): string
    {
        $annee = date('Y');
        $dernier = static::whereYear('created_at', $annee)
            ->orderBy('id', 'desc')
            ->first();
        
        $numero = $dernier ? ((int) substr($dernier->matricule, -4)) + 1 : 1;
        
        return 'ETU' . $annee . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}