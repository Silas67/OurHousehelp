<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApartmentOrder;

class ApartmentOrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'first_name'                => 'required|string|max:255',
            'last_name'                 => 'required|string|max:255',
            'email'                     => 'required|email|max:255',
            'phone'                     => 'required|string|max:15',
            'state_of_residence'        => 'required|string',
            'address'                   => 'required|string',
            'landmark'                  => 'nullable|string',
            'apartment_type'            => 'required|string',
            'service_type'              => 'required|string',
            'price'                     => 'required|string',
        ]);

        // Create a new ApartmentOrder
        $apartmentOrder = new ApartmentOrder();
        $apartmentOrder->first_name = $request->input('first_name');
        $apartmentOrder->last_name = $request->input('last_name');
        $apartmentOrder->email = $request->input('email');
        $apartmentOrder->phone = $request->input('phone');
        $apartmentOrder->state_of_residence = $request->input('state_of_residence');
        $apartmentOrder->address = $request->input('address');
        $apartmentOrder->landmark = $request->input('landmark');
        $apartmentOrder->apartment_type = $request->input('apartment_type');
        $apartmentOrder->service_type = $request->input('service_type');
        $apartmentOrder->price = $request->input('price');

        try {
            // Save the order and redirect to the receipt page with the order data
            $apartmentOrder->save();
            return redirect()->route('receipt')->with('order', $apartmentOrder);
        } catch (\Exception $e) {
            // Handle any errors and redirect back with error message and input data
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }
}
