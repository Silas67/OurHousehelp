<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',                  // Linked user (client)
        'name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'base_cost',
        'gender',
        'country_of_residence',
        'state_of_residence',
        'address',
        'preferred_religion',
        'landmark',
        'dob_month',
        'dob_day',
        'package',
        'days_per_week',
        'service_days',             // Stored as array (JSON)
        'serviceName',              // List of selected services (JSON)
        'features',                 // Apartment or service features (JSON)
        'working_hour',             // Chosen working hour
        'applicant_type',           // live-in | live-out | semi-live-in
        'on_behalf',                // Optional name or relation
        'details',                  // Optional notes
        'nin',                      // NIN
        'passport',                 // Passport image path
        'id_card',                  // ID card image path

        // 💰 Cost breakdown
        'feature_cost',             // Apartment/feature cost
        'additional_service_cost',  // Total of PMS/PSS add-on costs
        'extra_days_cost',          // ₦12,000 if multiple days selected
        'extra_service_cost',       // if multiple services selected
        'service_charge',           // Fixed admin/service charge
        'package_cost',             // Selected package cost
        'grand_cost',               // Final total (sum of all)
        'discounted_cost',          // If any discount applied
        'percentage',               // Optional percentage modifier

        // 🧾 Status and payment
        'status',                   // e.g. pending, approved, completed
        'payment_status',           // Paid | Pending

        // Coupon & Bonus Fields
        'bonus_slots_total',
        'bonus_slots_used',

        'registration_paid',
        'registration_paid_at',
        'registration_expires_at',
        'registration_status',

        // Booking flow fields
        'preferred_city',
        'preferred_start_date',
        'duration_weeks',
        'management_plan',
        'management_plan_cost',
        'express_matching',
        'express_matching_cost',
        'monthly_protection',
        'monthly_protection_cost',
        'payment_type',
        'amount_paid',
        'amount_remaining',
        'balance_due_date',
        'matching_status',
        'selected_staff_id',
        'staff_details_released',
        'call_with_staff_requested',
        'deployment_type',
        'is_urgent',
    ];

    protected $casts = [
        'service_days' => 'array',
        'serviceName'  => 'array',
        'features'     => 'array',
        'extra_days'    => 'array',
        'base_cost' => 'float',

        'registration_paid_at' => 'datetime',
        'registration_expires_at' => 'datetime',

        // Booking flow casts
        'preferred_start_date' => 'date',
        'balance_due_date' => 'date',
        'express_matching' => 'boolean',
        'monthly_protection' => 'boolean',
        'staff_details_released' => 'boolean',
        'call_with_staff_requested' => 'boolean',
        'is_urgent' => 'boolean',
        'management_plan_cost' => 'decimal:2',
        'express_matching_cost' => 'decimal:2',
        'monthly_protection_cost' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_remaining' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Client (user who made the request)
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Assigned house help (main)
    public function houseHelp()
    {
        return $this->belongsTo(User::class, 'house_help_id');
    }

    // Assistant house help (optional)
    public function houseHelpAssistant()
    {
        return $this->belongsTo(User::class, 'house_help_assist_id');
    }

    // Confirmation record (post-booking)
    public function confirmation()
    {
        return $this->hasOne(Confirmation::class, 'service_request_id');
    }

    // Staff offers/interest for this service request
    public function offers()
    {
        return $this->hasMany(ServiceRequestOffer::class, 'service_request_id');
    }

    // Optional: Link to service package model (if separate table)
    public function servicePackageCost()
    {
        return $this->belongsTo(ServicePackageCost::class, 'package_cost');
    }

    // app\Models\ServiceRequest.php
    public function serviceCharge()
    {
        return $this->belongsTo(ServiceCharge::class, 'service_charge_id');
    }


    // In ServiceRequest model
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function liveOutPayment()
    {
        return $this->hasOne(LiveOutPayment::class);
    }

    public function hasLiveOutPayment(): bool
    {
        return $this->liveOutPayment()->exists();
    }

    public function isLiveOutPaymentPaid(): bool
    {
        return $this->liveOutPayment && $this->liveOutPayment->isPaid();
    }


    // Registration methods
    public function markRegistrationPaid()
    {
        $this->update([
            'registration_paid' => true,
            'registration_paid_at' => now(),
            'registration_expires_at' => now()->addYear(),
            'registration_status' => 'active',
            'payment_status' => 'Paid'
        ]);

        // Also activate user registration
        if ($this->user) {
            $this->user->activateRegistration(12);
        }
    }

    public function isRegistrationActive()
    {
        return $this->registration_paid &&
            $this->registration_status === 'active' &&
            $this->registration_expires_at &&
            $this->registration_expires_at->isFuture();
    }

    // public function registrationPayment()
    // {
    //     return $this->hasOne(RegistrationPayment::class);
    // }


    // Relationship to registration payment
    // public function registrationPayment()
    // {
    //     return $this->belongsTo(RegistrationPayment::class);
    // }

    public function registrationPayment()
    {
        return $this->hasOne(RegistrationPayment::class, 'service_request_id')
            ->latestOfMany();
    }

    // Accessor for backward compatibility
    public function getRegistrationPaidAttribute($value)
    {
        // If we have a linked registration payment, use its status
        if ($this->registrationPayment) {
            return $this->registrationPayment->status === 'completed';
        }

        // Otherwise use the old column value
        return $value;
    }

    public function getRegistrationPaidAtAttribute($value)
    {
        if ($this->registrationPayment) {
            return $this->registrationPayment->paid_at;
        }
        return $value;
    }

    public function getRegistrationExpiresAtAttribute($value)
    {
        if ($this->registrationPayment) {
            return $this->registrationPayment->expires_at;
        }
        return $value;
    }

    public function getRegistrationStatusAttribute($value)
    {
        if ($this->registrationPayment) {
            return $this->registrationPayment->status === 'completed' ? 'active' : 'pending';
        }
        return $value;
    }

    public function getPaymentStatusAttribute($value)
    {
        if ($this->registrationPayment) {
            switch ($this->registrationPayment->status) {
                case 'completed':
                    return 'Paid';
                case 'pending_approval':
                    return 'Pending Approval';
                case 'failed':
                    return 'Failed';
                default:
                    return 'Pending';
            }
        }
        return $value;
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_request_service', 'service_request_id', 'service_id')
            ->withTimestamps();
    }

    /**
     * Get the package associated with the service request.
     */
    // public function package()
    // {
    //     return $this->belongsTo(Package::class, 'package', 'name'); // Adjust if needed
    // }
    public function packageModel()
    {
        return $this->belongsTo(Package::class, 'package', 'name');
    }


    /**
     * Get the apartment type associated with the service request.
     */
    public function apartmentType()
    {
        return $this->belongsTo(Apartments::class, 'apartment_type');
    }

    /**
     * Get registration payments for this service request.
     */
    public function registrationPayments()
    {
        return $this->hasMany(RegistrationPayment::class, 'service_request_id');
    }


    public function getServiceNameListAttribute()
    {
        return is_array($this->serviceName)
            ? implode(', ', $this->serviceName)
            : $this->serviceName;
    }

    public function getServiceDaysListAttribute()
    {
        return is_array($this->service_days)
            ? implode(', ', $this->service_days)
            : $this->service_days;
    }

    public function getExtraDaysListAttribute()
    {
        return is_array($this->extra_days)
            ? implode(', ', $this->extra_days)
            : $this->extra_days;
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING FLOW RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function shortlistedStaff()
    {
        return $this->hasMany(StaffShortlist::class);
    }

    public function selectedStaff()
    {
        return $this->belongsTo(User::class, 'selected_staff_id');
    }

    public function managementPlanDetails()
    {
        return $this->belongsTo(ManagementPlan::class, 'management_plan', 'slug');
    }

    public function bookingPayments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function monthlySubscription()
    {
        return $this->hasOne(MonthlySubscription::class);
    }

    public function replacementRequests()
    {
        return $this->hasMany(ReplacementRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING FLOW COMPUTED ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    /**
     * Statuses where the client still has something to do or see.
     *
     * Once a booking reaches one of these the client can navigate away and,
     * until now, had no route back: the dashboard only offered "book a
     * service" and the bookings page never linked to a shortlist. The only
     * way back in was the notification email.
     */
    public const RESUMABLE_STATUSES = [
        'matching',
        'shortlisted',
        'staff_selected',
        'payment_pending',
    ];

    /**
     * 'pending' is deliberately excluded: it is the initial state and covers
     * ~199 historic rows where matching never started. Surfacing those would
     * bury a genuinely active booking.
     *
     * The age guard stops long-abandoned requests reappearing as if live.
     */
    public function scopeResumable($query, int $withinDays = 60)
    {
        return $query->whereIn('matching_status', self::RESUMABLE_STATUSES)
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays($withinDays));
    }

    /**
     * Is the client engaging and managing the staff member themselves?
     *
     * Client-managed bookings skip the company payment step entirely, and
     * the client contacts the staff member directly - so they see every
     * match rather than waiting for staff to accept first.
     */
    public function isClientManaged(): bool
    {
        return $this->management_plan === 'client-managed';
    }

    /**
     * Can the client still cancel this booking?
     *
     * Mirrors the guard in BookingController::cancelOrder(). Client-managed
     * bookings carry payment_status = 'Paid' from creation even though no
     * money changes hands, so testing that column alone wrongly treated them
     * as paid and hid the cancel control - leaving the booking with no way
     * out, and blocking the client from starting another.
     */
    public function canBeCancelled(): bool
    {
        if ($this->matching_status === 'cancelled' || $this->status === 'order_cancelled') {
            return false;
        }

        return !($this->payment_status === 'Paid' && !$this->isClientManaged());
    }

    /**
     * A client-managed booking is finished once the client says so - the
     * company has no deployment step to complete on their behalf.
     */
    public function canBeCompletedByClient(): bool
    {
        return $this->isClientManaged()
            && $this->selected_staff_id
            && in_array($this->matching_status, ['staff_selected', 'approved'], true);
    }

    public function isResumable(): bool
    {
        return in_array($this->matching_status, self::RESUMABLE_STATUSES, true)
            && $this->status !== 'cancelled';
    }

    /**
     * Where this booking should send the client to carry on.
     */
    public function resumeUrl(): string
    {
        // A staff member already picked: go straight to their profile.
        if ($this->matching_status === 'staff_selected' && $this->selected_staff_id) {
            $shortlist = $this->shortlistedStaff()
                ->where('staff_id', $this->selected_staff_id)
                ->first();

            if ($shortlist) {
                return route('booking.staff-profile', [$this->id, $shortlist->id]);
            }

            return route('booking.management-plans', $this->id);
        }

        if ($this->matching_status === 'payment_pending') {
            return route('booking.payment', $this->id);
        }

        // Anyone accepted yet? Then the shortlist is worth looking at.
        $accepted = $this->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted', 'selected'])
            ->count();

        return $accepted > 0
            ? route('booking.shortlist', $this->id)
            : route('booking.staff-matching', $this->id);
    }

    /**
     * Short, client-facing description of what is waiting for them.
     */
    public function resumeLabel(): string
    {
        if ($this->matching_status === 'payment_pending') {
            return 'Payment needed to confirm your booking';
        }

        if ($this->matching_status === 'staff_selected') {
            return 'You selected a staff member - finish your booking';
        }

        $accepted = $this->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted', 'selected'])
            ->count();

        if ($accepted > 0) {
            return $accepted === 1
                ? '1 staff member accepted - view your shortlist'
                : $accepted . ' staff members accepted - view your shortlist';
        }

        return 'We are still finding staff for you';
    }

    /**
     * Number of months this booking is billed over.
     *
     * Mirrors BookingCostService::calculateStaffSalary() so that cost labels in
     * the UI describe the same period the stored figures were computed for.
     * grand_cost is a total for this many months, NOT a monthly rate.
     */
    public function getBillingMonthsAttribute(): int
    {
        if ((int) $this->duration_weeks === 1) {
            return 1;
        }

        return max(1, (int) round(((int) $this->duration_weeks) / 4));
    }

    /**
     * grand_cost expressed as a per-month figure, for display next to the total.
     */
    public function getMonthlyServiceCostAttribute(): float
    {
        return $this->billing_months > 0
            ? round((float) $this->grand_cost / $this->billing_months)
            : (float) $this->grand_cost;
    }

    public function getTotalBookingCostAttribute(): float
    {
        return (float) $this->grand_cost
            + (float) $this->management_plan_cost
            + (float) $this->express_matching_cost
            + ($this->monthly_protection ? (float) $this->monthly_protection_cost : 0);
    }

    public function isStaffDetailsVisible(): bool
    {
        return $this->staff_details_released
            && $this->selected_staff_id
            && $this->bookingPayments()->completed()->exists();
    }

    public function getMatchingStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pending',
            'matching' => 'Finding Staff',
            'shortlisted' => 'Staff Available',
            'staff_selected' => 'Staff Selected',
            'payment_pending' => 'Awaiting Payment',
            'approved' => 'Approved',
            'deployed' => 'Staff Deployed',
        ];

        return $labels[$this->matching_status] ?? 'Unknown';
    }

    public function getInstallmentFirstHalfAttribute(): float
    {
        return round($this->total_booking_cost / 2, 2);
    }

    public function getInstallmentSecondHalfAttribute(): float
    {
        return $this->total_booking_cost - $this->installment_first_half;
    }
}
