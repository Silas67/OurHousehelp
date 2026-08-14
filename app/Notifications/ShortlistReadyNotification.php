<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShortlistReadyNotification extends Notification
{
    use Queueable;

    protected $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Staff Shortlist is Ready - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! We have found qualified staff matching your request.')
            ->line('Your shortlist is ready for review. You can now view staff profiles and select your preferred staff.')
            ->action('View Shortlist', url('/booking/shortlist/' . $this->serviceRequest->id))
            ->line('Thank you for choosing Our House Help!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'shortlist_ready',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'Your staff shortlist is ready! Review profiles and select your preferred staff.',
            'url' => '/booking/shortlist/' . $this->serviceRequest->id,
        ];
    }
}
