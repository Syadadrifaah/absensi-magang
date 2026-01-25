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

        Gate::define('role', function ($user, $roles) {
            if (!$user) return false;

            $allowed = is_array($roles) ? $roles : array_map('trim', explode(',', $roles));
            // normalize
            $allowed = array_filter(array_map(function ($r) {
                return $r === null ? null : (string) $r;
            }, $allowed));

            $roleName = strtolower(optional($user->role)->name ?? '');
            $roleId = $user->role_id ? (string) $user->role_id : null;

            // if role relation not loaded but role_id exists, attempt to resolve name
            if ($roleName === '' && $roleId !== null) {
                $r = Role::find((int) $roleId);
                if ($r) $roleName = strtolower($r->name);
            }

            foreach ($allowed as $token) {
                if ($token === '') continue;
                if (is_numeric($token)) {
                    if ($roleId !== null && $roleId === (string) $token) return true;
                } else {
                    if ($roleName !== '' && strtolower($token) === $roleName) return true;
                }
            }

            return false;
        });


    //    Gate::define('role', function ($user, $roleName) {
    //         dd(
    //             'USER:', $user->email,
    //             'ROLE USER:', optional($user->role)->name,
    //             'ROLE DIMINTA:', $roleName
    //         );
    //     });


    }
}
