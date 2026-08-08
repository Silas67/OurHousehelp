<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has an appropriate role
        if (Auth::check()) {
            // Allow access if the user is either an Admin (role 1) or Super Admin (role 2)
            if (in_array(Auth::user()->user_role, [1, 2])) {
                return $next($request);
            }
        }

        // For any other case, redirect back
        return redirect('/');
    }
}
