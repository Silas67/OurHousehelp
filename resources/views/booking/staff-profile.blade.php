@extends('layouts.usermain')

@section('booking-staff-details')

@php
    $isUnlocked = true; // Always show full profile — gate is handled by card modal
    
    $phone = preg_replace('/\D/', '', $staff->phone ?? '');
    if (str_starts_with($phone, '0')) {
        $phone = '234' . substr($phone, 1);
    }
    $whatsappLink = 'https://wa.me/' . $phone;
    $isClientManaged = $serviceRequest->management_plan === 'client-managed';
@endphp

<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- Back button
        <div class="mb-3">
            <a href="{{ route('booking.shortlist', $serviceRequest->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Shortlist
            </a>
        </div> --}}

        {{-- ── OVERLAY: NO CREDITS ── --}}
        @if(!$isUnlocked && !$hasCredits)
            <div style="position:fixed;inset:0;background:rgba(0,0,0,0.65);backdrop-filter:blur(6px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;">
                <div class="bg-white rounded-3 shadow-lg p-4 text-center" style="max-width:480px;width:100%;">
                    <i class="fas fa-coins fa-3x text-warning mb-3"></i>
                    <h4 class="fw-bold mb-1">You're out of credits!</h4>
                    <p class="text-muted mb-4">You need at least 1 credit to unlock a staff profile and view their contact details.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="border rounded p-3">
                                <div class="fw-bold fs-5">₦5,000</div>
                                <div class="text-muted small mb-2">1 Credit</div>
                                <form action="{{ route('credits.pay') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="starter">
                                    <input type="hidden" name="redirect_to" value="{{ route('booking.staff-profile', [$serviceRequest->id, $shortlistItem->id]) }}">
                                    <button class="btn btn-sm btn-outline-dark w-100">Buy</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border border-primary rounded p-3 position-relative">
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-primary" style="font-size:9px;">Popular</span>
                                <div class="fw-bold fs-5 text-primary">₦12,000</div>
                                <div class="text-muted small mb-2">3 Credits</div>
                                <form action="{{ route('credits.pay') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="standard">
                                    <input type="hidden" name="redirect_to" value="{{ route('booking.staff-profile', [$serviceRequest->id, $shortlistItem->id]) }}">
                                    <button class="btn btn-sm btn-primary w-100">Buy</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-3">
                                <div class="fw-bold fs-5">₦20,000</div>
                                <div class="text-muted small mb-2">6 Credits</div>
                                <form action="{{ route('credits.pay') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan" value="premium">
                                    <input type="hidden" name="redirect_to" value="{{ route('booking.staff-profile', [$serviceRequest->id, $shortlistItem->id]) }}">
                                    <button class="btn btn-sm btn-outline-warning w-100">Buy</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('booking.shortlist', $serviceRequest->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-1"></i> Back to Shortlist
                    </a>
                </div>
            </div>
        @endif

        {{-- ── PROFILE CONTENT ── --}}
        <div style="{{ !$isUnlocked ? 'filter:blur(5px);pointer-events:none;user-select:none;' : '' }}">

            {{-- Hero --}}
            <div class="card border-0 overflow-hidden mb-4" style="border-radius: var(--bs-border-radius-lg, 12px); box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

                {{-- Teal accent bar --}}
                <div style="height: 6px; background: linear-gradient(90deg, #1D9E75, #5DCAA5, #9FE1CB);"></div>

                {{-- Main content --}}
                <div class="d-flex gap-4 align-items-start p-4">

                    {{-- Avatar with availability dot --}}
                    <div style="position: relative; flex-shrink: 0;">
                        @if($staff->profile_photo_path)
                            <img src="{{ asset('storage/' . $staff->profile_photo_path) }}"
                                class="rounded-circle"
                                width="88" height="88"
                                style="object-fit: cover; border: 3px solid white; box-shadow: 0 0 0 2px #1D9E75;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 88px; height: 88px; background: #E1F5EE; border: 3px solid white; box-shadow: 0 0 0 2px #1D9E75;">
                                <span style="font-size: 32px; font-weight: 500; color: #0F6E56;">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        {{-- Availability dot --}}
                        @if($staff->availability)
                            <div style="position: absolute; bottom: 4px; right: 4px; width: 16px; height: 16px; background: #1D9E75; border-radius: 50%; border: 2px solid white;"></div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="flex: 1; min-width: 0;">
                        <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                            <div>
                                <h5 class="fw-semibold mb-1" style="font-size: 20px;">
                                    {{ $staff->name }}
                                    @if(!empty($staff->last_name)) {{ substr($staff->last_name, 0, 1) }}. @endif
                                </h5>
                                @if(!empty($staff->specialties))
                                    <p class="mb-0" style="font-size: 13px; color: #6c757d;">
                                        {{ implode(' · ', $staff->specialties) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Verified badge --}}
                            @if($staff->isFullyVerified())
                                <span style="background: #E1F5EE; color: #0F6E56; font-size: 11px; font-weight: 500; padding: 4px 10px; border-radius: 999px; white-space: nowrap; flex-shrink: 0;">
                                    <i class="fas fa-shield-alt me-1" style="font-size: 11px;"></i>Fully verified
                                </span>
                            @else
                                <span style="background: #e8f4fd; color: #0a58ca; font-size: 11px; font-weight: 500; padding: 4px 10px; border-radius: 999px; white-space: nowrap; flex-shrink: 0;">
                                    <i class="fas fa-check me-1" style="font-size: 11px;"></i>Verified
                                </span>
                            @endif
                        </div>

                        {{-- Meta info row --}}
                        <div class="d-flex flex-wrap gap-3 mt-2" style="font-size: 12px; color: #6c757d;">
                            @if($staff->city || $staff->state_of_residence)
                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $staff->city ?? $staff->state_of_residence }}</span>
                            @endif
                            <span><i class="fas fa-briefcase me-1"></i>{{ $staff->years_of_experience ?? '1' }}+ yrs experience</span>
                            <span><i class="fas fa-calendar me-1"></i>Member since {{ $staff->created_at->format('M Y') }}</span>
                            @if($staff->state_of_origin)
                                <span><i class="fas fa-home me-1"></i>From {{ $staff->state_of_origin }}</span>
                            @endif
                            @if($staff->religion)
                                <span><i class="fas fa-pray me-1" style="color: #6f42c1;"></i>{{ ucfirst($staff->religion) }}</span>
                            @endif
                            @if($staff->availability)
                                <span style="color: #1D9E75;"><i class="fas fa-circle me-1" style="font-size: 8px;"></i>Available</span>
                            @endif
                        </div>

                        {{-- Star Rating --}}
                        @if($rating && $rating->rating_count > 0)
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <div>
                                    @for($star = 1; $star <= 5; $star++)
                                        <i class="fas fa-star" style="font-size: 12px; color: {{ $star <= round($rating->avg_rating) ? '#BA7517' : '#dee2e6' }};"></i>
                                    @endfor
                                </div>
                                <span style="font-size: 12px; color: #6c757d;">{{ $rating->avg_rating }} ({{ $rating->rating_count }} reviews)</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contact buttons footer --}}
                <div class="d-flex gap-2 flex-wrap px-4 pb-4" style="border-top: 0.5px solid #e9ecef; padding-top: 1.25rem; margin-top: 0;">
                    @if($isUnlocked && $isClientManaged)
                        <a href="tel:{{ $staff->phone }}"
                            class="d-flex align-items-center justify-content-center gap-2"
                            style="flex: 1; min-width: 140px; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; background: white; color: #212529; font-size: 13px; font-weight: 500; text-decoration: none;">
                            <i class="fas fa-phone" style="font-size: 14px;"></i>
                            {{ $staff->phone }}
                        </a>
                        <a href="{{ $whatsappLink }}" target="_blank"
                            class="d-flex align-items-center justify-content-center gap-2"
                            style="flex: 1; min-width: 140px; padding: 10px 16px; border-radius: 8px; border: none; background: #1D9E75; color: #E1F5EE; font-size: 13px; font-weight: 500; text-decoration: none;">
                            <i class="fab fa-whatsapp" style="font-size: 16px;"></i>
                            Chat on WhatsApp
                        </a>
                    @elseif($isUnlocked && !$isClientManaged)
                        <div class="w-100 p-3 rounded" style="background: #e8f4fd; border: 1px solid #b6d4fe; font-size: 13px; color: #0a58ca;">
                            <i class="fas fa-shield-alt me-2"></i>
                            <strong>OurHouseHelp is managing this staff.</strong>
                            Our team will coordinate everything — you do not need to contact them directly.
                        </div>
                    @else
                        <button class="d-flex align-items-center justify-content-center gap-2" disabled
                            style="flex: 1; min-width: 140px; padding: 10px 16px; border-radius: 8px; border: 1px solid #dee2e6; background: #f8f9fa; color: #adb5bd; font-size: 13px; font-weight: 500; cursor: not-allowed;">
                            <i class="fas fa-phone" style="font-size: 14px;"></i>
                            Call mobile phone
                        </button>
                        <button class="d-flex align-items-center justify-content-center gap-2" disabled
                            style="flex: 1; min-width: 140px; padding: 10px 16px; border-radius: 8px; border: none; background: #d3f9d8; color: #74c69d; font-size: 13px; font-weight: 500; cursor: not-allowed;">
                            <i class="fab fa-whatsapp" style="font-size: 16px;"></i>
                            Chat on WhatsApp
                        </button>
                    @endif
                </div>

            </div>

            <div class="row g-4 mb-4">

                {{-- About --}}
                @if($staff->work_summary || $staff->bio)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3"><i class="fas fa-user text-primary me-2"></i>About</h6>
                                <p class="text-muted mb-0">{{ $staff->work_summary ?? $staff->bio }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Trust & Safety --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt text-success me-2"></i>Trust & Safety</h6>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-{{ $staff->id_verified ? 'check-circle text-success' : 'times-circle text-muted' }}"></i>
                                    <span>ID Verified</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-{{ $staff->background_checked ? 'check-circle text-success' : 'times-circle text-muted' }}"></i>
                                    <span>Background Checked</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-{{ $staff->guarantors_forms_uploaded ? 'check-circle text-success' : 'times-circle text-muted' }}"></i>
                                    <span>Guarantor Confirmed</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-{{ $staff->address_confirmed ? 'check-circle text-success' : 'times-circle text-muted' }}"></i>
                                    <span>Address Confirmed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Work Details --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-briefcase text-warning me-2"></i>Work Details</h6>
                            <div class="d-flex flex-column gap-2 text-muted small">
                                <div><strong>Experience:</strong> {{ $staff->years_of_experience ?? 'N/A' }} years</div>
                                <div><strong>Location:</strong> {{ $staff->city ?? '' }}{{ $staff->city ? ', ' : '' }}{{ $staff->state_of_residence }}</div>
                                <div><strong>Languages:</strong> {{ $staff->languages_list }}</div>
                                <div><strong>Gender:</strong> {{ ucfirst($staff->gender ?? 'N/A') }}</div>
                                @if($staff->marital_status)
                                    <div><strong>Marital Status:</strong> {{ ucfirst($staff->marital_status) }}</div>
                                @endif
                                @if($staff->qualification)
                                    <div><strong>Qualification:</strong> {{ $staff->qualification }}</div>
                                @endif
                                <div><strong>Available:</strong> {{ !$staff->available_from || $staff->available_from->lte(now()) ? 'Immediately' : $staff->available_from->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Match Score --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-chart-bar text-info me-2"></i>Match Details</h6>
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="fs-1 fw-bold text-success">{{ $shortlistItem->match_score }}%</div>
                                    <div class="text-muted small">Match Score</div>
                                </div>
                                <div class="col-md-8">
                                    <div class="progress mb-2" style="height:8px;">
                                        <div class="progress-bar bg-success" style="width:{{ $shortlistItem->match_score }}%"></div>
                                    </div>
                                    @if($shortlistItem->match_reasons)
                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                            @foreach($shortlistItem->match_reasons as $reason)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-check-circle text-success me-1"></i>{{ $reason }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── NEXT STEPS ── --}}
                @if($isUnlocked)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-flag-checkered me-2"></i>Next Steps</h6>

                            @if($isClientManaged)
                                {{-- Client Managed: contact staff, go to dashboard --}}
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    <strong>You're managing this staff.</strong>
                                    Contact them directly using the phone or WhatsApp above.
                                    Your staff is expected to resume on
                                    <strong>{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'your agreed date' }}</strong>.
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                                        Go to Dashboard <i class="fas fa-home ms-2"></i>
                                    </a>
                                    <p class="text-muted small mt-2">Your booking is confirmed. Contact your staff directly.</p>
                                </div>
                           @else
                                {{-- Company Managed --}}
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-building me-2"></i>
                                    <strong>OurHouseHelp is managing this staff.</strong>
                                    Our team will deploy your staff and handle all coordination.
                                    Expected deployment on
                                    <strong>{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'your agreed date' }}</strong>.
                                </div>

                                @if($serviceRequest->payment_status !== 'Paid')
                                    {{-- Payment not yet done --}}
                                    <div class="text-center mt-3">
                                        <a href="{{ route('booking.payment', $serviceRequest->id) }}" class="btn btn-success btn-lg px-5">
                                            Proceed to Payment <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                        <p class="text-muted small mt-2">Complete your payment to confirm deployment.</p>
                                    </div>
                                @else
                                    {{-- Payment approved — show staff contact --}}
                                    <div class="alert alert-success mb-3" style="background:#E1F5EE;border-color:#0F6E56;">
                                        <i class="fas fa-check-circle me-2" style="color:#0F6E56;"></i>
                                        <strong style="color:#0F6E56;">Payment confirmed!</strong>
                                        Your staff details are below. Our team will coordinate the deployment.
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-3">
                                        <a href="tel:{{ $staff->phone }}"
                                            class="btn btn-outline-dark px-4">
                                            <i class="fas fa-phone me-2"></i>{{ $staff->phone }}
                                        </a>
                                        <a href="https://wa.me/234{{ ltrim($staff->phone, '0') }}" target="_blank"
                                            class="btn btn-success px-4">
                                            <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                                        </a>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                                            Go to Dashboard <i class="fas fa-home ms-2"></i>
                                        </a>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection