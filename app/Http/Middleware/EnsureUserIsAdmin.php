<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * Blocks access to admin-only routes for anyone who isn't an admin.
     * Runs after the 'auth' middleware, so $request->user() is guaranteed
     * to exist here.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}