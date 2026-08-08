<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceType;

class ServiceTypeController extends Controller
{
    // Display all service types
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('id')->get();
        return view('admin.service-types', compact('serviceTypes'));
    }

    // Update cost only (no edit page)
    public function update(Request $request, $id)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
        ]);

        $serviceType = ServiceType::findOrFail($id);

        // Prevent accidental edits to protected fields
        $serviceType->update(['cost' => $request->cost]);

        return redirect()
            ->route('admin.service-types.index')
            ->with('success', "Cost for '{$serviceType->name}' updated successfully!");
    }
}
