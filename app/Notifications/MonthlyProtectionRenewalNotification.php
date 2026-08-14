<?php

namespace App\Notifications;

use App\Models\MonthlySubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyProtectionRenewalNotification extends Notification
{
    use Queueable;

    protected $subscription;

    public function __construct(MonthlySubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Monthly Protection Plan Renewal - Our House Help')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your Monthly Protection Plan is due for renewal.')
            ->line('**Details:**')
            ->line('- **Amount:** N' . number_format($this->subscription->monthly_amount))
            ->line('- **Next Billing:** ' . ($this->subscription->next_billing_date ? $this->subscription->next_billing_date->format('M d, Y') : 'Now'))
            ->line('Your plan includes replacement guarantee, staff monitoring, and priority matching.')
            ->action('View Dashboard', url('/dashboard'))
            ->line('Thank you for choosing Our House Help!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'monthly_protection_renewal',
            'subscription_id' => $this->subscription->id,
            'message' => 'Your Monthly Protection Plan (N' . number_format($this->subscription->monthly_amount) . '/month) is due for renewal.',
            'amount' => $this->subscription->monthly_amount,
        ];
    }
}
