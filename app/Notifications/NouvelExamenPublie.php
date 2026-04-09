<?php

namespace App\Notifications;

use App\Models\Examen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelExamenPublie extends Notification implements ShouldQueue
{
    use Queueable;

    protected $examen;

    public function __construct(Examen $examen)
    {
        $this->examen = $examen;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('etudiant.examens.show', $this->examen);
        
        return (new MailMessage)
            ->subject('📚 Nouvel examen disponible')
            ->greeting('Bonjour ' . $notifiable->prenom . ' !')
            ->line('Un nouvel examen a été publié : **' . $this->examen->titre . '**')
            ->line('**Matière :** ' . $this->examen->matiere->nom)
            ->line('**Durée :** ' . $this->examen->duree . ' minutes')
            ->line('**Date limite :** ' . $this->examen->date_fin->format('d/m/Y à H:i'))
            ->action('Voir l\'examen', $url)
            ->line('Bonne chance !');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'nouvel_examen',
            'titre' => 'Nouvel examen disponible',
            'message' => 'L\'examen "' . $this->examen->titre . '" est maintenant disponible.',
            'examen_id' => $this->examen->id,
            'matiere' => $this->examen->matiere->nom,
            'date_fin' => $this->examen->date_fin->format('d/m/Y à H:i'),
            'duree' => $this->examen->duree,
            'url' => route('etudiant.examens.show', $this->examen),
            'icon' => '📚',
            'color' => 'green',
        ];
    }
}