@extends('layouts.adminmain')

@section('admindashboard')
    <div class="container-fluid">
        <h3 class="fw-bold mb-4">Booking Management</h3>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-2">
                <div class="card bg-info text-white text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $stats['matching'] }}</div>
                        <small>Matching</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $stats['shortlisted'] }}</div>
                        <small>Shortlisted</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $stats['payment_pending'] }}</div>
                        <small>Payment Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card bg-success text-white text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $stats['approved'] }}</div>
                        <small>Approved</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card bg-dark text-white text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $stats['deployed'] }}</div>
                        <small>Deployed</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card bg-danger text-white text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold">{{ $pendingPayments }}</div>
                        <small>Pending Payments</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Bookings</h5>
                <a href="{{ route('admin.management-plans') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-cog me-1"></i>Manage Plans
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Services</th>
                                <th>Location</th>
                                <th>Selected Staff</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Dates</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr class="{{ $booking->is_urgent ? 'table-danger' : '' }}">

                                {{-- ID --}}
                                <td>
                                    {{ $booking->id }}
                                    @if($booking->is_urgent)
                                        <span class="badge bg-danger ms-1">URGENT</span>
                                    @endif
                                </td>

                                {{-- Client --}}
                                <td>
                                    <div class="fw-semibold" style="font-size:13px;">{{ $booking->name }} {{ $booking->last_name }}</div>
                                </td>

                                {{-- Services --}}
                                <td>
                                    <small class="text-muted">{{ $booking->service_name_list }}</small>
                                </td>

                                {{-- Location --}}
                                <td>
                                    <small>{{ $booking->preferred_city }}, {{ $booking->state_of_residence }}</small>
                                </td>

                                {{-- Selected Staff — Fix #1 --}}
                                <td>
                                    @if($booking->selected_staff_id)
                                        @php $staff = \App\Models\User::find($booking->selected_staff_id); @endphp
                                        @if($staff)
                                            <div class="fw-semibold" style="font-size:13px;">
                                                {{ $staff->name }} {{ $staff->last_name }}
                                            </div>
                                            <small class="text-muted font-bold">{{ $staff->phone }}</small><br>
                                           
                                        @else
                                            <span class="text-muted small">Staff deleted</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted border">Not selected</span>
                                    @endif
                                </td>

                                {{-- Plan — Fix #2: correct plan values --}}
                                <td>
                                    @if($booking->management_plan === 'company-managed')
                                        <span class="badge bg-success">Company Managed</span>
                                    @elseif($booking->management_plan === 'client-managed')
                                        <span class="badge bg-secondary">Client Managed</span>
                                    @elseif($booking->management_plan)
                                        <span class="badge bg-secondary">{{ ucfirst($booking->management_plan) }}</span>
                                    @else
                                        <span class="badge bg-light text-dark border">Not selected</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @php
                                        $statusColors = [
                                            'matching'       => 'info',
                                            'shortlisted'    => 'primary',
                                            'staff_selected' => 'success',
                                            'payment_pending'=> 'warning',
                                            'approved'       => 'success',
                                            'deployed'       => 'dark',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$booking->matching_status] ?? 'secondary' }}">
                                        {{ $booking->matching_status_label }}
                                    </span>
                                </td>

                                {{-- Payment --}}
                                <td>
                                    @php $latestPayment = $booking->bookingPayments->first(); @endphp
                                    @if($latestPayment)
                                        <span class="badge bg-{{ $latestPayment->status === 'completed' ? 'success' : ($latestPayment->status === 'pending_approval' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $latestPayment->status)) }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                {{-- Dates — Fix #3: show start date too --}}
                                <td>
                                    <small class="text-muted d-block">
                                        Booked: {{ $booking->created_at->format('M d, Y') }}
                                    </small>
                                    @if($booking->preferred_start_date)
                                        <small class="text-success d-block">
                                            Start: {{ \Carbon\Carbon::parse($booking->preferred_start_date)->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td>
                                    <a href="{{ route('admin.booking.detail', $booking->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No bookings yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Fix #4: centered pagination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $bookings->links() }}
        </div>

    </div>
@endsection