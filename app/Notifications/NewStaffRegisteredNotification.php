<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewStaffRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $staff;

    public function __construct(User $staff)
    {
        $this->staff = $staff;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'new_staff_registered',
            'user_id'     => $this->staff->id,
            'message'     => 'New staff registered: ' . $this->staff->name . ' ' . $this->staff->last_name . ' (' . $this->staff->email . ').',
            'staff_name'  => $this->staff->name . ' ' . $this->staff->last_name,
            'email'       => $this->staff->email,
            'phone'       => $this->staff->phone,
            'state'       => $this->staff->state_of_residence,
            'applicant_type' => $this->staff->applicant_type,
        ];
    }
}
