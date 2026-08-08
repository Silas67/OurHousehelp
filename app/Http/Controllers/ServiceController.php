<?php

namespace App\Http\Controllers;

use App\Models\Apartments;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Package;
use App\Models\Discount;
use App\Models\ExtraServiceCost;
use App\Models\Lead;
use App\Models\User;
use App\Models\Service;
use App\Models\ServicePackageCost;
use App\Models\MultipleService;
use App\Models\ServiceCharge;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\Services\RegistrationService;
use App\Models\RegistrationPayment;
use App\Notifications\ServiceRequestApprovedNotification;
use App\Notifications\ServiceRequestStatusNotification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Log; // Add this import


class ServiceController extends Controller
{

    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }


    // public function showOrderReceipt($id)
    // {
    //     // Retrieve the service request details
    //     $order = ServiceRequest::findOrFail($id);
    //     // Show the receipt page with the order details
    //     return view('orderreceipt', compact('order'));
    // }

    public function showOrderReceipt($id)
    {
        $order = ServiceRequest::with('serviceCharge')->findOrFail($id);
        return view('orderreceipt', compact('order'));
    }


    private function handleFileUpload($file, $path)
    {
        if ($file) {
            return $file->storeAs(
                $path,
                time() . '_' . $file->getClientOriginalName(),
                'public'
            );
        }
        return null;
    }


    public function createultipleservice(Request $request)
    {
        $services = Service::all(); // Fetch all services from the database
        return view('admin.multipleServices', compact('services'));
    }


    public function searchOrders(Request $request)
    {
        $query = $request->input('q');
        $orders = ServiceRequest::where('name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('state_of_residence', 'LIKE', "%{$query}%")
            ->orWhere('package', 'LIKE', "%{$query}%")
            ->get();

        return response()->json(['orders' => $orders]);
    }


    public function updateStatus(Request $request, $serviceRequestId)
    {
        // Find the service request by its ID
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);

        $previousStatus = $serviceRequest->status;

        // Update the status
        $serviceRequest->status = $request->status;
        $serviceRequest->save();

        // Notify all approved staff when service request is confirmed
        // Guard: only send if status actually changed to confirmed (avoid double notification from approvePayment)
        if ($request->status === 'confirmed' && $previousStatus !== 'confirmed') {
            // Check if staff were already notified for this request (from payment approval)
            $alreadyNotified = \DB::table('notifications')
                ->where('type', 'App\\Notifications\\ServiceRequestApprovedNotification')
                ->where('data', 'like', '%"service_request_id":' . $serviceRequest->id . '%')
                ->exists();

            if (!$alreadyNotified) {
                try {
                    $approvedStaff = User::whereIn('account_type', ['applicant', 'staff'])
                        ->where('approval_status', 'approved')
                        ->get();

                    if ($approvedStaff->count() > 0) {
                        Notification::send($approvedStaff, new ServiceRequestApprovedNotification($serviceRequest));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send staff notifications: ' . $e->getMessage());
                }
            }
        }

        // Notify the client about their service request status change
        if ($previousStatus !== $request->status && $serviceRequest->user_id) {
            try {
                $client = User::find($serviceRequest->user_id);
                if ($client) {
                    $client->notify(new ServiceRequestStatusNotification($serviceRequest, $request->status));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send client status notification: ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Service request status updated successfully']);
    }


    // Method to show the assign house help view
    public function assignHouseHelpView($serviceRequestId)
    {
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);
        $availableHouseHelps = User::where('availability', true)->where('account_type', 'househelp')->get(); // Assuming house helps are available

        return view('admin.assign-house-help', compact('serviceRequest', 'availableHouseHelps'));
    }


    public function updateActive(Request $request, $serviceRequestId)
    {
        // Find the service request by its ID
        $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);

        $previousStatus = $serviceRequest->status;

        // Update the status
        $serviceRequest->status = $request->status;
        $serviceRequest->save();

        // Notify all approved staff when service request is activated
        if ($request->status === 'active' && $previousStatus !== 'active') {
            try {
                $approvedStaff = User::whereIn('account_type', ['applicant', 'staff'])
                    ->where('approval_status', 'approved')
                    ->get();

                if ($approvedStaff->count() > 0) {
                    Notification::send($approvedStaff, new ServiceRequestApprovedNotification($serviceRequest));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send staff notifications: ' . $e->getMessage());
            }
        }

        // Notify the client about their service request status change
        if ($previousStatus !== $request->status && $serviceRequest->user_id) {
            try {
                $client = User::find($serviceRequest->user_id);
                if ($client) {
                    $client->notify(new ServiceRequestStatusNotification($serviceRequest, $request->status));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send client status notification: ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Service request status updated successfully']);
    }


    public function createservice()
    {
        $packages = Package::all();
        return view('admin.newservice', compact('packages'));
    }



    public function servicestore(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'service_name'          => 'required|string|max:255',
            'about_service'         => 'required|string',
            'icon_class'            => 'nullable|string|max:255',
            'hours_of_work'         => 'nullable|integer|min:0',
            'service_type'          => 'required|in:PMS,PSS,PRS',
            'number_of_apartment'   => 'nullable|boolean',
            'dos_and_donts'         => 'nullable|string',
            'base_cost'             => 'required_if:service_type,PMS,PSS|nullable|numeric|min:0',
            'package_name'          => 'nullable|array',
            'package_cost'          => 'nullable|array',
            'package_name.*'        => 'nullable|string|max:255',
            'package_cost.*'        => 'nullable|numeric|min:0',
        ]);


        // Create the service and retrieve its ID
        $service = Service::create([
            'service_name'        => $validated['service_name'],
            'about_service'       => $validated['about_service'],
            'icon_class'          => $validated['icon_class'] ?? null,
            'hours_of_work'       => $validated['hours_of_work'] ?? 0,
            'service_type'        => $validated['service_type'],
            'number_of_apartment' => $validated['number_of_apartment'] ?? false,
            'dos_and_donts'       => $validated['dos_and_donts'] ?? null,
            'base_cost'           => $validated['base_cost'], // Add base cost
        ]);

        return redirect()->back()->with('success', 'Service created successfully.');
    }





    // Display all services
    public function allservices()
    {
        $services = Service::with('servicePackageCosts')->get();  // Eager load the servicePackageCosts relationship
        $packages = ServicePackageCost::all();

        return view('admin.allservices', compact('services', 'packages'));
    }


    public function editservice($id)
    {
        $services = Service::with('servicePackageCosts')->get();  // Eager load the servicePackageCosts relationship
        $service = Service::findOrFail($id);
        $packages = ServicePackageCost::all();
        return view('admin.editservice', compact('services', 'service', 'packages'));
    }


    public function updateservice(Request $request, $id)
    {
        $validated = $request->validate([
            'service_name'        => 'required|string|max:255',
            'about_service'       => 'required|string',
            'icon_class'          => 'nullable|string',
            'hours_of_work'       => 'nullable|numeric|min:0',
            'premium'             => 'nullable|boolean',
            'number_of_apartment' => 'nullable|boolean',
            'special_services'    => 'nullable|boolean',
            'dos_and_donts'       => 'nullable|string',
            'package_name'        => 'nullable|array',
            'package_cost'        => 'nullable|array',
            'base_cost'           => 'required_if:service_type,PMS,PSS|nullable|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'service_name'        => $validated['service_name'],
            'about_service'       => $validated['about_service'],
            'icon_class'          => $validated['icon_class'] ?? null,
            'hours_of_work'       => $validated['hours_of_work'] ?? 0,
            'premium'             => $validated['premium'] ?? 0,
            'number_of_apartment' => $validated['number_of_apartment'] ?? 0,
            'special_services'    => $validated['special_services'] ?? 0,
            'dos_and_donts'       => $validated['dos_and_donts'] ?? null,
            'base_cost'           => $validated['base_cost'] ?? 0, // Ensure default value
        ]);

        // Save or update package costs in the `service_package_costs` table
        if ($request->has('package_name') && $request->has('package_cost')) {
            foreach ($request->package_name as $index => $name) {
                $cost = $request->package_cost[$index] ?? 0;

                // Retrieve the old package if it exists
                $existingPackage = ServicePackageCost::where('service_id', $service->id)
                    ->where('name', $name)
                    ->first();

                if ($existingPackage) {
                    $existingPackage->update(['cost' => $cost]);
                } else {
                    // Create a new package if it doesn't exist
                    ServicePackageCost::create([
                        'service_id' => $service->id,
                        'name'       => $name,
                        'cost'       => $cost,
                    ]);
                }
            }
        }

        return redirect()->route('admin.allservices')->with('success', 'Service updated successfully.');
    }



    // Delete a service
    public function servicedestroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }

    // Show the service request form
    public function requestForm($id)
    {
        $service = Service::findOrFail($id); // Will throw an error if service is not found
        $packages = Package::all(); // Fetch all package data
        $servicePackageCosts = ServicePackageCost::where('service_id', $id)->get(); // Fetch service-specific costs
        $apartmentdetails = Apartments::all(); // Fetch all apartment details

        // Check if there is any service package costs
        if ($servicePackageCosts->isEmpty()) {
            // Optional: Handle scenario where no service package costs are found
            // You could pass a message to the view if necessary
        }

        return view('services.request', compact('service', 'packages', 'servicePackageCosts', 'apartmentdetails'));
    }

    public function multipleSelectedServices(Request $request)
    {
        // Fetch services with base_cost > 0
        $servicesWithCost = Service::where('base_cost', '>', 0)->get();

        // Fetch services with base_cost as null
        $servicesWithoutCost = Service::whereNull('base_cost')->get();

        // Single query to fetch all services, ordered by cost (nulls last)
        $services = Service::orderByRaw('ISNULL(base_cost), base_cost DESC')->get();

        // Fetch all services
        $allServices = Service::all();

        // Fetch all packages with related services
        $packages = Package::with('service')->get();

        // Fetch all apartments
        $apartments = Apartments::all();

        // Fetch all service package costs
        $servicePackageCosts = ServicePackageCost::all();

        // Prepare services with hours of work
        $servicesWithHours = $allServices->map(function ($service) {
            return [
                'service_name' => $service->service_name,
                'hours_of_work' => $service->hours_of_work,
            ];
        });

        // Fetch global charges
        $serviceCharge = ServiceCharge::first()?->amount ?? 10000;

        // Fetch extra service cost
        $extraServiceCost = ExtraServiceCost::first()?->cost ?? 10000;

        // Determine discount based on service count
        $serviceCount = count($request->input('serviceName', []));
        $discount = Discount::where('number_of_services', $serviceCount)->first();
        $discountPercent = $discount ? ($discount->discount_percent / 100) : 0;

        return view('services.multipleSelectedServices', compact(
            'packages',
            'servicePackageCosts',
            'apartments',
            'servicesWithoutCost',
            'servicesWithHours',
            'servicesWithCost',
            'services',
            'allServices',
            'serviceCharge',
            'extraServiceCost',
            'discountPercent'
        ));
    }

    // public function multipleSelectedServicesStore(Request $request)
    // {
    //     // 1️⃣ Validate request data
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'middle_name' => 'nullable|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //         'phone' => 'required|string|max:15',
    //         'gender' => 'required|in:male,female,no_preference',
    //         'country_of_residence' => 'required|string|max:255',
    //         'state_of_residence' => 'required|string|max:255',
    //         'address' => 'required|string|max:255',
    //         'landmark' => 'nullable|string|max:255',
    //         'package' => 'required|string|max:255',
    //         'on_behalf' => 'required|in:yes,no',
    //         'dob_month' => 'nullable|integer|min:0|max:12',
    //         'dob_day' => 'nullable|integer|min:0|max:31',
    //         'serviceName' => 'required|array|min:1',
    //         'serviceName.*' => 'exists:services,id',
    //         'apartment_type' => 'nullable|exists:apartments,id',
    //         'days_per_week' => 'required|integer|min:1',
    //         'percentage' => 'nullable|numeric|min:0',
    //         'working_hour' => 'nullable|string',
    //         'passport' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //         'id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',

    //         // 🔐 Payment Validation Rules
    //         'payment_method' => 'required|in:card,bank_transfer,wallet',
    //         'payment_terms' => 'required|accepted',

    //         // Card payment validation (required if payment method is card)
    //         'card_number' => 'required_if:payment_method,card|string|min:13|max:19',
    //         'card_expiry' => 'required_if:payment_method,card|string|max:5',
    //         'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',
    //         'cardholder_name' => 'required_if:payment_method,card|string|max:255',

    //         // Bank transfer validation
    //         'bank_reference' => 'required_if:payment_method,bank_transfer|string|max:255',
    //         'transfer_date' => 'required_if:payment_method,bank_transfer|date|before_or_equal:today',
    //         'bank_receipt' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',

    //         // Wallet validation
    //         'wallet_pin' => 'required_if:payment_method,wallet|string|min:6|max:6',
    //     ], [
    //         // Custom error messages
    //         'payment_method.required' => 'Please select a payment method.',
    //         'payment_method.in' => 'Please select a valid payment method.',
    //         'payment_terms.required' => 'You must accept the payment terms to proceed.',
    //         'payment_terms.accepted' => 'You must accept the payment terms to proceed.',

    //         'card_number.required_if' => 'Card number is required for card payments.',
    //         'card_number.min' => 'Card number must be at least 13 digits.',
    //         'card_number.max' => 'Card number cannot exceed 19 digits.',

    //         'card_expiry.required_if' => 'Expiry date is required for card payments.',
    //         'card_expiry.max' => 'Expiry date must be in MM/YY format.',

    //         'card_cvv.required_if' => 'CVV is required for card payments.',
    //         'card_cvv.min' => 'CVV must be at least 3 digits.',
    //         'card_cvv.max' => 'CVV cannot exceed 4 digits.',

    //         'cardholder_name.required_if' => 'Cardholder name is required for card payments.',

    //         'bank_reference.required_if' => 'Bank transfer reference is required.',
    //         'transfer_date.required_if' => 'Transfer date is required.',
    //         'transfer_date.before_or_equal' => 'Transfer date cannot be in the future.',
    //         'bank_receipt.required_if' => 'Bank transfer receipt is required.',
    //         'bank_receipt.mimes' => 'Receipt must be a JPG, PNG, or PDF file.',
    //         'bank_receipt.max' => 'Receipt file size cannot exceed 5MB.',

    //         'wallet_pin.required_if' => 'Wallet PIN is required for wallet payments.',
    //         'wallet_pin.min' => 'Wallet PIN must be exactly 6 digits.',
    //         'wallet_pin.max' => 'Wallet PIN must be exactly 6 digits.',
    //     ]);

    //     // 2️⃣ Additional Payment Validation Logic
    //     $this->validatePaymentDetails($request);

    //     // 3️⃣ Load selected services
    //     $services = Service::whereIn('id', $request->input('serviceName'))->get();
    //     if ($services->isEmpty()) {
    //         return back()->withErrors(['serviceName' => 'No valid services selected.']);
    //     }

    //     // 4️⃣ Load package and dynamic charges
    //     $package = Package::find($request->package);
    //     if (!$package) {
    //         return back()->withErrors(['package' => 'Invalid package selected.']);
    //     }

    //     $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
    //     $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

    //     // 5️⃣ Apartment (PRS) cost setup
    //     $apartmentCost = 0;
    //     $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
    //     if ($containsPRS && $request->filled('apartment_type')) {
    //         $apartment = Apartments::find($request->apartment_type);
    //         $apartmentCost = (float) ($apartment?->cost ?? 0);
    //     }

    //     // 6️⃣ Build effective cost for each service
    //     $effectiveCosts = [];
    //     foreach ($services as $s) {
    //         if ($s->service_type === 'PRS') {
    //             $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
    //         } else {
    //             $effectiveCosts[$s->id] = (float) $s->base_cost;
    //         }
    //     }

    //     // 7️⃣ Determine main (base) service
    //     $mainServiceId = array_keys($effectiveCosts, max($effectiveCosts))[0];
    //     $baseCost = $effectiveCosts[$mainServiceId];

    //     // 8️⃣ Calculate total extra service cost (applying rules)
    //     $extraContributionTotal = 0.0;
    //     foreach ($services as $s) {
    //         if ($s->id == $mainServiceId) {
    //             continue;
    //         }

    //         switch ($s->service_type) {
    //             case 'PRS':
    //                 $prsCost = $apartmentCost ?: (float) $s->base_cost;
    //                 $extraContributionTotal += $prsCost / 2;
    //                 break;
    //             case 'PMS':
    //                 $extraContributionTotal += ((float) $s->base_cost) / 2;
    //                 break;
    //             case 'PSS':
    //                 $extraContributionTotal += (float) $extraServiceCost;
    //                 break;
    //             default:
    //                 $extraContributionTotal += (float) $extraServiceCost;
    //                 break;
    //         }
    //     }

    //     // 9️⃣ Calculate totals
    //     $packageCost = (float) ($package->cost ?? 0);
    //     $totalServiceCost = $baseCost + $packageCost + $extraContributionTotal;
    //     $grandTotal = $totalServiceCost + (float) $serviceCharge;

    //     // 🔟 Prepare readable and storable values
    //     $serviceNamesReadable = $services->pluck('service_name')->implode(', ');
    //     $serviceIds = $services->pluck('id')->toArray();

    //     // 1️⃣1️⃣ Handle file uploads
    //     $passportPath = $request->file('passport')?->store('passports', 'public');
    //     $idCardPath = $request->file('id_card')?->store('idcards', 'public');
    //     $bankReceiptPath = $request->file('bank_receipt')?->store('bank_receipts', 'public');

    //     // 1️⃣2️⃣ Persist to database FIRST
    //     $serviceRequest = ServiceRequest::create([
    //         'user_id' => auth()->id(),
    //         'name' => $request->name,
    //         'middle_name' => $request->middle_name,
    //         'last_name' => $request->last_name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'gender' => $request->gender,
    //         'country_of_residence' => $request->country_of_residence,
    //         'state_of_residence' => $request->state_of_residence,
    //         'address' => $request->address,
    //         'landmark' => $request->landmark,
    //         'dob_month' => $request->dob_month ?? 0,
    //         'dob_day' => $request->dob_day ?? 0,
    //         'package' => $package->name ?? null,
    //         'days_per_week' => $request->days_per_week,
    //         'service_days' => $request->service_days ?? [],
    //         'serviceName' => json_encode($serviceIds),
    //         'service_names' => $serviceNamesReadable,
    //         'apartment_type' => $request->apartment_type,
    //         'base_cost' => $baseCost,
    //         'extra_service_cost' => $extraContributionTotal,
    //         'service_charge' => $serviceCharge,
    //         'package_cost' => $packageCost,
    //         'grand_cost' => $grandTotal,
    //         'on_behalf' => $request->on_behalf,
    //         'percentage' => $package->percentage ?? 0,
    //         'discounted_cost' => 0,
    //         'working_hour' => $request->working_hour ?? null,
    //         'payment_status' => 'Pending',
    //         'status' => 'pending',
    //         'passport' => $passportPath,
    //         'id_card' => $idCardPath,
    //         'bank_receipt' => $bankReceiptPath,
    //         'registration_paid' => false,
    //         'registration_status' => 'pending',
    //     ]);

    //     // 1️⃣3️⃣ Process registration payment
    //     try {
    //         $paymentData = [
    //             'amount' => 10000,
    //             'payment_method' => $request->payment_method,
    //             'transaction_reference' => $this->generateTransactionReference(),
    //             'payment_details' => $this->preparePaymentDetails($request)
    //         ];

    //         // Validate card details if payment method is card
    //         if ($request->payment_method === 'card') {
    //             $cardValidation = $this->validateCardDetails($request);
    //             if (!$cardValidation['valid']) {
    //                 throw new \Exception($cardValidation['message']);
    //             }
    //             $paymentData['card_details'] = $cardValidation['details'];
    //         }

    //         // For bank transfers, set status to pending_approval
    //         if ($request->payment_method === 'bank_transfer') {
    //             $paymentData['status'] = 'pending_approval';
    //             $paymentData['bank_reference'] = $request->bank_reference;
    //             $paymentData['transfer_date'] = $request->transfer_date;
    //         }

    //         // Process payment
    //         $registrationPayment = $this->registrationService->processRegistrationPayment(
    //             auth()->user(),
    //             $serviceRequest,
    //             $paymentData
    //         );

    //         // Update service request based on payment status
    //         if ($request->payment_method === 'bank_transfer') {
    //             $serviceRequest->update([
    //                 'payment_status' => 'Pending Approval',
    //                 'registration_status' => 'pending_approval'
    //             ]);
    //             $successMessage = 'Thank you for submitting your service request! Your bank transfer details have been received and are pending admin approval. You will be notified once approved.';
    //         } else {
    //             $serviceRequest->update([
    //                 'payment_status' => 'Paid',
    //                 'registration_paid' => true,
    //                 'registration_paid_at' => now(),
    //                 'registration_expires_at' => now()->addYear(),
    //                 'registration_status' => 'active'
    //             ]);
    //             $successMessage = 'Thank you for submitting your service request and payment! Your registration is now active.';
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Payment processing failed: ' . $e->getMessage());

    //         // Update service request to reflect payment failure
    //         $serviceRequest->update([
    //             'payment_status' => 'Failed',
    //             'registration_status' => 'payment_failed'
    //         ]);

    //         $successMessage = 'Thank you for submitting your service request! However, there was an issue with your payment: ' . $e->getMessage() . ' Please contact support.';
    //     }

    //     // 1️⃣4️⃣ Redirect with confirmation
    //     return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])
    //         ->with('success', $successMessage);
    // }


    // public function multipleSelectedServicesStore(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // 1️⃣ Check if user is authenticated
    //         $isAuthenticated = auth()->check();

    //         // 2️⃣ Validate request data (slightly modified for guest users)
    //         $validationRules = [
    //             'name' => 'required|string|max:255',
    //             'middle_name' => 'nullable|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'required|email',
    //             'phone' => 'required|string|max:15',
    //             'gender' => 'required|in:male,female,no_preference',
    //             'country_of_residence' => 'required|string|max:255',
    //             'state_of_residence' => 'required|string|max:255',
    //             'address' => 'required|string|max:255',
    //             'landmark' => 'nullable|string|max:255',
    //             'package' => 'required|string|max:255',
    //             'on_behalf' => 'required|in:yes,no',
    //             'dob_month' => 'nullable|integer|min:0|max:12',
    //             'dob_day' => 'nullable|integer|min:0|max:31',
    //             'serviceName' => 'required|array|min:1',
    //             'serviceName.*' => 'exists:services,id',
    //             'apartment_type' => 'nullable|exists:apartments,id',
    //             'days_per_week' => 'required|integer|min:1',
    //             'percentage' => 'nullable|numeric|min:0',
    //             'working_hour' => 'nullable|string',
    //             'passport' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //             'id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //         ];

    //         // Only require payment validation if user is paying now
    //         if ($request->has('proceed_to_payment') && $request->proceed_to_payment == '1') {
    //             $validationRules = array_merge($validationRules, [
    //                 'payment_method' => 'required|in:card,bank_transfer,wallet',
    //                 'payment_terms' => 'required|accepted',
    //                 'card_number' => 'required_if:payment_method,card|string|min:13|max:19',
    //                 'card_expiry' => 'required_if:payment_method,card|string|max:5',
    //                 'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',
    //                 'cardholder_name' => 'required_if:payment_method,card|string|max:255',
    //                 'bank_reference' => 'required_if:payment_method,bank_transfer|string|max:255',
    //                 'transfer_date' => 'required_if:payment_method,bank_transfer|date|before_or_equal:today',
    //                 'bank_receipt' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',
    //                 'wallet_pin' => 'required_if:payment_method,wallet|string|min:6|max:6',
    //             ]);
    //         }

    //         $validated = $request->validate($validationRules, [
    //             // Your custom error messages
    //         ]);

    //         // 3️⃣ Check if user exists by email, create or authenticate
    //         $user = null;
    //         $isNewUser = false;
    //         $passwordSet = false;

    //         if ($isAuthenticated) {
    //             $user = auth()->user();
    //             $passwordSet = true; // Already has a password
    //         } else {
    //             // Check if user exists by email
    //             $user = User::where('email', $request->email)->first();

    //             if (!$user) {
    //                 // Create new user with phone number as password
    //                 $user = User::create([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'email' => $request->email,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'account_type' => 'client',
    //                     'password' => Hash::make($request->phone), // Phone number as password
    //                     'form_completed' => true, // Since they filled the service request
    //                 ]);
    //                 $isNewUser = true;
    //                 $passwordSet = true;

    //                 // Log the user in automatically
    //                 auth()->login($user);
    //             } else {
    //                 // Update existing user information
    //                 $user->update([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'form_completed' => true,
    //                 ]);

    //                 // Check if user has a password set
    //                 $passwordSet = !is_null($user->password);
    //             }
    //         }

    //         // 4️⃣ Load selected services
    //         $services = Service::whereIn('id', $request->input('serviceName'))->get();
    //         if ($services->isEmpty()) {
    //             return back()->withErrors(['serviceName' => 'No valid services selected.']);
    //         }

    //         // 5️⃣ Load package and dynamic charges
    //         $package = Package::find($request->package);
    //         if (!$package) {
    //             return back()->withErrors(['package' => 'Invalid package selected.']);
    //         }

    //         $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
    //         $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

    //         // 6️⃣ Calculate costs (your existing calculation logic)
    //         $apartmentCost = 0;
    //         $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
    //         if ($containsPRS && $request->filled('apartment_type')) {
    //             $apartment = Apartments::find($request->apartment_type);
    //             $apartmentCost = (float) ($apartment?->cost ?? 0);
    //         }

    //         $effectiveCosts = [];
    //         foreach ($services as $s) {
    //             if ($s->service_type === 'PRS') {
    //                 $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
    //             } else {
    //                 $effectiveCosts[$s->id] = (float) $s->base_cost;
    //             }
    //         }

    //         $mainServiceId = array_keys($effectiveCosts, max($effectiveCosts))[0];
    //         $baseCost = $effectiveCosts[$mainServiceId];

    //         $extraContributionTotal = 0.0;
    //         foreach ($services as $s) {
    //             if ($s->id == $mainServiceId) {
    //                 continue;
    //             }

    //             switch ($s->service_type) {
    //                 case 'PRS':
    //                     $prsCost = $apartmentCost ?: (float) $s->base_cost;
    //                     $extraContributionTotal += $prsCost / 2;
    //                     break;
    //                 case 'PMS':
    //                     $extraContributionTotal += ((float) $s->base_cost) / 2;
    //                     break;
    //                 case 'PSS':
    //                     $extraContributionTotal += (float) $extraServiceCost;
    //                     break;
    //                 default:
    //                     $extraContributionTotal += (float) $extraServiceCost;
    //                     break;
    //             }
    //         }

    //         $packageCost = (float) ($package->cost ?? 0);
    //         $totalServiceCost = $baseCost + $packageCost + $extraContributionTotal;
    //         $grandTotal = $totalServiceCost + (float) $serviceCharge;

    //         // 7️⃣ Prepare service details
    //         $serviceNamesReadable = $services->pluck('service_name')->implode(', ');
    //         $serviceIds = $services->pluck('id')->toArray();

    //         // 8️⃣ Handle file uploads
    //         $passportPath = $request->file('passport')?->store('passports', 'public');
    //         $idCardPath = $request->file('id_card')?->store('idcards', 'public');
    //         $bankReceiptPath = $request->file('bank_receipt')?->store('bank_receipts', 'public');

    //         // 9️⃣ Create Service Request (regardless of payment)
    //         $serviceRequest = ServiceRequest::create([
    //             'user_id' => $user->id,
    //             'name' => $request->name,
    //             'middle_name' => $request->middle_name,
    //             'last_name' => $request->last_name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'gender' => $request->gender,
    //             'country_of_residence' => $request->country_of_residence,
    //             'state_of_residence' => $request->state_of_residence,
    //             'address' => $request->address,
    //             'landmark' => $request->landmark,
    //             'dob_month' => $request->dob_month ?? 0,
    //             'dob_day' => $request->dob_day ?? 0,
    //             'package' => $package->name ?? null,
    //             'days_per_week' => $request->days_per_week,
    //             'service_days' => $request->service_days ?? [],
    //             'serviceName' => json_encode($serviceIds),
    //             'service_names' => $serviceNamesReadable,
    //             'apartment_type' => $request->apartment_type,
    //             'base_cost' => $baseCost,
    //             'extra_service_cost' => $extraContributionTotal,
    //             'service_charge' => $serviceCharge,
    //             'package_cost' => $packageCost,
    //             'grand_cost' => $grandTotal,
    //             'on_behalf' => $request->on_behalf,
    //             'percentage' => $package->percentage ?? 0,
    //             'discounted_cost' => 0,
    //             'working_hour' => $request->working_hour ?? null,
    //             'payment_status' => $request->has('proceed_to_payment') && $request->proceed_to_payment == '1' ? 'Pending' : 'Pending',
    //             'status' => 'draft',
    //             'passport' => $passportPath,
    //             'id_card' => $idCardPath,
    //             'bank_receipt' => $bankReceiptPath,
    //             'registration_paid' => false,
    //             'registration_status' => 'pending',
    //         ]);

    //         // 🔟 Process Registration Payment if user chose to pay now
    //         $registrationPayment = null;
    //         $successMessage = 'Service request created successfully!';
    //         $needsPasswordNotification = $isNewUser;

    //         if ($request->has('proceed_to_payment') && $request->proceed_to_payment == '1') {
    //             try {
    //                 $transactionRef = $this->generateTransactionReference();

    //                 // Create registration payment record
    //                 $registrationPayment = RegistrationPayment::create([
    //                     'user_id' => $user->id,
    //                     'service_request_id' => $serviceRequest->id,
    //                     'amount' => 10000,
    //                     'payment_method' => $request->payment_method,
    //                     'transaction_reference' => $transactionRef,
    //                     'status' => $request->payment_method === 'bank_transfer' ? 'pending_approval' : 'completed',
    //                     'payment_details' => json_encode($this->preparePaymentDetails($request)),
    //                     'bank_receipt' => $bankReceiptPath,
    //                     'paid_at' => $request->payment_method === 'bank_transfer' ? null : now(),
    //                     'expires_at' => $request->payment_method === 'bank_transfer' ? null : now()->addYear(),
    //                 ]);

    //                 // Update service request based on payment status
    //                 if ($request->payment_method === 'bank_transfer') {
    //                     $serviceRequest->update([
    //                         'payment_status' => 'Pending Approval',
    //                         'registration_status' => 'pending_approval',
    //                         'status' => 'pending_approval'
    //                     ]);

    //                     $successMessage = 'Thank you for your service request! Your registration payment is pending admin approval.';
    //                 } else {
    //                     $serviceRequest->update([
    //                         'payment_status' => 'Paid',
    //                         'registration_paid' => true,
    //                         'registration_paid_at' => now(),
    //                         'registration_expires_at' => now()->addYear(),
    //                         'registration_status' => 'active',
    //                         'status' => 'active'
    //                     ]);

    //                     // Update user registration status
    //                     $user->update([
    //                         'is_registered' => true,
    //                         'registration_date' => now(),
    //                         'registration_expires_at' => now()->addYear(),
    //                         'registration_status' => 'active'
    //                     ]);

    //                     $successMessage = 'Thank you! Your service request and registration are now active.';
    //                 }
    //             } catch (\Exception $e) {
    //                 \Log::error('Payment processing failed: ' . $e->getMessage());

    //                 $serviceRequest->update([
    //                     'payment_status' => 'Failed',
    //                     'registration_status' => 'payment_failed',
    //                     'status' => 'draft'
    //                 ]);

    //                 $successMessage = 'Service request created, but payment failed. You can complete payment later.';
    //             }
    //         } else {
    //             // User didn't pay now, just created service request
    //             $serviceRequest->update([
    //                 'payment_status' => 'Pending',
    //                 'registration_status' => 'pending',
    //                 'status' => 'draft'
    //             ]);

    //             $successMessage = 'Service request created successfully! You can complete your registration payment later.';
    //         }

    //         DB::commit();

    //         // 1️⃣1️⃣ Send notifications to user
    //         if ($isNewUser) {
    //             // Send welcome email with login instructions
    //             $this->sendWelcomeEmail($user, $request->phone);

    //             // Store password notification in session
    //             session()->flash('password_notification', [
    //                 'phone' => $request->phone,
    //                 'email' => $user->email,
    //                 'message' => 'Your account has been created. Use your phone number as your password to login.'
    //             ]);
    //         }

    //         // 1️⃣2️⃣ Redirect based on payment status and user type
    //         if ($registrationPayment && $request->payment_method !== 'bank_transfer') {
    //             // Payment completed, show receipt
    //             return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])
    //                 ->with('success', $successMessage)
    //                 ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //         } else {
    //             // Show service request confirmation with option to pay later
    //             return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])
    //                 ->with('success', $successMessage)
    //                 ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Service request creation failed: ' . $e->getMessage());

    //         return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again.'])
    //             ->withInput();
    //     }
    // }


    // public function multipleSelectedServicesStore(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // 1️⃣ Check if user is authenticated
    //         $isAuthenticated = Auth::check(); // Use Auth facade

    //         // 2️⃣ Validate request data (slightly modified for guest users)
    //         $validationRules = [
    //             'name' => 'required|string|max:255',
    //             'middle_name' => 'nullable|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'required|email',
    //             'phone' => 'required|string|max:15',
    //             'gender' => 'required|in:male,female,no_preference',
    //             'country_of_residence' => 'required|string|max:255',
    //             'state_of_residence' => 'required|string|max:255',
    //             'address' => 'required|string|max:255',
    //             'landmark' => 'nullable|string|max:255',
    //             'package' => 'required|string|max:255',
    //             'on_behalf' => 'required|in:yes,no',
    //             'dob_month' => 'nullable|integer|min:0|max:12',
    //             'dob_day' => 'nullable|integer|min:0|max:31',
    //             'serviceName' => 'required|array|min:1',
    //             'serviceName.*' => 'exists:services,id',
    //             'apartment_type' => 'nullable|exists:apartments,id',
    //             'days_per_week' => 'required|integer|min:1',
    //             'percentage' => 'nullable|numeric|min:0',
    //             'working_hour' => 'nullable|string',
    //             'passport' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //             'id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //         ];

    //         // Only require payment validation if user is paying now
    //         if ($request->has('proceed_to_payment') && $request->proceed_to_payment == '1') {
    //             $validationRules = array_merge($validationRules, [
    //                 'payment_method' => 'required|in:card,bank_transfer,wallet',
    //                 'payment_terms' => 'required|accepted',
    //                 'card_number' => 'required_if:payment_method,card|string|min:13|max:19',
    //                 'card_expiry' => 'required_if:payment_method,card|string|max:5',
    //                 'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',
    //                 'cardholder_name' => 'required_if:payment_method,card|string|max:255',
    //                 'bank_reference' => 'required_if:payment_method,bank_transfer|string|max:255',
    //                 'transfer_date' => 'required_if:payment_method,bank_transfer|date|before_or_equal:today',
    //                 'bank_receipt' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',
    //                 'wallet_pin' => 'required_if:payment_method,wallet|string|min:6|max:6',
    //             ]);
    //         }

    //         $validated = $request->validate($validationRules, [
    //             // Your custom error messages here
    //             'payment_method.required' => 'Please select a payment method.',
    //             'payment_method.in' => 'Please select a valid payment method.',
    //             'payment_terms.required' => 'You must accept the payment terms to proceed.',
    //             'payment_terms.accepted' => 'You must accept the payment terms to proceed.',
    //             // Add more custom messages as needed
    //         ]);

    //         // 3️⃣ Check if user exists by email, create or authenticate
    //         $user = null;
    //         $isNewUser = false;
    //         $passwordSet = false;

    //         if ($isAuthenticated) {
    //             $user = Auth::user();
    //             $passwordSet = true; // Already has a password
    //         } else {
    //             // Check if user exists by email
    //             $user = User::where('email', $request->email)->first();

    //             if (!$user) {
    //                 // Create new user with phone number as password
    //                 $user = User::create([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'email' => $request->email,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'account_type' => 'client',
    //                     'password' => Hash::make($request->phone), // Phone number as password
    //                     'form_completed' => true, // Since they filled the service request
    //                 ]);
    //                 $isNewUser = true;
    //                 $passwordSet = true;

    //                 // Log the user in automatically
    //                 Auth::login($user);
    //             } else {
    //                 // Update existing user information
    //                 $user->update([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'form_completed' => true,
    //                 ]);

    //                 // Check if user has a password set
    //                 $passwordSet = !is_null($user->password);
    //             }
    //         }

    //         // 4️⃣ Load selected services
    //         $services = Service::whereIn('id', $request->input('serviceName'))->get();
    //         if ($services->isEmpty()) {
    //             return back()->withErrors(['serviceName' => 'No valid services selected.']);
    //         }

    //         // 5️⃣ Load package and dynamic charges
    //         $package = Package::find($request->package);
    //         if (!$package) {
    //             return back()->withErrors(['package' => 'Invalid package selected.']);
    //         }

    //         $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
    //         $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

    //         // 6️⃣ Calculate costs (your existing calculation logic)
    //         $apartmentCost = 0;
    //         $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
    //         if ($containsPRS && $request->filled('apartment_type')) {
    //             $apartment = Apartments::find($request->apartment_type);
    //             $apartmentCost = (float) ($apartment?->cost ?? 0);
    //         }

    //         $effectiveCosts = [];
    //         foreach ($services as $s) {
    //             if ($s->service_type === 'PRS') {
    //                 $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
    //             } else {
    //                 $effectiveCosts[$s->id] = (float) $s->base_cost;
    //             }
    //         }

    //         $mainServiceId = array_keys($effectiveCosts, max($effectiveCosts))[0];
    //         $baseCost = $effectiveCosts[$mainServiceId];

    //         $extraContributionTotal = 0.0;
    //         foreach ($services as $s) {
    //             if ($s->id == $mainServiceId) {
    //                 continue;
    //             }

    //             switch ($s->service_type) {
    //                 case 'PRS':
    //                     $prsCost = $apartmentCost ?: (float) $s->base_cost;
    //                     $extraContributionTotal += $prsCost / 2;
    //                     break;
    //                 case 'PMS':
    //                     $extraContributionTotal += ((float) $s->base_cost) / 2;
    //                     break;
    //                 case 'PSS':
    //                     $extraContributionTotal += (float) $extraServiceCost;
    //                     break;
    //                 default:
    //                     $extraContributionTotal += (float) $extraServiceCost;
    //                     break;
    //             }
    //         }

    //         $packageCost = (float) ($package->cost ?? 0);
    //         $totalServiceCost = $baseCost + $packageCost + $extraContributionTotal;
    //         $grandTotal = $totalServiceCost + (float) $serviceCharge;

    //         // 7️⃣ Prepare service details
    //         $serviceNamesReadable = $services->pluck('service_name')->implode(', ');
    //         $serviceIds = $services->pluck('id')->toArray();

    //         // 8️⃣ Handle file uploads
    //         $passportPath = $request->file('passport')?->store('passports', 'public');
    //         $idCardPath = $request->file('id_card')?->store('idcards', 'public');
    //         $bankReceiptPath = $request->file('bank_receipt')?->store('bank_receipts', 'public');

    //         // 9️⃣ Create Service Request (regardless of payment)
    //         $serviceRequest = ServiceRequest::create([
    //             'user_id' => $user->id,
    //             'name' => $request->name,
    //             'middle_name' => $request->middle_name,
    //             'last_name' => $request->last_name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //             'gender' => $request->gender,
    //             'country_of_residence' => $request->country_of_residence,
    //             'state_of_residence' => $request->state_of_residence,
    //             'address' => $request->address,
    //             'landmark' => $request->landmark,
    //             'dob_month' => $request->dob_month ?? 0,
    //             'dob_day' => $request->dob_day ?? 0,
    //             'package' => $package->name ?? null,
    //             'days_per_week' => $request->days_per_week,
    //             'service_days' => $request->service_days ?? [],
    //             'serviceName' => json_encode($serviceIds),
    //             'service_names' => $serviceNamesReadable,
    //             'apartment_type' => $request->apartment_type,
    //             'base_cost' => $baseCost,
    //             'extra_service_cost' => $extraContributionTotal,
    //             'service_charge' => $serviceCharge,
    //             'package_cost' => $packageCost,
    //             'grand_cost' => $grandTotal,
    //             'on_behalf' => $request->on_behalf,
    //             'percentage' => $package->percentage ?? 0,
    //             'discounted_cost' => 0,
    //             'working_hour' => $request->working_hour ?? null,
    //             'payment_status' => $request->has('proceed_to_payment') && $request->proceed_to_payment == '1' ? 'Pending' : 'Pending',
    //             'status' => 'draft',
    //             'passport' => $passportPath,
    //             'id_card' => $idCardPath,
    //             'bank_receipt' => $bankReceiptPath,
    //             'registration_paid' => false,
    //             'registration_status' => 'pending',
    //         ]);

    //         // 🔟 Process Registration Payment if user chose to pay now
    //         $registrationPayment = null;
    //         $successMessage = 'Service request created successfully!';
    //         $needsPasswordNotification = $isNewUser;

    //         if ($request->has('proceed_to_payment') && $request->proceed_to_payment == '1') {
    //             try {
    //                 $transactionRef = $this->generateTransactionReference();

    //                 // Create registration payment record
    //                 $registrationPayment = RegistrationPayment::create([
    //                     'user_id' => $user->id,
    //                     'service_request_id' => $serviceRequest->id,
    //                     'amount' => 10000,
    //                     'payment_method' => $request->payment_method,
    //                     'transaction_reference' => $transactionRef,
    //                     'status' => $request->payment_method === 'bank_transfer' ? 'pending_approval' : 'completed',
    //                     'payment_details' => json_encode($this->preparePaymentDetails($request)),
    //                     'bank_receipt' => $bankReceiptPath,
    //                     'paid_at' => $request->payment_method === 'bank_transfer' ? null : now(),
    //                     'expires_at' => $request->payment_method === 'bank_transfer' ? null : now()->addYear(),
    //                 ]);

    //                 // Update service request based on payment status
    //                 if ($request->payment_method === 'bank_transfer') {
    //                     $serviceRequest->update([
    //                         'payment_status' => 'Pending Approval',
    //                         'registration_status' => 'pending_approval',
    //                         'status' => 'pending_approval'
    //                     ]);

    //                     $successMessage = 'Thank you for your service request! Your registration payment is pending admin approval.';
    //                 } else {
    //                     $serviceRequest->update([
    //                         'payment_status' => 'Paid',
    //                         'registration_paid' => true,
    //                         'registration_paid_at' => now(),
    //                         'registration_expires_at' => now()->addYear(),
    //                         'registration_status' => 'active',
    //                         'status' => 'active'
    //                     ]);

    //                     // Update user registration status
    //                     $user->update([
    //                         'is_registered' => true,
    //                         'registration_date' => now(),
    //                         'registration_expires_at' => now()->addYear(),
    //                         'registration_status' => 'active'
    //                     ]);

    //                     $successMessage = 'Thank you! Your service request and registration are now active.';
    //                 }
    //             } catch (\Exception $e) {
    //                 Log::error('Payment processing failed: ' . $e->getMessage());

    //                 $serviceRequest->update([
    //                     'payment_status' => 'Failed',
    //                     'registration_status' => 'payment_failed',
    //                     'status' => 'draft'
    //                 ]);

    //                 $successMessage = 'Service request created, but payment failed. You can complete payment later.';
    //             }
    //         } else {
    //             // User didn't pay now, just created service request
    //             $serviceRequest->update([
    //                 'payment_status' => 'Pending',
    //                 'registration_status' => 'pending',
    //                 'status' => 'draft'
    //             ]);

    //             $successMessage = 'Service request created successfully! You can complete your registration payment later.';
    //         }

    //         DB::commit();
    //         Log::info('Transaction committed successfully');

    //         // Store the service request ID in session for guest access
    //         if (!$isAuthenticated) {
    //             session(['recently_created_service_request' => $serviceRequest->id]);

    //             // Also add to allowed guest requests list
    //             $allowedRequests = session('guest_service_requests', []);
    //             $allowedRequests[] = $serviceRequest->id;
    //             session(['guest_service_requests' => array_unique($allowedRequests)]);
    //         }

    //         // 1️⃣1️⃣ Send notifications to user
    //         if ($isNewUser) {
    //             // Send welcome email with login instructions
    //             $this->sendWelcomeEmail($user, $request->phone);

    //             // Store password notification in session
    //             session()->flash('password_notification', [
    //                 'phone' => $request->phone,
    //                 'email' => $user->email,
    //                 'message' => 'Your account has been created. Use your phone number as your password to login.'
    //             ]);
    //         }

    //         // 1️⃣2️⃣ Redirect based on payment status and user type
    //         if ($registrationPayment && $request->payment_method !== 'bank_transfer') {
    //             // Payment completed, show receipt
    //             return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])
    //                 ->with('success', $successMessage)
    //                 ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //         } else {
    //             // Show service request confirmation with option to pay later
    //             return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])
    //                 ->with('success', $successMessage)
    //                 ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //         }
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Service request creation failed: ' . $e->getMessage());

    //         return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again.'])
    //             ->withInput();
    //     }
    // }

    // public function multipleSelectedServicesStore(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Check if this is a "save and continue" request (Step 1 only)
    //         $isSaveAndContinue = $request->has('save_and_continue') && $request->save_and_continue == '1';

    //         // Check if this is a partial registration (no services selected)
    //         $isPartialRegistration = $request->has('partial_registration') && $request->partial_registration == '1';

    //         Log::info('Starting service request creation', [
    //             'isSaveAndContinue' => $isSaveAndContinue,
    //             'isPartialRegistration' => $isPartialRegistration,
    //             'hasServiceName' => $request->has('serviceName'),
    //             'serviceName' => $request->serviceName,
    //             'authenticated' => Auth::check(),
    //             'user_email' => $request->email ?? 'no email',
    //             'payment_choice' => $request->payment_choice ?? 'not set'
    //         ]);

    //         // 1️⃣ Determine the type of request based on user journey
    //         $requestType = 'full_request';
    //         if ($isSaveAndContinue) {
    //             $requestType = 'save_and_continue';
    //         } elseif ($isPartialRegistration) {
    //             $requestType = 'partial_registration';
    //         } elseif (!$request->filled('serviceName') || !is_array($request->serviceName) || count($request->serviceName) === 0) {
    //             $requestType = 'no_services';
    //         }

    //         Log::info('Request type determined', ['request_type' => $requestType]);

    //         // 2️⃣ Define base validation rules (always required)
    //         $baseValidationRules = [
    //             'name' => 'required|string|max:255',
    //             'middle_name' => 'nullable|string|max:255',
    //             'last_name' => 'required|string|max:255',
    //             'email' => 'required|email',
    //             'phone' => 'required|string|max:15',
    //             'gender' => 'required|in:male,female,no_preference',
    //             'country_of_residence' => 'required|string|max:255',
    //             'state_of_residence' => 'required|string|max:255',
    //             'address' => 'required|string|max:255',
    //             'landmark' => 'nullable|string|max:255',
    //             'on_behalf' => 'required|in:yes,no',
    //             'dob_month' => 'nullable|integer|min:0|max:12',
    //             'dob_day' => 'nullable|integer|min:0|max:31',
    //             'passport' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //             'id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //         ];

    //         // 3️⃣ Define conditional validation rules based on request type
    //         $conditionalRules = [];

    //         switch ($requestType) {
    //             case 'save_and_continue':
    //                 // Only validate personal info for save_and_continue
    //                 $validationRules = $baseValidationRules;
    //                 break;

    //             case 'partial_registration':
    //             case 'no_services':
    //                 // Validate personal info only
    //                 $validationRules = $baseValidationRules;
    //                 // Add these as hidden fields in your form
    //                 $validationRules['partial_registration'] = 'required|in:1';
    //                 break;

    //             case 'full_request':
    //                 // Full validation including services
    //                 $validationRules = array_merge($baseValidationRules, [
    //                     'serviceName' => 'required|array|min:1',
    //                     'serviceName.*' => 'exists:services,id',
    //                     'package' => 'required|exists:packages,id',
    //                     'days_per_week' => 'required|integer|min:1',
    //                     'percentage' => 'required|numeric|min:0',
    //                     'service_days' => 'required|array|min:1',
    //                     'working_hour' => 'nullable|string',
    //                     'apartment_type' => 'nullable|exists:apartments,id',
    //                 ]);

    //                 // Check if PRS services are selected
    //                 if ($request->filled('serviceName') && is_array($request->serviceName)) {
    //                     $prsServices = Service::whereIn('id', $request->serviceName)
    //                         ->where('service_type', 'PRS')
    //                         ->exists();

    //                     if ($prsServices) {
    //                         $validationRules['apartment_type'] = 'required|exists:apartments,id';
    //                     }
    //                 }

    //                 // Payment validation
    //                 if ($request->filled('payment_choice')) {
    //                     $validationRules['payment_choice'] = 'required|in:now,later';

    //                     if ($request->payment_choice === 'now') {
    //                         $validationRules = array_merge($validationRules, [
    //                             'payment_method' => 'required|in:card,bank_transfer,wallet',
    //                             'payment_terms' => 'required|accepted',
    //                         ]);

    //                         // Payment method specific validation
    //                         switch ($request->payment_method) {
    //                             case 'bank_transfer':
    //                                 $validationRules = array_merge($validationRules, [
    //                                     'bank_reference' => 'required|string|max:255',
    //                                     'transfer_date' => 'required|date|before_or_equal:today',
    //                                     'bank_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    //                                 ]);
    //                                 break;
    //                             case 'card':
    //                                 $validationRules = array_merge($validationRules, [
    //                                     'card_number' => 'required|string|min:13|max:19',
    //                                     'card_expiry' => 'required|string|max:5',
    //                                     'card_cvv' => 'required|string|min:3|max:4',
    //                                     'cardholder_name' => 'required|string|max:255',
    //                                 ]);
    //                                 break;
    //                             case 'wallet':
    //                                 $validationRules = array_merge($validationRules, [
    //                                     'wallet_pin' => 'required|string|min:6|max:6',
    //                                 ]);
    //                                 break;
    //                         }
    //                     }
    //                 }
    //                 break;
    //         }

    //         // 4️⃣ Run validation
    //         try {
    //             $validated = $request->validate($validationRules, [
    //                 'serviceName.required' => 'Please select at least one service.',
    //                 'serviceName.*.exists' => 'One or more selected services are invalid.',
    //                 'package.required' => 'Please select a service package.',
    //                 'apartment_type.required' => 'Apartment type is required for PRS services.',
    //                 'payment_method.required' => 'Please select a payment method.',
    //                 'payment_method.in' => 'Please select a valid payment method.',
    //                 'payment_terms.required' => 'You must accept the payment terms to proceed.',
    //                 'payment_terms.accepted' => 'You must accept the payment terms to proceed.',
    //                 'bank_reference.required_if' => 'Bank transfer reference is required for bank transfers.',
    //                 'transfer_date.required_if' => 'Transfer date is required for bank transfers.',
    //                 'bank_receipt.required_if' => 'Bank receipt is required for bank transfers.',
    //                 'card_number.required_if' => 'Card number is required for card payments.',
    //                 'card_cvv.required_if' => 'CVV is required for card payments.',
    //                 'cardholder_name.required_if' => 'Cardholder name is required for card payments.',
    //                 'wallet_pin.required_if' => 'Wallet PIN is required for wallet payments.',
    //             ]);

    //             Log::info('Validation passed', [
    //                 'request_type' => $requestType,
    //                 'validated_fields' => array_keys($validated)
    //             ]);
    //         } catch (\Illuminate\Validation\ValidationException $e) {
    //             Log::error('Validation failed', [
    //                 'request_type' => $requestType,
    //                 'errors' => $e->errors(),
    //                 'input' => $request->except(['_token', 'password', 'card_number', 'card_cvv', 'wallet_pin'])
    //             ]);
    //             throw $e;
    //         }

    //         // 5️⃣ Handle user creation/authentication
    //         $user = null;
    //         $isNewUser = false;

    //         if (Auth::check()) {
    //             $user = Auth::user();
    //             Log::info('User already authenticated', ['user_id' => $user->id]);
    //         } else {
    //             $user = User::where('email', $request->email)->first();

    //             if (!$user) {
    //                 // Create new user with phone number as password
    //                 $user = User::create([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'email' => $request->email,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'account_type' => 'client',
    //                     'password' => Hash::make($request->phone),
    //                     'form_completed' => false,
    //                     'registration_status' => 'incomplete',
    //                     'registration_source' => 'service_request_form',
    //                 ]);
    //                 $isNewUser = true;
    //                 Auth::login($user);
    //                 Log::info('New user created and logged in', ['user_id' => $user->id]);
    //             } else {
    //                 // Update existing user
    //                 $user->update([
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'form_completed' => false,
    //                     'registration_status' => 'incomplete',
    //                 ]);
    //                 Auth::login($user);
    //                 Log::info('Existing user updated and logged in', ['user_id' => $user->id]);
    //             }
    //         }

    //         // 6️⃣ Handle file uploads
    //         $passportPath = $request->file('passport')?->store('passports', 'public');
    //         $idCardPath = $request->file('id_card')?->store('idcards', 'public');
    //         $bankReceiptPath = $request->file('bank_receipt')?->store('bank_receipts', 'public');

    //         if ($passportPath) $user->update(['passport' => $passportPath]);
    //         if ($idCardPath) $user->update(['id_card' => $idCardPath]);

    //         // 7️⃣ Handle different request types
    //         switch ($requestType) {
    //             case 'save_and_continue':
    //                 Log::info('Processing save_and_continue request');

    //                 $user->update(['form_completed' => false]);

    //                 Lead::create([
    //                     'user_id' => $user->id,
    //                     'source' => 'service_request_form',
    //                     'status' => 'registered_incomplete',
    //                     'notes' => 'User completed personal info but did not select services (save and continue)',
    //                     'follow_up_date' => now()->addDays(2),
    //                 ]);

    //                 DB::commit();

    //                 if ($isNewUser) {
    //                     $this->sendWelcomeEmail($user, $request->phone);
    //                     session()->flash('password_notification', [
    //                         'phone' => $request->phone,
    //                         'email' => $user->email,
    //                         'message' => 'Your account has been created. Use your phone number as your password to login.'
    //                     ]);
    //                 }

    //                 return redirect()->route('service_requests.create')
    //                     ->with('success', 'Your information has been saved! Please continue with service selection.')
    //                     ->with('user_data_saved', true)
    //                     ->with('user_id', $user->id)
    //                     ->with('is_new_user', $isNewUser);

    //             case 'partial_registration':
    //             case 'no_services':
    //                 Log::info('Processing partial registration (no services selected)');

    //                 $user->update(['form_completed' => true]);

    //                 Lead::create([
    //                     'user_id' => $user->id,
    //                     'source' => 'service_request_form',
    //                     'status' => 'registered_incomplete',
    //                     'notes' => 'User completed registration without selecting services',
    //                     'follow_up_date' => now()->addDays(2),
    //                 ]);

    //                 DB::commit();

    //                 if ($isNewUser) {
    //                     $this->sendWelcomeEmail($user, $request->phone);
    //                     session()->flash('password_notification', [
    //                         'phone' => $request->phone,
    //                         'email' => $user->email,
    //                         'message' => 'Your account has been created. Use your phone number as your password to login.'
    //                     ]);
    //                 }

    //                 return redirect()->route('service-request.registration-complete')
    //                     ->with('success', 'Registration completed successfully! You can now browse our services.')
    //                     ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);

    //             case 'full_request':
    //                 Log::info('Processing full service request', [
    //                     'service_count' => count($request->serviceName),
    //                     'services' => $request->serviceName
    //                 ]);

    //                 // 8️⃣ Load selected services
    //                 $services = Service::whereIn('id', $request->serviceName)->get();
    //                 if ($services->isEmpty()) {
    //                     throw new \Exception('No valid services found');
    //                 }

    //                 // 9️⃣ Load package
    //                 $package = Package::find($request->package);
    //                 if (!$package) {
    //                     throw new \Exception('Invalid package selected');
    //                 }

    //                 // 🔟 Load charges
    //                 $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
    //                 $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

    //                 // 1️⃣1️⃣ Calculate costs
    //                 $apartmentCost = 0;
    //                 $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
    //                 if ($containsPRS && $request->filled('apartment_type')) {
    //                     $apartment = Apartments::find($request->apartment_type);
    //                     $apartmentCost = (float) ($apartment?->cost ?? 0);
    //                 }

    //                 $effectiveCosts = [];
    //                 foreach ($services as $s) {
    //                     if ($s->service_type === 'PRS') {
    //                         $effectiveCosts[$s->id] = $apartmentCost ?: (float) $s->base_cost;
    //                     } else {
    //                         $effectiveCosts[$s->id] = (float) $s->base_cost;
    //                     }
    //                 }

    //                 $mainServiceId = array_keys($effectiveCosts, max($effectiveCosts))[0];
    //                 $baseCost = $effectiveCosts[$mainServiceId];

    //                 $extraContributionTotal = 0.0;
    //                 foreach ($services as $s) {
    //                     if ($s->id == $mainServiceId) continue;

    //                     switch ($s->service_type) {
    //                         case 'PRS':
    //                             $prsCost = $apartmentCost ?: (float) $s->base_cost;
    //                             $extraContributionTotal += $prsCost / 2;
    //                             break;
    //                         case 'PMS':
    //                             $extraContributionTotal += ((float) $s->base_cost) / 2;
    //                             break;
    //                         case 'PSS':
    //                             $extraContributionTotal += (float) $extraServiceCost;
    //                             break;
    //                         default:
    //                             $extraContributionTotal += (float) $extraServiceCost;
    //                             break;
    //                     }
    //                 }

    //                 $packageCost = (float) ($package->cost ?? 0);
    //                 $totalServiceCost = $baseCost + $packageCost + $extraContributionTotal;
    //                 $grandTotal = $totalServiceCost + (float) $serviceCharge;

    //                 Log::info('Cost calculation complete', [
    //                     'base_cost' => $baseCost,
    //                     'package_cost' => $packageCost,
    //                     'extra_contribution' => $extraContributionTotal,
    //                     'service_charge' => $serviceCharge,
    //                     'grand_total' => $grandTotal
    //                 ]);

    //                 // 1️⃣2️⃣ Prepare service details
    //                 $serviceNamesReadable = $services->pluck('service_name')->implode(', ');
    //                 $serviceIds = $services->pluck('id')->toArray();

    //                 // 1️⃣3️⃣ Create Service Request
    //                 $serviceRequest = ServiceRequest::create([
    //                     'user_id' => $user->id,
    //                     'name' => $request->name,
    //                     'middle_name' => $request->middle_name,
    //                     'last_name' => $request->last_name,
    //                     'email' => $request->email,
    //                     'phone' => $request->phone,
    //                     'gender' => $request->gender,
    //                     'country_of_residence' => $request->country_of_residence,
    //                     'state_of_residence' => $request->state_of_residence,
    //                     'address' => $request->address,
    //                     'landmark' => $request->landmark,
    //                     'dob_month' => $request->dob_month ?? 0,
    //                     'dob_day' => $request->dob_day ?? 0,
    //                     'package' => $package->name ?? null,
    //                     'days_per_week' => $request->days_per_week,
    //                     'service_days' => $request->service_days ?? [],
    //                     'serviceName' => json_encode($serviceIds),
    //                     'service_names' => $serviceNamesReadable,
    //                     'apartment_type' => $request->apartment_type,
    //                     'base_cost' => $baseCost,
    //                     'extra_service_cost' => $extraContributionTotal,
    //                     'service_charge' => $serviceCharge,
    //                     'package_cost' => $packageCost,
    //                     'grand_cost' => $grandTotal,
    //                     'on_behalf' => $request->on_behalf,
    //                     'percentage' => $package->percentage ?? 0,
    //                     'discounted_cost' => 0,
    //                     'working_hour' => $request->working_hour ?? null,
    //                     'payment_status' => 'pending',
    //                     'status' => 'draft',
    //                     'passport' => $passportPath,
    //                     'id_card' => $idCardPath,
    //                     'bank_receipt' => $bankReceiptPath,
    //                     'registration_paid' => false,
    //                     'registration_status' => 'pending',
    //                     'payment_choice' => $request->payment_choice ?? null,
    //                 ]);

    //                 Log::info('Service request created', ['service_request_id' => $serviceRequest->id]);

    //                 // 1️⃣4️⃣ Update user
    //                 $user->update(['form_completed' => true]);

    //                 // 1️⃣5️⃣ Handle payment based on payment choice
    //                 $registrationPayment = null;
    //                 $successMessage = 'Service request created successfully!';

    //                 if ($request->filled('payment_choice') && $request->payment_choice === 'now') {
    //                     try {
    //                         $transactionRef = $this->generateTransactionReference();

    //                         // Create registration payment record
    //                         $registrationPayment = RegistrationPayment::create([
    //                             'user_id' => $user->id,
    //                             'service_request_id' => $serviceRequest->id,
    //                             'amount' => 10000,
    //                             'payment_method' => $request->payment_method,
    //                             'transaction_reference' => $transactionRef,
    //                             'status' => $request->payment_method === 'bank_transfer' ? 'pending_approval' : 'completed',
    //                             'payment_details' => json_encode($this->preparePaymentDetails($request)),
    //                             'bank_receipt' => $bankReceiptPath,
    //                             'paid_at' => $request->payment_method === 'bank_transfer' ? null : now(),
    //                             'expires_at' => $request->payment_method === 'bank_transfer' ? null : now()->addYear(),
    //                         ]);

    //                         Log::info('Registration payment created', [
    //                             'payment_id' => $registrationPayment->id,
    //                             'method' => $request->payment_method,
    //                             'status' => $registrationPayment->status
    //                         ]);

    //                         // Update service request and user based on payment status
    //                         if ($request->payment_method === 'bank_transfer') {
    //                             $serviceRequest->update([
    //                                 'payment_status' => 'pending_approval',
    //                                 'registration_status' => 'pending_approval',
    //                                 'status' => 'pending_approval'
    //                             ]);

    //                             $successMessage = 'Thank you for your service request! Your registration payment is pending admin approval.';
    //                         } else {
    //                             $serviceRequest->update([
    //                                 'payment_status' => 'paid',
    //                                 'registration_paid' => true,
    //                                 'registration_paid_at' => now(),
    //                                 'registration_expires_at' => now()->addYear(),
    //                                 'registration_status' => 'active',
    //                                 'status' => 'active'
    //                             ]);

    //                             $user->update([
    //                                 'is_registered' => true,
    //                                 'registration_date' => now(),
    //                                 'registration_expires_at' => now()->addYear(),
    //                                 'registration_status' => 'active'
    //                             ]);

    //                             $successMessage = 'Thank you! Your service request and registration are now active.';
    //                         }
    //                     } catch (\Exception $e) {
    //                         Log::error('Payment processing failed: ' . $e->getMessage());

    //                         $serviceRequest->update([
    //                             'payment_status' => 'failed',
    //                             'registration_status' => 'payment_failed',
    //                             'status' => 'draft'
    //                         ]);

    //                         $successMessage = 'Service request created, but payment failed. You can complete payment later.';
    //                     }
    //                 } else {
    //                     // Pay later or no payment choice
    //                     $serviceRequest->update([
    //                         'payment_status' => 'pending',
    //                         'registration_status' => 'pending',
    //                         'status' => 'draft'
    //                     ]);

    //                     $successMessage = 'Service request created successfully! You can complete your registration payment later.';
    //                 }

    //                 DB::commit();

    //                 // 1️⃣6️⃣ Store session data for guest access
    //                 if (!Auth::check()) {
    //                     session(['recently_created_service_request' => $serviceRequest->id]);
    //                     $allowedRequests = session('guest_service_requests', []);
    //                     $allowedRequests[] = $serviceRequest->id;
    //                     session(['guest_service_requests' => array_unique($allowedRequests)]);
    //                 }

    //                 // 1️⃣7️⃣ Send notifications
    //                 if ($isNewUser) {
    //                     $this->sendWelcomeEmail($user, $request->phone);
    //                     session()->flash('password_notification', [
    //                         'phone' => $request->phone,
    //                         'email' => $user->email,
    //                         'message' => 'Your account has been created. Use your phone number as your password to login.'
    //                     ]);
    //                 }

    //                 // 1️⃣8️⃣ Redirect based on payment status
    //                 if ($registrationPayment && $request->payment_method !== 'bank_transfer') {
    //                     return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])
    //                         ->with('success', $successMessage)
    //                         ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //                 } else {
    //                     return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])
    //                         ->with('success', $successMessage)
    //                         ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
    //                 }
    //         }
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         Log::error('Validation failed in service request creation', [
    //             'errors' => $e->errors(),
    //             'request_data' => $request->except(['_token', 'password', 'card_number', 'card_cvv', 'wallet_pin'])
    //         ]);

    //         return back()
    //             ->withErrors($e->validator)
    //             ->withInput();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Service request creation failed: ' . $e->getMessage());
    //         Log::error('Stack trace: ' . $e->getTraceAsString());

    //         return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again.'])
    //             ->withInput();
    //     }
    // }

    public function multipleSelectedServicesStore(Request $request)
    {
        DB::beginTransaction();

        try {
            // Check if this is a "save and continue" request (Step 1 only)
            $isSaveAndContinue = $request->has('save_and_continue') && $request->save_and_continue == '1';

            // Check if this is a partial registration (no services selected)
            $isPartialRegistration = $request->has('partial_registration') && $request->partial_registration == '1';

            Log::info('=== STARTING SERVICE REQUEST CREATION ===', [
                'isSaveAndContinue' => $isSaveAndContinue,
                'isPartialRegistration' => $isPartialRegistration,
                'hasServiceName' => $request->has('serviceName'),
                'serviceName' => $request->serviceName,
                'authenticated' => Auth::check(),
                'user_email' => $request->email ?? 'no email',
                'payment_choice' => $request->payment_choice ?? 'not set',
                'session_id' => session()->getId()
            ]);

            // 1️⃣ Determine the type of request based on user journey
            $requestType = 'full_request';
            if ($isSaveAndContinue) {
                $requestType = 'save_and_continue';
            } elseif ($isPartialRegistration) {
                $requestType = 'partial_registration';
            } elseif (!$request->filled('serviceName') || !is_array($request->serviceName) || count($request->serviceName) === 0) {
                $requestType = 'no_services';
            }

            Log::info('Request type determined', ['request_type' => $requestType]);

            // 2️⃣ Define base validation rules (always required)
            $baseValidationRules = [
                'name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'gender' => 'required|in:male,female,no_preference',
                'country_of_residence' => 'required|string|max:255',
                'state_of_residence' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'on_behalf' => 'required|in:yes,no',
                'dob_month' => 'nullable|integer|min:0|max:12',
                'dob_day' => 'nullable|integer|min:0|max:31',
                'passport' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ];

            // 3️⃣ Define conditional validation rules based on request type
            $validationRules = $baseValidationRules;

            if ($isSaveAndContinue) {
                Log::info('Setting validation for save_and_continue - only personal info');
                // Only validate personal info for save_and_continue
                $validationRules = $baseValidationRules;
            } elseif ($isPartialRegistration || $requestType === 'no_services') {
                Log::info('Setting validation for partial registration/no services');
                // Validate personal info only
                $validationRules = $baseValidationRules;
                // Add these as hidden fields in your form
                $validationRules['partial_registration'] = 'required|in:1';
            } elseif ($requestType === 'full_request') {
                Log::info('Setting validation for full request');
                // Full validation including services
                $validationRules = array_merge($baseValidationRules, [
                    'serviceName' => 'required|array|min:1',
                    'serviceName.*' => 'exists:services,id',
                    'package' => 'required|exists:packages,id',
                    'days_per_week' => 'required|integer|min:1',
                    'percentage' => 'required|numeric|min:0',
                    'service_days' => 'required|array|min:1',
                    'working_hour' => 'nullable|string',
                    'apartment_type' => 'nullable|exists:apartments,id',
                ]);

                // Check if PRS services are selected
                if ($request->filled('serviceName') && is_array($request->serviceName)) {
                    $prsServices = Service::whereIn('id', $request->serviceName)
                        ->where('service_type', 'PRS')
                        ->exists();

                    if ($prsServices) {
                        $validationRules['apartment_type'] = 'required|exists:apartments,id';
                    }
                }

                // Payment validation - only if payment_choice is present
                if ($request->filled('payment_choice')) {
                    $validationRules['payment_choice'] = 'required|in:now,later';

                    if ($request->payment_choice === 'now') {
                        $validationRules = array_merge($validationRules, [
                            'payment_method' => 'required|in:card,bank_transfer,wallet',
                            'payment_terms' => 'required|accepted',
                        ]);

                        // Payment method specific validation
                        switch ($request->payment_method) {
                            case 'bank_transfer':
                                $validationRules = array_merge($validationRules, [
                                    'bank_reference' => 'required|string|max:255',
                                    'transfer_date' => 'required|date|before_or_equal:today',
                                    'bank_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                                ]);
                                break;
                            case 'card':
                                $validationRules = array_merge($validationRules, [
                                    'card_number' => 'required|string|min:13|max:19',
                                    'card_expiry' => 'required|string|max:5',
                                    'card_cvv' => 'required|string|min:3|max:4',
                                    'cardholder_name' => 'required|string|max:255',
                                ]);
                                break;
                            case 'wallet':
                                $validationRules = array_merge($validationRules, [
                                    'wallet_pin' => 'required|string|min:6|max:6',
                                ]);
                                break;
                        }
                    }
                }
            }

            Log::info('Validation rules set', ['rules_count' => count($validationRules)]);

            // 4️⃣ Run validation
            try {
                $validated = $request->validate($validationRules, [
                    'serviceName.required' => 'Please select at least one service.',
                    'serviceName.*.exists' => 'One or more selected services are invalid.',
                    'package.required' => 'Please select a service package.',
                    'apartment_type.required' => 'Apartment type is required for PRS services.',
                    'payment_method.required' => 'Please select a payment method.',
                    'payment_method.in' => 'Please select a valid payment method.',
                    'payment_terms.required' => 'You must accept the payment terms to proceed.',
                    'payment_terms.accepted' => 'You must accept the payment terms to proceed.',
                    'bank_reference.required_if' => 'Bank transfer reference is required for bank transfers.',
                    'transfer_date.required_if' => 'Transfer date is required for bank transfers.',
                    'bank_receipt.required_if' => 'Bank receipt is required for bank transfers.',
                    'card_number.required_if' => 'Card number is required for card payments.',
                    'card_cvv.required_if' => 'CVV is required for card payments.',
                    'cardholder_name.required_if' => 'Cardholder name is required for card payments.',
                    'wallet_pin.required_if' => 'Wallet PIN is required for wallet payments.',
                ]);

                Log::info('Validation passed', [
                    'request_type' => $requestType,
                    'validated_fields' => array_keys($validated)
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed', [
                    'request_type' => $requestType,
                    'errors' => $e->errors(),
                    'input_keys' => array_keys($request->except(['_token', 'password', 'card_number', 'card_cvv', 'wallet_pin']))
                ]);
                throw $e;
            }

            // 5️⃣ Handle user creation/authentication
            $user = null;
            $isNewUser = false;

            Log::info('Checking user authentication', ['authenticated' => Auth::check()]);

            if (Auth::check()) {
                $user = Auth::user();
                Log::info('User already authenticated', ['user_id' => $user->id, 'email' => $user->email]);
            } else {
                Log::info('No authenticated user, checking by email', ['email' => $request->email]);
                $user = User::where('email', $request->email)->first();

                if (!$user) {
                    Log::info('Creating new user', ['email' => $request->email]);

                    // Create new user with phone number as password
                    $user = User::create([
                        'name' => $request->name,
                        'middle_name' => $request->middle_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'gender' => $request->gender,
                        'country_of_residence' => $request->country_of_residence,
                        'state_of_residence' => $request->state_of_residence,
                        'address' => $request->address,
                        'landmark' => $request->landmark,
                        'account_type' => 'client',
                        'password' => Hash::make($request->phone),
                        'form_completed' => false,
                        'registration_status' => 'incomplete',
                        'registration_source' => 'service_request_form',
                    ]);
                    $isNewUser = true;

                    Log::info('New user created', ['user_id' => $user->id, 'email' => $user->email]);

                    // IMPORTANT: Log the user in
                    Auth::login($user);
                    Log::info('User logged in after creation', ['user_id' => $user->id, 'session' => session()->getId()]);
                } else {
                    Log::info('Updating existing user', ['user_id' => $user->id, 'email' => $user->email]);

                    // Update existing user
                    $user->update([
                        'name' => $request->name,
                        'middle_name' => $request->middle_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'gender' => $request->gender,
                        'country_of_residence' => $request->country_of_residence,
                        'state_of_residence' => $request->state_of_residence,
                        'address' => $request->address,
                        'landmark' => $request->landmark,
                        'form_completed' => false,
                        'registration_status' => 'incomplete',
                    ]);

                    // IMPORTANT: Log the user in
                    Auth::login($user);
                    Log::info('Existing user updated and logged in', ['user_id' => $user->id]);
                }
            }

            // 6️⃣ Handle file uploads
            $passportPath = null;
            $idCardPath = null;
            $bankReceiptPath = null;

            if ($request->hasFile('passport')) {
                $passportPath = $request->file('passport')->store('passports', 'public');
                Log::info('Passport uploaded', ['path' => $passportPath]);
            }

            if ($request->hasFile('id_card')) {
                $idCardPath = $request->file('id_card')->store('idcards', 'public');
                Log::info('ID Card uploaded', ['path' => $idCardPath]);
            }

            if ($request->hasFile('bank_receipt')) {
                $bankReceiptPath = $request->file('bank_receipt')->store('bank_receipts', 'public');
                Log::info('Bank receipt uploaded', ['path' => $bankReceiptPath]);
            }

            if ($passportPath) $user->update(['passport' => $passportPath]);
            if ($idCardPath) $user->update(['id_card' => $idCardPath]);

            Log::info('File uploads completed');

            // 7️⃣ Handle different request types
            Log::info('Processing request type', ['request_type' => $requestType]);

            if ($requestType === 'save_and_continue') {
                Log::info('Processing save_and_continue request');

                $user->update(['form_completed' => false]);

                // Try to create lead or log if model doesn't exist
                try {
                    if (class_exists('App\\Models\\Lead')) {
                        \App\Models\Lead::create([
                            'user_id' => $user->id,
                            'source' => 'service_request_form',
                            'status' => 'registered_incomplete',
                            'notes' => 'User completed personal info but did not select services (save and continue)',
                            'follow_up_date' => now()->addDays(2),
                        ]);
                        Log::info('Lead created for save_and_continue');
                    } else {
                        Log::info('Lead model not found, skipping lead creation');
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not create lead: ' . $e->getMessage());
                }

                DB::commit();
                Log::info('Transaction committed for save_and_continue');

                if ($isNewUser) {
                    try {
                        $this->sendWelcomeEmail($user, $request->phone);
                        Log::info('Welcome email sent');
                    } catch (\Exception $e) {
                        Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }

                    session()->flash('password_notification', [
                        'phone' => $request->phone,
                        'email' => $user->email,
                        'message' => 'Your account has been created. Use your phone number as your password to login.'
                    ]);
                }

                // Store session data before redirect
                session()->put('user_data_saved', true);
                session()->put('user_id', $user->id);
                session()->put('is_new_user', $isNewUser);

                Log::info('Session data stored', [
                    'user_data_saved' => session('user_data_saved'),
                    'user_id' => session('user_id'),
                    'session_id' => session()->getId()
                ]);

                return redirect()->route('multipleSelectedServices')
                    ->with('success', 'Your information has been saved! Please continue with service selection.')
                    ->with('user_data_saved', true)
                    ->with('user_id', $user->id)
                    ->with('is_new_user', $isNewUser);
            } elseif ($requestType === 'partial_registration' || $requestType === 'no_services') {
                Log::info('Processing partial registration (no services selected)');

                $user->update(['form_completed' => true]);

                // Try to create lead or log if model doesn't exist
                try {
                    if (class_exists('App\\Models\\Lead')) {
                        \App\Models\Lead::create([
                            'user_id' => $user->id,
                            'source' => 'service_request_form',
                            'status' => 'registered_incomplete',
                            'notes' => 'User completed registration without selecting services',
                            'follow_up_date' => now()->addDays(2),
                        ]);
                        Log::info('Lead created for partial registration');
                    } else {
                        Log::info('Lead model not found, skipping lead creation');
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not create lead: ' . $e->getMessage());
                }

                DB::commit();
                Log::info('Transaction committed for partial registration');

                if ($isNewUser) {
                    try {
                        $this->sendWelcomeEmail($user, $request->phone);
                        Log::info('Welcome email sent');
                    } catch (\Exception $e) {
                        Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }

                    session()->flash('password_notification', [
                        'phone' => $request->phone,
                        'email' => $user->email,
                        'message' => 'Your account has been created. Use your phone number as your password to login.'
                    ]);
                }

                return redirect()->route('service-request.registration-complete')
                    ->with('success', 'Registration completed successfully! You can now browse our services.')
                    ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
            } elseif ($requestType === 'full_request') {
                Log::info('Processing full service request', [
                    'service_count' => count($request->serviceName),
                    'services' => $request->serviceName
                ]);

                // 8️⃣ Load selected services
                $services = Service::whereIn('id', $request->serviceName)->get();
                if ($services->isEmpty()) {
                    throw new \Exception('No valid services found');
                }

                // 9️⃣ Load package
                $package = Package::find($request->package);
                if (!$package) {
                    throw new \Exception('Invalid package selected');
                }

                // 🔟 Load charges
                $serviceCharge = ServiceCharge::first()?->amount ?? 10000;
                $extraServiceCost = ExtraServiceCost::first()?->cost ?? 12000;

                // 1️⃣1️⃣ Calculate costs
                $apartmentCost = 0;
                $containsPRS = $services->contains(fn($s) => $s->service_type === 'PRS');
                if ($containsPRS && $request->filled('apartment_type')) {
                    $apartment = Apartments::find($request->apartment_type);
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

                Log::info('Cost calculation complete', [
                    'base_cost' => $baseCost,
                    'package_cost' => $packageCost,
                    'extra_contribution' => $extraContributionTotal,
                    'service_charge' => $serviceCharge,
                    'grand_total' => $grandTotal
                ]);

                // 1️⃣2️⃣ Prepare service details
                $serviceNamesReadable = $services->pluck('service_name')->implode(', ');
                $serviceIds = $services->pluck('id')->toArray();

                // 1️⃣3️⃣ Create Service Request
                $serviceRequest = ServiceRequest::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'country_of_residence' => $request->country_of_residence,
                    'state_of_residence' => $request->state_of_residence,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                    'dob_month' => $request->dob_month ?? 0,
                    'dob_day' => $request->dob_day ?? 0,
                    'package' => $package->name ?? null,
                    'days_per_week' => $request->days_per_week,
                    'service_days' => $request->service_days ?? [],
                    'serviceName' => json_encode($serviceIds),
                    'service_names' => $serviceNamesReadable,
                    'apartment_type' => $request->apartment_type,
                    'base_cost' => $baseCost,
                    'extra_service_cost' => $extraContributionTotal,
                    'service_charge' => $serviceCharge,
                    'package_cost' => $packageCost,
                    'grand_cost' => $grandTotal,
                    'on_behalf' => $request->on_behalf,
                    'percentage' => $package->percentage ?? 0,
                    'discounted_cost' => 0,
                    'working_hour' => $request->working_hour ?? null,
                    'payment_status' => 'pending',
                    'status' => 'draft',
                    'passport' => $passportPath,
                    'id_card' => $idCardPath,
                    'bank_receipt' => $bankReceiptPath,
                    'registration_paid' => false,
                    'registration_status' => 'pending',
                    'payment_choice' => $request->payment_choice ?? null,
                ]);

                Log::info('Service request created', ['service_request_id' => $serviceRequest->id]);

                // 1️⃣4️⃣ Update user
                $user->update(['form_completed' => true]);

                // 1️⃣5️⃣ Handle payment based on payment choice
                $registrationPayment = null;
                $successMessage = 'Service request created successfully!';

                if ($request->filled('payment_choice') && $request->payment_choice === 'now') {
                    try {
                        $transactionRef = $this->generateTransactionReference();

                        // Create registration payment record
                        $registrationPayment = RegistrationPayment::create([
                            'user_id' => $user->id,
                            'service_request_id' => $serviceRequest->id,
                            'amount' => 10000,
                            'payment_method' => $request->payment_method,
                            'transaction_reference' => $transactionRef,
                            'status' => $request->payment_method === 'bank_transfer' ? 'pending_approval' : 'completed',
                            'payment_details' => json_encode($this->preparePaymentDetails($request)),
                            'bank_receipt' => $bankReceiptPath,
                            'paid_at' => $request->payment_method === 'bank_transfer' ? null : now(),
                            'expires_at' => $request->payment_method === 'bank_transfer' ? null : now()->addYear(),
                        ]);

                        Log::info('Registration payment created', [
                            'payment_id' => $registrationPayment->id,
                            'method' => $request->payment_method,
                            'status' => $registrationPayment->status
                        ]);

                        // Update service request and user based on payment status
                        if ($request->payment_method === 'bank_transfer') {
                            $serviceRequest->update([
                                'payment_status' => 'pending_approval',
                                'registration_status' => 'pending_approval',
                                'status' => 'pending_approval'
                            ]);

                            $successMessage = 'Thank you for your service request! Your registration payment is pending admin approval.';
                        } else {
                            $serviceRequest->update([
                                'payment_status' => 'paid',
                                'registration_paid' => true,
                                'registration_paid_at' => now(),
                                'registration_expires_at' => now()->addYear(),
                                'registration_status' => 'active',
                                'status' => 'active'
                            ]);

                            $user->update([
                                'is_registered' => true,
                                'registration_date' => now(),
                                'registration_expires_at' => now()->addYear(),
                                'registration_status' => 'active'
                            ]);

                            $successMessage = 'Thank you! Your service request and registration are now active.';
                        }
                    } catch (\Exception $e) {
                        Log::error('Payment processing failed: ' . $e->getMessage());

                        $serviceRequest->update([
                            'payment_status' => 'failed',
                            'registration_status' => 'payment_failed',
                            'status' => 'draft'
                        ]);

                        $successMessage = 'Service request created, but payment failed. You can complete payment later.';
                    }
                } else {
                    // Pay later or no payment choice
                    $serviceRequest->update([
                        'payment_status' => 'pending',
                        'registration_status' => 'pending',
                        'status' => 'draft'
                    ]);

                    $successMessage = 'Service request created successfully! You can complete your registration payment later.';
                }

                DB::commit();
                Log::info('Transaction committed for full request');

                // 1️⃣6️⃣ Store session data for guest access
                if (!Auth::check()) {
                    session(['recently_created_service_request' => $serviceRequest->id]);
                    $allowedRequests = session('guest_service_requests', []);
                    $allowedRequests[] = $serviceRequest->id;
                    session(['guest_service_requests' => array_unique($allowedRequests)]);
                }

                // 1️⃣7️⃣ Send notifications
                if ($isNewUser) {
                    try {
                        $this->sendWelcomeEmail($user, $request->phone);
                        Log::info('Welcome email sent for new user');
                    } catch (\Exception $e) {
                        Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }

                    session()->flash('password_notification', [
                        'phone' => $request->phone,
                        'email' => $user->email,
                        'message' => 'Your account has been created. Use your phone number as your password to login.'
                    ]);
                }

                // 1️⃣8️⃣ Redirect based on payment status
                if ($registrationPayment && $request->payment_method !== 'bank_transfer') {
                    return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])
                        ->with('success', $successMessage)
                        ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
                } else {
                    return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])
                        ->with('success', $successMessage)
                        ->with('password_info', $isNewUser ? 'Your password is your phone number. You can change it in your account settings.' : null);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed in service request creation', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token', 'password', 'card_number', 'card_cvv', 'wallet_pin'])
            ]);

            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service request creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());

            return back()->withErrors(['error' => 'An error occurred while processing your request. Please try again.'])
                ->withInput();
        }
    }

    public function savePartialRegistration(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate only personal information
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:15',
                'gender' => 'required|in:male,female,no_preference',
                'country_of_residence' => 'required|string|max:255',
                'state_of_residence' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'on_behalf' => 'required|in:yes,no',
                'dob_month' => 'nullable|integer|min:0|max:12',
                'dob_day' => 'nullable|integer|min:0|max:31',
            ]);

            // Check if user exists by email
            $user = User::where('email', $request->email)->first();
            $isNewUser = false;

            if (!$user) {
                // Create new user with phone number as password
                $user = User::create([
                    'name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'country_of_residence' => $request->country_of_residence,
                    'state_of_residence' => $request->state_of_residence,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                    'account_type' => 'client',
                    'password' => Hash::make($request->phone),
                    'form_completed' => false,
                    'registration_status' => 'incomplete',
                    'registration_source' => 'service_request_wizard',
                ]);
                $isNewUser = true;

                // Log the user in automatically - THIS IS CRITICAL
                Auth::login($user, true); // Add 'true' for "remember me"

                // Send welcome email
                $this->sendWelcomeEmail($user, $request->phone);
            } else {
                // Update existing user information
                $user->update([
                    'name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'country_of_residence' => $request->country_of_residence,
                    'state_of_residence' => $request->state_of_residence,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                    'form_completed' => false,
                    'registration_status' => 'incomplete',
                    'registration_source' => 'service_request_wizard',
                ]);

                // Log the existing user in
                Auth::login($user, true);
            }

            // Handle file uploads
            if ($request->hasFile('passport')) {
                $passportPath = $request->file('passport')->store('passports', 'public');
                $user->passport = $passportPath;
            }

            if ($request->hasFile('id_card')) {
                $idCardPath = $request->file('id_card')->store('idcards', 'public');
                $user->id_card = $idCardPath;
            }

            // Save user if files were uploaded
            if ($request->hasFile('passport') || $request->hasFile('id_card')) {
                $user->save();
            }

            // Create a lead record for follow-up
            Lead::create([
                'user_id' => $user->id,
                'source' => 'service_request_wizard',
                'status' => 'registered_incomplete',
                'notes' => 'User registered but did not complete service request',
                'follow_up_date' => now()->addDays(3),
            ]);

            DB::commit();

            // IMPORTANT: Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Store in session for JavaScript to know user is saved
            session([
                'user_data_saved' => true,
                'user_id' => $user->id,
                'is_new_user' => $isNewUser
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your information has been saved successfully.',
                'user_id' => $user->id,
                'is_new_user' => $isNewUser,
                'is_logged_in' => Auth::check(), // Debug info
                'user_email' => $user->email, // Debug info
                'redirect_url' => route('service-request.registration-complete')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Partial registration failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save your information: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // For debugging only
            ], 500);
        }
    }

    // Add this method to handle the registration complete page
    public function registrationComplete()
    {
        return view('service-request.registration-complete');
    }


    /**
     * Generate transaction reference
     */
    private function generateTransactionReference(): string
    {
        return 'REG_' . strtoupper(Str::random(8)) . '_' . time();
    }

    /**
     * Prepare payment details
     */
    private function preparePaymentDetails(Request $request): array
    {
        $details = [
            'payment_method' => $request->payment_method,
            'timestamp' => now()->toDateTimeString(),
        ];

        switch ($request->payment_method) {
            case 'card':
                $details['card_last_four'] = substr($request->card_number, -4);
                $details['cardholder_name'] = $request->cardholder_name;
                $details['card_expiry'] = $request->card_expiry;
                break;

            case 'bank_transfer':
                $details['bank_reference'] = $request->bank_reference;
                $details['transfer_date'] = $request->transfer_date;
                $details['bank_name'] = 'GT Bank';
                $details['account_name'] = 'Pik and Crest Dynamic Concepts LTD';
                $details['account_number'] = '0051480410';
                break;

            case 'wallet':
                // Implement wallet details if you have a wallet system
                $details['wallet_payment'] = true;
                break;
        }

        return $details;
    }

    /**
     * Send welcome email
     */
    private function sendWelcomeEmail(User $user, string $phoneNumber): void
    {
        try {
            // Implement your email sending logic here
            // Example:
            // Mail::to($user->email)->send(new WelcomeEmail($user, $phoneNumber));
            Log::info('Welcome email would be sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Welcome email failed: ' . $e->getMessage());
        }
    }


    // private function sendWelcomeEmail($user, $phoneNumber)
    // {
    //     try {
    //         // You can implement email sending here
    //         // For example, using Laravel Mail or Notification

    //         Mail::to($user->email)->send(new WelcomeEmail($user, $phoneNumber));
    //     } catch (\Exception $e) {
    //         \Log::error('Welcome email failed: ' . $e->getMessage());
    //     }
    // }

    /**
     * Additional payment validation logic
     */
    private function validatePaymentDetails(Request $request)
    {
        $errors = [];

        // Card payment validation
        if ($request->payment_method === 'card') {
            // Validate card number format (Luhn algorithm)
            if (!$this->validateCardNumber($request->card_number)) {
                $errors['card_number'] = 'Invalid card number. Please check and try again.';
            }

            // Validate expiry date
            if (!$this->validateExpiryDate($request->card_expiry)) {
                $errors['card_expiry'] = 'Invalid or expired card. Please check the expiry date.';
            }

            // Validate CVV
            if (!$this->validateCVV($request->card_cvv)) {
                $errors['card_cvv'] = 'Invalid CVV. Please check and try again.';
            }
        }

        // Bank transfer validation
        if ($request->payment_method === 'bank_transfer') {
            // Validate transfer date is not in the future (already handled in main validation)

            // FIXED: Updated regex to match the format generated in frontend (REF_ABC12345)
            if ($request->bank_reference && !preg_match('/^REF_[A-Z0-9]{8,12}$/i', $request->bank_reference)) {
                $errors['bank_reference'] = 'Invalid bank reference format. Should be like REF_ABC12345';
            }

            // Validate receipt file
            if ($request->hasFile('bank_receipt')) {
                $file = $request->file('bank_receipt');
                $allowedMimes = ['jpg', 'jpeg', 'png', 'pdf'];
                if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
                    $errors['bank_receipt'] = 'Invalid file type. Please upload JPG, PNG, or PDF files only.';
                }
            }
        }

        // Wallet payment validation
        if ($request->payment_method === 'wallet') {
            // Check wallet balance
            $userBalance = $this->getUserWalletBalance(auth()->id());
            if ($userBalance < 10000) {
                $errors['wallet_pin'] = 'Insufficient wallet balance. Please top up your wallet or choose another payment method.';
            }

            // Validate wallet PIN format
            if ($request->wallet_pin && !preg_match('/^\d{6}$/', $request->wallet_pin)) {
                $errors['wallet_pin'] = 'Wallet PIN must be exactly 6 digits.';
            }

            // Validate wallet PIN against user's actual PIN (if implemented)
            if (!$this->validateWalletPIN(auth()->id(), $request->wallet_pin)) {
                $errors['wallet_pin'] = 'Invalid wallet PIN.';
            }
        }

        if (!empty($errors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }
    }

    /**
     * Validate card number using Luhn algorithm
     */
    private function validateCardNumber($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        // Check if card number is empty or too short
        if (empty($cardNumber) || strlen($cardNumber) < 13) {
            return false;
        }

        $sum = 0;
        $reverse = strrev($cardNumber);

        for ($i = 0, $j = strlen($reverse); $i < $j; $i++) {
            if (($i % 2) == 0) {
                $val = $reverse[$i];
            } else {
                $val = $reverse[$i] * 2;
                if ($val > 9) {
                    $val -= 9;
                }
            }
            $sum += $val;
        }

        return (($sum % 10) === 0);
    }

    /**
     * Validate card expiry date
     */
    private function validateExpiryDate($expiryDate)
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiryDate, $matches)) {
            return false;
        }

        $month = (int)$matches[1];
        $year = (int)$matches[2] + 2000; // Convert YY to YYYY

        $expiry = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return $expiry->isFuture();
    }

    /**
     * Validate CVV
     */
    private function validateCVV($cvv)
    {
        return preg_match('/^[0-9]{3,4}$/', $cvv);
    }

    /**
     * Validate wallet PIN (implement based on your wallet system)
     */
    private function validateWalletPIN($userId, $pin)
    {
        // This is a placeholder - implement based on your wallet system
        $user = \App\Models\User::find($userId);

        // Example: Check if PIN matches user's wallet PIN
        // return Hash::check($pin, $user->wallet_pin_hash);

        // For demo purposes, accept any 6-digit PIN
        // In production, you should verify against the actual stored PIN hash
        return preg_match('/^\d{6}$/', $pin);
    }

    /**
     * Get user wallet balance (implement based on your wallet system)
     */
    private function getUserWalletBalance($userId)
    {
        // This is a placeholder - implement based on your wallet system
        // return \App\Models\Wallet::where('user_id', $userId)->value('balance') ?? 0;

        // For demo purposes, return a balance that's sufficient
        return 15000;
    }

    /**
     * Prepare payment details for storage
     */
    // private function preparePaymentDetails(Request $request)
    // {
    //     $details = [
    //         'payment_method' => $request->payment_method,
    //         'paid_at' => now()->toDateTimeString(),
    //         'user_id' => auth()->id(),
    //     ];

    //     switch ($request->payment_method) {
    //         case 'card':
    //             $details['card_last_four'] = substr(preg_replace('/\D/', '', $request->card_number), -4);
    //             $details['card_type'] = $this->getCardType($request->card_number);
    //             $details['expiry_date'] = $request->card_expiry;
    //             $details['cardholder_name'] = $request->cardholder_name;
    //             break;

    //         case 'bank_transfer':
    //             $details['bank_reference'] = $request->bank_reference;
    //             $details['transfer_date'] = $request->transfer_date;
    //             $details['status'] = 'pending_approval';
    //             $details['receipt_uploaded'] = $request->hasFile('bank_receipt');
    //             break;

    //         case 'wallet':
    //             $details['wallet_transaction_id'] = 'WALLET_' . time();
    //             $details['wallet_user_id'] = auth()->id();
    //             break;
    //     }

    //     return $details;
    // }

    /**
     * Get card type based on card number
     */
    private function getCardType($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        if (preg_match('/^4/', $cardNumber)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'MasterCard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $cardNumber)) {
            return 'Discover';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Validate card details comprehensively
     */
    private function validateCardDetails(Request $request)
    {
        $cardNumber = preg_replace('/\D/', '', $request->card_number);

        // Check card length based on type
        $cardType = $this->getCardType($cardNumber);
        $validLength = true;

        switch ($cardType) {
            case 'Visa':
            case 'MasterCard':
            case 'Discover':
                $validLength = (strlen($cardNumber) === 16);
                break;
            case 'American Express':
                $validLength = (strlen($cardNumber) === 15);
                break;
            default:
                $validLength = (strlen($cardNumber) >= 13 && strlen($cardNumber) <= 19);
                break;
        }

        if (!$validLength) {
            return [
                'valid' => false,
                'message' => 'Invalid card number length for ' . $cardType
            ];
        }

        return [
            'valid' => true,
            'details' => [
                'last_four' => substr($cardNumber, -4),
                'type' => $cardType,
                'expiry' => $request->card_expiry,
                'cardholder_name' => $request->cardholder_name
            ]
        ];
    }

    /**
     * Generate unique transaction reference
     */
    // private function generateTransactionReference()
    // {
    //     return 'TXN_' . time() . '_' . strtoupper(\Str::random(6));
    // }
}
