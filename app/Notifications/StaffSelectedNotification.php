<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffSelectedNotification extends Notification
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
            ->subject('You Have Been Selected! - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Congratulations! A client has selected you for their service request.')
            ->line('**Request Details:**')
            ->line('- **Location:** ' . $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence)
            ->line('- **Start Date:** ' . ($this->serviceRequest->preferred_start_date ? $this->serviceRequest->preferred_start_date->format('M d, Y') : 'TBD'))
            ->line('You will receive the client\'s full details once payment is confirmed.')
            ->line('Thank you for being part of Our House Help!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'staff_selected',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'A client has selected you for their service request! Details will follow after payment confirmation.',
        ];
    }
}
