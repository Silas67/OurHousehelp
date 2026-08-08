<?php

namespace App\Services;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\RegistrationPayment;
use Carbon\Carbon;

class RegistrationService
{
    public function processRegistrationPayment($user, $serviceRequest, $paymentData)
    {
        // Create registration payment record
        $registrationPayment = RegistrationPayment::create([
            'user_id' => $user->id,
            'service_request_id' => $serviceRequest->id,
            'amount' => $paymentData['amount'],
            'payment_method' => $paymentData['payment_method'],
            'transaction_reference' => $paymentData['transaction_reference'],
            'status' => 'completed',
            'payment_details' => $paymentData['payment_details'],
            'paid_at' => now(),
            'expires_at' => now()->addYear()
        ]);

        // Mark service request as paid
        $serviceRequest->markRegistrationPaid();

        return $registrationPayment;
    }

    public function checkExpiredRegistrations()
    {
        // Expire service requests
        ServiceRequest::where('registration_status', 'active')
            ->where('registration_expires_at', '<', now())
            ->update(['registration_status' => 'expired']);

        // Expire user registrations
        User::where('registration_status', 'active')
            ->where('registration_expires_at', '<', now())
            ->update([
                'registration_status' => 'expired',
                'is_registered' => false
            ]);

        // Expire registration payments
        RegistrationPayment::where('status', 'completed')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
    }

    public function getUserRegistrationStatus($userId)
    {
        $user = User::with(['activeRegistrationPayment', 'serviceRequests' => function($query) {
            $query->where('registration_status', 'active');
        }])->find($userId);

        if (!$user) {
            return null;
        }

        return [
            'is_registered' => $user->isRegistrationActive(),
            'registration_date' => $user->registration_date,
            'expires_at' => $user->registration_expires_at,
            'days_remaining' => $user->getDaysUntilExpiration(),
            'active_payment' => $user->activeRegistrationPayment,
            'active_service_requests' => $user->serviceRequests
        ];
    }

    public function renewRegistration($user, $paymentData)
    {
        // Deactivate old registrations
        $user->deactivateRegistration();

        // Create new payment record
        $registrationPayment = RegistrationPayment::create([
            'user_id' => $user->id,
            'amount' => $paymentData['amount'],
            'payment_method' => $paymentData['payment_method'],
            'transaction_reference' => $paymentData['transaction_reference'],
            'status' => 'completed',
            'payment_details' => $paymentData['payment_details'],
            'paid_at' => now(),
            'expires_at' => now()->addYear()
        ]);

        // Activate user registration
        $user->activateRegistration(12);

        return $registrationPayment;
    }
}