@extends('layouts.main')
@section('orderreceipt')
    <div class="container py-5">

        @php
            use App\Models\Service;

            // Decode JSON if necessary
            $serviceIds = is_string($order->serviceName) ? json_decode($order->serviceName, true) : $order->serviceName;

            // Ensure it's an array
$serviceIds = is_array($serviceIds) ? $serviceIds : [$serviceIds];

// Fetch the corresponding service names from DB
$serviceNames = Service::whereIn('id', $serviceIds)->pluck('service_name')->toArray();

// Convert to comma-separated string
$serviceNamesList = implode(', ', $serviceNames);
        @endphp

        <h4>Order Summary Receipt for <br>
            <span>{{ $serviceNamesList }}</span>
            <br>service
        </h4>



        <div class="card">
            <div class="card-body">
                <h3 class="display-6">Order Details - ID: {{ $order->id }}</h3>
                @if (request()->routeIs('orderreceipt'))
                    @php
                        // Decode serviceName if it's a JSON string
$services = is_string($order->serviceName)
    ? json_decode($order->serviceName, true)
    : $order->serviceName;

// Check if $services is an array, and convert it to a comma-separated string
$serviceNames = is_array($services) ? implode(', ', $services) : $services;
                    @endphp

                    <a href="https://api.whatsapp.com/send?phone=2349167696525&text={{ urlencode('I placed an order with Details: ID: ' . $order->id . ', Service: ' . $serviceNames . ', Grand Total: ₦' . number_format($order->grand_cost, 2)) }}"
                        target="_blank" class="btn btn-success">
                        Share to WhatsApp
                    </a>
                @endif
                <hr>

                <div class="row">
                    <!-- Client's Personal Details -->
                    <div class="col-md-6">
                        <h3>Client's Personal Detail</h3>
                        <p><strong>First Name:</strong> {{ $order->name }}</p>
                        <p><strong>Last Name:</strong> {{ $order->last_name }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p hidden><strong>User Id:</strong> {{ $order->user_id }}</p>

                        @php
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

                        <p><strong>Date of Birth:</strong> {{ $months[$order->dob_month] ?? 'Invalid Month' }}
                            {{ $order->dob_day }}</p>
                        <p><strong>Client's State of Residence:</strong> {{ $order->state_of_residence }}</p>
                        <p><strong>Address:</strong> {{ $order->address }}</p>
                        <p><strong>Landmark:</strong> {{ $order->landmark }}</p>
                        <p hidden><strong>NIN:</strong> {{ $order->nin }}</p> <!-- NIN Field -->
                        <p><strong>On Behalf of someone else?:</strong> {{ $order->on_behalf ?? 'Not specified' }}</p>
                        <!-- New field for on_behalf -->


                    </div>

                    <!-- Service Details -->
                    <div class="col-md-6">
                        <h3>Service Detail</h3>
                        <p><strong>Package:</strong> {{ $order->package }}</p>


                        @php
                            $serviceDays = is_string($order->service_days)
                                ? json_decode($order->service_days, true)
                                : $order->service_days;
                        @endphp

                        {{-- <p><strong>Service Days:</strong> {{ implode(', ', $serviceDays) }}</p> --}}
                        @php
                            $serviceDaysArray = is_string($serviceDays)
                                ? json_decode($serviceDays, true)
                                : $serviceDays;
                        @endphp

                        <p><strong>Service Days:</strong> {{ implode(', ', $serviceDaysArray ?? []) }}</p>

                        <p><strong>Gender:</strong> {{ $order->gender }}</p>

                        @if ($order->working_hour)
                            <p><strong>Selected Working Hours:</strong> {{ $order->working_hour }}</p>
                        @else
                            <p>No working hours selected.</p>
                        @endif

                        @if (!$order->serviceName)
                            <h4>Apartment Features:</h4>
                            @if ($order->features && !empty(json_decode($order->features, true)))
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($order->features, true) as $feature => $amount)
                                            <tr>
                                                <td>{{ ucfirst($feature) }}</td>
                                                <td>{{ $amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No apartment features selected.</p>
                            @endif

                            <!-- Apartment Feature Cost -->
                            <p><strong>Apartment Cost:</strong> ₦{{ number_format($order->feature_cost, 2) }}</p>
                        @endif

                        @if ($order->details)
                            <p><strong>Details:</strong> {{ $order->details }}</p>
                        @endif


                        <!-- Grand Total of everything -->
                        <h4><strong>Grand Total:</strong> ₦{{ number_format($order->grand_cost, 2) }}</h4>

                        <!-- Service Charge -->
                        @php
                            // Check service charge from relationship or fallback
                            $serviceChargeAmount = optional($order->serviceCharge)->amount;

                            // If not set in relationship, try fetching it from table dynamically
                            if (empty($serviceChargeAmount)) {
                                $serviceChargeAmount = \App\Models\ServiceCharge::value('amount'); // gets the first amount in the table
                            }

                            // Final fallback if table or relationship missing
                            $serviceChargeAmount = $serviceChargeAmount ?: 10000;
                        @endphp

                        <p>
                            <strong>Service Charge:</strong>
                            ₦{{ number_format($serviceChargeAmount, 2) }}
                        </p>


                        <h5 class="text-danger">Congratulations!!! You have a Coupon</h5>
                        <p>You have been awarded <strong>{{ $order->bonus_slots_total }}</strong> slot(s) to use our
                            Skilled Workers service. You’ll be able to use them once your booking is approved.</p>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Payment Instructions Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h3>Payment Instructions</h3>
                    <p>Please make your payment using the following bank details:</p>
                    <p style="margin-bottom: 0px"><strong>Account Number:</strong> </p>
                    <h3>0051480410</h3>
                    <p style="margin-bottom: 0px"><strong>Account Name:</strong></p>
                    <h3> Pik and Crest Dynamic Concepts LTD</h3>
                    <p style="margin-bottom: 0px"><strong>Bank:</strong></p>
                    <h3> GT Bank</h3>
                    <p>Ensure to include your order ID <strong>{{ $order->id }}</strong> as the payment reference.</p>

                    <p>After making the payment, kindly send the payment confirmation or receipt to our customer service via
                        email at <br><strong><a href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a></strong> or
                        call
                        <strong>
                            <a href="tel:+2349167696525">+234 916 7696 525</a>,
                            <a href="tel:+2347066663868">+234 706 6663 868</a>
                        </strong>.
                    </p>

                    <p>Once your payment is confirmed, your service will be scheduled accordingly.</p>

                    <!-- New note about verification -->
                    <p class="text-danger"><strong>Note:</strong> After all verifications, including payment confirmation
                        and ID
                        checks, we will contact you with further details regarding your service schedule.</p>
                </div>
            </div>

            <hr>

            @include('partials._nextstepprint')
        </div>
    @endsection
