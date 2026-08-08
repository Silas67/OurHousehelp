<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestClick;
use Carbon\Carbon;

class RequestClickController extends Controller
{

    public function store(Request $request)
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'househelp_id' => 'required|integer|exists:users,id',
            'payment_id' => 'required|integer|exists:payments,id'
        ]);

        // Get the user's latest approved payment for househelp plan
        $payment = Payment::where('id', $validated['payment_id'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            // ->where('plan_type', 'househelp')
            ->first();

        if (!$payment) {
            return response()->json([
                'error' => 'No valid payment found',
                'message' => 'You need an approved househelp subscription to view details'
            ], 403);
        }

        // Check if payment is expired
        if (Carbon::now()->greaterThan($payment->expiry_date)) {
            return response()->json([
                'error' => 'Payment expired',
                'message' => 'Your subscription has expired. Please renew.'
            ], 403);
        }

        // Count existing clicks for this payment
        $usedSearches = RequestClick::where('payment_id', $payment->id)->count();

        // Check if search limit reached
        if ($usedSearches >= $payment->num_searches) {
            return response()->json([
                'error' => 'Search limit reached',
                'message' => 'You have used all your allowed views for this subscription'
            ], 403);
        }

        // Log the click
        $click = RequestClick::create([
            'user_id' => $user->id,
            'househelp_id' => $validated['househelp_id'],
            'payment_id' => $payment->id
        ]);

        // Calculate remaining searches
        $remainingSearches = max($payment->num_searches - ($usedSearches + 1), 0);

        return response()->json([
            'success' => true,
            'remainingSearches' => $remainingSearches,
            'message' => 'Request logged successfully'
        ]);
    }

    // public function store(Request $request)
    // {
    //     dd($request->all()); // Dump all incoming request data
    // }
}
