<?php

namespace App\Http\Controllers;

use App\Models\CorporateInquiry;
use App\Models\User;
use App\Notifications\NewCorporateInquiryNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CorporateInquiryController extends Controller
{
    /**
     * Show the "For Your Company" lead-capture form.
     *
     * Public page - no auth required. A corporate inquiry isn't tied to a
     * user account; it's a lead your team follows up on directly.
     */
    public function showForm()
    {
        return view('corporate');
    }

    /**
     * Submit a corporate staffing inquiry.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'work_email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'staff_count' => 'required|integer|min:1|max:500',
            'staff_types' => 'required|array|min:1',
            'staff_types.*' => 'string|in:driver,personal_assistant,housekeeper,chef,nanny,security,other',
            'preferred_start_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:2000',
        ]);

        $inquiry = CorporateInquiry::create($validated);

        // Same pattern used throughout BookingController for admin
        // notifications: a failure to notify shouldn't block the inquiry
        // itself from being recorded and available in the admin panel.
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewCorporateInquiryNotification($inquiry));
            }
        } catch (\Exception $e) {
            Log::error('CorporateInquiry: Failed to notify admins of new inquiry #' . $inquiry->id . ': ' . $e->getMessage());
        }

        return redirect()->route('corporate')
            ->with('success', 'Thank you — your inquiry has been received. Our team will reach out to '
                . $validated['contact_name'] . ' at ' . $validated['work_email'] . ' within one business day.');
    }
}
