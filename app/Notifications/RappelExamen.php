<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Examen;

class RappelExamen extends Notification implements ShouldQueue
{
    use Queueable;

    protected $examen;
    protected $heuresRestantes;

    /**
     * Créer une nouvelle instance de notification
     */
    public function __construct(Examen $examen, $heuresRestantes = 24)
    {
        $this->examen = $examen;
        $this->heuresRestantes = $heuresRestantes;
    }

    /**
     * Obtenir les canaux de notification
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Obtenir la représentation mail de la notification
     */
    public function toMail($notifiable)
    {
        $message = $this->heuresRestantes <= 1 
            ? 'L\'examen commence dans moins d\'1 heure !' 
            : 'L\'examen commence dans ' . $this->heuresRestantes . ' heures !';

        return (new MailMessage)
            ->subject('⏰ Rappel : ' . $this->examen->titre)
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line($message)
            ->line('**Examen :** ' . $this->examen->titre)
            ->line('**Matière :** ' . $this->examen->matiere->nom)
            ->line('**Date de début :** ' . $this->examen->date_debut->format('d/m/Y à H:i'))
            ->line('**Durée :** ' . $this->examen->duree_minutes . ' minutes')
            ->action('Voir l\'examen', route('etudiant.examens.show', $this->examen))
            ->line('Préparez-vous ! 📖')
            ->salutation('L\'équipe IRIS EXAM');
    }

    /**
     * Obtenir la représentation en tableau de la notification (pour la base de données)
     */
    public function toArray($notifiable)
    {
        $message = $this->heuresRestantes <= 1 
            ? '⏰ Rappel : ' . $this->examen->titre . ' commence bientôt !' 
            : '⏰ Rappel : ' . $this->examen->titre . ' dans ' . $this->heuresRestantes . 'h';

        return [
            'type' => 'rappel_examen',
            'examen_id' => $this->examen->id,
            'examen_titre' => $this->examen->titre,
            'matiere' => $this->examen->matiere->nom,
            'date_debut' => $this->examen->date_debut->format('d/m/Y à H:i'),
            'heures_restantes' => $this->heuresRestantes,
            'message' => $message,
            'url' => route('etudiant.examens.show', $this->examen),
            'icon' => '⏰',
            'color' => $this->heuresRestantes <= 1 ? 'red' : 'orange',
        ];
    }
}