<?php

namespace App\Notifications;

use App\Models\ReplacementRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplacementRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected ReplacementRequest $replacementRequest;

    public function __construct(ReplacementRequest $replacementRequest)
    {
        $this->replacementRequest = $replacementRequest;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $client = $this->replacementRequest->user;
        $sr = $this->replacementRequest->serviceRequest;

        return (new MailMessage)
            ->subject('Staff Replacement Requested - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A client has requested a staff replacement.')
            ->line('**Client:** ' . ($client?->name ?? 'N/A'))
            ->line('**Booking ID:** #' . $sr?->id)
            ->line('**Reason:** ' . $this->replacementRequest->reason)
            ->action('View Booking', url('/admin/bookings/' . $sr?->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'replacement_request',
            'replacement_request_id' => $this->replacementRequest->id,
            'service_request_id' => $this->replacementRequest->service_request_id,
            'message' => 'Client has requested a staff replacement for booking #' . $this->replacementRequest->service_request_id . '.',
        ];
    }
}
