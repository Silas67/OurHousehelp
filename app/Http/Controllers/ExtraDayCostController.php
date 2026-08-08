<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraDayCost;

class ExtraDayCostController extends Controller
{
    // Show the management page
    public function index()
    {
        $extraDayCost = ExtraDayCost::first(); // Only one record expected
        return view('admin.extra-day-costs', compact('extraDayCost'));
    }

    // Create or update the record
    public function createOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        // If record exists, update; otherwise, create a new one
        $record = ExtraDayCost::first();

        if ($record) {
            $record->update($validated);
            $message = 'Extra day cost updated successfully!';
        } else {
            ExtraDayCost::create($validated);
            $message = 'Extra day cost created successfully!';
        }

        return redirect()
            ->route('admin.extra-day-costs.index')
            ->with('success', $message);
    }
}
