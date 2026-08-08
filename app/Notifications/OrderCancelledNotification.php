<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public ServiceRequest $serviceRequest) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $services = is_array($this->serviceRequest->serviceName)
            ? implode(', ', $this->serviceRequest->serviceName)
            : implode(', ', json_decode($this->serviceRequest->serviceName, true) ?? []);

        return [
            'type' => 'order_cancelled',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'The ' . $services . ' job in ' . $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence . ' has been cancelled by the client. Your slot is now free for other bookings.',
            'location' => $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence,
            'service' => $services,
        ];
    }
}
