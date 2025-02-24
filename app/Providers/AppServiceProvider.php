<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            $this->app->instance('path.storage', '/tmp/laravel/storage');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // SUPER ADMIN
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    
    }
}
