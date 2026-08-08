<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceRequestApprovedNotification extends Notification implements ShouldQueue
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

    private function getServiceNames(): string
    {
        $services = $this->serviceRequest->serviceName;
        // Handle double-encoded JSON
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
            ->subject('New Service Request Available - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new service request has been approved and is available for you.')
            ->line('**Service Details:**')
            ->line('- **Service(s):** ' . $serviceNames)
            ->line('- **Client:** ' . $clientName)
            ->line('- **Location:** ' . $this->serviceRequest->state_of_residence)
            ->line('- **Address:** ' . $this->serviceRequest->address)
            ->line('- **Package:** ' . $this->serviceRequest->package)
            ->line('- **Working Hours:** ' . $this->serviceRequest->working_hour)
            ->action('View & Accept/Decline', url('/liveout-service-requests'))
            ->line('Please log in to view the full details and accept or decline this offer.')
            ->line('Thank you for being part of Our House Help!');
    }

    public function toArray($notifiable): array
    {
        $serviceNames = $this->getServiceNames();

        return [
            'type' => 'service_request_approved',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'A new service request for "' . $serviceNames . '" is available. Check your profile to accept or reject the offer.',
            'client_name' => $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name,
            'location' => $this->serviceRequest->state_of_residence,
            'address' => $this->serviceRequest->address,
            'package' => $this->serviceRequest->package,
            'working_hour' => $this->serviceRequest->working_hour,
        ];
    }
}
