<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Examen;

class RappelExamenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $examen;
    protected $type;

    public function __construct(Examen $examen, $type = '24h')
    {
        $this->examen = $examen;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $delai = $this->type === '24h' ? '24 heures' : '1 heure';
        
        return (new MailMessage)
            ->subject('Rappel : Examen dans ' . $delai)
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line('Ceci est un rappel pour votre examen à venir.')
            ->line('Examen : ' . $this->examen->titre)
            ->line('Matière : ' . $this->examen->matiere->nom)
            ->line('Date : ' . $this->examen->date_debut->format('d/m/Y à H:i'))
            ->line('Durée : ' . $this->examen->duree . ' minutes')
            ->action('Voir l\'examen', route('etudiant.examens.show', $this->examen))
            ->line('Bonne préparation !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'rappel_examen',
            'examen_id' => $this->examen->id,
            'examen_titre' => $this->examen->titre,
            'matiere' => $this->examen->matiere->nom,
            'date_examen' => $this->examen->date_debut->format('d/m/Y à H:i'),
            'delai' => $this->type,
            'message' => 'Rappel : Examen "' . $this->examen->titre . '" dans ' . ($this->type === '24h' ? '24 heures' : '1 heure'),
            'url' => route('etudiant.examens.show', $this->examen),
        ];
    }
}