<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StaffAcceptedOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $staff;
    protected $serviceRequestId;
    protected $serviceType;

    public function __construct(User $staff, $serviceRequestId, string $serviceType = 'service')
    {
        $this->staff = $staff;
        $this->serviceRequestId = $serviceRequestId;
        $this->serviceType = $serviceType;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $specialties = is_array($this->staff->specialties)
            ? implode(', ', $this->staff->specialties)
            : ($this->staff->specialties ?? 'N/A');

        return (new MailMessage)
            ->subject('Staff Has Accepted Your Service Request - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! A staff member has accepted your service request.')
            ->line('**Staff Details:**')
            ->line('- **Name:** ' . $this->staff->name . ' ' . ($this->staff->last_name ?? ''))
            ->line('- **Phone:** ' . ($this->staff->phone ?? 'N/A'))
            ->line('- **Email:** ' . $this->staff->email)
            ->line('- **Specialties:** ' . $specialties)
            ->line('- **Experience:** ' . ($this->staff->years_of_experience ?? 'N/A') . ' years')
            ->action('View Dashboard', url('/dashboard'))
            ->line('You can view more details and manage your booking from your dashboard.')
            ->line('Thank you for using Our House Help!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'staff_accepted_offer',
            'service_request_id' => $this->serviceRequestId,
            'service_type' => $this->serviceType,
            'staff_id' => $this->staff->id,
            'message' => $this->staff->name . ' ' . ($this->staff->last_name ?? '') . ' has accepted your service request.',
            'staff_name' => $this->staff->name . ' ' . ($this->staff->last_name ?? ''),
            'staff_phone' => $this->staff->phone ?? 'N/A',
            'staff_email' => $this->staff->email,
        ];
    }
}
