@extends('layouts.applicantmain')
@section('liveOutServiceRequests')

    <div class="container mt-5">
        <h2 class="mb-4">Available Live-Out Service Requests</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Scrollable Table -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Client Name</th>
                        <th>Service(s)</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Package</th>
                        <th class="text-center">Posted</th>
                        <th class="text-center">Your Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceRequests as $index => $request)
                        @php
                            $myOffer = $request->offers->first();
                            $myStatus = $myOffer ? $myOffer->status : null;
                        @endphp

                        <!-- Detail Modal -->
                        <div class="modal fade" id="viewrequest{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Service Request Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="text-primary fw-bold mb-3">Client Information</h6>
                                        @if ($request->user)
                                            <p><strong>Name:</strong> {{ $request->user->name }} {{ $request->user->last_name }}</p>
                                            <p><strong>Phone:</strong> {{ $request->user->phone ?? 'N/A' }}</p>
                                            <p><strong>Email:</strong> {{ $request->user->email ?? 'N/A' }}</p>
                                            <p><strong>State:</strong> {{ $request->user->state_of_residence ?? 'N/A' }}</p>
                                        @endif

                                        <hr>
                                        <h6 class="text-primary fw-bold mb-3">Service Details</h6>
                                        <p><strong>Services Required:</strong>
                                            {{ is_array($request->serviceName) ? implode(', ', $request->serviceName) : $request->serviceName }}
                                        </p>
                                        <p><strong>Package:</strong> {{ $request->package }}</p>
                                        <p><strong>Location:</strong> {{ $request->state_of_residence }}</p>
                                        <p><strong>Address:</strong> {{ $request->address }}</p>
                                        <p><strong>Landmark:</strong> {{ $request->landmark ?? 'N/A' }}</p>
                                        <p><strong>Working Hours:</strong> {{ $request->working_hour ?? 'N/A' }}</p>
                                        <p><strong>Days Per Week:</strong> {{ $request->days_per_week ?? 'N/A' }}</p>
                                        <p><strong>Service Days:</strong>
                                            {{ is_array($request->service_days) ? implode(', ', $request->service_days) : ($request->service_days ?? 'N/A') }}
                                        </p>
                                        <p><strong>Gender Preference:</strong> {{ $request->gender ?? 'No preference' }}
                                        </p>
                                        @if ($request->details)
                                            <p><strong>Additional Details:</strong> {{ $request->details }}</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        @if (is_null($myStatus))
                                            <form
                                                action="{{ route('liveout.requests.accept', $request->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    style="width: 80px;"
                                                    onclick="return confirm('Accept this service request? The client will be notified.');">
                                                    Accept
                                                </button>
                                            </form>
                                            <form
                                                action="{{ route('liveout.requests.decline', $request->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    style="width: 80px;"
                                                    onclick="return confirm('Decline this service request?');">
                                                    Decline
                                                </button>
                                            </form>
                                        @else
                                            <span class="btn btn-sm btn-secondary" disabled>Action Taken</span>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <tr>
                            <td class="text-center text-muted">
                                {{ ($serviceRequests->currentPage() - 1) * $serviceRequests->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                @if ($request->user)
                                    {{ $request->user->name }} {{ $request->user->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if (is_array($request->serviceName))
                                    {{ implode(', ', array_slice($request->serviceName, 0, 2)) }}
                                    @if (count($request->serviceName) > 2)
                                        <span class="text-muted small">+{{ count($request->serviceName) - 2 }}
                                            more</span>
                                    @endif
                                @else
                                    {{ $request->serviceName ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="text-center">{{ $request->state_of_residence ?? 'N/A' }}</td>
                            <td class="text-center">{{ $request->package ?? 'N/A' }}</td>
                            <td class="text-center">{{ $request->created_at->format('d M, Y') }}</td>
                            <td class="text-center">
                                @if (is_null($myStatus))
                                    <span class="text-muted">Not Responded</span>
                                @elseif($myStatus === 'accepted')
                                    <span class="text-success fw-bold">Accepted</span>
                                @elseif($myStatus === 'declined')
                                    <span class="text-danger">Declined</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#viewrequest{{ $request->id }}">
                                    View
                                </button>
                                @if (is_null($myStatus))
                                    <form action="{{ route('liveout.requests.accept', $request->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Accept this service request? The client will be notified.');">
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('liveout.requests.decline', $request->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Decline this service request?');">
                                            Decline
                                        </button>
                                    </form>
                                @else
                                    <span class="btn btn-sm btn-secondary" disabled>Action Taken</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No available live-out service requests at this time.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-block text-center card-footer">
            <div class="d-flex justify-content-center mt-4">
                {{ $serviceRequests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
