<?php

namespace App\Mail;

use App\Models\Examen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamenPublieMail extends Mailable
{
    use Queueable, SerializesModels;

    public $examen;

    public function __construct(Examen $examen)
    {
        $this->examen = $examen;
    }

    public function build()
    {
        return $this->subject('Nouvel examen disponible - ' . $this->examen->titre)
                    ->view('emails.examen-publie');
    }
}