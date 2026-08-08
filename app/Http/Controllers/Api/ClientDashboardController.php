<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = ServiceRequest::with('selectedStaff:id,name')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'delete')
            ->orderByDesc('created_at')
            ->get();

        $statusCounts = $bookings->countBy('status');

        $stats = [
            'active_bookings' => $statusCounts->only(['pending', 'confirmed', 'active'])->sum(),
            'completed' => $statusCounts->get('completed', 0),
            'credits_remaining' => $user->getCreditBalance(),
        ];

        $items = $bookings->map(function (ServiceRequest $booking) {
            return [
                'id' => $booking->id,
                'staff_name' => $booking->selectedStaff?->name,
                'role' => $this->buildRoleLabel($booking),
                'date' => optional($booking->preferred_start_date)->toDateString(),
                'status' => $booking->status,
                'is_urgent' => (bool) $booking->is_urgent,
            ];
        });

        return response()->json([
            'stats' => $stats,
            'bookings' => $items,
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
