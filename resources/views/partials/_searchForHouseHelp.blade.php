<h5>Search for Househelps</h5>
@if (Auth::check() && isset($hasApprovedPayment) && $hasApprovedPayment)    
    @include('partials._subscribe')
@endif
<br>
<br>

<!-- Search Form -->
<form method="GET" action="{{ route('ourLiveInServices') }}" class="mb-4">
    <div class="row g-2">
        <div class="col-6 col-md-3">
            <select name="gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        {{-- <div class="col-6 col-md-3">
            <select name="state_of_origin" class="form-select">
                <option value="">Select State of Origin</option>
                @foreach ($states as $state)
                    <option value="{{ $state }}" {{ request('state_of_origin') == $state ? 'selected' : '' }}>
                        {{ $state }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="col-6 col-md-3">
            <select name="state_of_residence" class="form-select">
                <option value="">Select State of Residence</option>
                @foreach ($states as $state)
                    <option value="{{ $state }}" {{ request('state_of_residence') == $state ? 'selected' : '' }}>
                        {{ $state }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-6 col-md-1">
            @if (request()->routeIs('househelps'))
                <a href="{{ route('househelps') }}" class="btn btn-secondary w-100">Clear</a>
            @else
                <a href="{{ route('ourLiveInServices') }}" class="btn btn-secondary w-100">Clear</a>
            @endif
        </div>

        {{-- @if ($hasApprovedPayment)
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-6 col-md-1">
                @if (request()->routeIs('househelps'))
                    <a href="{{ route('househelps') }}" class="btn btn-secondary w-100">Clear</a>
                @else
                    <a href="{{ route('ourLiveInServices') }}" class="btn btn-secondary w-100">Clear</a>
                @endif
            </div>
        @else
            <div class="col-6 col-md-2">
                @if ($hasApprovedPayment && $payment->status === pending)
                    <a class="btn btn-info w-100" >🔒 Waiting for Approval</a>
                @else
                    <a class="btn btn-primary w-100" href="{{ route('payments.paymentoption') }}">🔒 Search</a>
                @endif
            </div>
            <div class="col-6 col-md-1">
                <a class="btn btn-secondary w-100">Clear</a>
            </div>
        @endif --}}
    </div>
</form>



<!-- Table -->
<div class="table-responsive">
    @if ($househelps->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No house helps found matching your search criteria. Please expad or narrow your search.
        </div>
    @else
        {{-- @if ($hasApprovedPayment) --}}
        <table class="table table-bordered table-hover">
            <thead class="bg-success text-white">
                <tr>
                    {{-- <th>Photo</th> --}}
                    <th>Name</th>
                    <th>Avg Rating</th>
                    {{-- <th>Availability</th> --}}
                    <th>Profile</th>
                    <th>Request</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($househelps as $househelp)
                    <tr>
                        {{-- <td>
                        <img src="{{ $househelp->profile_photo_path ? asset('build/assets/image/profile_photo/' . $househelp->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"
                             alt="{{ $househelp->name }}'s photo" class="img-fluid rounded" style="width: 50px; height: 50px; cursor: pointer"
                             data-bs-toggle="modal" data-bs-target="#viewpictureprofile{{ $househelp->id }}" loading="lazy" decoding="async">
                    </td> --}}
                        <td>{{ $househelp->name }}</td>
                        <td>
                            {{ number_format($househelp->averageRating, 1) ?? 'No ratings yet' }}
                        </td>
                        {{-- <td>
                        {{ $househelp->availability ? 'Available' : 'Not Available' }}
                    </td> --}}
                        <td>
                            {{-- @if ($hasApprovedPayment) --}}
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#viewprofile{{ $househelp->id }}">View</a>
                            {{-- @else
                                <a class="btn btn-sm btn-primary">🔒 Subscribe to View</a>
                            @endif --}}
                        </td>

                        <td class="text-center">
                            @if ($househelp->activeRequest)
                                <p class="text-secondary" disabled>Sent</p>
                            @elseif ($househelp->acceptedRequest)
                                <p class="text-secondary" disabled>Accepted</p>
                            @else
                                <p class="text-primary">No Request</p>
                            @endif
                        </td>
                    </tr>
                    @if ($hasApprovedPayment)
                        <!-- Applicant picture Modal -->
                        <div class="modal fade" id="viewpictureprofile{{ $househelp->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Picture of {{ $househelp->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) }}"
                                            alt="{{ $househelp->name }}'s photo" class="img-fluid rounded mb-3"
                                            style="width: 100%; max-height: 200px; object-fit: contain;" loading="lazy" decoding="async">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Applicant profile Modal -->
                    <div class="modal fade" id="viewprofile{{ $househelp->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Profile of {{ $househelp->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{-- @if ($pendingPayment)
                                        <div>
                                            <p>
                                                🔒 Your account is currently pending approval. If you require immediate
                                                assistance, please contact our support team.
                                                </br>
                                                Call us at:
                                                <a href="tel:+2349167696525">+234 916 7696 525</a> or
                                                <a href="tel:+2349091144474">+234 909 1144 474</a>
                                            </p>
                                        </div>
                                    @endif --}}
                                    @include('partials._subscribe')
                                    <br>

                                    <img src="{{ asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) }}"
                                        alt="{{ $househelp->name }}'s photo" class="img-fluid rounded mb-3"
                                        style="width: 100%; max-height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                                    <p><strong>Name:</strong> {{ $househelp->name }} {{ $househelp->last_name }}
                                    </p>
                                    <p><strong>Gender:</strong> {{ ucfirst($househelp->gender) }}</p>
                                    <p><strong>Experience:</strong> {{ $househelp->years_of_experience }} years</p>

                                    {{-- @if ($househelp->acceptedRequest) --}}
                                    {{-- @if ($househelp->activeRequest)
                                    <p><strong>Date of Birth:</strong> {{ $househelp->dob }}</p>
                                    <p><strong>State of Origin:</strong> {{ $househelp->state_of_origin }}</p>
                                    <p><strong>State of Residence:</strong> {{ $househelp->state_of_residence }}</p>
                                    <p><strong>Phone Number:</strong> {{ $househelp->phone }}</p>
                                    <p><strong>Email:</strong> <a
                                            href="mailto:{{ $househelp->email }}">{{ $househelp->email }}</a></p>
                                    <p><strong>Residential Address:</strong> {{ $househelp->address }}</p>
                                    <p><strong>Landmark:</strong> {{ $househelp->landmark }}</p>
                                @endif --}}
                                    @if ($househelp->activeRequest)
                                        @if ($hasApprovedPayment)
                                            <p><strong>Date of Birth:</strong> {{ $househelp->dob }}</p>
                                            <p><strong>State of Origin:</strong> {{ $househelp->state_of_origin }}
                                            </p>
                                            <p><strong>State of Residence:</strong>
                                                {{ $househelp->state_of_residence }}
                                            </p>
                                            <p><strong>Phone Number:</strong> {{ $househelp->phone }}</p>
                                            <p><strong>Email:</strong> <a
                                                    href="mailto:{{ $househelp->email }}">{{ $househelp->email }}</a>
                                            </p>
                                            <p><strong>Residential Address:</strong> {{ $househelp->address }}</p>
                                            <p><strong>Landmark:</strong> {{ $househelp->landmark }}</p>
                                        @else
                                            <p class="text-danger">🔒 Unlock details by completing your payment.</p>
                                        @endif
                                    @endif



                                    <p><strong>Rating:</strong>
                                        @for ($i = 0; $i < $househelp->rating; $i++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                        @for ($i = 0; $i < 5 - $househelp->rating; $i++)
                                            <i class="far fa-star text-warning"></i>
                                        @endfor
                                    </p>
                                    <p><strong>Availability:</strong>
                                        {{ $househelp->availability ? 'Available' : 'Not Available' }}</p>
                                    {{-- <p><strong>Job Preference:</strong>
                                    {{ $househelp->applicant_type == 'live-in' ? 'Live-in' : ($househelp->applicant_type == 'live-out' ? 'Live-out' : 'Both') }}
                                </p> --}}
                                    {{-- <p><strong>Bio:</strong> {{ $househelp->bio ?? 'No bio available.' }}</p> --}}
                                </div>
                                <div class="modal-footer">
                                    @if ($hasApprovedPayment)
                                        <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}"
                                            class="btn btn-sm btn-primary">
                                            View Applicant ({{ $remainingSearches }} remaining)
                                        </a>
                                    @elseif ($pendingPayment)
                                        <a class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-lock"></i> Wait for Approval
                                        </a>
                                    @else
                                        <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-lock"></i>  Click here to Subscribe and then See More Detail
                                        </a>
                                    @endif

                                    {{-- @if ($hasApprovedPayment && !$isSubscriptionExpired)
                                        <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}"
                                            class="btn btn-sm btn-primary">
                                            View Applicant ({{ $remainingSearches }} remaining)
                                        </a>
                                    @elseif ($pendingPayment)
                                        <a class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-lock"></i> Wait for Approval
                                        </a>
                                    @else
                                        <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-lock"></i> Subscribe to View More Detail
                                        </a>
                                    @endif --}}
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </tbody>
        </table>
        {{-- @else
            <p class="text-danger">🔒 To search for househelps, <a href="{{ route('payments.paymentoption') }}">
                    complete your payment.</a></p>
        @endif --}}
    @endif
</div>



<script>
    function rateApplicant(househelpId, rating) {
        fetch(`/applicant/${househelpId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    rating: rating
                }),
            })
            .then(response => {
                if (response.ok) {
                    // Update client rating display
                    document.getElementById('client-rating-' + househelpId).textContent = rating + ' stars';
                    // Update stars display
                    updateStars(househelpId, rating);
                } else {
                    alert('Failed to submit rating.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateStars(househelpId, rating) {
        const stars = document.querySelectorAll(`#star-rating-${househelpId} i`);
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#FFD700'; // Gold for filled stars
            } else {
                star.style.color = ''; // Default for unfilled stars
            }
        });
    }
</script>
