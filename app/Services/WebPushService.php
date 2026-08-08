<?php
// app/Services/WebPushService.php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'    => config('services.vapid.subject'),
                'publicKey'  => config('services.vapid.public_key'),
                'privateKey' => config('services.vapid.private_key'),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setReuseVAPIDHeaders(true);
    }

    public function sendToUser(int $userId, string $title, string $body, string $url = '/'): void
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) return;

        foreach ($subscriptions as $sub) {
            $this->webPush->queueNotification(
                Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $sub->p256dh_key,
                        'auth'   => $sub->auth_key,
                    ],
                ]),
                json_encode([
                    'title' => $title,
                    'body'  => $body,
                    'url'   => $url,
                    'icon'  => '/images/logo-icon.png',
                    'badge' => '/images/logo-icon.png',
                ])
            );
        }

        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                Log::info("WebPush: Delivered to user {$userId}");
            } elseif ($report->isSubscriptionExpired()) {
                // Remove stale subscription
                PushSubscription::where('endpoint', $endpoint)->delete();
                Log::info("WebPush: Removed expired subscription for user {$userId}");
            } else {
                Log::warning("WebPush: Failed for user {$userId} — " . $report->getReason());
            }
        }
    }
}
