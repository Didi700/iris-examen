<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectCheating
{
    public function handle(Request $request, Closure $next): Response
    {
        // Détecter les changements d'onglet
        if ($request->has('tab_change')) {
            Log::warning('Changement d\'onglet détecté', [
                'user_id' => auth()->id(),
                'session_id' => $request->input('session_id'),
                'timestamp' => now(),
            ]);
        }

        // Détecter le copier-coller
        if ($request->has('paste_detected')) {
            Log::warning('Copier-coller détecté', [
                'user_id' => auth()->id(),
                'session_id' => $request->input('session_id'),
                'timestamp' => now(),
            ]);
        }

        // Détecter les screenshots (si possible via JS)
        if ($request->has('screenshot_detected')) {
            Log::warning('Screenshot détecté', [
                'user_id' => auth()->id(),
                'session_id' => $request->input('session_id'),
                'timestamp' => now(),
            ]);
        }

        return $next($request);
    }
}