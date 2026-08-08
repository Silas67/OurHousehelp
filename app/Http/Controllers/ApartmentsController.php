<?php

namespace App\Http\Controllers;

use App\Models\Apartments;
use Illuminate\Http\Request;

class ApartmentsController extends Controller
{
    public function newapartments()
    {
        return view('admin.newapartments');
    }


    public function newapartmentsstore(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'about' => 'required|string',
        ]);

        // Create a new one-time service
        Apartments::create([
            'name' => $request->input('name'),
            'cost' => $request->input('cost'),
            'about' => $request->input('about'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Created successfully.');
    }


    // Display a list of all apartments
    public function apartments()
    {
        // Fetch all apartments from the database
        $apartments = Apartments::all();

        return view('admin.apartments', compact('apartments'));
    }



    public function update(Request $request, $id)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'about' => 'required|string',
        ]);


        // Find the service entry by ID and update
        $Apartments = Apartments::findOrFail($id);
        $Apartments->name = $validated['name'];
        $Apartments->cost = $validated['cost'];
        if (isset($validated['about'])) {
            $Apartments->about = $validated['about'];
        }
        $Apartments->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Updated successfully!');
    }


    public function destroy($id)
    {
        $Apartments = Apartments::findOrFail($id);
        $Apartments->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}
