<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\LiveInService;

use App\Models\Package;

use App\Models\ServiceRequest;

use App\Models\Apartments;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Models\Payment;

use App\Models\Service;

use App\Models\ServiceCharge;

use App\Models\Skill;

use App\Models\BookingPayment;

use App\Models\Location;

use App\Models\ManagementPlan;

use App\Models\ReplacementRequest;

use App\Models\StaffShortlist;

use App\Notifications\StaffDetailsReleasedNotification;

use Carbon\Carbon;



class AdminController extends Controller

{



    // Display the admin registration form

    public function adminregister()

    {

        return view('admin.adminregister');

    }





    public function storeAdmin(Request $request)

    {

        // Validate input

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|string|email|max:255|unique:users',

            'password' => 'required|string|min:8|confirmed',

            'account_type' => 'required|string|in:admin',

            'super_password' => 'nullable|string',

        ]);



        // Check if super admin password is correct

        $superAdminPin = 'Admin1234@.Regis';

        $userRole = ($request->super_password === $superAdminPin) ? 2 : 0; // 2 = Super Admin, 0 = User



        // Create a new admin user

        $admin = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => bcrypt($request->password),

            'user_role' => $userRole,

            'account_type' => $request->account_type, // Include account_type

        ]);



        // Log the new admin in and redirect to admin dashboard or relevant page

        Auth::login($admin);



        return redirect()->route('here')->with('success', 'Admin registered successfully!');

    }







    public function admindashboard()

    {

        // Fetch all packages and paginate orders (5 per page) in descending order

        // $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(5);

        $orders = ServiceRequest::orderBy('created_at', 'desc')

            ->whereIn('status', ['active', 'confirmed', 'pending'])

            // ->where('status', 'pending')

            ->paginate(5);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $totalServiceRequests = ServiceRequest::count();



        // Count requests by status

        $pendingCount = ServiceRequest::where('status', 'pending')->count();

        $activeCount = ServiceRequest::where('status', 'active')->count();

        $confirmedCount = ServiceRequest::where('status', 'confirmed')->count();

        $cancelledCount = ServiceRequest::where('status', 'cancelled')->count();

        $completedCount = ServiceRequest::where('status', 'completed')->count();



        return view('admin.admindashboard', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'totalServiceRequests',

            'pendingCount',

            'activeCount',

            'confirmedCount',

            'cancelledCount',

            'completedCount'

        ));

    }







    public function pendingOrder()

    {

        // Fetch all packages and paginate orders (10 per page) in descending order

        $orders = ServiceRequest::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $apartments = Apartments::all();



        // Count the total number of service requests

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.pendingOrder', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'totalServiceRequests' // Pass the count to the view

        ));

    }



    // public function deleteOrder($id)

    // {

    //     $order = ServiceRequest::findOrFail($id);

    //     $order->delete();



    //     return redirect()->route('pendingOrder')->with('success', 'Order deleted successfully.');

    // }



    public function deleteOrder($id)

    {

        try {

            DB::transaction(function () use ($id) {

                $order = ServiceRequest::findOrFail($id);

                $order->delete();

            });



            return redirect()->route('pendingOrder')->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {

            return redirect()->route('pendingOrder')->with('error', 'Failed to delete order: ' . $e->getMessage());

        }

    }





    public function archieveOrder($id)

    {

        $order = ServiceRequest::findOrFail($id);

        $order->status = 'delete';

        $order->save();



        return redirect()->back()->with('success', 'User archieved successfully');

    }





    public function confirmedOrder()

    {

        // Fetch all packages and paginate orders (10 per page) in descending order

        $orders = ServiceRequest::where('status', 'confirmed')->orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $apartments = Apartments::all();



        // Count the total number of service requests

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.confirmedOrder', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'totalServiceRequests' // Pass the count to the view

        ));

    }





    public function activeOrder()

    {

        // Fetch all packages and paginate orders (10 per page) in descending order

        $orders = ServiceRequest::where('status', 'active')->orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $apartments = Apartments::all();



        // Count the total number of service requests

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.activeOrder', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'totalServiceRequests' // Pass the count to the view

        ));

    }



    public function allOrders()

    {

        // Fetch all orders without filtering by status, ordered by creation date in descending order

        $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $apartments = Apartments::all();



        // Count the total number of service requests

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.allOrders', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'totalServiceRequests' // Pass the count to the view

        ));

    }





    // public function orderAdminDetail($id)

    // {

    //     // Fetch the specific order by id

    //     $order = ServiceRequest::findOrFail($id);



    //     $packages = Package::all(); // Fetch all packages from the database

    //     $apartmentdetails = Apartments::all(); // Fetch all apartment details from the database



    //     return view('admin.orderAdminDetail', compact('order', 'packages', 'apartmentdetails'));

    // }



    public function orderAdminDetail($id)

    {

        // Fetch the specific order by id

        $order = ServiceRequest::findOrFail($id);



        $packages = Package::all(); // Fetch all packages from the database

        $apartmentdetails = Apartments::all(); // Fetch all apartment details from the database

        $serviceCharge = ServiceCharge::first(); // Fetch the service charge



        return view('admin.orderAdminDetail', compact('order', 'packages', 'apartmentdetails', 'serviceCharge'));

    }





    public function newregistration()

    {

        // Fetch all packages and paginate orders (10 per page) in descending order

        $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all(); // Fetch all apartments from the database

        $apartments = Apartments::all();



        // Count the total number of service requests

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.newregistration', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'totalServiceRequests' // Pass the count to the view

        ));

    }





    // public function createHouseHelp(Request $request)

    // {

    //     $request->validate([

    //         'name' => 'required|string|max:255',

    //         'middle_name' => 'nullable|string|max:255',

    //         'last_name' => 'required|string|max:255',

    //         'email' => 'required|string|email|max:255|unique:users',

    //         'phone' => 'nullable|string|max:15',

    //         'state_of_origin' => 'nullable|string|max:255',

    //         'password' => 'required|string|min:8|confirmed',

    //         'profile_photo_path' => 'required|image|mimes:jpg,png,jpeg|max:2048',

    //         'gender' => 'required|string|in:male,female',

    //         'account_type' => 'required|string|in:client,applicant,skilled,admin',

    //         'applicant_type' => 'required|string|in:live-in,live-out,both',

    //     ]);





    //     $profilePhotoPath = null;

    //     if ($request->hasFile('profile_photo_path')) {

    //         $profilePhotoPath = time() . '_profile.' . $request->file('profile_photo_path')->getClientOriginalExtension();

    //         $request->file('profile_photo_path')->move(public_path('build/assets/image/profile_photo'), $profilePhotoPath);

    //     }



    //     $user = User::create([

    //         'name' => $request->name,

    //         'middle_name' => $request->middle_name,

    //         'last_name' => $request->last_name,

    //         'email' => $request->email,

    //         'phone' => $request->phone,

    //         'state_of_origin' => $request->state_of_origin,

    //         'password' => Hash::make($request->password),

    //         'user_role' => 0,  // Default user role

    //         'profile_photo_path' => $profilePhotoPath,

    //         'applicant_type' => $request->applicant_type,

    //         'account_type' => $request->account_type,

    //         'gender' => $validated['gender'] ?? null,

    //     ]);



    //     return redirect()->back()->with('success', 'House Help account created successfully.');

    // }



    public function createHouseHelp(Request $request)

    {

        $validated = $request->validate([

            // Personal Information

            'name' => 'required|string|max:255|regex:/^[A-Za-z]+$/',

            'middle_name' => 'nullable|string|max:255|regex:/^[A-Za-z]+$/',

            'last_name' => 'nullable|string|max:255|regex:/^[A-Za-z]+$/',

            'email' => 'required|email|unique:users,email',

            'phone' => 'nullable|string|max:20|unique:users,phone',

            'gender' => 'required|string|in:male,female,other',

            'dob' => 'nullable|date|before_or_equal:today',

            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'bio' => 'nullable|string|max:1000',



            // Account Information

            'password' => 'required|string|min:8|confirmed',

            'account_type' => 'required|string|in:applicant,client,admin',



            // Location Information

            'state_of_origin' => 'nullable|string|max:255',

            'state_of_residence' => 'nullable|string|max:255',

            'lga_of_origin' => 'nullable|string|max:255',

            'country' => 'nullable|string|max:255',

            'country_of_residence' => 'nullable|string|max:255',

            'address' => 'nullable|string|max:2048',

            'landmark' => 'nullable|string|max:255',



            // Professional Information

            'applicant_type' => 'nullable|string|in:live-in,live-out,semi-live-in,skill',

            'years_of_experience' => 'nullable|string|max:50',

            'nin' => 'nullable|string|max:50',

            'id_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',



            // Status Fields

            'approval_status' => 'nullable|string|in:pending,approved,rejected',

            'status' => 'nullable|string|in:active,inactive,archived',

            'availability' => 'nullable|boolean',

            'form_completed' => 'nullable|boolean',

            'guarantors_forms_uploaded' => 'nullable|boolean',

            'guarantors_forms_uploaded_2' => 'nullable|boolean',

            'terms_accepted' => 'nullable|boolean',



            'specialties' => 'nullable|array',

        ]);



        // Handle file uploads

        // $profilePhotoPath = null;

        // if ($request->hasFile('profile_photo_path')) {

        //     $profilePhotoPath = time() . '_profile.' . $request->file('profile_photo_path')->getClientOriginalExtension();

        //     $request->file('profile_photo_path')->storeAs('public/profile_photos', $profilePhotoPath);

        // }



        // Handle file uploads

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo_path')) {

            $filename = time() . '_profile.' . $request->file('profile_photo_path')->getClientOriginalExtension();



            // Store in public/build/assets/image/profile_photo directory

            $request->file('profile_photo_path')->move(public_path('build/assets/image/profile_photo'), $filename);

            $profilePhotoPath = $filename;

        }





        $idCardPath = null;

        if ($request->hasFile('id_card')) {

            $idCardPath = time() . '_idcard.' . $request->file('id_card')->getClientOriginalExtension();

            $request->file('id_card')->storeAs('public/id_cards', $idCardPath);

        }



        // Create user

        $user = User::create([

            'name' => $validated['name'],

            'middle_name' => $validated['middle_name'] ?? null,

            'last_name' => $validated['last_name'] ?? null,

            'email' => $validated['email'],

            'phone' => $validated['phone'] ?? null,

            'gender' => $validated['gender'],

            'dob' => $validated['dob'] ?? null,

            'state_of_origin' => $validated['state_of_origin'] ?? null,

            'state_of_residence' => $validated['state_of_residence'] ?? null,

            'lga_of_origin' => $validated['lga_of_origin'] ?? null,

            'country' => $validated['country'] ?? null,

            'country_of_residence' => $validated['country_of_residence'] ?? null,

            'address' => $validated['address'] ?? null,

            'landmark' => $validated['landmark'] ?? null,

            'password' => Hash::make($validated['password']),

            'user_role' => 0,  // Default user role

            'profile_photo_path' => $profilePhotoPath,

            'applicant_type' => $validated['applicant_type'] ?? null,

            'account_type' => $validated['account_type'] ?? null,

            'years_of_experience' => $validated['years_of_experience'] ?? null,

            'nin' => $validated['nin'] ?? null,

            'id_card' => $idCardPath,

            'bio' => $validated['bio'] ?? null,

            'approval_status' => $validated['approval_status'] ?? 'pending',

            'status' => $validated['status'] ?? 'active',

            'availability' => $validated['availability'] ?? true,

            'terms_accepted' => $validated['terms_accepted'],

            'specialties' => $validated['specialties'] ?? null,

        ]);



        return redirect()->back()->with('success', 'Account created successfully.');

    }





    public function editHouseHelp(User $user)
    {
        $services = Service::all();
        $skills = Skill::all();
        $guarantor1 = \App\Models\Guarantor::where('applicant_id', $user->id)->first();
        $guarantor2 = \App\Models\Guarantor2::where('applicant_id', $user->id)->first();
        return view('admin.edithousehelp', compact('user', 'services', 'skills', 'guarantor1', 'guarantor2'));
    }



    public function updateHouseHelp(Request $request, User $user)

    {

        $validated = $request->validate([

            // Personal Information

            'name' => 'required|string|max:255',

            'middle_name' => 'nullable|string|max:255',

            'last_name' => 'nullable|string|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'phone' => 'nullable|string|max:20',

            'gender' => 'nullable|string|in:male,female,other',

            'dob' => 'nullable|date',

            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'bio' => 'nullable|string|max:1000',



            // Location Information

            'state_of_origin' => 'nullable|string|max:255',

            'state_of_residence' => 'nullable|string|max:255',

            'lga_of_origin' => 'nullable|string|max:255',

            'country' => 'nullable|string|max:255',

            'country_of_residence' => 'nullable|string|max:255',

            'address' => 'nullable|string|max:2048',

            'landmark' => 'nullable|string|max:255',



            // Professional Information

            'applicant_type' => 'nullable|string|in:live-in,live-out,semi-live-in,skill',

            'years_of_experience' => 'nullable|string|max:50',

            'nin' => 'nullable|string|max:50',

            'id_card' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',



            // Status Fields

            'approval_status' => 'nullable|string|in:pending,approved,rejected',

            'status' => 'nullable|string|in:active,inactive,archived',

            'availability' => 'nullable|boolean',

            'form_completed' => 'nullable|boolean',

            'guarantors_forms_uploaded' => 'nullable|boolean',

            'guarantors_forms_uploaded_2' => 'nullable|boolean',

            'terms_accepted' => 'nullable|boolean',



            'specialties' => 'sometimes|array',

            'specialties.*' => 'string|exists:services,service_name',

        ]);







        // Handle file uploads

        if ($request->hasFile('profile_photo_path')) {

            if ($user->profile_photo_path) {

                Storage::delete('public/' . $user->profile_photo_path);

            }

            $path = $request->file('profile_photo_path')->store('profile_photos', 'public');

            $validated['profile_photo_path'] = $path;

        }



        if ($request->hasFile('id_card')) {

            if ($user->id_card) {

                Storage::delete('public/' . $user->id_card);

            }

            $path = \App\Support\SecureDocument::store($request->file('id_card'), 'idcards');

            $validated['id_card'] = $path;

        }



        // Handle specialties - no need for manual JSON encoding because of the cast

        if ($request->has('specialties')) {

            $validated['specialties'] = $request->specialties; // Let the cast handle conversion

        } else {

            $validated['specialties'] = []; // Empty array if none selected

        }



        // Handle checkbox fields

        $validated['availability'] = $request->has('availability');

        $validated['terms_accepted'] = $request->has('terms_accepted');

        $validated['form_completed'] = $request->has('form_completed');

        $validated['guarantors_forms_uploaded'] = $request->has('guarantors_forms_uploaded');

        $validated['guarantors_forms_uploaded_2'] = $request->has('guarantors_forms_uploaded_2');



        // Handle date

        $validated['dob'] = $request->dob ? Carbon::createFromFormat('Y-m-d', $request->dob) : null;



        $user->update($validated);



        return redirect()->route('adminapplicants')

            ->with('success', 'House help profile updated successfully');

    }



    public function staffLiveOut()

    {

        $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all();

        $apartments = Apartments::all();

        $admin = DB::table('users')->where('account_type', 'admin')->whereIn('user_role', [0, 1, 2])->paginate(10);

        $users = User::whereIn('account_type', ['client', 'applicant', 'staff'])->with(['guarantors', 'guarantor2s'])->paginate(10);

        $approvedusers = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('applicant_type', 'live-out')

            ->where('approval_status', 'approved')

            ->with(['guarantors', 'guarantor2s'])

            ->paginate(10);



        $totalClients = User::where('account_type', 'client')->count();

        $totalApplicants = User::whereIn('account_type', ['applicant', 'staff'])

            ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

            ->count();

        $totalskilledworkers = User::where('applicant_type', 'skill')->count();

        $totalAdmin = User::where('account_type', 'admin')->count();

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.staffLiveIn', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'admin',

            'users',

            'totalClients',

            'totalApplicants',

            'totalAdmin',

            'totalServiceRequests',

            'approvedusers',

            'totalskilledworkers',

        ));

    }





    // public function applicantshousehelps()

    // {

    //     $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

    //     $packages = Package::all();

    //     $apartmentdetails = Apartments::all();

    //     $apartments = Apartments::all();

    //     $admin = DB::table('users')->where('account_type', 'admin')->whereIn('user_role', [0, 1, 2])->paginate(10);



    //     $users = User::whereIn('account_type', ['applicant'])->with(['guarantors', 'guarantor2s'])->paginate(10);

    //     $clients = User::whereIn('account_type', ['client'])->paginate(10);



    //     $approvedusers = User::where('account_type', 'applicant')

    //         ->where('applicant_type', 'live-out')

    //         ->with(['guarantors', 'guarantor2s'])

    //         ->paginate(10);



    //     $totalClients = User::where('account_type', 'client')->count();

    //     $totalApplicants = User::where('account_type', 'applicant')

    //         ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

    //         ->count();

    //     $totalskilledworkers = User::where('applicant_type', 'skill')->count();

    //     $totalAdmin = User::where('account_type', 'admin')->count();

    //     $totalServiceRequests = ServiceRequest::count();



    //     return view('admin.applicants', compact(

    //         'packages',

    //         'orders',

    //         'apartmentdetails',

    //         'apartments',

    //         'admin',

    //         'users',

    //         'totalClients',

    //         'totalApplicants',

    //         'totalAdmin',

    //         'totalServiceRequests',

    //         'approvedusers',

    //         'clients',

    //         'totalskilledworkers'

    //     ));

    // }



    // public function applicantshousehelps()

    // {

    //     // Live-out applicants

    //     $approvedusers = User::where('account_type', 'applicant')

    //         ->where('applicant_type', 'live-out')

    //         ->with(['guarantors', 'guarantor2s'])

    //         ->orderBy('created_at', 'desc')

    //         ->paginate(10, ['*'], 'liveout_page');



    //     // Live-in applicants

    //     $users = User::where('account_type', 'applicant')

    //         ->where('applicant_type', 'live-in')

    //         ->with(['guarantors', 'guarantor2s'])

    //         ->orderBy('created_at', 'desc')

    //         ->paginate(10, ['*'], 'livein_page');



    //     // Counts for dashboard

    //     $counts = [

    //         'totalClients' => User::where('account_type', 'client')->count(),

    //         'totalApplicants' => User::where('account_type', 'applicant')

    //             ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

    //             ->count(),

    //         'totalSkilledWorkers' => User::where('applicant_type', 'skill')->count(),

    //         'totalAdmin' => User::where('account_type', 'admin')->count(),

    //         'totalServiceRequests' => ServiceRequest::count()

    //     ];



    //     return view('admin.applicants', array_merge(

    //         compact('approvedusers', 'users'),

    //         $counts

    //     ));

    // }



    public function applicantshousehelps(Request $request)

    {

        $skills = Skill::all();

        $services = Service::all();

        // Base queries

        $liveOutQuery = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('applicant_type', 'live-out')

            ->with(['guarantors', 'guarantor2s']);



        $liveInQuery = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('applicant_type', 'live-in')

            ->with(['guarantors', 'guarantor2s']);



        // Search functionality

        if ($request->has('liveout_search')) {

            $searchTerm = $request->input('liveout_search');

            $liveOutQuery->where(function ($query) use ($searchTerm) {

                $query->where('name', 'like', "%{$searchTerm}%")

                    ->orWhere('middle_name', 'like', "%{$searchTerm}%")

                    ->orWhere('last_name', 'like', "%{$searchTerm}%")

                    ->orWhere('email', 'like', "%{$searchTerm}%")

                    ->orWhere('phone', 'like', "%{$searchTerm}%")

                    ->orWhere('gender', 'like', "%{$searchTerm}%");

            });

        }



        if ($request->has('livein_search')) {

            $searchTerm = $request->input('livein_search');

            $liveInQuery->where(function ($query) use ($searchTerm) {

                $query->where('name', 'like', "%{$searchTerm}%")

                    ->orWhere('middle_name', 'like', "%{$searchTerm}%")

                    ->orWhere('last_name', 'like', "%{$searchTerm}%")

                    ->orWhere('email', 'like', "%{$searchTerm}%")

                    ->orWhere('phone', 'like', "%{$searchTerm}%")

                    ->orWhere('gender', 'like', "%{$searchTerm}%");

            });

        }



        // Paginate results

        $approvedusers = $liveOutQuery->orderBy('created_at', 'desc')

            ->paginate(10, ['*'], 'liveout_page')

            ->appends($request->except('liveout_page'));



        $users = $liveInQuery->orderBy('created_at', 'desc')

            ->paginate(10, ['*'], 'livein_page')

            ->appends($request->except('livein_page'));



        // Counts for dashboard (unchanged)

        $counts = [

            'totalClients' => User::where('account_type', 'client')->count(),

            'totalApplicants' => User::whereIn('account_type', ['applicant', 'staff'])

                ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

                ->count(),

            'totalSkilledWorkers' => User::where('applicant_type', 'skill')->count(),

            'totalAdmin' => User::where('account_type', 'admin')->count(),

            'totalServiceRequests' => ServiceRequest::count()

        ];



        $totalLiveIn = User::whereIn('account_type', ['applicant', 'staff'])

            ->whereIn('applicant_type', ['live-in'])

            ->count();



        $totalLiveOut = User::whereIn('account_type', ['applicant', 'staff'])

            ->whereIn('applicant_type', ['live-out'])

            ->count();



        $totalBoth = User::whereIn('account_type', ['applicant', 'staff'])

            ->whereIn('applicant_type', ['semi-live-in'])

            ->count();



        return view('admin.applicants', array_merge(

            compact(

                'approvedusers',

                'users',

                'skills',

                'totalLiveIn',

                'totalLiveOut',

                'totalBoth',

                'services'

            ),

            $counts

        ));

    }



    public function approveUser($id)

    {

        $user = User::findOrFail($id);

        $user->approval_status = 'approved';



        // Change account type from applicant to staff upon approval

        if ($user->account_type === 'applicant') {

            $user->account_type = 'staff';

        }



        $user->save();



        return redirect()->back()->with('success', 'User approved successfully');

    }



    public function unapproveUser($id)

    {

        $user = User::findOrFail($id);

        $user->approval_status = 'pending';



        // Revert back to applicant when unapproved

        if ($user->account_type === 'staff') {

            $user->account_type = 'applicant';

        }



        $user->save();



        return redirect()->back()->with('success', 'User approval removed successfully');

    }



    public function archieveUser($id)

    {

        $user = User::findOrFail($id);

        $user->account_type = 'delete';

        $user->save();



        return redirect()->back()->with('success', 'User archieved successfully');

    }









    public function applicantsskilledworkers()

    {

        $services = Service::all();

        $skills = Skill::all();



        $admin = DB::table('users')->where('account_type', 'admin')->whereIn('user_role', [0, 1, 2])->paginate(10);



        $users = User::whereIn('account_type', ['applicant', 'staff'])->with(['guarantors', 'guarantor2s'])->paginate(10);



        $skilledworkers = User::whereIn('account_type', ['applicant', 'staff'])

            ->where('applicant_type', 'skill')

            ->paginate(10);



        $totalClients = User::where('account_type', 'client')->count();

        $totalApplicants = User::whereIn('account_type', ['applicant', 'staff'])

            ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

            ->count();

        $totalskilledworkers = User::where('applicant_type', 'skill')->count();

        $totalAdmin = User::where('account_type', 'admin')->count();

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.skilledworkerslist', compact(

            'admin',

            'users',

            'totalClients',

            'totalApplicants',

            'totalskilledworkers',

            'totalAdmin',

            'totalServiceRequests',

            'skilledworkers',

            'services',

            'skills',

        ));

    }





    public function clients()

    {

        $services = Service::all();

        $skills = Skill::all();



        $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all();

        $apartments = Apartments::all();

        $admin = DB::table('users')->where('account_type', 'admin')->whereIn('user_role', [0, 1, 2])->paginate(10);



        $users = User::whereIn('account_type', ['applicant'])->with(['guarantors', 'guarantor2s'])->paginate(10);

        $clients = User::whereIn('account_type', ['client'])->paginate(10);



        $approvedusers = User::where('account_type', 'applicant')

            ->where('applicant_type', 'live-out')

            ->with(['guarantors', 'guarantor2s'])

            ->paginate(10);



        $totalClients = User::where('account_type', 'client')->count();

        $totalApplicants = User::where('account_type', 'applicant')

            ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

            ->count();

        $totalskilledworkers = User::where('applicant_type', 'skill')->count();

        $totalAdmin = User::where('account_type', 'admin')->count();

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.clients', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'admin',

            'users',

            'totalClients',

            'totalApplicants',

            'totalAdmin',

            'totalServiceRequests',

            'approvedusers',

            'clients',

            'totalskilledworkers',

            'services',

            'skills',

        ));

    }





    public function staffs()

    {

        $orders = ServiceRequest::orderBy('created_at', 'desc')->paginate(10);

        $packages = Package::all();

        $apartmentdetails = Apartments::all();

        $apartments = Apartments::all();

        $admin = DB::table('users')->where('account_type', 'admin')->whereIn('user_role', [0, 1, 2])->paginate(10);



        $users = User::whereIn('account_type', ['applicant'])->with(['guarantors', 'guarantor2s'])->paginate(10);

        $clients = User::whereIn('account_type', ['client'])->paginate(10);



        $approvedusers = User::where('account_type', 'applicant')

            ->where('applicant_type', 'live-out')

            ->with(['guarantors', 'guarantor2s'])

            ->paginate(10);



        $totalClients = User::where('account_type', 'client')->count();

        $totalApplicants = User::where('account_type', 'applicant')

            ->whereIn('applicant_type', ['live-in', 'live-out', 'semi-live-in'])

            ->count();

        $totalskilledworkers = User::where('applicant_type', 'skill')->count();

        $totalAdmin = User::where('account_type', 'admin')->count();

        $totalServiceRequests = ServiceRequest::count();



        return view('admin.staffs', compact(

            'packages',

            'orders',

            'apartmentdetails',

            'apartments',

            'admin',

            'users',

            'totalClients',

            'totalApplicants',

            'totalAdmin',

            'totalServiceRequests',

            'approvedusers',

            'clients',

            'totalskilledworkers'

        ));

    }





    // public function deleteUser($id)

    // {

    //     $user = User::findOrFail($id);

    //     $user->delete();



    //     return redirect()->back()->with('success', 'User deleted successfully.');

    // }



    public function deleteUser($id)

    {

        try {

            DB::beginTransaction();



            $user = User::findOrFail($id);



            // Delete related records first if needed

            $user->guarantors()->delete();

            $user->guarantor2s()->delete();



            $user->delete();



            DB::commit();



            return redirect()->back()->with('success', 'User deleted successfully');

        } catch (\Exception $e) {

            DB::rollBack();



            $errorMessage = 'Error deleting user: ' . $e->getMessage();



            // Log the full error for debugging

            \Log::error($errorMessage);



            // Return a user-friendly message

            return redirect()->back()->with('error', 'Failed to delete user. Please try again. If the problem persists, contact support.');

        }

    }





    public function suspendUser(Request $request, $id)

    {

        $user = User::findOrFail($id);

        $user->update(['status' => 'suspended']); // Ensure 'status' exists in the users table

        return redirect()->route('users')->with('success', 'User suspended successfully.');

    }



    public function liftSuspension(Request $request, $id)

    {

        $user = User::findOrFail($id);

        $user->update(['status' => 'active']); // Ensure 'status' is updated correctly

        return redirect()->route('users')->with('success', 'Suspension lifted successfully for ' . $user->name);

    }





    public function updateUserRole(Request $request, $id)

    {

        $request->validate(['role' => 'required|integer']);



        $user = User::findOrFail($id);

        $user->user_role = $request->role;

        $user->save();



        return redirect()->back()->with('success', 'User role updated successfully!');

    }





    public function searchUsers(Request $request)

    {

        $query = $request->input('q');



        // Perform search across name, middle_name, last_name, email, account_type, phone, gender fields

        $users = DB::table('users_view')

            ->where('name', 'like', "%{$query}%")

            ->orWhere('middle_name', 'like', "%{$query}%")

            ->orWhere('last_name', 'like', "%{$query}%")

            ->orWhere('email', 'like', "%{$query}%")

            ->orWhere('account_type', 'like', "%{$query}%")

            ->orWhere('phone', 'like', "%{$query}%")

            ->orWhere('gender', 'like', "%{$query}%")

            ->paginate(10); // Paginate if desired



        // Return the results as JSON

        return response()->json(['users' => $users]);

    }







    public function allpayments()

    {

        // Fetch all payments with related user, service, plan, and request clicks

        $payments = Payment::with(['user', 'service', 'paymentPlan', 'requestClick'])

            ->orderBy('created_at', 'desc')

            ->paginate(10);



        return view('admin.allpayments', compact('payments'));

    }





    // Add this to your AdminController or create a new controller

    public function costCalculator()

    {

        $allServices = Service::all();

        $apartments = Apartments::all();

        $packages = Package::all();



        return view('admin.cost-calculator', compact('allServices', 'apartments', 'packages'));

    }



    /*

    |--------------------------------------------------------------------------

    | BOOKING FLOW MANAGEMENT

    |--------------------------------------------------------------------------

    */



    public function bookingsDashboard()

    {

        $bookings = ServiceRequest::whereNotNull('matching_status')

            ->where('matching_status', '!=', 'pending')

            ->with(['user', 'selectedStaff', 'bookingPayments'])

            ->orderByDesc('created_at')

            ->paginate(20);



        $stats = [

            'matching' => ServiceRequest::where('matching_status', 'matching')->count(),

            'shortlisted' => ServiceRequest::where('matching_status', 'shortlisted')->count(),

            'payment_pending' => ServiceRequest::where('matching_status', 'payment_pending')->count(),

            'approved' => ServiceRequest::where('matching_status', 'approved')->count(),

            'deployed' => ServiceRequest::where('matching_status', 'deployed')->count(),

        ];



        $pendingPayments = BookingPayment::where('status', 'pending_approval')->count();



        return view('admin.booking.dashboard', compact('bookings', 'stats', 'pendingPayments'));

    }



    public function bookingDetail($id)

    {

        $booking = ServiceRequest::with([

            'user', 'selectedStaff', 'shortlistedStaff.staff',

            'bookingPayments', 'managementPlanDetails', 'monthlySubscription'

        ])->findOrFail($id);



        return view('admin.booking.detail', compact('booking'));

    }



    public function approveBookingPayment(Request $request, $id)

    {

        $booking = ServiceRequest::findOrFail($id);

        $payment = $booking->bookingPayments()->where('status', 'pending_approval')->latest()->first();



        if (!$payment) {

            return redirect()->back()->with('error', 'No pending payment found.');

        }



        $payment->markAsCompleted();



        // Release staff details

        $booking->update([

            'staff_details_released' => true,

            'matching_status' => 'approved',

            'payment_status' => 'Paid',

        ]);



        // Notify client

        if ($booking->user) {

            $booking->user->notify(new StaffDetailsReleasedNotification($booking));

        }



        return redirect()->back()->with('success', 'Payment approved and staff details released.');

    }



    public function releaseStaffDetails(Request $request, $id)

    {

        $booking = ServiceRequest::findOrFail($id);



        $booking->update([

            'staff_details_released' => true,

            'matching_status' => 'approved',

        ]);



        if ($booking->user) {

            $booking->user->notify(new StaffDetailsReleasedNotification($booking));

        }



        return redirect()->back()->with('success', 'Staff details released to client.');

    }



    public function manageManagementPlans()

    {

        $plans = ManagementPlan::ordered()->get();

        return view('admin.booking.management-plans', compact('plans'));

    }



    public function storeManagementPlan(Request $request)

    {

        $validated = $request->validate([

            'slug' => 'required|string|unique:management_plans',

            'name' => 'required|string',

            'base_price' => 'required|numeric|min:0',

            'max_price' => 'nullable|numeric|min:0',

            'features' => 'required|string',

            'sort_order' => 'required|integer',

        ]);



        $validated['features'] = array_map('trim', explode("\n", $validated['features']));

        $validated['is_active'] = true;



        ManagementPlan::create($validated);



        return redirect()->back()->with('success', 'Management plan created.');

    }



    public function updateManagementPlan(Request $request, $id)

    {

        $plan = ManagementPlan::findOrFail($id);



        $validated = $request->validate([

            'name' => 'required|string',

            'base_price' => 'required|numeric|min:0',

            'max_price' => 'nullable|numeric|min:0',

            'features' => 'required|string',

            'label' => 'nullable|string',

            'sort_order' => 'required|integer',

            'is_active' => 'nullable|boolean',

        ]);



        $validated['features'] = array_map('trim', explode("\n", $validated['features']));

        $validated['is_active'] = $request->has('is_active');



        $plan->update($validated);



        return redirect()->back()->with('success', 'Management plan updated.');

    }



    /*

    |--------------------------------------------------------------------------

    | MARK AS DEPLOYED

    |--------------------------------------------------------------------------

    */



    public function markAsDeployed(Request $request, $id)

    {

        $booking = ServiceRequest::findOrFail($id);



        $booking->update([

            'matching_status' => 'deployed',

        ]);



        return redirect()->back()->with('success', 'Booking marked as deployed.');

    }



    /*

    |--------------------------------------------------------------------------

    | LOCATION MANAGEMENT

    |--------------------------------------------------------------------------

    */



    public function locations()

    {

        $locations = Location::orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.locations.index', compact('locations'));

    }



    public function storeLocation(Request $request)

    {

        $validated = $request->validate([

            'name' => 'required|string|max:100|unique:locations,name',

            'state' => 'required|string|max:50',

            'is_active' => 'nullable|boolean',

            'sort_order' => 'nullable|integer|min:0',

        ]);



        $validated['is_active'] = $request->has('is_active');

        $validated['sort_order'] = $validated['sort_order'] ?? 0;



        Location::create($validated);



        return redirect()->back()->with('success', 'Location added successfully.');

    }



    public function updateLocation(Request $request, $id)

    {

        $location = Location::findOrFail($id);



        $validated = $request->validate([

            'name' => 'required|string|max:100|unique:locations,name,' . $id,

            'state' => 'required|string|max:50',

            'is_active' => 'nullable|boolean',

            'sort_order' => 'nullable|integer|min:0',

        ]);



        $validated['is_active'] = $request->has('is_active');



        $location->update($validated);



        return redirect()->back()->with('success', 'Location updated.');

    }



    public function destroyLocation($id)

    {

        Location::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Location deleted.');

    }



    /*

    |--------------------------------------------------------------------------

    | REPLACEMENT REQUEST MANAGEMENT

    |--------------------------------------------------------------------------

    */



    public function manageReplacements()

    {

        $replacements = ReplacementRequest::with(['serviceRequest', 'user'])

            ->orderByDesc('requested_at')

            ->paginate(20);



        return view('admin.replacements.index', compact('replacements'));

    }



    public function updateReplacementStatus(Request $request, $id)

    {

        $replacement = ReplacementRequest::findOrFail($id);



        $validated = $request->validate([

            'status' => 'required|in:approved,rejected',

            'admin_notes' => 'nullable|string|max:500',

        ]);



        $replacement->update(array_merge($validated, ['resolved_at' => now()]));



        return redirect()->back()->with('success', 'Replacement request updated.');

    }

}

