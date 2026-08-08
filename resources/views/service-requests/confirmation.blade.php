<!-- resources/views/service-requests/confirmation.blade.php -->
@extends('layouts.main')
@section('confirmation')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i> Service Request Created
                            <small class="float-end">#SR-{{ str_pad($serviceRequest->id, 6, '0', STR_PAD_LEFT) }}</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if (session('password_info'))
                            <div class="alert alert-info">
                                <h5><i class="fas fa-key me-2"></i> Login Information</h5>
                                <p class="mb-1"><strong>Email:</strong> {{ $serviceRequest->email }}</p>
                                <p class="mb-1"><strong>Password:</strong> Your phone number
                                    ({{ $serviceRequest->phone }})</p>
                                <small class="text-muted">You can change your password after logging in.</small>
                            </div>
                        @endif

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Service Request Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Reference:</strong>
                                            SR-{{ str_pad($serviceRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        {{-- <p><strong>Services:</strong> {{ $serviceRequest->service_names }}</p> --}}
                                        <p><strong>Services:</strong> {{ $serviceRequest->service_name_list }}</p>

                                        <p><strong>Package:</strong> {{ $serviceRequest->package }}</p>
                                        <p><strong>Days per Week:</strong> {{ $serviceRequest->days_per_week }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong>
                                            <span
                                                class="badge bg-{{ $serviceRequest->status == 'active' ? 'success' : ($serviceRequest->status == 'pending_approval' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                                            </span>
                                        </p>
                                        <p><strong>Registration:</strong>
                                            <span
                                                class="badge bg-{{ $serviceRequest->registration_paid ? 'success' : 'warning' }}">
                                                {{ $serviceRequest->registration_paid ? 'Paid' : 'Pending' }}
                                            </span>
                                        </p>
                                        <p><strong>Total Cost:</strong>
                                            ₦{{ number_format($serviceRequest->grand_cost, 2) }}</p>
                                        <p><strong>Created:</strong>
                                            {{-- {{ $serviceRequest->created_at->format('M d, Y H:i') }} --}}
                                            {{ optional($serviceRequest->created_at)->format('M d, Y H:i') ?? 'N/A' }}
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- @if (!$serviceRequest->registration_paid && $serviceRequest->payment_status !== 'Pending Approval') --}}
                        @if (!$serviceRequest->registration_paid && !in_array($serviceRequest->payment_status, ['Pending Approval', 'Pending']))
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Action Required</h5>
                                </div>
                                <div class="card-body">
                                    <p>To activate your service request, please complete the ₦10,000 registration fee
                                        payment.</p>
                                    <p class="mb-0"><strong>Registration Fee:</strong> <span
                                            class="h4 text-success">₦10,000.00</span> (One-time yearly fee)</p>

                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                        @auth
                                            <a href="{{ route('service-request.payment', ['id' => $serviceRequest->id]) }}"
                                                class="btn btn-success btn-lg">
                                                <i class="fas fa-credit-card me-2"></i> Pay Registration Fee
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                                <i class="fas fa-sign-in-alt me-2"></i> Login to Complete Payment
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($serviceRequest->payment_status === 'Pending Approval')
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Payment Under Review</h5>
                                </div>
                                <div class="card-body">
                                    <p>Your bank transfer payment is currently being reviewed by our admin team.</p>
                                    <p>You will receive a notification once your payment has been approved and your service
                                        request is activated.</p>
                                    <p class="mb-0">Estimated approval time: 24-48 hours</p>
                                </div>
                            </div>
                        @endif

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i> Return Home
                            </a>

                            {{-- @if ($serviceRequest->registration_paid) --}}
                            @if (($serviceRequest->registration_paid ?? false) === true)
                                <a href="{{ route('orderreceipt', ['serviceRequest' => $serviceRequest->id]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-receipt me-2"></i> View Receipt
                                </a>
                            @endif

                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
                                    <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login to Your Account
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
