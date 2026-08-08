<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Confirmation;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Notifications\StaffAcceptedOfferNotification;


class ConfirmationController extends Controller
{
    public function show($id)
    {
        // Fetch the service request by ID
        $serviceRequest = ServiceRequest::findOrFail($id);

        // Fetch the confirmation related to the service request
        // If no confirmation exists yet, create a new record for it
        $confirmation = Confirmation::firstOrCreate(
            ['service_request_id' => $id],
            ['admin_id' => auth()->id(), 'status' => 'pending']
        );

        // Return the confirmation view with the necessary data
        return view('admin.confirm', compact('serviceRequest', 'confirmation'));
    }


    public function updateSwitch(Request $request, $confirmationId)
    {
        // Find the confirmation by its ID
        $confirmation = Confirmation::findOrFail($confirmationId);

        // Update the relevant switch field
        switch ($request->switch) {
            case 'nin_verified':
                $confirmation->nin_verified = $request->value;
                break;
            case 'payment_verified':
                $confirmation->payment_verified = $request->value;
                break;
            case 'client_contacted':
                $confirmation->client_contacted = $request->value;
                break;
            case 'other_checks_completed':
                $confirmation->other_checks_completed = $request->value;
                break;
            case 'status':
                $confirmation->status = $request->value;
                break;
        }

        // Save the changes to the database
        $confirmation->save();

        return response()->json(['message' => 'Switch status updated successfully']);
    }




    public function showAssignForm($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $confirmation = Confirmation::where('service_request_id', $serviceRequest->id)->first();

        // Fetch house helps and assistants where availability is true, account_type is 'applicant', and applicant_type is 'both' or 'live_out'
        $houseHelps = User::where('availability', true)
            ->whereIn('account_type', ['applicant', 'staff'])
            ->whereIn('applicant_type', ['semi-live-in', 'live-out'])
            ->where('approval_status', 'approved') // Changed to 'where'
            ->get();

        $assistants = User::where('availability', true)
            ->whereIn('account_type', ['applicant', 'staff'])
            ->whereIn('applicant_type', ['semi-live-in', 'live-out'])
            ->where('approval_status', 'approved') // Changed to 'where'
            ->get();

        return view(
            'admin.assign-house-help',
            compact(
                'confirmation',
                'houseHelps',
                'assistants'
            )
        );
    }



    public function assignHouseHelp(Request $request, $id)
    {
        // Find the confirmation
        $confirmation = Confirmation::findOrFail($id);

        // Update the confirmation with house help and assistant
        $confirmation->update([
            'house_help_id' => $request->input('house_help_id'),
            'house_help_assist_id' => $request->input('house_help_assist_id'),
            'status' => 'active', // Set the confirmation status to active
            'assigned_at' => now(),
        ]);

        // Notify the client that a staff member has been assigned
        try {
            $serviceRequest = ServiceRequest::find($confirmation->service_request_id);
            $staff = User::find($request->input('house_help_id'));

            if ($serviceRequest && $staff) {
                $client = User::find($serviceRequest->user_id);
                if ($client) {
                    $client->notify(new StaffAcceptedOfferNotification($staff, $serviceRequest->id, 'live-out'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send client notification: ' . $e->getMessage());
        }

        return response()->json(['message' => 'House help assigned successfully']);
    }
}
