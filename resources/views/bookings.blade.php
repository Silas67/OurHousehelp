@extends('layouts.usermain')

@section('bookings')





    <div class="container mt-4">

        {{-- In-progress bookings surfaced at the top, so a client can always
             get back into a shortlist they navigated away from. --}}
        <x-resume-booking />

        @if (session('warning'))

            <div class="alert alert-warning alert-dismissible fade show" role="alert">

                <i class="fas fa-exclamation-triangle me-2"></i>

                {{ session('warning') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>

        @endif



        @if (session('info'))

            <div class="alert alert-info alert-dismissible fade show" role="alert">

                <i class="fas fa-info-circle me-2"></i>

                {{ session('info') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>

        @endif

        <!-- Registration Status Banner -->
        {{-- @auth

            @php

                $user = auth()->user();

                $registrationStatus = $user->getRegistrationStatus();

                $daysUntilExpiration = $user->getDaysUntilExpiration();



                // Format days for display

                if ($daysUntilExpiration > 0) {

                    if ($daysUntilExpiration >= 30) {

                        $months = floor($daysUntilExpiration / 30);

                        $remainingDays = $daysUntilExpiration % 30;

                        if ($remainingDays > 0) {

                            $expirationText =

                                "{$months} month" .

                                ($months > 1 ? 's' : '') .

                                " and {$remainingDays} day" .

                                ($remainingDays > 1 ? 's' : '');

                        } else {

                            $expirationText = "{$months} month" . ($months > 1 ? 's' : '');

                        }

                    } elseif ($daysUntilExpiration >= 7) {

                        $weeks = floor($daysUntilExpiration / 7);

                        $remainingDays = $daysUntilExpiration % 7;

                        if ($remainingDays > 0) {

                            $expirationText =

                                "{$weeks} week" .

                                ($weeks > 1 ? 's' : '') .

                                " and {$remainingDays} day" .

                                ($remainingDays > 1 ? 's' : '');

                        } else {

                            $expirationText = "{$weeks} week" . ($weeks > 1 ? 's' : '');

                        }

                    } else {

                        $expirationText = "{$daysUntilExpiration} day" . ($daysUntilExpiration > 1 ? 's' : '');

                    }

                }

            @endphp



            @if ($registrationStatus === 'pending')

                <div class="alert alert-warning mb-4">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-clock fa-2x me-3"></i>

                        <div>

                            <h5 class="mb-1"><strong>Registration Pending Approval</strong></h5>

                            <p class="mb-0">Your registration is currently under review. You will be notified once approved.

                            </p>

                        </div>

                    </div>

                </div>

            @elseif ($registrationStatus === 'active')

                <div class="alert alert-success mb-4">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-check-circle fa-2x me-3"></i>

                        <div class="flex-grow-1">

                            <h5 class="mb-1"><strong>Registration Active</strong></h5>

                            @if ($daysUntilExpiration > 0)

                                <div class="row align-items-center">

                                    <div class="col-md-8">

                                        <div class="d-flex align-items-center">

                                            <div class="progress flex-grow-1" style="height: 10px;">

                                                @php

                                                    $percentage = min(100, ($daysUntilExpiration / 365) * 100);

                                                    $progressColor =

                                                        $percentage > 50

                                                            ? 'bg-success'

                                                            : ($percentage > 25

                                                                ? 'bg-warning'

                                                                : 'bg-danger');

                                                @endphp

                                                <div class="progress-bar {{ $progressColor }}" role="progressbar"

                                                    style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"

                                                    aria-valuemin="0" aria-valuemax="100">

                                                </div>

                                            </div>

                                            <div class="ms-3">

                                                <small class="text-muted">{{ round($percentage, 1) }}% remaining</small>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">

                                        <span class="badge bg-success">

                                            <i class="fas fa-calendar-alt me-1"></i> {{ $expirationText }} left

                                        </span>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @elseif ($registrationStatus === 'expired')

                <div class="alert alert-danger mb-4">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>

                        <div>

                            <h5 class="mb-1"><strong>Registration Expired</strong></h5>

                            <p class="mb-2">Your registration has expired. Please renew to continue using our services.</p>

                            <div class="mt-2">

                                <a href="{{ route('service-request.registration') }}" class="btn btn-danger btn-sm">

                                    <i class="fas fa-sync-alt me-2"></i> Renew Registration

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="alert alert-warning mb-4">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-user-check fa-2x me-3"></i>

                        <div>

                            <h5 class="mb-1"><strong>Registration Required</strong></h5>

                            <p class="mb-2">Complete your registration to activate service requests.</p>

                            <div class="mt-2">

                                <a href="{{ route('service-request.registration') }}" class="btn btn-warning btn-sm">

                                    <i class="fas fa-user-check me-2"></i> Complete Registration

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endif

        @endauth --}}



        <!-- Simple Header -->

        <div class="mb-4">

            <h3 class="fw-bold">My Bookings</h3>

            <p class="text-muted">View all your service requests and assigned jobs</p>

        </div>



        <!-- Simple Table -->

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 50px;">#</th>

                                <th>Type</th>

                                <th>Services</th>

                                {{-- <th>Package</th> --}}

                                <th>Status</th>

                                <th>Date</th>

                                <th>Actions</th>

                            </tr>

                        </thead>

                        <tbody>

                            @php

                                $allBookings = [];

                                // Add orders

                                foreach ($orders as $order) {

                                    $allBookings[] = [

                                        'type' => 'order',

                                        'data' => $order,

                                        'date' => $order->created_at,

                                    ];

                                }

                                // Add assignments

                                foreach ($assignments as $assignment) {

                                    $allBookings[] = [

                                        'type' => 'assignment',

                                        'data' => $assignment,

                                        'date' => $assignment->assigned_at ?? $assignment->created_at,

                                    ];

                                }

                                // Sort by date (newest first)

                                usort($allBookings, function ($a, $b) {

                                    return strtotime($b['date']) - strtotime($a['date']);

                                });

                            @endphp



                            @if (count($allBookings) > 0)

                                @foreach ($allBookings as $index => $booking)

                                    @if ($booking['type'] == 'order')

                                        @php $order = $booking['data']; @endphp

                                        <tr>

                                            <td>{{ $index + 1 }}</td>

                                            <td>

                                                <span class="badge bg-primary">Request</span>

                                            </td>

                                            <td>
                                                <small>{{ is_array($order->serviceName) ? implode(', ', $order->serviceName) : (is_string($order->serviceName) ? implode(', ', json_decode($order->serviceName, true) ?? [$order->serviceName]) : 'N/A') }}</small>
                                            </td>

                                            {{-- <td>{{ $order->package }}</td> --}}

                                            <td>

                                                <span

                                                    class="badge bg-{{ $order->status == 'confirmed' ? 'success' : 'warning' }}">

                                                    {{ ucfirst($order->status) }}

                                                </span>

                                            </td>

                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>

                                            <td>

                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"

                                                    data-bs-target="#orderModal{{ $order->id }}">

                                                    View

                                                </button>

                                            </td>

                                        </tr>

                                    @else

                                        @php $assignment = $booking['data']; @endphp

                                        <tr>

                                            <td>{{ $index + 1 }}</td>

                                            <td>

                                                <span class="badge bg-success">Assignment</span>

                                            </td>

                                            <td>
                                                <small>{{ $assignment->serviceRequest->package ?? 'N/A' }}</small>
                                            </td>

                                            {{-- <td>{{ $assignment->serviceRequest->package ?? 'N/A' }}</td> --}}

                                            <td>

                                                <span

                                                    class="badge bg-{{ $assignment->status == 'confirmed' ? 'success' : 'warning' }}">

                                                    {{ ucfirst($assignment->status) }}

                                                </span>

                                            </td>

                                            <td>

                                                @if ($assignment->assigned_at)

                                                    {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y') }}

                                                @else

                                                    {{ \Carbon\Carbon::parse($assignment->created_at)->format('M d, Y') }}

                                                @endif

                                            </td>

                                            <td>

                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"

                                                    data-bs-target="#assignmentModal{{ $assignment->id }}">

                                                    View

                                                </button>

                                            </td>

                                        </tr>

                                    @endif

                                @endforeach

                            @else

                                <tr>

                                    <td colspan="6" class="text-center py-4">

                                        <p class="text-muted mb-0">No bookings found</p>

                                    </td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>

        </div>



        <!-- Simple Modals for Orders -->

        @foreach ($orders as $order)

            <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">Service Request Details</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <small class="text-muted">Client Name</small>

                                <p class="fw-bold">{{ $order->name }} {{ $order->last_name }}</p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Package</small>

                                <p class="fw-bold">{{ $order->package }}</p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Status</small>

                                <p>

                                    <span class="badge bg-{{ $order->status == 'confirmed' ? 'success' : 'warning' }}">

                                        {{ ucfirst($order->status) }}

                                    </span>

                                </p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Payment Status</small>

                                <p>

                                    <span class="badge bg-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">

                                        {{ $order->payment_status }}

                                    </span>

                                </p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Total Cost</small>

                                <p class="fw-bold text-primary">₦{{ number_format($order->grand_cost, 2) }}</p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Created Date</small>

                                <p>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}</p>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            @if ($order->status == 'pending')

                                <a href="{{ route('booking.cancel', ['id' => $order->id]) }}" class="btn btn-danger"

                                    onclick="return confirm('Cancel this request?')">

                                    Cancel

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @endforeach



        <!-- Simple Modals for Assignments -->

        @foreach ($assignments as $assignment)

            <div class="modal fade" id="assignmentModal{{ $assignment->id }}" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">Assigned Job Details</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <small class="text-muted">Client Name</small>

                                <p class="fw-bold">{{ $assignment->serviceRequest->name ?? 'N/A' }}

                                    {{ $assignment->serviceRequest->last_name ?? '' }}</p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Package</small>

                                <p class="fw-bold">{{ $assignment->serviceRequest->package ?? 'N/A' }}</p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Assigned Date</small>

                                <p>

                                    @if ($assignment->assigned_at)

                                        {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y h:i A') }}

                                    @else

                                        Not assigned

                                    @endif

                                </p>

                            </div>

                            <div class="mb-3">

                                <small class="text-muted">Status</small>

                                <p>

                                    <span

                                        class="badge bg-{{ $assignment->status == 'confirmed' ? 'success' : 'warning' }}">

                                        {{ ucfirst($assignment->status) }}

                                    </span>

                                </p>

                            </div>



                            @if ($assignment->houseHelp || $assignment->houseHelpAssist)

                                <div class="mb-3">

                                    <small class="text-muted">Assigned Staff</small>

                                    <div class="mt-2">

                                        @if ($assignment->houseHelp)

                                            <div class="mb-2">

                                                <small class="text-muted">Primary House Help:</small>

                                                <p class="mb-0">{{ $assignment->houseHelp->name }}</p>

                                                <small class="text-muted">{{ $assignment->houseHelp->phone }}</small>

                                            </div>

                                        @endif

                                        @if ($assignment->houseHelpAssist)

                                            <div>

                                                <small class="text-muted">Assistant:</small>

                                                <p class="mb-0">{{ $assignment->houseHelpAssist->name }}</p>

                                                <small

                                                    class="text-muted">{{ $assignment->houseHelpAssist->phone }}</small>

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            @endif

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            @if ($assignment->status == 'confirmed')

                                <a href="{{ route('booking.cancel', ['id' => $assignment->id]) }}" class="btn btn-danger"

                                    onclick="return confirm('Cancel this assignment?')">

                                    Cancel

                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>



    <style>

        .table th {

            font-weight: 600;

            color: #495057;

            border-bottom: 2px solid #dee2e6;

        }



        .table td {

            vertical-align: middle;

        }



        .badge {

            padding: 0.35em 0.65em;

            font-weight: 500;

        }



        .modal-header {

            background-color: #f8f9fa;

            border-bottom: 1px solid #dee2e6;

        }



        .btn-outline-primary {

            border-color: #0d6efd;

            color: #0d6efd;

        }



        .btn-outline-primary:hover {

            background-color: #0d6efd;

            color: white;

        }

    </style>



    <script>

        // Simple row hover effect

        document.addEventListener('DOMContentLoaded', function() {

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {

                row.addEventListener('mouseenter', function() {

                    this.style.backgroundColor = '#f8f9fa';

                });

                row.addEventListener('mouseleave', function() {

                    this.style.backgroundColor = '';

                });

            });

        });

    </script>

@endsection

