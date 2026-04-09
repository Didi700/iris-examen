<?php

namespace App\Notifications;

use App\Models\SessionExamen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultatsDisponibles extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;

    public function __construct(SessionExamen $session)
    {
        $this->session = $session;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $reussi = $this->session->pourcentage >= 50;
        $emoji = $this->session->pourcentage >= 70 ? '🎉' : ($this->session->pourcentage >= 50 ? '👍' : '💪');

        return (new MailMessage)
            ->subject('📊 Vos résultats sont disponibles - IRIS EXAM')
            ->greeting("Bonjour {$notifiable->prenom} {$notifiable->nom},")
            ->line("Vos résultats pour l'examen **{$this->session->examen->titre}** sont maintenant disponibles.")
            ->line("**Note obtenue :** {$this->session->note_obtenue}/{$this->session->note_totale} ({$this->session->pourcentage}%)")
            ->line("**Statut :** " . ($reussi ? '✅ Réussi' : '❌ Échoué'))
            ->action('Voir mes résultats', route('etudiant.resultats.show', $this->session->id))
            ->line("Continuez à travailler dur ! $emoji");
    }

    public function toArray($notifiable)
    {
        $emoji = $this->session->pourcentage >= 70 ? '🎉' : ($this->session->pourcentage >= 50 ? '👍' : '💪');

        return [
            'type' => 'resultats_disponibles',
            'session_id' => $this->session->id,
            'examen_id' => $this->session->examen_id,
            'titre' => $this->session->examen->titre,
            'note_obtenue' => $this->session->note_obtenue,
            'note_totale' => $this->session->note_totale,
            'pourcentage' => $this->session->pourcentage,
            'reussi' => $this->session->pourcentage >= 50,
            'message' => "Vos résultats pour '{$this->session->examen->titre}' sont disponibles : {$this->session->note_obtenue}/{$this->session->note_totale}",
            'icon' => $emoji,
            'url' => route('etudiant.resultats.show', $this->session->id),
        ];
    }
}