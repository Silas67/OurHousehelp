@extends('layouts.usermain')

@section('booking-payment')
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
                                <td class="text-muted">Service Cost</td>
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
                                    <div class="fw-bold">Pay in Full</div>
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
                            id="optInstallment" onclick="selectOption('installment')">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="payment_type_display" value="installment" id="payInstallment">
                                <label class="form-check-label w-100" for="payInstallment">
                                    <div class="fw-bold">Pay 50% Now</div>
                                    <div class="fs-4 fw-bold text-primary mt-1">
                                        ₦{{ number_format($installment['first_payment']) }}
                                    </div>
                                    <small class="text-muted">
                                        Balance ₦{{ number_format($installment['second_payment']) }} due before staff resumes
                                    </small>
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
                            You can also submit without a receipt and upload later via WhatsApp
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3"
                        style="font-size:15px;" id="bankSubmitBtn">
                        <i class="fas fa-paper-plane me-2"></i>
                        Submit Payment — ₦<span id="bankBtnAmount">{{ number_format($totalCost) }}</span>
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
            <input type="hidden" name="amount_paid" id="paystackAmountPaid" value="">
        </form>
        @endif

    </div>
</div>

<style>
.booking-steps { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; }
.booking-steps .step { padding: 8px 16px; background: #e9ecef; border-radius: 20px; font-size: 14px; color: #666; }
.booking-steps .step.active { background: #0d6efd; color: white; font-weight: bold; }
.booking-steps .step.completed { background: #198754; color: white; }
.payment-option { cursor: pointer; transition: all 0.15s; }
.payment-option:hover { border-color: #198754 !important; }
.payment-option.selected { border-color: #198754 !important; border-width: 2px !important; background: #f0fff4; }
</style>

<script>
const totalCost       = {{ $totalCost }};
const installmentFirst = {{ $installment['first_payment'] }};

let selectedPaymentType = 'full';
let selectedAmount = totalCost;

function selectOption(type) {
    selectedPaymentType = type;
    selectedAmount = type === 'installment' ? installmentFirst : totalCost;

    document.getElementById('payFull').checked       = type === 'full';
    document.getElementById('payInstallment').checked = type === 'installment';
    document.getElementById('optFull').classList.toggle('selected', type === 'full');
    document.getElementById('optInstallment').classList.toggle('selected', type === 'installment');

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
    if (file) {
        document.getElementById('uploadText').innerHTML =
            `<i class="fas fa-check-circle text-success"></i> <strong>${file.name}</strong><br>
             <span style="font-size:11px; color:#6c757d;">${(file.size/1024).toFixed(0)} KB — ready to upload</span>`;
        document.getElementById('uploadArea').style.borderColor = '#198754';
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
            document.getElementById('paystackAmountPaid').value = selectedAmount;
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