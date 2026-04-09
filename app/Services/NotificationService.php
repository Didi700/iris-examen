<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Utilisateur;
use App\Models\Examen;
use App\Models\SessionExamen;

class NotificationService
{
    /**
     * Notifier qu'un examen a été publié
     */
    public static function notifierExamenPublie(Examen $examen)
    {
        // Récupérer tous les étudiants de la classe
        $etudiants = Utilisateur::whereHas('role', function($q) {
            $q->where('nom', 'Etudiant');
        })->whereHas('etudiant.classe', function($q) use ($examen) {
            $q->where('classe_id', $examen->classe_id);
        })->get();

        foreach ($etudiants as $etudiant) {
            Notification::create([
                'utilisateur_id' => $etudiant->id,
                'type' => 'examen_publie',
                'titre' => 'Nouvel examen disponible',
                'message' => "L'examen « {$examen->titre} » est maintenant disponible en {$examen->matiere->nom}.",
                'lien' => route('etudiant.examens.show', $examen->id),
                'icone' => '📝'
            ]);
        }
    }

    /**
     * Notifier qu'une note est disponible
     */
    public static function notifierNoteDisponible(SessionExamen $session)
    {
        $noteMax = $session->note_maximale ?? $session->examen->note_totale;
        $noteSur20 = ($session->note_obtenue / $noteMax) * 20;
        
        $mention = '';
        if ($noteSur20 >= 16) {
            $mention = '🎉 Excellent !';
            $icone = '🌟';
        } elseif ($noteSur20 >= 14) {
            $mention = '👏 Très bien !';
            $icone = '✨';
        } elseif ($noteSur20 >= 12) {
            $mention = '👍 Bien !';
            $icone = '💪';
        } elseif ($noteSur20 >= 10) {
            $mention = '✓ Passable';
            $icone = '📊';
        } else {
            $mention = 'Continuez vos efforts';
            $icone = '📚';
        }

        Notification::create([
            'utilisateur_id' => $session->etudiant_id,
            'type' => 'note_disponible',
            'titre' => 'Nouvelle note disponible',
            'message' => "Votre note pour « {$session->examen->titre} » : {$noteSur20}/20. {$mention}",
            'lien' => route('etudiant.resultats.show', $session->id),
            'icone' => $icone
        ]);
    }

    /**
     * Notifier l'enseignant qu'une copie est à corriger
     */
    public static function notifierCorrectionRequise(SessionExamen $session)
    {
        $enseignant = $session->examen->enseignant;

        Notification::create([
            'utilisateur_id' => $enseignant->id,
            'type' => 'correction_requise',
            'titre' => 'Nouvelle copie à corriger',
            'message' => "{$session->etudiant->prenom} {$session->etudiant->nom} a terminé l'examen « {$session->examen->titre} ».",
            'lien' => route('enseignant.corrections.show', $session->id),
            'icone' => '✏️'
        ]);
    }

    /**
     * Notifier qu'un examen commence bientôt
     */
    public static function notifierExamenProche(Examen $examen)
    {
        // Récupérer tous les étudiants de la classe
        $etudiants = Utilisateur::whereHas('role', function($q) {
            $q->where('nom', 'Etudiant');
        })->whereHas('etudiant.classe', function($q) use ($examen) {
            $q->where('classe_id', $examen->classe_id);
        })->get();

        $heuresRestantes = now()->diffInHours($examen->date_debut);

        foreach ($etudiants as $etudiant) {
            Notification::create([
                'utilisateur_id' => $etudiant->id,
                'type' => 'examen_proche',
                'titre' => "⏰ Examen dans {$heuresRestantes}h",
                'message' => "N'oubliez pas : l'examen « {$examen->titre} » commence dans {$heuresRestantes} heures.",
                'lien' => route('etudiant.examens.show', $examen->id),
                'icone' => '⏰'
            ]);
        }
    }

    /**
     * Notifier qu'un examen se termine bientôt
     */
    public static function notifierExamenBientotFini(Examen $examen)
    {
        // Récupérer les étudiants qui n'ont pas encore passé l'examen
        $etudiants = Utilisateur::whereHas('role', function($q) {
            $q->where('nom', 'Etudiant');
        })->whereHas('etudiant.classe', function($q) use ($examen) {
            $q->where('classe_id', $examen->classe_id);
        })->whereDoesntHave('etudiant.sessionsExamen', function($q) use ($examen) {
            $q->where('examen_id', $examen->id);
        })->get();

        $heuresRestantes = now()->diffInHours($examen->date_fin);

        foreach ($etudiants as $etudiant) {
            Notification::create([
                'utilisateur_id' => $etudiant->id,
                'type' => 'examen_bientot_fini',
                'titre' => '⚠️ Dernière chance !',
                'message' => "L'examen « {$examen->titre} » se termine dans {$heuresRestantes} heures !",
                'lien' => route('etudiant.examens.show', $examen->id),
                'icone' => '⚠️'
            ]);
        }
    }

    /**
     * Notifier l'étudiant du délai d'examen
     */
    public static function notifierRappelExamen(SessionExamen $session, int $minutesRestantes)
    {
        Notification::create([
            'utilisateur_id' => $session->etudiant_id,
            'type' => 'rappel_examen',
            'titre' => "⏱️ Plus que {$minutesRestantes} minutes !",
            'message' => "Il vous reste {$minutesRestantes} minutes pour terminer « {$session->examen->titre} ».",
            'lien' => route('etudiant.examens.passer', $session->examen->id),
            'icone' => '⏱️'
        ]);
    }

    /**
     * Notifier de la création d'une nouvelle classe
     */
    public static function notifierNouvelleClasse($classe, $enseignants)
    {
        foreach ($enseignants as $enseignant) {
            Notification::create([
                'utilisateur_id' => $enseignant->id,
                'type' => 'nouvelle_classe',
                'titre' => 'Nouvelle classe assignée',
                'message' => "Vous avez été assigné à la classe « {$classe->nom} ».",
                'lien' => route('enseignant.classes.show', $classe->id),
                'icone' => '👥'
            ]);
        }
    }


    /**
    * Bienvenue pour les nouveaux utilisateurs
    */
    public static function notifierBienvenue(Utilisateur $utilisateur)
    {
        $message = match($utilisateur->role->nom) {
            'enseignant' => 'Bienvenue sur IRIS EXAM ! Commencez par créer vos premières questions.',
            'etudiant' => 'Bienvenue sur IRIS EXAM ! Consultez vos examens disponibles dès maintenant.',
            'admin' => 'Bienvenue sur le panel d\'administration IRIS EXAM.',
            default => 'Bienvenue sur IRIS EXAM !'
        };
        $lien = match($utilisateur->role->nom) {
            'enseignant' => route('enseignant.dashboard'),
            'etudiant' => route('etudiant.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('home')
        };
        Notification::create([
            'utilisateur_id' => $utilisateur->id,
            'type' => 'bienvenue',
            'titre' => '👋 Bienvenue !',
            'message' => $message,
            'lien' => $lien,
            'icone' => '🎓'
        ]);

    }
}