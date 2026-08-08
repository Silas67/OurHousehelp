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
                    style="width: 100%; max-height: 200px; object-fit: cover;">
                <p><strong>Name:</strong> {{ $househelp->name }} {{ $househelp->last_name }}
                </p>
                <p><strong>Gender:</strong> {{ ucfirst($househelp->gender) }}</p>
                @if (Route::currentRouteName() === 'ourSkilledWorkerServices')
                    <p><strong>specialty:</strong> {{ $househelp->specialties }}</p>
                @else
                    <p><strong>Experience:</strong> {{ $househelp->years_of_experience }} years</p>
                @endif



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
                    <a href="{{ route('payments.paymentoption') }}"
                        class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-lock"></i> Click here to Subscribe and then See More Detail
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