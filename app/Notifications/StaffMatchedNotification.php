<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use App\Models\StaffShortlist;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffMatchedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $serviceRequest;
    protected $shortlist;

    public function __construct(ServiceRequest $serviceRequest, StaffShortlist $shortlist)
    {
        $this->serviceRequest = $serviceRequest;
        $this->shortlist = $shortlist;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Service Request Matched - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been matched to a new service request based on your profile.')
            ->line('**Request Details:**')
            ->line('- **Location:** ' . $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence)
            ->line('- **Start Date:** ' . ($this->serviceRequest->preferred_start_date ? $this->serviceRequest->preferred_start_date->format('M d, Y') : 'TBD'))
            ->line('- **Days/Week:** ' . $this->serviceRequest->days_per_week)
            ->action('Accept or Decline', url('/staff-shortlist-offers'))
            ->line('Please log in to review and accept or decline this offer.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'staff_matched',
            'service_request_id' => $this->serviceRequest->id,
            'shortlist_id' => $this->shortlist->id,
            'message' => 'You have been matched to a new service request in ' . $this->serviceRequest->preferred_city . '. Accept or decline the offer.',
            'location' => $this->serviceRequest->preferred_city . ', ' . $this->serviceRequest->state_of_residence,
        ];
    }
}
