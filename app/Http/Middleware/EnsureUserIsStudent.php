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
        if (auth()->check() && !auth()->user()->isStudent()) {
            return redirect()->route('practice-classes.index')->with('error', 'Access restricted to students only.');
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Access restricted to students only.');
        }

        return $next($request);
    }
}
