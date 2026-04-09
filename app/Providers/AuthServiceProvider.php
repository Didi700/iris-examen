<?php

namespace App\Providers;

use App\Models\SessionExamen;
use App\Policies\SessionExamenPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SessionExamen::class => SessionExamenPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}