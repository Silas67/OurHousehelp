@extends('layouts.usermain')

@section('booking-deployment')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <i class="fas fa-rocket fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Staff Deployment</h3>
            <p class="text-muted">Your staff has been matched and payment confirmed.</p>
        </div>

        <!-- Deployment Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-header {{ $serviceRequest->matching_status === 'deployed' ? 'bg-success' : 'bg-primary' }} text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ $serviceRequest->matching_status === 'deployed' ? 'check-circle' : 'clock' }} me-2"></i>
                    {{ $serviceRequest->matching_status === 'deployed' ? 'Staff Deployed' : 'Deployment Pending' }}
                </h5>
            </div>
            <div class="card-body p-4">
                <!-- Timeline -->
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-icon bg-success"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <strong>Service Booked</strong>
                            <p class="text-muted mb-0">{{ $serviceRequest->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item completed">
                        <div class="timeline-icon bg-success"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <strong>Staff Matched & Selected</strong>
                            <p class="text-muted mb-0">{{ $selectedStaff?->name ?? 'Staff' }} assigned</p>
                        </div>
                    </div>
                    <div class="timeline-item completed">
                        <div class="timeline-icon bg-success"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <strong>Payment Confirmed</strong>
                            <p class="text-muted mb-0">{{ ucfirst($serviceRequest->management_plan) }} Plan</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $serviceRequest->matching_status === 'deployed' ? 'completed' : 'pending' }}">
                        <div class="timeline-icon {{ $serviceRequest->matching_status === 'deployed' ? 'bg-success' : 'bg-warning' }}">
                            <i class="fas fa-{{ $serviceRequest->matching_status === 'deployed' ? 'check' : 'clock' }}"></i>
                        </div>
                        <div class="timeline-content">
                            <strong>Staff Resumes</strong>
                            <p class="text-muted mb-0">
                                {{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'TBD' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Type Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                @if($serviceRequest->deployment_type === 'self_managed')
                    <div class="text-center mb-3">
                        <i class="fas fa-user-cog fa-2x text-primary mb-2"></i>
                        <h5 class="fw-bold">Self-Managed</h5>
                    </div>
                    <p class="text-muted">You're managing your staff directly. Here's what you need to know:</p>
                    <ul>
                        <li>Contact your staff directly using their details</li>
                        <li>Agree on work schedule and duties</li>
                        <li>{{ $plan?->replacement_text ?? '1 Replacement within 7 days' }}</li>
                        <li>Basic support from our team if needed</li>
                    </ul>
                @else
                    <div class="text-center mb-3">
                        <i class="fas fa-building fa-2x text-success mb-2"></i>
                        <h5 class="fw-bold">Company Managed</h5>
                    </div>
                    <p class="text-muted">Our team handles everything. Here's what's included:</p>
                    <ul>
                        @if($plan)
                            @foreach($plan->features_list as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        @else
                            <li>Staff deployment and coordination</li>
                            <li>Performance monitoring</li>
                            <li>Conflict resolution</li>
                            <li>Free replacement if needed</li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Booking Summary</h5>
                <div class="row g-2">
                    <div class="col-6"><strong>Services:</strong></div>
                    <div class="col-6">{{ $serviceRequest->service_name_list }}</div>
                    <div class="col-6"><strong>Location:</strong></div>
                    <div class="col-6">{{ $serviceRequest->preferred_city }}, {{ $serviceRequest->state_of_residence }}</div>
                    <div class="col-6"><strong>Start Date:</strong></div>
                    <div class="col-6">{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'N/A' }}</div>
                    <div class="col-6"><strong>Days/Week:</strong></div>
                    <div class="col-6">{{ $serviceRequest->days_per_week }} days</div>
                    <div class="col-6"><strong>Duration:</strong></div>
                    <div class="col-6">{{ $serviceRequest->duration_weeks }} weeks</div>
                    <div class="col-6"><strong>Plan:</strong></div>
                    <div class="col-6">{{ ucfirst($serviceRequest->management_plan) }}</div>
                    <div class="col-6"><strong>Total Paid:</strong></div>
                    <div class="col-6 text-primary fw-bold">N{{ number_format($serviceRequest->amount_paid) }}</div>
                    @if($serviceRequest->amount_remaining > 0)
                    <div class="col-6"><strong>Balance Due:</strong></div>
                    <div class="col-6 text-danger">N{{ number_format($serviceRequest->amount_remaining) }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Staff Rating -->
        @if($serviceRequest->matching_status === 'deployed' && $selectedStaff)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Rate Your Staff</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">How has {{ $selectedStaff->name }} been performing?</p>

                <div id="ratingFormContainer">
                    <div class="d-flex gap-2 mb-3 justify-content-center" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star fa-2x text-muted rating-star"
                           data-value="{{ $i }}"
                           style="cursor:pointer;"
                           onclick="setRating({{ $i }})"></i>
                        @endfor
                    </div>
                    <input type="hidden" id="selectedRating" value="">

                    <div class="mb-3">
                        <textarea id="ratingComment" class="form-control" rows="2"
                            placeholder="Optional comment about your experience..."></textarea>
                    </div>

                    <button class="btn btn-info w-100" onclick="submitRating({{ $selectedStaff->id }})">
                        <i class="fas fa-paper-plane me-1"></i>Submit Rating
                    </button>
                    <div id="ratingMessage" class="mt-2"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Replacement Request -->
        @if($serviceRequest->matching_status === 'deployed' && $plan)
        @php
            $withinWindow = true;
            if ($plan->replacement_window && $serviceRequest->preferred_start_date) {
                preg_match('/(\d+)/i', $plan->replacement_window, $matches);
                $windowDays = isset($matches[1]) ? (int) $matches[1] : 7;
                $withinWindow = now()->diffInDays($serviceRequest->preferred_start_date, false) >= -$windowDays;
            }
            $pendingReplacement = $serviceRequest->replacementRequests()->where('status', 'pending')->exists();
            $showReplacementButton = $withinWindow && !$pendingReplacement && $plan->free_replacements != 0;
        @endphp

        @if($showReplacementButton)
        <div class="card shadow-sm mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Request Staff Replacement</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Not satisfied with your current staff? You can request a replacement within your plan's {{ $plan->replacement_window ?? '7 days' }} window.</p>
                <form action="{{ route('booking.request-replacement', $serviceRequest->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Reason for Replacement <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="4"
                            placeholder="Please describe why you need a replacement (minimum 20 characters)..."
                            required minlength="20"></textarea>
                        @error('reason')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i>Submit Replacement Request
                    </button>
                </form>
            </div>
        </div>
        @elseif($pendingReplacement)
        <div class="alert alert-info mb-4">
            <i class="fas fa-clock me-2"></i>Your replacement request is pending admin review.
        </div>
        @endif
        @endif

        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-home me-1"></i>Dashboard
            </a>
            @if($serviceRequest->isStaffDetailsVisible())
            <a href="{{ route('booking.staff-details', $serviceRequest->id) }}" class="btn btn-primary">
                <i class="fas fa-user me-1"></i>View Staff Details
            </a>
            @endif
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    margin-bottom: 24px;
}
.timeline-icon {
    position: absolute;
    left: -40px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}
.timeline-item.pending .timeline-icon {
    background: #ffc107;
}
</style>

<script>
function setRating(value) {
    document.getElementById('selectedRating').value = value;
    document.querySelectorAll('.rating-star').forEach(function(star, index) {
        star.classList.toggle('text-warning', index < value);
        star.classList.toggle('text-muted', index >= value);
    });
}

function submitRating(staffId) {
    var rating = document.getElementById('selectedRating').value;
    var comment = document.getElementById('ratingComment').value;

    if (!rating) {
        document.getElementById('ratingMessage').innerHTML =
            '<div class="alert alert-warning py-1">Please select a star rating.</div>';
        return;
    }

    fetch('/applicant/' + staffId + '/rate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ rating: parseInt(rating), comment: comment })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('ratingMessage').innerHTML =
                '<div class="alert alert-success py-1">Thank you for your rating!</div>';
        }
    })
    .catch(function() {
        document.getElementById('ratingMessage').innerHTML =
            '<div class="alert alert-danger py-1">Failed to submit rating. Please try again.</div>';
    });
}
</script>
@endsection
