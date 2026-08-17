<?php



namespace App\Http\Controllers;



use App\Models\User;

use Illuminate\Http\Request;

use App\Models\Package;

use App\Models\ServiceRequest;

use App\Models\Apartments;

use App\Models\LiveInServiceRequest;

use App\Models\Rating;

use App\Models\Payment;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\Service;

use App\Models\Confirmation;

use App\Models\Guarantor2;

use App\Models\Guarantor;

use Illuminate\Support\Facades\DB;

use App\Models\PaymentPlan;

use App\Models\SearchLog;

use Carbon\Carbon;

use App\Models\RequestClick;

use App\Models\Skill;



class UserController extends Controller

{

    /** Session key holding a profile photo parked across a failed validation. */
    private const PENDING_PHOTO_KEY = 'pending_profile_photo';

    /** Document fields parked the same way, field name => session key. */
    private const PENDING_DOC_FIELDS = [
        'id_card'                    => 'pending_id_card',
        'guarantor1_passport_path'   => 'pending_g1_passport',
        'guarantor1_signature_path'  => 'pending_g1_signature',
        'guarantor2_passport_path'   => 'pending_g2_passport',
        'guarantor2_signature_path'  => 'pending_g2_signature',
    ];



    public function update(Request $request)

    {

        // Validate input

        $request->validate([

            'current_password' => 'required', // Ensure old password is entered

            'password' => 'nullable|min:8|confirmed',

        ]);



        // Get the authenticated user

        $user = Auth::user();



        // Check if the current password matches the user's current password

        if (!Hash::check($request->current_password, $user->password)) {

            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }



        // Update password if provided and matches the confirmation

        if ($request->filled('password')) {

            $user->password = Hash::make($request->password);
        }



        // Save the user's updated information

        $user->save();



        // Redirect with success message

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function dashboard()

    {

        $user = Auth::user();

        $clientId = $user->id;



        // Check if the user has an approved payment

        $hasApprovedPayment = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->exists();



        $pendingPayment = Payment::where('user_id', $clientId)

            ->where('status', 'pending')

            ->exists();



        // Count user searches

        // $searchCount = RequestClick::where('user_id', $clientId)->count();

        // Count searches made by the user

        $searchCount = 0;



        if ($clientId) {

            // Get the latest approved payment for the user

            $latestPayment = Payment::where('user_id', $clientId)

                ->where('status', 'approved')

                ->latest('created_at')

                ->value('id'); // Assuming ‘id’ is the primary key for payments



            if ($latestPayment) {

                $searchCount = RequestClick::where('user_id', $clientId)

                    ->where('payment_id', $latestPayment)

                    ->count();
            }
        }



        // Get the latest active subscription

        $subscription = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->latest()

            ->first();



        // Set subscription limit (default to 5 if no active subscription)

        $subscriptionLimit = $subscription ? $subscription->num_searches : 5;



        // Calculate remaining searches

        $remainingSearches = max($subscriptionLimit - $searchCount, 0);



        // Check if subscription is expired (based on expiry date or search limit)

        $isSubscriptionExpired = !$subscription || Carbon::now()->greaterThan($subscription->expiry_date) || $remainingSearches <= 0;



        return view('dashboard', compact(

            'hasApprovedPayment',

            'pendingPayment',

            'searchCount',

            'subscriptionLimit',

            'remainingSearches',

            'isSubscriptionExpired' // Now defined

        ));
    }

    public function premium()

    {

        // $dashboard = user::where('user_id', auth()->id())->get(); // Fetch dashboard for the logged-in user

        return view(

            'premium'

            // , compact('dashboard')

        ); // Return the dashboard view with data

    }

    public function specialtiesupdate(Request $request, $id)

    {

        $request->validate([

            'specialties' => 'required|array|min:1', // Ensures at least one specialty is selected

            'specialties.*' => 'string',

        ], [

            'specialties.required' => 'Please select at least one specialty.',

        ]);



        $user = User::findOrFail($id);

        $user->specialties = $request->input('specialties');

        $user->save();



        return redirect()->route('applicant.guarantors', ['user_id' => Auth::user()->id, 'name' => Auth::user()->name])->with('success', 'Specialties updated successfully.');
    }

    // public function bookings(Request $request)

    // {

    //     // Fetch paginated service requests with assigned house helps, assistants, and client details

    //     $orders = ServiceRequest::with(['client', 'houseHelp', 'houseHelpAssistant'])

    //         ->orderByDesc('created_at')

    //         ->paginate(10);



    //     // Fetch confirmation assignments for assigned house helps and assistants

    //     $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

    //         ->orderByDesc('created_at')

    //         ->paginate(10); // Add pagination to assignments



    //     // Fetch all packages and apartments

    //     $packages = Package::all();

    //     $apartments = Apartments::all();



    //     // Count total service requests

    //     $totalServiceRequests = ServiceRequest::count();



    //     return view('bookings', compact(

    //         'orders',

    //         'packages',

    //         'apartments',

    //         'totalServiceRequests',

    //         'assignments'

    //     ));

    // }





    // public function bookings(Request $request)

    // {

    //     // Fetch only the current user's service requests

    //     $orders = ServiceRequest::with(['client', 'houseHelp', 'houseHelpAssistant'])

    //         ->where('user_id', Auth::id())

    //         ->orderByDesc('created_at')

    //         ->paginate(10);



    //     // Fetch confirmations only for the current user's service requests

    //     $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

    //         ->whereHas('serviceRequest', function ($query) {

    //             $query->where('user_id', Auth::id());

    //         })

    //         ->orderByDesc('created_at')

    //         ->paginate(10);



    //     // Fetch confirmation assignments for assigned house helps and assistants

    //     // $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

    //     //     ->where('service_request_id', Auth::id())

    //     //     ->orderByDesc('created_at')

    //     //     ->paginate(10); // Add pagination to assignments





    //     $packages = Package::all();

    //     $apartments = Apartments::all();

    //     $totalServiceRequests = ServiceRequest::where('user_id', Auth::id())->count();



    //     return view('bookings', compact(

    //         'orders',

    //         'packages',

    //         'apartments',

    //         'totalServiceRequests',

    //         'assignments'

    //     ));

    // }



    // public function bookings(Request $request)

    // {

    //     // Fetch only the current user's service requests (orders)

    //     $orders = ServiceRequest::with(['client', 'houseHelp', 'houseHelpAssistant'])

    //         ->where('user_id', Auth::id())

    //         ->orderByDesc('created_at')

    //         ->paginate(10, ['*'], 'orders_page'); // Added custom page name



    //     // Fetch confirmations only for the current user's service requests (assignments)

    //     $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

    //         ->whereHas('serviceRequest', function ($query) {

    //             $query->where('user_id', Auth::id());

    //         })

    //         ->orderByDesc('created_at')

    //         ->paginate(10, ['*'], 'assignments_page'); // Added custom page name



    //     $packages = Package::all();

    //     $apartments = Apartments::all();

    //     $totalServiceRequests = ServiceRequest::where('user_id', Auth::id())->count();



    //     return view('bookings', compact(

    //         'orders',

    //         'packages',

    //         'apartments',

    //         'totalServiceRequests',

    //         'assignments'

    //     ));

    // }



    public function bookings(Request $request)

    {

        // Fetch only the current user's service requests (orders)

        $orders = ServiceRequest::with(['client', 'houseHelp', 'houseHelpAssistant'])

            ->where('user_id', Auth::id())

            ->orderByDesc('created_at')

            ->get(); // Changed from paginate() to get() since you're combining them



        // Fetch confirmations only for the current user's service requests (assignments)

        $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

            ->whereHas('serviceRequest', function ($query) {

                $query->where('user_id', Auth::id());
            })

            ->orderByDesc('created_at')

            ->get(); // Changed from paginate() to get()



        // Combine and paginate all bookings

        $allBookings = collect();



        // Add orders

        foreach ($orders as $order) {

            $allBookings->push([

                'type' => 'order',

                'data' => $order,

                'date' => $order->created_at,

            ]);
        }



        // Add assignments

        foreach ($assignments as $assignment) {

            $allBookings->push([

                'type' => 'assignment',

                'data' => $assignment,

                'date' => $assignment->assigned_at ?? $assignment->created_at,

            ]);
        }



        // Sort by date (newest first)

        $allBookings = $allBookings->sortByDesc('date');



        // Paginate the combined results

        $page = $request->get('page', 1);

        $perPage = 10;

        $paginatedBookings = new \Illuminate\Pagination\LengthAwarePaginator(

            $allBookings->forPage($page, $perPage),

            $allBookings->count(),

            $perPage,

            $page,

            ['path' => $request->url()]

        );



        $packages = Package::all();

        $apartments = Apartments::all();

        $totalServiceRequests = ServiceRequest::where('user_id', Auth::id())->count();



        return view('bookings', compact(

            'orders',

            'packages',

            'apartments',

            'totalServiceRequests',

            'assignments',

            'paginatedBookings' // Send paginated bookings to view

        ));
    }

    public function househelprequest()

    {



        $user = Auth::user();

        $clientId = $user->id;



        // Fetch all live-in service requests made by the authenticated client, ordered by creation date

        $requests = LiveInServiceRequest::where('client_id', auth()->id())

            ->orderBy('created_at', 'desc')

            ->paginate(10); // Paginate with 10 requests per page



        // Check if the user has an approved payment

        $hasApprovedPayment = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->exists();



        $totalServiceRequests = LiveInServiceRequest::where('client_id', auth()->id())->count(); // Total number of live-in service requests for the client



        return view('househelprequest', compact(

            'requests',

            'totalServiceRequests',

            'hasApprovedPayment'



        ));
    }

    public function cancelBooking($id)

    {

        // Find the order by its ID and ensure the authenticated user owns it

        $order = ServiceRequest::where('id', $id)->where('user_id', auth()->id())->first();



        if (!$order) {

            return redirect()->route('bookings')->with('error', 'Booking not found or you are not authorized.');
        }



        // Check if the order can be canceled (e.g., only if it's pending or confirmed)

        if ($order->status == 'completed' || $order->status == 'cancelled') {

            return redirect()->route('bookings')->with('error', 'This booking cannot be cancelled.');
        }



        // Update the status to cancelled

        $order->status = 'cancelled';

        $order->save();



        return redirect()->route('bookings')->with('success', 'Booking has been successfully cancelled.');
    }

    public function updateStatus(Request $request, $id)

    {

        // Validate the new status

        $request->validate([

            'status' => 'required|in:pending,confirmed,completed,cancelled',

        ]);



        // Find the order and ensure the authenticated user owns it

        $order = ServiceRequest::where('id', $id)->where('user_id', auth()->id())->first();



        if (!$order) {

            return redirect()->route('bookings')->with('error', 'Booking not found or you are not authorized.');
        }



        // Update the order's status

        $order->status = $request->status;

        $order->save();



        return redirect()->route('bookings')->with('success', 'Booking status has been updated successfully.');
    }

    public function houseHelps(Request $request)

    {

        $user = Auth::user();

        $clientId = $user ? $user->id : null;

        $isSubscriptionExpired = true; // Default to expired



        // Count searches made by the user

        $searchCount = 0;



        if ($clientId) {

            // Get the latest approved payment for the user

            $latestPayment = Payment::where('user_id', $clientId)

                ->where('status', 'approved')

                ->latest('created_at')

                ->value('id'); // Assuming ‘id’ is the primary key for payments



            if ($latestPayment) {

                $searchCount = RequestClick::where('user_id', $clientId)

                    ->where('payment_id', $latestPayment)

                    ->count();
            }
        }



        // Fetch the latest approved payment

        $payment = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->latest()

            ->first();



        // Determine subscription limit

        $subscriptionLimit = $payment ? $payment->num_searches : 0;



        // Check if user has an approved payment

        $hasApprovedPayment = !is_null($payment);



        // Validate expiration based on expiry date and search count

        if ($payment) {

            $isSubscriptionExpired = Carbon::now()->greaterThan($payment->expiry_date) || ($searchCount >= $subscriptionLimit);
        }



        $pendingPayment = Payment::where('user_id', $clientId)

            ->where('status', 'pending')

            ->exists();



        // Query for househelps with filters

        $query = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('availability', 1)

            ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in']);



        if ($request->filled('gender')) {

            $query->where('gender', $request->input('gender'));
        }



        if ($request->filled('applicant_type')) {

            $query->where('applicant_type', $request->input('applicant_type'));
        }



        if ($request->filled('state_of_origin')) {

            $query->where('state_of_origin', $request->input('state_of_origin'));
        }



        if ($request->filled('state_of_residence')) {

            $query->where('state_of_residence', $request->input('state_of_residence'));
        }



        if ($request->filled('specialties')) {

            $query->whereJsonContains('specialties', $request->input('specialties'));
        }



        $househelps = $query->inRandomOrder()->paginate(5);



        // Log search activity if user is authenticated

        if ($clientId) {

            SearchLog::create([

                'user_id' => $clientId,

                'search_parameters' => json_encode($request->all()),

            ]);
        }



        $househelpIds = $househelps->pluck('id');



        // Fetch ratings

        $ratings = Rating::whereIn('user_id', $househelpIds)->get()->groupBy('user_id');

        $clientRatings = Rating::whereIn('user_id', $househelpIds)

            ->where('rated_by', $clientId)

            ->get()->keyBy('user_id');



        foreach ($househelps as $househelp) {

            $userRatings = $ratings->get($househelp->id, collect());

            $househelp->averageRating = $userRatings->avg('rating') ?? 0;

            $househelp->totalCount = $userRatings->count();

            $househelp->totalFormatted = $househelp->totalCount >= 1000

                ? number_format($househelp->totalCount / 1000, 1) . 'k'

                : $househelp->totalCount;



            $househelp->clientRating = $clientRatings->get($househelp->id)->rating ?? null;

            $househelp->activeRequest = LiveInServiceRequest::where('client_id', $clientId)

                ->where('househelp_id', $househelp->id)

                ->where('status', 'pending')

                ->exists();



            $househelp->acceptedRequest = LiveInServiceRequest::where('client_id', $clientId)

                ->where('househelp_id', $househelp->id)

                ->where('status', 'accepted')

                ->exists();
        }



        // List of Nigerian states

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

            'Zamfara'

        ];



        $specialties = Service::pluck('service_name');



        // Calculate remaining searches

        $remainingSearches = max($subscriptionLimit - $searchCount, 0);



        return view('househelps', compact(

            'househelps',

            'states',

            'specialties',

            'hasApprovedPayment',

            'searchCount',

            'pendingPayment',

            'isSubscriptionExpired',

            'subscriptionLimit',

            'remainingSearches'

        ));
    }

    public function paymenthistory()

    {

        // Fetch payment history for the logged-in user, paginated for better UX

        $payments = Payment::where('user_id', auth()->id())

            ->with('service') // assuming each payment is linked to a 'service' model

            ->orderBy('created_at', 'desc')

            ->paginate(10);



        return view('paymenthistory', compact('payments'));
    }

    public function makepayment()

    {

        $plans = PaymentPlan::all(); // Fetch all payment plans



        return view('payments.makepayment', compact('plans'));
    }

    public function clientprofile()

    {

        // Get the logged-in user's information

        $user = Auth::user();



        // Get ratings where the logged-in user is the one being rated (applicant)

        $ratings = Rating::where('user_id', $user->id)->get();



        // Calculate average rating

        $averageRating = $ratings->avg('rating');

        $totalCount = $ratings->count();



        return view('clientprofile', compact('user', 'ratings', 'averageRating', 'totalCount'));
    }

    public function toggleAvailability(Request $request, $id)

    {

        $user = User::findOrFail($id);

        $user->availability = $request->input('availability');

        $user->save();



        return response()->json(['success' => true]);
    }

    public function updateAvailability(Request $request)

    {

        $user = Auth::user();



        $request->validate([

            'availability' => 'required|boolean',

        ]);



        // Update availability

        $user->availability = $request->input('availability');

        $user->save();



        return response()->json(['success' => true]);
    }

    public function applicantregister()

    {

        $services = Service::all();

        $applicant = null;

        return view('auth.applicantregister', compact('services', 'applicant'));
    }

    public function updateUserStatus(Request $request, $id)

    {

        $user = User::findOrFail($id);

        $user->approval_status = $request->input('status');

        $user->save();



        return redirect()->back()->with('success', 'User status updated successfully.');
    }


    public function index(Request $request)

    {

        $search = $request->input('search');

        $users = User::query()

            ->when($search, function ($query, $search) {

                $query->where('name', 'like', "%{$search}%")

                    ->orWhere('middle_name', 'like', "%{$search}%")

                    ->orWhere('last_name', 'like', "%{$search}%")

                    ->orWhere('phone', 'like', "%{$search}%");
            })

            ->paginate(10);



        return view('users.index', compact('users'));
    }

    public function applyhousehelp()

    {

        // Block logged-in clients
        if (Auth::check() && Auth::user()->account_type === 'client') {
            return redirect()->route('dashboard')
                ->with('error', 'You are registered as a client and cannot apply as a househelp.');
        }


        $services = Service::all();
        $user = Auth::user();
        $applicant = $user;
        $guarantor1 = \App\Models\Guarantor::where('applicant_id', $user->id ?? 0)->first();
        $guarantor2 = \App\Models\Guarantor2::where('applicant_id', $user->id ?? 0)->first();

        return view('liveouts.liveouts', compact('services', 'user', 'applicant', 'guarantor1', 'guarantor2'));
    }

    public function househelpapply()

    {

        // Block logged-in clients
        if (Auth::check() && Auth::user()->account_type === 'client') {
            return redirect()->route('dashboard')
                ->with('error', 'You are registered as a client and cannot apply as a househelp.');
        }

        $services = Service::all();

        return view('liveouts.househelpapply', compact(

            'services',

        ));
    }


    public function storeliveouts(Request $request)

    {

        // Park the profile photo before validating. A browser cannot repopulate
        // a file input, so without this a validation error anywhere in the form
        // - including step 6 - forced the applicant back to step 1 to re-upload.
        $pendingPhoto = \App\Support\PendingUpload::keep(
            $request,
            'profile_photo_path',
            self::PENDING_PHOTO_KEY
        );

        // Same for the identity and guarantor documents - re-uploading five
        // images because of one typo is the worst part of this form.
        $pendingDocs = [];
        foreach (self::PENDING_DOC_FIELDS as $field => $sessionKey) {
            $pendingDocs[$field] = \App\Support\PendingUpload::keep($request, $field, $sessionKey);
        }

        // Validation

        $validated = $request->validate([

            // Validation for the applicant's personal details

            'name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|email|unique:users',

            'applicant_type' => 'required|string|in:live-in,live-out,semi-live-in',

            'phone' => 'required|string|size:11|unique:users',

            'gender' => 'required|string|in:male,female',

            'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],

            'state_of_origin' => 'required|string|max:255',

            'state_of_residence' => 'required|string|max:255',

            'lga_of_origin' => 'required|string|max:255',

            'country' => 'required|string|max:255',

            'country_of_residence' => 'required|string|max:255',

            'years_of_experience' => 'required|string',

            'profile_photo_path' => ($pendingPhoto ? 'nullable' : 'required') . '|image|mimes:jpg,png,jpeg|max:2048',

            'address' => 'required|string|max:2048',

            'landmark' => 'required|string|max:255',

            'nin' => 'required|string|unique:users|size:11',

            'id_card' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            'bio' => 'required|string',

            'specialties' => 'required|array',

            'password' => 'required|string|min:8|confirmed',

            'religion'       => 'required|string|max:100',

            'marital_status' => 'required|in:single,married,divorced,widowed',

            'qualification'  => 'required|string|max:100',


            // Guarantor 1 validation

            'guarantor1_first_name' => 'required|string|max:255',

            'guarantor1_last_name' => 'required|string|max:255',

            'guarantor1_email' => 'nullable|email|max:255',

            'guarantor1_phone' => 'required|string|digits:11', // Exactly 11 digits

            'guarantor1_nin' => 'required|string|digits:11',

            'guarantor1_relationship' => 'required|string|max:100',

            'guarantor1_occupation' => 'required|string|max:255',

            'guarantor1_address' => 'required|string|max:500',

            'guarantor1_passport_path' => ($pendingDocs['guarantor1_passport_path'] ?? null ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg|max:2048',

            'guarantor1_signature_path' => ($pendingDocs['guarantor1_signature_path'] ?? null ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg|max:2048',



            // Guarantor 2 validation

            'guarantor2_first_name' => 'nullable|string|max:255',

            'guarantor2_last_name' => 'nullable|string|max:255',

            'guarantor2_email' => 'nullable|email|max:255',

            'guarantor2_phone' => 'nullable|string|max:15',

            'guarantor2_nin' => 'nullable|string|digits:11',

            'guarantor2_relationship' => 'nullable|string|max:100',

            'guarantor2_occupation' => 'nullable|string|max:255',

            'guarantor2_address' => 'nullable|string|max:500',

            'guarantor2_passport_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'guarantor2_signature_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        DB::beginTransaction();

        try {

            // File uploads for user

            // The photo was parked before validation, so it is taken from there
            // rather than from the request - it may have been uploaded on an
            // earlier attempt that failed validation.
            $profilePhotoPath = null;

            $parked = \App\Support\PendingUpload::stored(self::PENDING_PHOTO_KEY);

            if ($parked) {
                $profilePhotoPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo($parked, PATHINFO_EXTENSION);

                $profilePhotoPath = \App\Support\PendingUpload::commit(
                    self::PENDING_PHOTO_KEY,
                    public_path('build/assets/image/profile_photo'),
                    $profilePhotoPath
                );
            }



            $idCardPath = null;

            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['id_card'])) {

                $idCardPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['id_card']), PATHINFO_EXTENSION);

                $idCardPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['id_card'], \App\Support\SecureDocument::storageDirectory('idcards'), $idCardPath);
            }



            // Create User

            $user = User::create([

                'name' => $validated['name'],

                'middle_name' => $validated['middle_name'] ?? null,

                'last_name' => $validated['last_name'] ?? null,

                'email' => $validated['email'],

                'account_type' => 'applicant',

                'applicant_type' => $validated['applicant_type'] ?? null,

                'phone' => $validated['phone'],

                'gender' => $validated['gender'] ?? null,

                'dob' => $validated['dob'],

                'state_of_origin' => $validated['state_of_origin'] ?? null,

                'state_of_residence' => $validated['state_of_residence'] ?? null,

                'lga_of_origin' => $validated['lga_of_origin'] ?? null,

                'country' => $validated['country'] ?? null,

                'country_of_residence' => $validated['country_of_residence'] ?? null,

                'years_of_experience' => $validated['years_of_experience'] ?? null,

                'profile_photo_path' => $profilePhotoPath,

                'address' => $validated['address'] ?? null,

                'landmark' => $validated['landmark'] ?? null,

                'nin' => $validated['nin'] ?? null,

                'id_card' => $idCardPath,

                'bio' => $validated['bio'] ?? null,

                'specialties' => $validated['specialties'] ?? null,

                'password' => bcrypt($validated['password']),

                'form_completed' => true,

                'religion'       => $validated['religion'] ?? null,

                'marital_status' => $validated['marital_status'] ?? null,

                'qualification'  => $validated['qualification'] ?? null,

                'guarantors_forms_uploaded' => true,

                'guarantors_forms_uploaded_2' => false,

                'terms_accepted' => true,

            ]);





            // File uploads for Guarantor 1

            $guarantor1PassportPath = null;

            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_passport_path'])) {

                $guarantor1PassportPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_passport_path']), PATHINFO_EXTENSION);

                $guarantor1PassportPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor1_passport_path'], \App\Support\SecureDocument::storageDirectory('passports'), $guarantor1PassportPath);
            }



            $guarantor1SignaturePath = null;

            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_signature_path'])) {

                $guarantor1SignaturePath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_signature_path']), PATHINFO_EXTENSION);

                $guarantor1SignaturePath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor1_signature_path'], \App\Support\SecureDocument::storageDirectory('signatures'), $guarantor1SignaturePath);
            }



            // Create Guarantor 1

            Guarantor::create([

                'applicant_id' => $user->id,

                // 'guarantor1_number' => 1,

                'guarantor1_first_name' => $validated['guarantor1_first_name'],

                'guarantor1_last_name' => $validated['guarantor1_last_name'],

                'guarantor1_email' => $validated['guarantor1_email'] ?? null,

                'guarantor1_phone' => $validated['guarantor1_phone'],

                'guarantor1_nin' => $validated['guarantor1_nin'],

                'guarantor1_relationship' => $validated['guarantor1_relationship'],

                'guarantor1_occupation' => $validated['guarantor1_occupation'],

                'guarantor1_address' => $validated['guarantor1_address'],

                'guarantor1_passport_path' => $guarantor1PassportPath,

                'guarantor1_signature_path' => $guarantor1SignaturePath,

            ]);



            

            // File uploads for Guarantor 2 (only if provided)
            if (!empty($request->input('guarantor2_first_name'))) {

                $guarantor2PassportPath = null;

                if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_passport_path'])) {

                    $guarantor2PassportPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_passport_path']), PATHINFO_EXTENSION);

                    $guarantor2PassportPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor2_passport_path'], \App\Support\SecureDocument::storageDirectory('passports'), $guarantor2PassportPath);
                }

                $guarantor2SignaturePath = null;

                if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_signature_path'])) {

                    $guarantor2SignaturePath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_signature_path']), PATHINFO_EXTENSION);

                    $guarantor2SignaturePath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor2_signature_path'], \App\Support\SecureDocument::storageDirectory('signatures'), $guarantor2SignaturePath);
                }

                Guarantor2::create([
                    'applicant_id' => $user->id,
                    'guarantor2_first_name' => $validated['guarantor2_first_name'],
                    'guarantor2_last_name' => $validated['guarantor2_last_name'],
                    'guarantor2_email' => $validated['guarantor2_email'] ?? null,
                    'guarantor2_phone' => $validated['guarantor2_phone'],
                    'guarantor2_relationship' => $validated['guarantor2_relationship'],
                    'guarantor2_occupation' => $validated['guarantor2_occupation'],
                    'guarantor2_address' => $validated['guarantor2_address'],
                    'guarantor2_passport_path' => $guarantor2PassportPath,
                    'guarantor2_signature_path' => $guarantor2SignaturePath,
                ]);

            } // end guarantor 2


            // Create Guarantor 2

            Guarantor2::create([

                'applicant_id' => $user->id,

                // 'guarantor2_number' => 2,

                'guarantor2_first_name' => $validated['guarantor2_first_name'],

                'guarantor2_last_name' => $validated['guarantor2_last_name'],

                'guarantor2_email' => $validated['guarantor2_email'] ?? null,

                'guarantor2_phone' => $validated['guarantor2_phone'],

                'guarantor2_nin' => $validated['guarantor2_nin'] ?? null,

                'guarantor2_relationship' => $validated['guarantor2_relationship'],

                'guarantor2_occupation' => $validated['guarantor2_occupation'],

                'guarantor2_address' => $validated['guarantor2_address'],

                'guarantor2_passport_path' => $guarantor2PassportPath,

                'guarantor2_signature_path' => $guarantor2SignaturePath,

            ]);


            DB::commit();

            return redirect()->back()->with('success', 'User and Guarantors created successfully!');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'There was an error submitting the form. Please try again.']);
        }
    }



    public function storeliveins(Request $request)

    {

        // Park uploads before validation so a mistake late in the form does
        // not force the applicant to re-select every file. See
        // App\Support\PendingUpload.
        $pendingPhoto = \App\Support\PendingUpload::keep($request, 'profile_photo_path', self::PENDING_PHOTO_KEY);

        $pendingDocs = [];
        foreach (self::PENDING_DOC_FIELDS as $field => $sessionKey) {
            $pendingDocs[$field] = \App\Support\PendingUpload::keep($request, $field, $sessionKey);
        }

        // Validation

        $validated = $request->validate([

            // Validation for the applicant's personal details

            'name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|email|unique:users',

            'applicant_type' => 'required|string|in:live-in,live-out,semi-live-in',

            'phone' => 'required|string|size:11|unique:users',

            'gender' => 'required|string|in:male,female',

            'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],

            'state_of_origin' => 'required|string|max:255',

            'state_of_residence' => 'required|string|max:255',

            'lga_of_origin' => 'required|string|max:255',

            'country' => 'required|string|max:255',

            'country_of_residence' => 'required|string|max:255',

            'years_of_experience' => 'required|string',

            'profile_photo_path' => ($pendingPhoto ? 'nullable' : 'required') . '|image|mimes:jpg,png,jpeg|max:2048',

            'address' => 'required|string|max:2048',

            'landmark' => 'required|string|max:255',

            'nin' => 'required|string|unique:users|size:11',

            'id_card' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            'bio' => 'required|string',

            'specialties' => 'required|array',

            'password' => 'required|string|min:8|confirmed',

            'religion'       => 'required|string|max:100',

            'marital_status' => 'required|in:single,married,divorced,widowed',

            'qualification'  => 'required|string|max:100',

            'guarantor1_first_name'    => 'required|string|max:255',
            'guarantor1_last_name'     => 'required|string|max:255',
            'guarantor1_email'         => 'required|email|max:255',
            'guarantor1_phone'         => 'required|string|digits:11',
            'guarantor1_nin'           => 'required|string|digits:11',
            'guarantor1_relationship'  => 'required|string|max:100',
            'guarantor1_occupation'    => 'required|string|max:255',
            'guarantor1_address'       => 'required|string|max:500',
            'guarantor1_passport_path' => ($pendingDocs['guarantor1_passport_path'] ?? null ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg|max:2048',
            'guarantor1_signature_path'=> 'required|image|mimes:jpeg,png,jpg|max:2048',

            'guarantor2_first_name'    => 'required|string|max:255',
            'guarantor2_last_name'     => 'required|string|max:255',
            'guarantor2_email'         => 'required|email|max:255',
            'guarantor2_phone'         => 'required|string|digits:11',
            'guarantor2_nin'           => 'required|string|digits:11',
            'guarantor2_relationship'  => 'required|string|max:100',
            'guarantor2_occupation'    => 'required|string|max:255',
            'guarantor2_address'       => 'required|string|max:500',
            'guarantor2_passport_path' => ($pendingDocs['guarantor2_passport_path'] ?? null ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg|max:2048',
            'guarantor2_signature_path'=> 'required|image|mimes:jpeg,png,jpg|max:2048',


        ]);

        DB::beginTransaction();

        try {

            // File uploads for user
            $profilePhotoPath = null;

            if (\App\Support\PendingUpload::stored(self::PENDING_PHOTO_KEY)) {

                $profilePhotoPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_PHOTO_KEY), PATHINFO_EXTENSION);

                $profilePhotoPath = \App\Support\PendingUpload::commit(self::PENDING_PHOTO_KEY, public_path('build/assets/image/profile_photo'), $profilePhotoPath);
            }

            $idCardPath = null;

            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['id_card'])) {

                $idCardPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['id_card']), PATHINFO_EXTENSION);

                $idCardPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['id_card'], \App\Support\SecureDocument::storageDirectory('idcards'), $idCardPath);
            }

            // Create User
            $user = User::create([

                'name' => $validated['name'],

                'middle_name' => $validated['middle_name'] ?? null,

                'last_name' => $validated['last_name'] ?? null,

                'email' => $validated['email'],

                'account_type' => 'applicant',

                'applicant_type' => $validated['applicant_type'] ?? null,

                'phone' => $validated['phone'],

                'gender' => $validated['gender'] ?? null,

                'dob' => $validated['dob'],

                'state_of_origin' => $validated['state_of_origin'] ?? null,

                'state_of_residence' => $validated['state_of_residence'] ?? null,

                'lga_of_origin' => $validated['lga_of_origin'] ?? null,

                'country' => $validated['country'] ?? null,

                'country_of_residence' => $validated['country_of_residence'] ?? null,

                'years_of_experience' => $validated['years_of_experience'] ?? null,

                'profile_photo_path' => $profilePhotoPath,

                'address' => $validated['address'] ?? null,

                'landmark' => $validated['landmark'] ?? null,

                'nin' => $validated['nin'] ?? null,

                'id_card' => $idCardPath,

                'bio' => $validated['bio'] ?? null,

                'specialties' => $validated['specialties'] ?? null,

                'password' => bcrypt($validated['password']),

                'religion'       => $validated['religion'] ?? null,

                'marital_status' => $validated['marital_status'] ?? null,

                'qualification'  => $validated['qualification'] ?? null,

                'form_completed' => true,

                'guarantors_forms_uploaded' => true,

                'guarantors_forms_uploaded_2' => true,

                'terms_accepted' => true,

            ]);


            // File uploads for Guarantor 1
            $guarantor1PassportPath = null;
            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_passport_path'])) {
                $guarantor1PassportPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_passport_path']), PATHINFO_EXTENSION);
                $guarantor1PassportPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor1_passport_path'], \App\Support\SecureDocument::storageDirectory('passports'), $guarantor1PassportPath);
            }

            $guarantor1SignaturePath = null;
            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_signature_path'])) {
                $guarantor1SignaturePath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor1_signature_path']), PATHINFO_EXTENSION);
                $guarantor1SignaturePath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor1_signature_path'], \App\Support\SecureDocument::storageDirectory('signatures'), $guarantor1SignaturePath);
            }

            // Create Guarantor 1
            Guarantor::create([
                'applicant_id'             => $user->id,
                'guarantor1_first_name'    => $validated['guarantor1_first_name'],
                'guarantor1_last_name'     => $validated['guarantor1_last_name'],
                'guarantor1_email'         => $validated['guarantor1_email'],
                'guarantor1_phone'         => $validated['guarantor1_phone'],
                'guarantor1_nin'           => $validated['guarantor1_nin'],
                'guarantor1_relationship'  => $validated['guarantor1_relationship'],
                'guarantor1_occupation'    => $validated['guarantor1_occupation'],
                'guarantor1_address'       => $validated['guarantor1_address'],
                'guarantor1_passport_path' => $guarantor1PassportPath,
                'guarantor1_signature_path'=> $guarantor1SignaturePath,
            ]);

            // File uploads for Guarantor 2
            $guarantor2PassportPath = null;
            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_passport_path'])) {
                $guarantor2PassportPath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_passport_path']), PATHINFO_EXTENSION);
                $guarantor2PassportPath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor2_passport_path'], \App\Support\SecureDocument::storageDirectory('passports'), $guarantor2PassportPath);
            }

            $guarantor2SignaturePath = null;
            if (\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_signature_path'])) {
                $guarantor2SignaturePath = \Illuminate\Support\Str::random(40) . '.' . pathinfo(\App\Support\PendingUpload::stored(self::PENDING_DOC_FIELDS['guarantor2_signature_path']), PATHINFO_EXTENSION);
                $guarantor2SignaturePath = \App\Support\PendingUpload::commitToDisk(self::PENDING_DOC_FIELDS['guarantor2_signature_path'], \App\Support\SecureDocument::storageDirectory('signatures'), $guarantor2SignaturePath);
            }

            // Create Guarantor 2
            Guarantor2::create([
                'applicant_id'             => $user->id,
                'guarantor2_first_name'    => $validated['guarantor2_first_name'],
                'guarantor2_last_name'     => $validated['guarantor2_last_name'],
                'guarantor2_email'         => $validated['guarantor2_email'],
                'guarantor2_phone'         => $validated['guarantor2_phone'],
                'guarantor2_nin'           => $validated['guarantor2_nin'],
                'guarantor2_relationship'  => $validated['guarantor2_relationship'],
                'guarantor2_occupation'    => $validated['guarantor2_occupation'],
                'guarantor2_address'       => $validated['guarantor2_address'],
                'guarantor2_passport_path' => $guarantor2PassportPath,
                'guarantor2_signature_path'=> $guarantor2SignaturePath,
            ]);

            DB::commit();

            // Notify all admins of new staff registration
            $admins = \App\Models\User::where('user_role', '>=', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NewStaffRegisteredNotification($user));
            }

            // To this:
            Auth::login($user);
            return redirect()->route('here')
                ->with('success', 'Account created! Please complete your profile to be activated as staff.');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'There was an error submitting the form. Please try again.']);
        }
    }



    /**
     * Is a unique field already taken?
     *
     * Backs the live duplicate check on the registration forms. This returned
     * a hardcoded `false` behind a "TEMP: disabled for testing" comment, so the
     * client-side checks always reported every value as available and the
     * applicant only discovered a clash after submitting the whole form.
     *
     * Rate limited at the route (throttle:10,1). It answers only "taken or
     * not" for a value the caller already holds, so it does not expose data
     * beyond confirming a duplicate - the same thing the form tells them on
     * submit.
     */
    public function checkAvailability(Request $request)
    {
        $type = (string) $request->get('type');
        $value = trim((string) $request->get('value'));

        if ($value === '' || !in_array($type, ['email', 'phone', 'nin'], true)) {
            return response()->json(['exists' => false]);
        }

        if ($type === 'email') {
            $query = User::where('email', strtolower($value));
        } else {
            $digits = preg_replace('/\D/', '', $value);

            if ($type === 'nin') {
                if (strlen($digits) !== 11) {
                    return response()->json(['exists' => false]);
                }

                $query = User::where('nin', $digits);
            } else {
                // Phone numbers are stored inconsistently: 26 rows hold 10
                // digits (no leading zero) alongside the usual 11, and some
                // carry a 234 country code. Compare every equivalent form of
                // the same number, or a duplicate slips through.
                if (strlen($digits) < 10) {
                    return response()->json(['exists' => false]);
                }

                $local = substr($digits, -10);

                $query = User::whereIn('phone', array_unique([
                    $digits,
                    $local,
                    '0' . $local,
                    '234' . $local,
                    '+234' . $local,
                ]));
            }
        }

        // When signed in, the user's own value is not a clash - this endpoint
        // is also used by profile edit forms.
        if (Auth::check()) {
            $query->where('id', '!=', Auth::id());
        }

        return response()->json(['exists' => $query->exists()]);
    }



    public function register(Request $request)

    {

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'phone' => 'required|string|max:15',

        ]);



        // Create or find the user

        $user = User::firstOrCreate(

            ['email' => $request->email],

            [

                'name' => $request->name,

                'phone' => $request->phone,

                'password' => Hash::make('12345678'),

                'account_type' => 'client',

            ]

        );



        // Log the user in

        Auth::login($user);



        return response()->json(['success' => true]);
    }


    public function verifyBankAccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string|digits:10',
            'bank_code' => 'required|string',
        ]);

       $response = \Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get('https://api.paystack.co/bank/resolve', [
            'account_number' => $request->account_number,
            'bank_code' => $request->bank_code,
        ]);

        $data = $response->json();

        if ($response->successful() && $data['status']) {
            return response()->json([
                'success' => true,
                'account_name' => $data['data']['account_name'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not verify account. Please check your account number and bank.',
        ], 422);
    }

    public function saveUserData(Request $request)

    {

        // Validate the incoming data

        $validatedData = $request->validate([

            'name' => 'required|string|max:255',

            // 'email' => 'required|email|max:255|unique:users,email',

            'email' => 'required|email|max:255',

            'phone' => 'required|string|max:15',

            'househelp_id' => 'required|exists:users,id', // Ensure the househelp ID is valid

            'message' => 'nullable|string', // Optional message

            'specialties' => 'nullable|array', // Optional specialties

        ]);



        DB::transaction(function () use ($validatedData, $request) {

            // Save the user data or retrieve an existing user by email

            $user = User::firstOrCreate(

                ['email' => $validatedData['email']],

                [

                    'name' => $validatedData['name'],

                    'phone' => $validatedData['phone'],

                    'password' => bcrypt('12345678.'), // Set a default password for guest

                    'account_type' => 'client', // Default to 'client' account type

                ]

            );



            // Auto-login the user

            Auth::login($user);



            // Create the service request

            $serviceRequest = new LiveInServiceRequest();

            $serviceRequest->client_id = $user->id;

            $serviceRequest->househelp_id = $validatedData['househelp_id'];

            $serviceRequest->message = $validatedData['message'] ?? null;

            $serviceRequest->specialties = $request->specialties ? json_encode($request->specialties) : null; // Convert specialties to JSON

            $serviceRequest->status = 'pending'; // Default status

            $serviceRequest->save();
        });



        // Redirect with a success message

        return redirect()->route('payments.paymentoption')->with('success', 'Your details and service request have been saved successfully. Proceed to make payment.');
    }



    // public function saveUserData(Request $request)

    // {

    //     dd($request->all()); // Dump all incoming request data

    // }

}
