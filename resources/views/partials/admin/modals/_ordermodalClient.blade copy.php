@php
    // Fetch serviceName, service_days, and extra_days from the related service request
    $services = is_string($assignment->serviceRequest->serviceName)
        ? json_decode($assignment->serviceRequest->serviceName, true)
        : $assignment->serviceRequest->serviceName;
    $serviceNames = is_array($services) ? implode(', ', $services) : $services;

    $serviceDays = is_string($assignment->serviceRequest->service_days)
        ? json_decode($assignment->serviceRequest->service_days, true)
        : $assignment->serviceRequest->service_days;
    $serviceDaysList = is_array($serviceDays) ? implode(', ', $serviceDays) : $serviceDays;

    $extraDays = is_string($assignment->serviceRequest->extra_days)
        ? json_decode($assignment->serviceRequest->extra_days, true)
        : $assignment->serviceRequest->extra_days;
    $extraDaysList = is_array($extraDays) ? implode(', ', $extraDays) : $extraDays;

    $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
@endphp

<!-- Modal HTML -->
<div class="modal fade" id="detail{{ $assignment->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    Order Receipt for {{ $assignment->serviceRequest->name }}
                    {{ $assignment->serviceRequest->last_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body / Order Receipt -->
            <div class="modal-body">
                <!-- Order Summary Header -->
                <h4 class="text-center">Order Summary</h4>
                <p class="text-center text-muted">Service: <strong>{{ $serviceNames }}</strong></p>

                <!-- Receipt Details -->
                <div class="receipt-details mt-4">
                    <div class="d-flex justify-content-between">
                        <span><strong>Order ID:</strong></span>
                        <span>#{{ $assignment->serviceRequest->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Order Date:</strong></span>
                        <span>{{ $assignment->serviceRequest->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><strong>Service Type:</strong></span>
                        <span>{{ $serviceNames }}</span>
                    </div>
                    @if (Auth::user()->account_type !== 'applicant')
                        <div class="d-flex justify-content-between">
                            <span><strong>Total Cost:</strong></span>
                            <span>₦{{ number_format($assignment->serviceRequest->grand_cost, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span><strong>Discounted Cost:</strong></span>
                            <span>₦{{ number_format($assignment->serviceRequest->discounted_cost, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>Payment Status:</strong></span>
                            <span>{{ $assignment->serviceRequest->payment_status ? 'Paid' : 'Pending' }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <span><strong>Order Status:</strong></span>
                        <span
                            class="{{ $assignment->serviceRequest->status == 'cancelled' ? 'text-danger' : ($assignment->serviceRequest->status == 'completed' ? 'text-success' : ($assignment->serviceRequest->status == 'pending' ? 'text-warning' : 'text-muted')) }}">
                            {{ ucfirst($assignment->serviceRequest->status) }}
                        </span>
                    </div>

                </div>

                <!-- Service Details Section -->
                <hr>
                <h5 class="text-center mt-3">Service Details</h5>
                <p><strong>Package:</strong> {{ $assignment->serviceRequest->package }}</p>
                <p><strong>Service Day(s):</strong> {{ $serviceDaysList }}</p>
                <p><strong>Extra Day(s):</strong> {{ $extraDaysList }}</p>
                <p><strong>Working Hours:</strong> {{ $assignment->serviceRequest->working_hour }}</p>

                <!-- Apartment Features List -->
                <hr>
                <h5 class="text-center mt-3">Apartment Features:</h5>
                <ul class="list-group">
                    @if (!is_null($assignment->serviceRequest->features))
                        @foreach (json_decode($assignment->serviceRequest->features, true) as $feature => $amount)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucfirst($feature) }}
                                <span>{{ $amount }}</span>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item">No features available.</li>
                    @endif
                </ul>

                <!-- Client Details Section -->
                <hr>
                <h5 class="text-center mt-3">Client Information</h5>
                <div class="client-details">
                    <p><strong>Name:</strong> {{ $assignment->serviceRequest->name }}
                        {{ $assignment->serviceRequest->last_name }}</p>
                    <p><strong>Date of Birth:</strong>
                        {{ $months[$assignment->serviceRequest->dob_month] ?? 'Invalid Month' }}
                        {{ $assignment->serviceRequest->dob_day }}</p>
                    <p><strong>Email:</strong> {{ $assignment->serviceRequest->email }}</p>
                    <p><strong>Phone Number:</strong> {{ $assignment->serviceRequest->phone }}</p>
                    <p><strong>State of Residence:</strong> {{ $assignment->serviceRequest->state }}</p>
                    <p><strong>Address of Residence:</strong> {{ $assignment->serviceRequest->address }}</p>
                    <p><strong>Landmark of Residence:</strong> {{ $assignment->serviceRequest->landmark }}</p>
                </div>

                <!-- Account Details Section -->
                <hr>
                <h5 class="text-center mt-3">Account Owner Information</h5>
                <div class="client-details">
                    <p><strong>Name:</strong> {{ $assignment->serviceRequest->client->name ?? 'N/A' }}</p>
                    {{-- <p><strong>Date of Birth:</strong> {{ $months[$assignment->serviceRequest->dob_month] ?? 'Invalid Month' }} {{ $assignment->serviceRequest->dob_day }}</p> --}}
                    <p><strong>Email:</strong> {{ $assignment->serviceRequest->client->email ?? 'N/A' }}</p>
                    <p><strong>Phone Number:</strong> {{ $assignment->serviceRequest->client->phone ?? 'N/A' }}</p>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
