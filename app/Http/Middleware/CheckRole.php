<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user has the required role
        if ($request->user() && $request->user()->role == $role) {
            return $next($request);
        }

        // Redirect 
        abort(403, 'Unauthorized.');
    }
}
