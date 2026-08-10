@extends ('layouts.applicantmain')
@section('applicantpositions')



    <div class="container mt-5">
        <h2 class="mb-4">Available Job Positions</h2>
        <!-- Scrollable Table -->
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="requests-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Client Name</th>
                        <th class="text-center">Services</th>
                        <th class="text-center">Request Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody id="requests-body">
                    @if ($requests->count() > 0)
                        @foreach ($requests as $index => $request)
                            <!-- Modal Dialog Scrollable -->
                            <div class="modal fade" id="viewprofile{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Profile of {{ $request->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($request->client->profile_photo_path)
                                                <img src="{{ asset('build/assets/image/profile_photo/' . $request->client->profile_photo_path) }}"
                                                    alt="{{ $request->name }}'s photo" class="img-fluid rounded mb-3"
                                                    style="width: 100%; max-height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                                            @else
                                                <x-img src="build/assets/image/slider1.jpg" alt="Profile Photo" class="img-thumbnail" style="width: 100%; max-height: 200px; object-fit: cover;" loading="lazy" decoding="async" />
                                            @endif

                                            <p><strong>Name:</strong> {{ $request->client->name }}
                                                {{ $request->client->last_name }}</p>
                                            <p><strong>Gender:</strong> {{ ucfirst($request->client->gender) }}</p>
                                            <p hidden><strong>Date of Birth:</strong> {{ $request->client->dob }}</p>
                                            <p><strong>State of Origin:</strong> {{ $request->client->state_of_origin }}</p>
                                            <p><strong>State of Residence:</strong>
                                                {{ $request->client->state_of_residence }}</p>

                                            <p hidden>
                                                <strong>Years of Experience:</strong> {{ $request->years_of_experience }}
                                            </p>


                                            <p><strong>Phone Number:</strong> {{ $request->client->phone }}</p>
                                            <p><strong>Email:</strong> <a
                                                    href="mailto:{{ $request->email }}">{{ $request->client->email }}</a>
                                            </p>
                                            <p><strong>Residential Address:</strong> {{ $request->client->address }}
                                            </p>
                                            <p><strong>Landmark:</strong> {{ $request->client->landmark }}</p>


                                            <p><strong>Rating:</strong>
                                                @for ($i = 0; $i < $request->client->rating; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                                @for ($i = 0; $i < 5 - $request->client->rating; $i++)
                                                    <i class="far fa-star text-warning"></i>
                                                @endfor
                                            </p>
                                            <p hidden><strong>Availability:</strong>
                                                {{ $request->client->availability ? 'Available' : 'Not Available' }}</p>
                                            <p hidden><strong>Job Preference:</strong>
                                                {{ $request->client->applicant_type == 'live-in' ? 'Live-in' : ($request->client->applicant_type == 'live-out' ? 'Live-out' : 'Both') }}
                                            </p>
                                            <p hidden><strong>Bio:</strong>
                                                {{ $request->client->bio ?? 'No bio available.' }}</p>

                                            {{-- </div> --}}
                                            {{-- <div class="modal-footer"> --}}


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
                                            @if ($request->status == 'pending')
                                                <!-- Accept button with confirmation -->
                                                <form action="{{ route('requests.accept', $request->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        style="width: 80px;"
                                                        onclick="return confirm('Are you sure you want to accept this request?');">
                                                        Accept
                                                    </button>
                                                </form>

                                                <!-- Reject button with confirmation -->
                                                <form action="{{ route('requests.reject', $request->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        style="width: 80px;"
                                                        onclick="return confirm('Are you sure you want to reject this request?');">
                                                        Reject
                                                    </button>
                                                </form>



                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            @else
                                                <!-- Disable buttons if already accepted or rejected -->
                                                <span class="btn btn-sm btn-secondary" disabled>Action Taken</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left flex2">
                                                <div class="widget-heading">{{ $request->client->name }}
                                                    {{ $request->client->last_name }}
                                                </div>
                                                {{-- <div class="widget-subheading opacity-7">{{ $request->client->email }}</div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- <td class="text-center">{{ $request->message ?? 'No message' }}</td> --}}
                                {{-- @php
                                    use Illuminate\Support\Str;
                                @endphp --}}

                                <td class="text-center">
                                    {{ $request->message ? Str::limit($request->message, 20) : 'No message' }}</td>

                                <td class="text-center">{{ $request->requested_at->format('d M, Y') }}</td>
                                <td class="text-center">
                                    @if ($request->status == 'pending')
                                        <span class="text-warning">Pending</span>
                                    @elseif($request->status == 'accepted')
                                        <span class="text-success">Accepted</span>
                                    @elseif($request->status == 'completed')
                                        <span class="text-primary">Completed</span>
                                    @elseif($request->status == 'cancelled')
                                        <span class="text-danger">Cancelled</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="text-danger">rejected</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($request->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewprofile{{ $request->id }}">
                                            View
                                        </button>
                                        <!-- Accept button with confirmation -->
                                        <form action="{{ route('requests.accept', $request->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Are you sure you want to accept this request?');">
                                                Accept
                                            </button>
                                        </form>

                                        <!-- Reject button with confirmation -->
                                        <form action="{{ route('requests.reject', $request->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to reject this request?');">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewprofile{{ $request->id }}">
                                            View
                                        </button>
                                        <!-- Reject button with confirmation -->
                                        @if ($request->status == 'accepted')


                                            <form action="{{ route('requests.cancel', $request->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to cancel this accepted request?');">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif



                                        <!-- Disable buttons if already accepted or rejected -->
                                        <span class="btn btn-sm btn-secondary" disabled>Action Taken</span>
                                    @endif
                                </td>
                            </tr>
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
    </div>


@endsection
