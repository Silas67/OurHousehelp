<?php

namespace App\Http\Controllers;

use App\Models\ManagementPlan;
use App\Models\Package;
use App\Models\Service;
use App\Models\ServiceCharge;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $serviceCharge = ServiceCharge::first();
        $managementPlans = ManagementPlan::orderBy('sort_order')->get();
        $services = Service::orderBy('service_name')->get();
        $packages = Package::orderBy('name')->get();

        return view('admin.pricing', compact(
            'serviceCharge',
            'managementPlans',
            'services',
            'packages'
        ));
    }

    public function updateServiceCharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        $charge = ServiceCharge::first();

        if ($charge) {
            $charge->update([
                'amount' => $request->amount,
                'currency' => $request->currency,
            ]);
        } else {
            ServiceCharge::create([
                'amount' => $request->amount,
                'currency' => $request->currency,
            ]);
        }

        return redirect()->route('admin.pricing')
            ->with('success', 'Platform access fee updated successfully!');
    }

    public function updateManagementPlan(Request $request, $id)
    {
        $request->validate([
            'base_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'platform_markup' => 'nullable|numeric|min:0|max:100',
            'free_replacements' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'name' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
        ]);

        $plan = ManagementPlan::findOrFail($id);

        $updateData = [
            'base_price' => $request->base_price ?? $plan->base_price,
            'max_price' => $request->max_price ?? $plan->max_price,
            'free_replacements' => $request->free_replacements ?? $plan->free_replacements,
            'is_active' => $request->has('is_active') ? $request->is_active : $plan->is_active,
        ];

        // Platform markup
        if ($request->has('platform_markup') && $plan->slug !== 'client-managed') {
            $updateData['platform_markup'] = $request->platform_markup;
        }

        // Optional fields from management-plans blade
        if ($request->has('name')) $updateData['name'] = $request->name;
        if ($request->has('label')) $updateData['label'] = $request->label;
        if ($request->has('sort_order')) $updateData['sort_order'] = $request->sort_order;
        if ($request->has('features')) {
            $updateData['features_list'] = array_filter(explode("\n", $request->features));
        }

        $plan->update($updateData);

        return redirect()->back()
            ->with('success', "Management plan '{$plan->name}' updated successfully!");
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'base_cost' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update(['base_cost' => $request->base_cost]);

        return redirect()->route('admin.pricing')
            ->with('success', "Service '{$service->service_name}' cost updated successfully!");
    }

    public function updatePackage(Request $request, $id)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
        ]);

        $package = Package::findOrFail($id);
        $package->update(['cost' => $request->cost]);

        return redirect()->route('admin.pricing')
            ->with('success', "Package '{$package->name}' cost updated successfully!");
    }
}
