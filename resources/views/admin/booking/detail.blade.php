@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Booking #{{ $booking->id }}</h3>
        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Bookings
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-8">

            <!-- Client Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Client Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6"><strong>Name:</strong></div>
                        <div class="col-6">{{ $booking->name }} {{ $booking->middle_name }} {{ $booking->last_name }}</div>
                        <div class="col-6"><strong>Email:</strong></div>
                        <div class="col-6">
                            <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
                        </div>
                        <div class="col-6"><strong>Phone:</strong></div>
                        <div class="col-6">
                            <a href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a>
                        </div>
                        <div class="col-6"><strong>Location:</strong></div>
                        <div class="col-6">{{ $booking->preferred_city }}, {{ $booking->state_of_residence }}</div>
                        <div class="col-6"><strong>Address:</strong></div>
                        <div class="col-6">{{ $booking->address }}</div>
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Service Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6"><strong>Services:</strong></div>
                        <div class="col-6">{{ $booking->service_name_list }}</div>
                        <div class="col-6"><strong>Type:</strong></div>
                        <div class="col-6">{{ ucfirst($booking->applicant_type ?? 'N/A') }}</div>
                        <div class="col-6"><strong>Start Date:</strong></div>
                        <div class="col-6">{{ $booking->preferred_start_date ? $booking->preferred_start_date->format('M d, Y') : 'N/A' }}</div>
                        <div class="col-6"><strong>Days/Week:</strong></div>
                        <div class="col-6">{{ $booking->days_per_week }}</div>
                        <div class="col-6"><strong>Duration:</strong></div>
                        <div class="col-6">{{ $booking->duration_weeks }} weeks</div>

                        {{-- Fix #4: correct plan badge --}}
                        <div class="col-6"><strong>Plan:</strong></div>
                        <div class="col-6">
                            @if($booking->management_plan === 'company-managed')
                                <span class="badge bg-success">Company Managed</span>
                            @elseif($booking->management_plan === 'client-managed')
                                <span class="badge bg-secondary">Client Managed</span>
                            @else
                                <span class="badge bg-light text-dark border">Not selected</span>
                            @endif
                        </div>

                        <div class="col-6"><strong>Deployment:</strong></div>
                        <div class="col-6">{{ ucfirst(str_replace('_', ' ', $booking->deployment_type ?? 'N/A')) }}</div>
                    </div>
                </div>
            </div>

            <!-- Shortlisted Staff -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Shortlisted Staff ({{ $booking->shortlistedStaff->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Staff</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>City</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->shortlistedStaff as $sl)
                                <tr class="{{ $sl->status === 'selected' ? 'table-success' : '' }}">
                                    <td>
                                        <div class="fw-semibold">{{ $sl->staff?->name ?? 'N/A' }} {{ $sl->staff?->last_name ?? '' }}</div>
                                        <small class="text-muted">{{ $sl->staff?->state_of_residence ?? '' }}</small>
                                    </td>
                                    <td>{{ $sl->match_score }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $sl->status === 'selected' ? 'success' : ($sl->status === 'accepted' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($sl->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $sl->staff?->city ?? 'N/A' }}</td>
                                    <td>
                                        @if($sl->staff?->phone)
                                        <a href="tel:{{ $sl->staff->phone }}" class="btn btn-sm btn-outline-dark me-1">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <a href="https://wa.me/234{{ ltrim($sl->staff->phone, '0') }}" target="_blank" class="btn btn-sm btn-success">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Payments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>For</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Receipt</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($booking->bookingPayments as $pmt)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $pmt->payment_for)) }}</td>
                                    <td>₦{{ number_format($pmt->amount) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $pmt->payment_method ?? 'N/A')) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pmt->status === 'completed' ? 'success' : ($pmt->status === 'pending_approval' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $pmt->status)) }}
                                        </span>
                                    </td>
                                    {{-- Fix #2: receipts served via controller not public storage --}}
                                    <td>
                                        @if($pmt->bank_receipt)
                                            <a href="{{ \App\Support\SecureDocument::url('receipts', $pmt->bank_receipt) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file me-1"></i>View
                                            </a>
                                        @elseif($pmt->whatsapp_receipt)
                                            <a href="{{ \App\Support\SecureDocument::url('receipts', $pmt->whatsapp_receipt) }}"
                                                target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="fab fa-whatsapp me-1"></i>View
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $pmt->created_at->format('M d, H:i') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No payments yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-md-4">

            <!-- Status & Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Status & Actions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Matching Status:</strong><br>
                        <span class="badge bg-primary fs-6 mt-1">{{ $booking->matching_status_label }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Staff Details:</strong><br>
                        @if($booking->staff_details_released)
                            <span class="badge bg-success mt-1">Released</span>
                        @else
                            <span class="badge bg-warning text-dark mt-1">Not Released</span>
                        @endif
                    </div>

                    @if($booking->call_with_staff_requested)
                    <div class="alert alert-info py-2 mb-3">
                        <i class="fas fa-phone me-1"></i>Client requested call with staff
                    </div>
                    @endif

                    <hr>

                    {{-- Approve Payment --}}
                    @if($booking->bookingPayments()->where('status', 'pending_approval')->exists())
                    <form action="{{ route('admin.booking.approve-payment', $booking->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100"
                            onclick="return confirm('Approve payment for this booking?')">
                            <i class="fas fa-check me-1"></i>Approve Payment
                        </button>
                    </form>
                    @endif

                    {{-- Fix #1: Only show release button for company-managed --}}
                    @if(!$booking->staff_details_released && $booking->selected_staff_id && $booking->management_plan === 'company-managed')
                    <form action="{{ route('admin.booking.release-staff', $booking->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100"
                            onclick="return confirm('Release staff details to client?')">
                            <i class="fas fa-unlock me-1"></i>Release Staff Details
                        </button>
                    </form>
                    @endif

                    {{-- Mark as Deployed --}}
                    @if($booking->matching_status !== 'deployed' && $booking->staff_details_released)
                    <form action="{{ route('admin.booking.mark-deployed', $booking->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-dark w-100"
                            onclick="return confirm('Mark this booking as deployed?')">
                            <i class="fas fa-rocket me-1"></i>Mark as Deployed
                        </button>
                    </form>
                    @elseif($booking->matching_status === 'deployed')
                    <div class="alert alert-success py-2 mb-2">
                        <i class="fas fa-check-circle me-1"></i>Staff Deployed
                    </div>
                    @endif

                </div>
            </div>

            <!-- Cost Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Cost Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Service Cost:</span>
                        <strong>₦{{ number_format($booking->grand_cost) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Plan Cost:</span>
                        <strong>₦{{ number_format($booking->management_plan_cost) }}</strong>
                    </div>
                    @if($booking->express_matching)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Express Matching:</span>
                        <strong>₦{{ number_format($booking->express_matching_cost) }}</strong>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total:</strong>
                        <strong class="text-primary fs-5">₦{{ number_format($booking->total_booking_cost) }}</strong>
                    </div>
                    @if($booking->amount_paid > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span>Paid:</span>
                        <span class="text-success fw-semibold">₦{{ number_format($booking->amount_paid) }}</span>
                    </div>
                    @endif
                    @if($booking->amount_remaining > 0)
                    <div class="d-flex justify-content-between">
                        <span>Remaining:</span>
                        <span class="text-danger fw-semibold">₦{{ number_format($booking->amount_remaining) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Selected Staff card --}}
            @if($booking->selectedStaff)
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Selected Staff</h5>
                </div>
                <div class="card-body text-center">
                    {{-- Avatar --}}
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width:60px;height:60px;background:#E1F5EE;color:#0F6E56;font-size:24px;font-weight:700;">
                        {{ strtoupper(substr($booking->selectedStaff->name, 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-1">
                        {{ $booking->selectedStaff->name }} {{ $booking->selectedStaff->last_name }}
                    </h6>
                    <p class="text-muted small mb-1">{{ $booking->selectedStaff->email }}</p>
                    <p class="text-muted small mb-1">{{ $booking->selectedStaff->phone }}</p>
                    <p class="text-muted small mb-3">
                        {{ $booking->selectedStaff->city }}, {{ $booking->selectedStaff->state_of_residence }}
                    </p>

                    {{-- Fix #3: quick contact buttons --}}
                    <div class="d-flex justify-content-center gap-2">
                        <a href="tel:{{ $booking->selectedStaff->phone }}"
                            class="btn btn-outline-dark btn-sm">
                            <i class="fas fa-phone me-1"></i>Call
                        </a>
                        <a href="https://wa.me/234{{ ltrim($booking->selectedStaff->phone, '0') }}"
                            target="_blank" class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp me-1"></i>WhatsApp
                        </a>
                        <a href="{{ route('applicantprofile', $booking->selectedStaff->id) }}"
                            target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user me-1"></i>Profile
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection