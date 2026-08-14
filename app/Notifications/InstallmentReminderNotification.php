<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstallmentReminderNotification extends Notification
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
            ->subject('Payment Balance Reminder - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that your installment balance is due.')
            ->line('**Payment Details:**')
            ->line('- **Remaining Amount:** N' . number_format($this->serviceRequest->amount_remaining))
            ->line('- **Due Date:** ' . ($this->serviceRequest->balance_due_date ? $this->serviceRequest->balance_due_date->format('M d, Y') : 'Before staff resumes'))
            ->action('Make Payment', url('/booking/payment/' . $this->serviceRequest->id))
            ->line('Please complete your payment to ensure your staff resumes on schedule.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'installment_reminder',
            'service_request_id' => $this->serviceRequest->id,
            'message' => 'Your installment balance of N' . number_format($this->serviceRequest->amount_remaining) . ' is due. Please complete payment.',
            'amount_remaining' => $this->serviceRequest->amount_remaining,
        ];
    }
}
