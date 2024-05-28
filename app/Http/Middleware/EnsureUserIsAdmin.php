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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is logged in and if the user is an admin
        if (auth()->check() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Access restricted to administrator only.');
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Access restricted to administrator only.');
        }

        return $next($request);
    }
}
