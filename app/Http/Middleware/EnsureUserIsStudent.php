<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and if the user is a student
        if (!auth()->check() || !auth()->user()->isStudent()) {
            // If user is not a student, return a 404 response
            abort(404);
        }

        return $next($request);
    }
}
