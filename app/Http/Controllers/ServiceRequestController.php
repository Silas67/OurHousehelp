<?php

namespace App\Http\Controllers;

use App\Models\Apartments;
use App\Models\ExtraServiceCost;
use App\Models\Package;
use App\Models\RegistrationPayment;
use App\Models\Service;
use App\Models\ServiceCharge;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\BankReceiptUploadedNotification;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    // Personal Information (For new users)
    public function showPersonalInfo(Request $request)
    {
        // If coming from service request, store the data
        if ($request->has('from_service_request')) {
            session(['from_service_request' => true]);
        }
        return view('service-requests.personal-info');
    }

    public function savePersonalInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:male,female',
            'country_of_residence' => 'required|string|max:255',
            'state_of_residence' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'dob_month' => 'nullable|integer|min:1|max:12',
            'dob_day' => 'nullable|integer|min:1|max:31',
        ]);

        // Check if user already exists with this email
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'country_of_residence' => $validated['country_of_residence'],
                'state_of_residence' => $validated['state_of_residence'],
                'address' => $validated['address'],
                'landmark' => $validated['landmark'],
                'dob_month' => $validated['dob_month'],
                'dob_day' => $validated['dob_day'],
                'account_type' => 'client',
                'is_registered' => false,
                'password' => Hash::make($validated['phone']), // Temporary password
            ]);
        }

        // Log in the user
        Auth::login($user);

        // Check if coming from service request
        if (session('from_service_request') && session('service_request_data')) {
            // Store user ID in service request data
            $serviceRequestData = session('service_request_data');
            $serviceRequestData['user_id'] = $user->id;
            session(['service_request_data' => $serviceRequestData]);

            // Redirect to registration
            return redirect()->route('service-request.registration')
                ->with('success', 'Personal information saved! Please complete registration to continue with your service request.');
        }

        // Regular flow - go to registration
        return redirect()->route('service-request.registration')
            ->with('success', 'Personal information saved! Please complete registration.');
    }



    // Show service request form (accessible to all)
    public function showServiceRequest()
    {
        $user = auth()->user();
        $allServices = Service::all();
        $apartments = Apartments::all();
        $packages = Package::all();

        return view('service-requests.service-request', compact('user', 'allServices', 'apartments', 'packages'));
    }

    public function saveServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'serviceName' => 'required|array|min:1',
            'serviceName.*' => 'exists:services,id',
            'apartment_type' => 'nullable|exists:apartments,id',
            'package' => 'required|exists:packages,id',
            'service_days' => 'required|array|min:1',
            'days_per_week' => 'required|integer|min:1',
            'percentage' => 'nullable|numeric|min:0',
            'terms' => 'required|accepted',
        ]);

        $user = auth()->user();

        if (!$user) {
            // Guest user - store data and redirect to personal info
            session(['service_request_data' => $validated]);
            session(['from_service_request' => true]);

            return redirect()->route('service-request.personal-info')
                ->with('info', 'Please provide your personal information to continue with your service request.');
        }

        // Check profile completeness
        $requiredProfileFields = ['name', 'last_name', 'email', 'phone'];
        $missingFields = collect($requiredProfileFields)->filter(fn($field) => empty($user->{$field}));

        if ($missingFields->isNotEmpty()) {
            return redirect()->route('clientprofile')
                ->with('warning', 'Your profile is incomplete. Please update your profile before submitting a service request.');
        }

        // Check if user is registered
        if (!$user->is_registered) {
            // Store service request data in session
            session(['pending_service_request' => $validated]);

            return redirect()->route('service-request.registration')
                ->with('warning', 'Please complete your registration to submit this service request.');
        }

        // User is registered, create service request
        try {
            $serviceRequest = $this->createServiceRequest($validated, $user);

            // Redirect to bookings page instead of confirmation
            return redirect()->route('bookings')
                ->with('success', 'Service request created successfully!');
        } catch (\Exception $e) {
            \Log::error('Service request creation failed: ' . $e->getMessage());

            return redirect()->route('clientprofile')
                ->with('error', 'Failed to create service request. Please ensure your profile is complete.');
        }
    }



    private function createServiceRequest($data, $user)
    {
        // Load selected services
        $services = Service::whereIn('id', $data['serviceName'])->get();

        // Get service names from loaded services
        $serviceNames = $services->pluck('service_name')->toArray();


        // Load package
        $package = Package::find($data['package']);

        // Load charges (adjust based on your database)
        $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
        $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

        // Calculate costs (your existing calculation logic)
        $apartmentCost = 0;
        $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');

        if ($containsPRS && isset($data['apartment_type'])) {
            $apartment = Apartments::find($data['apartment_type']);
            $apartmentCost = (float) ($apartment?->cost ?? 0);
        }

        $effectiveCosts = [];
        foreach ($services as $s) {
            if ($s->service_type === 'PRS') {
                $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
            } else {
                $effectiveCosts[$s->id] = (float) $s->base_cost;
            }
        }

        $mainServiceId = array_keys($effectiveCosts, max($effectiveCosts))[0];
        $baseCost = $effectiveCosts[$mainServiceId];

        $extraContributionTotal = 0.0;
        foreach ($services as $s) {
            if ($s->id == $mainServiceId) continue;

            switch ($s->service_type) {
                case 'PRS':
                    $prsCost = $apartmentCost ?: (float) $s->base_cost;
                    $extraContributionTotal += $prsCost / 2;
                    break;
                case 'PMS':
                    $extraContributionTotal += ((float) $s->base_cost) / 2;
                    break;
                case 'PSS':
                    $extraContributionTotal += (float) $extraServiceCost;
                    break;
                default:
                    $extraContributionTotal += (float) $extraServiceCost;
                    break;
            }
        }

        $packageCost = (float) ($package->cost ?? 0);
        $totalServiceCost = $baseCost + $packageCost + $extraContributionTotal;
        $grandTotal = $totalServiceCost + (float) $serviceCharge;

        // Prepare service request data
        $serviceRequestData = [
            'user_id' => $user->id,
            'name' => $user->name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'country_of_residence' => $user->country_of_residence,
            'state_of_residence' => $user->state_of_residence,
            'address' => $user->address,
            'landmark' => $user->landmark,
            'package' => $package->name,
            'days_per_week' => $data['days_per_week'],
            'service_days' => json_encode($data['service_days']),
            // 'serviceName' => json_encode($data['serviceName']),
            'serviceName' => json_encode($serviceNames),
            'base_cost' => $baseCost,
            'extra_service_cost' => $extraContributionTotal,
            'service_charge' => $serviceCharge,
            'package_cost' => $packageCost,
            'grand_cost' => $grandTotal,
            'percentage' => 0.0,
            'discounted_cost' => 0,
            'payment_status' => 'Pending',
            'status' => 'pending',
            'registration_paid' => $user->is_registered,
            'registration_status' => $user->is_registered ? 'active' : 'pending',
            'registration_expires_at' => $user->is_registered ? $user->registration_expires_at : null,
        ];

        // Add optional fields
        if ($user->dob_month) $serviceRequestData['dob_month'] = $user->dob_month;
        if ($user->dob_day) $serviceRequestData['dob_day'] = $user->dob_day;
        if ($containsPRS && isset($data['apartment_type'])) {
            $serviceRequestData['apartment_type'] = $data['apartment_type'];
        }

        // Add nullable fields to prevent errors
        $serviceRequestData = array_merge([
            'applicant_type' => null,
            'features' => null,
            'working_hour' => null,
            'nin' => null,
            'details' => null,
            'on_behalf' => 'no',
            'passport' => null,
            'id_card' => null,
            'bank_receipt' => null,
            'bonus_slots_total' => 0,
            'bonus_slots_used' => 0,
            'service_charge_id' => null,
        ], $serviceRequestData);

        $serviceRequest = ServiceRequest::create($serviceRequestData);

        // If you have the services() relationship defined, use it
        // Otherwise, comment this out and just store IDs in JSON field
        if (method_exists($serviceRequest, 'services')) {
            try {
                $serviceRequest->services()->attach($data['serviceName']);
            } catch (\Exception $e) {
                \Log::error('Failed to attach services: ' . $e->getMessage());
            }
        }

        // Notify admin about the new service request
        try {
            $admins = User::whereIn('user_role', [1, 2])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewServiceRequestNotification($serviceRequest));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send new service request notification to admin: ' . $e->getMessage());
        }

        return $serviceRequest;
    }



    // Calculate total amount
    private function calculateTotalAmount($data)
    {
        $serviceCharge = 10000;
        $extraServiceCost = 12000;

        // Get package cost
        $package = Package::find($data['package']);
        $packageCost = $package->cost ?? 0;

        // Get selected services
        $services = Service::whereIn('id', $data['serviceName'])->get();

        // Calculate base cost (highest cost service)
        $baseCost = $services->max('base_cost') ?? 0;

        // Calculate extra services cost
        $extraCost = 0;
        $mainServiceId = $services->sortByDesc('base_cost')->first()->id ?? null;

        foreach ($services as $service) {
            if ($service->id === $mainServiceId) continue;

            switch ($service->service_type) {
                case 'PRS':
                    $extraCost += ($baseCost / 2);
                    break;
                case 'PMS':
                    $extraCost += ($service->base_cost / 2);
                    break;
                case 'PSS':
                default:
                    $extraCost += $extraServiceCost;
                    break;
            }
        }

        return $serviceCharge + $packageCost + $baseCost + $extraCost;
    }

    // Generate transaction reference
    private function generateTransactionReference()
    {
        do {
            $ref = 'TXN' . date('YmdHis') . strtoupper(Str::random(4));
        } while (RegistrationPayment::where('transaction_reference', $ref)->exists());

        return $ref;
    }

    // Show registration payment form
    public function showRegistration()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Already fully registered
        if ($user->is_registered) {
            if (session()->has('service_request_data')) {
                return redirect()
                    ->route('service-request.create')
                    ->with('info', 'You are already registered. You can now complete your service request.');
            }

            return redirect()
                ->route('dashboard')
                ->with('info', 'You are already registered.');
        }

        // Registration payment exists but pending
        if ($user->hasPendingRegistration()) {
            return redirect()
                ->route('bookings')
                ->with('info', 'Your registration is pending. Waiting for approval.');
        }

        // No payment yet → show registration form
        return view('service-requests.registration', compact('user'));
    }



    // Save registration payment
    public function saveRegistration(Request $request, User $user)
    {
        if (!$user) {
            return redirect()->route('login');
        }

        // --- Check profile completeness ---
        $requiredProfileFields = ['name', 'last_name', 'email', 'phone'];
        $missingFields = collect($requiredProfileFields)->filter(fn($field) => empty($user->{$field}));

        if ($missingFields->isNotEmpty()) {
            return redirect()->route('clientprofile')
                ->with('error', 'Your profile is incomplete. Please update your profile before completing registration.');
        }

        // --- Validate payment input ---
        $validated = $request->validate([
            'payment_choice' => 'required|in:now,later',
            'payment_method' => 'required_if:payment_choice,now|in:bank_transfer,card,wallet',
            'bank_reference' => 'required_if:payment_method,bank_transfer|string|max:255',
            'transfer_date' => 'required_if:payment_method,bank_transfer|date|before_or_equal:today',
            'bank_receipt' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'card_number' => 'required_if:payment_method,card|string|min:13|max:19',
            'card_expiry' => 'required_if:payment_method,card|string|max:5',
            'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',
            'cardholder_name' => 'required_if:payment_method,card|string|max:255',
            'wallet_pin' => 'required_if:payment_method,wallet|string|min:6|max:6',
            'payment_terms' => 'required_if:payment_choice,now|accepted',
        ]);

        // --- Process payment ---
        if ($validated['payment_choice'] === 'now') {
            $transactionRef = $this->generateTransactionReference();

            $registrationPayment = RegistrationPayment::create([
                'user_id' => $user->id,
                'amount' => 10000,
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $transactionRef,
                'status' => $validated['payment_method'] === 'bank_transfer' ? 'pending_approval' : 'completed',
                'bank_receipt' => ($request->file('bank_receipt') ? \App\Support\SecureDocument::store($request->file('bank_receipt'), 'receipts') : null),
                'paid_at' => $validated['payment_method'] === 'bank_transfer' ? null : now(),
                'expires_at' => now()->addYear(),
            ]);

            // Notify admin when bank transfer receipt is uploaded
            if ($validated['payment_method'] === 'bank_transfer') {
                try {
                    $admins = User::whereIn('user_role', [1, 2])->get();
                    if ($admins->count() > 0) {
                        Notification::send($admins, new BankReceiptUploadedNotification($registrationPayment, $user));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send bank receipt notification to admin: ' . $e->getMessage());
                }
            }

            if ($validated['payment_method'] !== 'bank_transfer') {
                $user->update([
                    'is_registered' => true,
                    'registration_date' => now(),
                    'registration_expires_at' => now()->addYear(),
                ]);

                $message = 'Registration completed! Your account is now active.';
            } else {
                $message = 'Registration payment submitted. Your account will be activated after admin approval.';
            }
        } else {
            // Pay later - still mark as registered but with pending payment
            $user->update([
                'is_registered' => true,
                'registration_date' => now(),
                'registration_expires_at' => now()->addYear(),
            ]);

            $message = 'Registration completed with pending payment. Please complete payment within 7 days.';
        }

        // --- Handle any pending service request ---
        if (session('service_request_data')) {
            $serviceRequestData = session('service_request_data');
            session()->forget('service_request_data');
            session()->forget('from_service_request');

            $serviceRequest = $this->createServiceRequest($serviceRequestData, $user);

            return redirect()->route('bookings')
                ->with('success', $message . ' Your service request has been submitted!');
        }

        if (session('pending_service_request')) {
            $serviceRequestData = session('pending_service_request');
            session()->forget('pending_service_request');

            $serviceRequest = $this->createServiceRequest($serviceRequestData, $user);

            return redirect()->route('bookings')
                ->with('success', 'Registration completed! Your service request has been submitted.');
        }

        // Default redirect to dashboard
        return redirect()->route('dashboard')
            ->with('success', $message);
    }


    // Confirmation page
    public function showConfirmation($id)
    {
        $serviceRequest = ServiceRequest::with(['user', 'services', 'package'])->findOrFail($id);

        // Check if user owns this request or is admin
        if (auth()->id() !== $serviceRequest->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        return view('service-requests.confirmation', compact('serviceRequest'));
    }
}
