<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Pages à mettre en cache (en minutes)
     */
    protected $cachePages = [
        'etudiant.dashboard' => 5,
        'etudiant.examens.index' => 5,
        'etudiant.resultats.index' => 10,
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Seulement pour les requêtes GET
        if ($request->method() !== 'GET') {
            return $response;
        }

        // Vérifier si la route est à mettre en cache
        $routeName = $request->route()?->getName();
        
        if (!isset($this->cachePages[$routeName])) {
            return $response;
        }

        // Ajouter les headers de cache
        $minutes = $this->cachePages[$routeName];
        $seconds = $minutes * 60;

        return $response->withHeaders([
            'Cache-Control' => "public, max-age={$seconds}",
            'Expires' => gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT',
        ]);
    }
}