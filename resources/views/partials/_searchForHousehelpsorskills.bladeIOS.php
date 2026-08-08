@if (Route::currentRouteName() === 'ourSkilledWorkerServices')
    <h5>Search for Skilled Workers</h5>
    <span class="text-muted small">Search using one or more filters - combine skills, location, or other preferences to
        find perfect matches</span>
@else
    <h5>Search for Househelps</h5>
    <span class="text-muted small">Search using one or more filters - combine gender, state of residence, or other preferences to
        find perfect matches</span>
@endif

@if (Auth::check() && isset($hasApprovedPayment) && $hasApprovedPayment)
    @include('partials._subscribe')
@endif

<br>

@if (Route::currentRouteName() === 'ourSkilledWorkerServices')
    <!-- Search Form for skilled workers -->
    <form method="GET" action="{{ route('ourSkilledWorkerServices') }}" class="mb-4">
        <div class="row g-2 justify-content-center">
            <div class="col-6 col-md-4">
                <select name="specialties" class="form-select form-control ios-select-fix">
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
                <select name="state_of_residence" class="form-select form-control ios-select-fix">
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

            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-primary w-100 form-control ios-btn-fix">
                    <i class="fas fa-search me-1 text-white"></i> Search
                    @if ($hasApprovedPayment && $remainingSearches > 0)
                        <small>({{ $remainingSearches }} left)</small>
                    @endif
                </button>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('ourSkilledWorkerServices') }}" class="btn btn-secondary w-100 form-control ios-btn-fix">
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
                <select name="gender" class="form-select ios-select-fix">
                    <option value="">Select Gender</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="state_of_residence" class="form-select ios-select-fix">
                    <option value="">Select State of Residence</option>
                    @foreach ($states as $state)
                        <option value="{{ $state }}"
                            {{ request('state_of_residence') == $state ? 'selected' : '' }}>
                            {{ $state }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary w-100 ios-btn-fix">Search</button>
            </div>
            <div class="col-6 col-md-1">
                @if (request()->routeIs('househelps'))
                    <a href="{{ route('househelps') }}" class="btn btn-secondary w-100 ios-btn-fix">Clear</a>
                @else
                    <a href="{{ route('ourLiveInServices') }}" class="btn btn-secondary w-100 ios-btn-fix">Clear</a>
                @endif
            </div>
        </div>
    </form>
@endif

<!-- Table -->
<div class="table-responsive">
    @if ($househelps->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            No house helps found matching your search criteria. Please expand or narrow your search.
        </div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="bg-success text-white">
                <tr>
                    <th>Name</th>
                    <th>Avg Rating</th>
                    <th>Profile</th>
                    <th>Request</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($househelps as $househelp)
                    <tr>
                        <td>{{ $househelp->name }}</td>
                        <td>
                            {{ number_format($househelp->averageRating, 1) ?? 'No ratings yet' }}
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary ios-btn-fix" data-bs-toggle="modal"
                                data-bs-target="#viewprofile{{ $househelp->id }}">View</a>
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
                        <!-- Applicant picture Modal - Updated for iOS -->
                        <div class="modal fade ios-modal-fix" id="viewpictureprofile{{ $househelp->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Picture of {{ $househelp->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) }}"
                                            alt="{{ $househelp->name }}'s photo" class="img-fluid rounded" 
                                            style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary ios-btn-fix" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @include('partials.modals._applicantProfileModal')
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style>
    /* iOS-specific fixes */
    .ios-select-fix {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }
    
    .ios-btn-fix {
        -webkit-appearance: none;
        -webkit-border-radius: 0;
    }
    
    .ios-modal-fix {
        position: fixed;
        -webkit-overflow-scrolling: touch;
    }
    
    body.modal-open {
        position: fixed;
        width: 100%;
    }
</style>

<script>
    // iOS modal fix
    document.addEventListener('DOMContentLoaded', function() {
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                     (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        
        if (isIOS) {
            // Fix for modal backdrop
            document.addEventListener('show.bs.modal', function() {
                document.body.style.position = 'fixed';
                document.body.style.top = `-${window.scrollY}px`;
            });

            document.addEventListener('hidden.bs.modal', function() {
                const scrollY = document.body.style.top;
                document.body.style.position = '';
                document.body.style.top = '';
                window.scrollTo(0, parseInt(scrollY || '0') * -1);
            });
        }

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
                        document.getElementById('client-rating-' + househelpId).textContent = rating + ' stars';
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
                    star.style.color = '#FFD700';
                } else {
                    star.style.color = '';
                }
            });
        }
        
        // Make rating functions globally available
        window.rateApplicant = rateApplicant;
        window.updateStars = updateStars;
    });
</script>