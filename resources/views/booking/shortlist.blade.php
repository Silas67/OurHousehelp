@extends('layouts.usermain')

@section('booking-shortlist')
<div  class="row justify-content-center" onload="noBack();"b>
    <div class="col-lg-10"  >

        {{-- Progress Steps --}}
        <div class="d-flex justify-content-center mb-4">
            <div class="booking-steps">
                <span class="step completed"><i class="fas fa-check"></i> Service</span>
                <span class="step completed"><i class="fas fa-check"></i> Staff Matched</span>
                <span class="step active">3. Select Staff</span>
                <span class="step">4. Payment</span>
            </div>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold">Available Staff</h3>
            <p class="text-muted">
                <span id="staffCount">{{ $shortlist->count() }}</span> staff available for your request.
                More may accept as time goes on. Choose wisely — viewing a profile costs 1 credit.
            </p>
            <div class="d-inline-flex align-items-center gap-2 bg-light border rounded px-3 py-1 mb-2">
                <i class="fas fa-coins text-warning"></i>
                <small class="fw-semibold">Your credits: {{ auth()->user()->getCreditBalance() }}</small>
                <a href="{{ route('credits.index') }}" class="btn btn-sm btn-outline-warning ms-2">Buy Credits</a>
            </div>
        </div>

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Live updating indicator --}}
        <div class="text-center mb-3">
            <small class="text-muted">
                <span class="spinner-grow spinner-grow-sm text-success me-1" role="status"></span>
                Live — refreshes every 10 seconds as more staff accept
            </small>
        </div>

        <div class="row g-4" id="staffGrid">
            @foreach($shortlist as $shortlistItem)
                <div class="col-md-6 col-lg-4">
                    @include('booking._staff-card', [
                        'shortlistItem' => $shortlistItem,
                        'serviceRequest' => $serviceRequest
                    ])
                </div>
            @endforeach
        </div>

        @if($serviceRequest->selected_staff_id)
        <div class="text-center mt-4 mb-3">
            <a href="{{ route('booking.trust-assurance', $serviceRequest->id) }}"
                class="btn btn-primary btn-lg px-5">
                Continue <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                All staff are verified. Contact details unlock after you view a profile (costs 1 credit).
            </small>
        </div>

        {{-- Cancel Order Button --}}
        <div class="text-center mt-3 mb-2">
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                <i class="fas fa-times-circle me-1"></i> Cancel this booking
            </button>
        </div>
    </div>
</div>

{{-- Cancel Order Modal --}}
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true" onload="noBack();">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Cancel this Booking?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <p class="text-muted mb-3">Before you cancel, please understand what will happen:</p>
                <ul class="list-unstyled" style="font-size:14px;">
                    <li class="mb-2">
                        <i class="fas fa-coins text-success me-2"></i>
                        <strong>1 credit will be refunded</strong> to your account
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-bell-slash text-warning me-2"></i>
                        All staff who accepted or were notified will be <strong>told the job is cancelled</strong>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-trash text-danger me-2"></i>
                        This booking will be <strong>permanently cancelled</strong> — you will need to start a new booking
                    </li>
                </ul>
                <div class="alert alert-warning py-2 mb-0" style="font-size:13px;">
                    <i class="fas fa-info-circle me-1"></i>
                    If you simply want to wait for more staff to accept, just close this dialog — your booking is still active.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i> Keep my booking
                </button>
                <button type="button" class="btn btn-danger flex-grow-1" id="confirmCancelBtn">
                    <i class="fas fa-times-circle me-1"></i> Yes, cancel booking
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.booking-steps { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }
.booking-steps .step { padding: 8px 16px; background: #e9ecef; border-radius: 20px; font-size: 14px; color: #666; }
.booking-steps .step.active { background: #0d6efd; color: white; font-weight: bold; }
.booking-steps .step.completed { background: #198754; color: white; }
.staff-card { transition: transform 0.2s, box-shadow 0.2s; }
.staff-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
</style>

<script>
// Cancel booking handler
document.getElementById('confirmCancelBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelling...';

    fetch('{{ route("booking.cancel-order", $serviceRequest->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Yes, cancel booking';
        }
    })
    .catch(() => {
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-times-circle me-1"></i> Yes, cancel booking';
    });
});

function noBack() {
    window.history.forward();
}

// Auto refresh every 30 seconds to show new accepted staff
let refreshTimer = setInterval(function() {
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.text())
    .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newGrid = doc.getElementById('staffGrid');
        const newCount = doc.getElementById('staffCount');
        const currentCount = document.getElementById('staffCount');

        if (newGrid && newCount) {
            const newNum = parseInt(newCount.textContent);
            const currentNum = parseInt(currentCount.textContent);

            if (newNum > currentNum) {
                document.getElementById('staffGrid').innerHTML = newGrid.innerHTML;
                document.getElementById('staffCount').textContent = newNum;
            }
        }
    }).catch(() => {});
}, 10000);
</script>
@endsection
