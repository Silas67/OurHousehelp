<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function newdiscount()
    {
        return view('admin.newdiscount');
    }


    // Store a new discount
    public function postdiscount(Request $request)
    {
        $request->validate([
            'number_of_services' => 'required|numeric|min:1|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string'
        ]);

        Discount::create($request->all());

        return redirect()->back()->with('success', 'Discount created successfully!');
    }


    public function discount()
    {
        $discounts = Discount::all();
        return view('admin.discount', compact(
            'discounts'
        ));
    }


    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'number_of_services' => 'required|numeric|min:1|max:255',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        // Find the discount by ID
        $discount = Discount::findOrFail($id);

        // Update the discount with new data
        $discount->number_of_services = $request->input('number_of_services');
        $discount->discount_percent = $request->input('discount_percent');
        $discount->description = $request->input('description');

        // Save the updated discount
        $discount->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Discount updated successfully.');
    }

    public function destroy($id)
    {
        $service = Discount::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Discount deleted successfully.');
    }
}
