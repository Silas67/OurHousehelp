<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Fortify\TwoFactorAuthenticatable;

use Laravel\Jetstream\HasProfilePhoto;

use Laravel\Sanctum\HasApiTokens;

use App\Models\Credit;

use App\Models\CreditTransaction;


class User extends Authenticatable

{

    use HasApiTokens;

    use HasFactory;

    use HasProfilePhoto;

    use Notifiable;

    use TwoFactorAuthenticatable;



    const ROLE_USER = 0;

    const ROLE_ADMIN = 1;

    const ROLE_SUPER_ADMIN = 2;



    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [

        'name',

        'middle_name',

        'last_name',

        'email',

        'account_type',

        'applicant_type',

        'phone',

        'gender',

        'dob',

        'state_of_origin',

        'state_of_residence',

        'approval_status',

        'status',

        'lga_of_origin',

        'years_of_experience',

        'profile_photo_path',

        'address',

        'landmark',

        'nin',

        'nin_verified',

        'nin_verified_at',

        'id_card',

        'user_role',

        'availability',

        'form_completed',

        'guarantors_forms_uploaded',

        'guarantors_forms_uploaded_2',

        'terms_accepted',

        'bio',

        'password',

        'specialties',

        'country',

        'country_of_residence',

        'account_name',

        'bank',

        'account_number',

        'is_registered',

        'registration_date',

        'registration_expires_at',

        'registration_status',



        // Staff profile fields

        'languages',

        'city',

        'available_from',

        'address_confirmed',

        'background_checked',

        'id_verified',

        'work_summary',

        'marital_status',

        'qualification',

        'religion',
    ];



    // All casts are defined in the casts() method below



    /**

     * The attributes that should be hidden for serialization.

     *

     * @var array<int, string>

     */

    protected $hidden = [

        'password',

        'remember_token',

        'two_factor_recovery_codes',

        'two_factor_secret',

    ];



    /**

     * The accessors to append to the model's array form.

     *

     * @var array<int, string>

     */

    protected $appends = [

        'profile_photo_url',

    ];



    /**

     * Get the attributes that should be cast.

     *

     * @return array<string, string>

     */

    protected function casts(): array

    {

        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'specialties' => 'array',

            'languages' => 'array',

            'dob' => 'date',

            'available_from' => 'date',

            'availability' => 'boolean',

            'terms_accepted' => 'boolean',

            'form_completed' => 'boolean',

            'guarantors_forms_uploaded' => 'boolean',

            'guarantors_forms_uploaded_2' => 'boolean',

            'address_confirmed' => 'boolean',

            'background_checked' => 'boolean',

            'id_verified' => 'boolean',

            'registration_date' => 'datetime',

            'registration_expires_at' => 'datetime',

            'nin_verified' => 'boolean',

            'nin_verified_at' => 'datetime',

        ];
    }



    // Relationships

    public function houseHelps()

    {

        return $this->hasMany(ServiceRequest::class, 'user_id')

            ->whereNotNull('house_help_id');
    }



    public function guarantors()

    {

        return $this->hasMany(Guarantor::class, 'applicant_id');
    }



    public function guarantor2s()

    {

        return $this->hasMany(Guarantor2::class, 'applicant_id');
    }



    public function contactMessages()

    {

        return $this->hasMany(ContactMessage::class);
    }



    public function replies()

    {

        return $this->hasMany(Reply::class);
    }



    public function registrationPayments()

    {

        return $this->hasMany(RegistrationPayment::class);
    }



    // Scopes

    public function scopeSearch($query, $field, $value)

    {

        if ($value) {

            return $query->where($field, 'like', '%' . $value . '%');
        }

        return $query;
    }



    // Registration Methods - BASED ON registration_payments TABLE



    /**

     * Check if user has any pending registration payments

     */

    public function hasPendingRegistration()

    {

        return $this->registrationPayments()

            ->whereIn('status', ['pending', 'pending_approval'])

            ->exists();
    }



    /**

     * Check if user has an active registration (completed payment that hasn't expired)

     */

    public function isRegistrationActive()

    {

        return $this->registrationPayments()

            ->where('status', 'completed')

            ->where('expires_at', '>', now())

            ->exists();
    }



    /**

     * Check if user has an expired registration (completed payment that has expired)

     */

    public function isRegistrationExpired()

    {

        return $this->registrationPayments()

            ->where('status', 'completed')

            ->where('expires_at', '<', now())

            ->exists();
    }



    /**

     * Get the user's registration status based on registration_payments

     */

    public function getRegistrationStatus()

    {

        if ($this->hasPendingRegistration()) {

            return 'pending';
        }



        if ($this->isRegistrationActive()) {

            return 'active';
        }



        if ($this->isRegistrationExpired()) {

            return 'expired';
        }



        return 'unregistered';
    }



    /**

     * Get the active registration payment

     */

    public function activeRegistrationPayment()

    {

        return $this->hasOne(RegistrationPayment::class)

            ->where('status', 'completed')

            ->where('expires_at', '>', now())

            ->latest();
    }



    /**

     * Get days until registration expires

     */

    public function getDaysUntilExpiration()

    {

        $activePayment = $this->activeRegistrationPayment;



        if (!$activePayment || !$activePayment->expires_at) {

            return 0;
        }



        $days = now()->diffInDays($activePayment->expires_at, false);



        // Return 0 if already expired

        return max(0, $days);
    }


    /**

     * Get formatted expiration text

     */

    public function getFormattedExpirationText()

    {

        $days = $this->getDaysUntilExpiration();



        if ($days <= 0) {

            return 'Expired';
        }



        if ($days >= 30) {

            $months = floor($days / 30);

            $remainingDays = $days % 30;

            if ($remainingDays > 0) {

                return "{$months} month" . ($months > 1 ? 's' : '') . " and {$remainingDays} day" . ($remainingDays > 1 ? 's' : '');
            }

            return "{$months} month" . ($months > 1 ? 's' : '');
        }



        if ($days >= 7) {

            $weeks = floor($days / 7);

            $remainingDays = $days % 7;

            if ($remainingDays > 0) {

                return "{$weeks} week" . ($weeks > 1 ? 's' : '') . " and {$remainingDays} day" . ($remainingDays > 1 ? 's' : '');
            }

            return "{$weeks} week" . ($weeks > 1 ? 's' : '');
        }



        return "{$days} day" . ($days > 1 ? 's' : '');
    }


    /**

     * Get expiration progress percentage

     */

    public function getExpirationPercentage()

    {

        $days = $this->getDaysUntilExpiration();

        return min(100, ($days / 365) * 100);
    }



    /**

     * Get expiration date formatted

     */

    public function getFormattedExpirationDate()

    {

        $activePayment = $this->activeRegistrationPayment;

        if (!$activePayment || !$activePayment->expires_at) {

            return null;
        }

        return $activePayment->expires_at->format('F j, Y');
    }





    /**

     * Get the expiration date from registration_payments table

     */

    public function getRegistrationExpiresAtAttribute()

    {

        $activePayment = $this->activeRegistrationPayment;

        return $activePayment ? $activePayment->expires_at : null;
    }







    /**

     * Get the registration date from registration_payments table

     */

    public function getRegistrationDateAttribute()

    {

        $activePayment = $this->activeRegistrationPayment;

        return $activePayment ? $activePayment->paid_at : null;
    }



    /**

     * Check if user is registered (compatibility method)

     * Now based on registration_payments table

     */

    public function getIsRegisteredAttribute()

    {

        return $this->isRegistrationActive();
    }



    /**

     * Get registration status text (compatibility method)

     */

    public function getRegistrationStatusAttribute()

    {

        return $this->getRegistrationStatus();
    }



    // Helper methods for admin approval

    public function activateRegistration($durationInMonths = 12)

    {

        // This method should be called from the controller after payment approval

        // The actual activation happens in the RegistrationPaymentController

    }



    public function deactivateRegistration()

    {

        // This method should be called from the controller if needed

    }



    // Staff Shortlist Relationships

    public function staffShortlists()

    {

        return $this->hasMany(StaffShortlist::class, 'staff_id');
    }



    public function ratingsReceived()

    {

        return $this->hasMany(Rating::class, 'user_id');
    }



    public function getAverageRatingAttribute(): ?float

    {

        $avg = $this->ratingsReceived()->avg('rating');

        return $avg ? round($avg, 1) : null;
    }



    public function bookingPayments()

    {

        return $this->hasMany(BookingPayment::class);
    }



    public function monthlySubscriptions()

    {

        return $this->hasMany(MonthlySubscription::class);
    }



    // Staff Matching Scopes

    public function scopeVerifiedStaff($query)

    {

        return $query->whereIn('account_type', ['applicant', 'staff'])

            ->where('approval_status', 'approved')

            ->where('availability', true)

            ->where('id_verified', true)

            ->where('guarantors_forms_uploaded', true)

            ->where('address_confirmed', true);
    }



    public function scopeInCity($query, $city)

    {

        return $query->where('city', $city);
    }



    public function scopeInState($query, $state)
    {
        return $query->where('state_of_residence', 'like', '%' . explode(' ', $state)[0] . '%');
    }


    public function scopeAvailableBy($query, $date)
    {
        return $query->where('availability', true)
            ->where(function ($q) use ($date) {
                $q->whereNull('available_from')
                    ->orWhere('available_from', '<=', $date);
            });
    }



    public function scopeWithSpecialties($query, array $services)

    {

        return $query->where(function ($q) use ($services) {

            foreach ($services as $service) {

                $q->orWhereJsonContains('specialties', $service);
            }
        });
    }



    public function isFullyVerified(): bool

    {

        return $this->id_verified

            && $this->guarantors_forms_uploaded

            && $this->address_confirmed

            && $this->background_checked;
    }



    public function getVerificationBadgeAttribute(): string

    {

        if ($this->isFullyVerified()) {

            return 'fully_verified';
        }

        if ($this->id_verified && $this->guarantors_forms_uploaded) {

            return 'verified';
        }

        return 'pending';
    }



    /**
     * Resolve a profile photo URL across both locations this app has used.
     *
     * profile_photo_path is a bare filename, but the application reads it from
     * two different directories: 7 views use build/assets/image/profile_photo/
     * and 6 use storage/. Jetstream's own accessor assumes storage/ only, so a
     * photo saved to one location renders as a broken image wherever the other
     * is assumed - which is why staff photos load on some screens and not others.
     *
     * This checks disk and returns whichever actually holds the file, falling
     * back to Jetstream's generated initials avatar.
     */
    public function profilePhotoUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(function () {
            $path = $this->profile_photo_path;

            if (blank($path)) {
                return $this->defaultProfilePhotoUrl();
            }

            // Already a full URL (some records store one).
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            $candidates = [
                'storage/' . $path,
                'build/assets/image/profile_photo/' . $path,
                $path,
            ];

            foreach ($candidates as $candidate) {
                if (is_file(public_path($candidate))) {
                    return asset($candidate);
                }
            }

            return $this->defaultProfilePhotoUrl();
        });
    }

    public function getLanguagesListAttribute(): string

    {

        $languages = $this->languages;

        if (empty($languages)) {

            return 'English';
        }

        return implode(', ', $languages);
    }



    // Credit Methods
    public function credit()
    {
        return $this->hasOne(Credit::class);
    }

    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function getCreditBalance(): int
    {
        return $this->credit?->balance ?? 0;
    }

    public function hasCredits(): bool
    {
        return $this->getCreditBalance() > 0;
    }

    public function addCredits(int $amount, string $description = 'Credit purchase', ?string $reference = null): void
    {
        $credit = $this->credit()->firstOrCreate(
            ['user_id' => $this->id],
            ['balance' => 0]
        );

        $credit->increment('balance', $amount);

        CreditTransaction::create([
            'user_id' => $this->id,
            'type' => 'purchase',
            'amount' => $amount,
            'description' => $description,
            'reference' => $reference,
            'created_at' => now(),
        ]);
    }

    public function deductCredit(int $amount = 1, ?int $serviceRequestId = null): bool
    {
        $credit = $this->credit()->first();

        if (!$credit || $credit->balance < $amount) {
            return false;
        }

        $credit->decrement('balance', $amount);

        CreditTransaction::create([
            'user_id' => $this->id,
            'type' => 'deduction',
            'amount' => $amount,
            'description' => 'Credit used for booking',
            'service_request_id' => $serviceRequestId,
            'created_at' => now(),
        ]);

        return true;
    }

    public function profileCompletionData(): array
    {
        $steps = [
            [
                'label' => 'Basic Info',
                'done'  => !empty($this->name) && !empty($this->phone) && !empty($this->gender) && !empty($this->state_of_residence),
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-user',
            ],
            [
                'label' => 'Profile Photo',
                'done'  => !empty($this->profile_photo_path),
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-camera',
            ],
            [
                'label' => 'NIN Uploaded',
                'done'  => !empty($this->nin),
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-id-card',
            ],
            [
                'label' => 'Guarantor 1',
                'done'  => (bool) $this->guarantors_forms_uploaded,
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-user-shield',
            ],
            [
                'label' => 'Guarantor 2',
                'done'  => (bool) $this->guarantors_forms_uploaded_2,
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-user-shield',
            ],
            [
                'label' => 'Terms Accepted',
                'done'  => (bool) $this->terms_accepted,
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-file-contract',
            ],
            [
                'label' => 'Bank Details',
                'done'  => !empty($this->account_name) && !empty($this->bank) && !empty($this->account_number),
                'url'   => route('applicantprofile', ['id' => $this->id]),
                'icon'  => 'fas fa-university',
            ],
        ];

        $completed  = collect($steps)->filter(fn($s) => $s['done'])->count();
        $percentage = (int) round(($completed / count($steps)) * 100);

        return [
            'steps'      => $steps,
            'percentage' => $percentage,
            'completed'  => $completed,
            'total'      => count($steps),
        ];
    }
}