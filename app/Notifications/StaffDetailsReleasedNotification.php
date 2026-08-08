<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffDetailsReleasedNotification extends Notification implements ShouldQueue
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
            ->subject('Staff Details Released - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payment has been approved! Your staff\'s full contact details are now available.')
            ->line('You can now view their phone number, email, and full profile.')
            ->action('View Staff Details', url('/booking/staff-details/' . $this->serviceRequest->id))
            ->line('Thank you for choosing Our House Help!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'staff_details_released',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'Your payment has been approved! Staff contact details are now available.',
            'url' => '/booking/staff-details/' . $this->serviceRequest->id,
        ];
    }
}
