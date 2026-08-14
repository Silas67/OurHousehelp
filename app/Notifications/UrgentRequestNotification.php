<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UrgentRequestNotification extends Notification
{
    use Queueable;

    protected ServiceRequest $serviceRequest;

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
            ->subject('URGENT: Client Needs Staff Immediately - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A client has submitted an URGENT staff request.')
            ->line('**Client:** ' . $this->serviceRequest->name . ' ' . ($this->serviceRequest->last_name ?? ''))
            ->line('**Phone:** ' . ($this->serviceRequest->phone ?? 'N/A'))
            ->line('**Email:** ' . ($this->serviceRequest->email ?? 'N/A'))
            ->line('**Location:** ' . $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence)
            ->line('**Services:** ' . ($this->serviceRequest->details ?? 'Not specified'))
            ->line('**Amount:** ₦' . number_format($this->serviceRequest->grand_cost, 2))
            ->action('View Booking', url('/admin/bookings/' . $this->serviceRequest->id))
            ->line('Please contact this client immediately.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'urgent_request',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'URGENT: ' . $this->serviceRequest->name . ' ' . ($this->serviceRequest->last_name ?? '') . ' needs staff immediately.',
            'client_phone' => $this->serviceRequest->phone ?? 'N/A',
            'location' => $this->serviceRequest->preferred_city,
            'amount' => '₦' . number_format($this->serviceRequest->grand_cost, 2),
        ];
    }
}
