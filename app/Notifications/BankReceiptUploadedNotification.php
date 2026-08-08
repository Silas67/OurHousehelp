<?php

namespace App\Notifications;

use App\Models\RegistrationPayment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BankReceiptUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;
    protected $client;

    public function __construct(RegistrationPayment $payment, User $client)
    {
        $this->payment = $payment;
        $this->client = $client;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bank Receipt Uploaded - Pending Approval - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A client has uploaded a bank receipt for registration payment.')
            ->line('**Client:** ' . $this->client->name . ' ' . ($this->client->last_name ?? ''))
            ->line('**Email:** ' . $this->client->email)
            ->line('**Phone:** ' . ($this->client->phone ?? 'N/A'))
            ->line('**Amount:** ₦' . number_format($this->payment->amount, 2))
            ->line('**Transaction Ref:** ' . $this->payment->transaction_reference)
            ->action('Review Payments', url('/admin/registration-payments'))
            ->line('Please review and approve or reject this payment.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'bank_receipt_uploaded',
            'payment_id' => $this->payment->id,
            'message' => $this->client->name . ' ' . ($this->client->last_name ?? '') . ' has uploaded a bank receipt for ₦' . number_format($this->payment->amount, 2) . '. Pending your approval.',
            'client_name' => $this->client->name . ' ' . ($this->client->last_name ?? ''),
            'client_email' => $this->client->email,
            'amount' => $this->payment->amount,
            'transaction_reference' => $this->payment->transaction_reference,
        ];
    }
}
