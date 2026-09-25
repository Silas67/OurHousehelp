<?php

namespace App\Notifications;

use App\Models\CorporateInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCorporateInquiryNotification extends Notification
{
    use Queueable;

    public function __construct(protected CorporateInquiry $inquiry)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New corporate staffing inquiry — ' . $this->inquiry->company_name)
            ->greeting('New corporate inquiry')
            ->line($this->inquiry->company_name . ' is looking for ' . $this->inquiry->staff_count . ' staff member(s).')
            ->line('Contact: ' . $this->inquiry->contact_name . ' — ' . $this->inquiry->work_email . ' — ' . $this->inquiry->phone)
            ->line('Staff types requested: ' . implode(', ', $this->inquiry->staff_types))
            ->when($this->inquiry->notes, fn ($mail) => $mail->line('Notes: ' . $this->inquiry->notes))
            ->action('View in Admin', url('/admin/corporate-inquiries/' . $this->inquiry->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'corporate_inquiry_id' => $this->inquiry->id,
            'company_name' => $this->inquiry->company_name,
            'staff_count' => $this->inquiry->staff_count,
        ];
    }
}
