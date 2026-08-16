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

        <div class="card shadow-sm">
            <div class="card-body p-4 p-md-5">

                {{-- Confirmation, not a loading state. The real wait is measured in
                     days, so a spinner here would promise something we cannot keep. --}}
                <div class="text-center mb-4">
                    <div class="confirm-badge mb-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Your request is confirmed</h4>
                    <p class="text-muted mb-0">
                        We're notifying verified staff in
                        <strong>{{ $serviceRequest->preferred_city ?: $serviceRequest->state_of_residence }}</strong> now.
                        You don't need to stay on this page.
                    </p>
                </div>

                <!-- What happens next -->
                <div class="next-steps mb-4">
                    <div class="next-step">
                        <span class="next-step-dot done"><i class="fas fa-check"></i></span>
                        <div>
                            <div class="fw-semibold">Request sent</div>
                            <small class="text-muted">
                                <span id="notifiedCount">{{ $totalNotified }}</span> matched staff notified
                            </small>
                        </div>
                    </div>
                    <div class="next-step">
                        <span class="next-step-dot current"></span>
                        <div>
                            <div class="fw-semibold">Staff review your request</div>
                            <small class="text-muted">Most clients are matched within 48 hours</small>
                        </div>
                    </div>
                    <div class="next-step">
                        <span class="next-step-dot"></span>
                        <div>
                            <div class="fw-semibold">You pick your preferred staff</div>
                            <small class="text-muted">We'll alert you by SMS and WhatsApp the moment someone accepts</small>
                        </div>
                    </div>
                </div>

                <!-- Live status, shown only while we are actively polling -->
                <div id="livePanel" class="alert alert-success d-none" role="status" aria-live="polite">
                    <i class="fas fa-user-check me-2"></i>
                    <span id="liveMessage">A staff member has accepted your request.</span>
                    <a href="{{ route('booking.shortlist', $serviceRequest->id) }}" class="alert-link ms-1">
                        View your shortlist
                    </a>
                </div>

                <!-- Primary action is to leave, not to wait -->
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-4">
                    <a href="{{ route('bookings') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-clipboard-list me-2"></i>View my bookings
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                        <i class="fas fa-home me-2"></i>Go to dashboard
                    </a>
                </div>

                <!-- Urgent path -->
                <div class="urgent-box d-flex align-items-start gap-3 mb-4">
                    <i class="fas fa-bolt mt-1" style="color:#BA7517;flex-shrink:0;"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="color:#633806;">Need someone urgently?</div>
                        <div style="color:#854F0B;font-size:14px;" class="mb-2">
                            Skip the queue with an urgent request, or talk to us directly.
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('booking.urgent-request') }}" class="btn btn-sm btn-warning">
                                Submit urgent request
                            </a>
                            <a href="https://wa.me/2348126237436?text={{ rawurlencode('Hi, I just booked (request #' . $serviceRequest->id . ') and need staff urgently.') }}"
                               class="btn btn-sm btn-outline-success" rel="noopener">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp us
                            </a>
                            <a href="tel:08126237436" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-phone me-1"></i>0812 623 7436
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Cancel. Previously the only cancel button lived on the shortlist
                     page, which a client cannot reach until a staff member accepts -
                     so a request sitting in "matching" for the usual 24-96h could
                     neither be cancelled nor replaced with a new one. --}}
                <div class="d-grid d-sm-flex justify-content-sm-center mb-4">
                    <button type="button" class="btn btn-outline-danger px-4"
                        data-bs-toggle="modal" data-bs-target="#cancelBookingModal">
                        <i class="fas fa-times-circle me-2"></i>Cancel this booking
                    </button>
                </div>

                <!-- Request summary -->
                <details class="summary-block">
                    <summary class="fw-bold">Your request summary</summary>
                    <div class="pt-3">
                        <p class="mb-1"><strong>Services:</strong> <span class="text-muted">{{ $serviceRequest->service_name_list }}</span></p>
                        <p class="mb-1"><strong>Location:</strong> <span class="text-muted">{{ $serviceRequest->preferred_city }}, {{ $serviceRequest->state_of_residence }}</span></p>
                        <p class="mb-1"><strong>Start date:</strong> <span class="text-muted">{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'N/A' }}</span></p>
                        <p class="mb-0"><strong>Duration:</strong> <span class="text-muted">{{ $serviceRequest->duration_weeks }} weeks</span></p>
                    </div>
                </details>

            </div>
        </div>
    </div>
</div>

{{-- Cancel confirmation --}}
<div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBookingLabel">Cancel this booking?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">
                    We'll stop looking for staff and let anyone already notified know the
                    job is closed.
                </p>
                <p class="mb-0 text-muted" style="font-size:14px;">
                    <i class="fas fa-coins me-1" style="color:#BA7517;"></i>
                    Your booking credit will be refunded, so you can book again whenever
                    you're ready.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Keep looking
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times-circle me-1"></i> Yes, cancel booking
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.booking-steps { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
.booking-steps .step { padding:8px 16px; background:#e9ecef; border-radius:20px; font-size:14px; color:#666; }
.booking-steps .step.active { background:#0d6efd; color:#fff; font-weight:bold; }
.booking-steps .step.completed { background:#198754; color:#fff; }

.confirm-badge {
    width:64px; height:64px; border-radius:50%;
    background:#198754; color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:28px; margin:0 auto;
}

.next-steps { border-left:2px solid #e9ecef; margin-left:12px; padding-left:20px; }
.next-step { position:relative; display:flex; gap:12px; padding-bottom:18px; }
.next-step:last-child { padding-bottom:0; }
.next-step-dot {
    position:absolute; left:-29px; top:2px;
    width:16px; height:16px; border-radius:50%;
    background:#e9ecef; border:2px solid #fff;
    display:flex; align-items:center; justify-content:center;
    font-size:8px; color:#fff;
}
.next-step-dot.done { background:#198754; }
.next-step-dot.current { background:#0d6efd; box-shadow:0 0 0 4px rgba(13,110,253,.15); }

.urgent-box { background:#FAEEDA; border-left:4px solid #BA7517; border-radius:8px; padding:16px; }
.summary-block { border-top:1px solid #e9ecef; padding-top:16px; }
.summary-block summary { cursor:pointer; }
</style>

<script>
// Cancel booking handler (same contract as the shortlist page).
document.getElementById('confirmCancelBtn').addEventListener('click', function () {
    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...';

    fetch(@json(route('booking.cancel-order', $serviceRequest->id)), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (data.success) {
            window.location.href = data.redirect || @json(route('dashboard'));
        } else {
            alert(data.message || 'Could not cancel this booking.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Yes, cancel booking';
        }
    })
    .catch(function () {
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Yes, cancel booking';
    });
});
</script>

<script>
(function () {
    // The wait is typically measured in hours, so polling forever is wasted
    // data. Check briefly in case someone accepts straight away, then stop and
    // let SMS / WhatsApp / push do the re-engagement.
    var STATUS_URL   = @json(route('booking.matching-status', $serviceRequest->id));
    var INTERVAL_MS  = 15000;
    var MAX_ATTEMPTS = 12; // ~3 minutes

    var attempts = 0;
    var panel    = document.getElementById('livePanel');
    var counter  = document.getElementById('notifiedCount');

    function poll() {
        if (attempts++ >= MAX_ATTEMPTS) return;

        fetch(STATUS_URL, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (data) {
                if (!data) return schedule();

                if (counter && typeof data.notified === 'number') {
                    counter.textContent = data.notified;
                }

                if (data.ready && data.redirect) {
                    panel.classList.remove('d-none');
                    window.location.href = data.redirect;
                    return;
                }

                schedule();
            })
            .catch(schedule);
    }

    function schedule() {
        if (attempts < MAX_ATTEMPTS) setTimeout(poll, INTERVAL_MS);
    }

    // Don't burn data in a background tab.
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden && attempts < MAX_ATTEMPTS) poll();
    });

    setTimeout(poll, INTERVAL_MS);
})();
</script>
@endsection
