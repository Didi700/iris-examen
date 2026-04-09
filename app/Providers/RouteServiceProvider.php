<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Rate limit général
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limit pour les connexions - 5 tentatives par minute
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            
            return Limit::perMinute(5)->by($email . $request->ip());
        });

        // Rate limit pour l'inscription - 3 tentatives par heure
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip());
        });

        // Rate limit pour les réinitialisations de mot de passe
        RateLimiter::for('password-reset', function (Request $request) {
            $email = (string) $request->email;
            
            return Limit::perHour(3)->by($email . $request->ip());
        });

        // Rate limit pour les examens (passage d'examen)
        RateLimiter::for('examen', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limit pour les corrections
        RateLimiter::for('correction', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}