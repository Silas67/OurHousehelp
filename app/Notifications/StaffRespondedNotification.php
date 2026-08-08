<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffRespondedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $staff;
    protected int $serviceRequestId;
    protected string $response;

    public function __construct(User $staff, int $serviceRequestId, string $response)
    {
        $this->staff = $staff;
        $this->serviceRequestId = $serviceRequestId;
        $this->response = $response;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $verb = ucfirst($this->response);
        return (new MailMessage)
            ->subject("Staff Has {$verb} a Shortlist Offer - Our House Help")
            ->greeting('Hello Admin,')
            ->line("A staff member has {$this->response} a shortlist offer.")
            ->line('**Staff:** ' . $this->staff->name . ' ' . ($this->staff->last_name ?? ''))
            ->line('**Response:** ' . $verb)
            ->line('**Booking ID:** #' . $this->serviceRequestId)
            ->action('View Booking', url('/admin/bookings/' . $this->serviceRequestId));
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'staff_responded',
            'service_request_id' => $this->serviceRequestId,
            'staff_id' => $this->staff->id,
            'response' => $this->response,
            'message' => $this->staff->name . ' ' . ($this->staff->last_name ?? '') . ' has ' . $this->response . ' a shortlist offer for booking #' . $this->serviceRequestId . '.',
        ];
    }
}
