<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ServiceRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $serviceRequest;
    protected $newStatus;

    public function __construct(ServiceRequest $serviceRequest, string $newStatus)
    {
        $this->serviceRequest = $serviceRequest;
        $this->newStatus = $newStatus;
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

        $statusLabel = ucfirst(str_replace('_', ' ', $this->newStatus));

        $mailMessage = (new MailMessage)
            ->subject('Service Request Update - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your service request status has been updated.')
            ->line('**Service(s):** ' . $serviceNames)
            ->line('**New Status:** ' . $statusLabel);

        switch ($this->newStatus) {
            case 'confirmed':
                $mailMessage->line('Your service request has been confirmed. Our staff will be notified and you will hear from them soon.');
                break;
            case 'active':
                $mailMessage->line('Your service request is now active. A staff member has been assigned to your request.');
                break;
            case 'payment_completed':
                $mailMessage->line('Your live-out service payment has been received. Your service will begin as scheduled.');
                break;
            case 'completed':
                $mailMessage->line('Your service request has been marked as completed. Thank you for using Our House Help!');
                break;
            case 'cancelled':
                $mailMessage->line('Your service request has been cancelled. Please contact support if you have questions.');
                break;
        }

        return $mailMessage
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for using Our House Help!');
    }

    public function toArray($notifiable): array
    {
        $serviceNames = $this->getServiceNames();

        $statusLabel = ucfirst(str_replace('_', ' ', $this->newStatus));

        return [
            'type' => 'service_request_status_update',
            'service_request_id' => $this->serviceRequest->id,
            'new_status' => $this->newStatus,
            'message' => 'Your service request for "' . $serviceNames . '" has been updated to: ' . $statusLabel . '.',
            'services' => $serviceNames,
        ];
    }
}
