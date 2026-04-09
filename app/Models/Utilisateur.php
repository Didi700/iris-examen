<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ AJOUTE CETTE LIGNE
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'email_verified_at',
        'password',
        'doit_changer_mot_de_passe', // ← AJOUTEZ CETTE LIGNE
        'role_id',
        'telephone',
        'matricule',
        'date_naissance',
        'genre',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'contact_urgence_nom',
        'contact_urgence_lien',
        'contact_urgence_telephone',
        'photo',
        'statut',
        'cree_par',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'doit_changer_mot_de_passe' => 'boolean', // ← AJOUTEZ CETTE LIGNE
        'date_naissance' => 'date',
        'password' => 'hashed',
    ];

    // ============================================
    // RELATIONS DE BASE
    // ============================================

    /**
     * Un utilisateur appartient à un rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Un utilisateur a été créé par un autre utilisateur
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    /**
     * Un utilisateur peut créer d'autres utilisateurs
     */
    public function utilisateursCreees(): HasMany
    {
        return $this->hasMany(Utilisateur::class, 'cree_par');
    }

    // ============================================
    // RELATIONS ÉTUDIANTS/ENSEIGNANTS (NOUVELLES)
    // ============================================

    /**

    * Relation avec l'entité Etudiant
    */
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'utilisateur_id');
    }

    /**
     * Relation vers la table enseignants (1-1)
     */
    public function enseignant(): HasOne
    {
        return $this->hasOne(Enseignant::class);
    }

    // ============================================
    // RELATIONS CLASSES
    // ============================================

    /**
     * Un étudiant appartient à plusieurs classes
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'classe_etudiant', 'etudiant_id', 'classe_id')
            ->withPivot([
                'regime',
                'date_inscription',
                'entreprise',
                'adresse_entreprise',
                'ville_entreprise',
                'code_postal_entreprise',
                'tuteur_entreprise',
                'poste_tuteur',
                'email_tuteur',
                'telephone_tuteur',
                'rythme_alternance',
                'statut',
                'inscrit_par',
                'commentaire',
            ])
            ->withTimestamps();
    }

    /**
     * Un enseignant enseigne plusieurs classes
     */
    public function classesEnseignees(): BelongsToMany
    {
        return $this->belongsToMany(Classe::class, 'enseignant_classe', 'enseignant_id', 'classe_id')
            ->withPivot('matiere_id')
            ->withTimestamps();
    }

    /**
     * Un utilisateur peut créer des classes
     */
    public function classesCreees(): HasMany
    {
        return $this->hasMany(Classe::class, 'cree_par');
    }

    // ============================================
    // RELATIONS MATIÈRES
    // ============================================

    /**
     * Un enseignant enseigne plusieurs matières (via enseignant_classe)
     */
    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(
            Matiere::class,
            'enseignant_classe',
            'enseignant_id',
            'matiere_id'
        )->distinct();
    }

    /**
     * Un utilisateur peut créer des matières
     */
    public function matieresCreees(): HasMany
    {
        return $this->hasMany(Matiere::class, 'cree_par');
    }

    // ============================================
    // RELATIONS QUESTIONS
    // ============================================

    /**
     * Un enseignant peut créer des questions
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'enseignant_id');
    }

    /**
     * Alias pour les questions créées
     */
    public function questionsCreees(): HasMany
    {
        return $this->hasMany(Question::class, 'enseignant_id');
    }

    // ============================================
    // RELATIONS EXAMENS
    // ============================================

    /**
     * Un enseignant peut créer des examens
     */
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'enseignant_id');
    }

    /**
     * Alias pour les examens créés
     */
    public function examensCreés(): HasMany
    {
        return $this->hasMany(Examen::class, 'enseignant_id');
    }

    /**
     * Un étudiant peut passer des sessions d'examen
     */
    public function sessionsExamen(): HasMany
    {
        return $this->hasMany(SessionExamen::class, 'etudiant_id');
    }

    // ============================================
    // RELATIONS LOGS
    // ============================================

    /**
     * Un utilisateur génère des logs d'activité
     */
    public function logsActivite(): HasMany
    {
        return $this->hasMany(LogActivite::class, 'utilisateur_id');
    }

    /**
     * Alias pour les activités
     */
    public function activites(): HasMany
    {
        return $this->hasMany(LogActivite::class, 'utilisateur_id');
    }

    // ============================================
    // ACCESSEURS (GETTERS)
    // ============================================

    /**
     * Accesseur pour obtenir le nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Accesseur pour obtenir les initiales
     */
    public function getInitialesAttribute(): string
    {
        return strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
    }

    /**
     * Accesseur pour la classe (via la relation etudiant)
     * Permet d'utiliser $user->classe au lieu de $user->etudiant->classe
     */
    public function getClasseAttribute()
    {
        if ($this->role_id === 4 && $this->etudiant) {
            return $this->etudiant->classe;
        }
        return null;
    }

    /**
     * Accesseur pour classe_id (compatibilité avec l'ancien code)
     * Permet d'utiliser $user->classe_id au lieu de $user->etudiant->classe_id
     */
    public function getClasseIdAttribute()
    {
        if ($this->role_id === 4 && $this->etudiant) {
            return $this->etudiant->classe_id;
        }
        return null;
    }

    // ============================================
    // MÉTHODES UTILITAIRES (VÉRIFICATION DE RÔLE)
    // ============================================

    /**
     * Vérifier si l'utilisateur est super admin (role_id = 2)
     */
    public function estSuperAdmin(): bool
    {
        return $this->role_id === 2 || ($this->role && $this->role->nom === 'super_admin');
    }

    /**
     * Vérifier si l'utilisateur est admin (role_id = 1)
     */
    public function estAdmin(): bool
    {
        return $this->role_id === 1 || ($this->role && $this->role->nom === 'admin');
    }

    /**
     * Vérifier si l'utilisateur est enseignant (role_id = 3)
     */
    public function estEnseignant(): bool
    {
        return $this->role_id === 3 || ($this->role && $this->role->nom === 'enseignant');
    }

    /**
     * Vérifier si l'utilisateur est étudiant (role_id = 4)
     */
    public function estEtudiant(): bool
    {
        return $this->role_id === 4 || ($this->role && $this->role->nom === 'etudiant');
    }

    /**
     * Vérifier si l'utilisateur a une permission
     */
    public function aPermission(string $permissionNom): bool
    {
        return $this->role && $this->role->permissions()->where('nom', $permissionNom)->exists();
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function aLeRole(string $roleNom): bool
    {
        return $this->role && strtolower($this->role->nom) === strtolower($roleNom);
    }

    // ============================================
    // MÉTHODES UTILITAIRES (STATUT)
    // ============================================

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function estActif(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Obtenir le nom complet de l'utilisateur (méthode)
     */
    public function nomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Obtenir les initiales du nom complet (méthode)
     */
    public function initiales(): string
    {
        return strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
    }

    // ============================================
    // SCOPES (REQUÊTES RÉUTILISABLES)
    // ============================================

    /**
     * Scope pour récupérer uniquement les utilisateurs actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour récupérer les utilisateurs d'un rôle spécifique
     */
    public function scopeDeRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope pour récupérer uniquement les étudiants
     */
    public function scopeEtudiants($query)
    {
        return $query->where('role_id', 4);
    }

    /**
     * Scope pour récupérer uniquement les enseignants
     */
    public function scopeEnseignants($query)
    {
        return $query->where('role_id', 3);
    }

    /**
     * Scope pour récupérer uniquement les admins
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role_id', [1, 2]);
    }
    /**
    * Relation avec les notifications
    */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
    * Notifications non lues
    */
    public function notificationsNonLues()
    {
        return $this->hasMany(Notification::class)->nonLues();
    }
}