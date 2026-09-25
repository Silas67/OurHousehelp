@php
    $staff = $shortlistItem->staff;
    $isSelected = $shortlistItem->status === 'selected';
    $staffRating = $ratingAverages[$shortlistItem->staff_id] ?? null;
    $cardId = 'staffCard_' . $shortlistItem->id;
    $modalId = 'confirmModal_' . $shortlistItem->id;

    $specialties = $staff && is_array($staff->specialties)
        ? $staff->specialties
        : [];

    $city = strtolower(trim($staff->city ?? ''));
    $preferredCity = strtolower(trim($serviceRequest->preferred_city ?? ''));

    // Calculate age from dob
    $age = null;
    if ($staff->dob) {
        $age = \Carbon\Carbon::parse($staff->dob)->age;
    }

    $isCompanyManaged = $serviceRequest->management_plan === 'company-managed';
@endphp

<div  class="card h-100 shadow-sm staff-card {{ $isSelected ? 'border-success border-2' : '' }}" id="{{ $cardId }}">
    <div class="card-body text-center p-3">

        {{-- Selected banner --}}
        @if($isSelected)
            <div class="alert alert-success py-1 px-2 mb-2" style="font-size:12px;">
                <i class="fas fa-check-circle me-1"></i> You selected this staff
            </div>
        @endif

        {{-- Photo --}}
        <div class="mb-2">
            @if($staff && $staff->profile_photo_path)
                <img src="{{ $staff->profile_photo_url }}"
                    alt="{{ $staff->name }}" class="rounded-circle"
                    width="85" height="85" style="object-fit:cover;border:3px solid #e9ecef;" loading="lazy" decoding="async">
            @else
                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                    style="width:85px;height:85px;border:3px solid #e9ecef;">
                    <span class="text-white fs-3 fw-bold">{{ strtoupper(substr($staff->name ?? 'U', 0, 1)) }}</span>
                </div>
            @endif
        </div>

        {{-- Name --}}
        <h6 class="fw-bold mb-1">
            {{ $staff->name ?? 'Unknown Staff' }}
            @if(!empty($staff->last_name)) {{ substr($staff->last_name, 0, 1) }}. @endif
        </h6>

        {{-- Specialties subtitle --}}
        @if(!empty($specialties))
            <p class="text-primary mb-2" style="font-size:11px;">
                {{ implode(' · ', array_slice($specialties, 0, 3)) }}
                @if(count($specialties) > 3) <span class="text-muted">+{{ count($specialties) - 3 }}</span> @endif
            </p>
        @endif

        {{-- Verification.
             The old @else branch showed a blue "Verified" badge to everyone who
             was not fully verified - including staff with no checks at all. That
             asserted something we cannot back. Each badge below corresponds to a
             record that actually exists. --}}
        <div class="mb-2">
            @if($staff && $staff->isFullyVerified())
                <span class="badge bg-success" style="font-size:10px;">
                    <i class="fas fa-shield-alt me-1"></i>Fully Verified
                </span>
            @endif
            <x-trust-badges :staff="$staff" mode="compact" />
        </div>

        {{-- Star Rating --}}
        @if($staffRating && $staffRating->rating_count > 0)
            <div class="mb-2">
                @for($star = 1; $star <= 5; $star++)
                    <i class="fas fa-star {{ $star <= round($staffRating->avg_rating) ? 'text-warning' : 'text-muted' }}"
                       style="font-size:11px;"></i>
                @endfor
                <small class="text-muted">({{ $staffRating->rating_count }})</small>
            </div>
        @endif

        {{-- Info Grid --}}
        <div class="text-start border rounded p-2 mb-2" style="font-size:12px; background:#f8f9fa;">
            <div class="row g-1">

                <div class="col-6">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    @if($city && $preferredCity && $city === $preferredCity)
                        <span class="text-success fw-semibold">Near you</span>
                    @else
                        {{ $staff->city ?? $staff->state_of_residence ?? 'N/A' }}
                    @endif
                </div>
                
                <div class="col-6">
                    <i class="fas fa-briefcase text-[#6f42c1] me-1"></i>
                    {{ $staff->years_of_experience ?? '1' }}+ exp
                </div>

                <div class="col-6">
                    <i class="fas fa-venus-mars text-info me-1"></i>
                    {{ ucfirst($staff->gender ?? 'N/A') }}
                </div>

                @if($age)
                    <div class="col-6">
                        <i class="fas fa-birthday-cake text-secondary me-1"></i>
                        {{ $age }} years old
                    </div>
                @endif

                @if($staff->religion)
                    <div class="col-6">
                        <i class="fas fa-pray text-purple me-1" style="color:#6f42c1;"></i>
                        {{ ucfirst($staff->religion) }}
                    </div>
                @endif

                @if($staff->marital_status)
                    <div class="col-6">
                        <i class="fas fa-ring text-pink me-1" style="color:#e83e8c;"></i>
                        {{ ucfirst($staff->marital_status) }}
                    </div>
                @endif

                @if($staff->qualification)
                    <div class="col-6">
                        <i class="fas fa-graduation-cap text-success me-1"></i>
                        {{ $staff->qualification }}
                    </div>
                @endif

                @if($staff->state_of_origin)
                    <div class="col-6">
                        <i class="fas fa-home text-success me-1"></i>
                        {{ $staff->state_of_origin }}
                    </div>
                @endif

                @if(!empty($staff->languages_list))
                    <div class="col-6">
                        <i class="fas fa-language text-primary me-1"></i>
                        {{ $staff->languages_list }}
                    </div>
                @endif

                <div class="col-12">
                    <i class="fas fa-clock text-danger me-1"></i>
                    @if(!$staff->available_from || $staff->available_from->lte(now()))
                        Available immediately
                    @else
                        Available {{ $staff->available_from->diffForHumans() }}
                    @endif
                </div>

            </div>
        </div>

        {{-- Match Score --}}
        <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">Match Score</small>
                <small class="fw-bold text-success">{{ $shortlistItem->match_score }}%</small>
            </div>
            <div class="progress" style="height:5px;">
                <div class="progress-bar bg-success" style="width:{{ $shortlistItem->match_score }}%"></div>
            </div>
            @if($shortlistItem->match_reasons)
                <div class="d-flex flex-wrap gap-1 mt-2 justify-content-center">
                    @foreach($shortlistItem->match_reasons as $reason)
                        <span class="match-reason">
                            <i class="fas fa-circle-check" aria-hidden="true"></i>{{ $reason }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Contact hidden notice --}}
        <div class="text-muted mb-3" style="font-size:11px;">
            <i class="fas fa-lock me-1"></i>

            @if($isCompanyManaged)
                Phone & WhatsApp hidden — OurHouseHelp manages communication
            @else
                Phone & WhatsApp hidden — unlock by viewing profile
            @endif
        </div>

        {{-- Button --}}
       @if($isSelected)

            <a href="{{ route('booking.staff-profile', [$serviceRequest->id, $shortlistItem->id]) }}"
                class="btn btn-success w-100 btn-sm">
                <i class="fas fa-eye me-1"></i> View Selected Profile
            </a>

        @elseif($isCompanyManaged)

            {{-- Company Managed: no credit is required --}}
            <form action="{{ route('booking.confirm-staff', [$serviceRequest->id, $shortlistItem->id]) }}"
                method="POST">
                @csrf

                <button type="submit" class="btn btn-primary w-100 btn-sm">
                    <i class="fas fa-user me-1"></i> Select Staff
                    <small class="d-block" style="font-size:10px;opacity:0.8;">
                        No credit required
                    </small>
                </button>
            </form>

        @else

            {{-- Client Managed: credit confirmation modal --}}
            <button type="button"
                    class="btn btn-primary w-100 btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $modalId }}">
                <i class="fas fa-user me-1"></i> View Full Profile
                <small class="d-block" style="font-size:10px;opacity:0.8;">
                    Uses 1 credit
                </small>
            </button>

        @endif
    </div>
</div>

    {{-- ── CONFIRMATION MODAL ── --}}
    @if(!$isCompanyManaged)
        <div onload="noBack();" class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Before you proceed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-2">

                        {{-- Staff summary --}}
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-3">
                            @if($staff->profile_photo_path)
                                <img src="{{ $staff->profile_photo_url }}"
                                    class="rounded-circle" width="55" height="55" style="object-fit:cover;" loading="lazy" decoding="async">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                    style="width:55px;height:55px;">
                                    <span class="text-white fw-bold">{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $staff->name }} @if(!empty($staff->last_name)){{ substr($staff->last_name, 0, 1) }}.@endif</div>
                                @if(!empty($specialties))
                                    <small class="text-primary">{{ implode(' · ', array_slice($specialties, 0, 2)) }}</small>
                                @endif
                                <div class="mt-1">
                                    @if($staff->isFullyVerified())
                                        <span class="badge bg-success" style="font-size:10px;"><i class="fas fa-shield-alt me-1"></i>Fully Verified</span>
                                    @else
                                        <span class="badge bg-info" style="font-size:10px;">Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- What happens when they proceed --}}
                        <div class="mb-3">
                            <p class="fw-semibold mb-2">What happens when you proceed:</p>
                            <ul class="list-unstyled mb-0" style="font-size:14px;">
                                <li class="mb-2">
                                    <i class="fas fa-unlock text-success me-2"></i>
                                    <strong>{{ $staff->name }}'s</strong> full profile, phone number and WhatsApp will be revealed to you
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-coins text-warning me-2"></i>
                                    <strong>1 credit</strong> will be deducted from your balance
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                    All <strong>other staff</strong> who accepted this job will be notified that the position has been filled
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-lock text-secondary me-2"></i>
                                    Once you unlock a profile, this booking is set with that staff member. If you want someone else, you'd need to cancel this booking and start a new one — a new booking, a new credit
                                </li>
                            </ul>
                        </div>

                        {{-- Credit cost --}}
                        <div class="alert alert-warning py-2 mb-0" style="font-size:13px;">
                            <i class="fas fa-coins me-1"></i>
                            <strong>Cost: 1 credit</strong>
                            <span class="text-muted ms-2">— Make sure you are satisfied with this choice before proceeding</span>
                        </div>

                    </div>

                <div class="modal-footer border-0 pt-0">
                        @if(!$serviceRequest->selected_staff_id)
                            <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left me-1"></i> Go back & choose again
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        @endif
                        <form action="{{ route('booking.confirm-staff', [$serviceRequest->id, $shortlistItem->id]) }}"
                            method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-unlock me-1"></i> Yes, proceed (1 credit)
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endif

<script type="text/javascript">
function selectStaff(url) {
    // This replaces the "Shortlist" entry in history with the next page
    // window.location.replace(url);
}

function noBack() {
    window.history.forward();
}
</script>