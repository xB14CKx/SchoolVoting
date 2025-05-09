<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Admin Gate
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Student Gate
        Gate::define('is-student', function (User $user) {
            return $user->isStudent();
        });
    }
}
