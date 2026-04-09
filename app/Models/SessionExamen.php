<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExamen extends Model
{
    use HasFactory;

    protected $table = 'sessions_examen';

    protected $fillable = [
        'examen_id',
        'etudiant_id',
        'numero_tentative',
        'date_debut',
        'date_fin',
        'date_soumission',
        'note_obtenue',
        'note_maximale',
        'pourcentage',
        'temps_passe_secondes',
        'changements_onglet',
        'questions_repondues',
        'ip_address',
        'user_agent',
        'statut',
        'statut_correction',
        'ordre_questions',
        'ordre_reponses',
        'tentatives_triche',
        // ✅ NOUVEAUX CHAMPS
        'alertes_triche',
        'decision_enseignant',
        'commentaire_enseignant',
        'date_decision',
        'decision_par',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_soumission' => 'datetime',
        'date_decision' => 'datetime',
        'note_obtenue' => 'decimal:2',
        'note_maximale' => 'decimal:2',
        'pourcentage' => 'decimal:2',
        'numero_tentative' => 'integer',
        'temps_passe_secondes' => 'integer',
        'changements_onglet' => 'integer',
        'questions_repondues' => 'integer',
        'tentatives_triche' => 'integer',
        'ordre_questions' => 'array',
        'ordre_reponses' => 'array',
        'alertes_triche' => 'array', // ✅ NOUVEAU
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }

    public function reponsesEtudiants()
    {
        return $this->hasMany(ReponseEtudiant::class, 'session_examen_id');
    }

    public function reponses()
    {
        return $this->hasMany(ReponseEtudiant::class, 'session_examen_id');
    }

    public function utilisateur()
    {
        return $this->hasOneThrough(
            Utilisateur::class,
            Etudiant::class,
            'id',
            'id',
            'etudiant_id',
            'utilisateur_id'
        );
    }

    /**
     * ✅ NOUVEAU : Enseignant qui a pris la décision
     */
    public function decisionPar()
    {
        return $this->belongsTo(Utilisateur::class, 'decision_par');
    }

    // ============================================
    // ✅ MÉTHODES ALERTES ANTI-TRICHE
    // ============================================

    /**
     * Ajouter une alerte de triche
     */
    public function ajouterAlerte(string $type, $details = null)
    {
        $alertes = $this->alertes_triche ?? [];
        
        $alertes[] = [
            'type' => $type,
            'timestamp' => now()->toIso8601String(),
            'details' => $details
        ];

        $this->alertes_triche = $alertes;
        $this->save();
    }

    /**
     * Obtenir le nombre total d'alertes
     */
    public function getNombreAlertesAttribute()
    {
        return count($this->alertes_triche ?? []);
    }

    /**
     * Vérifier si la session a des alertes
     */
    public function aDesAlertes()
    {
        return $this->nombre_alertes > 0;
    }

    /**
     * Obtenir le niveau de gravité (0-3)
     * 0 = Aucune, 1 = Faible, 2 = Modéré, 3 = Élevé
     */
    public function getNiveauGraviteAttribute()
    {
        $total = $this->nombre_alertes;
        
        if ($total === 0) return 0;
        if ($total <= 2) return 1;
        if ($total <= 5) return 2;
        return 3;
    }

    /**
     * Obtenir la couleur selon la gravité
     */
    public function getCouleurGraviteAttribute()
    {
        return match($this->niveau_gravite) {
            0 => 'green',
            1 => 'yellow',
            2 => 'orange',
            3 => 'red',
            default => 'gray'
        };
    }

    /**
     * Obtenir le libellé de la décision
     */
    public function getDecisionLibelleAttribute()
    {
        return match($this->decision_enseignant) {
            'aucune' => 'Aucune décision',
            'ignore' => '✅ Ignoré',
            'avertissement' => '⚠️ Avertissement',
            'annulation' => '❌ Examen annulé',
            'sanction' => '🚫 Sanctionné',
            default => $this->decision_enseignant
        };
    }

    /**
     * Vérifier si une décision a été prise
     */
    public function aUneDecision()
    {
        return $this->decision_enseignant !== 'aucune';
    }

    // ============================================
    // MÉTHODES UTILITAIRES EXISTANTES
    // ============================================

    public function estEnCours()
    {
        return $this->statut === 'en_cours';
    }

    public function estSoumis()
    {
        return $this->statut === 'soumis';
    }

    public function dateFinCalculee()
    {
        if (!$this->date_debut || !$this->examen?->duree_minutes) {
            return null;
        }
        return $this->date_debut->copy()
            ->addMinutes($this->examen->duree_minutes);
    }

    public function estTermine()
    {
        return in_array($this->statut, ['soumis', 'termine', 'corrige']);
    }

    public function estCorrige()
    {
        return $this->statut_correction === 'corrige' || $this->statut_correction === 'publie';
    }

    public function estPublie()
    {
        return $this->statut_correction === 'publie';
    }

    public function necessiteCorrection()
    {
        if (!in_array($this->statut, ['soumis', 'termine'])) {
            return false;
        }

        return $this->reponsesEtudiants()
            ->whereNull('points_obtenus')
            ->exists();
    }

    public function calculerPourcentage()
    {
        $noteMax = $this->note_totale ?? $this->note_maximale ?? $this->examen->note_totale ?? 20;

        if ($noteMax > 0 && $this->note_obtenue !== null) {
            return round(($this->note_obtenue / $noteMax) * 100, 2);
        }
        
        return 0;
    }

    public function estReussi()
    {
        if ($this->note_obtenue === null) {
            return false;
        }

        $pourcentage = $this->pourcentage ?? $this->calculerPourcentage();
        
        return $pourcentage >= ($this->examen->seuil_reussite ?? 50);
    }

    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'en_cours' => 'En cours',
            'soumis' => 'Soumis',
            'termine' => 'Terminé',
            'corrige' => 'Corrigé',
            'abandonne' => 'Abandonné',
            'temps_ecoule' => 'Temps écoulé',
            default => ucfirst($this->statut),
        };
    }

    public function getStatutCorrectionLibelleAttribute()
    {
        return match($this->statut_correction) {
            'en_attente' => 'En attente',
            'corrige' => 'Corrigé',
            'publie' => 'Publié',
            default => ucfirst($this->statut_correction),
        };
    }

    public function getStatutCouleurAttribute()
    {
        return match($this->statut) {
            'en_cours' => 'blue',
            'soumis' => 'orange',
            'termine' => 'orange',
            'corrige' => 'green',
            'abandonne' => 'red',
            'temps_ecoule' => 'gray',
            default => 'gray',
        };
    }

    public function getStatutCorrectionCouleurAttribute()
    {
        return match($this->statut_correction) {
            'en_attente' => 'yellow',
            'corrige' => 'blue',
            'publie' => 'green',
            default => 'gray',
        };
    }

    public function getNoteFormateeAttribute()
    {
        if ($this->note_obtenue === null) {
            return 'En attente';
        }

        $noteMax = $this->note_totale ?? $this->note_maximale ?? $this->examen->note_totale ?? 20;
        
        return number_format($this->note_obtenue, 1) . '/' . $noteMax;
    }

    public function getTempsPasseFormate()
    {
        if (!$this->temps_passe_secondes || $this->temps_passe_secondes < 0) {
            return '0 min';
        }

        $heures = floor($this->temps_passe_secondes / 3600);
        $minutes = floor(($this->temps_passe_secondes % 3600) / 60);
        $secondes = $this->temps_passe_secondes % 60;

        if ($heures > 0) {
            return sprintf('%dh %02dm', $heures, $minutes);
        } elseif ($minutes > 0) {
            return sprintf('%dm %02ds', $minutes, $secondes);
        } else {
            return sprintf('%ds', $secondes);
        }
    }

    public function tempsRestant()
    {
        if ($this->statut !== 'en_cours') {
            return 0;
        }
        $dateFin = $this->dateFinCalculee();

        if (!$dateFin) {
            return 0;
        }

        return max(0, now()->diffInSeconds($dateFin, false));
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeSoumis($query)
    {
        return $query->where('statut', 'soumis');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeCorrigees($query)
    {
        return $query->whereIn('statut_correction', ['corrige', 'publie']);
    }

    public function scopeTerminees($query)
    {
        return $query->whereIn('statut', ['soumis', 'termine', 'corrige']);
    }

    public function scopeParEtudiant($query, $etudiantId)
    {
        return $query->where('etudiant_id', $etudiantId);
    }

    public function scopeParExamen($query, $examenId)
    {
        return $query->where('examen_id', $examenId);
    }

    public function scopeAvecNote($query)
    {
        return $query->whereNotNull('note_obtenue');
    }

    public function scopeReussies($query)
    {
        return $query->whereNotNull('note_obtenue')
            ->whereNotNull('pourcentage')
            ->where('pourcentage', '>=', 50);
    }

    public function scopeACorreiger($query)
    {
        return $query->where('statut', 'termine')
            ->where('statut_correction', 'en_attente');
    }

    public function scopePubliees($query)
    {
        return $query->where('statut_correction', 'publie');
    }

    /**
     * ✅ NOUVEAU SCOPE : Sessions avec alertes
     */
    public function scopeAvecAlertes($query)
    {
        return $query->whereNotNull('alertes_triche')
                     ->where('alertes_triche', '!=', '[]')
                     ->where('alertes_triche', '!=', 'null');
    }

    /**
     * ✅ NOUVEAU SCOPE : Sessions sans décision
     */
    public function scopeSansDecision($query)
    {
        return $query->where('decision_enseignant', 'aucune');
    }
}