<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\LiveInServiceRequest;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Rating;

use Illuminate\Support\Facades\Route;

use App\Models\Guarantor;

use App\Models\Guarantor2;

use App\Models\Confirmation;

use App\Models\Service;

use App\Models\Payment;

use App\Models\RequestClick;

use Carbon\Carbon;

use App\Models\PaymentPlan;

use Illuminate\Support\Str;



class ApplicantController extends Controller

{



    // Show speciality form

    public function applicantspeciality($user_id, $name)

    {

        // Get the logged-in user's information

        $user = Auth::user();

        $services = Service::all();



        // Retrieve the applicant's details

        $applicant = User::findOrFail($user_id);



        // Ensure that the logged-in user is accessing their own profile

        if ($user->id !== $applicant->id) {

            return redirect()->back()->with('error', 'Unauthorized access.');

        }



        // Check if the user's profile is incomplete

        if (

            !$user->middle_name ||

            !$user->last_name ||

            // !$user->applicant_type ||

            !$user->phone ||

            !$user->gender ||

            !$user->dob

        ) {

            return redirect()->route('here')->with('info', 'Please update your profile.');

        }



        // Pass the applicant details, user ID, name, and services to the view

        return view('applicant.speciality', compact('user', 'user_id', 'name', 'applicant', 'services'));

    }







    public function applicantterms()

    {

        $user = Auth::user();

        return view('applicant.terms', [

            'user' => $user, // Pass the user object to the view

            'availability' => $user->availability, // Assuming 'availability' is a boolean attribute

        ]);

    }



    public function applicantprivacy()

    {

        $user = Auth::user();

        return view('applicant.privacy', [

            'user' => $user, // Pass the user object to the view

            'availability' => $user->availability, // Assuming 'availability' is a boolean attribute

        ]);

    }



    public function applicantdashboard()

    {

        // Get the authenticated user

        $user = Auth::user();



        return view('applicantdashboard', [

            'user' => $user, // Pass the user object to the view

            'availability' => $user->availability, // Assuming 'availability' is a boolean attribute

        ]);

    }





    public function bookings()

    {

        // $bookings = user::where('user_id', auth()->id())->get(); // Fetch bookings for the logged-in user

        return view(

            'bookings'

            // , compact('bookings')

        ); // Return the bookings view with data

    }





    public function receivedLiveInServiceRequests()

    {

        $user = Auth::user();



        // Fetch all requests where the househelp_id is the currently authenticated user

        $requests = LiveInServiceRequest::where('househelp_id', auth()->id())

            ->with('client') // Assuming you've set up a relationship with the client

            ->orderBy('requested_at', 'desc')

            ->paginate(10);



        return view('applicantpositions', compact('user', 'requests'));

    }





    public function specialDuties()

    {

        $applicantId = auth()->id();



        // Fetch confirmation details where the current user is the assigned house help or assistant

        $assignments = Confirmation::with(['serviceRequest.client', 'admin', 'houseHelp', 'houseHelpAssist'])

            ->where(function ($query) use ($applicantId) {

                $query->where('house_help_id', $applicantId)

                    ->orWhere('house_help_assist_id', $applicantId);

            })

            ->get();



        return view('applicant.specialDuties', compact('assignments'));

    }











    public function applicantprofile($id)

    {

        // Get the logged-in user's information

        $user = Auth::user();



        // Get ratings where the logged-in user is the one being rated (applicant)

        $ratings = Rating::where('user_id', $user->id)->get();



        // Calculate average rating

        $averageRating = $ratings->avg('rating');

        $totalCount = $ratings->count();

        $applicant = User::findOrFail($id);



        // Retrieve guarantor data from separate models

        $guarantor1 = Guarantor::where('applicant_id', $id)->first();

        $guarantor2 = Guarantor2::where('applicant_id', $id)->first();



        // Pass 'applicant' instead of 'user'

        return view('applicantprofile', compact(

            'user',

            'ratings',

            'averageRating',

            'totalCount',

            'applicant',

            'guarantor1',

            'guarantor2'

        ));

    }







    // Update the applicant's profile

    public function update(Request $request)

    {

        $user = Auth::user();



        $rules = [

            // NOTE: the form field is named `profile_photo_path` (see
            // resources/views/partials/_profileupdate.blade.php). A rule keyed
            // `profile_photo` never fired, leaving the upload unvalidated.
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'nullable|string|max:255',

            'phone' => 'required|string|digits:11|unique:users,phone,' . $user->id,

            'address' => 'required|string|max:255',

            'state_of_residence' => 'required|string',

            'landmark' => 'required|string',

            'country' => 'required|string',

            'country_of_residence' => 'required|string',

            'gender' => 'required|string',

            'nin' => 'required|string|size:11|unique:users,nin,' . $user->id,

        ];



        /**

         * Applicant-only validation

         */

        if ($user->account_type !== 'client') {

            $rules = array_merge($rules, [

                'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],

                'state_of_origin' => 'required|string',

                'lga_of_origin' => 'required|string|max:255',

                'years_of_experience' => 'required|string',

                'id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            ]);

        }



        $validated = $request->validate($rules);



        /**

         * File uploads

         */

        if ($request->hasFile('profile_photo_path')) {

            $file = $request->file('profile_photo_path');

            // Extension is derived from the detected MIME type, never from the
            // client-supplied filename, and the basename is random so uploads
            // cannot be enumerated or guessed.
            $filename = Str::random(40) . '.' . $file->extension();

            $file->move(public_path('build/assets/image/profile_photo'), $filename);

            $user->profile_photo_path = $filename;

        }



        if ($request->hasFile('id_card')) {

            $file = $request->file('id_card');

            $filename = Str::random(40) . '.' . $file->extension();

            $file->storeAs(\App\Support\SecureDocument::storageDirectory('idcards'), $filename, 'local');

            $user->id_card = $filename;

        }



        /**

         * Update shared fields

         */

        $user->fill([

            'middle_name' => $request->middle_name,

            'last_name' => $request->last_name,

            'phone' => $request->phone,

            'address' => $request->address,

            'state_of_residence' => $request->state_of_residence,

            'landmark' => $request->landmark,

            'country' => $request->country,

            'country_of_residence' => $request->country_of_residence,

            'gender' => $request->gender,

            'nin' => $request->nin,

        ]);



        /**

         * Applicant-only fields

         */

        if ($user->account_type !== 'client') {

            $user->fill([

                'dob' => $request->dob,

                'state_of_origin' => $request->state_of_origin,

                'lga_of_origin' => $request->lga_of_origin,

                'years_of_experience' => $request->years_of_experience,

            ]);

        }



        $user->form_completed = true;

        $user->save();



        return redirect()

            ->route(

                $user->account_type === 'client' ? 'dashboard' : 'applicantspeciality',

                ['user_id' => $user->id, 'name' => $user->name]

            )

            ->with('success', 'Profile updated successfully.');

    }



    // public function update(Request $request)

    // {

    //     $user = Auth::user();



    //     /**

    //      * Base rules (ALL users — NIN included)

    //      */

    //     $rules = [

    //         'profile_photo_path'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

    //         'middle_name'         => 'nullable|string|max:255',

    //         'last_name'           => 'nullable|string|max:255',

    //         'phone'               => 'required|string|digits:11|unique:users,phone,' . $user->id,

    //         'address'             => 'required|string|max:255',

    //         'state_of_residence'  => 'required|string',

    //         'landmark'            => 'required|string',

    //         'country'             => 'required|string',

    //         'country_of_residence' => 'required|string',

    //         'gender'              => 'required|string',



    //         // 🔥 REQUIRED FOR EVERYONE

    //         'nin' => 'required|string|size:11|unique:users,nin,' . $user->id,

    //     ];



    //     /**

    //      * Applicant-only rules

    //      */

    //     if ($user->account_type !== 'client') {

    //         $rules = array_merge($rules, [

    //             'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],

    //             'state_of_origin' => 'required|string',

    //             'lga_of_origin'   => 'required|string|max:255',

    //             'years_of_experience' => 'required|string',

    //             'id_card' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

    //         ]);

    //     }



    //     $validated = $request->validate($rules);



    //     /**

    //      * NIN verification

    //      * - Runs for ALL users

    //      * - Only runs if NIN changed

    //      */

    //     if ($request->nin !== $user->nin) {



    //         $result = $this->verifyNin($request->nin);



    //         if (!isset($result['status']) || $result['status'] !== 'verified') {

    //             return back()->withErrors([

    //                 'nin' => 'NIN could not be verified with NIMC records.'

    //             ]);

    //         }



    //         // Optional strict name match

    //         if (

    //             strtolower(trim($result['first_name'])) !== strtolower(trim($user->name)) ||

    //             strtolower(trim($result['last_name'])) !== strtolower(trim($user->last_name))

    //         ) {

    //             return back()->withErrors([

    //                 'nin' => 'NIN does not match the provided name.'

    //             ]);

    //         }



    //         $user->nin_verified = true;

    //         $user->nin_verified_at = now();

    //     }



    //     /**

    //      * File uploads

    //      */

    //     if ($request->hasFile('profile_photo_path')) {

    //         $file = $request->file('profile_photo_path');

    //         $filename = time() . '.' . $file->getClientOriginalExtension();

    //         $file->move(public_path('build/assets/image/profile_photo'), $filename);

    //         $user->profile_photo_path = $filename;

    //     }



    //     if ($request->hasFile('id_card') && $user->account_type !== 'client') {

    //         $file = $request->file('id_card');

    //         $filename = time() . '.' . $file->getClientOriginalExtension();

    //         $file->storeAs(\App\Support\SecureDocument::storageDirectory('idcards'), $filename, 'local');

    //         $user->id_card = $filename;

    //     }



    //     /**

    //      * Shared fields

    //      */

    //     $user->fill([

    //         'middle_name'         => $request->middle_name,

    //         'last_name'           => $request->last_name,

    //         'phone'               => $request->phone,

    //         'address'             => $request->address,

    //         'state_of_residence'  => $request->state_of_residence,

    //         'landmark'            => $request->landmark,

    //         'country'             => $request->country,

    //         'country_of_residence' => $request->country_of_residence,

    //         'gender'              => $request->gender,

    //         'nin'                 => $request->nin,

    //     ]);



    //     /**

    //      * Applicant-only fields

    //      */

    //     if ($user->account_type !== 'client') {

    //         $user->fill([

    //             'dob' => $request->dob,

    //             'state_of_origin' => $request->state_of_origin,

    //             'lga_of_origin' => $request->lga_of_origin,

    //             'years_of_experience' => $request->years_of_experience,

    //         ]);

    //     }



    //     $user->form_completed = true;

    //     $user->save();



    //     return redirect()

    //         ->route(

    //             $user->account_type === 'client' ? 'dashboard' : 'applicantspeciality',

    //             ['user_id' => $user->id, 'name' => $user->name]

    //         )

    //         ->with('success', 'Profile updated successfully.');

    // }







    public function accountupdate(Request $request)

    {

        $request->validate([

            'account_name' => 'required|string|max:255',

            'bank' => 'required|string|max:255',

            'account_number' => 'required|digits_between:10,15',

        ]);



        $user = Auth::user();

        $user->account_name = $request->account_name;

        $user->bank = $request->bank;

        $user->account_number = $request->account_number;

        $user->save();



        return back()->with('success', 'Account details updated successfully.');

    }





    public function showapplicant(Request $request, $id)

    {

        $househelp = User::findOrFail($id);

        $user = Auth::user();

        $clientId = $user ? $user->id : null;



        // Initialize variables

        $hasApprovedPayment = false;

        $pendingPayment = false;

        $clickCount = 0;

        $searchCount = 0;

        $remainingSearches = 0;

        $isSubscriptionExpired = true;

        $payment = null;



        if ($clientId) {

            // Fetch latest approved payment

            $payment = Payment::where('user_id', $clientId)

                ->where('status', 'approved')

                ->where('plan_type', 'househelp')

                ->latest()

                ->first();



            if ($payment) {

                $hasApprovedPayment = true;



                // Fetch number of searches made under this payment

                $searchCount = RequestClick::where('user_id', $clientId)

                    ->where('payment_id', $payment->id)

                    ->count();



                // Subscription limits

                $subscriptionLimit = $payment->num_searches ?? 0;

                $remainingSearches = max($subscriptionLimit - $searchCount, 0);

                $isSubscriptionExpired = now()->greaterThan($payment->expiry_date) || $remainingSearches <= 0;



                // Fetch number of service clicks (could be same as search count)

                $clickCount = $searchCount; // Assuming same as search count unless tracked separately

            }



            // Check if there's any pending payment

            $pendingPayment = Payment::where('user_id', $clientId)

                ->where('status', 'pending')

                ->exists();



            // Optimize active & accepted request status

            $requests = LiveInServiceRequest::where('client_id', $clientId)

                ->where('househelp_id', $househelp->id)

                ->pluck('status');



            $househelp->activeRequest = $requests->contains('pending');

            $househelp->acceptedRequest = $requests->contains('accepted');

        } else {

            // If not authenticated

            $househelp->activeRequest = false;

            $househelp->acceptedRequest = false;

        }



        // Available specialties

        $specialties = Service::pluck('service_name');



        return view('applicant.show', compact(

            'specialties',

            'househelp',

            'hasApprovedPayment',

            'pendingPayment',

            'clickCount',

            'remainingSearches',

            'isSubscriptionExpired',

            'searchCount',

            'payment'

        ));

    }

}

