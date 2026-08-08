@extends('layouts.main')
@section('payment')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i> Complete Registration Payment</h4>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h5 class="mb-2">Service Request: {{ $serviceRequest->service_names }}</h5>
                            <p class="mb-0"><strong>Registration Fee:</strong> ₦10,000.00 (One-time yearly fee)</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('service-request.payment.process', $serviceRequest->id) }}" method="POST"
                            enctype="multipart/form-data" id="payment-form">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Select Payment Method <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check card-option">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment_card" value="card">
                                            <label class="form-check-label" for="payment_card">
                                                <i class="fas fa-credit-card me-2"></i> Credit/Debit Card
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check card-option">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment_bank" value="bank_transfer" checked>
                                            <label class="form-check-label" for="payment_bank">
                                                <i class="fas fa-university me-2"></i> Bank Transfer
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check card-option">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment_wallet" value="wallet">
                                            <label class="form-check-label" for="payment_wallet">
                                                <i class="fas fa-wallet me-2"></i> Wallet
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Payment Details -->
                            <div id="card-payment-details" class="payment-method-details" style="display: none;">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Card Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Cardholder Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cardholder_name">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Card Number <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_number"
                                                    placeholder="1234 5678 9012 3456">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Expiry Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_expiry"
                                                    placeholder="MM/YY">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">CVV <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="card_cvv"
                                                    placeholder="123">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Transfer Details -->
                            <div id="bank_transfer-payment-details" class="payment-method-details">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Bank Transfer Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning">
                                            <h6><i class="fas fa-info-circle me-2"></i>Transfer Instructions</h6>
                                            <p>Transfer <strong>₦10,000.00</strong> to:</p>
                                            <div class="bank-details bg-light p-3 rounded">
                                                <p class="mb-1"><strong>Bank:</strong> GT Bank</p>
                                                <p class="mb-1"><strong>Account Name:</strong> Pik and Crest Dynamic
                                                    Concepts LTD</p>
                                                <p class="mb-1"><strong>Account Number:</strong> 0051480410</p>
                                                <p class="mb-0"><strong>Reference:</strong> <span
                                                        id="bank-reference-display">SR{{ $serviceRequest->id }}_{{ strtoupper(Str::random(6)) }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Transfer Reference <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bank_reference"
                                                    id="bank_reference_input" required readonly>
                                                <small class="form-text text-muted">This reference will be
                                                    auto-filled</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Transfer Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="transfer_date"
                                                    max="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Upload Receipt <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="bank_receipt"
                                                accept=".jpg,.jpeg,.png,.pdf" required>
                                            <small class="form-text text-muted">Upload a clear image or PDF of your
                                                transfer receipt</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Wallet Payment Details -->
                            <div id="wallet-payment-details" class="payment-method-details" style="display: none;">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Wallet Payment</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <p class="mb-2">Current Wallet Balance:
                                                <strong>₦{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</strong>
                                            </p>
                                            <p class="mb-0">After payment:
                                                <strong>₦{{ number_format((auth()->user()->wallet_balance ?? 0) - 10000, 2) }}</strong>
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Wallet PIN <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="wallet_pin" maxlength="6"
                                                placeholder="Enter 6-digit PIN">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Terms -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="payment_terms" id="payment_terms"
                                    required>
                                <label class="form-check-label" for="payment_terms">
                                    I understand that the registration fee of ₦10,000.00 is mandatory and non-refundable.
                                    I authorize the deduction of this amount from my selected payment method.
                                </label>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('service-request.confirmation', $serviceRequest->id) }}"
                                    class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-lock me-2"></i> Complete Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate bank reference
            const bankRef = 'SR{{ $serviceRequest->id }}_{{ strtoupper(Str::random(6)) }}';
            document.getElementById('bank-reference-display').textContent = bankRef;
            document.getElementById('bank_reference_input').value = bankRef;

            // Show/hide payment method details
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const detailsContainers = {
                'card': document.getElementById('card-payment-details'),
                'bank_transfer': document.getElementById('bank_transfer-payment-details'),
                'wallet': document.getElementById('wallet-payment-details')
            };

            function showPaymentDetails(method) {
                // Hide all details
                Object.values(detailsContainers).forEach(container => {
                    if (container) container.style.display = 'none';
                });

                // Show selected method details
                if (detailsContainers[method]) {
                    detailsContainers[method].style.display = 'block';
                }
            }

            // Initialize with bank transfer selected
            showPaymentDetails('bank_transfer');

            // Add change listeners
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (this.checked) {
                        showPaymentDetails(this.value);
                    }
                });
            });

            // Form validation
            document.getElementById('payment-form').addEventListener('submit', function(e) {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const termsAccepted = document.getElementById('payment_terms').checked;

                if (!termsAccepted) {
                    e.preventDefault();
                    alert('Please accept the payment terms to proceed.');
                    return;
                }

                // Add additional validation per payment method if needed
                if (selectedMethod === 'wallet') {
                    const walletBalance = {{ auth()->user()->wallet_balance ?? 0 }};
                    if (walletBalance < 10000) {
                        e.preventDefault();
                        alert('Insufficient wallet balance. Please choose another payment method.');
                        return;
                    }
                }
            });
        });
    </script>
@endsection
