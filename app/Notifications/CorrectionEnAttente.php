<?php

namespace App\Notifications;

use App\Models\Examen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CorrectionEnAttente extends Notification implements ShouldQueue
{
    use Queueable;

    protected $examen;
    protected $nbCopies;

    public function __construct(Examen $examen, int $nbCopies)
    {
        $this->examen = $examen;
        $this->nbCopies = $nbCopies;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('✏️ Copies en attente de correction')
            ->greeting('Bonjour ' . $notifiable->prenom . ' ' . $notifiable->nom . ',')
            ->line($this->nbCopies . ' copie(s) sont en attente de correction pour l\'examen :')
            ->line('**' . $this->examen->titre . '**')
            ->line('Classe : ' . $this->examen->classe->nom)
            ->action('Corriger maintenant', route('enseignant.corrections.index', ['examen_id' => $this->examen->id]))
            ->line('Merci pour votre travail ! 📚');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'correction_en_attente',
            'examen_id' => $this->examen->id,
            'examen_titre' => $this->examen->titre,
            'classe' => $this->examen->classe->nom,
            'nb_copies' => $this->nbCopies,
            'message' => "{$this->nbCopies} copie(s) à corriger pour {$this->examen->titre}",
            'icon' => '✏️',
            'url' => route('enseignant.corrections.index', ['examen_id' => $this->examen->id]),
        ];
    }
}