<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and if the user is a teacher
        if (auth()->check() && !auth()->user()->isTeacher()) {
            return redirect()->route('practice-classes.index')->with('error', 'Access restricted to teachers only.');

        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Access restricted to teachers only.');
        }

        return $next($request);
    }
}
