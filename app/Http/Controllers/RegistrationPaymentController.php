<?php

namespace App\Http\Controllers;

use App\Models\RegistrationPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;
use App\Notifications\BankReceiptUploadedNotification;
use App\Notifications\ServiceRequestApprovedNotification;
use App\Notifications\ServiceRequestStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class RegistrationPaymentController extends Controller
{
    public function registrationPayments()
    {
        $payments = RegistrationPayment::with(['user', 'serviceRequest'])
            ->orderByDesc('created_at')
            ->paginate(15);

        // Statistics
        $totalAmount = RegistrationPayment::sum('amount');
        $totalPayments = RegistrationPayment::count();
        $completedPayments = RegistrationPayment::where('status', 'completed')->count();
        $completedAmount = RegistrationPayment::where('status', 'completed')->sum('amount');
        $pendingApprovalPayments = RegistrationPayment::where('status', 'pending_approval')->count();
        $pendingApprovalAmount = RegistrationPayment::where('status', 'pending_approval')->sum('amount');
        $failedRefundedPayments = RegistrationPayment::whereIn('status', ['failed', 'refunded'])->count();
        $failedRefundedAmount = RegistrationPayment::whereIn('status', ['failed', 'refunded'])->sum('amount');

        // This should be your main dashboard view
        return view('admin.payments.showRegistrationPayments', compact(
            'payments',
            'totalAmount',
            'totalPayments',
            'completedPayments',
            'completedAmount',
            'pendingApprovalPayments',
            'pendingApprovalAmount',
            'failedRefundedPayments',
            'failedRefundedAmount'
        ));
    }

    public function paymentDetails($id)
    {
        $payment = RegistrationPayment::with(['user', 'serviceRequest'])->findOrFail($id);

        // Decode payment details if it's JSON
        if ($payment->payment_details && is_string($payment->payment_details)) {
            $payment->payment_details = json_decode($payment->payment_details, true);
        }

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    public function approvePayment(Request $request, $id)
    {
        $payment = RegistrationPayment::with('user')->findOrFail($id);

        $payment->update([
            'status' => 'completed',
            'paid_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
        ]);

        // Update user registration status
        $user = $payment->user;
        if ($user) {
            $user->update([
                'is_registered' => true,
                'registration_date' => Carbon::now(),
                'registration_expires_at' => Carbon::now()->addYear(),
            ]);
        }

        // Update service request if exists (linked or find user's latest one)
        $serviceRequestToUpdate = $payment->serviceRequest;
        if (!$serviceRequestToUpdate && $user) {
            $serviceRequestToUpdate = \App\Models\ServiceRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'pending_approval', 'confirmed'])
                ->latest()
                ->first();
        }

        if ($serviceRequestToUpdate) {
            $serviceRequestToUpdate->update([
                'registration_paid' => true,
                'registration_paid_at' => Carbon::now(),
                'registration_expires_at' => Carbon::now()->addYear(),
                'registration_status' => 'active',
                'payment_status' => 'Paid',
                'status' => 'confirmed',
            ]);
        }

        // Notify all approved staff about the confirmed service request
        if ($serviceRequestToUpdate) {
            try {
                // Check if staff were already notified for this service request
                $alreadyNotified = \DB::table('notifications')
                    ->where('type', 'App\\Notifications\\ServiceRequestApprovedNotification')
                    ->where('data', 'like', '%"service_request_id":' . $serviceRequestToUpdate->id . '%')
                    ->exists();

                if (!$alreadyNotified) {
                    $approvedStaff = User::whereIn('account_type', ['applicant', 'staff'])
                        ->where('approval_status', 'approved')
                        ->get();

                    if ($approvedStaff->count() > 0) {
                        Notification::send($approvedStaff, new ServiceRequestApprovedNotification($serviceRequestToUpdate));
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send staff notifications: ' . $e->getMessage());
            }
        }

        // Notify the client that their registration has been approved and service request is confirmed
        if ($user && $serviceRequestToUpdate) {
            try {
                $user->notify(new ServiceRequestStatusNotification($serviceRequestToUpdate, 'confirmed'));
            } catch (\Exception $e) {
                \Log::error('Failed to send client approval notification: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Payment approved successfully!');
    }

    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500'
        ]);

        $payment = RegistrationPayment::findOrFail($id);

        $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
        $paymentDetails['rejection_reason'] = $request->reason;
        $paymentDetails['rejected_at'] = Carbon::now()->toDateTimeString();
        $paymentDetails['rejected_by'] = auth()->id();

        $payment->update([
            'status' => 'failed',
            'payment_details' => json_encode($paymentDetails),
        ]);

        return back()->with('success', 'Payment rejected successfully!');
    }

    public function refundPayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:10000',
            'reason' => 'required|string|min:5|max:500'
        ]);

        $payment = RegistrationPayment::findOrFail($id);

        $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
        $paymentDetails['refund_amount'] = $request->amount;
        $paymentDetails['refund_reason'] = $request->reason;
        $paymentDetails['refunded_at'] = Carbon::now()->toDateTimeString();
        $paymentDetails['refunded_by'] = auth()->id();

        $payment->update([
            'status' => 'refunded',
            'payment_details' => json_encode($paymentDetails),
        ]);

        return back()->with('success', 'Payment refunded successfully!');
    }

    public function sendReceipt($id)
    {
        $payment = RegistrationPayment::with('user')->findOrFail($id);

        if (!$payment->user || !$payment->user->email) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'User email not found!'
            // ], 400);
            return back()->with('success', 'User email not found!');
        }

        try {
            // Send receipt email
            Mail::to($payment->user->email)->send(new PaymentReceiptMail($payment));

            // Update payment details
            $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
            $paymentDetails['receipt_sent_at'] = Carbon::now()->toDateTimeString();
            $paymentDetails['receipt_sent_by'] = auth()->id();

            $payment->update([
                'payment_details' => json_encode($paymentDetails),
            ]);

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Receipt sent successfully!'
            // ]);
            return back()->with('success', 'Receipt sent successfully!');
        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Failed to send receipt: ' . $e->getMessage()
            // ], 500);
            return back()->with('success', 'Failed to send receipt: ' . $e->getMessage());
        }
    }

    public function getEditData($id)
    {
        $payment = RegistrationPayment::findOrFail($id);

        $editData = [
            'amount' => $payment->amount,
            'status' => $payment->status,
            'notes' => $payment->payment_details ?
                (is_string($payment->payment_details) ?
                    json_decode($payment->payment_details, true)['admin_notes'] ?? '' :
                    $payment->payment_details['admin_notes'] ?? '') : ''
        ];

        return response()->json([
            'success' => true,
            'data' => $editData
        ]);
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'sometimes|numeric|min:0|max:10000',
            'status' => 'sometimes|in:pending_approval,completed,failed,refunded',
            'notes' => 'nullable|string|max:500'
        ]);

        $payment = RegistrationPayment::findOrFail($id);

        $updateData = [];

        if ($request->has('amount')) {
            $updateData['amount'] = $request->amount;
        }

        if ($request->has('status')) {
            $updateData['status'] = $request->status;

            if ($request->status === 'completed') {
                $updateData['paid_at'] = $payment->paid_at ?? Carbon::now();
                $updateData['expires_at'] = $payment->expires_at ?? Carbon::now()->addYear();
            }
        }

        if ($request->has('notes')) {
            $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
            $paymentDetails['admin_notes'] = $request->notes;
            $paymentDetails['updated_by'] = auth()->id();
            $paymentDetails['updated_at'] = Carbon::now()->toDateTimeString();

            $updateData['payment_details'] = json_encode($paymentDetails);
        }

        $payment->update($updateData);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Payment updated successfully!'
        // ]);
        return back()->with('success', 'Payment updated successfully!');
    }

    public function getPaymentAmount($id)
    {
        $payment = RegistrationPayment::findOrFail($id);

        return response()->json([
            'success' => true,
            'amount' => $payment->amount
        ]);
    }

    public function filterPayments(Request $request)
    {
        $query = RegistrationPayment::with('user');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('bank_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Method filter
        if ($request->has('method') && $request->method) {
            $query->where('payment_method', $request->method);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'paid_at');
        $sortDir = $request->get('sort_dir', 'desc');

        if ($sortBy === 'user_name') {
            $query->join('users', 'registration_payments.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortDir)
                ->select('registration_payments.*');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $payments = $query->paginate(15);

        if ($request->ajax()) {
            return view('admin.payments._payments_table', compact('payments'))->render();
        }

        return redirect()->route('admin.payments.index');
    }

    public function exportPayments(Request $request, $format)
    {
        $query = RegistrationPayment::with('user');

        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $payments = $query->get();

        if ($format === 'csv') {
            return $this->exportCsv($payments);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($payments);
        }

        return back()->with('error', 'Invalid export format');
    }

    private function exportCsv($payments)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registration_payments_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Transaction Reference',
                'User Name',
                'User Email',
                'Amount (₦)',
                'Payment Method',
                'Status',
                'Paid At',
                'Bank Reference',
                'Bank Receipt'
            ]);

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->transaction_reference,
                    $payment->user->name ?? 'N/A',
                    $payment->user->email ?? 'N/A',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->status,
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '',
                    $payment->bank_reference ?? '',
                    $payment->bank_receipt ? 'Yes' : 'No'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($payments)
    {
        // You'll need to implement PDF generation
        // For now, redirect back
        return back()->with('info', 'PDF export will be implemented soon');
    }

    // Legacy methods (keep for backward compatibility)

    public function showRegistrationPayments($id)
    {
        $payment = RegistrationPayment::with(['user', 'serviceRequest'])->findOrFail($id);
        return view('admin.payments.showRegistrationPayments', compact('payment'));
    }

    public function markCompleted($id)
    {
        return $this->approvePayment(request(), $id);
    }

    public function markFailed($id)
    {
        $payment = RegistrationPayment::findOrFail($id);

        $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
        $paymentDetails['note'] = 'Payment verification failed or cancelled.';
        $paymentDetails['updated_at'] = now();

        $payment->update([
            'status' => 'failed',
            'payment_details' => json_encode($paymentDetails),
        ]);

        return back()->with('error', 'Payment marked as failed.');
    }

    public function markRefunded($id)
    {
        $payment = RegistrationPayment::findOrFail($id);

        $paymentDetails = $payment->payment_details ? json_decode($payment->payment_details, true) : [];
        $paymentDetails['note'] = 'Payment refunded to user.';
        $paymentDetails['refunded_at'] = now();

        $payment->update([
            'status' => 'refunded',
            'payment_details' => json_encode($paymentDetails),
        ]);

        return back()->with('info', 'Payment marked as refunded.');
    }

    public function registerandpayment()
    {
        $user = Auth::user();

        // Get user's registration payments ordered by latest first
        $registrationPayments = RegistrationPayment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('registerandpayment', compact('user', 'registrationPayments'));
    }

    /**
     * Process new registration payment
     */
    public function processRegistration(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,card',
            'terms' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Generate transaction reference
        $transactionRef = 'REG' . date('Ymd') . strtoupper(uniqid());

        // Create registration payment record
        $registrationPayment = RegistrationPayment::create([
            'user_id' => $user->id,
            'amount' => 10000, // ₦10,000
            'payment_method' => $request->payment_method,
            'transaction_reference' => $transactionRef,
            'status' => 'pending',
            'expires_at' => now()->addYear(),
        ]);

        if ($request->payment_method === 'bank_transfer') {
            // For bank transfer, show bank details
            return redirect()->route('registerandpayment')
                ->with('success', 'Registration initiated. Please complete bank transfer and upload receipt.')
                ->with('show_bank_details', true)
                ->with('transaction_ref', $transactionRef);
        } else {
            // For card payment, redirect to payment gateway
            // You'll need to implement your payment gateway integration here
            return redirect()->route('registerandpayment')
                ->with('info', 'Card payment integration coming soon.');
        }
    }

    /**
     * Upload bank receipt
     */
    public function uploadReceipt(Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|exists:registration_payments,transaction_reference',
            'bank_receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $payment = RegistrationPayment::where('transaction_reference', $request->transaction_reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Store the receipt
        $path = $request->file('bank_receipt')->store('bank_receipts', 'public');

        $payment->update([
            'bank_receipt' => $path,
            'status' => 'pending_approval',
        ]);

        // Notify admin about the uploaded receipt
        try {
            $admins = User::whereIn('user_role', [1, 2])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new BankReceiptUploadedNotification($payment, Auth::user()));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send bank receipt notification to admin: ' . $e->getMessage());
        }

        return redirect()->route('registerandpayment')
            ->with('success', 'Receipt uploaded successfully. Your registration will be activated after admin approval.');
    }

    /**
     * Cancel pending registration
     */
    public function cancelRegistration($id)
    {
        $payment = RegistrationPayment::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'pending_approval'])
            ->firstOrFail();

        $payment->update(['status' => 'cancelled']);

        return redirect()->route('registerandpayment')
            ->with('success', 'Registration cancelled successfully.');
    }
}
