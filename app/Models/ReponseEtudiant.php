<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReponseEtudiant extends Model
{
    use HasFactory;

    protected $table = 'reponses_etudiant';

    protected $fillable = [
        'session_examen_id',
        'session_id',
        'question_id',
        'reponse_id',
        'reponse_donnee',
        'est_correcte',
        'est_correct',
        'points_obtenus',
        'points_max',
        'commentaire_correcteur',
        'commentaire',
        'corrige_par',
        'corrige_le',
        'temps_passe_secondes',
        'temps_reponse_secondes',
        'ordre_affichage',
    ];

    protected $casts = [
        'reponse_donnee' => 'array',
        'reponse_id' => 'array',
        'est_correcte' => 'boolean',
        'est_correct' => 'boolean',
        'points_obtenus' => 'decimal:2',
        'points_max' => 'decimal:2',
        'corrige_le' => 'datetime',
        'temps_passe_secondes' => 'integer',
        'temps_reponse_secondes' => 'integer',
        'ordre_affichage' => 'integer',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    /**
     * ✅ CORRIGÉ : Gestion des deux noms de colonnes possibles
     */
    public function session()
    {
        // Essayer d'abord session_id, puis session_examen_id
        if ($this->session_id) {
            return $this->belongsTo(SessionExamen::class, 'session_id');
        }
        return $this->belongsTo(SessionExamen::class, 'session_examen_id');
    }

    /**
     * Alias pour compatibilité
     */
    public function sessionExamen()
    {
        return $this->session();
    }

    /**
     * Une réponse appartient à une question
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Une réponse peut avoir une réponse sélectionnée (pour QCM)
     */
    public function reponse()
    {
        return $this->belongsTo(Reponse::class, 'reponse_id');
    }

    /**
     * Une réponse peut avoir été corrigée par un enseignant
     */
    public function correcteur()
    {
        return $this->belongsTo(Utilisateur::class, 'corrige_par');
    }

    // ============================================
    // ACCESSEURS & MUTATEURS
    // ============================================

    /**
     * Accesseur pour est_correct (compatibilité)
     */
    public function getEstCorrectAttribute()
    {
        return $this->attributes['est_correct'] ?? $this->attributes['est_correcte'] ?? false;
    }

    /**
     * Mutateur pour est_correct (synchronise est_correcte)
     */
    public function setEstCorrectAttribute($value)
    {
        $this->attributes['est_correct'] = $value;
        $this->attributes['est_correcte'] = $value;
    }

    /**
     * Accesseur pour commentaire (compatibilité)
     */
    public function getCommentaireAttribute()
    {
        return $this->attributes['commentaire'] ?? $this->attributes['commentaire_correcteur'] ?? null;
    }

    /**
     * Mutateur pour commentaire (synchronise commentaire_correcteur)
     */
    public function setCommentaireAttribute($value)
    {
        $this->attributes['commentaire'] = $value;
        $this->attributes['commentaire_correcteur'] = $value;
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Vérifier si la réponse a été corrigée
     */
    public function estCorrigee()
    {
        return $this->points_obtenus !== null;
    }

    /**
     * Vérifier si la réponse nécessite une correction manuelle
     */
    public function necessiteCorrection()
    {
        if (!$this->question) {
            return false;
        }

        return $this->question->type === 'ouverte' && !$this->estCorrigee();
    }

    /**
     * Obtenir les points formatés
     */
    public function getPointsFormatesAttribute()
    {
        if ($this->points_obtenus === null) {
            return 'Non corrigé';
        }

        $pointsMax = $this->points_max ?? 0;
        
        return number_format($this->points_obtenus, 1) . '/' . $pointsMax;
    }

    /**
     * Obtenir la réponse correcte pour les QCM
     */
    public function getReponseCorrecteAttribute()
    {
        if (!$this->question) {
            return null;
        }

        if (!in_array($this->question->type, ['choix_unique', 'choix_multiple', 'vrai_faux'])) {
            return null;
        }

        return $this->question->reponses()
            ->where('est_correcte', true)
            ->get();
    }

    /**
     * Vérifier si cette réponse est correcte
     */
    public function verifierReponse()
    {
        if (!$this->question) {
            return false;
        }

        $question = $this->question;

        switch ($question->type) {
            case 'choix_unique':
            case 'vrai_faux':
                $reponseDonnee = is_array($this->reponse_donnee) 
                    ? ($this->reponse_donnee['reponse_id'] ?? null)
                    : $this->reponse_donnee;

                $reponseBonne = $question->reponses()
                    ->where('est_correcte', true)
                    ->first();

                return $reponseBonne && $reponseDonnee == $reponseBonne->id;

            case 'choix_multiple':
                $reponsesBonnes = $question->reponses()
                    ->where('est_correcte', true)
                    ->pluck('id')
                    ->sort()
                    ->values()
                    ->toArray();

                $reponsesEtudiant = is_array($this->reponse_donnee)
                    ? collect($this->reponse_donnee)->sort()->values()->toArray()
                    : [];

                return $reponsesBonnes === $reponsesEtudiant;

            default:
                return false;
        }
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeCorrigees($query)
    {
        return $query->whereNotNull('points_obtenus');
    }

    public function scopeNonCorrigees($query)
    {
        return $query->whereNull('points_obtenus');
    }

    public function scopeCorrectes($query)
    {
        return $query->where(function($q) {
            $q->where('est_correct', true)
              ->orWhere('est_correcte', true);
        });
    }

    public function scopeIncorrectes($query)
    {
        return $query->where(function($q) {
            $q->where('est_correct', false)
              ->orWhere('est_correcte', false);
        });
    }

    public function scopeParQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    public function scopeParSession($query, $sessionId)
    {
        return $query->where(function($q) use ($sessionId) {
            $q->where('session_id', $sessionId)
              ->orWhere('session_examen_id', $sessionId);
        });
    }

    public function scopeQuestionsOuvertes($query)
    {
        return $query->whereHas('question', function($q) {
            $q->where('type', 'ouverte');
        });
    }

    public function scopeQuestionsQCM($query)
    {
        return $query->whereHas('question', function($q) {
            $q->whereIn('type', ['choix_unique', 'choix_multiple', 'vrai_faux']);
        });
    }
}