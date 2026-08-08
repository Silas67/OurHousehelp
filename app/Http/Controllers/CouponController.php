<?php

namespace App\Http\Controllers;

use App\Models\CouponUsage;
use App\Models\LiveInServiceRequest;
use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\Rating;
use App\Models\RequestClick;
use App\Models\SearchLog;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Skill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        $requestRecord = ServiceRequest::where('phone', $request->phone)
            ->where('status', 'confirmed')
            ->whereColumn('bonus_slots_total', '>', 'bonus_slots_used')
            ->first();

        if (!$requestRecord) {
            return back()->withErrors(['phone' => 'Invalid or inactive coupon (phone number).']);
        }

        // If valid, store in session or pass to the next request
        session(['active_coupon_phone' => $request->phone]);

        // return back()->with('success', 'Coupon (phone number) applied successfully!');
        return redirect()->route('couponSkilledWorkerServices')->with('success', 'Coupon (phone number) applied successfully!');
    }

    public function showSkilledWorkerServices(Request $request)
    {
        // Check if coupon is valid (phone number in session)
        $phone = session('active_coupon_phone');
        if (!$phone) {
            return redirect()->route('home')->with('error', 'Please validate your coupon first.');
        }

        // Get the service request record
        $requestRecord = ServiceRequest::where('phone', $phone)
            ->where('status', 'confirmed')
            ->whereColumn('bonus_slots_total', '>', 'bonus_slots_used')
            ->first();

        if (!$requestRecord) {
            return redirect()->route('home')->with('error', 'Invalid or expired coupon.');
        }

        // Calculate remaining searches
        $searchCount = $requestRecord->bonus_slots_used ?? 0;
        $remainingSearches = $requestRecord->bonus_slots_total - $searchCount;

        // Build the query for skilled workers
        $query = User::whereIn('account_type', ['applicant', 'staff'])
            ->where('availability', 1)
            ->where('applicant_type', 'skill');

        // Apply filters if search parameters exist
        if ($request->filled('skill')) {
            $query->where('specialties', 'like', '%' . $request->input('skill') . '%');
        }

        if ($request->filled('state_of_residence')) {
            $query->where('state_of_residence', $request->input('state_of_residence'));
        }

        if ($request->filled('lga_of_origin')) {
            $query->where('lga_of_origin', 'like', '%' . $request->input('lga_of_origin') . '%');
        }

        // Get 5 random skilled workers
        $workers = $query->inRandomOrder()->paginate(5);

        // Get all available skills from skills table
        $skills = Skill::orderBy('skill_name')->get(['id', 'skill_name']);

        // Nigerian states
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

        return view('coupon.skilled-worker-services', [
            'workers' => $workers,
            'remainingSlots' => $remainingSearches,
            'skills' => $skills,
            'states' => $states,
            'requestRecord' => $requestRecord
        ]);
    }

    // public function unlockWorker(Request $request, $id)
    // {
    //     // Validate coupon session
    //     $phone = session('active_coupon_phone');
    //     if (!$phone) {
    //         return back()->with('error', 'Coupon session expired. Please validate again.');
    //     }

    //     // Find the worker in users table - updated to match your current structure
    //     $worker = User::where('id', $id)
    //         ->where('account_type', 'applicant')
    //         ->where('applicant_type', 'skill')
    //         ->where('approval_status', 'approved')
    //         ->where('status', 'active')
    //         ->firstOrFail();

    //     // Get the service request record
    //     $requestRecord = ServiceRequest::where('phone', $phone)
    //         ->where('status', 'confirmed')
    //         ->whereColumn('bonus_slots_total', '>', 'bonus_slots_used')
    //         ->first();

    //     if (!$requestRecord) {
    //         return back()->with('error', 'No remaining slots available.');
    //     }

    //     // Deduct one slot
    //     DB::transaction(function () use ($requestRecord, $worker) {
    //         $requestRecord->increment('bonus_slots_used');

    //         // Log the usage
    //         CouponUsage::create([
    //             'coupon' => $requestRecord->phone,
    //             'service_request_id' => $requestRecord->id,
    //             'worker_id' => $worker->id,
    //             'description' => 'Viewed details of skilled worker: ' . $worker->name,
    //             'used_at' => now(),
    //         ]);
    //     });

    //     return view('coupon.worker-details', [
    //         'worker' => $worker,
    //         'remainingSlots' => $requestRecord->bonus_slots_total - $requestRecord->bonus_slots_used
    //     ]);
    // }

    public function unlockWorker(Request $request, $id)
    {
        $phone = session('active_coupon_phone');
        $worker = User::findOrFail($id);

        // Get the service request record
        $requestRecord = ServiceRequest::where('phone', $phone)
            ->where('status', 'confirmed')
            ->whereColumn('bonus_slots_total', '>', 'bonus_slots_used')
            ->first();

        if (!$requestRecord) {
            return back()->with('error', 'No remaining slots available.');
        }

        // Check if already viewed this worker
        $alreadyViewed = CouponUsage::where([
            ['coupon', $phone],
            ['service_request_id', $requestRecord->id],
            ['worker_id', $id]
        ])->exists();

        if ($alreadyViewed) {
            return view('coupon.worker-details', [
                'worker' => $worker,
                'remainingSlots' => $requestRecord->bonus_slots_total - $requestRecord->bonus_slots_used
            ]);
        }

        // Deduct slot if first time viewing
        DB::transaction(function () use ($requestRecord, $worker, $phone) {
            $requestRecord->increment('bonus_slots_used');

            CouponUsage::create([
                'coupon' => $phone,
                'service_request_id' => $requestRecord->id,
                'worker_id' => $worker->id,
                'description' => 'Viewed details of skilled worker: ' . $worker->name,
                'used_at' => now()
            ]);
        });

        return view('coupon.worker-details', [
            'worker' => $worker,
            'remainingSlots' => $requestRecord->bonus_slots_total - $requestRecord->bonus_slots_used
        ]);
    }
}
