<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\Reply;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share unread message count across all views
        View::composer('*', function ($view) {
            $user = Auth::user();

            if ($user) {
                // Check if the user is an admin (role 1 or 2)
                if (in_array(Auth::user()->role, [1, 2])) {
                    $unreadMessagesCount = ContactMessage::where('read_status', false)->count();
                } else {
                    // For clients, count only unread replies related to them
                    $unreadMessagesCount = Reply::where('read_status', false)
                        ->where('user_id', $user->id) // Assuming 'user_id' is the field in replies for clients
                        ->count();
                }

                // Share unreadMessagesCount with all views
                $view->with('unreadMessagesCount', $unreadMessagesCount);

                // Share unread notifications count with all views
                $unreadNotificationsCount = $user->unreadNotifications()->count();
                $view->with('unreadNotificationsCount', $unreadNotificationsCount);
            }
        });
    }
}
