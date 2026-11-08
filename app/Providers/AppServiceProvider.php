<?php

namespace App\Providers;

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
        if (
            app()->environment('production') ||
            config('app.env') === 'production' || 
            env('APP_ENV') === 'production' || 
            str_starts_with(config('app.url', ''), 'https://') ||
            request()->isSecure() ||
            request()->header('X-Forwarded-Proto') === 'https' ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['HTTP_CF_VISITOR']) && str_contains($_SERVER['HTTP_CF_VISITOR'], 'https'))
        ) {
            URL::forceScheme('https');
        }
    }
}
