<?php

namespace App\Http\Controllers;

use App\Models\BonusUsage;
use App\Models\ServiceRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function settings(Request $request)
    {
        $bonus = Setting::get('liveout_bonus_skilled_slots', 0);
        $usages = BonusUsage::latest()->paginate(20);


        return view('admin.settings', compact(
            'bonus',
            'usages'
        ));
    }



    public function updateLiveoutBonus(Request $request)
    {
        $request->validate([
            'bonus' => 'required|integer|min:0'
        ]);

        Setting::set('liveout_bonus_skilled_slots', $request->bonus);

        return redirect()->back()->with('success', 'Bonus slot value updated successfully!');
    }
}
