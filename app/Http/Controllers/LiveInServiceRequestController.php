<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Apartments;
use App\Models\Package;
use App\Models\Discount;
use App\Models\User;
use App\Models\HouseHelp;
use App\Models\LiveInServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\RequestClick;
use App\Notifications\StaffAcceptedOfferNotification;


class LiveInServiceRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'househelp_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
        ]);

        $client = Auth::user();

        // Ensure the househelp exists and is of the correct account type
        $househelp = User::where('id', $request->househelp_id)
            ->whereIn('account_type', ['applicant', 'staff'])
            ->first();

        if (!$househelp) {
            return redirect()->back()->with('error', 'Invalid househelp selection.');
        }

        // Fetch the latest approved payment plan
        $payment = Payment::where('user_id', $client->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'You need an active subscription to request service.');
        }

        // Check if the plan has expired
        if ($payment->expiry_date && Carbon::now()->greaterThan($payment->expiry_date)) {
            return redirect()->back()->with('error', 'Your subscription has expired. Please renew.');
        }

        // Check if searches are exhausted
        if ($payment->used_searches >= $payment->num_searches) {
            return redirect()->back()->with('error', 'You have reached your maximum request limit. Upgrade your plan.');
        }

        // Create a new service request
        $serviceRequest = new LiveInServiceRequest();
        $serviceRequest->client_id = $client->id;
        $serviceRequest->househelp_id = $househelp->id;
        $serviceRequest->message = $request->message;
        $serviceRequest->save();

        // Track the request click
        RequestClick::create([
            'user_id' => $client->id,
            'househelp_id' => $househelp->id,
            'payment_id' => $payment->id,
        ]);

        // **Increment used searches**
        $payment->increment('used_searches');

        return redirect()->back()->with('success', 'Service request has been sent.');
    }


    // public function store(Request $request)
    // {
    //     dd($request->all()); // Dump all incoming request data
    // }





    public function cancelLiveinserviceBooking($id)
    {
        // Find the live-in service request by its ID and ensure the authenticated user owns it
        $request = LiveInServiceRequest::where('id', $id)
            ->where('client_id', auth()->id())
            ->first();

        if (!$request) {
            return redirect()->route('liveinjobrequest')->with('error', 'Booking not found or you are not authorized.');
        }

        // Check if the request can be canceled (e.g., only if it's pending or accepted)
        if ($request->status == 'completed' || $request->status == 'cancelled') {
            return redirect()->route('liveinjobrequest')->with('error', 'This booking cannot be cancelled.');
        }

        // Update the status to cancelled
        $request->status = 'cancelled';
        $request->save();

        return redirect()->back()->with('success', 'Booking has been successfully cancelled.');
    }



    public function cancelRequest($id)
    {
        // Find the request by its ID
        $request = LiveInServiceRequest::find($id);

        if (!$request || $request->househelp_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Request not found or you are not authorized.');
        }

        // Only allow accepting if the status is 'pending'
        if ($request->status == 'accepted') {
            $request->status = 'cancelled';
            $request->save();
        }

        return redirect()->back()->with('success', 'Request has been canceled.');
    }



    public function acceptRequest($id)
    {
        // Find the request by its ID
        $request = LiveInServiceRequest::find($id);

        if (!$request || $request->househelp_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Request not found or you are not authorized.');
        }

        // Only allow accepting if the status is 'pending'
        if ($request->status == 'pending') {
            $request->status = 'accepted';
            $request->save();

            // Notify the client that staff has accepted
            try {
                $client = User::find($request->client_id);
                $staff = auth()->user();

                if ($client && $staff) {
                    $client->notify(new StaffAcceptedOfferNotification($staff, $request->id, 'live-in'));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send client notification: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Request has been accepted.');
    }



    public function rejectRequest($id)
    {
        // Find the request by its ID
        $request = LiveInServiceRequest::find($id);

        if (!$request || $request->househelp_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Request not found or you are not authorized.');
        }

        // Only allow rejecting if the status is 'pending'
        if ($request->status == 'pending') {
            $request->status = 'rejected';
            $request->save();
        }

        return redirect()->back()->with('success', 'Request has been rejected.');
    }
}
