<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return $this->storeManualPayment($request, 'househelp', true);
    }

    public function handlePaymentSkill(Request $request)
    {
        // The active-payment guard is deliberately not enforced for skilled
        // plans; it was commented out inline in the original implementation.
        return $this->storeManualPayment($request, 'skilled', false);
    }

    /**
     * Record a manual (bank transfer) payment request against a payment plan.
     *
     * Amount, search quota, plan description, plan type and the owning user are
     * all resolved on the server. They were previously accepted from hidden
     * form inputs, which allowed a client to set their own price, grant
     * themselves an arbitrary search quota, or attribute a payment to
     * another user's account.
     */
    private function storeManualPayment(Request $request, string $planType, bool $enforceActivePaymentGuard)
    {
        $request->validate([
            'payment_plan_id' => 'required|exists:payment_plans,id',
            'receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $userId = Auth::id();

        // Scoping by plan_type stops a househelp form from purchasing a
        // skilled-worker plan (or vice versa) by swapping the plan id.
        $plan = PaymentPlan::where('id', $request->input('payment_plan_id'))
            ->where('plan_type', $planType)
            ->firstOrFail();

        if ($enforceActivePaymentGuard) {
            $activePaymentExists = Payment::where('user_id', $userId)
                ->where('status', 'approved')
                ->where('plan_type', $planType)
                ->where(function ($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
                })
                ->whereColumn('used_searches', '<', 'num_searches')
                ->exists();

            if ($activePaymentExists) {
                return redirect()->back()->with('error', 'You already have an active payment.');
            }
        }

        // Check for pending, exhausted, or expired payments
        $existingPayment = Payment::where('user_id', $userId)
            ->where('plan_type', $planType)
            ->whereIn('status', ['pending', 'exhausted', 'expired'])
            ->first();

        $file = $request->file('receipt');
        $receiptPath = Str::random(40) . '.' . $file->extension();
        $file->storeAs(\App\Support\SecureDocument::storageDirectory('receipts'), $receiptPath, 'local');

        $attributes = [
            'user_id'           => $userId,
            'payment_plan_id'   => $plan->id,
            'plan_type'         => $plan->plan_type,
            'amount'            => $plan->cost,
            'num_searches'      => $plan->num_searches,
            'about_payment'     => $plan->about,
            'payment_frequency' => 'Not Specified',
            'payment_method'    => 'Bank Transfer',
            'receipt_path'      => $receiptPath,
        ];

        if ($existingPayment) {
            // Status is intentionally left untouched here, matching the
            // previous behaviour.
            $existingPayment->update($attributes);
            return redirect()->route('dashboard')->with('success', 'Payment updated successfully.');
        }

        Payment::create($attributes + ['status' => 'pending']);

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
