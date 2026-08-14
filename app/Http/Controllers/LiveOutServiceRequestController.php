<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestOffer;
use App\Models\StaffShortlist;
use App\Models\User;
use App\Models\Confirmation;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StaffAcceptedOfferNotification;
use App\Notifications\StaffRespondedNotification;
use App\Notifications\ShortlistReadyNotification;
use App\Services\StaffMatchingService;
use Illuminate\Support\Facades\Notification;

class LiveOutServiceRequestController extends Controller
{
    public function index()
    {
        $staff = Auth::user();

        // Get IDs of service requests this staff has already declined
        $declinedIds = ServiceRequestOffer::where('staff_id', $staff->id)
            ->where('status', 'declined')
            ->pluck('service_request_id');

        // Get IDs already fully assigned via confirmations
        $assignedIds = Confirmation::whereNotNull('house_help_id')
            ->pluck('service_request_id');

        // Fetch available live-out service requests
        $serviceRequests = ServiceRequest::whereIn('status', ['confirmed', 'active'])
            ->whereNotIn('id', $declinedIds)
            ->whereNotIn('id', $assignedIds)
            ->with(['user', 'offers' => function ($query) use ($staff) {
                $query->where('staff_id', $staff->id);
            }])
            ->latest()
            ->paginate(10);

        return view('applicant.liveOutServiceRequests', compact('staff', 'serviceRequests'));
    }

    public function accept($id)
    {
        $staff = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        // Verify the service request is in an acceptable state
        if (!in_array($serviceRequest->status, ['confirmed', 'active'])) {
            return redirect()->back()->with('error', 'This service request is no longer available.');
        }

        // Create or update the offer record
        $offer = ServiceRequestOffer::firstOrNew([
            'service_request_id' => $serviceRequest->id,
            'staff_id' => $staff->id,
        ]);

        if ($offer->exists && $offer->status === 'accepted') {
            return redirect()->back()->with('info', 'You have already accepted this request.');
        }

        $offer->status = 'accepted';
        $offer->responded_at = now();
        $offer->save();

        // Notify the client via email + in-app
        try {
            $client = User::find($serviceRequest->user_id);
            if ($client) {
                $client->notify(new StaffAcceptedOfferNotification($staff, $serviceRequest->id, 'live-out'));
            }
        } catch (\Exception $e) {
            \Log::error('LiveOutOffer: Failed to notify client: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'You have accepted this service request. The client has been notified.');
    }

    public function decline($id)
    {
        $staff = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        $offer = ServiceRequestOffer::firstOrNew([
            'service_request_id' => $serviceRequest->id,
            'staff_id' => $staff->id,
        ]);

        if ($offer->exists && $offer->status === 'accepted') {
            return redirect()->back()->with('error', 'You cannot decline a request you have already accepted.');
        }

        $offer->status = 'declined';
        $offer->responded_at = now();
        $offer->save();

        return redirect()->back()->with('success', 'You have declined this service request.');
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING FLOW - STAFF SHORTLIST OFFERS
    |--------------------------------------------------------------------------
    */

    public function shortlistOffers()
    {
        $staff = Auth::user();

        $offers = StaffShortlist::where('staff_id', $staff->id)
            ->whereIn('status', ['notified', 'accepted', 'shortlisted', 'selected'])
            ->with(['serviceRequest'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('applicant.shortlist-offers', compact('staff', 'offers'));
    }

    public function acceptShortlistOffer($id)
    {
        $staff = Auth::user();
        $shortlist = StaffShortlist::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        if ($shortlist->status !== 'notified') {
            return redirect()->back()->with('info', 'You have already responded to this offer.');
        }

        $shortlist->markAccepted();

        // Notify admins that staff accepted
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            Notification::send($admins, new StaffRespondedNotification($staff, $shortlist->service_request_id, 'accepted'));
        } catch (\Exception $e) {
            \Log::error('ShortlistAccept: Failed to notify admins: ' . $e->getMessage());
        }

        $serviceRequest = $shortlist->serviceRequest;
        $acceptedCount = $serviceRequest->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted'])
            ->count();

        // Tell the client on the FIRST acceptance.
        //
        // This previously fired only once three staff had accepted, but
        // BookingController::showStaffMatching sends the client to their
        // shortlist as soon as ANY staff accepts. With one or two acceptances
        // the shortlist was viewable and the client was never told - while this
        // method still returned "The client will be notified".
        //
        // ShortlistReadyNotification is used rather than
        // StaffAcceptedOfferNotification because the latter includes the staff
        // member's phone and email, which this flow withholds until the booking
        // is paid for (see ServiceRequest::isStaffDetailsVisible).
        if ($acceptedCount === 1 && $serviceRequest->user) {
            try {
                $serviceRequest->user->notify(new ShortlistReadyNotification($serviceRequest));
            } catch (\Exception $e) {
                \Log::error('ShortlistAccept: Failed to notify client: ' . $e->getMessage());
            }
        }

        // Promote to the curated client shortlist once enough have accepted.
        if ($acceptedCount >= 3) {
            $matchingService = app(StaffMatchingService::class);
            $matchingService->promoteToClientShortlist($serviceRequest);
        }

        return redirect()->back()->with('success', 'You have accepted this offer. The client will be notified.');
    }

    public function declineShortlistOffer($id)
    {
        $staff = Auth::user();
        $shortlist = StaffShortlist::where('id', $id)
            ->where('staff_id', $staff->id)
            ->firstOrFail();

        if ($shortlist->status !== 'notified') {
            return redirect()->back()->with('error', 'You cannot change your response.');
        }

        $shortlist->markDeclined();

        // Notify admins that staff declined
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            Notification::send($admins, new StaffRespondedNotification($staff, $shortlist->service_request_id, 'declined'));
        } catch (\Exception $e) {
            \Log::error('ShortlistDecline: Failed to notify admins: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'You have declined this offer.');
    }
}
