<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StaffSelectionConfirmedNotification extends Notification
{
    use Queueable;

    protected $serviceRequest;
    protected $staff;

    public function __construct(ServiceRequest $serviceRequest, User $staff)
    {
        $this->serviceRequest = $serviceRequest;
        $this->staff = $staff;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $isClientManaged = $this->serviceRequest->management_plan === 'client-managed';

        $message = $isClientManaged
            ? 'You selected ' . $this->staff->name . ' ' . ($this->staff->last_name ?? '') . '. Contact them directly to arrange your start date.'
            : 'You selected ' . $this->staff->name . ' ' . ($this->staff->last_name ?? '') . '. Please proceed to payment to confirm deployment.';

        return [
            'type'               => 'staff_selection_confirmed',
            'service_request_id' => $this->serviceRequest->id,
            'staff_id'           => $this->staff->id,
            'staff_name'         => $this->staff->name . ' ' . ($this->staff->last_name ?? ''),
            'management_plan'    => $this->serviceRequest->management_plan,
            'message'            => $message,
            'is_client_managed'  => $isClientManaged,
        ];
    }
}
