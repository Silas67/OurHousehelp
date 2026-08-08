<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\StaffShortlist;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $jobsCompleted = ServiceRequest::where('selected_staff_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $matches = StaffShortlist::forStaff($user->id)
            ->whereIn('status', ['notified', 'shortlisted'])
            ->with('serviceRequest')
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'jobs_completed' => $jobsCompleted,
            'rating' => $user->average_rating,
            'pending_offers' => $matches->count(),
        ];

        $verification = [
            ['key' => 'id_verified', 'label' => 'ID Verification', 'verified' => (bool) $user->id_verified],
            ['key' => 'guarantors_forms_uploaded', 'label' => 'Guarantor Check', 'verified' => (bool) $user->guarantors_forms_uploaded],
            ['key' => 'address_confirmed', 'label' => 'Address Confirmation', 'verified' => (bool) $user->address_confirmed],
            ['key' => 'background_checked', 'label' => 'Background Check', 'verified' => (bool) $user->background_checked],
        ];

        $jobMatches = $matches
            ->filter(fn (StaffShortlist $match) => $match->serviceRequest !== null)
            ->map(function (StaffShortlist $match) {
                $booking = $match->serviceRequest;

                return [
                    'id' => $match->id,
                    'service' => $this->buildRoleLabel($booking),
                    'area' => $booking->preferred_city ?? $booking->state_of_residence,
                    'pay' => $booking->grand_cost ? '₦' . number_format((float) $booking->grand_cost) : null,
                    'schedule' => $booking->applicant_type,
                    'status' => $match->status,
                ];
            })
            ->values();

        return response()->json([
            'stats' => $stats,
            'verification' => $verification,
            'job_matches' => $jobMatches,
        ]);
    }

    private function buildRoleLabel(ServiceRequest $booking): string
    {
        $services = is_array($booking->serviceName) ? $booking->serviceName : [];
        $serviceLabel = count($services) ? implode(' & ', $services) : null;

        $packageLabel = $booking->package ? trim(explode(':', $booking->package, 2)[0]) : null;

        if ($serviceLabel && $packageLabel) {
            return "{$serviceLabel} ({$packageLabel})";
        }

        return $serviceLabel ?? $packageLabel ?? 'Service Request';
    }
}
