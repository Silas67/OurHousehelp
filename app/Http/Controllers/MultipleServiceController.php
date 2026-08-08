<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultipleService;
use App\Models\Service;

class MultipleServiceController extends Controller
{
    public function store(Request $request)
    {
        // Validate the multipleservice array
        $validated = $request->validate([
            'multipleservice' => ['required', 'array', 'min:2'], // Ensure at least 2 items are selected
            'multipleservice.*' => ['nullable', 'string'], // Allow nullable fields for unselected dropdowns
            'cost' => ['required', 'numeric', 'min:0'], // Validate cost
            'description' => ['nullable', 'string'], // Optional description
            'working_hours' => 'required|integer|min:1', // At least 1 working hour
        ]);

        // Filter out empty values from the multipleservice array
        $validated['multipleservice'] = array_filter($validated['multipleservice'], function ($value) {
            return !empty($value);
        });

        // Proceed to store the data (example code below)
        // Store in your MultipleService model or any logic needed
        MultipleService::create([
            'services' => json_encode($validated['multipleservice']),
            'cost' => $validated['cost'],
            'working_hours' => $validated['working_hours'],
            'description' => $validated['description'],
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', 'Multiple services created successfully.');
    }


    // Display all multiple services
    public function manageMultipleServices()
    {
        $multipleServices = MultipleService::all();

        // Decode the services JSON field into an array
        foreach ($multipleServices as $multipleService) {
            $multipleService->services = json_decode($multipleService->services);
        }

        return view('admin.manageMultipleServices', compact('multipleServices'));
    }



    public function edit($id)
    {
        // Fetch the MultipleService by its ID
        $multipleService = MultipleService::findOrFail($id);

        // Fetch all services for the dropdown
        $services = Service::all();

        // Pass the service data to the edit view
        return view('admin.editMultipleService', compact('multipleService', 'services'));
    }



    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'multipleservice' => 'required|array|min:2', // At least two services should be selected
            'cost' => 'required|numeric|min:0',
            'working_hours' => 'required|integer|min:1', // At least 1 working hour
            'description' => 'nullable|string|max:500',
        ]);

        // Find the MultipleService record by ID
        $multipleService = MultipleService::findOrFail($id);

        // Update the fields
        $multipleService->services = json_encode($request->multipleservice); // Store selected services as JSON
        $multipleService->cost = $request->cost;
        $multipleService->working_hours = $request->working_hours;
        $multipleService->description = $request->description;
        $multipleService->save();

        // Redirect or return a response after updating
        return redirect()->back()->with('success', 'Multiple service updated successfully!');
    }

    public function destroy($id)
    {
        // Find the MultipleService record by ID
        $multipleService = MultipleService::findOrFail($id);

        // Delete the service
        $multipleService->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Multiple Service deleted successfully!');
    }
}
