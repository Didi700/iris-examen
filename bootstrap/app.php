<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias des middlewares
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'verifier.mot.de.passe' => \App\Http\Middleware\VerifierChangementMotDePasse::class,
            'cache.response' => \App\Http\Middleware\CacheResponse::class,
            'throttle.login' => \App\Http\Middleware\ThrottleLogin::class,
            'detect.cheating' => \App\Http\Middleware\DetectCheating::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // ✅ PLANIFICATION DES NOTIFICATIONS AUTOMATIQUES
        
        // Envoyer les notifications automatiques toutes les heures
        $schedule->command('notifications:envoyer')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Nettoyer les anciennes notifications tous les jours à 2h du matin
        $schedule->command('notifications:nettoyer --days=30')
                 ->dailyAt('02:00');
    })
    ->create();