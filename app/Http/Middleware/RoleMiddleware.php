<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 🔥 FIX UTAMA DI SINI
        $roles = collect($roles)
            ->flatMap(fn ($r) => explode(',', $r))
            ->map(fn ($r) => trim($r))
            ->toArray();

        if (!auth()->user()->hasRole($roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

