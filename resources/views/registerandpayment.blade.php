@extends('layouts.usermain')
@section('registerandpayment')

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-receipt me-2 text-white"></i> Registration & Payment History
                        </h4>
                    </div>
                    <div class="card-body">

                        <!-- Current Registration Status -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Current Registration Status</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($user->isRegistrationActive())
                                            <div class="alert alert-success">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-check-circle fa-2x"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="mb-1">Your registration is active!</h5>
                                                        <p class="mb-0">
                                                            <strong>Expires:</strong>
                                                            {{ $user->registration_expires_at->format('F d, Y') }}
                                                            ({{ $user->getDaysUntilExpiration() }} days remaining)
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>Registration Date:</strong>
                                                            {{ $user->registration_date->format('F d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($user->isRegistrationExpired())
                                            <div class="alert alert-warning">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="mb-1">Your registration has expired</h5>
                                                        <p class="mb-0">
                                                            <strong>Expired on:</strong>
                                                            {{ $user->registration_expires_at->format('F d, Y') }}
                                                        </p>
                                                        <p class="mb-2">Please renew your registration to continue using
                                                            our services.</p>

                                                        <a class="btn btn-success"
                                                            href="{{ route('service-request.registration') }}"><i
                                                                class="fas fa-user-check me-1 text-white"></i> Register
                                                            Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-info-circle fa-2x"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="mb-1">You are not registered yet</h5>
                                                        <p class="mb-0">Registration is required to access our services.
                                                        </p>
                                                        <p class="mb-2">Annual registration fee: <strong
                                                                class="h5">₦10,000.00</strong></p>

                                                        <a class="btn btn-success"
                                                            href="{{ route('service-request.registration') }}"><i
                                                                class="fas fa-user-check me-1 text-white"></i> Register
                                                            Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Details (if showing) -->
                        @if (session('show_bank_details'))
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h5 class="mb-0">
                                                <i class="fas fa-university me-2"></i> Bank Transfer Instructions
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <p class="mb-2">Please transfer <strong>₦10,000.00</strong> to:</p>
                                                <div class="bank-details p-3 bg-light rounded mb-3">
                                                    <p class="mb-1"><strong>Bank:</strong> GT Bank</p>
                                                    <p class="mb-1"><strong>Account Name:</strong> Pik and Crest Dynamic
                                                        Concepts LTD</p>
                                                    <p class="mb-1"><strong>Account Number:</strong> 0051480410</p>
                                                    <p class="mb-0"><strong>Reference:</strong>
                                                        {{ session('transaction_ref') }}</p>
                                                </div>
                                                <p class="mb-3">After making the transfer, upload your receipt below:</p>

                                                <form action="{{ route('registerandpayment.upload-receipt') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="transaction_reference"
                                                        value="{{ session('transaction_ref') }}">

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="file" class="form-control" name="bank_receipt"
                                                                accept=".jpg,.jpeg,.png,.pdf" required>
                                                            <div class="form-text">Upload receipt (JPG, PNG, or PDF, max
                                                                5MB)</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="submit" class="btn btn-success w-100">
                                                                <i class="fas fa-upload me-1"></i> Upload Receipt
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Payment History -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Payment History</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($registrationPayments->isEmpty())
                                            <div class="text-center py-4">
                                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No payment history found.</p>
                                            </div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Transaction Ref</th>
                                                            <th>Amount</th>
                                                            <th>Method</th>
                                                            <th>Status</th>
                                                            <th>Expires</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($registrationPayments as $payment)
                                                            <tr>
                                                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                                                <td>
                                                                    <code>{{ $payment->transaction_reference }}</code>
                                                                </td>
                                                                <td>₦{{ number_format($payment->amount, 2) }}</td>
                                                                <td>
                                                                    @if ($payment->payment_method === 'bank_transfer')
                                                                        <span class="badge bg-info">
                                                                            <i class="fas fa-university me-1"></i> Bank
                                                                            Transfer
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-primary">
                                                                            <i class="fas fa-credit-card me-1"></i> Card
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusColors = [
                                                                            'pending' => 'warning',
                                                                            'pending_approval' => 'info',
                                                                            'completed' => 'success',
                                                                            'failed' => 'danger',
                                                                            'refunded' => 'secondary',
                                                                            'cancelled' => 'dark',
                                                                        ];
                                                                    @endphp
                                                                    <span
                                                                        class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($payment->expires_at)
                                                                        {{ $payment->expires_at->format('M d, Y') }}
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if (in_array($payment->status, ['pending', 'pending_approval']))
                                                                        <form
                                                                            action="{{ route('registerandpayment.cancel', $payment->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('POST')
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-outline-danger"
                                                                                onclick="return confirm('Are you sure you want to cancel this registration?')">
                                                                                <i class="fas fa-times me-1"></i> Cancel
                                                                            </button>
                                                                        </form>
                                                                    @endif

                                                                    @if ($payment->bank_receipt)
                                                                        <a href="{{ Storage::url($payment->bank_receipt) }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-outline-info">
                                                                            <i class="fas fa-eye me-1"></i> View Receipt
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert"
            style="z-index: 1060;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert"
            style="z-index: 1060;">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert"
            style="z-index: 1060;">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <style>
        .bank-details {
            border-left: 4px solid #479c18;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }

        .modal-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .alert {
            border-radius: 10px;
        }

        .card {
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }
    </style>
@endsection
