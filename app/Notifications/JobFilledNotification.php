<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobFilledNotification extends Notification implements ShouldQueue
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
            'type' => 'job_filled',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'The ' . $services . ' job in ' . $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence . ' has been filled by another staff. Keep an eye out for new job opportunities!',
            'location' => $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence,
            'service' => $services,
        ];
    }
}
