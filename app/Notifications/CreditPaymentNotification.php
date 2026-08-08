<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreditPaymentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $client,
        public int $credits,
        public string $amount,
        public ?string $receiptPath = null
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'         => 'credit_payment',
            'client_id'    => $this->client->id,
            'client_name'  => $this->client->name . ' ' . $this->client->last_name,
            'client_email' => $this->client->email,
            'client_phone' => $this->client->phone,
            'credits'      => $this->credits,
            'amount'       => $this->amount,
            'receipt_path' => $this->receiptPath,
            'message'      => $this->client->name . ' has submitted payment proof for ' . $this->credits . ' credit(s) — ₦' . $this->amount . '. Please verify and add credits.',
            'action_url'   => '/admin/credits',
        ];
    }
}
