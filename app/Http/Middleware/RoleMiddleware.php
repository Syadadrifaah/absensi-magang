<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:Admin') or ->middleware('role:Admin,Manager')
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user()?->role || $request->user()->role->name !== $role) {
            abort(403);
        }

        return $next($request);
    }

}
