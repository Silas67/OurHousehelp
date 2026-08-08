<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceCharge;
use Illuminate\Http\Request;

class ServiceChargeController extends Controller
{
    public function index()
    {
        $charge = ServiceCharge::first();
        return view('admin.service_charge', compact('charge'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
        ]);

        $charge = ServiceCharge::firstOrCreate(['id' => 1]);
        $charge->update([
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
        ]);

        return back()->with('success', 'Service charge updated successfully.');
    }
}
