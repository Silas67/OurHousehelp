@if (Route::currentRouteName() === 'ourSkilledWorkerServices')
    <h5>Search for Skilled Workers</h5>
    <span class="text-muted small">Search using one or more filters - combine skills, location, or other preferences to
        find perfect matches <br>You can click the search button again to view a different 5 other Skilled Workers.</span>
@else
    <h5>Search for Househelps</h5>
    <span class="text-muted small">Search using one or more filters - combine gender, state of residence, or other
        preferences to
        find perfect matches. <br>You can click the search button again to view a different 5 other Househelps.</span>
@endif


@if (Auth::check() && isset($hasApprovedPayment) && $hasApprovedPayment)
    @include('partials._subscribe')
@endif

<br>

@if (Route::currentRouteName() === 'ourSkilledWorkerServices')
    <!-- Search Form for skilled workers -->
    <form method="GET" action="{{ route('ourSkilledWorkerServices') }}" class="mb-4">
        <div class="row g-2  justify-content-center">
            <div class="col-6 col-md-4">
                <select name="specialties" class="form-select form-control">
                    <option value="">Select Skill</option>
                    @foreach ($skills as $skill)
                        <option value="{{ $skill->skill_name }}"
                            {{ request('specialties') == $skill->id ? 'selected' : '' }}>
                            {{ $skill->skill_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-4">
                <select name="state_of_residence" class="form-select form-control">
                    <option value="">Select State of Residence</option>
                    @foreach ($states as $state)
                        <option value="{{ $state }}"
                            {{ strtolower(request('state_of_residence')) == strtolower($state) ? 'selected' : '' }}>
                            {{ $state }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-4">
                <input type="text" name="lga_of_origin" class="form-control" placeholder="LGA (type partially)"
                    value="{{ request('lga_of_origin') }}">
            </div>

            {{-- <div class="col-6 col-md-3">
                <input type="text" name="address" class="form-control" placeholder="Address (type partially)"
                    value="{{ request('address') }}">
            </div> --}}



            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-primary w-100 form-control">
                    <i class="fas fa-search me-1 text-white"></i> Search
                    @if ($hasApprovedPayment && $remainingSearches > 0)
                        <small>({{ $remainingSearches }} left)</small>
                    @endif
                </button>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('ourSkilledWorkerServices') }}" class="btn btn-secondary w-100 form-control">
                    <i class="fas fa-undo me-1 text-white"></i> Clear
                </a>
            </div>
        </div>
    </form>
@else
    <!-- Search Form for live-in services -->
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
                        <option value="{{ $state }}"
                            {{ request('state_of_residence') == $state ? 'selected' : '' }}>
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
@endif


<!-- Table -->
<div class="container">
    @if ($househelps->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No house helps found matching your search criteria. Please expand or narrow your search.
        </div>
    @else
        <div class="row">
            @foreach ($househelps as $househelp)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Card Header with Name and Rating -->
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $househelp->name }}</h5>
                                <span class="badge bg-light text-dark">
                                    {{ number_format($househelp->averageRating, 1) ?? 'N/A' }}
                                    <i class="fas fa-star text-warning"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Card Body with Photo and Basic Info -->
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img src="{{ $househelp->profile_photo_path ? asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"
                                    alt="{{ $househelp->name }}'s photo"
                                    class="img-fluid rounded-circle border border-3 border-primary"
                                    style="width: 120px; height: 120px; object-fit: cover; cursor: pointer"
                                    data-bs-toggle="modal" data-bs-target="#viewpictureprofile{{ $househelp->id }}" loading="lazy" decoding="async">
                            </div>

                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="fas fa-venus-mars me-1"></i> Gender:</strong>
                                    {{ ucfirst($househelp->gender) }}
                                </p>

                                @if ($househelp->applicant_type == 'skill')
                                    <p class="mb-1"><strong><i class="fas fa-tools me-1"></i> Specialty:</strong>
                                        {{ is_array($househelp->specialties) ? implode(', ', $househelp->specialties) : $househelp->specialties }}
                                    </p>
                                    
                                @else
                                    <p class="mb-1"><strong><i class="fas fa-briefcase me-1"></i> Experience:</strong>
                                        {{ $househelp->years_of_experience }}
                                    </p>
                                @endif

                                <p class="mb-1"><strong><i class="fas fa-location-dot me-1"></i> Location:</strong>
                                    {{ $househelp->state_of_residence }}
                                </p>

                                <div class="mt-2">
                                    <span class="badge bg-{{ $househelp->availability ? 'success' : 'danger' }}">
                                        {{ $househelp->availability ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>

                                <div class="mt-2">
                                    @if ($househelp->activeRequest)
                                        <span class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-check-circle me-1"></i> Sent
                                        </span>
                                    @elseif ($househelp->acceptedRequest)
                                        <span class="btn btn-sm btn-success" disabled>
                                            <i class="fas fa-user-check me-1"></i> Accepted
                                        </span>
                                    @else
                                        <span class="text-primary align-self-center" disabled>
                                            <i class="fas fa-envelope me-1"></i> No Request
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between">
                                {{-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#viewprofile{{ $househelp->id }}">
                                    <i class="fas fa-eye me-1"></i> View
                                </button> --}}

                                {{-- @if ($hasApprovedPayment)
                                    <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-user-circle me-1 text-white"></i> Full Profile
                                        ({{ $remainingSearches }} left)
                                    </a>
                                @elseif ($pendingPayment)
                                    <button class="btn btn-primary" disabled>
                                        <i class="fas fa-hourglass-half me-1"></i> Pending Approval
                                    </button>
                                @else
                                    <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-lock"></i> Want the full detail? Click here to Subscribe
                                    </a>
                                @endif --}}

                                @if (Auth::check() && isset($hasApprovedPayment) && $hasApprovedPayment)
                                    {{-- User has paid - show full access --}}
                                    <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-user-circle me-1 text-white"></i> Full Profile
                                        ({{ $remainingSearches }} left)
                                    </a>
                                @elseif (Auth::check() && $pendingPayment)
                                    {{-- Payment pending approval --}}
                                    <button class="btn btn-primary" disabled>
                                        <i class="fas fa-hourglass-half me-1"></i> Pending Approval
                                    </button>
                                @else
                                    {{-- No payment - show subscribe button --}}
                                    <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-lock"></i> Want the full detail? Click here to Subscribe
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

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
            @endforeach

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $househelps->appends(request()->query())->links() }}
            </div>
        </div>
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
