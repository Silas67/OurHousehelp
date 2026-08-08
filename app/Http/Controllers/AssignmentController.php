<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;

class assignmentController extends Controller
{
    public function assignHouseHelp(Request $request)
    {
        // Validate input
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'house_help_id' => 'required|exists:users,id',
            'house_help_assist_id' => 'nullable|exists:users,id',
        ]);

        // Create assignment
        Assignment::create([
            'client_id' => $request->client_id,
            'house_help_id' => $request->house_help_id,
            'house_help_assist_id' => $request->house_help_assist_id,
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'House Help assigned successfully');
    }
}
