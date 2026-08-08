<?php

namespace App\Http\Controllers;

use App\Models\LiveOutPayment;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\ServiceRequestStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LiveOutPaymentController extends Controller
{
    public function showPaymentForm($serviceRequestId)
    {
        $serviceRequest = ServiceRequest::with('liveOutPayment')
            ->where('user_id', auth()->id())
            ->findOrFail($serviceRequestId);

        // Check if payment already exists and is completed
        if ($serviceRequest->isLiveOutPaymentPaid()) {
            return redirect()->route('service_requests.show', $serviceRequest)
                ->with('success', 'Live-out payment has already been completed.');
        }

        return view('live-out-payment.form', compact('serviceRequest'));
    }

    public function processPayment(Request $request, $serviceRequestId)
    {
        $request->validate([
            'payment_method' => 'required|string|in:card,bank_transfer,wallet',
            'amount' => 'required|numeric|min:1'
        ]);

        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->findOrFail($serviceRequestId);

        DB::transaction(function () use ($request, $serviceRequest) {
            // Create or update live-out payment
            $liveOutPayment = $serviceRequest->liveOutPayment()->updateOrCreate(
                [],
                [
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'transaction_reference' => $this->generateTransactionReference(),
                    'status' => 'pending',
                    'payment_details' => [
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]
                ]
            );

            // Process payment based on method
            $paymentResult = $this->processPaymentByMethod(
                $liveOutPayment, 
                $request->payment_method
            );

            if ($paymentResult['success']) {
                $liveOutPayment->markAsCompleted($paymentResult['details']);

                // Update service request status if needed
                $serviceRequest->update(['status' => 'payment_completed']);

                // Notify the client about payment completion
                try {
                    $client = User::find($serviceRequest->user_id);
                    if ($client) {
                        $client->notify(new ServiceRequestStatusNotification($serviceRequest, 'payment_completed'));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send live-out payment notification to client: ' . $e->getMessage());
                }

                // Notify admin about the payment completion
                try {
                    $admins = User::whereIn('user_role', [1, 2])->get();
                    if ($admins->count() > 0) {
                        Notification::send($admins, new ServiceRequestStatusNotification($serviceRequest, 'payment_completed'));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send live-out payment notification to admin: ' . $e->getMessage());
                }
            } else {
                $liveOutPayment->markAsFailed($paymentResult['details']);
                throw new \Exception('Payment processing failed: ' . $paymentResult['message']);
            }
        });

        return redirect()->route('service_requests.show', $serviceRequest)
            ->with('success', 'Live-out payment completed successfully!');
    }

    public function paymentCallback(Request $request)
    {
        $reference = $request->input('reference');
        
        $liveOutPayment = LiveOutPayment::where('transaction_reference', $reference)
            ->with('serviceRequest')
            ->firstOrFail();

        // Verify payment with payment gateway
        $verificationResult = $this->verifyPayment($reference);

        if ($verificationResult['success']) {
            DB::transaction(function () use ($liveOutPayment, $verificationResult) {
                $liveOutPayment->markAsCompleted($verificationResult['details']);
                $liveOutPayment->serviceRequest->update(['status' => 'payment_completed']);
            });

            // Notify client and admin about payment completion
            try {
                $sr = $liveOutPayment->serviceRequest;
                $client = User::find($sr->user_id);
                if ($client) {
                    $client->notify(new ServiceRequestStatusNotification($sr, 'payment_completed'));
                }
                $admins = User::whereIn('user_role', [1, 2])->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new ServiceRequestStatusNotification($sr, 'payment_completed'));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send live-out payment callback notifications: ' . $e->getMessage());
            }

            return response()->json(['status' => 'success']);
        } else {
            $liveOutPayment->markAsFailed($verificationResult['details']);
            return response()->json(['status' => 'failed'], 400);
        }
    }

    public function paymentSuccess($serviceRequestId)
    {
        $serviceRequest = ServiceRequest::with('liveOutPayment')
            ->where('user_id', auth()->id())
            ->findOrFail($serviceRequestId);

        return view('live-out-payment.success', compact('serviceRequest'));
    }

    public function paymentFailure($serviceRequestId)
    {
        $serviceRequest = ServiceRequest::with('liveOutPayment')
            ->where('user_id', auth()->id())
            ->findOrFail($serviceRequestId);

        return view('live-out-payment.failure', compact('serviceRequest'));
    }

    private function generateTransactionReference(): string
    {
        return 'LOP_' . time() . '_' . rand(1000, 9999);
    }

    private function processPaymentByMethod(LiveOutPayment $payment, string $method): array
    {
        // Implement your payment gateway logic here
        // This is a simplified example
        
        switch ($method) {
            case 'card':
                return $this->processCardPayment($payment);
            case 'bank_transfer':
                return $this->processBankTransfer($payment);
            case 'wallet':
                return $this->processWalletPayment($payment);
            default:
                return [
                    'success' => false,
                    'message' => 'Invalid payment method',
                    'details' => null
                ];
        }
    }

    private function processCardPayment(LiveOutPayment $payment): array
    {
        // Integrate with Paystack, Flutterwave, etc.
        // For now, simulate successful payment
        return [
            'success' => true,
            'message' => 'Card payment processed successfully',
            'details' => [
                'gateway' => 'paystack',
                'gateway_reference' => 'PS_' . time(),
                'processed_at' => now()->toDateTimeString()
            ]
        ];
    }

    private function verifyPayment(string $reference): array
    {
        // Verify payment with your payment gateway
        // This is a simplified example
        return [
            'success' => true,
            'details' => [
                'verified_at' => now()->toDateTimeString(),
                'gateway_response' => 'Approved'
            ]
        ];
    }
}