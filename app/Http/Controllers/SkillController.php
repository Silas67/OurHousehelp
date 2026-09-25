<?php



namespace App\Http\Controllers;



use App\Models\RequestClick;

use App\Models\Skill;

use App\Models\User;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\SearchLog;

use App\Models\Rating;

use App\Models\LiveInServiceRequest;

use App\Models\Service;

use App\Models\Payment;

use App\Models\ServiceRequest;

use App\Models\BonusUsage;



class SkillController extends Controller

{

    public function applyasaskillworker()

    {

        $skills = Skill::all();

        return view('skilledworkers.skilledworkers', compact(

            'skills'

        ));

    }



    public function storeSkilledWorkers(Request $request)

    {

        // Validation

        $validated = $request->validate([

            // Validation for the applicant's personal details

            'name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => 'required|email|unique:users',

            'applicant_type' => 'required|string|in:skill',

            'phone' => 'required|string|size:11|unique:users',

            'gender' => 'required|string|in:male,female',

            'dob' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],

            'state_of_residence' => 'required|string|max:255',

            'country_of_residence' => 'required|string|max:255',

            'profile_photo_path' => 'required|image|mimes:jpg,png,jpeg|max:10240',

            'address' => 'required|string|max:2048',

            'landmark' => 'required|string|max:255',

            'specialties' => 'required|string',

            'lga_of_origin' => 'required|string',

            'password' => 'required|string|min:8|confirmed',

        ]);





        DB::beginTransaction();

        try {

            // File uploads for user

            $profilePhotoPath = null;

            if ($request->hasFile('profile_photo_path')) {

                $profilePhotoPath = time() . '_profile.' . $request->file('profile_photo_path')->getClientOriginalExtension();

                $request->file('profile_photo_path')->move(public_path('build/assets/image/profile_photo'), $profilePhotoPath);

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

                'state_of_residence' => $validated['state_of_residence'] ?? null,

                'country_of_residence' => $validated['country_of_residence'] ?? null,

                'lga_of_origin' => $validated['lga_of_origin'] ?? null,

                'years_of_experience' => $validated['years_of_experience'] ?? null,

                'profile_photo_path' => $profilePhotoPath,

                'address' => $validated['address'] ?? null,

                'landmark' => $validated['landmark'] ?? null,

                'bio' => $validated['bio'] ?? null,

                'specialties' => $validated['specialties'] ?? null,

                'password' => bcrypt($validated['password']),

                'form_completed' => true,

                'terms_accepted' => true,

            ]);





            DB::commit();

            return redirect()->back()->with('success', 'Your account was created successfully!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withErrors(['error' => 'There was an error submitting the form. Please try again.']);

        }

    }





    public function skillstore(Request $request)

    {

        // Validate the input

        $validated = $request->validate([

            'skill_name'          => 'required|string|max:255',

            'about_skill'         => 'required|string',

            'icon_class'            => 'nullable|string|max:255',

        ]);





        // Create the service and retrieve its ID

        $service = Skill::create([

            'skill_name'        => $validated['skill_name'],

            'about_skill'       => $validated['about_skill'],

            'icon_class'        => $validated['icon_class'] ?? null,

        ]);



        return redirect()->back()->with('success', 'Skill created successfully.');

    }



    public function createskill()

    {

        return view('admin.newskill');

    }



    // List all skills

    public function allskill()

    {

        $skills = Skill::latest()->paginate(10);

        return view('admin.allskill', compact('skills'));

    }



    // Show edit form

    public function editskill(Skill $skill)

    {

        return view('admin.editskill', compact('skill'));

    }



    // Update skill

    public function updateskill(Request $request, Skill $skill)

    {

        $validated = $request->validate([

            'skill_name' => 'required|string|max:255',

            'about_skill' => 'required|string',

            'icon_class' => 'nullable|string|max:255',

        ]);



        $skill->update($validated);



        return redirect()->route('admin.allskill')->with('success', 'Skill updated successfully.');

    }



    // Delete skill

    public function destroyskill(Skill $skill)

    {

        $skill->delete();

        return redirect()->route('admin.allskill')->with('success', 'Skill deleted successfully.');

    }





    // Do not delete this. Use this when you want the payment for house help to be different from that of skilled workers

    // public function ourSkilledWorkerServices(Request $request)

    // {

    //     // Authentication and payment checks

    //     $user = Auth::user();

    //     $clientId = $user ? $user->id : null;



    //     // Get all skills for the dropdown

    //     $skills = Skill::orderBy('skill_name')->get();



    //     $hasApprovedPayment = Payment::where('user_id', $clientId)

    //         ->where('status', 'approved')

    //         ->exists();



    //     $pendingPayment = Payment::where('user_id', $clientId)

    //         ->where('status', 'pending')

    //         ->exists();



    //     // Search count logic

    //     $searchCount = 0;

    //     if ($clientId) {

    //         $latestPayment = Payment::where('user_id', $clientId)

    //             ->where('status', 'approved')

    //             ->latest('created_at')

    //             ->value('id');



    //         if ($latestPayment) {

    //             $searchCount = RequestClick::where('user_id', $clientId)

    //                 ->where('payment_id', $latestPayment)

    //                 ->count();

    //         }

    //     }



    //     // Subscription logic

    //     $payment = Payment::where('user_id', $clientId)

    //         ->where('status', 'approved')

    //         ->latest()

    //         ->first();



    //     $subscriptionLimit = $payment ? $payment->num_searches : 0;

    //     $remainingSearches = max($subscriptionLimit - $searchCount, 0);

    //     $isSubscriptionExpired = !$payment || ($remainingSearches <= 0);



    //     // Base query

    //     $query = User::where('account_type', 'applicant')

    //         ->where('availability', 1)

    //         ->where('applicant_type', 'skill');



    //     // Case-insensitive search filters

    //     if ($request->filled('gender')) {

    //         $query->where('gender', 'like', $request->input('gender'));

    //     }



    //     if ($request->filled('applicant_type')) {

    //         $query->where('applicant_type', 'like', $request->input('applicant_type'));

    //     }



    //     if ($request->filled('state_of_origin')) {

    //         $query->where('state_of_origin', 'like', '%' . $request->input('state_of_origin') . '%');

    //     }



    //     if ($request->filled('country_of_residence')) {

    //         $query->where('country_of_residence', 'like', '%' . $request->input('country_of_residence') . '%');

    //     }



    //     if ($request->filled('landmark')) {

    //         $query->where('landmark', 'like', '%' . $request->input('landmark') . '%');

    //     }



    //     if ($request->filled('address')) {

    //         $query->where('address', 'like', '%' . $request->input('address') . '%');

    //     }



    //     if ($request->filled('lga_of_origin')) {

    //         $query->where('lga_of_origin', 'like', '%' . $request->input('lga_of_origin') . '%');

    //     }



    //     if ($request->filled('state_of_residence')) {

    //         $query->where('state_of_residence', 'like', '%' . $request->input('state_of_residence') . '%');

    //     }



    //     // if ($request->filled('specialties')) {

    //     //     $skillId = $request->input('specialties');

    //     //     $query->whereHas('skills', function ($q) use ($skillId) {

    //     //         $q->where('skills.id', $skillId);

    //     //     });

    //     // }





    //     if ($request->filled('specialties')) {

    //         $query->where('specialties', 'like', '%' . $request->input('specialties') . '%');

    //     }



    //     // Randomize and paginate results

    //     $househelps = $query->inRandomOrder()->paginate(5);



    //     // Log search if authenticated

    //     if ($clientId) {

    //         SearchLog::create([

    //             'user_id' => $clientId,

    //             'search_parameters' => json_encode($request->all()),

    //         ]);

    //     }



    //     // Process ratings

    //     $ratings = collect();

    //     $clientRatings = collect();



    //     if ($clientId) {

    //         $househelpIds = $househelps->pluck('id');

    //         $ratings = Rating::whereIn('user_id', $househelpIds)->get()->groupBy('user_id');

    //         $clientRatings = Rating::whereIn('user_id', $househelpIds)

    //             ->where('rated_by', $clientId)

    //             ->get()->keyBy('user_id');

    //     }



    //     foreach ($househelps as $househelp) {

    //         $userRatings = $ratings->get($househelp->id, collect());

    //         $househelp->averageRating = $userRatings->avg('rating') ?? 0;

    //         $househelp->totalCount = $userRatings->count();

    //         $househelp->totalFormatted = $househelp->totalCount >= 1000

    //             ? number_format($househelp->totalCount / 1000, 1) . 'k'

    //             : $househelp->totalCount;



    //         if ($clientId) {

    //             $househelp->clientRating = $clientRatings->get($househelp->id)->rating ?? null;

    //             $househelp->activeRequest = LiveInServiceRequest::where('client_id', $clientId)

    //                 ->where('househelp_id', $househelp->id)

    //                 ->where('status', 'pending')

    //                 ->exists();

    //             $househelp->acceptedRequest = LiveInServiceRequest::where('client_id', $clientId)

    //                 ->where('househelp_id', $househelp->id)

    //                 ->where('status', 'accepted')

    //                 ->exists();

    //         } else {

    //             $househelp->clientRating = null;

    //             $househelp->activeRequest = false;

    //             $househelp->acceptedRequest = false;

    //         }

    //     }



    //     // Data for view

    //     $states = [

    //         'Abia',

    //         'Adamawa',

    //         'Akwa Ibom',

    //         'Anambra',

    //         'Bauchi',

    //         'Bayelsa',

    //         'Benue',

    //         'Borno',

    //         'Cross River',

    //         'Delta',

    //         'Ebonyi',

    //         'Edo',

    //         'Ekiti',

    //         'Enugu',

    //         'FCT',

    //         'Gombe',

    //         'Imo',

    //         'Jigawa',

    //         'Kaduna',

    //         'Kano',

    //         'Katsina',

    //         'Kebbi',

    //         'Kogi',

    //         'Kwara',

    //         'Lagos',

    //         'Nasarawa',

    //         'Niger',

    //         'Ogun',

    //         'Ondo',

    //         'Osun',

    //         'Oyo',

    //         'Plateau',

    //         'Rivers',

    //         'Sokoto',

    //         'Taraba',

    //         'Yobe',

    //         'Zamfara'

    //     ];



    //     $specialties = Service::pluck('service_name');



    //     return view('ourSkilledWorkerServices', compact(

    //         'househelps',

    //         'states',

    //         'specialties',

    //         'hasApprovedPayment',

    //         'searchCount',

    //         'pendingPayment',

    //         'remainingSearches',

    //         'isSubscriptionExpired',

    //         'payment',

    //         'subscriptionLimit',

    //         'skills'

    //     ));

    // }



    // also go to _paymentoptionskilledworkers.blade.php and active 

    // {{-- <a class="mt-2 btn btn-primary"

    //     href="{{ route('payments.accountdetailskill', ['plan_id' => $plan->id]) }}">

    //     Select {{ $plan->plan_name }}

    // </a> --}}





    // Use this when you want the payment for house help to be the same with skilled workers

    public function ourSkilledWorkerServices(Request $request)

    {

        // Authentication and payment checks

        $user = Auth::user();

        $clientId = $user ? $user->id : null;



        // Get all skills for the dropdown

        $skills = Skill::orderBy('skill_name')->get();



        $hasApprovedPayment = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->exists();



        $pendingPayment = Payment::where('user_id', $clientId)

            ->where('status', 'pending')

            ->exists();



        // Search count logic

        $searchCount = 0;

        if ($clientId) {

            $latestPayment = Payment::where('user_id', $clientId)

                ->where('status', 'approved')

                ->latest('created_at')

                ->value('id');



            if ($latestPayment) {

                $searchCount = RequestClick::where('user_id', $clientId)

                    ->where('payment_id', $latestPayment)

                    ->count();

            }

        }



        // Subscription logic

        $payment = Payment::where('user_id', $clientId)

            ->where('status', 'approved')

            ->latest()

            ->first();



        $subscriptionLimit = $payment ? $payment->num_searches : 0;

        $remainingSearches = max($subscriptionLimit - $searchCount, 0);

        $isSubscriptionExpired = !$payment || ($remainingSearches <= 0);



        // Base query

        $query = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('availability', 1)

            ->where('applicant_type', 'skill');



        // Case-insensitive search filters

        if ($request->filled('gender')) {

            $query->where('gender', 'like', $request->input('gender'));

        }



        if ($request->filled('applicant_type')) {

            $query->where('applicant_type', 'like', $request->input('applicant_type'));

        }



        if ($request->filled('state_of_origin')) {

            $query->where('state_of_origin', 'like', '%' . $request->input('state_of_origin') . '%');

        }



        if ($request->filled('country_of_residence')) {

            $query->where('country_of_residence', 'like', '%' . $request->input('country_of_residence') . '%');

        }



        if ($request->filled('landmark')) {

            $query->where('landmark', 'like', '%' . $request->input('landmark') . '%');

        }



        if ($request->filled('address')) {

            $query->where('address', 'like', '%' . $request->input('address') . '%');

        }



        if ($request->filled('lga_of_origin')) {

            $query->where('lga_of_origin', 'like', '%' . $request->input('lga_of_origin') . '%');

        }



        if ($request->filled('state_of_residence')) {

            $query->where('state_of_residence', 'like', '%' . $request->input('state_of_residence') . '%');

        }



        // if ($request->filled('specialties')) {

        //     $skillId = $request->input('specialties');

        //     $query->whereHas('skills', function ($q) use ($skillId) {

        //         $q->where('skills.id', $skillId);

        //     });

        // }





        if ($request->filled('specialties')) {

            $query->where('specialties', 'like', '%' . $request->input('specialties') . '%');

        }



        // Randomize and paginate results

        $househelps = $query->inRandomOrder()->paginate(5);



        // Log search if authenticated

        if ($clientId) {

            SearchLog::create([

                'user_id' => $clientId,

                'search_parameters' => json_encode($request->all()),

            ]);

        }



        // Process ratings

        $ratings = collect();

        $clientRatings = collect();



        if ($clientId) {

            $househelpIds = $househelps->pluck('id');

            $ratings = Rating::whereIn('user_id', $househelpIds)->get()->groupBy('user_id');

            $clientRatings = Rating::whereIn('user_id', $househelpIds)

                ->where('rated_by', $clientId)

                ->get()->keyBy('user_id');

        }



        foreach ($househelps as $househelp) {

            $userRatings = $ratings->get($househelp->id, collect());

            $househelp->averageRating = $userRatings->avg('rating') ?? 0;

            $househelp->totalCount = $userRatings->count();

            $househelp->totalFormatted = $househelp->totalCount >= 1000

                ? number_format($househelp->totalCount / 1000, 1) . 'k'

                : $househelp->totalCount;



            if ($clientId) {

                $househelp->clientRating = $clientRatings->get($househelp->id)->rating ?? null;

                $househelp->activeRequest = LiveInServiceRequest::where('client_id', $clientId)

                    ->where('househelp_id', $househelp->id)

                    ->where('status', 'pending')

                    ->exists();

                $househelp->acceptedRequest = LiveInServiceRequest::where('client_id', $clientId)

                    ->where('househelp_id', $househelp->id)

                    ->where('status', 'accepted')

                    ->exists();

            } else {

                $househelp->clientRating = null;

                $househelp->activeRequest = false;

                $househelp->acceptedRequest = false;

            }

        }



        // Data for view

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



        return view('ourSkilledWorkerServices', compact(

            'househelps',

            'states',

            'specialties',

            'hasApprovedPayment',

            'searchCount',

            'pendingPayment',

            'remainingSearches',

            'isSubscriptionExpired',

            'payment',

            'subscriptionLimit',

            'skills'

        ));

    }



    public function autocomplete(Request $request)

    {

        $term = strtolower($request->get('term'));



        $skills = Service::whereRaw('LOWER(service_name) like ?', ['%' . $term . '%'])

            ->limit(10)

            ->get();



        $results = [];

        foreach ($skills as $skill) {

            $results[] = [

                'id' => $skill->id,

                'label' => $skill->service_name,

                'value' => $skill->service_name

            ];

        }



        return response()->json($results);

    }

}

