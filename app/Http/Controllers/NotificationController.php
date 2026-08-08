<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        if (in_array($user->user_role, [1, 2])) {
            return view('notifications.admin_notifications', compact('notifications'));
        }

        if ($user->account_type === 'applicant' || $user->account_type === 'staff') {
            return view('notifications.staff_notifications', compact('notifications'));
        }

        return view('notifications.client_notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
