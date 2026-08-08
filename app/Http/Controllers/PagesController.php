<?php



namespace App\Http\Controllers;



use App\Models\LiveInService;

use Illuminate\Http\Request;

use App\Models\Package;

use App\Models\OneTimeService;

use App\Models\Apartments;

use App\Models\Discount;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Rating;

use App\Models\Service;

use App\Models\LiveInServiceRequest;

use App\Models\ServicePackageCost;

use App\Models\Payment;

use App\Models\RegistrationPayment;

use App\Models\RequestClick;

use App\Models\SearchLog;

use App\Models\ServiceRequest;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;



class PagesController extends Controller

{

    public function here()

    {

        // Get the logged-in user's information

        $user = Auth::user();



        // Get ratings where the logged-in user is the one being rated (applicant)

        $ratings = Rating::where('user_id', $user->id)->get();



        // Calculate average rating

        $averageRating = $ratings->avg('rating');

        $totalCount = $ratings->count();



        // Extract user_id

        $user_id = $user->id;



        // Define an array of inspirational quotes

        $quotes = [

            "Success is not the key to happiness. Happiness is the key to success. If you love what you are doing, you will be successful.",

            "The best way to find yourself is to lose yourself in the service of others.",

            "Excellence is not an act, but a habit. You are what you repeatedly do.",

            "Small acts, when multiplied by millions of people, can transform the world.",

            "It always seems impossible until it’s done.",

            "The best preparation for tomorrow is doing your best today.",

            "Dream big, start small, and act now."

        ];



        // Select a random quote

        $randomQuote = $quotes[array_rand($quotes)];



        // Pass data to the view, including the random quote

        return view('here', compact('user', 'ratings', 'averageRating', 'totalCount', 'user_id', 'randomQuote'));

    }





    public function getstarted(Request $request)

    {

        // Get the logged-in user's information

        $services = Service::all();

        return view('get-started', compact('services'));

    }







    public function onetime(Request $request)

    {

        $services = OneTimeService::all();

        return view('one-time', compact(

            'services'

        ));

    }





    public function livein(Request $request)

    {

        $services = LiveInService::all();

        return view('live-in', compact(

            'services'

        ));

    }





    public function packages(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        return view('packages', compact('packages'));

    }



    public function ourLiveInServices(Request $request)

    {

        // Check if a user is authenticated

        $user = Auth::user();

        $clientId = $user ? $user->id : null;





        // Fetch payment data in ONE query instead of four
        $payment = $clientId ? Payment::where('user_id', $clientId)
            ->where('plan_type', 'househelp')
            ->latest()
            ->first() : null;

        $hasApprovedPayment = $payment && $payment->status === 'approved';
        $pendingPayment = $payment && $payment->status === 'pending';
        $subscription = $hasApprovedPayment ? $payment : null;

        // Count searches made by the user
        $searchCount = 0;

        if ($clientId && $hasApprovedPayment) {
            $searchCount = RequestClick::where('user_id', $clientId)
                ->where('payment_id', $payment->id)
                ->count();
        }




        // Determine subscription limit

        $subscriptionLimit = $payment ? $payment->num_searches : 0;



        // Set search limit based on subscription (default to 5 if no active subscription)

        // $subscriptionLimit = $subscription ? $subscription->num_searches : 5;



        // Calculate remaining searches

        $remainingSearches = max($subscriptionLimit - $searchCount, 0);



        // Check if subscription is expired (based on expiry date or search limit)

        $isSubscriptionExpired = !$subscription || Carbon::now()->greaterThan($subscription->expiry_date) || $remainingSearches <= 0;



        // Base query to get house helps who are live-in or both and available

        $query = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('availability', 1)

            ->where(function ($query) {

                $query->where('applicant_type', 'live-in')

                    ->orWhere('applicant_type', 'semi-live-in');

            });



        // Apply filters based on user input

        if ($request->filled('gender')) {

            $query->where('gender', $request->input('gender'));

        }



        if ($request->filled('applicant_type')) {

            $query->where('applicant_type', $request->input('applicant_type'));

        }



        if ($request->filled('state_of_origin')) {

            $query->where('state_of_origin', 'like', '%' . $request->input('state_of_origin') . '%');

        }



        if ($request->filled('state_of_residence')) {

            $query->where('state_of_residence', 'like', '%' . $request->input('state_of_residence') . '%');

        }



        if ($request->filled('specialties')) {

            $specialty = $request->input('specialties');

            $query->whereJsonContains('specialties', $specialty);

        }



        // Randomize results before paginating

        $househelps = $query->inRandomOrder()->paginate(6);



        // Save the search query in the SearchLog table if the user is authenticated

        if ($clientId) {

            SearchLog::create([

                'user_id' => $clientId,

                'search_parameters' => json_encode($request->all()), // Store search filters

            ]);

        }



        // Process ratings and requests only for authenticated users

        $ratings = collect();

        $clientRatings = collect();



        if ($clientId) {

            // Fetch ratings for displayed house helps

            $househelpIds = $househelps->pluck('id');

            $ratings = Rating::whereIn('user_id', $househelpIds)->get()->groupBy('user_id');



            // Fetch current client’s ratings for house helps

            $clientRatings = Rating::whereIn('user_id', $househelpIds)

                ->where('rated_by', $clientId)

                ->get()->keyBy('user_id');

        }

        // Preload ALL requests in ONE query to fix N+1 problem
        $allRequests = collect();
        if ($clientId && isset($househelpIds)) {
            $allRequests = LiveInServiceRequest::where('client_id', $clientId)
                ->whereIn('househelp_id', $househelpIds)
                ->get()
                ->groupBy('househelp_id');
        }

        foreach ($househelps as $househelp) {

            $userRatings = $ratings->get($househelp->id, collect());

            $househelp->averageRating = $userRatings->avg('rating') ?? 0;

            $househelp->totalCount = $userRatings->count();



            // Format total count (e.g., '3k' for large numbers)

            $househelp->totalFormatted = $househelp->totalCount >= 1000

                ? number_format($househelp->totalCount / 1000, 1) . 'k'

                : $househelp->totalCount;



            // Set client-specific data if authenticated

            if ($clientId) {

                $househelp->clientRating = $clientRatings->get($househelp->id)->rating ?? null;

                // Use preloaded requests instead of per-staff DB queries
                $staffRequests = $allRequests->get($househelp->id, collect());
                $househelp->activeRequest = $staffRequests->contains('status', 'pending');
                $househelp->acceptedRequest = $staffRequests->contains('status', 'accepted');

            } else {

                // Guests don't have client-specific data

                $househelp->clientRating = null;

                $househelp->activeRequest = false;

                $househelp->acceptedRequest = false;

            }

        }



        // List of Nigerian states for filters

        $states = [

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

            'FCT Abuja',

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

            'Zamfara'

        ];



        // Calculate remaining searches

        $remainingSearches = max($subscriptionLimit - $searchCount, 0);



        // Fetch specialties for the filter dropdown

        $specialties = Service::pluck('service_name');



        // Return view with data

        return view('ourLiveInServices', compact(

            'househelps',

            'states',

            'specialties',

            'hasApprovedPayment',

            'searchCount',

            'pendingPayment',

            'remainingSearches', // Now defined

            'isSubscriptionExpired', // Now defined

            'remainingSearches',

            'payment',

            'subscriptionLimit'

        ));

    }









    public function premiumLiveOut(Request $request)

    {

        return view('premiumLiveOut');

    }





    public function single(Request $request)

    {

        // Get the logged-in user's information

        $services = Service::all();

        return view('single', compact('services'));

    }







    public function getDiscount(Request $request)

    {

        // Get the number of selected services from the request

        $serviceCount = count($request->input('serviceName', [])); // Count selected services



        // Fetch the discount based on the service count

        $discount = Discount::where('number_of_services', $serviceCount)->first();



        // Return the discount percentage as a JSON response

        return response()->json([

            'discount_percent' => $discount ? $discount->discount_percent : 0 // Return 0 if no discount found

        ]);

    }







    public function newonetimeservice(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        $service = OneTimeService::all();

        return view('admin.newonetimeservice', compact(

            'packages',

            'service'

        ));

    }





    public function newliveinservice(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        $service = OneTimeService::all();

        return view('admin.newliveinservice', compact(

            'packages',

            'service'

        ));

    }









    public function newpackage(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        return view('admin.newpackage', compact('packages'));

    }





    public function cleaning(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        $apartmentdetails = Apartments::all(); // Fetch all packages from the database

        // $packages = Package::where('serviceName', 1)->get();

        return view('CleaningAndHousekeeping', compact('packages', 'apartmentdetails'));

    }





    public function laundry(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        // $packages = Package::where('serviceName', 2)->get();

        $apartmentdetails = Apartments::all(); // Fetch all packages from the database

        return view('LaundryAnd', compact('packages', 'apartmentdetails'));

    }



    public function errand(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        // $packages = Package::where('serviceName', 3)->get();

        $apartmentdetails = Apartments::all(); // Fetch all packages from the database

        return view('ErrandAndHousekeeping', compact('packages', 'apartmentdetails'));

    }





    public function nanny(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        // $packages = Package::where('serviceName', 4)->get();

        $apartmentdetails = Apartments::all(); // Fetch all packages from the database

        return view('NannyAndHousekeeping', compact('packages', 'apartmentdetails'));

    }







    public function cook(Request $request)

    {

        $packages = Package::all(); // Fetch all packages from the database

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        return view('CookAndErrands', compact(

            'packages',

            'apartmentdetails'

        ));

    }









    //  admin packages .................................................................



    public function allpackages(Request $request)

    {

        // $packages = Package::all(); // Fetch all packages from the database

        $packages = Package::all();

        return view('admin.allpackages', compact('packages'));

    }









    public function Receipt()

    {

        // Check if order is available in session

        if (session()->has('order')) {

            $order = session('order');

            return view('receipt', compact('order'));

        }

        return redirect()->route('receipt'); // In case someone directly navigates here without submitting the form

    }







    // Show Terms and Conditions page



    // Handle Terms and Conditions acceptance

    public function acceptTerms(Request $request)

    {

        $user = Auth::user();

        if ($user) {

            $user->terms_accepted = true; // Assuming `terms_accepted` is a boolean field in the users table

            $user->save();

            return redirect()->route('applicantdashboard')->with('success', 'You have accepted the Terms and Conditions.');

        }

        return redirect()->route('login')->with('error', 'Please log in to accept the Terms and Conditions.');

    }









    public function showTerms()

    {

        $user = Auth::user();

        // Check if the user has already uploaded the guarantor forms

        if ($user->guarantors_forms_uploaded && $user->guarantors_forms_uploaded_2 && $user->terms_accepted) {

            // Redirect to a different page if both forms are uploaded

            return redirect()->route('applicantdashboard')->with('info', 'You have already uploaded the guarantor forms.');

        }



        // return view('terms_and_conditions', ['applicant_type' => $user->applicant_type]);

        return view('terms_and_conditions', compact('user'));

    }





    public function showTermsCondition()

    {

        $user = Auth::user();

        return view('our_terms_and_conditions');

    }





    public function storeapplicanttype(Request $request)

    {

        $request->validate([

            'applicant_type' => 'required|string',

        ]);



        $user = Auth::user();



        if ($user) {

            $user->applicant_type = $request->input('applicant_type');

            $user->save();



            return response()->json(['success' => true, 'message' => 'Applicant type updated successfully.']);

        } else {

            return response()->json(['success' => false, 'message' => 'User not authenticated.']);

        }

    }



    public function maintenance(Request $request)

    {

        $services = OneTimeService::all();

        return view('maintenance', compact(

            'services'

        ));

    }





    public function comingsoon(Request $request)

    {

        $services = OneTimeService::all();

        return view('coming-soon', compact(

            'services'

        ));

    }



    public function faq(Request $request)

    {

        return view('faq');

    }



    public function showAllTermsCondition(Request $request)

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



        // Determine discount based on service count

        $serviceCount = count($request->input('serviceName', []));

        $discount = Discount::where('number_of_services', $serviceCount)->first();

        $discountPercent = $discount ? ($discount->discount_percent / 100) : 0;



        return view('t&c', compact(

            'packages',

            'servicePackageCosts',

            'apartments',

            'servicesWithoutCost',

            'servicesWithHours',

            'servicesWithCost',

            'services',

            'allServices'

        ));

    }







    public function confirmation($id)

    {

        try {

            $serviceRequest = ServiceRequest::with(['user', 'registrationPayment'])->findOrFail($id);



            // If user is logged in, verify ownership

            if (Auth::check() && $serviceRequest->user_id !== Auth::id()) {

                abort(403, 'Unauthorized access.');

            }



            // For guests, store service request ID in session to allow viewing

            if (!Auth::check()) {

                $allowedRequests = session('guest_service_requests', []);

                if (!in_array($serviceRequest->id, $allowedRequests)) {

                    // Allow viewing if they just created it (check session)

                    $recentlyCreated = session('recently_created_service_request');

                    if ($recentlyCreated !== $serviceRequest->id) {

                        abort(403, 'Please log in to view this page.');

                    }

                }

            }



            return view('service-request.confirmation', compact('serviceRequest'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->route('home')

                ->with('error', 'Service request not found.');

        } catch (\Exception $e) {

            Log::error('Error loading confirmation page: ' . $e->getMessage());

            return redirect()->route('bookings')

                ->with('error', 'An error occurred while loading the page.');

        }

    }



    /**

     * Show payment page for a service request

     */

    public function showPaymentPage($id)

    {

        try {

            $serviceRequest = ServiceRequest::with('user')->findOrFail($id);



            // Verify ownership

            if (Auth::id() !== $serviceRequest->user_id) {

                abort(403, 'Unauthorized access.');

            }



            // Check if already paid

            if ($serviceRequest->registration_paid) {

                return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])

                    ->with('info', 'Registration fee has already been paid.');

            }



            // Check if there's already a pending payment

            $pendingPayment = RegistrationPayment::where('service_request_id', $serviceRequest->id)

                ->whereIn('status', ['pending', 'pending_approval'])

                ->first();



            if ($pendingPayment) {

                return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])

                    ->with('info', 'You already have a pending payment for this service request.');

            }



            // Get user's wallet balance if applicable

            $walletBalance = $this->getUserWalletBalance(Auth::user());



            return view('service-request.payment', compact('serviceRequest', 'walletBalance'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return redirect()->route('dashboard')

                ->with('error', 'Service request not found.');

        } catch (\Exception $e) {

            Log::error('Error showing payment page: ' . $e->getMessage());

            return redirect()->route('dashboard')

                ->with('error', 'An error occurred while loading the payment page.');

        }

    }



    public function processPayment(Request $request, $id)

    {

        DB::beginTransaction();



        try {

            $serviceRequest = ServiceRequest::with('user')->findOrFail($id);



            // Verify ownership

            if (auth()->id() !== $serviceRequest->user_id) {

                abort(403);

            }



            // Check if already paid

            if ($serviceRequest->registration_paid) {

                return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])

                    ->with('info', 'Registration fee has already been paid.');

            }



            // Validate payment request

            $validationRules = [

                'payment_method' => 'required|in:card,bank_transfer,wallet',

                'payment_terms' => 'required|accepted',

                'card_number' => 'required_if:payment_method,card|string|min:13|max:19',

                'card_expiry' => 'required_if:payment_method,card|string|max:5',

                'card_cvv' => 'required_if:payment_method,card|string|min:3|max:4',

                'cardholder_name' => 'required_if:payment_method,card|string|max:255',

                'bank_reference' => 'required_if:payment_method,bank_transfer|string|max:255',

                'transfer_date' => 'required_if:payment_method,bank_transfer|date|before_or_equal:today',

                'bank_receipt' => 'required_if:payment_method,bank_transfer|file|mimes:jpg,jpeg,png,pdf|max:5120',

                'wallet_pin' => 'required_if:payment_method,wallet|string|min:6|max:6',

            ];



            $validated = $request->validate($validationRules, [

                'payment_method.required' => 'Please select a payment method.',

                'payment_method.in' => 'Please select a valid payment method.',

                'payment_terms.required' => 'You must accept the payment terms to proceed.',

                'payment_terms.accepted' => 'You must accept the payment terms to proceed.',



                'card_number.required_if' => 'Card number is required for card payments.',

                'card_number.min' => 'Card number must be at least 13 digits.',

                'card_number.max' => 'Card number cannot exceed 19 digits.',



                'card_expiry.required_if' => 'Expiry date is required for card payments.',

                'card_expiry.max' => 'Expiry date must be in MM/YY format.',



                'card_cvv.required_if' => 'CVV is required for card payments.',

                'card_cvv.min' => 'CVV must be at least 3 digits.',

                'card_cvv.max' => 'CVV cannot exceed 4 digits.',



                'cardholder_name.required_if' => 'Cardholder name is required for card payments.',



                'bank_reference.required_if' => 'Bank transfer reference is required.',

                'transfer_date.required_if' => 'Transfer date is required.',

                'transfer_date.before_or_equal' => 'Transfer date cannot be in the future.',

                'bank_receipt.required_if' => 'Bank transfer receipt is required.',

                'bank_receipt.mimes' => 'Receipt must be a JPG, PNG, or PDF file.',

                'bank_receipt.max' => 'Receipt file size cannot exceed 5MB.',



                'wallet_pin.required_if' => 'Wallet PIN is required for wallet payments.',

                'wallet_pin.min' => 'Wallet PIN must be exactly 6 digits.',

                'wallet_pin.max' => 'Wallet PIN must be exactly 6 digits.',

            ]);



            // Handle file upload

            $bankReceiptPath = null;

            if ($request->hasFile('bank_receipt')) {

                $bankReceiptPath = \App\Support\SecureDocument::store($request->file('bank_receipt'), 'receipts');

            }



            // Validate card details if payment method is card

            if ($request->payment_method === 'card') {

                $cardValidation = $this->validateCardDetails($request);

                if (!$cardValidation['valid']) {

                    return back()->withErrors(['card_error' => $cardValidation['message']])->withInput();

                }

            }



            // Generate transaction reference

            $transactionRef = 'REG_' . strtoupper(Str::random(8)) . '_' . time();



            // Prepare payment details

            $paymentDetails = [];

            switch ($request->payment_method) {

                case 'card':

                    $paymentDetails = [

                        'card_last_four' => substr($request->card_number, -4),

                        'cardholder_name' => $request->cardholder_name,

                        'card_expiry' => $request->card_expiry,

                        'payment_method' => 'card',

                        'timestamp' => now()->toDateTimeString()

                    ];

                    break;



                case 'bank_transfer':

                    $paymentDetails = [

                        'bank_reference' => $request->bank_reference,

                        'transfer_date' => $request->transfer_date,

                        'bank_name' => 'GT Bank',

                        'account_name' => 'Pik and Crest Dynamic Concepts LTD',

                        'account_number' => '0051480410',

                        'payment_method' => 'bank_transfer',

                        'timestamp' => now()->toDateTimeString()

                    ];

                    break;



                case 'wallet':

                    // Validate wallet balance (you need to implement this)

                    $walletBalance = $this->checkWalletBalance(auth()->user());

                    if ($walletBalance < 10000) {

                        return back()->withErrors(['wallet_error' => 'Insufficient wallet balance.'])->withInput();

                    }



                    $paymentDetails = [

                        'wallet_payment' => true,

                        'previous_balance' => $walletBalance,

                        'new_balance' => $walletBalance - 10000,

                        'payment_method' => 'wallet',

                        'timestamp' => now()->toDateTimeString()

                    ];

                    break;

            }



            // Create registration payment record

            $registrationPayment = RegistrationPayment::create([

                'user_id' => auth()->id(),

                'service_request_id' => $serviceRequest->id,

                'amount' => 10000,

                'payment_method' => $request->payment_method,

                'transaction_reference' => $transactionRef,

                'status' => $request->payment_method === 'bank_transfer' ? 'pending_approval' : 'completed',

                'payment_details' => json_encode($paymentDetails),

                'bank_receipt' => $bankReceiptPath,

                'paid_at' => $request->payment_method === 'bank_transfer' ? null : now(),

                'expires_at' => $request->payment_method === 'bank_transfer' ? null : now()->addYear(),

            ]);



            // Update service request based on payment status

            if ($request->payment_method === 'bank_transfer') {

                $serviceRequest->update([

                    'payment_status' => 'Pending Approval',

                    'registration_status' => 'pending_approval',

                    'status' => 'pending_approval',

                    'bank_receipt' => $bankReceiptPath,

                    'registration_paid' => false // Not paid until approved

                ]);



                // Send notification to admin for approval

                $this->notifyAdminForBankTransferApproval($registrationPayment);



                $successMessage = 'Bank transfer details submitted successfully! Your payment is pending admin approval.';

            } else {

                // For card and wallet payments, mark as completed immediately

                $serviceRequest->update([

                    'payment_status' => 'Paid',

                    'registration_paid' => true,

                    'registration_paid_at' => now(),

                    'registration_expires_at' => now()->addYear(),

                    'registration_status' => 'active',

                    'status' => 'active'

                ]);



                // Update user registration status

                $user = auth()->user();

                $user->update([

                    'is_registered' => true,

                    'registration_date' => now(),

                    'registration_expires_at' => now()->addYear(),

                    'registration_status' => 'active'

                ]);



                // Deduct from wallet if wallet payment

                if ($request->payment_method === 'wallet') {

                    $this->deductFromWallet(auth()->user(), 10000);

                }



                $successMessage = 'Registration fee paid successfully! Your service request is now active.';

            }



            DB::commit();



            // Send payment confirmation email

            $this->sendPaymentConfirmationEmail(auth()->user(), $registrationPayment);



            // Redirect based on payment method

            if ($request->payment_method === 'bank_transfer') {

                return redirect()->route('service-request.confirmation', ['id' => $serviceRequest->id])

                    ->with('success', $successMessage);

            } else {

                return redirect()->route('orderreceipt', ['serviceRequest' => $serviceRequest->id])

                    ->with('success', $successMessage);

            }

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Payment processing failed: ' . $e->getMessage());



            return back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again.'])

                ->withInput();

        }

    }



// Helper methods that need to be implemented in your controller:



    /**

     * Validate card details

     */

    private function validateCardDetails($request)

    {

        // Implement card validation logic

        // This could include Luhn algorithm validation, expiry date check, etc.



        $cardNumber = str_replace(' ', '', $request->card_number);

        $expiry = $request->card_expiry;



        // Basic validation

        if (!preg_match('/^\d{13,19}$/', $cardNumber)) {

            return ['valid' => false, 'message' => 'Invalid card number format.'];

        }



        // Check expiry date format and validity

        if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {

            return ['valid' => false, 'message' => 'Invalid expiry date format. Use MM/YY.'];

        }



        list($month, $year) = explode('/', $expiry);

        $currentYear = date('y');

        $currentMonth = date('m');



        if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {

            return ['valid' => false, 'message' => 'Card has expired.'];

        }



        // You could add more validation like card type detection, etc.



        return [

            'valid' => true,

            'details' => [

                'last_four' => substr($cardNumber, -4),

                'cardholder_name' => $request->cardholder_name,

                'expiry_month' => $month,

                'expiry_year' => '20' . $year

            ]

        ];

    }



    /**

     * Check wallet balance

     */

    private function checkWalletBalance($user)

    {

        // Implement wallet balance check

        // This depends on how you've implemented wallet functionality



        // Example: If you have a wallet_balance field in users table

        return (float) $user->wallet_balance ?? 0;



        // Or if you have a separate Wallet model

        // return $user->wallet->balance ?? 0;

    }



    /**

     * Deduct from wallet

     */

    private function deductFromWallet($user, $amount)

    {

        // Implement wallet deduction logic



        // Example with field in users table

        $user->wallet_balance -= $amount;

        $user->save();



        // Create wallet transaction record if you have a wallet_transactions table

        // WalletTransaction::create([

        //     'user_id' => $user->id,

        //     'type' => 'debit',

        //     'amount' => $amount,

        //     'description' => 'Registration fee payment',

        //     'balance_after' => $user->wallet_balance

        // ]);

    }



    /**

     * Send payment confirmation email

     */

    private function sendPaymentConfirmationEmail($user, $payment)

    {

        try {

            // Implement email sending logic

            Mail::to($user->email)->send(new PaymentConfirmationMail($user, $payment));

        } catch (\Exception $e) {

            \Log::error('Payment confirmation email failed: ' . $e->getMessage());

        }

    }



    /**

     * Notify admin for bank transfer approval

     */

    private function notifyAdminForBankTransferApproval($payment)

    {

        try {

            // Find admin users

            $admins = User::where('account_type', 'admin')->get();



            foreach ($admins as $admin) {

                // Send notification (email, in-app notification, etc.)

                Mail::to($admin->email)->send(new BankTransferApprovalNotification($payment));



                // Or create in-app notification

                // Notification::send($admin, new BankTransferPending($payment));

            }

        } catch (\Exception $e) {

            \Log::error('Admin notification failed: ' . $e->getMessage());

        }

    }









    // Add this method to save partial registration via AJAX

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



                // Log the user in automatically

                Auth::login($user);



                // Send welcome email

                if ($isNewUser) {

                    try {

                        Mail::to($user->email)->send(new WelcomeEmail($user, $request->phone));

                    } catch (\Exception $e) {

                        Log::error('Welcome email failed: ' . $e->getMessage());

                    }

                }

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

            }



            // Handle file uploads

            if ($request->hasFile('passport')) {

                $passportPath = \App\Support\SecureDocument::store($request->file('passport'), 'passports');

                $user->update(['passport' => $passportPath]);

            }



            if ($request->hasFile('id_card')) {

                $idCardPath = \App\Support\SecureDocument::store($request->file('id_card'), 'idcards');

                $user->update(['id_card' => $idCardPath]);

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



            // Store in session for JavaScript to know user is saved

            session(['user_data_saved' => true, 'user_id' => $user->id]);



            return response()->json([

                'success' => true,

                'message' => 'Your information has been saved successfully.',

                'user_id' => $user->id,

                'is_new_user' => $isNewUser,

                'redirect_url' => route('service-request.registration-complete')

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Partial registration failed: ' . $e->getMessage());



            return response()->json([

                'success' => false,

                'message' => 'Failed to save your information. Please try again.',

                'error' => $e->getMessage()

            ], 500);

        }

    }



    // Add this method to handle the registration complete page

    public function registrationComplete()

    {

        // Check if user is logged in

        if (!Auth::check()) {

            return redirect()->route('login')->with('error', 'Please login to continue.');

        }



        $user = Auth::user();



        // Update user as form completed

        $user->update(['form_completed' => true]);



        // Clear session data

        session()->forget(['user_data_saved', 'user_id']);



        return view('service-request.registration-complete', [

            'user' => $user

        ]);

    }



    // Helper method to send welcome email

    private function sendWelcomeEmail($user, $phone)

    {

        try {

            Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $phone));

        } catch (\Exception $e) {

            Log::error('Welcome email sending failed: ' . $e->getMessage());

        }

    }

}