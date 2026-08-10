@extends('layouts.adminmain')



@section('orderAdminDetail')

    @php

        // Decode serviceName if it's JSON-encoded and convert it to a comma-separated string

$services = is_string($order->serviceName) ? json_decode($order->serviceName, true) : $order->serviceName;

$serviceNames = is_array($services) ? implode(', ', $services) : $services;



// Decode service_days and extra_days if necessary

$serviceDays = $order->service_days;
if (is_string($serviceDays)) {
    $decoded = json_decode($serviceDays, true);
    $serviceDaysList = is_array($decoded) ? implode(', ', $decoded) : $serviceDays;
} elseif (is_array($serviceDays)) {
    $serviceDaysList = implode(', ', $serviceDays);
} else {
    $serviceDaysList = $serviceDays;
}



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



// Status colors

$statusColors = [

    'cancelled' => 'danger',

    'completed' => 'success',

    'pending' => 'warning',

    'confirmed' => 'primary',

    'paid' => 'info',

        ];

    @endphp



    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="h4 mb-1">Order #{{ $order->id }}</h2>

                <span class="text-muted">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>

            </div>

            <span class="badge bg-{{ $statusColors[strtolower($order->status)] ?? 'secondary' }} fs-6 py-2 px-3">

                {{ ucfirst($order->status) }}

            </span>

        </div>



        <div class="row">

            <!-- Customer Information -->

            <div class="col-lg-6 mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-light">

                        <h5 class="mb-0">Customer Information</h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Full Name</label>

                                <p class="mb-0">{{ $order->name }} {{ $order->middle_name ?? '' }}

                                    {{ $order->last_name }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Email</label>

                                <p class="mb-0">{{ $order->email }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Phone</label>

                                <p class="mb-0">{{ $order->phone }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Date of Birth</label>

                                <p class="mb-0">{{ $months[$order->dob_month] ?? 'N/A' }} {{ $order->dob_day }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Country</label>

                                <p class="mb-0">{{ $order->country_of_residence }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">State</label>

                                <p class="mb-0">{{ $order->state_of_residence }}</p>

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label text-muted small mb-1">Address</label>

                                <p class="mb-0">{{ $order->address }}</p>

                                @if ($order->landmark)

                                    <small class="text-muted">Landmark: {{ $order->landmark }}</small>

                                @endif

                            </div>

                            @if ($order->nin)

                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted small mb-1">National ID (NIN)</label>

                                    <p class="mb-0">{{ $order->nin }}</p>

                                </div>

                            @endif

                            @if ($order->on_behalf)

                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted small mb-1">Booking For</label>

                                    <p class="mb-0">On behalf of someone else</p>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>



            <!-- Service Details -->

            <div class="col-lg-6 mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-light">

                        <h5 class="mb-0">Service Details</h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label text-muted small mb-1">Services</label>

                            <p class="mb-0">{{ $serviceNames }}</p>

                        </div>



                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Package</label>

                                <p class="mb-0">{{ $order->package }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Gender Preference</label>

                                <p class="mb-0">{{ ucfirst($order->gender) }}</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Days Per Week</label>

                                <p class="mb-0">{{ $order->days_per_week }} days</p>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Service Days</label>

                                <p class="mb-0">{{ $serviceDaysList }}</p>

                            </div>

                            @if ($order->working_hour)

                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted small mb-1">Working Hours</label>

                                    <p class="mb-0">{{ $order->working_hour }}</p>

                                </div>

                            @endif

                        </div>



                        @if ($order->details)

                            <div class="mb-3">

                                <label class="form-label text-muted small mb-1">Special Instructions</label>

                                <p class="mb-0">{{ $order->details }}</p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>



        <!-- Pricing Breakdown -->

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">Pricing Breakdown</h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table">

                        <thead>

                            <tr>

                                <th width="60%">Item</th>

                                <th class="text-end">Cost</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>Base Package Cost</td>

                                <td class="text-end">₦{{ number_format($order->base_cost, 2) }}</td>

                            </tr>



                            @php

                                $features = [];



                                if (!empty($order->features)) {

                                    $decoded = json_decode($order->features, true);

                                    $features = is_array($decoded) ? $decoded : [];

                                }

                            @endphp



                            @if (!empty($features))

                                @foreach ($features as $feature)

                                    @if (is_array($feature) && isset($feature['name'], $feature['quantity'], $feature['unit_cost'], $feature['total_cost']))

                                        <tr>

                                            <td>

                                                {{ ucfirst($feature['name']) }}

                                                <small class="text-muted">({{ $feature['quantity'] }} ×

                                                    ₦{{ number_format($feature['unit_cost']) }})</small>

                                            </td>

                                            <td class="text-end">₦{{ number_format($feature['total_cost']) }}</td>

                                        </tr>

                                    @endif

                                @endforeach

                            @endif





                            <tr>

                                <td>Service Charge</td>

                                <td class="text-end">

                                    {{-- {{ $serviceCharge->currency }} --}}

                                    ₦{{ number_format($serviceCharge->amount, 2) }}</td>

                            </tr>



                            <tr class="fw-bold">

                                <td>Total Cost</td>

                                <td class="text-end">₦{{ number_format($order->grand_cost, 2) }}</td>

                            </tr>



                            @if ($order->discounted_cost && $order->discounted_cost != $order->grand_cost)

                                <tr class="text-success">

                                    <td>Discount Applied</td>

                                    <td class="text-end">

                                        ₦{{ number_format($order->discounted_cost, 2) }}</td>

                                </tr>

                                <tr class="fw-bold">

                                    <td>Final Amount Due</td>

                                    <td class="text-end">

                                        ₦{{ number_format($order->grand_cost + $order->discounted_cost, 2) }}</td>

                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>



                <div class="d-flex justify-content-between align-items-center mt-4">

                    <div>

                        <span class="text-muted me-2">Payment Status:</span>

                        <span class="badge bg-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">

                            {{ $order->payment_status }}

                        </span>

                    </div>



                    @if ($order->status == 'pending' || $order->status == 'confirmed')

                        <div>

                            <a href="{{ route('confirmation.show', $order->id) }}" class="btn btn-primary">

                                <i class="fas fa-check-circle me-2"></i>Confirm Order

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Staff Salary Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Staff Salary Details</h5>
                <span class="badge bg-{{ $order->management_plan === 'company-managed' ? 'success' : 'secondary' }}">
                    {{ $order->management_plan === 'company-managed' ? 'Company Managed' : 'Client Managed' }}
                </span>
            </div>
            <div class="card-body">
                @php
                    use App\Services\StaffSalaryService;
                    $salaryService = new StaffSalaryService();
                    $staffSalary = $salaryService->getStaffSalary($order);
                @endphp

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#E1F5EE;border-left:4px solid #0F6E56;">
                            <div class="text-muted small mb-1">Monthly Staff Salary</div>
                            <div class="fs-4 fw-bold" style="color:#0F6E56;">₦{{ number_format($staffSalary) }}</div>
                            <small class="text-muted">Per month</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small mb-1">Who Pays Staff</div>
                            <div class="fw-semibold">
                                @if($order->management_plan === 'client-managed')
                                    <i class="fas fa-user text-warning me-1"></i>Client pays directly
                                @else
                                    <i class="fas fa-building text-primary me-1"></i>OurHouseHelp pays
                                @endif
                            </div>
                            <small class="text-muted">
                                @if($order->management_plan === 'company-managed')
                                    Collected from client payment
                                @else
                                    No payment to OurHouseHelp
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded bg-light">
                            <div class="text-muted small mb-1">Duration</div>
                            <div class="fw-semibold">{{ $order->duration_weeks ?? 'N/A' }} weeks</div>
                            <small class="text-muted">
                                Total: ₦{{ number_format($staffSalary * (($order->duration_weeks ?? 4) / 4)) }}
                            </small>
                        </div>
                    </div>
                </div>

                @if($order->management_plan === 'company-managed')
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Client pays <strong>₦{{ number_format($order->grand_cost) }}</strong> to OurHouseHelp.
                    After the 20% platform fee, staff receives <strong>₦{{ number_format($staffSalary) }}/month</strong>.
                </div>
                @endif
            </div>
        </div>



        <!-- Documents Section -->

        @if ($order->passport || $order->id_card)

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-light">

                    <h5 class="mb-0">Uploaded Documents</h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        @if ($order->passport)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">Passport Photo</label>

                                <div class="border p-2 rounded">

                                    <img src="{{ \App\Support\SecureDocument::url('passports', $order->passport) }}" alt="Passport Photo"

                                        class="img-fluid" style="max-height: 200px;" loading="lazy" decoding="async">

                                    <a href="{{ \App\Support\SecureDocument::url('passports', $order->passport) }}" target="_blank"

                                        class="d-block mt-2">View Full Image</a>

                                </div>

                            </div>

                        @endif



                        @if ($order->id_card)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">ID Card</label>

                                <div class="border p-2 rounded">

                                    <img src="{{ \App\Support\SecureDocument::url('idcards', $order->id_card) }}" alt="ID Card"

                                        class="img-fluid" style="max-height: 200px;" loading="lazy" decoding="async">

                                    <a href="{{ \App\Support\SecureDocument::url('idcards', $order->id_card) }}" target="_blank"

                                        class="d-block mt-2">View Full Image</a>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @endif

    </div>

@endsection

