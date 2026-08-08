@if (Route::currentRouteName() === 'ourSkilledWorkerServices')
    <h5>Search for Skilled Workers</h5>
    <span class="text-muted small">Search using one or more filters - combine skills, location, or other preferences to
        find perfect matches</span>
@else
    <h5>Search for Househelps</h5>
    <span class="text-muted small">Search using one or more filters - combine gender, state of residence, or other
        preferences to
        find perfect matches</span>
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
                
                    <!-- Applicant profile Modal -->
                    @include('partials.modals._applicantProfileModal')
                    <tr>
                        {{-- <td>
                        <img src="{{ $househelp->profile_photo_path ? asset('build/assets/image/profile_photo/' . $househelp->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"
                             alt="{{ $househelp->name }}'s photo" class="img-fluid rounded" style="width: 50px; height: 50px; cursor: pointer"
                             data-bs-toggle="modal" data-bs-target="#viewpictureprofile{{ $househelp->id }}">
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
                            @include('partials.modals._ModalforOne-timeServices')

                            <span class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#viewprofile{{ $househelp->id }}">View</span>

                            <a class="btn btn-sm btn-primary" data-bs-toggle="modal"
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
                                            style="width: 100%; max-height: 200px; object-fit: contain;">
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
