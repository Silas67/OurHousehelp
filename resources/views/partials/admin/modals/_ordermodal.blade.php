@php

    // Decode serviceName if it's JSON-encoded and convert it to a comma-separated string

$services = is_string($order->serviceName) ? json_decode($order->serviceName, true) : $order->serviceName;

$serviceNames = is_array($services) ? implode(', ', $services) : $services;



// Decode service_days and extra_days if necessary, and convert to comma-separated strings

$serviceDays = $order->service_days;
if (is_string($serviceDays)) {
    $decoded = json_decode($serviceDays, true);
    $serviceDaysList = is_array($decoded) ? implode(', ', $decoded) : $serviceDays;
} elseif (is_array($serviceDays)) {
    $serviceDaysList = implode(', ', $serviceDays);
} else {
    $serviceDaysList = $serviceDays;
}



$extraDays = is_string($order->extra_days) ? json_decode($order->extra_days, true) : $order->extra_days;

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

<div class="modal fade" id="detail{{ $order->id }}" tabindex="-1">

    <div class="modal-dialog modal-dialog-scrollable">

        <div class="modal-content">

            <!-- Modal Header -->

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">

                    Order Receipt for {{ $order->name }} {{ $order->last_name }}

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

                        <span>#{{ $order->id }}</span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span><strong>Order Date:</strong></span>

                        <span>{{ $order->created_at->format('M d, Y H:i') }}</span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span><strong>Service:</strong></span>

                        <span>{{ $serviceNames }}</span>

                    </div>



                    <div class="d-flex justify-content-between">

                        <span><strong>Total Cost:</strong></span>

                        <span>₦{{ number_format($order->grand_cost, 2) }}</span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span><strong>Discounted Cost:</strong></span>

                        <span>₦{{ number_format($order->discounted_cost, 2) }}</span>

                    </div>

                    <div class="d-flex justify-content-between">

                        <span><strong>Payment Status:</strong></span>

                        <span>{{ $order->payment_status ? 'Paid' : 'Pending' }}</span>

                    </div>





                    <div class="d-flex justify-content-between">

                        <span><strong>Order Status:</strong></span>

                        <span

                            class="

                            @if ($order->status == 'cancelled') text-danger

                            @elseif ($order->status == 'completed')

                                text-success

                            @elseif ($order->status == 'pending')

                                text-warning

                            @else

                                text-muted  <!-- Optional: Fallback for any other status --> @endif">

                            {{ ucfirst($order->status) }} <!-- Capitalizes the first letter of the status -->

                        </span>

                    </div>





                </div>



                <!-- Service Details Section -->

                <hr>

                <h5 class="text-center mt-3">Service Details</h5>

                <p><strong>Package:</strong> {{ $order->package }}</p>

                <p><strong>Service Day(s):</strong> {{ $serviceDaysList }}</p>

                {{-- <p><strong>Extra Day(s):</strong> {{ $extraDaysList }}</p> --}}

                <p><strong>Working Hours:</strong> {{ $order->working_hour }}</p>



                <!-- Apartment Features List -->

                <hr>

                <h5 class="text-center mt-3">Apartment Features:</h5>

                <ul class="list-group">

                    {{-- @foreach (json_decode($order->features, true) as $feature => $amount)

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            {{ ucfirst($feature) }}

                            <span>{{ $amount }}</span>

                        </li>

                    @endforeach --}}

                    {{-- @if (!is_null($order->features) && json_decode($order->features) !== null)

                        @foreach (json_decode($order->features, true) as $feature => $amount)

                            <li class="list-group-item d-flex justify-content-between align-items-center">

                                {{ ucfirst($feature) }}

                                <span>{{ $amount }}</span>

                            </li>

                        @endforeach

                    @else

                        <li class="list-group-item">No features available.</li>

                    @endif --}}





                    @php

                        $features = $order->features;



                        if (is_string($features)) {

                            $features = json_decode($features, true);

                        }



                        // Ensure it's now an array

                        $features = is_array($features) ? $features : [];

                    @endphp



                    @if (!empty($features))

                        @foreach ($features as $feature)

                            @if (is_array($feature) && isset($feature['name']))

                                {{-- New format --}}

                                <li class="list-group-item d-flex justify-content-between align-items-center">

                                    <div>

                                        <strong>{{ ucfirst($feature['name']) }}</strong><br>

                                        <small>Qty: {{ $feature['quantity'] }} ×

                                            ₦{{ number_format($feature['unit_cost']) }}</small>

                                    </div>

                                    <span>₦{{ number_format($feature['total_cost']) }}</span>

                                </li>

                            @elseif (is_string($feature))

                                {{-- Optional: handle if somehow a string slips in --}}

                                <li class="list-group-item">{{ $feature }}</li>

                            @elseif (is_numeric($feature))

                                {{-- You can skip or show it as unknown --}}

                                <li class="list-group-item">Unknown Feature - ₦{{ number_format($feature) }}</li>

                            @elseif (is_array($features))

                                {{-- Fallback for old flat format --}}

                                @foreach ($features as $key => $value)

                                    @if (is_numeric($value))

                                        <li class="list-group-item d-flex justify-content-between align-items-center">

                                            {{ ucfirst($key) }}

                                            <span>₦{{ number_format($value) }}</span>

                                        </li>

                                    @endif

                                @endforeach

                                @break

                            @endif

                        @endforeach

                    @else

                        <li class="list-group-item">No features available.</li>

                    @endif





                </ul>



                <!-- Client Details Section -->

                <hr>

                <h5 class="text-center mt-3">Client Information</h5>

                <div class="client-details">

                    <p><strong>Name:</strong> {{ $order->name }} {{ $order->last_name }}</p>

                    <p><strong>Date of Birth:</strong>

                        {{ $months[$order->dob_month] ?? 'Invalid Month' }} {{ $order->dob_day }}</p>

                    <p><strong>Email:</strong> {{ $order->email }}</p>

                    <p><strong>Phone Number:</strong> {{ $order->phone }}</p>

                    <p><strong>State of Residence:</strong> {{ $order->state_of_residence }}</p>

                    <p><strong>Address of Residence:</strong> {{ $order->address }}</p>

                    <p><strong>Landmark of Residence:</strong> {{ $order->landmark }}</p>

                </div>



                <!-- Account Details Section -->

                <hr>

                <h5 class="text-center mt-3">Account Owner Information</h5>

                <div class="client-details">

                    <p><strong>Name:</strong> {{ $order->name ?? 'N/A' }}</p>

                    {{-- <p><strong>Date of Birth:</strong> {{ $months[$assignment->serviceRequest->dob_month] ?? 'Invalid Month' }} {{ $assignment->serviceRequest->dob_day }}</p> --}}

                    <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>

                    <p><strong>Phone Number:</strong> {{ $order->phone ?? 'N/A' }}</p>

                </div>

            </div>



            <!-- Modal Footer -->

            <div class="modal-footer">

                @if (in_array(Auth::user()->user_role, [1, 2]))

                    @if ($order->status == 'pending')

                        <button class="btn btn-success approve-order-btn"

                            data-order-id="{{ $order->id }}"

                            onclick="approveServiceRequest({{ $order->id }}, this)">

                            <i class="fas fa-check"></i> Approve & Notify Staff

                        </button>

                    @elseif ($order->status == 'confirmed')

                        <span class="btn btn-outline-success" disabled>

                            <i class="fas fa-check-circle"></i> Approved

                        </span>

                    @endif

                    <a class="btn btn-primary" href="{{ route('orderAdminDetail', ['id' => $order->id]) }}">More

                        Details</a>

                @endif



                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>

