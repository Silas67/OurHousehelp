<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TermiiService
{
    protected string $apiKey;
    protected string $senderId;
    protected string $channel;
    protected string $baseUrl = 'https://v3.api.termii.com/api/sms/send';

    public function __construct()
    {
        $this->apiKey   = config('services.termii.api_key');
        $this->senderId = config('services.termii.sender_id');
        $this->channel  = config('services.termii.channel');
    }

    public function send(string $to, string $message): bool
    {
        // Normalize Nigerian number to international format
        $to = $this->normalizePhone($to);

        try {
            $response = Http::post($this->baseUrl, [
                'to'          => $to,
                'from'        => $this->senderId,
                'sms'         => $message,
                'type'        => 'plain',
                'channel'     => $this->channel,
                'api_key'     => $this->apiKey,
            ]);

            if ($response->successful()) {
                Log::info('Termii SMS sent', ['to' => $to, 'status' => $response->json()]);
                return true;
            }

            Log::error('Termii SMS failed', ['to' => $to, 'response' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error('Termii SMS exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '234' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '234')) {
            $phone = '234' . $phone;
        }

        return $phone;
    }
}
