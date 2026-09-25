<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewServiceRequestNotification extends Notification
{
    use Queueable;

    protected $serviceRequest;

    public function construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    private function getServiceNames(): string
    {
        $services = $this->serviceRequest->serviceName;
        if (is_string($services)) {
            $decoded = json_decode($services, true);
            $services = is_array($decoded) ? $decoded : (json_decode($decoded, true) ?? [$services]);
        }
        return is_array($services) ? implode(', ', $services) : (string) $services;
    }

    public function toMail($notifiable): MailMessage
    {
        $serviceNames = $this->getServiceNames();

        $clientName = $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name;

        return (new MailMessage)
            ->subject('New Service Request Submitted - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A new service request has been submitted by a client.')
            ->line('**Client:** ' . $clientName)
            ->line('**Email:** ' . ($this->serviceRequest->email ?? 'N/A'))
            ->line('**Phone:** ' . ($this->serviceRequest->phone ?? 'N/A'))
            ->line('**Service(s):** ' . $serviceNames)
            ->line('**Location:** ' . $this->serviceRequest->state_of_residence)
            ->line('**Package:** ' . $this->serviceRequest->package)
            ->line('**Amount:** ₦' . number_format($this->serviceRequest->grand_cost, 2))
            ->action('View Service Requests', url('/admin/service-requests'))
            ->line('Please review and process this request.');
    }

    public function toArray($notifiable): array
    {
        $serviceNames = $this->getServiceNames();

        return [
            'type' => 'new_service_request',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'New service request for "' . $serviceNames . '" submitted by ' . $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name . '.',
            'client_name' => $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name,
            'location' => $this->serviceRequest->state_of_residence,
            'package' => $this->serviceRequest->package,
            'amount' => '₦' . number_format($this->serviceRequest->grand_cost, 2),
        ];
    }
}
