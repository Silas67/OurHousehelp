<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send($to, $message)
    {
        // FOR NOW (no Termii yet)
        Log::info("SMS to {$to}: {$message}");

        // When ready, replace with Termii:
        /*
        Http::post('https://api.ng.termii.com/api/sms/send', [
            'to' => $to,
            'from' => config('services.termii.sender'),
            'sms' => $message,
            'type' => 'plain',
            'channel' => 'generic',
            'api_key' => config('services.termii.key'),
        ]);
        */
    }
}
