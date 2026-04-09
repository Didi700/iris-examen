<?php

namespace App\Http\Middleware;

use App\Models\LogActivite;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Logger uniquement pour les utilisateurs authentifiés
        if (Auth::check()) {
            // Ne pas logger les requêtes GET de lecture simple
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                $this->logUserActivity($request, $response);
            }
        }

        return $response;
    }

    protected function logUserActivity(Request $request, $response)
    {
        $action = $this->getActionName($request);
        
        // Ne pas logger les actions non pertinentes
        if (!$action) {
            return;
        }

        try {
            LogActivite::create([
                'utilisateur_id' => Auth::id(),
                'action' => $action,
                'module' => $this->getModuleName($request),
                'modele' => $this->getModelName($request),
                'modele_id' => $this->getModelId($request),
                'description' => $this->getDescription($request, $action),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'status_code' => $response->getStatusCode(),
            ]);
        } catch (\Exception $e) {
            // Silencieux en cas d'erreur de logging
            \Log::warning('Erreur logging activité: ' . $e->getMessage());
        }
    }

    protected function getActionName(Request $request): ?string
    {
        $routeName = $request->route()?->getName();
        
        if (!$routeName) {
            return null;
        }

        if (str_contains($routeName, '.store')) return 'creation';
        if (str_contains($routeName, '.update')) return 'modification';
        if (str_contains($routeName, '.destroy')) return 'suppression';
        if (str_contains($routeName, '.publish')) return 'publication';
        if (str_contains($routeName, '.archive')) return 'archivage';
        
        return null;
    }

    protected function getModuleName(Request $request): string
    {
        $routeName = $request->route()?->getName() ?? '';
        
        if (str_contains($routeName, 'examen')) return 'examens';
        if (str_contains($routeName, 'question')) return 'questions';
        if (str_contains($routeName, 'correction')) return 'corrections';
        if (str_contains($routeName, 'classe')) return 'classes';
        if (str_contains($routeName, 'utilisateur')) return 'utilisateurs';
        if (str_contains($routeName, 'matiere')) return 'matieres';
        
        return 'autre';
    }

    protected function getModelName(Request $request): ?string
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }

        // Récupérer le premier paramètre de route qui est un modèle
        foreach ($route->parameters() as $param) {
            if (is_object($param)) {
                return get_class($param);
            }
        }

        return null;
    }

    protected function getModelId(Request $request): ?int
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }

        foreach ($route->parameters() as $param) {
            if (is_object($param) && isset($param->id)) {
                return $param->id;
            }
        }

        return null;
    }

    protected function getDescription(Request $request, string $action): string
    {
        $module = $this->getModuleName($request);
        $user = Auth::user();
        
        return "{$user->prenom} {$user->nom} a effectué une {$action} dans le module {$module}";
    }
}