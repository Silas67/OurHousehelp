<?php

namespace App\Services;

class StaffSalaryService
{
    protected BookingCostService $costService;

    public function __construct()
    {
        $this->costService = new BookingCostService();
    }

    /**
     * Calculate staff salary from a service request model.
     */
    public function calculateFromServiceRequest($serviceRequest): array
    {
        // Get service IDs from service names stored in serviceName
        $serviceNames = is_array($serviceRequest->serviceName)
            ? $serviceRequest->serviceName
            : (json_decode($serviceRequest->serviceName, true) ?? []);

        $serviceIds = \App\Models\Service::whereIn('service_name', $serviceNames)
            ->pluck('id')
            ->toArray();

        if (empty($serviceIds)) {
            return [
                'staff_salary' => 0,
                'client_cost' => 0,
                'discount_percentage' => 0,
                'platform_fee' => 0,
            ];
        }

        // Find package ID by exact name match
        $packageId = \App\Models\Package::where('name', $serviceRequest->package)
            ->value('id');

        // Fallback: match by days_per_week
        if (!$packageId && $serviceRequest->days_per_week) {
            $packageId = \App\Models\Package::where('days_per_week', $serviceRequest->days_per_week)
                ->value('id');
        }

        if (!$packageId) {
            return [
                'staff_salary' => 0,
                'client_cost' => 0,
                'discount_percentage' => 0,
                'platform_fee' => 0,
            ];
        }

        // feature_cost stores the apartment cost for cleaning service
        // Pass it directly to avoid re-looking up the apartment
        $featureCost = (float) ($serviceRequest->feature_cost ?? 0);

        if ($featureCost === 0.0 && $serviceRequest->features) {
            $features = is_array($serviceRequest->features)
                ? $serviceRequest->features
                : json_decode($serviceRequest->features, true);
            $apartmentId = $features['apartment_type_id'] ?? null;
            if ($apartmentId) {
                $featureCost = (float) (\App\Models\Apartments::find($apartmentId)?->cost ?? 0);
            }
        }

        return $this->costService->calculateStaffSalaryWithFeatureCost(
            $serviceIds,
            $packageId,
            $serviceRequest->management_plan ?? 'client-managed',
            $serviceRequest->duration_weeks ?? 4,
            $featureCost
        );
    }

    /**
     * Quick salary lookup — returns just the monthly salary number.
     */
    public function getStaffSalary($serviceRequest): int
    {
        $data = $this->calculateFromServiceRequest($serviceRequest);
        return $data['staff_salary'] ?? 0;
    }
}
