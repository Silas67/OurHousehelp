@extends('layouts.usermain')

@section('booking-payment')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Pay Remaining Balance
                </h5>
            </div>

            <div class="card-body p-4 text-center">

                <p class="text-muted">
                    Your ₦30,000 upfront payment has been confirmed.
                </p>

                <div class="my-4">
                    <div class="text-muted small">Remaining Balance</div>
                    <div class="display-5 fw-bold text-success">
                        ₦{{ number_format($remainingAmount) }}
                    </div>
                </div>

               <p class="text-muted small mb-4">
                    Pay the remaining balance to complete your booking and fully confirm your placement.
                </p>

                <button type="button"
                        class="btn btn-primary btn-lg px-5"
                        id="payRemainingBtn">
                    <i class="fas fa-credit-card me-2"></i>
                    Pay ₦{{ number_format($remainingAmount) }}
                </button>

                <form id="remainingPaystackForm"
                    action="{{ route('booking.remaining-paystack-callback', $serviceRequest->id) }}"
                    method="POST"
                    class="d-none">
                    @csrf

                    <input type="hidden"
                        name="paystack_reference"
                        id="remainingPaystackReference">
                </form>

                @if(config('app.paystack_enabled', false))
                    <script src="https://js.paystack.co/v1/inline.js"></script>

                    <script>
                        const remainingAmount = {{ $remainingAmount }};
                        const paystackKey = '{{ config("paystack.publicKey") }}';
                        const userEmail = '{{ Auth::user()->email }}';

                        document.getElementById('payRemainingBtn')
                            .addEventListener('click', function () {

                                const button = this;
                                const reference = 'BK{{ $serviceRequest->id }}_BAL_' + Date.now();

                                const handler = PaystackPop.setup({
                                    key: paystackKey,
                                    email: userEmail,
                                    amount: remainingAmount * 100,
                                    currency: 'NGN',
                                    ref: reference,

                                    metadata: {
                                        service_request_id: {{ $serviceRequest->id }},
                                        payment_stage: 'remaining'
                                    },

                                    callback: function (response) {
                                        document.getElementById('remainingPaystackReference').value =
                                            response.reference;

                                        button.disabled = true;
                                        button.innerHTML =
                                            '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';

                                        document.getElementById('remainingPaystackForm').submit();
                                    }
                                });

                                handler.openIframe();
                            });
                    </script>
                @endif

                @if(config('app.paystack_enabled', false))
                    <div class="text-muted my-3">OR</div>
                @endif

                <div class="card bg-light border-0 mt-4">
                    <div class="card-body text-start">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-university me-2"></i>
                            Bank Transfer
                        </h6>

                        <p class="small text-muted mb-3">
                            Transfer
                            <strong>₦{{ number_format($remainingAmount) }}</strong>
                            to the company account below, then upload your receipt.
                        </p>

                        <div class="mb-3">
                            <div class="small text-muted">Bank Name</div>
                            <div class="fw-bold">GT Bank</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Account Name</div>
                            <div class="fw-bold">Pik and Crest Dynamic Concepts</div>
                        </div>

                        <div class="mb-4">
                            <div class="small text-muted">Account Number</div>
                            <div class="fw-bold fs-5">0051480410</div>
                        </div>

                        <form
                            action="{{ route('booking.remaining-bank-transfer', $serviceRequest->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            @csrf

                            <label class="form-label fw-semibold">
                                Upload Payment Receipt
                            </label>

                            <input
                                type="file"
                                name="bank_receipt"
                                class="form-control mb-3"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                            >

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Remaining Payment
                            </button>
                        </form>
                    </div>
                </div>


                <div class="mt-3">
                    <a href="{{ route('booking.payment-success', $serviceRequest->id) }}"
                       class="btn btn-outline-secondary">
                        Back
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection