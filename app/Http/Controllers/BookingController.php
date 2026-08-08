<?php

namespace App\Http\Controllers;

use App\Models\Apartments;
use App\Models\BookingPayment;
use App\Models\Location;
use App\Models\ManagementPlan;
use App\Models\MonthlySubscription;
use App\Models\Package;
use App\Models\ReplacementRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\StaffShortlist;
use App\Models\User;
use App\Notifications\BookingPaymentReceivedNotification;
use App\Notifications\CallRequestNotification;
use App\Notifications\NewServiceRequestNotification;
use App\Notifications\ReplacementRequestNotification;
use App\Notifications\StaffSelectedNotification;
use App\Notifications\UrgentRequestNotification;
use App\Services\BookingCostService;
use App\Services\StaffMatchingService;
use App\Notifications\JobFilledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    protected StaffMatchingService $matchingService;
    protected BookingCostService $costService;

    public function __construct(StaffMatchingService $matchingService, BookingCostService $costService)
    {
        $this->matchingService = $matchingService;
        $this->costService = $costService;
    }

    /**
     * Step 2: Show service selection form.
     */
    public function showServiceSelection()
    {
        $services = Service::all();
        $packages = Package::all();
        $apartments = Apartments::all();

        $nigerianStates = [
            'Abia',
            'Adamawa',
            'Akwa Ibom',
            'Anambra',
            'Bauchi',
            'Bayelsa',
            'Benue',
            'Borno',
            'Cross River',
            'Delta',
            'Ebonyi',
            'Edo',
            'Ekiti',
            'Enugu',
            'FCT',
            'Gombe',
            'Imo',
            'Jigawa',
            'Kaduna',
            'Kano',
            'Katsina',
            'Kebbi',
            'Kogi',
            'Kwara',
            'Lagos',
            'Nasarawa',
            'Niger',
            'Ogun',
            'Ondo',
            'Osun',
            'Oyo',
            'Plateau',
            'Rivers',
            'Sokoto',
            'Taraba',
            'Yobe',
            'Zamfara',
        ];

        $locations = Location::active()->get();

        return view('booking.service-selection', compact(
            'services',
            'packages',
            'apartments',
            'nigerianStates',
            'locations'
        ));
    }

    /**
     * Step 2: Process service selection and trigger matching.
     */
    public function saveServiceSelection(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'applicant_type' => 'required|in:live-in,live-out,semi-live-in',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'religion' => 'nullable|string|max:50',
            'preferred_start_date' => 'required|date|after_or_equal:today',
            'days_per_week' => 'nullable|integer|min:1|max:6',
            'duration_weeks' => 'required|integer|in:1,4,8,12',
            'package' => 'required|exists:packages,id',
            'apartment_type' => 'nullable|exists:apartments,id',
            'management_plan' => 'required|in:client-managed,company-managed',
            'service_days' => 'nullable|array',
            'service_days.*' => 'nullable|string',
            'working_hour_start' => 'nullable|string',
            'working_hour_end' => 'nullable|string',
        ]);
        

        $user = Auth::user();

        // Gate: client must have at least 1 credit before booking
        if (!$user->hasCredits()) {
            return redirect()->route('credits.index')
                ->with('error', 'You need at least 1 booking credit to proceed. You will only be charged when you view a staff profile.');
        }

        // Calculate service costs using unified salary method
        $costs = $this->costService->calculateStaffSalary(
            $validated['services'],
            $validated['package'],
            $validated['management_plan'],
            (int) $validated['duration_weeks'],
            $validated['apartment_type'] ?? null
        );

        // Also get apartment cost for feature_cost field
        $apartmentCost = 0;
        if (!empty($validated['apartment_type'])) {
            $apartmentCost = (float) (Apartments::find($validated['apartment_type'])?->cost ?? 0);
        }

        $services = Service::whereIn('id', $validated['services'])->get();
        $serviceNames = $services->pluck('service_name')->toArray();
        $package = Package::find($validated['package']);

        // Split user name into parts if last_name not set separately
        $firstName = $user->name;
        $middleName = $user->middle_name;
        $lastName = $user->last_name;

        if (empty($lastName) && !empty($firstName)) {
            $parts = preg_split('/\s+/', trim($firstName));
            if (count($parts) >= 3) {
                $firstName = $parts[0];
                $lastName = array_pop($parts);
                $middleName = $middleName ?? implode(' ', array_slice($parts, 1));
            } elseif (count($parts) === 2) {
                $firstName = $parts[0];
                $lastName = $parts[1];
            } else {
                $lastName = $firstName;
            }
        }


        // Create service request --Added Validation for management plan and deployment type
        $serviceRequest = ServiceRequest::create([
            'user_id' => $user->id,
            'name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'gender' => $user->gender ?? 'no_preference',
            'preferred_religion' => $validated['religion'] ?? null,
            'country_of_residence' => $user->country_of_residence ?? 'Nigeria',
            'state_of_residence' => $validated['state'],
            'preferred_city' => $validated['city'],
            'address' => $validated['address'],
            'landmark' => $user->landmark,
            'package' => $package->name,
            'applicant_type' => $validated['applicant_type'],
            'days_per_week' => $validated['days_per_week'] ?? $package->days_per_week ?? null,
            'duration_weeks' => $validated['duration_weeks'],
            'preferred_start_date' => $validated['preferred_start_date'],
            'service_days' => !empty($request->input('service_days')) ? implode(', ', $request->input('service_days')) : null,
            'working_hour' => !empty($validated['working_hour_start']) && !empty($validated['working_hour_end'])
                ? $validated['working_hour_start'] . ' - ' . $validated['working_hour_end']
                : null,
            'serviceName' => json_encode($serviceNames),
            'base_cost' => $costs['base_cost'],
            'extra_service_cost' => $costs['extra_service_cost'],
            'package_cost' => $costs['package_cost'],
            'grand_cost' => $costs['client_total'] ?? $costs['grand_cost'],
            'feature_cost' => $apartmentCost,
            'features' => !empty($validated['apartment_type'])
                ? json_encode(['apartment_type_id' => $validated['apartment_type']])
                : null,
            'status' => 'pending',
            'matching_status' => 'pending',
            'payment_status' => $validated['management_plan'] === 'client-managed' ? 'Paid' : 'Pending',
            'management_plan' => $validated['management_plan'],
            'management_plan_cost' => 0,
            'deployment_type' => $validated['management_plan'] === 'company-managed' ? 'company_managed' : 'self_managed',
        ]);

        // Attach services to pivot table
        $serviceRequest->services()->attach($validated['services']);
        // Notify admins of new service request
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewServiceRequestNotification($serviceRequest));
            }
        } catch (\Exception $e) {
            Log::error('BookingController: Failed to notify admins of new service request: ' . $e->getMessage());
        }

        // Trigger staff matching
        $matchedStaff = $this->matchingService->findMatchingStaff($serviceRequest);

        if ($matchedStaff->isNotEmpty()) {
            $this->matchingService->createShortlist($serviceRequest, $matchedStaff);
            $this->matchingService->notifyMatchedStaff($serviceRequest);
        } else {
            $serviceRequest->update(['matching_status' => 'matching']);
        }

        return redirect()->route('booking.staff-matching', $serviceRequest->id)
            ->with('success', 'Service request created. We are finding the best staff for you.');
    }

    /**
     * AJAX: Calculate cost in real-time.
     */
    public function calculateCost(Request $request)
    {
        $validated = $request->validate([
            'services' => 'required|array|min:1',
            'package' => 'required|exists:packages,id',
            'apartment_type' => 'nullable|exists:apartments,id',
            'management_plan' => 'nullable|string|in:client-managed,company-managed',
            'duration_weeks' => 'nullable|integer|min:1',
        ]);

        $managementPlan = $validated['management_plan'] ?? 'client-managed';
        $durationWeeks = (int) ($validated['duration_weeks'] ?? 4);

        // Calculate staff salary and client cost using unified method
        $salary = $this->costService->calculateStaffSalary(
            $validated['services'],
            $validated['package'],
            $managementPlan,
            $durationWeeks,
            $validated['apartment_type'] ?? null
        );

        return response()->json([
            'base_cost' => $salary['base_cost'],
            'extra_service_cost' => $salary['extra_service_cost'],
            'package_cost' => $salary['package_cost'],
            'grand_cost' => $salary['grand_cost'],
            'service_charge' => 0,
            'months' => $salary['months'],
            'discount_percentage' => $salary['discount_percentage'],
            'discount_amount' => $salary['discount_amount'],
            'total_before_discount' => $salary['total_before_discount'],
            'total_after_discount' => $salary['total_after_discount'],
            'client_total' => $salary['client_total'],
            'staff_salary' => $salary['staff_salary'],
            'client_cost' => $salary['client_cost'],
            'platform_fee' => $salary['platform_fee'],
            'management_plan' => $managementPlan,
            'is_one_off' => $salary['is_one_off'] ?? false,
            'breakdown' => $salary['breakdown'],
        ]);
    }

    /**
     * Step 3: Show staff matching loading state.
     */
    public function showStaffMatching(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // Redirect to shortlist as soon as ANY staff has accepted
        $acceptedCount = $serviceRequest->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted', 'selected'])
            ->count();

        if ($acceptedCount >= 1) {
            return redirect()->route('booking.shortlist', $serviceRequest->id);
        }

        $totalNotified = $serviceRequest->shortlistedStaff()->count();
        $totalAccepted = $serviceRequest->shortlistedStaff()->accepted()->count();

        return view('booking.staff-matching', compact('serviceRequest', 'totalNotified', 'totalAccepted'));
    }

    /**
     * Step 4: Show shortlist of matched staff.
     */
    public function showShortlist(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // Block going back to shortlist after staff selected
        if ($serviceRequest->selected_staff_id) {
            $selectedShortlist = StaffShortlist::where('service_request_id', $serviceRequest->id)
                ->where('staff_id', $serviceRequest->selected_staff_id)
                ->first();
            if ($selectedShortlist) {
                return redirect()->route('booking.staff-profile', [$serviceRequest->id, $selectedShortlist->id])
                    ->with('info', 'You have already selected a staff member. You cannot go back to the shortlist.');
            }
        }

        // Show ALL accepted staff — no minimum required
        $shortlist = $serviceRequest->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted', 'selected'])
            ->with('staff')
            ->orderByDesc('match_score')
            ->get();

        // If no one has accepted yet, redirect to matching page
        if ($shortlist->count() < 1) {
            return redirect()->route('booking.staff-matching', $serviceRequest->id)
                ->with('info', 'We are still finding staff for you. Please check back shortly.');
        }

        // Load average ratings for staff cards
        $staffIds = $shortlist->pluck('staff_id');
        $ratingAverages = DB::table('ratings')
            ->whereIn('user_id', $staffIds)
            ->groupBy('user_id')
            ->select('user_id', DB::raw('ROUND(AVG(rating), 1) as avg_rating'), DB::raw('COUNT(*) as rating_count'))
            ->get()
            ->keyBy('user_id');

        return view('booking.shortlist', compact('serviceRequest', 'shortlist', 'ratingAverages'));
    }

    /**
     * Step 5: Client selects preferred staff.
     */
    public function selectStaff(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $validated = $request->validate([
            'staff_shortlist_id' => 'required|exists:staff_shortlists,id',
        ]);

        $shortlist = StaffShortlist::where('id', $validated['staff_shortlist_id'])
            ->where('service_request_id', $serviceRequest->id)
            ->with('staff')
            ->firstOrFail();

        // mark selected
        $shortlist->markSelected();

        // update booking
        $serviceRequest->selected_staff_id = $shortlist->staff_id;
        $serviceRequest->matching_status = 'staff_selected';
        $serviceRequest->save();

        // notify selected
        if ($shortlist->staff) {
            $shortlist->staff->notify(new StaffSelectedNotification($serviceRequest));
        }

        return redirect()->route('booking.trust-assurance', $serviceRequest->id);
    }

    /**
     * Step 6: Show trust assurance display.
     */
    public function showTrustAssurance(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $selectedStaff = $serviceRequest->selectedStaff;

        if (!$selectedStaff) {
            return redirect()->route('booking.shortlist', $serviceRequest->id)
                ->with('error', 'Please select a staff member first.');
        }

        return view('booking.trust-assurance', compact('serviceRequest', 'selectedStaff'));
    }

    /**
     * Step 7: Client requests call with staff.
     */
    public function requestCallWithStaff(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $serviceRequest->update(['call_with_staff_requested' => true]);

        // Notify admin
        $admins = \App\Models\User::where('user_role', '>=', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new CallRequestNotification($serviceRequest));
        }

        return redirect()->route('booking.trust-assurance', $serviceRequest->id)
            ->with('success', 'Call request sent. Our team will schedule a call with the staff for you.');
    }

    /**
     * Step 8: Show management plans.
     */
    public function showManagementPlans(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // Guard: must have selected a staff member first
        if (!$serviceRequest->selected_staff_id) {
            return redirect()->route('booking.shortlist', $serviceRequest->id)
                ->with('error', 'Please select a staff member first.');
        }

        // Get plans ordered by sort_order (Premium first for price anchoring)
        $plans = ManagementPlan::active()->ordered()->get();

        $expressMatchingCost = $this->costService->getExpressMatchingCost();
        $monthlyProtectionCost = $this->costService->getMonthlyProtectionCost();

        return view('booking.management-plans', compact(
            'serviceRequest',
            'plans',
            'expressMatchingCost',
            'monthlyProtectionCost'
        ));
    }

    /**
     * Step 9: Process management plan selection.
     */
    public function selectManagementPlan(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $validated = $request->validate([
            'management_plan' => 'required|in:starter,assisted,premium',
            'express_matching' => 'nullable|boolean',
            'monthly_protection' => 'nullable|boolean',
        ]);

        $planCost = $this->costService->getManagementPlanCost($validated['management_plan']);
        $expressCost = !empty($validated['express_matching']) ? $this->costService->getExpressMatchingCost() : 0;
        $protectionCost = !empty($validated['monthly_protection']) ? $this->costService->getMonthlyProtectionCost() : 0;

        $deploymentType = $validated['management_plan'] === 'starter' ? 'self_managed' : 'company_managed';

        $serviceRequest->update([
            'management_plan' => $validated['management_plan'],
            'management_plan_cost' => $planCost,
            'express_matching' => !empty($validated['express_matching']),
            'express_matching_cost' => $expressCost,
            'monthly_protection' => !empty($validated['monthly_protection']),
            'monthly_protection_cost' => $protectionCost,
            'matching_status' => 'payment_pending',
            'deployment_type' => $deploymentType,
        ]);

        return redirect()->route('booking.payment', $serviceRequest->id);
    }

    /**
     * Step 10: Show payment page.
     */
    public function showPayment(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        if ($serviceRequest->management_plan === 'client-managed') {
            return redirect()->route(
                'booking.payment-success',
                $serviceRequest->id
            );
        }

        // Guard: must have selected a management plan first
        if (!$serviceRequest->management_plan) {
            return redirect()->route('booking.management-plans', $serviceRequest->id)
                ->with('error', 'Please select a management plan first.');
        }

        $plan = ManagementPlan::where('slug', $serviceRequest->management_plan)->first();

        $totalCost = $serviceRequest->total_booking_cost;
        $installment = $this->costService->calculateInstallmentAmounts($totalCost);

        // Pre-generate payment reference so it's consistent between display and storage
        $paymentReference = 'BK' . $serviceRequest->id . '_' . strtoupper(Str::random(6));

        return view('booking.payment', compact('serviceRequest', 'plan', 'totalCost', 'installment', 'paymentReference'));
    }

    /**
     * Step 11: Process payment.
     */
    public function processPayment(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,card',
            'payment_type' => 'required|in:full,installment',
            'payment_reference' => 'required|string',
            'bank_receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $totalCost = $serviceRequest->total_booking_cost;
        $isInstallment = $validated['payment_type'] === 'installment';
        $installmentAmounts = $this->costService->calculateInstallmentAmounts($totalCost);

        $amount = $isInstallment ? $installmentAmounts['first_payment'] : $totalCost;

        // Handle file upload
        $receiptPath = null;
        if ($request->hasFile('bank_receipt')) {
            $receiptPath = \App\Support\SecureDocument::store($request->file('bank_receipt'), 'receipts');
        }

        $transactionRef = $validated['payment_reference'];

        // Create booking payment record
        $payment = BookingPayment::create([
            'service_request_id' => $serviceRequest->id,
            'user_id' => Auth::id(),
            'payment_for' => 'management_plan',
            'amount' => $amount,
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $transactionRef,
            'status' => $receiptPath ? 'pending_approval' : 'pending',
            'bank_receipt' => $receiptPath,
            'is_installment' => $isInstallment,
            'installment_type' => $isInstallment ? 'first_half' : null,
            'due_date' => $isInstallment ? $serviceRequest->preferred_start_date : null,
        ]);

        // Update service request
        $serviceRequest->update([
            'payment_type' => $validated['payment_type'],
            'amount_paid' => $amount,
            'amount_remaining' => $isInstallment ? $installmentAmounts['second_payment'] : 0,
            'balance_due_date' => $isInstallment ? $serviceRequest->preferred_start_date : null,
        ]);

        // If receipt uploaded, mark as pending approval and notify admin
        if ($receiptPath) {
            $payment->markAsPendingApproval();

            $admins = \App\Models\User::where('user_role', '>=', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new BookingPaymentReceivedNotification($serviceRequest, $payment));
            }
        }

        // Create monthly subscription if selected
        if ($serviceRequest->monthly_protection) {
            MonthlySubscription::create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => Auth::id(),
                'plan_type' => 'monthly_protection',
                'monthly_amount' => $this->costService->getMonthlyProtectionCost(),
                'status' => 'active',
                'started_at' => now(),
                'next_billing_date' => now()->addMonth(),
            ]);
        }

        return redirect()->route('booking.payment-success', $serviceRequest->id)
            ->with('success', 'Payment submitted successfully.');
    }

    /**
     * Handle Paystack callback for booking payment
     */
    public function handlePaystackCallback(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // `amount_paid` is deliberately NOT accepted from the request. The
        // amount charged is decided by the browser (see booking/payment.blade.php),
        // so it is only authoritative once Paystack confirms it.
        $validated = $request->validate([
            'paystack_reference' => 'required|string',
            'payment_type' => 'required|in:full,installment',
        ]);

        $reference = $validated['paystack_reference'];

        try {
            $secretKey = config('paystack.secretKey');
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->get('https://api.paystack.co/transaction/verify/' . $reference);

            $data = $response->json();
            $txn = $data['data'] ?? null;

            if (!$response->successful() || !is_array($txn) || ($txn['status'] ?? null) !== 'success') {
                Log::warning('Paystack booking verify rejected', [
                    'service_request_id' => $serviceRequest->id,
                    'reference' => $reference,
                ]);
                return redirect()->route('booking.payment', $serviceRequest->id)
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

            // A reference may only ever be consumed once, by any flow. Without
            // the cross-table check a reference belonging to a cheaper
            // transaction on the same merchant account (a credit top-up, a
            // registration fee) could be replayed here to settle a booking.
            if ($this->referenceAlreadyConsumed($reference)) {
                return redirect()->route('booking.payment-success', $serviceRequest->id)
                    ->with('info', 'This payment has already been processed.');
            }

            $totalCost = $serviceRequest->total_booking_cost;
            $isInstallment = $validated['payment_type'] === 'installment';
            $installmentAmounts = $this->costService->calculateInstallmentAmounts($totalCost);
            $amount = $isInstallment ? $installmentAmounts['first_payment'] : $totalCost;

            // Paystack reports minor units (kobo). Compare in kobo to avoid
            // float rounding, and require at least the expected amount.
            $expectedKobo = (int) round($amount * 100);
            $paidKobo = (int) ($txn['amount'] ?? 0);
            $currency = $txn['currency'] ?? null;

            if ($currency !== 'NGN' || $paidKobo < $expectedKobo) {
                Log::warning('Paystack booking amount mismatch', [
                    'service_request_id' => $serviceRequest->id,
                    'reference' => $reference,
                    'expected_kobo' => $expectedKobo,
                    'paid_kobo' => $paidKobo,
                    'currency' => $currency,
                ]);
                return redirect()->route('booking.payment', $serviceRequest->id)
                    ->with('error', 'The amount paid does not match this booking. Please contact support.');
            }

            // The transaction must have been initiated for this booking.
            $metaRequestId = $txn['metadata']['service_request_id'] ?? null;
            if ($metaRequestId !== null && (int) $metaRequestId !== (int) $serviceRequest->id) {
                Log::warning('Paystack booking metadata mismatch', [
                    'service_request_id' => $serviceRequest->id,
                    'reference' => $reference,
                    'metadata_service_request_id' => $metaRequestId,
                ]);
                return redirect()->route('booking.payment', $serviceRequest->id)
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

            $payment = BookingPayment::create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => Auth::id(),
                'payment_for' => 'management_plan',
                'amount' => $amount,
                'payment_method' => 'card',
                'transaction_reference' => $reference,
                'status' => 'completed',
                'is_installment' => $isInstallment,
                'installment_type' => $isInstallment ? 'first_half' : null,
                'due_date' => $isInstallment ? $serviceRequest->preferred_start_date : null,
                'paid_at' => now(),
            ]);

            $serviceRequest->update([
                'payment_type' => $validated['payment_type'],
                'payment_status' => 'Paid',
                'amount_paid' => $amount,
                'amount_remaining' => $isInstallment ? $installmentAmounts['second_payment'] : 0,
                'balance_due_date' => $isInstallment ? $serviceRequest->preferred_start_date : null,
                'matching_status' => 'approved',
            ]);

            $admins = \App\Models\User::where('user_role', '>=', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new BookingPaymentReceivedNotification($serviceRequest, $payment));
            }

            if ($serviceRequest->monthly_protection) {
                MonthlySubscription::firstOrCreate(
                    ['service_request_id' => $serviceRequest->id],
                    [
                        'user_id' => Auth::id(),
                        'plan_type' => 'monthly_protection',
                        'monthly_amount' => $this->costService->getMonthlyProtectionCost(),
                        'status' => 'active',
                        'started_at' => now(),
                        'next_billing_date' => now()->addMonth(),
                    ]
                );
            }

            Log::info('Booking payment via Paystack', [
                'service_request_id' => $serviceRequest->id,
                'reference' => $reference,
                'amount' => $amount,
                'paid_kobo' => $paidKobo,
            ]);

            return redirect()->route('booking.payment-success', $serviceRequest->id)
                ->with('success', 'Payment successful! Your booking is now confirmed.');
        } catch (\Exception $e) {
            Log::error('Paystack booking callback failed: ' . $e->getMessage());
            return redirect()->route('booking.payment', $serviceRequest->id)
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Step 12: WhatsApp receipt upload.
     */
    public function uploadWhatsAppReceipt(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);
        $validated = $request->validate([
            'whatsapp_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
        $receiptPath = \App\Support\SecureDocument::store($request->file('whatsapp_receipt'), 'receipts');

        // Find existing pending payment or create one
        $payment = $serviceRequest->bookingPayments()
            ->where('payment_for', 'management_plan')
            ->latest()
            ->first();

        if ($payment) {
            $payment->update([
                'whatsapp_receipt' => $receiptPath,
                'status' => 'pending_approval',
            ]);
        } else {
            $totalCost = $serviceRequest->total_booking_cost;

            $payment = BookingPayment::create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => Auth::id(),
                'payment_for' => 'management_plan',
                'amount' => $totalCost,
                'payment_method' => 'whatsapp_upload',
                'transaction_reference' => 'WA' . $serviceRequest->id . '_' . strtoupper(Str::random(6)),
                'status' => 'pending_approval',
                'whatsapp_receipt' => $receiptPath,
            ]);
        }

        // Notify admin
        $admins = \App\Models\User::where('user_role', '>=', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new BookingPaymentReceivedNotification($serviceRequest, $payment));
        }

        return redirect()->route('booking.payment-success', $serviceRequest->id)
            ->with('success', 'WhatsApp receipt uploaded. Awaiting admin approval.');
    }

    /**
     * Step 13: Payment success page.
     */
    public function showPaymentSuccess(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $payment = $serviceRequest->bookingPayments()->latest()->first();

        return view('booking.payment-success', compact('serviceRequest', 'payment'));
    }

    /**
     * Step 14: Show staff details (gated by admin approval).
     */
    public function showStaffDetails(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        $selectedStaff = $serviceRequest->selectedStaff;

        if (!$selectedStaff) {
            return redirect()->route('booking.shortlist', $serviceRequest->id)
                ->with('error', 'The selected staff is no longer available. Please select another.');
        }

        $isClientManaged = $serviceRequest->management_plan === 'client-managed';
        $isCompanyManaged = $serviceRequest->management_plan === 'company-managed';

        // For client managed — contact details are available immediately after selection
        // For company managed — admin handles contact, details hidden
        $showContactDetails = $isClientManaged;

        return view('booking.staff-details', compact(
            'serviceRequest',
            'selectedStaff',
            'isClientManaged',
            'isCompanyManaged',
            'showContactDetails'
        ));
    }

    /**
     * Step 15: Show deployment info.
     */
    public function showDeployment(ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // Guard: must have staff details released (payment approved)
        if (!$serviceRequest->staff_details_released) {
            return redirect()->route('booking.payment-success', $serviceRequest->id)
                ->with('info', 'Deployment info will be available after payment approval.');
        }

        $selectedStaff = $serviceRequest->selectedStaff;

        if (!$selectedStaff) {
            return redirect()->route('booking.shortlist', $serviceRequest->id)
                ->with('error', 'The selected staff is no longer available. Please select another.');
        }

        $plan = ManagementPlan::where('slug', $serviceRequest->management_plan)->first();

        return view('booking.deployment', compact('serviceRequest', 'selectedStaff', 'plan'));
    }

    /**
     * Show urgent request form.
     */
    public function showUrgentRequest()
    {
        $locations = Location::active()->get();
        $nigerianStates = [
            'Abia',
            'Adamawa',
            'Akwa Ibom',
            'Anambra',
            'Bauchi',
            'Bayelsa',
            'Benue',
            'Borno',
            'Cross River',
            'Delta',
            'Ebonyi',
            'Edo',
            'Ekiti',
            'Enugu',
            'FCT',
            'Gombe',
            'Imo',
            'Jigawa',
            'Kaduna',
            'Kano',
            'Katsina',
            'Kebbi',
            'Kogi',
            'Kwara',
            'Lagos',
            'Nasarawa',
            'Niger',
            'Ogun',
            'Ondo',
            'Osun',
            'Oyo',
            'Plateau',
            'Rivers',
            'Sokoto',
            'Taraba',
            'Yobe',
            'Zamfara',
        ];

        return view('booking.urgent-request', compact('locations', 'nigerianStates'));
    }

    /**
     * Submit urgent staff request.
     */
    public function submitUrgentRequest(Request $request)
    {
        $validated = $request->validate([
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'services_needed' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $serviceRequest = ServiceRequest::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name ?? $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'gender' => 'no_preference',
            'country_of_residence' => $user->country_of_residence ?? 'Nigeria',
            'state_of_residence' => $validated['state'],
            'preferred_city' => $validated['city'],
            'address' => $validated['address'],
            'details' => $validated['services_needed'] ?? null,
            'serviceName' => $validated['services_needed'] ? [$validated['services_needed']] : ['General'],
            'is_urgent' => true,
            'status' => 'pending',
            'matching_status' => 'pending',
            'payment_status' => 'Pending',
            'applicant_type' => 'live-out',
            'days_per_week' => 5,
            'duration_weeks' => 4,
            'package' => 'Standard',
            'base_cost' => 0,
            'grand_cost' => 0,
        ]);

        // Notify all admins immediately
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            Notification::send($admins, new UrgentRequestNotification($serviceRequest));
        } catch (\Exception $e) {
            Log::error('UrgentRequest: Failed to notify admins: ' . $e->getMessage());
        }

        return redirect()->route('booking.service-selection')
            ->with('success', 'Your urgent request has been submitted! Our team will contact you shortly. You can also reach us directly at 08126237436.');
    }

    /**
     * Client requests staff replacement.
     */
    public function requestReplacement(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        // Check if within replacement window
        $plan = ManagementPlan::where('slug', $serviceRequest->management_plan)->first();
        $startDate = $serviceRequest->preferred_start_date;

        if ($plan && $plan->replacement_window && $startDate) {
            preg_match('/(\d+)\s*(day|days)/i', $plan->replacement_window, $matches);
            $windowDays = isset($matches[1]) ? (int) $matches[1] : 7;
            if (now()->diffInDays($startDate, false) < -$windowDays) {
                return redirect()->back()->with('error', 'The replacement window of ' . $plan->replacement_window . ' has passed.');
            }
        }

        // Check no pending replacement already exists
        $existing = $serviceRequest->replacementRequests()->where('status', 'pending')->first();
        if ($existing) {
            return redirect()->back()->with('info', 'You already have a pending replacement request.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:20|max:1000',
        ]);

        $replacementRequest = ReplacementRequest::create([
            'service_request_id' => $serviceRequest->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // Notify admins
        try {
            $admins = User::where('user_role', '>=', 1)->get();
            Notification::send($admins, new ReplacementRequestNotification($replacementRequest));
        } catch (\Exception $e) {
            Log::error('Replacement: Failed to notify admins: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Replacement request submitted. Our team will review and contact you shortly.');
    }

    /**
     * Staff Profile: Show full staff profile page
     */
    public function showStaffProfile(ServiceRequest $serviceRequest, $shortlistId)
    {
        $this->authorizeClient($serviceRequest);

        $shortlistItem = StaffShortlist::where('id', $shortlistId)
            ->where('service_request_id', $serviceRequest->id)
            ->with('staff')
            ->firstOrFail();

        $staff = $shortlistItem->staff;
        $creditBalance = Auth::user()->getCreditBalance();
        $hasCredits = Auth::user()->hasCredits();

        $rating = DB::table('ratings')
            ->where('user_id', $staff->id)
            ->selectRaw('ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as rating_count')
            ->first();

        $isClientManaged = $serviceRequest->management_plan === 'client-managed';

        return view('booking.staff-profile', compact(
            'serviceRequest',
            'shortlistItem',
            'staff',
            'creditBalance',
            'hasCredits',
            'rating',
            'isClientManaged'
        ));
    }

    /**
     * Staff Profile: Deduct credit and confirm staff selection
     */
    public function confirmStaffSelection(Request $request, ServiceRequest $serviceRequest, $shortlistId)
    {
        $this->authorizeClient($serviceRequest);

        $shortlistItem = StaffShortlist::where('id', $shortlistId)
            ->where('service_request_id', $serviceRequest->id)
            ->firstOrFail();

        // Already selected — redirect to their profile
        if ($serviceRequest->selected_staff_id) {
            $existingShortlist = StaffShortlist::where('service_request_id', $serviceRequest->id)
                ->where('staff_id', $serviceRequest->selected_staff_id)
                ->first();
            if ($existingShortlist) {
                return redirect()->route('booking.staff-profile', [$serviceRequest->id, $existingShortlist->id])
                    ->with('info', 'You have already selected a staff member.');
            }
        }

        $user = Auth::user();

        // Check credits
        if (!$user->hasCredits()) {
            return redirect()->route('credits.index')
                ->with('error', 'You need at least 1 credit to view staff details. Please purchase credits.');
        }

        // Deduct 1 credit
        $deducted = $user->deductCredit(1, $serviceRequest->id);

        if (!$deducted) {
            return redirect()->route('credits.index')
                ->with('error', 'Credit deduction failed. Please try again or purchase more credits.');
        }

        // Mark this staff as selected
        $shortlistItem->markSelected();

        // Get other staff BEFORE expiring them
        $otherStaff = StaffShortlist::where('service_request_id', $serviceRequest->id)
            ->where('id', '!=', $shortlistItem->id)
            ->whereIn('status', ['accepted', 'shortlisted', 'notified'])
            ->with('staff')
            ->get();

        // Expire all other candidates
        StaffShortlist::where('service_request_id', $serviceRequest->id)
            ->where('id', '!=', $shortlistItem->id)
            ->whereIn('status', ['accepted', 'shortlisted', 'notified'])
            ->update(['status' => 'expired']);

        // Update service request
        $serviceRequest->update([
            'selected_staff_id' => $shortlistItem->staff_id,
            'matching_status' => 'staff_selected',
        ]);

        // Notify selected staff
        if ($shortlistItem->staff) {
            try {
                $shortlistItem->staff->notify(new StaffSelectedNotification($serviceRequest));
            } catch (\Exception $e) {
                Log::error('StaffSelected notify failed: ' . $e->getMessage());
            }
        }

        // Notify other staff that job is filled
        foreach ($otherStaff as $item) {
            if ($item->staff) {
                try {
                    $item->staff->notify(new JobFilledNotification($serviceRequest));
                } catch (\Exception $e) {
                    Log::error('JobFilled: ' . $e->getMessage());
                }
            }
        }

        // If company managed — notify admin to coordinate
        if ($serviceRequest->management_plan === 'company-managed') {
            try {
                $admins = User::where('user_role', '>=', 1)->get();
                Notification::send($admins, new NewServiceRequestNotification($serviceRequest));
            } catch (\Exception $e) {
                Log::error('CompanyManaged admin notify: ' . $e->getMessage());
            }
        }

        return redirect()->route('booking.staff-profile', [$serviceRequest->id, $shortlistItem->id])
            ->with('profile_unlocked', true)
            ->with('success', 'Profile unlocked! 1 credit has been deducted.');
    }

    /**
     * Ensure the authenticated user owns this service request.
     */
    private function authorizeClient(ServiceRequest $serviceRequest): void
    {
        if ((int) $serviceRequest->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }
    }

    /**
     * Has this gateway reference already been settled by any payment flow?
     *
     * Each flow writes to its own table, so a per-table check would let a
     * reference settled elsewhere (credits, registration, live-out) be
     * replayed against a booking.
     */
    private function referenceAlreadyConsumed(string $reference): bool
    {
        return BookingPayment::where('transaction_reference', $reference)->exists()
            || \App\Models\RegistrationPayment::where('transaction_reference', $reference)->exists()
            || \App\Models\LiveOutPayment::where('transaction_reference', $reference)->exists()
            || \App\Models\CreditTransaction::where('reference', $reference)->exists();
    }


    /**
     * Cancel order from shortlist page
     */
    public function cancelOrder(Request $request, ServiceRequest $serviceRequest)
    {
        $this->authorizeClient($serviceRequest);

        if ($serviceRequest->payment_status === 'Paid' && $serviceRequest->management_plan === 'company-managed') {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be cancelled as payment has already been made.'
            ], 400);
        }

        $serviceRequest->update([
            'status' => 'order_cancelled',
            'matching_status' => 'cancelled',
        ]);

        $affectedStaff = StaffShortlist::where('service_request_id', $serviceRequest->id)
            ->whereIn('status', ['notified', 'accepted', 'shortlisted'])
            ->with('staff')
            ->get();

        StaffShortlist::where('service_request_id', $serviceRequest->id)
            ->whereIn('status', ['notified', 'accepted', 'shortlisted'])
            ->update(['status' => 'expired']);

        foreach ($affectedStaff as $shortlist) {
            if ($shortlist->staff) {
                try {
                    $shortlist->staff->notify(new \App\Notifications\OrderCancelledNotification($serviceRequest));
                } catch (\Exception $e) {
                    Log::error('OrderCancelled notify: ' . $e->getMessage());
                }
            }
        }

        $user = Auth::user();
        $user->addCredits(1, 'Credit refunded — booking #' . $serviceRequest->id . ' cancelled');

        return response()->json([
            'success' => true,
            'message' => 'Your booking has been cancelled and 1 credit has been refunded.',
            'redirect' => route('dashboard'),
        ]);
    }
}
