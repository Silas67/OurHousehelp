<?php

namespace App\Services;

use App\Models\ServiceRequest;
use App\Models\StaffShortlist;
use App\Models\User;
use App\Notifications\StaffMatchedNotification;
use App\Services\TermiiService;
use Illuminate\Support\Collection;

class StaffMatchingService
{

    /**
     * Find matching staff for a service request.
     * Returns up to 10 verified staff sorted by match score.
     */
    public function findMatchingStaff(ServiceRequest $serviceRequest): Collection
    {
        // Auto-expire stale notified shortlists older than 2 weeks
        \App\Models\StaffShortlist::whereHas('serviceRequest', function ($q) {
            $q->where('matching_status', 'matching')
                ->where('created_at', '<', now()->subDays(14));
        })->where('status', 'notified')
            ->update(['status' => 'expired']);

        $query = User::query()
            ->where('approval_status', 'approved')
            ->availableBy($serviceRequest->preferred_start_date ?? now()->addDays(7));

        // Apply account type logic

        if ($serviceRequest->applicant_type === 'live-in') {
            // Live-in → staff + applicant
            $query->whereIn('account_type', ['staff', 'applicant']);
        } else {
            // Live-out / semi-live-in → staff only
            $query->where('account_type', 'staff');
        }



        // Filter by state (required)
        if ($serviceRequest->state_of_residence) {
            $query->inState($serviceRequest->state_of_residence);
        }


        // Filter by specialties if service names are available
        $serviceNames = $serviceRequest->serviceName;


        // Exclude staff already shortlisted for this request
        $existingStaffIds = StaffShortlist::where('service_request_id', $serviceRequest->id)
            ->pluck('staff_id')
            ->toArray();
        if (!empty($existingStaffIds)) {
            $query->whereNotIn('id', $existingStaffIds);
        }

        $candidates = $query->get();

        // Score each candidate
        $scored = $candidates->map(function (User $staff) use ($serviceRequest) {
            $score = $this->calculateMatchScore($staff, $serviceRequest);
            $reasons = $this->getMatchReasons($staff, $serviceRequest);
            return [
                'staff' => $staff,
                'score' => $score,
                'reasons' => $reasons,
            ];
        });

        // Sort by score descending, take top 10
        return $scored->sortByDesc('score')->take(10)->values();
    }

    /**
     * Calculate a match score (0-100) for a staff member against a service request.
     */
    public function calculateMatchScore(User $staff, ServiceRequest $serviceRequest): int
    {
        $score = 0;

        // Location Match (0-30 points)
        $score += $this->scoreLocation($staff, $serviceRequest);

        // Verification Level (0-25 points)
        $score += $this->scoreVerification($staff);

        // Availability (0-20 points)
        $score += $this->scoreAvailability($staff, $serviceRequest);

        // Religion Match (0-10 points)
        $score += $this->scoreReligion($staff, $serviceRequest);

        // Experience (0-15 points)
        $score += $this->scoreExperience($staff);

        // Specialty Match (0-10 points)
        $score += $this->scoreSpecialties($staff, $serviceRequest);

        // Gender Match (0-10 points)
        $score += $this->scoreGender($staff, $serviceRequest);

        return min(100, $score);
    }

    /**
     * Create shortlist entries for matched staff and notify them.
     */
    public function createShortlist(ServiceRequest $serviceRequest, Collection $matchedStaff): void
    {
        foreach ($matchedStaff as $match) {
            StaffShortlist::create([
                'service_request_id' => $serviceRequest->id,
                'staff_id' => $match['staff']->id,
                'match_score' => $match['score'],
                'match_reasons' => $match['reasons'],
                'status' => 'notified',
                'notified_at' => now(),
            ]);
        }

        // Update service request matching status
        $serviceRequest->update(['matching_status' => 'matching']);
    }

    /**
     * Notify matched staff about the service request.
     */
    public function notifyMatchedStaff(ServiceRequest $serviceRequest): void
    {
        $shortlists = $serviceRequest->shortlistedStaff()->notified()->with('staff')->get();

        $termii = new TermiiService();
        $city = $serviceRequest->preferred_city ?? 'your area';
        $salaryService = new \App\Services\StaffSalaryService();
        $salary = $salaryService->getStaffSalary($serviceRequest);
        $salaryText = $salary ? '₦' . number_format($salary) . '/mth' : '';
        $services = is_array($serviceRequest->serviceName)
            ? implode(', ', $serviceRequest->serviceName)
            : implode(', ', json_decode($serviceRequest->serviceName, true) ?? []);
        $message = "OurHouseHelp: New {$services} job in {$city}! {$salaryText} for {$serviceRequest->duration_weeks} wks. Be first to accept: ourhousehelp.com/staff-shortlist-offers";

        $webPush = new WebPushService();

        foreach ($shortlists as $shortlist) {
            if ($shortlist->staff) {
                $shortlist->staff->notify(new StaffMatchedNotification($serviceRequest, $shortlist));

                if ($shortlist->staff->phone) {
                    $termii->send($shortlist->staff->phone, $message);
                }

                // Web push — inside loop, using $shortlist not $shortlists
                $webPush->sendToUser(
                    $shortlist->staff->id,
                    '🏠 New Job Available!',
                    "New {$services} job in {$city}! {$salaryText} — be the first to accept.",
                    url('/staff-shortlist-offers')
                );
            }
        }
    }

    /**
     * Get the client-visible shortlist (top 3-5 accepted staff).
     */
    public function getClientShortlist(ServiceRequest $serviceRequest): Collection
    {
        return $serviceRequest->shortlistedStaff()
            ->where(function ($query) {
                $query->where('status', 'accepted')
                    ->orWhere('status', 'shortlisted')
                    ->orWhere('status', 'selected');
            })
            ->where('client_visible', true)
            ->orderByDesc('match_score')
            ->with('staff')
            ->take(5)
            ->get();
    }

    /**
     * Promote accepted staff to client-visible shortlist.
     * Called when enough staff have accepted.
     */
    public function promoteToClientShortlist(ServiceRequest $serviceRequest): int
    {
        $accepted = $serviceRequest->shortlistedStaff()
            ->accepted()
            ->orderByDesc('match_score')
            ->take(5)
            ->get();

        $count = 0;
        foreach ($accepted as $shortlist) {
            $shortlist->markShortlisted();
            $count++;
        }

        if ($count >= 3) {
            $serviceRequest->update(['matching_status' => 'shortlisted']);
        }

        return $count;
    }

    // --- Private scoring methods ---

    private function scoreLocation(User $staff, ServiceRequest $serviceRequest): int
    {
        $requestCity = strtolower(trim($serviceRequest->preferred_city ?? ''));
        $requestState = strtolower(trim($serviceRequest->state_of_residence ?? ''));
        $staffCity = strtolower(trim($staff->city ?? ''));
        $staffState = strtolower(trim($staff->state_of_residence ?? ''));

        if ($requestCity && $staffCity && $requestCity === $staffCity) {
            return 30; // Same city
        }

        if ($requestState && $staffState && $requestState === $staffState) {
            return 15; // Same state, different city
        }

        return 0;
    }

    /**
     * Score the verification evidence held for a staff member (0-25).
     *
     * NOTE: this previously tested $staff->nin_verified, which is declared in
     * the model's $fillable and $casts but does not exist as a column on the
     * users table. Eloquent returned null for it, so the 8-point component
     * never awarded anything and every staff member scored 8 points below the
     * intended maximum. It now tests whether a NIN is actually recorded.
     *
     * address_confirmed and background_checked are real columns but are 0 for
     * every staff member - nothing in the application writes them. They are
     * kept here so scores rise automatically once a verification process
     * populates them.
     */
    private function scoreVerification(User $staff): int
    {
        $score = 0;

        if (filled($staff->nin)) {
            $score += 8;
        }
        if ($staff->guarantors_forms_uploaded) {
            $score += 7;
        }
        if ($staff->address_confirmed) {
            $score += 5;
        }
        if ($staff->background_checked) {
            $score += 5;
        }

        return $score;
    }

    private function scoreAvailability(User $staff, ServiceRequest $serviceRequest): int
    {
        $preferredDate = $serviceRequest->preferred_start_date ?? now()->addDays(7);

        if (!$staff->available_from || $staff->available_from->lte($preferredDate)) {
            return 20; // Available immediately or by requested date
        }

        $daysUntilAvailable = now()->diffInDays($staff->available_from);

        if ($daysUntilAvailable <= 3) {
            return 15;
        }
        if ($daysUntilAvailable <= 7) {
            return 10;
        }

        return 0;
    }

    private function scoreExperience(User $staff): int
    {
        $years = (int) $staff->years_of_experience;

        if ($years >= 5) return 15;
        if ($years >= 3) return 10;
        if ($years >= 1) return 5;

        return 2;
    }

    private function scoreSpecialties(User $staff, ServiceRequest $serviceRequest): int
    {
        $requestedServices = $serviceRequest->serviceName;
        if (empty($requestedServices) || !is_array($requestedServices)) {
            return 5;
        }

        $staffSpecialties = $staff->specialties;
        if (empty($staffSpecialties) || !is_array($staffSpecialties)) {
            return 0;
        }

        $staffSpecialtiesLower = array_map('strtolower', $staffSpecialties);
        $matchCount = 0;

        foreach ($requestedServices as $service) {
            if (in_array(strtolower($service), $staffSpecialtiesLower)) {
                $matchCount++;
            }
        }

        if ($matchCount === count($requestedServices)) {
            return 10; // All requested services matched
        }
        if ($matchCount > 0) {
            return 5; // Partial match
        }

        return 0;
    }

    private function scoreGender(User $staff, ServiceRequest $serviceRequest): int
    {
        if (empty($serviceRequest->gender) || $serviceRequest->gender === 'no_preference') {
            return 0;
        }

        if (strtolower($staff->gender ?? '') === strtolower($serviceRequest->gender)) {
            return 10;
        }

        return 0;
    }

    private function getMatchReasons(User $staff, ServiceRequest $serviceRequest): array
    {
        $reasons = [];

        // Location
        $requestCity = strtolower(trim($serviceRequest->preferred_city ?? ''));
        $staffCity = strtolower(trim($staff->city ?? ''));
        if ($requestCity && $staffCity && $requestCity === $staffCity) {
            $reasons[] = 'Same city';
        } elseif (strtolower(trim($staff->state_of_residence ?? '')) === strtolower(trim($serviceRequest->state_of_residence ?? ''))) {
            $reasons[] = 'Same state';
        }

        // Verification. isFullyVerified() requires id_verified,
        // address_confirmed and background_checked, all of which are 0 for
        // every staff member, so this reason never appeared. Fall back to the
        // evidence actually on file so the client sees something true.
        if ($staff->isFullyVerified()) {
            $reasons[] = 'Fully verified';
        } elseif (filled($staff->nin) && $staff->guarantors_forms_uploaded) {
            $reasons[] = 'NIN + guarantors on file';
        } elseif ($staff->guarantors_forms_uploaded) {
            $reasons[] = 'Guarantors on file';
        }

        // Availability
        $preferredDate = $serviceRequest->preferred_start_date ?? now()->addDays(7);
        if (!$staff->available_from || $staff->available_from->lte($preferredDate)) {
            $reasons[] = 'Available immediately';
        }

        // Religion
        if (
            !empty($serviceRequest->preferred_religion) &&
            $serviceRequest->preferred_religion !== 'no_preference' &&
            strtolower($staff->religion ?? '') === strtolower($serviceRequest->preferred_religion)
        ) {
            $reasons[] = 'Religion match';
        }

        // Gender
        if (
            !empty($serviceRequest->gender) &&
            $serviceRequest->gender !== 'no_preference' &&
            strtolower($staff->gender ?? '') === strtolower($serviceRequest->gender)
        ) {
            $reasons[] = 'Gender preference match';
        }

        // Experience
        $years = (int) $staff->years_of_experience;
        if ($years >= 3) {
            $reasons[] = $years . '+ years experience';
        }

        return $reasons;
    }

    private function scoreReligion(User $staff, ServiceRequest $serviceRequest): int
    {
        if (
            empty($serviceRequest->preferred_religion) ||
            $serviceRequest->preferred_religion === 'no_preference'
        ) {
            return 0;
        }

        if (strtolower($staff->religion ?? '') === strtolower($serviceRequest->preferred_religion)) {
            return 10;
        }

        return 0;
    }
}
