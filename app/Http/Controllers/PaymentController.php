<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentPlan;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function paymentoption()
    {
        // $plans = PaymentPlan::all(); // Fetch all payment plans
        $plans = PaymentPlan::where('plan_type', 'househelp')->get(); // Fetch only payment plans for househelp
        return view('payments.paymentoption', compact('plans'));
    }


    // public function paymentoption(Request $request)
    // {
    //     dd($request->all()); // Dump all incoming request data
    // }

    public function paymentoptionskilledworkers()
    {
        $plans = PaymentPlan::where('plan_type', 'skilled')->get(); // Fetch only payment plans for skilled
        return view('payments.paymentoptionskilledworkers', compact('plans'));
    }



    public function accountdetail($plan_id)
    {
        $user = auth()->user();
        $plan = PaymentPlan::findOrFail($plan_id); // Ensure the correct model is used
        $payment = Payment::where('user_id', $user->id)->where('payment_plan_id', $plan_id)->where('plan_type', 'househelp')->first();

        return view('payments.accountdetail', compact('plan', 'user', 'payment'));
    }

    public function accountdetailskill($plan_id)
    {
        $user = auth()->user();
        $plan = PaymentPlan::findOrFail($plan_id); // Ensure the correct model is used
        $payment = Payment::where('user_id', $user->id)->where('payment_plan_id', $plan_id)->where('plan_type', 'skilled')->first();

        return view('payments.accountdetailskill', compact('plan', 'user', 'payment'));
    }

    public function handlePayment(Request $request)
    {
        // dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_plan_id' => 'required|exists:payment_plans,id',
            'payment_frequency' => 'required|string',
            'amount' => 'required|numeric',
            'about_payment' => 'required|string',
            'num_searches' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'plan_type' => 'required|string',
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Receipt validation
        ]);

        $userId = Auth::id();

        // Check for active approved payment
        $activePaymentExists = Payment::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('plan_type', 'househelp')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->whereColumn('used_searches', '<', 'num_searches')
            ->exists();

        if ($activePaymentExists) {
            return redirect()->back()->with('error', 'You already have an active payment.');
        }

        // Check for pending, exhausted, or expired payments
        $existingPayment = Payment::where('user_id', $userId)
            ->where('plan_type', 'househelp')
            ->whereIn('status', ['pending', 'exhausted', 'expired'])
            ->first();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $filePath = time() . '_receipt.' . $request->file('receipt')->getClientOriginalExtension();
            $request->file('receipt')->move(public_path('build/assets/image/receipt'), $filePath);

            // Add receipt_path to the validated data
            $validated['receipt_path'] = $filePath;
        }

        if ($existingPayment) {
            // Update the existing payment
            $existingPayment->update($validated);
            return redirect()->route('dashboard')->with('success', 'Payment updated successfully.');
        }

        // Create a new payment
        $payment = Payment::create(array_merge($validated, [
            'status' => 'pending', // Default status
        ]));

        session()->put('receipt_uploaded', true);

        return redirect()->route('dashboard')->with('success', 'Payment request submitted. Please wait for confirmation.');
    }

    public function handlePaymentSkill(Request $request)
    {
        // dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_plan_id' => 'required|exists:payment_plans,id',
            'payment_frequency' => 'required|string',
            'amount' => 'required|numeric',
            'about_payment' => 'required|string',
            'num_searches' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'plan_type' => 'required|string',
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Receipt validation
        ]);

        $userId = Auth::id();

        // Check for active approved payment
        $activePaymentExists = Payment::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('plan_type', 'skilled')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->whereColumn('used_searches', '<', 'num_searches')
            ->exists();

        // if ($activePaymentExists) {
        //     return redirect()->back()->with('error', 'You already have an active payment.');
        // }

        // Check for pending, exhausted, or expired payments
        $existingPayment = Payment::where('user_id', $userId)
            ->where('plan_type', 'skilled')
            ->whereIn('status', ['pending', 'exhausted', 'expired'])
            ->first();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $filePath = time() . '_receipt.' . $request->file('receipt')->getClientOriginalExtension();
            $request->file('receipt')->move(public_path('build/assets/image/receipt'), $filePath);

            // Add receipt_path to the validated data
            $validated['receipt_path'] = $filePath;
        }

        if ($existingPayment) {
            // Update the existing payment
            $existingPayment->update($validated);
            return redirect()->route('dashboard')->with('success', 'Payment updated successfully.');
        }

        // Create a new payment
        $payment = Payment::create(array_merge($validated, [
            'status' => 'pending', // Default status
        ]));

        session()->put('receipt_uploaded', true);

        return redirect()->route('dashboard')->with('success', 'Payment request submitted. Please wait for confirmation.');
    }



    public function paymentSuccess()
    {
        return view('payments.success');
    }




    // public function approve($id)
    // {
    //     $payment = Payment::findOrFail($id);

    //     if ($payment->status !== 'pending') {
    //         return redirect()->back()->with('error', 'Payment is already processed.');
    //     }

    //     // Get the payment plan details
    //     $paymentPlan = PaymentPlan::find($payment->payment_plan_id);

    //     if (!$paymentPlan) {
    //         return redirect()->back()->with('error', 'Invalid payment plan.');
    //     }

    //     // Calculate the expiry date based on the plan duration
    //     $expiryDate = now()->addDays($paymentPlan->duration);


    //     // Update payment status and expiry date
    //     $payment->update([
    //         'status' => 'approved',
    //         'expiry_date' => $expiryDate,
    //     ]);

    //     return redirect()->back()->with('success', 'Payment approved successfully.');
    // }



    public function approve($id)
    {
        $payment = Payment::findOrFail($id);

        // if ($payment->status !== 'pending') {
        //     return redirect()->back()->with('error', 'Payment is already processed.');
        // }

        // Get the payment plan details
        $paymentPlan = PaymentPlan::find($payment->payment_plan_id);

        if (!$paymentPlan) {
            return redirect()->back()->with('error', 'Invalid payment plan.');
        }

        // Calculate the expiry date based on the plan duration
        $expiryDate = now()->addDays((int) $paymentPlan->duration); // Cast to int here

        // Update payment status and expiry date
        $payment->update([
            'status' => 'approved',
            'expiry_date' => $expiryDate,
        ]);

        return redirect()->back()->with('success', 'Payment approved successfully.');
    }


    public function cancel(Payment $payment)
    {
        $payment->update(['status' => 'failed']);
        return redirect()->route('paymenthistory')->with('success', 'Payment canceled successfully.');
    }


    public function unapprove($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'pending';
        $payment->save();

        return back()->with('success', 'Payment status set to pending.');
    }
}
