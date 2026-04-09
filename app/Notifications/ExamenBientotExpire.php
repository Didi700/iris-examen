<?php

namespace App\Notifications;

use App\Models\Examen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ExamenBientotExpire extends Notification implements ShouldQueue
{
    use Queueable;

    protected $examen;
    protected $heuresRestantes;

    public function __construct(Examen $examen)
    {
        $this->examen = $examen;
        $this->heuresRestantes = Carbon::now()->diffInHours($examen->date_fin);
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('etudiant.examens.show', $this->examen);
        
        return (new MailMessage)
            ->subject('⚠️ Rappel : Examen à rendre bientôt')
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line('L\'examen **' . $this->examen->titre . '** expire dans **' . $this->heuresRestantes . ' heure(s)**.')
            ->line('**Date limite :** ' . $this->examen->date_fin->format('d/m/Y à H:i'))
            ->action('Passer l\'examen maintenant', $url)
            ->line('Ne manquez pas cette échéance !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'examen_expire',
            'titre' => 'Examen bientôt expiré',
            'message' => 'L\'examen "' . $this->examen->titre . '" expire dans ' . $this->heuresRestantes . 'h.',
            'examen_id' => $this->examen->id,
            'date_fin' => $this->examen->date_fin->format('d/m/Y à H:i'),
            'heures_restantes' => $this->heuresRestantes,
            'url' => route('etudiant.examens.show', $this->examen),
            'icon' => '⚠️',
            'color' => 'orange',
        ];
    }
}