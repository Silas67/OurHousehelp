<?php

namespace App\Services;

use App\Models\ExtraServiceCost;
use App\Models\ManagementPlan;
use App\Models\Package;
use App\Models\Service;
use App\Models\Apartments;

class BookingCostService
{
    /**
     * Duration discount table (weeks → discount percentage)
     */
    protected array $durationDiscounts = [
        12 => 8,
        8  => 5,
        4  => 0,
        1  => 0,
    ];

    /**
     * Calculate the base service cost.
     * Main service = full price, secondary services = halved.
     * Package cost added on top.
     */
    public function calculateServiceCost(
        array $serviceIds,
        $packageId,
        ?int $apartmentTypeId = null
    ): array {
        $services = Service::whereIn('id', $serviceIds)->get();
        $package = Package::find($packageId);
        $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

        // Apartment cost for cleaning (PRS) service
        $apartmentCost = 0;
        $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
        if ($containsPRS && $apartmentTypeId) {
            $apartment = Apartments::find($apartmentTypeId);
            $apartmentCost = (float) ($apartment?->cost ?? 0);
        }

        // Effective cost per service
        $effectiveCosts = [];
        foreach ($services as $s) {
            $effectiveCosts[$s->id] = $s->service_type === 'PRS'
                ? ($apartmentCost ?: (float) $s->base_cost)
                : (float) $s->base_cost;
        }

        // Main service = highest cost (full price)
        $mainServiceId = !empty($effectiveCosts)
            ? array_keys($effectiveCosts, max($effectiveCosts))[0]
            : null;
        $baseCost = $mainServiceId ? $effectiveCosts[$mainServiceId] : 0;

        // Secondary services = halved
        $extraContributionTotal = 0.0;
        $breakdown = [];

        foreach ($services as $s) {
            if ($s->id == $mainServiceId) {
                $breakdown[] = [
                    'service' => $s->service_name,
                    'cost' => $baseCost,
                    'type' => 'main',
                ];
                continue;
            }

            switch ($s->service_type) {
                case 'PRS':
                    $cost = ($apartmentCost ?: (float) $s->base_cost) / 2;
                    break;
                case 'PMS':
                    $cost = ((float) $s->base_cost) / 2;
                    break;
                case 'PSS':
                default:
                    $cost = (float) $extraServiceCost;
                    break;
            }

            $extraContributionTotal += $cost;
            $breakdown[] = [
                'service' => $s->service_name,
                'cost' => $cost,
                'type' => 'secondary',
            ];
        }

        $packageCost = (float) ($package?->cost ?? 0);
        $grandTotal = $baseCost + $packageCost + $extraContributionTotal;

        return [
            'base_cost' => $baseCost,
            'extra_service_cost' => $extraContributionTotal,
            'package_cost' => $packageCost,
            'service_charge' => 0,
            'grand_cost' => $grandTotal,
            'breakdown' => $breakdown,
            'apartment_cost' => $apartmentCost,
        ];
    }

    /**
     * Calculate staff salary and client cost based on management plan.
     *
     * Staff salary = always the monthly rate (never discounted — staff always gets full pay)
     * Client managed: pays staff monthly rate directly, no discount (discount not relevant since no upfront payment)
     * Company managed: pays total for full duration upfront with discount applied to total
     *
     * One-off (duration_weeks = 1): no package cost, no discount, single visit price
     */
    public function calculateStaffSalary(
        array $serviceIds,
        $packageId,
        string $managementPlan,
        int $durationWeeks = 4,
        ?int $apartmentTypeId = null
    ): array {
        $isOneOff = $durationWeeks === 1;
        $isClientManaged = $managementPlan === 'client-managed';

        // For one-off, don't include package cost (no recurring frequency)
        if ($isOneOff) {
            $costs = $this->calculateServiceCost($serviceIds, null, $apartmentTypeId);
        } else {
            $costs = $this->calculateServiceCost($serviceIds, $packageId, $apartmentTypeId);
        }

        $monthlyRate = $costs['grand_cost']; // This is always the base monthly rate
        $months = $isOneOff ? 1 : round($durationWeeks / 4);

        // Staff always earns the full monthly rate — never discounted
        $staffSalary = round($monthlyRate);

        // Management plan markup (company managed only)
        $markup = $this->getPlatformMarkup($managementPlan);
        $monthlyClientRate = round($monthlyRate * (1 + $markup / 100));

        if ($isOneOff || $isClientManaged) {
            // One-off or client managed — no discount, no total calculation
            // Client managed: client pays staff monthly directly, discount irrelevant
            return [
                'grand_cost' => round($monthlyRate),
                'monthly_rate' => round($monthlyRate),
                'months' => $months,
                'discount_percentage' => 0,
                'discount_amount' => 0,
                'total_before_discount' => round($monthlyClientRate * $months),
                'total_after_discount' => round($monthlyClientRate * $months),
                'staff_salary' => $staffSalary,
                'platform_markup_percentage' => $markup,
                'platform_fee' => round($monthlyRate * ($markup / 100)),
                'client_cost' => round($monthlyClientRate),
                'client_total' => round($monthlyClientRate * $months),
                'management_plan' => $managementPlan,
                'duration_weeks' => $durationWeeks,
                'is_one_off' => $isOneOff,
                'breakdown' => $costs['breakdown'],
                'base_cost' => $costs['base_cost'],
                'extra_service_cost' => $costs['extra_service_cost'],
                'package_cost' => $costs['package_cost'],
            ];
        }

        // Company managed — discount applies to TOTAL not monthly rate
        $discountPercentage = $this->getDurationDiscount($durationWeeks);
        $totalBeforeDiscount = $monthlyClientRate * $months;
        $discountAmount = round($totalBeforeDiscount * ($discountPercentage / 100));
        $totalAfterDiscount = $totalBeforeDiscount - $discountAmount;

        // Monthly equivalent after discount (for display)
        $monthlyEquivalent = round($totalAfterDiscount / $months);

        return [
            'grand_cost' => round($monthlyRate),
            'monthly_rate' => round($monthlyRate),
            'months' => $months,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'total_before_discount' => round($totalBeforeDiscount),
            'total_after_discount' => round($totalAfterDiscount),
            'staff_salary' => $staffSalary,
            'platform_markup_percentage' => $markup,
            'platform_fee' => round($monthlyRate * ($markup / 100)),
            'client_cost' => round($monthlyClientRate),
            'client_total' => round($totalAfterDiscount),
            'monthly_equivalent' => $monthlyEquivalent,
            'management_plan' => $managementPlan,
            'duration_weeks' => $durationWeeks,
            'is_one_off' => false,
            'breakdown' => $costs['breakdown'],
            'base_cost' => $costs['base_cost'],
            'extra_service_cost' => $costs['extra_service_cost'],
            'package_cost' => $costs['package_cost'],
        ];
    }

    /**
     * Calculate staff salary using feature_cost directly instead of apartment lookup.
     * Used when reading from service_requests where apartment cost is stored as feature_cost.
     */
    public function calculateStaffSalaryWithFeatureCost(
        array $serviceIds,
        $packageId,
        string $managementPlan,
        int $durationWeeks = 4,
        float $featureCost = 0
    ): array {
        $services = \App\Models\Service::whereIn('id', $serviceIds)->get();
        $package = \App\Models\Package::find($packageId);
        $extraServiceCost = \App\Models\ExtraServiceCost::first()?->cost ?? 12000;

        // Use feature_cost as apartment cost for PRS (cleaning) service
        $apartmentCost = $featureCost;

        // Effective cost per service
        $effectiveCosts = [];
        foreach ($services as $s) {
            if ($s->service_type === 'PRS') {
                $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
            } else {
                $effectiveCosts[$s->id] = (float) $s->base_cost;
            }
        }

        // Main service = highest cost
        $mainServiceId = !empty($effectiveCosts)
            ? array_keys($effectiveCosts, max($effectiveCosts))[0]
            : null;
        $baseCost = $mainServiceId ? $effectiveCosts[$mainServiceId] : 0;

        // Secondary services halved
        $extraContributionTotal = 0.0;
        $breakdown = [];

        foreach ($services as $s) {
            if ($s->id == $mainServiceId) {
                $breakdown[] = ['service' => $s->service_name, 'cost' => $baseCost, 'type' => 'main'];
                continue;
            }
            switch ($s->service_type) {
                case 'PRS':
                    $cost = ($apartmentCost ?: (float) $s->base_cost) / 2;
                    break;
                case 'PMS':
                    $cost = ((float) $s->base_cost) / 2;
                    break;
                case 'PSS':
                default:
                    $cost = (float) $extraServiceCost;
                    break;
            }
            $extraContributionTotal += $cost;
            $breakdown[] = ['service' => $s->service_name, 'cost' => $cost, 'type' => 'secondary'];
        }

        $packageCost = $durationWeeks === 1 ? 0.0 : (float) ($package?->cost ?? 0);
        $monthlyRate = $baseCost + $packageCost + $extraContributionTotal;
        $months = $durationWeeks === 1 ? 1 : round($durationWeeks / 4);
        $isClientManaged = $managementPlan === 'client-managed';
        $isOneOff = $durationWeeks === 1;

        $staffSalary = round($monthlyRate);
        $markup = $this->getPlatformMarkup($managementPlan);
        $monthlyClientRate = round($monthlyRate * (1 + $markup / 100));

        if ($isOneOff || $isClientManaged) {
            return [
                'grand_cost' => round($monthlyRate),
                'monthly_rate' => round($monthlyRate),
                'months' => $months,
                'discount_percentage' => 0,
                'discount_amount' => 0,
                'total_before_discount' => round($monthlyClientRate * $months),
                'total_after_discount' => round($monthlyClientRate * $months),
                'staff_salary' => $staffSalary,
                'platform_markup_percentage' => $markup,
                'platform_fee' => round($monthlyRate * ($markup / 100)),
                'client_cost' => round($monthlyClientRate),
                'client_total' => round($monthlyClientRate * $months),
                'management_plan' => $managementPlan,
                'duration_weeks' => $durationWeeks,
                'is_one_off' => $isOneOff,
                'breakdown' => $breakdown,
                'base_cost' => $baseCost,
                'extra_service_cost' => $extraContributionTotal,
                'package_cost' => $packageCost,
            ];
        }

        $discountPercentage = $this->getDurationDiscount($durationWeeks);
        $totalBeforeDiscount = $monthlyClientRate * $months;
        $discountAmount = round($totalBeforeDiscount * ($discountPercentage / 100));
        $totalAfterDiscount = $totalBeforeDiscount - $discountAmount;

        return [
            'grand_cost' => round($monthlyRate),
            'monthly_rate' => round($monthlyRate),
            'months' => $months,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'total_before_discount' => round($totalBeforeDiscount),
            'total_after_discount' => round($totalAfterDiscount),
            'staff_salary' => $staffSalary,
            'platform_markup_percentage' => $markup,
            'platform_fee' => round($monthlyRate * ($markup / 100)),
            'client_cost' => round($monthlyClientRate),
            'client_total' => round($totalAfterDiscount),
            'monthly_equivalent' => round($totalAfterDiscount / $months),
            'management_plan' => $managementPlan,
            'duration_weeks' => $durationWeeks,
            'is_one_off' => false,
            'breakdown' => $breakdown,
            'base_cost' => $baseCost,
            'extra_service_cost' => $extraContributionTotal,
            'package_cost' => $packageCost,
        ];
    }

    /**
     * Get duration discount percentage based on number of weeks.
     */
    public function getDurationDiscount(int $durationWeeks): float
    {
        foreach ($this->durationDiscounts as $weeks => $discount) {
            if ($durationWeeks >= $weeks) {
                return $discount;
            }
        }
        return 0;
    }

    /**
     * Get platform markup percentage for a management plan.
     * Company managed = 20%, Client managed = 0%
     */
    public function getPlatformMarkup(string $planSlug): float
    {
        $plan = ManagementPlan::where('slug', $planSlug)->first();

        // Use platform_markup column if it exists, fallback to logic
        if ($plan && isset($plan->platform_markup)) {
            return (float) $plan->platform_markup;
        }

        // Fallback
        return $planSlug === 'company-managed' ? 20.0 : 0.0;
    }

    /**
     * Get the cost for a management plan (legacy — kept for compatibility).
     */
    public function getManagementPlanCost(string $planSlug): float
    {
        $plan = ManagementPlan::where('slug', $planSlug)->first();
        return (float) ($plan?->base_price ?? 0);
    }

    /**
     * Get express matching cost.
     */
    public function getExpressMatchingCost(): float
    {
        return 10000.00;
    }

    /**
     * Get monthly protection plan cost.
     */
    public function getMonthlyProtectionCost(): float
    {
        return 10000.00;
    }

    /**
     * Calculate the total booking cost (legacy — kept for compatibility).
     */
    public function calculateTotalCost(
        float $grandServiceCost,
        string $managementPlan,
        bool $expressMatching = false,
        bool $monthlyProtection = false
    ): array {
        $expressMatchingCost = $expressMatching ? $this->getExpressMatchingCost() : 0;
        $monthlyProtectionCost = $monthlyProtection ? $this->getMonthlyProtectionCost() : 0;
        $total = $grandServiceCost + $expressMatchingCost;

        return [
            'service_cost' => $grandServiceCost,
            'management_plan_cost' => 0,
            'express_matching_cost' => $expressMatchingCost,
            'monthly_protection_cost' => $monthlyProtectionCost,
            'total' => $total,
            'total_with_protection' => $total + $monthlyProtectionCost,
        ];
    }

    /**
     * Calculate installment amounts (50/50 split).
     */
    public function calculateInstallmentAmounts(float $total): array
    {
        $firstHalf = round($total / 2, 2);
        $secondHalf = $total - $firstHalf;
        return [
            'first_payment' => $firstHalf,
            'second_payment' => $secondHalf,
            'total' => $total,
        ];
    }

    /**
     * Generate a full cost breakdown for display.
     */
    public function generateCostBreakdown(
        array $serviceIds,
        $packageId,
        string $managementPlan,
        bool $expressMatching = false,
        bool $monthlyProtection = false,
        ?int $apartmentTypeId = null,
        int $durationWeeks = 4
    ): array {
        $salary = $this->calculateStaffSalary(
            $serviceIds,
            $packageId,
            $managementPlan,
            $durationWeeks,
            $apartmentTypeId
        );

        $installment = $this->calculateInstallmentAmounts(
            $managementPlan === 'company-managed'
                ? $salary['client_cost']
                : $salary['staff_salary']
        );

        return array_merge($salary, ['installment' => $installment]);
    }
}
