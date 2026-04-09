<?php

namespace App\Mail;

use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BienvenueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $motDePasseTemporaire;

    public function __construct(Utilisateur $utilisateur, $motDePasseTemporaire)
    {
        $this->utilisateur = $utilisateur;
        $this->motDePasseTemporaire = $motDePasseTemporaire;
    }

    public function build()
    {
        return $this->subject('Bienvenue sur IRIS EXAM - Vos identifiants de connexion')
                    ->view('emails.bienvenue');
    }
}