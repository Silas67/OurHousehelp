@extends('layouts.usermain')

@section('booking-payment')
<!-- @php
    // Same reliable signal used throughout BookingController - kept
    // self-contained here (rather than reusing the later @php block further
    // down) so this modal works independently of anything else on the page.
    $showBeforeProceedModal = (float) $serviceRequest->staff_verification_fee > 0
        && $serviceRequest->payment_status !== 'Paid';
    $beforeProceedAmount = (float) $serviceRequest->management_subscription_fee
        + (float) $serviceRequest->staff_verification_fee;
    $beforeProceedRegistrationPaid = (float) $serviceRequest->management_subscription_fee == 0;
@endphp

@if($showBeforeProceedModal)
{{-- Before You Proceed modal - auto-shown the moment this page loads, since
     the intended earlier interstitial (management-plans.blade.php) turned
     out not to be reachable in the live booking flow: showPayment()'s own
     "redirect to management-plans if no plan chosen" guard never fires,
     because management_plan is set at booking creation and is never empty.
     Rather than depend on a step that isn't actually part of the journey,
     this explains the upfront payment right here, before the client can
     interact with anything else on the page (static backdrop, no keyboard
     dismiss - it must be acknowledged via the button). --}}

    <div class="modal fade" id="beforeProceedModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Before You Proceed</h5>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted mb-3">
                        You are about to proceed to payment for your selected staff.
                    </p>
                    <div class="p-3 rounded bg-light border text-center mb-3">
                        <div class="text-muted small">Initial Payment</div>
                        <div class="fs-3 fw-bold text-primary">₦{{ number_format($beforeProceedAmount) }}</div>
                    </div>

                    {{-- Short itemized breakdown instead of a paragraph - covers
                        the "what am I actually paying for" question directly,
                        and correctly reflects that Annual Registration only
                        applies when it hasn't already been paid this year. --}}
                    <ul class="list-unstyled mb-3" style="font-size:14px;">
                        @unless($beforeProceedRegistrationPaid)
                        <li class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-id-card text-primary me-1"></i> Annual Registration</span>
                            <span class="fw-semibold">₦{{ number_format($serviceRequest->management_subscription_fee) }}</span>
                        </li>
                        @endunless
                        <li class="d-flex justify-content-between">
                            <span><i class="fas fa-user-shield text-primary me-1"></i> Staff Verification</span>
                            <span class="fw-semibold">₦{{ number_format($serviceRequest->staff_verification_fee) }}</span>
                        </li>
                    </ul>

                    @if($beforeProceedRegistrationPaid)
                    <p class="text-muted small mb-3">
                        Your Annual Registration is already active, so no
                        registration fee applies this time — this is purely for
                        verifying your selected staff and their guarantors. The
                        remaining balance is due when your staff resumes work.
                    </p>
                    @else
                    <p class="text-muted small mb-3">
                        Registration covers your account for 12 months. Verification
                        covers a thorough check of your selected staff and their
                        guarantors. The remaining balance is due when your staff
                        resumes work.
                    </p>
                    @endif

                    <p class="text-muted small mb-0">
                        Please review the payment details carefully before continuing.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary w-100" id="confirmProceedBtn" data-bs-dismiss="modal">
                        I Understand &amp; Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif -->

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Progress Steps --}}
        <div class="d-flex justify-content-center mb-4">
            <div class="booking-steps">
                <span class="step completed"><i class="fas fa-check"></i> Service</span>
                <span class="step completed"><i class="fas fa-check"></i> Staff</span>
                <span class="step active">3. Payment</span>

            </div>
        </div>

        <h3 class="fw-bold text-center mb-4">Complete Your Payment</h3>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Cost Breakdown --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">
                                    Service Cost
                                    @if($serviceRequest->billing_months > 1)
                                        <br><small>total for {{ $serviceRequest->billing_months }} months
                                        (₦{{ number_format($serviceRequest->monthly_service_cost) }}/month)</small>
                                    @endif
                                </td>
                                <td class="text-end">₦{{ number_format($serviceRequest->grand_cost) }}</td>
                            </tr>
                            @if($serviceRequest->management_plan_cost > 0)
                            <tr>
                                <td class="text-muted">
                                    Management Plan
                                    <br><small>{{ $plan?->name ?? ucfirst($serviceRequest->management_plan) }}</small>
                                </td>
                                <td class="text-end">₦{{ number_format($serviceRequest->management_plan_cost) }}</td>
                            </tr>
                            @endif
                            {{-- These are inside total_booking_cost, so they must be
                                 listed or the breakdown will not sum to its own Total. --}}

                            @if($serviceRequest->express_matching && $serviceRequest->express_matching_cost > 0)
                                <tr>
                                    <td class="text-muted">Express Matching</td>
                                    <td class="text-end">₦{{ number_format($serviceRequest->express_matching_cost) }}</td>
                                </tr>
                            @endif

                            @if($serviceRequest->monthly_protection && $serviceRequest->monthly_protection_cost > 0)
                            <tr>
                                <td class="text-muted">Monthly Protection</td>
                                <td class="text-end">
                                    ₦{{ number_format($serviceRequest->monthly_protection_cost) }}
                                </td>
                            </tr>
                            @endif

                            {{--
                                staff_verification_fee is used here instead of
                                checking management_plan === 'company-managed'.
                                By this point in the flow, selectManagementPlan()
                                has already overwritten management_plan to the
                                chosen tier ('starter'/'assisted'/'premium'), so
                                that check was never true for any real booking
                                that passed through the management-plans page -
                                these two rows were silently never rendering.
                                staff_verification_fee is set unconditionally
                                for every company-managed booking regardless of
                                tier or registration status, so it's reliable.
                            --}}
                            @if($serviceRequest->staff_verification_fee > 0)
                                <tr>
                                    <td class="text-muted">
                                        Staff Verification
                                        <br>
                                        <small>Thorough verification of selected staff and their guarantors</small>
                                    </td>
                                    <td class="text-end">
                                        ₦{{ number_format($serviceRequest->staff_verification_fee) }}
                                    </td>
                                </tr>
                            @endif

                            @if($serviceRequest->staff_verification_fee > 0 && $serviceRequest->management_subscription_fee > 0)
                            <tr>
                                <td class="text-muted">
                                    Annual Registration Fee
                                    <br>
                                    <small>Valid for 12 months</small>
                                </td>
                                <td class="text-end">
                                    ₦{{ number_format($serviceRequest->management_subscription_fee) }}
                                </td>
                            </tr>
                            @endif

                            <tr class="border-top">
                                <td class="fw-bold fs-5">Total</td>
                                <td class="text-end fw-bold fs-5 text-success">
                                    ₦{{ number_format($totalCost) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Already paid --}}
        @if($serviceRequest->payment_status === 'Paid')
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="fw-bold text-success">Payment Already Completed</h4>
                    <p class="text-muted">You have already paid for this booking.</p>
                    @php
                        $selectedShortlist = $serviceRequest->shortlistedStaff()
                            ->where('staff_id', $serviceRequest->selected_staff_id)->first();
                    @endphp
                    @if($selectedShortlist)
                        <a href="{{ route('booking.staff-profile', [$serviceRequest->id, $selectedShortlist->id]) }}"
                            class="btn btn-primary px-5">
                            <i class="fas fa-user me-2"></i>View Staff Details
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary px-5">
                            <i class="fas fa-home me-2"></i>Go to Dashboard
                        </a>
                    @endif
                </div>
            </div>

        @else

        {{-- Payment Type Selection --}}
        @php
            // staff_verification_fee (not management_plan) is the reliable
            // signal here - see the comment above the cost-breakdown rows
            // for why management_plan can't be trusted at this point.
            $isCompanyManaged = (float) $serviceRequest->staff_verification_fee > 0;
            $registrationAlreadyPaid = $isCompanyManaged && (float) $serviceRequest->management_subscription_fee == 0;
            $firstPaymentLabel = '₦' . number_format($installment['first_payment']);

            if ($isCompanyManaged && !$registrationAlreadyPaid) {
                $deferredExplanation = $firstPaymentLabel . ' is your upfront payment to proceed with your company-managed booking, including applicable registration and staff verification fees. The remaining ₦' . number_format($installment['second_payment']) . ' is due when your staff resumes.';
            } elseif ($isCompanyManaged && $registrationAlreadyPaid) {
                $deferredExplanation = $firstPaymentLabel . ' is your upfront payment to proceed with your company-managed booking and staff verification. The remaining ₦' . number_format($installment['second_payment']) . ' is due when your staff resumes.';
            } else {
                $deferredExplanation = 'This is your first payment toward the total shown in the Payment Summary above — capped at ' . $firstPaymentLabel . ' regardless of your total cost. The remaining ₦' . number_format($installment['second_payment']) . ' becomes due once your staff member resumes work — not before. This lets you lock in your booking now without paying the full amount upfront.';
            }
        @endphp

        <!-- PAYMENT TYPE -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="fw-bold mb-0">Select Payment Type</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 payment-option selected"
                            id="optFull" onclick="selectOption('full')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="payment_type_display" value="full" id="payFull" checked>
                                <label class="form-check-label w-100" for="payFull">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold">Pay in Full</span>
                                        <i class="fas fa-info-circle text-muted payment-info-icon"
                                            tabindex="0"
                                            role="button"
                                            aria-label="More information about paying in full"
                                            data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            data-bs-placement="top"
                                            data-bs-title="Pay in Full"
                                            data-bs-content="You pay the entire booking cost — ₦{{ number_format($totalCost) }} — in one transaction now. There's no balance left afterward and nothing further to schedule. Your booking moves straight to staff verification as soon as we confirm this payment."
                                            onclick="event.preventDefault(); event.stopPropagation();"></i>
                                    </div>
                                    <div class="fs-4 fw-bold text-success mt-1">
                                        ₦{{ number_format($totalCost) }}
                                    </div>
                                    <small class="text-muted">One-time complete payment</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 payment-option"
                            id="optInstallment" onclick="selectOption('deferred')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="payment_type_display" value="deferred" id="payInstallment">
                                <label class="form-check-label w-100" for="payInstallment">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="fw-bold">Pay {{ $firstPaymentLabel }} Now</span>
                                        <i class="fas fa-info-circle text-muted payment-info-icon"
                                            tabindex="0"
                                            role="button"
                                            aria-label="More information about paying {{ $firstPaymentLabel }} now"
                                            data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            data-bs-placement="top"
                                            data-bs-title="Pay {{ $firstPaymentLabel }} Now"
                                            data-bs-content="{{ $deferredExplanation }}"
                                            onclick="event.preventDefault(); event.stopPropagation();"></i>
                                    </div>
                                    <div class="fs-4 fw-bold text-primary mt-1">
                                        {{ $firstPaymentLabel }}
                                    </div>
                                    <small class="text-muted">
                                        Balance ₦{{ number_format($installment['second_payment']) }} due when your staff resumes
                                    </small>
                                    <div class="mt-1" id="deferredInfoNudge">
                                        <small class="text-primary" style="font-size:11px;">
                                            <i class="fas fa-hand-point-up me-1"></i>More info? Tap the <i class="fas fa-info-circle"></i> icon
                                        </small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BANK TRANSFER (Primary) ── --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-university me-2"></i>Bank Transfer
                    <span class="badge bg-success ms-2" style="font-size:11px;">Recommended</span>
                </h5>
            </div>
            <div class="card-body p-4">

                <p class="text-muted mb-4">
                    Transfer <strong id="transferAmount">₦{{ number_format($totalCost) }}</strong>
                    to our account below, then upload your receipt. We'll confirm within
                    <strong>30 minutes</strong> during business hours.
                </p>

                {{-- Bank Details --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6 text-center">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <i class="fas fa-university fa-2x text-muted mb-2"></i>
                            <div class="small text-muted">Bank Name</div>
                            <div class="fw-bold">GT Bank</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <i class="fas fa-user-tie fa-2x text-muted mb-2"></i>
                            <div class="small text-muted">Account Name</div>
                            <div class="fw-bold" style="font-size:13px;">
                                Pik and Crest Dynamic Concepts
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <i class="fas fa-wallet fa-2x text-muted mb-2"></i>
                            <div class="small text-muted">Account Number</div>
                            <div class="fw-bold fs-4" style="letter-spacing:0.1em;">
                                0051480410
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                onclick="navigator.clipboard.writeText('0051480410'); this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Bank Transfer Form --}}
                <form action="{{ route('booking.process-payment', $serviceRequest->id) }}"
                    method="POST" enctype="multipart/form-data" id="bankTransferForm">
                    @csrf
                    <input type="hidden" name="payment_method" value="bank_transfer">
                    <input type="hidden" name="payment_type" id="bankPaymentType" value="full">
                    <input type="hidden" name="payment_reference"
                        value="{{ $paymentReference ?? 'BK' . $serviceRequest->id . '_' . strtoupper(substr(md5(uniqid()), 0, 6)) }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Upload Payment Receipt <span class="text-danger">*</span>
                        </label>
                        <div class="border rounded p-4 text-center" id="uploadArea"
                            style="border-style:dashed !important; cursor:pointer; background:#fafafa;"
                            onclick="document.getElementById('bankReceipt').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <div class="text-muted small" id="uploadText">
                                Click to upload receipt
                                <br><span style="font-size:11px;">JPG, PNG or PDF — max 5MB</span>
                            </div>
                        </div>
                        <input type="file" name="bank_receipt" id="bankReceipt"
                            accept=".jpg,.jpeg,.png,.pdf" class="d-none">
                        @error('bank_receipt')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            A receipt is required so we can confirm your payment.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3"
                        style="font-size:15px;" id="bankSubmitBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i>
                        Upload receipt to continue — ₦<span id="bankBtnAmount">{{ number_format($totalCost) }}</span>
                    </button>
                </form>

                {{-- WhatsApp alternative --}}
                <div class="text-center mt-3">
                    <small class="text-muted">Or send payment proof on WhatsApp for faster processing:</small><br>
                    <a href="https://wa.me/2348126237436?text=Hi%2C%20I%20just%20made%20a%20booking%20payment%20for%20service%20request%20%23{{ $serviceRequest->id }}"
                        target="_blank" class="btn btn-sm btn-outline-success mt-1">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp Us
                    </a>
                </div>

            </div>
        </div>

        {{-- ── PAYSTACK (Disabled until approved) ── --}}
        <div class="card shadow-sm mb-4 opacity-75">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-muted">
                    <i class="fas fa-credit-card me-2"></i>Pay with Card
                    @if(!config('app.paystack_enabled', false))
                        <span class="badge bg-secondary ms-2" style="font-size:11px;">Coming Soon</span>
                    @endif
                </h5>
            </div>
            <div class="card-body p-4">
                @if(config('app.paystack_enabled', false))
                    {{-- Paystack live --}}
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg px-5" id="paystackBtn">
                            <i class="fas fa-credit-card me-2"></i>
                            Pay ₦<span id="payAmount">{{ number_format($totalCost) }}</span> Securely
                        </button>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Secured by Paystack — Visa, Mastercard & Verve accepted
                            </small>
                        </div>
                    </div>
                @else
                    {{-- Paystack disabled --}}
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-5"
                            disabled style="cursor:not-allowed;">
                            <i class="fas fa-credit-card me-2"></i>Card Payment — Coming Soon
                        </button>
                        <p class="text-muted small mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Card payments will be available shortly. Please use bank transfer for now.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        @endif

        {{-- Hidden Paystack form (only used when Paystack is enabled) --}}
        @if(config('app.paystack_enabled', false))
        <form id="paystackForm"
            action="{{ route('booking.paystack-callback', $serviceRequest->id) }}"
            method="POST" class="d-none">
            @csrf
            <input type="hidden" name="payment_type" id="paystackPaymentType" value="full">
            <input type="hidden" name="paystack_reference" id="paystackReference" value="">
            {{-- amount_paid is not submitted: the server reads the settled amount
                 from Paystack's verify response instead of trusting the browser. --}}
        </form>
        @endif

    </div>
</div>

<style>
    .booking-steps { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }
    .booking-steps .step { padding: 8px 16px; background: #e9ecef; border-radius: 20px; font-size: 14px; color: #666; }
    .booking-steps .step.active { background: #0d6efd; color: white; font-weight: bold; }
    .booking-steps .step.completed { background: #198754; color: white; }
    .payment-option { cursor: pointer; transition: all 0.50s; }
    .payment-option:hover { border-color: #198754 !important; }
    .payment-option.selected { border-color: #198754 !important; border-width: 2px !important; background: #f0fff4; }

    .payment-info-icon {
        cursor: pointer;
        font-size: 13px;
    }
    .payment-info-icon:hover,
    .payment-info-icon:focus {
        color: #0d6efd !important;
    }
    /* Keep the popover readable on narrow phones without overflowing */
    .popover {
        max-width: 260px;
    }

    /* Draws the eye to the ₦30,000 explanation before the auto-open popover
       fires, and stays as a passive hint if the client dismisses it early. */
    #deferredInfoNudge {
        animation: nudge-pulse 1.8s ease-in-out 3;
    }
    @keyframes nudge-pulse {
        0%, 100% { opacity: 0.65; }
        50% { opacity: 1; }
    }
</style>

<script>
    const totalCost       = {{ $totalCost }};
    const installmentFirst = {{ $installment['first_payment'] }};

   
    document.addEventListener('DOMContentLoaded', function () {
        const beforeProceedEl = document.getElementById('beforeProceedModal');
        if (beforeProceedEl) {
            new bootstrap.Modal(beforeProceedEl).show();
        }

        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
            new bootstrap.Popover(el);
        });

       
        const deferredIcon = document.querySelector('#optInstallment .payment-info-icon');
        if (deferredIcon && window.bootstrap) {
            const deferredPopover = bootstrap.Popover.getInstance(deferredIcon);
            if (deferredPopover) {
                setTimeout(function () {
                    deferredPopover.show();
                }, 700);

                const autoCloseTimer = setTimeout(function () {
                    deferredPopover.hide();
                }, 7000);

                document.addEventListener('click', function cancelAutoClose() {
                    clearTimeout(autoCloseTimer);
                    document.removeEventListener('click', cancelAutoClose);
                }, { once: true });
            }
        }
    });

    let selectedPaymentType = 'full';
    let selectedAmount = totalCost;

    function selectOption(type) {
        selectedPaymentType = type;
        selectedAmount = type === 'deferred' ? installmentFirst : totalCost;

        document.getElementById('payFull').checked       = type === 'full';
        document.getElementById('payInstallment').checked = type === 'deferred';
        document.getElementById('optFull').classList.toggle('selected', type === 'full');
        document.getElementById('optInstallment').classList.toggle('selected', type === 'deferred');

        // Update bank transfer form
        document.getElementById('bankPaymentType').value = type;
        document.getElementById('bankBtnAmount').textContent = selectedAmount.toLocaleString('en-NG');
        document.getElementById('transferAmount').textContent = '₦' + selectedAmount.toLocaleString('en-NG');

        @if(config('app.paystack_enabled', false))
        // Update Paystack amount display
        const payAmountEl = document.getElementById('payAmount');
        if (payAmountEl) payAmountEl.textContent = selectedAmount.toLocaleString('en-NG');
        @endif
    }

   
    

    // Show filename when receipt selected
    document.getElementById('bankReceipt').addEventListener('change', function() {
        const file = this.files[0];
        const submitBtn = document.getElementById('bankSubmitBtn');
        const uploadArea = document.getElementById('uploadArea');

        if (file) {
            document.getElementById('uploadText').innerHTML =
                `<i class="fas fa-check-circle text-success"></i>
                <strong>${file.name}</strong><br>
                <span style="font-size:11px; color:#6c757d;">
                    ${(file.size / 1024).toFixed(0)} KB — ready to upload
                </span>`;

            uploadArea.style.borderColor = '#198754';

            submitBtn.disabled = false;
            submitBtn.innerHTML =
                `<i class="fas fa-paper-plane me-2"></i>
                Submit Payment — ₦<span id="bankBtnAmount">${selectedAmount.toLocaleString('en-NG')}</span>`;
        } else {
            uploadArea.style.borderColor = '#dc3545';

            submitBtn.disabled = true;
            submitBtn.innerHTML =
                `<i class="fas fa-lock me-2"></i>
                Upload receipt to continue`;
        }
    });

    @if(config('app.paystack_enabled', false))
    // Paystack handler (only runs when enabled)
    const userEmail      = '{{ Auth::user()->email }}';
    const paystackKey    = '{{ config("paystack.publicKey") }}';
    const serviceReqId   = {{ $serviceRequest->id }};

    document.getElementById('paystackBtn').addEventListener('click', function () {
        const reference = 'BK{{ $serviceRequest->id }}_' + Date.now();

        const handler = PaystackPop.setup({
            key: paystackKey,
            email: userEmail,
            amount: selectedAmount * 100,
            currency: 'NGN',
            ref: reference,
            metadata: {
                service_request_id: serviceReqId,
                payment_type: selectedPaymentType,
            },
            callback: function(response) {
                document.getElementById('paystackReference').value  = response.reference;
                document.getElementById('paystackPaymentType').value = selectedPaymentType;
                document.getElementById('paystackBtn').disabled = true;
                document.getElementById('paystackBtn').innerHTML =
                    '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
                document.getElementById('paystackForm').submit();
            },
            onClose: function() {}
        });

        handler.openIframe();
    });
    @endif
</script>

@if(config('app.paystack_enabled', false))
<script src="https://js.paystack.co/v1/inline.js"></script>
@endif

@endsection