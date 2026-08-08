<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApplicantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // If the user is an applicant, allow them to proceed
            if (Auth::user()->account_type === 'applicant' || Auth::user()->account_type === 'staff') {
                return $next($request);
            }
        }
        // For any other case, redirect back
        return redirect()->back();
    }
}
