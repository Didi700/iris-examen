<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifierChangementMotDePasse
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Si l'utilisateur doit changer son mot de passe
        if ($user && $user->doit_changer_mot_de_passe) {
            // Sauf s'il est déjà sur la page de changement de mot de passe
            if (!$request->is('profil/changer-mot-de-passe')) {
                return redirect()->route('profil.changer-mot-de-passe')
                    ->with('warning', 'Vous devez changer votre mot de passe temporaire avant de continuer.');
            }
        }
        
        return $next($request);
    }
}