<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogin
{
    /**
     * Nombre maximum de tentatives
     */
    protected $maxAttempts = 5;

    /**
     * Durée de blocage en minutes
     */
    protected $decayMinutes = 15;

    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return back()->withErrors([
                'email' => "Trop de tentatives de connexion. Réessayez dans " . ceil($seconds / 60) . " minutes."
            ])->withInput($request->only('email'));
        }

        RateLimiter::hit($key, $this->decayMinutes * 60);

        $response = $next($request);

        // Si la connexion réussit, réinitialiser le compteur
        if ($response->isRedirection() && !$response->getTargetUrl()) {
            RateLimiter::clear($key);
        }

        return $response;
    }

    /**
     * Générer une signature unique pour la requête
     */
    protected function resolveRequestSignature(Request $request)
    {
        return sha1($request->ip() . '|' . $request->input('email'));
    }
}