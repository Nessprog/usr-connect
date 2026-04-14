<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Force le schéma HTTPS si on est derrière un tunnel
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // 2. Force l'URL racine pour que Laravel ne devine pas 'usr-connect.test'
        if (str_contains(request()->header('host'), 'ngrok-free.dev')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }
    }
}
