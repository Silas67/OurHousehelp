<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewClientRegisteredNotification extends Notification
{
    use Queueable;

    protected User $client;

    public function __construct(User $client)
    {
        $this->client = $client;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'new_client_registered',
            'user_id'     => $this->client->id,
            'message'     => 'New client registered: ' . $this->client->name . ' (' . $this->client->email . ').',
            'client_name' => $this->client->name,
            'email'       => $this->client->email,
            'phone'       => $this->client->phone,
        ];
    }
}
