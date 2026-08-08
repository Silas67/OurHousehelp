<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OneTimeService;

class OneTimeServiceController extends Controller
{

    public function newonetimeservicestore(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'apartment_size' => 'required|string|max:50',
            'price_min' => 'required|numeric|min:0',
            'price_max' => 'required|numeric|min:0|gte:price_min',
            'details' => 'nullable|string|max:255',
        ]);

        // Create a new one-time service
        OneTimeService::create([
            'apartment_size' => $request->input('apartment_size'),
            'price_min' => $request->input('price_min'),
            'price_max' => $request->input('price_max'),
            'details' => $request->input('details'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'One-time service created successfully.');
    }


    public function onetimeservice(Request $request)
    {
        $services = OneTimeService::all();
        return view('admin.onetimeservice', compact(
            'services'
        ));
    }



    public function update(Request $request, $id)
    {
        // Validate the form data
        $validated = $request->validate([
            'price_min' => 'required|numeric|min:0',
            'price_max' => 'required|numeric|min:0|gte:price_min',
            'details' => 'nullable|string|max:255', // Add this if you want to allow updating details
        ]);

        // Find the service entry by ID and update
        $service = OneTimeService::findOrFail($id);
        $service->price_min = $validated['price_min'];
        $service->price_max = $validated['price_max'];
        if (isset($validated['details'])) {
            $service->details = $validated['details'];
        }
        $service->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Service updated successfully!');
    }


    public function destroy($id)
    {
        $service = OneTimeService::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}
