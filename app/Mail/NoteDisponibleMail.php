<?php

namespace App\Mail;

use App\Models\SessionExamen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoteDisponibleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $session;

    public function __construct(SessionExamen $session)
    {
        $this->session = $session;
    }

    public function build()
    {
        return $this->subject('Votre note est disponible - ' . $this->session->examen->titre)
                    ->view('emails.note-disponible');
    }
}