@extends('layouts.usermain')

@section('booking-staff-matching')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Progress Steps -->
        <div class="d-flex justify-content-center mb-4">
            <div class="booking-steps">
                <span class="step completed"><i class="fas fa-check"></i> Select Service</span>
                <span class="step active">2. Staff Matching</span>
                <span class="step">3. Choose Plan</span>
                <span class="step">4. Payment</span>
            </div>
        </div>

        <div class="card shadow-sm text-center">
            <div class="card-body p-5">
                <div class="mb-4">
                    <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <h4 class="fw-bold mb-3">Finding the Best Staff for You</h4>
                <p class="text-muted mb-3">
                    We're matching you with verified, qualified staff in your area.
                    Staff are notified and will review your request before accepting.
                </p>
                <div class="d-flex align-items-start gap-3 p-3 rounded-3 mb-3"
                    style="background: #FAEEDA; border-left: 4px solid #BA7517;">
                    <i class="fas fa-clock mt-1" style="color: #BA7517; flex-shrink: 0;"></i>
                    <div>
                        <div class="fw-semibold" style="color: #633806; font-size: 14px;">
                            This usually takes 24 – 96 hours
                        </div>
                        <div style="color: #854F0B; font-size: 13px; margin-top: 2px;">
                            You'll be notified as soon as staff are shortlisted.
                            Feel free to check back — this page refreshes automatically.
                        </div>
                    </div>
                </div>
                

                <div class="alert alert-warning text-start mb-4">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                    <strong>Need staff urgently?</strong> If you can't wait, submit an
                    <a href="{{ route('booking.urgent-request') }}" class="fw-bold">urgent request</a>
                    or call us directly at <strong>08126237436</strong> for immediate assistance.
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fs-3 fw-bold text-primary">{{ $totalNotified }}</div>
                                <small class="text-muted">Staff Notified</small>
                            </div>
                            <div class="col-4">
                                <div class="fs-3 fw-bold text-success">{{ $totalAccepted }}</div>
                                <small class="text-muted">Accepted</small>
                            </div>
                            <div class="col-4">
                                <div class="fs-3 fw-bold text-warning">{{ max(0, 3 - $totalAccepted) }}</div>
                                <small class="text-muted">More Needed</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Your Request Summary</h6>
                    <p class="mb-1"><strong>Services:</strong> <span class="text-muted">{{ $serviceRequest->service_name_list }}</span></p>
                    <p class="mb-1"><strong>Location:</strong> <span class="text-muted">{{ $serviceRequest->preferred_city }}, {{ $serviceRequest->state_of_residence }}</span></p>
                    <p class="mb-1"><strong>Start Date:</strong> <span class="text-muted">{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'N/A' }}</span></p>
                    <p class="mb-0"><strong>Duration:</strong> <span class="text-muted">{{ $serviceRequest->duration_weeks }} weeks</span></p>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('booking.staff-matching', $serviceRequest->id) }}"
                        class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i>Check for Updates
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Go to Dashboard
                    </a>
                </div>

                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-bell me-1"></i>
                        You'll be notified when your shortlist is ready.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-steps {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}
.booking-steps .step {
    padding: 8px 16px;
    background: #e9ecef;
    border-radius: 20px;
    font-size: 14px;
    color: #666;
}
.booking-steps .step.active {
    background: #0d6efd;
    color: white;
    font-weight: bold;
}
.booking-steps .step.completed {
    background: #198754;
    color: white;
}
</style>

<!-- Auto-refresh every 30 seconds -->
<script>
setTimeout(function() {
    window.location.reload();
}, 30000);
</script>
@endsection
