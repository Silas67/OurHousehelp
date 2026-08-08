@extends('layouts.main')

@section('service_registration')
    <style>
        .payment-option-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .payment-option-card:hover {
            border-color: #479c18;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .payment-option-card.active {
            border-color: #479c18;
            background: linear-gradient(to bottom right, rgba(71, 156, 24, 0.05), rgba(71, 156, 24, 0.1));
            box-shadow: 0 8px 20px rgba(71, 156, 24, 0.15);
        }

        .payment-option-card input[type="radio"] {
            display: none;
        }

        .bank-details {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            border-left: 4px solid #479c18;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        /* Receipt Preview Styles */
        .receipt-preview-container {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: #f8f9fa;
        }

        .receipt-preview-container.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .receipt-preview {
            max-width: 300px;
            margin: 0 auto;
            position: relative;
        }

        .receipt-preview-img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 10px;
        }

        .receipt-preview .pdf-icon {
            font-size: 4rem;
            color: #dc3545;
        }

        .receipt-preview .file-name {
            margin-top: 10px;
            font-size: 0.9rem;
            word-break: break-all;
            background: white;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .remove-receipt-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dc3545;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            z-index: 10;
        }

        .remove-receipt-btn:hover {
            background: #c82333;
        }

        .receipt-upload-area {
            border: 2px dashed #479c18;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            background: rgba(71, 156, 24, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .receipt-upload-area:hover {
            background: rgba(71, 156, 24, 0.1);
            border-color: #479c18;
        }

        .receipt-upload-area i {
            font-size: 3rem;
            color: #479c18;
            margin-bottom: 15px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="mb-0 text-white"><i class="fas fa-credit-card me-2 text-white"></i> Registration Payment
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (auth()->check() && auth()->user()->is_registered)
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle fa-3x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">You're Already Registered!</h5>
                                        <p class="mb-0">Your registration is active until
                                            {{ auth()->user()->registration_expires_at ? auth()->user()->registration_expires_at->format('F d, Y') : 'N/A' }}.
                                            No additional payment required.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('service-request.create', $user->id) }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-2"></i> Continue to Service Request
                                </a>
                            </div>
                        @else
                            {{-- <form action="{{ route('service-request.save-registration', $user->id) }}" method="POST"
                                enctype="multipart/form-data" id="registrationForm"> --}}
                            <form action="{{ route('service-request.save-registration', ['user' => $user->id]) }}"
                                method="POST" enctype="multipart/form-data" id="registrationForm">
                                @csrf

                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">Registration Required</h5>
                                            <p class="mb-0">A one-time yearly registration fee of <strong
                                                    class="h5">₦10,000.00</strong> is required to activate your
                                                service
                                                request.
                                            </p>
                                            <button type="button" class="btn btn-light btn-sm mt-2" data-bs-toggle="modal"
                                                data-bs-target="#reasonsModal">
                                                <i class="fas fa-list me-1"></i> View All 10 Benefits
                                            </button>

                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-4 text-center" hidden>When would you like to pay?</h5>

                                <div class="row justify-content-center" hidden>
                                    <div class="col-md-5 mb-4">
                                        <div class="payment-option-card active" id="payNowCard">
                                            <div class="mb-3">
                                                <i class="fas fa-credit-card fa-3x" style="color: #28a745;"></i>
                                            </div>
                                            <h4 class="mb-2">Pay Now</h4>
                                            <p class="text-muted mb-3">Complete registration immediately and activate
                                                your
                                                service request.</p>
                                            <div class="mt-3">
                                                <span class="h3 text-success">₦10,000.00</span>
                                                <div class="text-success small">One-time yearly fee</div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="badge bg-success p-2">Recommended</div>
                                            </div>
                                            <input type="radio" name="payment_choice" value="now" id="pay_now"
                                                checked>
                                        </div>
                                    </div>

                                    <div class="col-md-5 mb-4">
                                        <div class="payment-option-card" id="payLaterCard">
                                            <div class="mb-3">
                                                <i class="fas fa-clock fa-3x" style="color: #6c757d;"></i>
                                            </div>
                                            <h4 class="mb-2">Pay Later</h4>
                                            <p class="text-muted mb-3">Create service request now, complete registration
                                                payment later.</p>
                                            <div class="mt-3">
                                                <span class="h3 text-muted">₦10,000.00</span>
                                                <div class="text-muted small">Pay within 7 days</div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="badge bg-warning p-2">Pending</div>
                                            </div>
                                            <input type="radio" name="payment_choice" value="later" id="pay_later">
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details (shown when Pay Now selected) -->
                                <div id="payment-details" class="mt-4">
                                    <div class="card">
                                        <div class="card-header" style="background: #479c18; color: white;">
                                            <h5 class="mb-0"><i class="fas fa-university me-2"></i> Bank Transfer
                                                Instructions</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <p class="mb-2">Please transfer the registration fee of
                                                    <strong>₦10,000.00</strong> to the following account:
                                                </p>
                                                <div class="bank-details mt-3">
                                                    <p class="mb-2"><strong>Bank:</strong> GT Bank</p>
                                                    <p class="mb-2"><strong>Account Name:</strong> Pik and Crest
                                                        Dynamic
                                                        Concepts LTD</p>
                                                    <p class="mb-0"><strong>Account Number:</strong> 0051480410</p>
                                                </div>
                                                <p class="mt-3 mb-0"><strong>Reference:</strong> <span
                                                        id="bank-reference-display"
                                                        class="text-primary">OHH{{ date('Ymd') }}{{ rand(1000, 9999) }}</span>
                                                </p>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Transfer Reference <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="bank_reference"
                                                        id="bank_reference_input" readonly required>
                                                    <div class="form-text">Use this reference when making the transfer
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Transfer Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="transfer_date"
                                                        max="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>

                                            <!-- Receipt Upload Area -->
                                            <div class="mb-3">
                                                <label class="form-label">Upload Receipt <span
                                                        class="text-danger">*</span></label>

                                                <div class="receipt-upload-area" id="receipt-upload-area">
                                                    <i class="fas fa-file-upload"></i>
                                                    <h5>Click to Upload Receipt</h5>
                                                    <p class="text-muted mb-2">Supported formats: JPG, PNG, PDF</p>
                                                    <p class="text-muted small">Max file size: 5MB</p>
                                                    <input type="file" class="form-control d-none" name="bank_receipt"
                                                        id="bank_receipt" accept=".jpg,.jpeg,.png,.pdf" required>
                                                </div>

                                                <!-- Receipt Preview Container -->
                                                <div class="receipt-preview-container" id="receipt-preview-container">
                                                    <h6 class="text-center mb-3">Receipt Preview</h6>
                                                    <div class="receipt-preview" id="receipt-preview">
                                                        <!-- Preview will be inserted here -->
                                                    </div>
                                                    <div class="text-center mt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            id="change-receipt-btn">
                                                            <i class="fas fa-exchange-alt me-1"></i> Change Receipt
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-text mt-2">Upload a clear image or PDF of your bank
                                                    transfer
                                                    receipt (Max: 5MB)</div>
                                            </div>

                                            <!-- Payment Method Selection -->
                                            <div class="mb-3">
                                                <label class="form-label">Payment Method <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="payment_method" id="payment_method"
                                                    required>
                                                    <option value="bank_transfer" selected>Bank Transfer</option>
                                                    <option value="card" hidden>Credit/Debit Card</option>
                                                    <option value="wallet" hidden>Wallet</option>
                                                </select>
                                            </div>

                                            <!-- Payment fields for different methods (conditionally shown) -->
                                            <div id="bank_transfer_fields" style="display: block;">
                                                <!-- Bank transfer fields already shown above -->
                                            </div>

                                            {{-- <div id="card_fields" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Card Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="card_number"
                                                            placeholder="1234 5678 9012 3456">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Expiry <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="card_expiry"
                                                            placeholder="MM/YY">
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">CVV <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="card_cvv"
                                                            placeholder="123">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cardholder Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="cardholder_name"
                                                        placeholder="John Doe">
                                                </div>
                                            </div>

                                            <div id="wallet_fields" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Wallet PIN <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="wallet_pin"
                                                        placeholder="Enter your wallet PIN">
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <!-- Payment Terms -->
                                    <div class="form-check mt-4 terms-checkbox-container text-danger text-start">
                                        <input class="form-check-input terms-checkbox" type="checkbox"
                                            name="payment_terms" id="payment_terms" required>
                                        <label class="form-check-label" for="payment_terms">
                                            I understand that the registration fee of ₦10,000.00 is required to activate
                                            my
                                            service request. I authorize the processing of this payment.
                                        </label>
                                    </div>

                                    <style>
                                        /* Terms and Conditions Styling */
                                        .terms-checkbox-container {
                                            padding: 20px;
                                            background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
                                            border: 2px solid #ffc107;
                                            border-radius: 10px;
                                            margin-bottom: 15px;
                                        }

                                        .terms-checkbox {
                                            margin-top: 5px;
                                            margin-right: 15px;
                                            border-width: 2px;
                                        }

                                        .terms-checkbox:checked {
                                            background-color: #198754;
                                            border-color: #198754;
                                            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
                                        }

                                        .terms-checkbox:focus {
                                            border-color: #198754;
                                            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
                                        }

                                        .terms-label {
                                            cursor: pointer;
                                            padding: 10px 0;
                                        }

                                        .terms-label:hover {
                                            color: #0a58ca;
                                        }

                                        /* Make the warning box more prominent */
                                        .alert-warning.border-3 {
                                            border-width: 3px !important;
                                        }

                                        /* Animation for when checkbox is checked */
                                        @keyframes checkboxPulse {
                                            0% {
                                                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
                                            }

                                            70% {
                                                box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
                                            }

                                            100% {
                                                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
                                            }
                                        }

                                        .terms-checkbox:checked {
                                            animation: checkboxPulse 0.6s ease;
                                        }
                                    </style>
                                </div>

                                <!-- Pay Later Notice -->
                                <div id="pay-later-notice" class="alert alert-info mt-4" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock fa-2x me-3  text-white"></i>
                                        <div>
                                            <h5 class="mb-2">Pay Later Option Selected</h5>
                                            <p class="mb-0">Your service request will be created but will not be
                                                activated until you complete the ₦10,000 registration payment within 7
                                                days.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5" id="submit-btn">
                                        <i class="fas fa-check-circle me-2"></i> Complete Registration
                                    </button>
                                    <a href="{{ route('service-request.create', $user->id) }}"
                                        class="btn btn-outline-secondary ms-2">
                                        Skip for Now
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reasons to Register Modal -->
    @include('partials._registration_benefits')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const payNowCard = document.getElementById('payNowCard');
            const payLaterCard = document.getElementById('payLaterCard');
            const paymentDetails = document.getElementById('payment-details');
            const payLaterNotice = document.getElementById('pay-later-notice');
            const paymentMethod = document.getElementById('payment_method');
            const submitBtn = document.getElementById('submit-btn');

            // Receipt upload elements
            const receiptUploadArea = document.getElementById('receipt-upload-area');
            const receiptFileInput = document.getElementById('bank_receipt');
            const receiptPreviewContainer = document.getElementById('receipt-preview-container');
            const receiptPreview = document.getElementById('receipt-preview');
            const changeReceiptBtn = document.getElementById('change-receipt-btn');

            // Set initial bank reference
            generateBankReference();

            // Payment choice handling
            payNowCard.addEventListener('click', function() {
                selectPaymentChoice('now');
            });

            payLaterCard.addEventListener('click', function() {
                selectPaymentChoice('later');
            });

            // Receipt upload handling
            receiptUploadArea.addEventListener('click', function() {
                receiptFileInput.click();
            });

            receiptFileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    previewReceipt(this.files[0]);
                }
            });

            changeReceiptBtn.addEventListener('click', function() {
                // Reset the preview
                receiptPreviewContainer.classList.remove('active');
                receiptUploadArea.style.display = 'block';
                receiptFileInput.value = '';
            });

            function previewReceipt(file) {
                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    receiptFileInput.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a JPG, PNG, or PDF file.');
                    receiptFileInput.value = '';
                    return;
                }

                // Hide upload area and show preview
                receiptUploadArea.style.display = 'none';
                receiptPreviewContainer.classList.add('active');

                // Clear previous preview
                receiptPreview.innerHTML = '';

                if (file.type === 'application/pdf') {
                    // PDF preview
                    receiptPreview.innerHTML = `
                        <div class="text-center">
                            <i class="fas fa-file-pdf pdf-icon"></i>
                            <div class="file-name">${file.name}</div>
                        </div>
                    `;
                } else {
                    // Image preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        receiptPreview.innerHTML = `
                            <button type="button" class="remove-receipt-btn" id="remove-receipt-btn">
                                <i class="fas fa-times"></i>
                            </button>
                            <img src="${e.target.result}" alt="Receipt Preview" class="receipt-preview-img">
                            <div class="file-name mt-2">${file.name}</div>
                        `;

                        // Add event listener to remove button
                        document.getElementById('remove-receipt-btn').addEventListener('click', function() {
                            resetReceiptUpload();
                        });
                    };
                    reader.readAsDataURL(file);
                }

                // Add remove functionality for PDF preview
                setTimeout(() => {
                    const removeBtn = document.getElementById('remove-receipt-btn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            resetReceiptUpload();
                        });
                    } else if (receiptPreview.querySelector('.pdf-icon')) {
                        // Add remove button for PDF
                        const pdfContainer = receiptPreview.querySelector('.text-center');
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.className = 'remove-receipt-btn';
                        removeButton.innerHTML = '<i class="fas fa-times"></i>';
                        removeButton.id = 'remove-receipt-btn';
                        pdfContainer.appendChild(removeButton);

                        removeButton.addEventListener('click', function() {
                            resetReceiptUpload();
                        });
                    }
                }, 100);
            }

            function resetReceiptUpload() {
                receiptPreviewContainer.classList.remove('active');
                receiptUploadArea.style.display = 'block';
                receiptFileInput.value = '';
                receiptPreview.innerHTML = '';
            }

            function selectPaymentChoice(choice) {
                // Update card styles
                if (choice === 'now') {
                    payNowCard.classList.add('active');
                    payLaterCard.classList.remove('active');
                    document.getElementById('pay_now').checked = true;
                    document.getElementById('pay_later').checked = false;

                    // Show payment details
                    paymentDetails.style.display = 'block';
                    payLaterNotice.style.display = 'none';

                    // Make payment fields required
                    setPaymentFieldsRequired(true);
                } else {
                    payNowCard.classList.remove('active');
                    payLaterCard.classList.add('active');
                    document.getElementById('pay_now').checked = false;
                    document.getElementById('pay_later').checked = true;

                    // Hide payment details
                    paymentDetails.style.display = 'none';
                    payLaterNotice.style.display = 'block';

                    // Remove required from payment fields
                    setPaymentFieldsRequired(false);
                }
            }

            function setPaymentFieldsRequired(isRequired) {
                const paymentFields = [
                    'payment_method',
                    'payment_terms',
                    'bank_reference',
                    'transfer_date',
                    'bank_receipt',
                    'card_number',
                    'card_expiry',
                    'card_cvv',
                    'cardholder_name',
                    'wallet_pin'
                ];

                paymentFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        if (isRequired) {
                            field.setAttribute('required', 'required');
                        } else {
                            field.removeAttribute('required');
                        }
                    }
                });
            }

            // Payment method change handler
            paymentMethod.addEventListener('change', function() {
                const method = this.value;

                // Hide all payment method specific fields
                document.getElementById('bank_transfer_fields').style.display = 'none';
                document.getElementById('card_fields').style.display = 'none';
                document.getElementById('wallet_fields').style.display = 'none';

                // Show selected payment method fields
                if (method === 'bank_transfer') {
                    document.getElementById('bank_transfer_fields').style.display = 'block';
                    generateBankReference();
                } else if (method === 'card') {
                    document.getElementById('card_fields').style.display = 'block';
                } else if (method === 'wallet') {
                    document.getElementById('wallet_fields').style.display = 'block';
                }
            });

            function generateBankReference() {
                const timestamp = Date.now();
                const random = Math.random().toString(36).substr(2, 6).toUpperCase();
                const ref = `OHH${timestamp}${random}`;

                const display = document.getElementById('bank-reference-display');
                const input = document.getElementById('bank_reference_input');

                if (display) display.textContent = ref;
                if (input) input.value = ref;

                return ref;
            }

            // Form submission
            form.addEventListener('submit', function(e) {
                const paymentChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;

                if (paymentChoice === 'later') {
                    if (!confirm(
                            'You have chosen to pay later. Your service request will be created but will not be activated until you complete the ₦10,000 registration payment within 7 days. Do you want to continue?'
                        )) {
                        e.preventDefault();
                        return;
                    }
                }

                // Validate receipt upload for "pay now" option
                if (paymentChoice === 'now' && receiptFileInput.files.length === 0) {
                    e.preventDefault();
                    alert('Please upload your bank transfer receipt.');
                    return;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                submitBtn.disabled = true;
            });

            // Initialize payment method fields
            paymentMethod.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
