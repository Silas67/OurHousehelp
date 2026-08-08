<?php
// app/Http/Controllers/PushSubscriptionController.php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'          => 'required|string',
            'keys.p256dh'       => 'required|string',
            'keys.auth'         => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'endpoint' => $request->endpoint,
            ],
            [
                'p256dh_key' => $request->input('keys.p256dh'),
                'auth_key'   => $request->input('keys.auth'),
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['success' => true]);
    }
}
