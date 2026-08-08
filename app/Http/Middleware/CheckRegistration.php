<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RegistrationService;

class CheckRegistration
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if ($user && !$user->isRegistrationActive()) {
            // Redirect to registration payment page if registration is expired or inactive
            if ($request->route()->getName() !== 'registration.payment') {
                return redirect()->route('registration.payment')
                    ->with('warning', 'Your registration has expired. Please renew to continue using our services.');
            }
        }

        return $next($request);
    }
}