<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Service; // Assuming the services table is used for hours_of_work
use Illuminate\Http\Request;

class PackageController extends Controller
{

public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100', // Validate percentage
            'days_per_week' => 'required|integer|min:1|max:7',
            'description' => 'nullable|string|max:150',
            'point1' => 'nullable|string|max:150',
            'point2' => 'nullable|string|max:150',
            'point3' => 'nullable|string|max:150',
            'working_hours_start_1' => 'nullable|date_format:H:i',
            'working_hours_start_2' => 'nullable|date_format:H:i',
            'working_hours_start_3' => 'nullable|date_format:H:i',
            'working_hours_start_4' => 'nullable|date_format:H:i',
            'working_hours_start_5' => 'nullable|date_format:H:i',
        ]);

        // Automatically calculate `working_hours_end_$i` if `hours_of_work` is available
        $service = Service::find($request->service_id); // Assume `service_id` is sent with the request
        $hoursOfWork = $service ? $service->hours_of_work : null;

        for ($i = 1; $i <= 5; $i++) {
            $startKey = "working_hours_start_{$i}";
            $endKey = "working_hours_end_{$i}";

            if ($request->filled($startKey)) {
                $startTime = new \DateTime($request->$startKey);

                if ($hoursOfWork) {
                    $endTime = clone $startTime;
                    $endTime->modify("+{$hoursOfWork} hours");
                    $validated[$endKey] = $endTime->format('H:i');
                } else {
                    $validated[$endKey] = null; // If no hours_of_work, leave it null
                }
            } else {
                $validated[$endKey] = null; // No start time provided
            }
        }

        // Create the package
        Package::create($validated);

        return redirect()->back()->with('success', 'Package created successfully.');
    }

    // public function update(Request $request, $id)
    // {
    //     // Validate incoming data
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'cost' => 'nullable|numeric|min:0',
    //         'days_per_week' => 'required|integer|min:1|max:7',
    //         'description' => 'nullable|string|max:150',
    //         'point1' => 'nullable|string|max:150',
    //         'point2' => 'nullable|string|max:150',
    //         'point3' => 'nullable|string|max:150',
    //         'working_hours_start_1' => 'nullable|date_format:H:i',
    //         'working_hours_start_2' => 'nullable|date_format:H:i',
    //         'working_hours_start_3' => 'nullable|date_format:H:i',
    //         'working_hours_start_4' => 'nullable|date_format:H:i',
    //         'working_hours_start_5' => 'nullable|date_format:H:i',
    //     ]);

    //     // Find the package
    //     $package = Package::findOrFail($id);

    //     // Automatically calculate `working_hours_end_$i` based on hours_of_work
    //     $service = Service::find($request->service_id); // Assume `service_id` is sent with the request
    //     $hoursOfWork = $service ? $service->hours_of_work : null;

    //     for ($i = 1; $i <= 5; $i++) {
    //         $startKey = "working_hours_start_{$i}";
    //         $endKey = "working_hours_end_{$i}";

    //         if ($request->filled($startKey)) {
    //             $startTime = new \DateTime($request->$startKey);

    //             if ($hoursOfWork) {
    //                 $endTime = clone $startTime;
    //                 $endTime->modify("+{$hoursOfWork} hours");
    //                 $validated[$endKey] = $endTime->format('H:i');
    //             } else {
    //                 $validated[$endKey] = null; // If no hours_of_work, leave it null
    //             }
    //         } else {
    //             $validated[$endKey] = null; // No start time provided
    //         }
    //     }

    //     // Update the package
    //     $package->update($validated);

    //     return redirect()->back()->with('success', 'Package updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'days_per_week' => 'required|integer|min:1|max:7',
            'description' => 'nullable|string|max:150',
            'point1' => 'nullable|string|max:150',
            'point2' => 'nullable|string|max:150',
            'point3' => 'nullable|string|max:150',
            'working_hours_start_1' => 'nullable|date_format:H:i',
            'working_hours_start_2' => 'nullable|date_format:H:i',
            'working_hours_start_3' => 'nullable|date_format:H:i',
            'working_hours_start_4' => 'nullable|date_format:H:i',
            'working_hours_start_5' => 'nullable|date_format:H:i',
        ]);

        // Find the package
        $package = Package::findOrFail($id);

        // Automatically calculate `working_hours_end_$i` based on hours_of_work
        $service = Service::find($request->service_id); // Assume `service_id` is sent with the request
        $hoursOfWork = $service ? $service->hours_of_work : null;

        for ($i = 1; $i <= 5; $i++) {
            $startKey = "working_hours_start_{$i}";
            $endKey = "working_hours_end_{$i}";

            if ($request->filled($startKey)) {
                try {
                    $startTime = new \DateTime($request->$startKey);

                    if ($hoursOfWork) {
                        $endTime = clone $startTime;
                        $endTime->modify("+{$hoursOfWork} hours");
                        $validated[$endKey] = $endTime->format('H:i');
                    } else {
                        $validated[$endKey] = null; // If no hours_of_work, leave it null
                    }
                } catch (\Exception $e) {
                    $validated[$endKey] = null; // Catch invalid times and leave null
                }
            } else {
                $validated[$endKey] = null; // No start time provided
            }
        }

        // Update the package
        $package->update($validated);

        return redirect()->back()->with('success', 'Package updated successfully.');
    }


    public function destroy($id)
    {
        // Find and delete the package
        $package = Package::findOrFail($id);
        $package->delete();

        return redirect()->route('dashboard')->with('success', 'Package deleted successfully.');
    }
}
