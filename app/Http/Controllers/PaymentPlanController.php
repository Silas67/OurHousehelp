<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\PaymentRecord;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentPlanController extends Controller
{
    public function paymentplanstore(Request $request)
    {
        $request->validate([
            'plan_type' => 'required|string|max:255',
            'plan_name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'about' => 'nullable|string',
            'duration' => 'required|integer',
            'num_searches' => 'required|integer|min:1', // New validation rule
        ]);

        PaymentPlan::create([
            'plan_type' => $request->plan_type,
            'plan_name' => $request->plan_name,
            'cost' => $request->cost,
            'about' => $request->about,
            'duration' => $request->duration,
            'num_searches' => $request->num_searches, // Store the new field
        ]);

        return redirect()->back()->with('success', 'Payment plan created successfully.');
    }



    public function paymentplan()
    {
        // Show form to create a new payment plan
        return view('admin.paymentplan');
    }


    public function allpaymentplan()
    {
        // Show form to create a new payment plan
        $paymentPlans = PaymentPlan::all();
        return view('admin.allpaymentplan', compact('paymentPlans'));
    }

    
    public function paymentplanupdate(Request $request, $id)
    {
        $request->validate([
            'plan_type' => 'required|string|max:255',
            'plan_name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'about' => 'nullable|string',
            'duration' => 'required|integer',
            'num_searches' => 'required|integer|min:1', // New validation rule
        ]);

        $paymentPlan = PaymentPlan::findOrFail($id);
        $paymentPlan->update([
            'plan_type' => $request->plan_type,
            'plan_name' => $request->plan_name,
            'cost' => $request->cost,
            'about' => $request->about,
            'duration' => $request->duration,
            'num_searches' => $request->num_searches, // Update the new field
        ]);

        return redirect()->back()->with('success', 'Payment plan updated successfully.');
    }


    public function paymentplansdestroy($id)
    {
        $paymentPlan = PaymentPlan::findOrFail($id);
        $paymentPlan->delete();

        return redirect()->back()->with('success', 'Payment plan deleted successfully.');
    }


}
