<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CallRequestNotification extends Notification
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
            ->subject('Call Request from Client - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A client has requested a call with their selected staff before proceeding to payment.')
            ->line('**Details:**')
            ->line('- **Client:** ' . $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name)
            ->line('- **Phone:** ' . $this->serviceRequest->phone)
            ->line('- **Selected Staff:** ' . ($this->serviceRequest->selectedStaff?->name ?? 'ID: ' . $this->serviceRequest->selected_staff_id))
            ->action('View Booking', url('/admin/bookings/' . $this->serviceRequest->id))
            ->line('Please schedule a call between the client and staff.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'call_request',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'Client ' . $this->serviceRequest->name . ' requested a call with selected staff. Please schedule.',
            'client_phone' => $this->serviceRequest->phone,
        ];
    }
}
