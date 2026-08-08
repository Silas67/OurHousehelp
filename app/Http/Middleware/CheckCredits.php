<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCredits
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Only check credits for clients
        if ($user->account_type !== 'client') {
            return $next($request);
        }

        // Allow dashboard, profile, credits pages — no credit check needed
        $allowedRoutes = ['dashboard', 'credits.index', 'credits.purchase', 'credits.pay', 'clientprofile', 'bookings', 'paymenthistory'];
        $currentRoute = $request->route()?->getName();
        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // If they have credits, proceed
        if ($user->hasCredits()) {
            return $next($request);
        }

        // No credits — store the intended URL and redirect to buy page
        session(['credit_intended_url' => $request->fullUrl()]);

        return redirect()->route('credits.index')
            ->with('error', 'You need at least 1 booking credit to proceed. Please purchase credits to continue.');
    }
}
