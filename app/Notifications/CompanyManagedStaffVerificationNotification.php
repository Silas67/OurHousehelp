<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyManagedStaffVerificationNotification extends Notification
{
    use Queueable;

    protected ServiceRequest $serviceRequest;

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
        $serviceRequest = $this->serviceRequest;

        $clientName = trim(
            ($serviceRequest->name ?? '') . ' ' .
            ($serviceRequest->last_name ?? '')
        );

        $selectedStaff = $serviceRequest->selectedStaff;

        $staffName = $selectedStaff
            ? trim(
                ($selectedStaff->name ?? '') . ' ' .
                ($selectedStaff->last_name ?? '')
            )
            : 'Selected staff member';

        return (new MailMessage)
            ->subject('Company-Managed Staff Verification Required - Our House Help')
            ->greeting('Hello Admin,')
            ->line('A client has selected a staff member for a Company-Managed booking.')
            ->line('**Client:** ' . ($clientName ?: 'N/A'))
            ->line('**Selected Staff:** ' . $staffName)
            ->line('**Staff Verification Fee:** ₦20,000')
            ->line(
                '**Annual Management Subscription:** ₦' .
                number_format(
                    (float) ($serviceRequest->management_subscription_fee ?? 0),
                    2
                )
            )
            ->line(
                'Please review and thoroughly verify the selected staff member before the placement is finalized.'
            )
            ->action(
                'Review Booking',
                url('/admin/bookings/' . $serviceRequest->id)
            )
            ->line(
                'Please complete the verification process before confirming the placement.'
            );
    }

    public function toArray($notifiable): array
    {
        $serviceRequest = $this->serviceRequest;

        $selectedStaff = $serviceRequest->selectedStaff;

        $staffName = $selectedStaff
            ? trim(
                ($selectedStaff->name ?? '') . ' ' .
                ($selectedStaff->last_name ?? '')
            )
            : 'Selected staff member';

        return [
            'type' => 'company_managed_staff_verification',
            'service_request_id' => $serviceRequest->id,
            'selected_staff_id' => $serviceRequest->selected_staff_id,
            'selected_staff_name' => $staffName,
            'client_name' => trim(
                ($serviceRequest->name ?? '') . ' ' .
                ($serviceRequest->last_name ?? '')
            ),
            'staff_verification_fee' => '₦' . number_format(
                (float) ($serviceRequest->staff_verification_fee ?? 0),
                2
            ),
            'management_subscription_fee' => '₦' . number_format(
                (float) ($serviceRequest->management_subscription_fee ?? 0),
                2
            ),
            'message' => 'A client selected a staff member for a Company-Managed booking. Staff verification is required.',
        ];
    }
}