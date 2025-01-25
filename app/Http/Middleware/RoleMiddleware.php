<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Ensure user is logged in
        if (! $request->user()) {
            abort(403);
        }

        if ($request->user()->role->slug !== $role) {
            abort(403, 'Not authorized');
        }

        return $next($request);
    }
}
