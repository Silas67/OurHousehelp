@extends('layouts.usermain')

@section('booking-management-plans')
@php
    // Same reliable signal used throughout BookingController/payment.blade.php:
    // staff_verification_fee is only ever non-zero for company-managed
    // bookings, and unlike management_plan (which selectManagementPlan()
    // is about to overwrite to the chosen tier), it doesn't change here.
    $isCompanyManagedBooking = (float) $serviceRequest->staff_verification_fee > 0;
    $upfrontAmount = (float) $serviceRequest->management_subscription_fee
        + (float) $serviceRequest->staff_verification_fee;
@endphp
<div class="row justify-content-center">
    <div class="col-lg-11">
        <!-- Progress Steps -->
        <div class="d-flex justify-content-center mb-4">
            <div class="booking-steps">
                <span class="step completed"><i class="fas fa-check"></i> Service</span>
                <span class="step completed"><i class="fas fa-check"></i> Staff</span>
                <span class="step active">3. Choose Plan</span>
                <span class="step">4. Payment</span>
            </div>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold">Choose Your Management Plan</h3>
            <p class="text-muted">How would you like your staff to be managed?</p>
        </div>

        <form action="{{ route('booking.select-plan', $serviceRequest->id) }}" method="POST" id="planForm">
            @csrf

            <!-- Price Anchoring: Show plans Premium -> Assisted -> Starter -->
            <div class="row g-4 mb-4">
                @foreach($plans as $plan)
                <div class="col-md-4">
                    <div class="card h-100 shadow plan-card {{ $plan->slug === 'assisted' ? 'border-primary border-2' : '' }}"
                        id="planCard_{{ $plan->slug }}">
                        @if($plan->label)
                        <div class="card-header text-center py-2
                            {{ $plan->slug === 'assisted' ? 'bg-primary text-white' : 'bg-warning text-dark' }}">
                            <small class="fw-bold">{{ $plan->label }}</small>
                        </div>
                        @endif
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-center mb-3">
                                @if($plan->slug === 'premium')
                                    <i class="fas fa-crown fa-2x text-warning mb-2"></i>
                                @elseif($plan->slug === 'assisted')
                                    <i class="fas fa-hands-helping fa-2x text-primary mb-2"></i>
                                @else
                                    <i class="fas fa-user fa-2x text-secondary mb-2"></i>
                                @endif
                                <h4 class="fw-bold">{{ $plan->name }}</h4>
                                <div class="fs-3 fw-bold text-primary">{{ $plan->price_display }}</div>
                            </div>

                            <ul class="list-unstyled mb-4 flex-grow-1">
                                @foreach($plan->features_list as $feature)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}
                                </li>
                                @endforeach
                            </ul>

                            <!-- Risk Reversal for Assisted & Premium -->
                            @if(in_array($plan->slug, ['assisted', 'premium']))
                            <div class="bg-light rounded p-2 mb-3 text-center">
                                <small class="text-success fw-bold">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    If staff fails to resume, we replace free.
                                </small>
                            </div>
                            @endif

                            <div class="text-center">
                                <input type="radio" name="management_plan" value="{{ $plan->slug }}"
                                    id="plan_{{ $plan->slug }}" class="d-none plan-radio"
                                    {{ $plan->slug === 'assisted' ? 'checked' : '' }}>
                                <label for="plan_{{ $plan->slug }}"
                                    class="btn w-100 {{ $plan->slug === 'assisted' ? 'btn-primary' : 'btn-outline-primary' }} plan-btn">
                                    Select {{ $plan->name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @error('management_plan')
                <div class="alert alert-danger text-center">{{ $message }}</div>
            @enderror

            <!-- Add-ons -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Optional Add-ons</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="checkbox" name="express_matching"
                                    value="1" id="expressMatching">
                                <label class="form-check-label" for="expressMatching">
                                    <strong><i class="fas fa-bolt text-warning me-1"></i>Express Matching - N{{ number_format($expressMatchingCost) }}</strong>
                                    <br>
                                    <small class="text-muted">Get staff within 24-48 hours</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="checkbox" name="monthly_protection"
                                    value="1" id="monthlyProtection">
                                <label class="form-check-label" for="monthlyProtection">
                                    <strong><i class="fas fa-shield-alt text-primary me-1"></i>Monthly Protection - N{{ number_format($monthlyProtectionCost) }}/month</strong>
                                    <br>
                                    <small class="text-muted">Replacement, monitoring, priority matching</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Value Comparison -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-center">Why Choose OurHouseHelp?</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="text-danger fw-bold mb-3">
                                    <i class="fas fa-times-circle me-1"></i>Hiring Through Informal Agent
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>No proper verification</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>No replacement guarantee</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>No accountability</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>Risk of theft</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger me-2"></i>No background check</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-success bg-opacity-10">
                                <h6 class="text-success fw-bold mb-3">
                                    <i class="fas fa-check-circle me-1"></i>Hiring Through OurHouseHelp
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fully verified with ID & guarantor</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Replacement guarantee</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Full accountability</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Background checked</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Structured & documented</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="{{ $isCompanyManagedBooking ? 'button' : 'submit' }}"
                    class="btn btn-primary btn-lg px-5"
                    id="continueBtn">
                    Continue to Payment <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@if($isCompanyManagedBooking)
{{-- Before You Proceed modal — briefly explains the upfront payment before
     the client ever reaches the payment page. Only company-managed bookings
     have an upfront fee to explain; client-managed bookings (which
     shouldn't normally reach this page at all, but are guarded here just
     in case) submit the form immediately with no interstitial. --}}
<div class="modal fade" id="beforeProceedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Before You Proceed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <p class="text-muted mb-3">
                    You are about to proceed to payment for your selected staff.
                </p>
                <div class="p-3 rounded bg-light border text-center mb-3">
                    <div class="text-muted small">Initial Payment</div>
                    <div class="fs-3 fw-bold text-primary">₦{{ number_format($upfrontAmount) }}</div>
                </div>
                <p class="text-muted small mb-3">
                    This payment is the upfront amount required to proceed with
                    your company-managed booking. Any remaining balance will be
                    due when your staff member resumes work.
                </p>
                <p class="text-muted small mb-0">
                    Please review the payment details carefully before continuing.
                </p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100" id="confirmProceedBtn">
                    I Understand &amp; Continue
                </button>
            </div>
        </div>
    </div>
</div>
@endif

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
.plan-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.plan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRadios = document.querySelectorAll('.plan-radio');
    const planBtns = document.querySelectorAll('.plan-btn');
    const planCards = document.querySelectorAll('.plan-card');

    function updatePlanSelection() {
        planBtns.forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-primary');
        });
        planCards.forEach(card => {
            card.classList.remove('border-primary', 'border-2');
        });

        planRadios.forEach(radio => {
            if (radio.checked) {
                const btn = document.querySelector(`label[for="${radio.id}"]`);
                const card = document.getElementById(`planCard_${radio.value}`);
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary');
                card.classList.add('border-primary', 'border-2');
            }
        });
    }

    planCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('button') || e.target.closest('label')) return;
            const slug = this.id.replace('planCard_', '');
            const radio = document.getElementById(`plan_${slug}`);
            radio.checked = true;
            updatePlanSelection();
        });
    });

    planRadios.forEach(radio => {
        radio.addEventListener('change', updatePlanSelection);
    });

    // Before You Proceed modal - only wired up when the modal actually
    // exists in the DOM (company-managed bookings; see the @if guard
    // around the modal markup above).
    const continueBtn = document.getElementById('continueBtn');
    const modalEl = document.getElementById('beforeProceedModal');
    if (continueBtn && modalEl) {
        const proceedModal = new bootstrap.Modal(modalEl);

        continueBtn.addEventListener('click', function () {
            proceedModal.show();
        });

        document.getElementById('confirmProceedBtn').addEventListener('click', function () {
            document.getElementById('planForm').submit();
        });
    }
});
</script>
@endsection