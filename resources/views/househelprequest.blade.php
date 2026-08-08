@extends('layouts.usermain')
@section('liveinjobrequest')
    <div class="container mt-5">
        <!-- Page Header -->
        <div class="text-center mb-4">
            <h2>Live-In Job Requests</h2>
            <p class="lead">Track your current and past bookings with ease.</p>
        </div>


        @if ($requests->isEmpty())
            <p>No order available at the moment.</p>
        @else
            <!-- Scrollable Table -->
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="requests-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Househelp Name</th>
                            <th class="text-center">Booking Date</th>
                            {{-- <th class="text-center">Status</th> --}}
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requests-body">
                        @if ($requests->count() > 0)
                            @foreach ($requests as $index => $request)
                                <!-- Use $index for numbering -->
                                <tr>
                                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                                    <!-- Increment index for display -->
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">
                                                        <!-- Display the househelp's name -->
                                                        {{ $request->househelp->name }} {{ $request->househelp->last_name }}
                                                    </div>
                                                    @if ($request->status === 'accepted')
                                                        <div class="widget-subheading opacity-7">
                                                            <!-- Display the househelp's email -->
                                                            {{ $request->househelp->email }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $request->created_at->format('d M, Y') }}</td>
                                    {{-- <td class="text-center">
                                        @if ($request->status == 'pending')
                                            <span class="text-warning">Pending</span>
                                        @elseif($request->status == 'accepted')
                                            <span class="text-success">Accepted</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="text-danger">Rejected</span>
                                        @elseif($request->status == 'cancelled')
                                            <span class="text-danger">Cancelled</span>
                                        @endif
                                    </td> --}}

                                    <td class="text-center">
                                        <!-- View details of the request in a modal -->
                                        <button type="button" id="PopoverCustomT-{{ $request->id }}"
                                            data-bs-toggle="modal" data-bs-target="#detail{{ $request->id }}"
                                            class="btn btn-primary btn-sm">
                                            Details
                                        </button>
                                        <!-- Link to cancel the booking -->
                                        @if ($request->status != 'cancelled')
                                            <a href="{{ route('liveinservice.cancel', ['id' => $request->id]) }}"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to cancel this request?');">
                                                Cancel
                                            </a>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal for viewing request details -->
                                <div class="modal fade" id="detail{{ $request->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Profile of {{ $request->househelp->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($request->househelp->profile_photo_path)
                                                    <img src="{{ asset('build/assets/image/profile_photo/' . $request->househelp->profile_photo_path) }}"
                                                        alt="{{ $request->househelp->name }}'s photo"
                                                        class="img-fluid rounded mb-3"
                                                        {{-- style="width: 100%; max-height: 200px; object-fit: cover;" --}}
                                                        >
                                                @else
                                                    <img src="{{ asset('build/assets/image/slider1.jpg') }}"
                                                        alt="Profile Photo" class="img-thumbnail"
                                                        style="width: 100%; max-height: 200px; object-fit: cover;">
                                                @endif

                                                <p><strong>Name:</strong> {{ $request->househelp->name }}
                                                    {{ $request->househelp->last_name }}</p>
                                                <p><strong>Gender:</strong> {{ ucfirst($request->househelp->gender) }}</p>
                                                <p><strong>Date of Birth:</strong> {{ $request->househelp->dob }}</p>
                                                <p><strong>State of Origin:</strong>
                                                    {{ $request->househelp->state_of_origin }}</p>
                                                <p><strong>State of Residence:</strong>
                                                    {{ $request->househelp->state_of_residence }}</p>
                                                <p><strong>Years of Experience:</strong>
                                                    {{ $request->househelp->years_of_experience }}</p>

                                                {{-- @if ($request->househelp->acceptedRequest) --}}
                                                {{-- @if ($request->househelp->activeRequest) --}}
                                                @if ($hasApprovedPayment)
                                                    <p><strong>Phone Number:</strong> {{ $request->househelp->phone }}</p>
                                                    <p><strong>Email:</strong> <a
                                                            href="mailto:{{ $request->househelp->email }}">{{ $request->househelp->email }}</a>
                                                    </p>
                                                    <p><strong>Residential Address:</strong>
                                                        {{ $request->househelp->address }}</p>
                                                    <p><strong>Landmark:</strong> {{ $request->househelp->landmark }}</p>
                                                @else
                                                    <p class="text-danger">🔒 Unlock details by completing your payment.</p>
                                                @endif
                                                {{-- @endif --}}

                                                <p><strong>Rating:</strong>
                                                    @for ($i = 0; $i < $request->househelp->rating; $i++)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endfor
                                                    @for ($i = 0; $i < 5 - $request->househelp->rating; $i++)
                                                        <i class="far fa-star text-warning"></i>
                                                    @endfor
                                                </p>
                                                <p><strong>Availability:</strong>
                                                    {{ $request->househelp->availability ? 'Available' : 'Not Available' }}
                                                </p>
                                                <p><strong>Job Preference:</strong>
                                                    {{ $request->househelp->applicant_type == 'live-in' ? 'Live-in' : ($request->househelp->applicant_type == 'live-out' ? 'Live-out' : 'Both') }}
                                                </p>
                                                <p><strong>Bio:</strong>
                                                    {{ $request->househelp->bio ?? 'No bio available.' }}</p>


                                                <hr style="border: 2px solid green;">

                                                <p><strong>Required Specialties:</strong></p>
                                                <ul>
                                                    @php
                                                        $specialties = json_decode($request->specialties, true);
                                                    @endphp
                                                    @if ($specialties && is_array($specialties) && count($specialties) > 0)
                                                        @foreach ($specialties as $specialty)
                                                            <li>{{ $specialty }}</li>
                                                        @endforeach
                                                    @else
                                                        <li>No specialties listed.</li>
                                                    @endif
                                                </ul>



                                                <div class="form-group mt-2">
                                                    <label for="message" class="pb-2"><strong>Message:</strong></label>
                                                    {{ $request->message }}
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                @if ($request->status != 'cancelled')
                                                    <a href="{{ route('liveinservice.cancel', ['id' => $request->id]) }}"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to cancel this request?');">
                                                        Cancel
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No requests found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-block text-center card-footer">
                <div class="d-flex justify-content-center mt-4">
                    {{ $requests->links('pagination::bootstrap-4') }}
                </div>
            </div>
            <!-- End Scrollable Table -->

        @endif

    </div>

    <script>
        document.getElementById('search-orders').addEventListener('input', function() {
            let query = this.value;

            fetch(`/admin/orders/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let ordersBody = document.getElementById('orders-body');
                    ordersBody.innerHTML = '';

                    if (data.orders.length > 0) {
                        data.orders.forEach(order => {
                            let row = `
                                <tr>
                                    <td class="text-center text-muted">${order.id}</td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">${order.first} ${order.last_name}</div>
                                                    <div class="widget-subheading opacity-7">${order.email}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${order.state}</td>
                                    <td class="text-center">${order.gender.charAt(0).toUpperCase() + order.gender.slice(1)}</td>
                                    <td class="text-center">₦${parseFloat(order.grand_cost).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="text-center">
                                        <button type="button" id="PopoverCustomT-${order.id}" class="btn btn-primary btn-sm">Details</button>
                                    </td>
                                </tr>`;
                            ordersBody.innerHTML += row;
                        });
                    } else {
                        ordersBody.innerHTML =
                            `<tr><td colspan="7" class="text-center">No orders found.</td></tr>`;
                    }
                });
        });
    </script>
    </div>
@endsection
