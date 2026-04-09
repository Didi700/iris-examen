<?php

namespace App\Policies;

use App\Models\SessionExamen;
use App\Models\Utilisateur;

class SessionExamenPolicy
{
    public function corriger(Utilisateur $user, SessionExamen $session)
    {
        return $user->estEnseignant() 
            && $session->examen->enseignant_id === $user->id;
    }
}