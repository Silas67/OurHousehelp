@extends('layouts.usermain')

@section('booking-payment-success')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm text-center">
            <div class="card-body p-5">
                
                @if($serviceRequest->management_plan === 'client-managed')
                    @php
                        $salaryService = new \App\Services\StaffSalaryService();
                        $staffSalary = $salaryService->getSalary($serviceRequest->days_per_week ?? 4);

                        $selectedShortlist = $serviceRequest->shortlistedStaff()
                            ->where('staff_id', $serviceRequest->selected_staff_id)
                            ->first();
                    @endphp

                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>

                    <h3 class="fw-bold text-success mb-3">
                        Booking Confirmed!
                    </h3>

                    <p class="text-muted mb-4">
                        Your staff has been successfully assigned.
                    </p>

                    <div class="alert alert-info text-start mx-auto" style="max-width:650px;">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Staff Payment Information
                        </h5>

                        <p class="mb-2">
                            You are required to pay your staff
                            <strong class="text-success">
                                ₦{{ number_format($staffSalary) }}/month
                            </strong>
                        </p>

                        <p class="mb-2">
                            Starting:
                            <strong>
                                {{ $serviceRequest->preferred_start_date?->format('M d, Y') ?? 'immediately' }}
                            </strong>
                        </p>

                        <p class="mb-0">
                            Please arrange this payment directly with your staff.
                        </p>
                    </div>

                    @if($selectedShortlist)
                        <a href="{{ route('booking.staff-profile', [$serviceRequest->id, $selectedShortlist->id]) }}"
                        class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-user me-2"></i>
                            View Staff Details
                        </a>
                    @endif

                @elseif ($payment && $payment->status === 'completed')
                    <!-- Payment Approved -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-3">Payment Approved!</h3>
                    <p class="text-muted mb-4">
                        Your payment has been confirmed. Staff details are now available.
                    </p>
                    @php
                        $selectedShortlist = $serviceRequest->shortlistedStaff()
                            ->where('staff_id', $serviceRequest->selected_staff_id)
                            ->first();
                    @endphp
                    @if($selectedShortlist)
                        <a href="{{ route('booking.staff-profile', [$serviceRequest->id, $selectedShortlist->id]) }}"
                            class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-user me-2"></i>View Staff Details
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-home me-2"></i>Go to Dashboard
                        </a>
                    @endif
                @elseif($payment && $payment->status === 'pending_approval')
                    <!-- Awaiting Admin Approval -->
                    <div class="mb-4">
                        <i class="fas fa-clock fa-4x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-3">Payment Submitted!</h3>
                    <p class="text-muted mb-4">
                        Your payment receipt has been received and is awaiting admin approval.
                        You'll be notified once approved and staff details will be released.
                    </p>

                    <div class="card bg-light mx-auto" style="max-width: 400px;">
                        <div class="card-body">
                            <p class="mb-1"><strong>Amount:</strong> N{{ number_format($payment->amount) }}</p>
                            <p class="mb-1"><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                            <p class="mb-1"><strong>Reference:</strong> {{ $payment->transaction_reference }}</p>
                            <p class="mb-0"><strong>Status:</strong>
                                <span class="badge bg-warning">Pending Approval</span>
                            </p>
                        </div>
                    </div>

                    @if($serviceRequest->payment_type === 'installment')
                    <div class="alert alert-info mt-4 text-start">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Installment Payment:</strong> You have paid the first installment.
                        The remaining N{{ number_format($serviceRequest->amount_remaining) }}
                        is due before your staff resumes
                        @if($serviceRequest->balance_due_date)
                            ({{ $serviceRequest->balance_due_date->format('M d, Y') }}).
                        @endif
                    </div>
                    @endif
                @else
                    <!-- Payment Pending -->
                    <div class="mb-4">
                        <i class="fas fa-hourglass-half fa-4x text-secondary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Payment Processing</h3>
                    <p class="text-muted mb-4">
                        Your payment is being processed. Please check back shortly.
                    </p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection