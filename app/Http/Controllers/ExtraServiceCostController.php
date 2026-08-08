<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExtraServiceCost;
use Illuminate\Http\Request;

class ExtraServiceCostController extends Controller
{
    public function index()
    {
        $extraService = ExtraServiceCost::first();

        // Ensure we always have an object, even if empty
        if (!$extraService) {
            $extraService = new ExtraServiceCost();
        }

        return view('admin.extra-service-cost', compact('extraService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        ExtraServiceCost::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()->back()->with('success', 'Extra service cost saved successfully.');
    }
}
