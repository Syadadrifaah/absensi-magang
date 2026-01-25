<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Role;

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
        //
        Paginator::useBootstrapFive();

        // Gate::define('role', function ($user, $roleName) {
        //     return $user->role && $user->role->name === $roleName;
        // });

        Gate::define('role', function ($user, $roleName) {
            dd(
                $user->role_id,
                optional($user->role)->name,
                $roleName
            );
        });

    }
}
