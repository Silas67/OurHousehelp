<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApplicantCompletion
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->account_type === 'applicant' || $user->account_type === 'staff') {
            // Approved staff can always access the dashboard
            if ($user->approval_status === 'approved') {
                return $next($request);
            }

            // Check if guarantor forms and terms are completed
            if (!$user->guarantors_forms_uploaded || !$user->guarantors_forms_uploaded_2 || !$user->terms_accepted) {
                return redirect()->route('here')
                    ->with('error', 'Please complete the required registration steps to access the dashboard.');
            }

            // If guarantors and terms are done, mark form as completed automatically
            if (!$user->form_completed) {
                $user->form_completed = true;
                $user->save();
            }
        }

        return $next($request);
    }
}
