<?php

namespace App\Notifications;

use App\Models\BookingPayment;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingPaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $serviceRequest;
    protected $payment;

    public function __construct(ServiceRequest $serviceRequest, ?BookingPayment $payment = null)
    {
        $this->serviceRequest = $serviceRequest;
        $this->payment = $payment;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $amount = $this->payment ? number_format($this->payment->amount) : 'N/A';

        return (new MailMessage)
            ->subject('New Booking Payment Received - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A new booking payment has been submitted and requires approval.')
            ->line('**Payment Details:**')
            ->line('- **Client:** ' . $this->serviceRequest->name . ' ' . $this->serviceRequest->last_name)
            ->line('- **Amount:** N' . $amount)
            ->line('- **Plan:** ' . ucfirst($this->serviceRequest->management_plan))
            ->line('- **Method:** ' . ($this->payment ? ucfirst(str_replace('_', ' ', $this->payment->payment_method)) : 'N/A'))
            ->action('Review Payment', url('/admin/bookings/' . $this->serviceRequest->id))
            ->line('Please review and approve or reject this payment.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'booking_payment_received',
            'service_request_id' => $this->serviceRequest->id,
            'payment_id' => $this->payment?->id,
            'message' => 'Booking payment received from ' . $this->serviceRequest->name . ' for ' . ucfirst($this->serviceRequest->management_plan) . ' plan. Requires approval.',
            'amount' => $this->payment?->amount,
        ];
    }
}
