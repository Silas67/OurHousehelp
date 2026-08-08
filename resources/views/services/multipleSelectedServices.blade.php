@extends('layouts.main')
@section('multipleSelectedServices')
    <style>
        .progress-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .progress-bar {
            flex-grow: 1;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-right: 10px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background-color: #479c18;
            width: 0;
            transition: width 0.3s ease;
        }

        .progress-label {
            font-weight: bold;
            color: #479c18;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        button:disabled {
            cursor: not-allowed;
            background-color: #ccc;
        }

        .service-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .service-item:hover {
            border-color: #479c18;
            background-color: #f8f9fa;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
        }

        #order-summary {
            display: none;
            margin-top: 30px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .payment-method-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .card-option {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .card-option:hover {
            border-color: #479c18;
            background-color: #f8f9fa;
        }

        .card-option .form-check-input:checked~.form-check-label {
            color: #479c18;
            font-weight: bold;
        }

        .sr-notification {
            position: fixed;
            right: 16px;
            bottom: 16px;
            z-index: 2000;
            padding: 12px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .bank-details {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #479c18;
        }

        .payment-pending-alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .reason-item {
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .reason-item:hover {
            background-color: #f8f9fa;
            border-left-color: #479c18;
        }

        .reason-item h6 {
            color: #2c5530;
            font-weight: 600;
        }

        .reason-item .badge {
            font-size: 0.8rem;
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-xl {
            max-width: 1200px;
        }

        .modal-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
    </style>

    <section class="my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('service_requests.store') }}" method="post" enctype="multipart/form-data"
                    class="p-4 bg-light rounded shadow" id="service-request-form">
                    @csrf
                    <h3 class="text-center mb-4">Please Select Service</h3>
                    <div class="text-center mb-4">
                        <i class="fas fa-list fa-3x text-success"></i>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" id="service-charge" value="{{ $serviceCharge ?? 10000 }}">
                    <input type="hidden" id="extra-service-cost" value="{{ $extraServiceCost ?? 12000 }}">

                    <!-- Progress Bar -->
                    <div class="progress-container text-center d-flex flex-column align-items-center">
                        <div class="progress-bar w-100 mb-2">
                            <div class="progress" id="progress"></div>
                        </div>
                        <div class="progress-label" id="progress-label">Step 1 of 7</div>
                    </div>

                    <!-- Step 1: Services Selection -->
                    <div class="form-step active" id="step-1">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">1</span> Select Services
                            </legend>
                            <hr>
                            <div id="services-container" class="mb-3">
                                @foreach ($allServices as $service)
                                    <div class="service-item">
                                        <input type="checkbox" id="service{{ $service->id }}" name="serviceName[]"
                                            value="{{ $service->id }}" class="form-check-input service-checkbox"
                                            data-cost="{{ $service->base_cost ?? '0.00' }}"
                                            data-type="{{ $service->service_type ?? '' }}"
                                            data-number-of-apartment="{{ $service->requires_apartment ? 'true' : 'false' }}"
                                            {{ in_array($service->id, old('serviceName', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service{{ $service->id }}">
                                            <i class="{{ $service->icon_class ?? 'fas fa-cogs' }} me-2"></i>
                                            {{ $service->service_name }}
                                        </label>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#serviceModal-{{ $service->id }}">
                                            View Terms
                                        </button>
                                    </div>
                                    <!-- Modal for Service Details -->
                                    <div class="modal fade" id="serviceModal-{{ $service->id }}" tabindex="-1"
                                        aria-labelledby="serviceModalLabel-{{ $service->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="serviceModalLabel-{{ $service->id }}">
                                                        {{ ucfirst($service->service_name) }} - Notice of Agreement
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {!! $service->dos_and_donts ?? 'No additional details available for this service.' !!}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback" id="service-error" style="display:none;">
                                Please select at least one service
                            </div>
                        </fieldset>
                    </div>

                    <!-- Step 2: Apartment Type -->
                    <div class="form-step" id="step-2">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">2</span> Apartment Type
                            </legend>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="apartment_type" class="form-label">Select Apartment Type</label>
                                    <select class="form-select" id="apartment_type" name="apartment_type">
                                        <option value="">Choose an apartment type...</option>
                                        @foreach ($apartments as $apartment)
                                            <option value="{{ $apartment->id }}" data-cost="{{ $apartment->cost }}"
                                                {{ old('apartment_type') == $apartment->id ? 'selected' : '' }}>
                                                {{ $apartment->name }} - N{{ number_format($apartment->cost, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="apartment-error" style="display:none;">
                                        Please select an apartment type for PRS services
                                    </div>
                                    <div id="apartment-help-text"></div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Step 3: Package Selection -->
                    <div class="form-step" id="step-3">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">3</span>
                                Select a Service Package <small class="text-muted">(e.g., 2x per week)</small>
                            </legend>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label">Package <span class="text-danger">*</span></label>
                                <select id="package" name="package" class="form-select" required>
                                    <option value="">-- Select a Package --</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" data-cost="{{ $package->cost }}"
                                            data-days="{{ $package->days_per_week }}"
                                            data-percentage="{{ $package->percentage }}">
                                            {{ $package->name }} - {{ $package->days_per_week }} time(s) each week
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a service package.</div>
                                <small class="form-text text-muted">
                                    <b class="text-danger">About Packages:</b><br>
                                    Choose how many days per week you'd like us to provide service.
                                </small>
                            </div>

                            <input type="hidden" name="days_per_week" id="days_per_week"
                                value="{{ old('days_per_week') }}">
                            <input type="hidden" name="percentage" id="percentage" value="{{ old('percentage') }}">
                        </fieldset>
                    </div>

                    <!-- Step 4: Service Days -->
                    <div class="form-step" id="step-4">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">4</span> Service Days
                            </legend>
                            <hr>
                            <div class="row">
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="service_days[]"
                                                id="day_{{ $day }}" value="{{ $day }}"
                                                {{ in_array($day, old('service_days', [])) ? 'checked' : '' }}>
                                            <label for="day_{{ $day }}"
                                                class="form-check-label">{{ $day }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback" id="days-error" style="display:none;">
                                Please select at least one service day
                            </div>
                        </fieldset>
                    </div>

                    <!-- Step 5: Personal Information -->
                    <div class="form-step" id="step-5">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">5</span> Personal Information
                            </legend>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name', Auth::user()->name ?? '') }}">
                                    <div class="invalid-feedback">Please enter your first name</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" required
                                        value="{{ old('last_name', Auth::user()->last_name ?? '') }}">
                                    <div class="invalid-feedback">Please enter your last name</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ old('email', Auth::user()->email ?? '') }}">
                                    <div class="invalid-feedback">Please enter a valid email</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" required pattern="\d{11}"
                                        title="11 digit phone number"
                                        value="{{ old('phone', Auth::user()->phone ?? '') }}">
                                    <div class="invalid-feedback">Please enter a valid 11-digit phone number</div>
                                </div>
                            </div>
                            <div class="mb-3" hidden>
                                <label class="form-label">On Behalf Of Someone? <span class="text-danger">*</span></label>
                                <select name="on_behalf" class="form-select">
                                    <option value="no">Select Option</option>
                                    <option value="yes" {{ old('on_behalf') == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ old('on_behalf') == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="dob_month" class="form-select">
                                                    <option value="">Month</option>
                                                    @foreach (range(1, 12) as $month)
                                                        <option value="{{ $month }}"
                                                            {{ old('dob_month', Auth::user()->dob_month ?? '') == $month ? 'selected' : '' }}>
                                                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="dob_day" class="form-select">
                                                    <option value="">Day</option>
                                                    @foreach (range(1, 31) as $day)
                                                        <option value="{{ $day }}"
                                                            {{ old('dob_day', Auth::user()->dob_day ?? '') == $day ? 'selected' : '' }}>
                                                            {{ $day }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-select" required>
                                            <option value="">Select Gender</option>
                                            <option value="male"
                                                {{ old('gender', Auth::user()->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                Male</option>
                                            <option value="female"
                                                {{ old('gender', Auth::user()->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female</option>
                                            {{-- <option value="no_preference"
                                                {{ old('gender', Auth::user()->gender ?? '') == 'no_preference' ? 'selected' : '' }}>
                                                No Preference</option> --}}
                                        </select>
                                        <div class="invalid-feedback">Please select a gender</div>
                                    </div>
                                </div>
                            </div>


                            <legend class="h5">Contact Information</legend>
                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <select name="country_of_residence" class="form-select" required>
                                        <option value="">Select Country</option>
                                        @include('partials._countries')
                                    </select>
                                    <div class="invalid-feedback">Please select your country</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select name="state_of_residence" class="form-select" required>
                                        <option value="">Select State</option>
                                        @include('partials._states')
                                    </select>
                                    <div class="invalid-feedback">Please select your state</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" required
                                    value="{{ old('address', Auth::user()->address ?? '') }}">
                                <div class="invalid-feedback">Please enter your address</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Landmark</label>
                                <input type="text" name="landmark" class="form-control"
                                    value="{{ old('landmark', Auth::user()->landmark ?? '') }}">
                            </div>
                        </fieldset>
                    </div>

                    <!-- Step 6: Registration Fee Payment -->
                    <div class="form-step" id="step-6">
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">6</span> Registration Fee Payment
                            </legend>
                            <hr>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Important:</strong> A registration fee of <strong>₦10,000.00</strong> is required to
                                complete your service registration. You must complete this payment to see your service
                                request summary.
                            </div>

                            <!-- Quick Reasons Summary with Modal Trigger -->
                            <div class="card mb-4">
                                <div class="card-header text-white d-flex justify-content-between align-items-center"
                                    style="background: #479c18">
                                    <div>
                                        <h5 class="mb-0">
                                            <i class="fas fa-crown me-2"></i>Why Register With Us?
                                        </h5>
                                        <small class="opacity-75">₦10,000 Yearly Membership = Complete Peace of
                                            Mind</small>
                                    </div>
                                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#reasonsModal">
                                        <i class="fas fa-list me-1"></i> View All 10 Benefits
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-3">
                                            <div class="text-success">
                                                <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                                <h6>Verified Staff</h6>
                                                <small class="text-muted">Background checked & guaranteed</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-success">
                                                <i class="fas fa-sync-alt fa-2x mb-2"></i>
                                                <h6>Free Replacements</h6>
                                                <small class="text-muted">No extra cost if not satisfied</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-success">
                                                <i class="fas fa-users fa-2x mb-2"></i>
                                                <h6>Backup Support</h6>
                                                <small class="text-muted">3 free staff changes per year</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="text-success">
                                                <i class="fas fa-headset fa-2x mb-2"></i>
                                                <h6>Priority Support</h6>
                                                <small class="text-muted">24/7 emergency response</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Payment Method <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4 mb-2" hidden>
                                        <div class="form-check card-option">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="payment_card" value="card" checked>
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
                                    <div class="col-md-4 mb-2" hidden>
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

                            <div id="payment-details-container">
                                <!-- Bank transfer details (default visible) -->
                                <div id="bank_transfer-payment-details" class="payment-method-details">
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-clock me-2"></i>Bank Transfer - Requires Admin Approval</h6>
                                        <p>Please transfer the registration fee of <strong>₦10,000.00</strong> to the
                                            following account details:</p>

                                        <div class="bank-details mt-3">
                                            <p class="mb-1"><strong>Bank:</strong> GT Bank</p>
                                            <p class="mb-1"><strong>Account Name:</strong> Pik and Crest Dynamic Concepts
                                                LTD</p>
                                            <p class="mb-1"><strong>Account Number:</strong> 0051480410</p>
                                        </div>

                                        <div class="mt-3">
                                            <p class="mb-1"><strong>Important Notes:</strong></p>
                                            <ul class="small">
                                                <li>Use this reference: <strong
                                                        id="bank-reference-display">REF_{{ strtoupper(Str::random(8)) }}</strong>
                                                </li>
                                                <li>After payment, upload your transaction receipt below</li>
                                                <li>Your registration will be activated within 24 hours of payment
                                                    confirmation by admin</li>
                                                <li>You will NOT see the service summary until admin approves your payment
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Bank Transfer Reference <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_reference"
                                                id="bank_reference_input" readonly required>
                                            <small class="form-text text-muted">This is your unique payment
                                                reference</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Transfer Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="transfer_date"
                                                max="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Transaction Receipt <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="bank_receipt"
                                            accept=".jpg,.jpeg,.png,.pdf" required>
                                        <small class="form-text text-muted">Upload a clear image or PDF of your bank
                                            transfer receipt</small>
                                    </div>
                                </div>

                                <!-- Other payment methods (hidden) -->
                                <div id="card-payment-details" class="payment-method-details" style="display: none;">
                                    <!-- Card payment form content -->
                                </div>

                                <div id="wallet-payment-details" class="payment-method-details" style="display: none;">
                                    <!-- Wallet payment form content -->
                                </div>
                            </div>

                            <!-- Payment Terms -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="payment_terms" id="payment_terms"
                                    required>
                                <label class="form-check-label" for="payment_terms">
                                    I understand that the registration fee of ₦10,000.00 is mandatory and non-refundable.
                                    I must complete this payment to proceed with my service request.
                                </label>
                                <div class="invalid-feedback">You must accept the payment terms to continue</div>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Step 7: Order Summary & Confirmation -->
                    <div class="form-step" id="step-7">
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Registration fee of ₦10,000.00 submitted successfully!</strong> Your information has
                            been received and is currently under verification. Thank you for your submission.
                        </div>
                        <fieldset class="mb-4">
                            <legend class="h5">
                                <span class="badge me-2" style="background: #479c18">7</span> Order Summary & Confirmation
                            </legend>
                            <hr>

                            <!-- Payment Status Alert -->
                            <div id="payment-status-alert"></div>

                            <!-- Order Summary -->
                            <div class="card mb-4">
                                <div class="card-header text-white" style="background: #479c18">
                                    <h5 class="mb-0">Service Request Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div id="order-summary-content">
                                        <!-- This will be populated by JavaScript after payment -->
                                        <div class="text-center py-4">
                                            <i class="fas fa-lock fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Complete your registration fee payment to view the
                                                service request summary</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ route('t&c') }}" target="_blank">Terms and
                                        Conditions</a>.
                                </label>
                                <div class="invalid-feedback">You must accept the terms and conditions</div>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="nav-buttons mt-4">
                        <button type="button" class="btn btn-secondary" id="prev-btn" disabled>
                            <i class="fas fa-arrow-left text-white"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" id="next-btn">
                            Next <i class="fas fa-arrow-right text-white"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="submit-btn" style="display:none;">
                            <i class="fas fa-check text-white"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Reasons to Register Modal -->
    <div class="modal fade" id="reasonsModal" tabindex="-1" aria-labelledby="reasonsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: #479c18">
                    <h5 class="modal-title" id="reasonsModalLabel">
                        <i class="fas fa-star me-2"></i>10 Strong Reasons to Register With Our House Help Services
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Registration Fee:</strong> ₦10,000.00 per year |
                        <strong>Benefits:</strong> Complete peace of mind and professional home support
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">1</span>
                                    <div>
                                        <h6 class="mb-1">Staff Verification & Background Checks</h6>
                                        <p class="small mb-0 text-muted">We do full identity verification and require two
                                            guarantors before assigning any staff to you. So you can relax knowing your home
                                            is secure.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">2</span>
                                    <div>
                                        <h6 class="mb-1">Verified House-Help & Artisan Database</h6>
                                        <p class="small mb-0 text-muted">Your membership gives you full access to our
                                            constantly updated database of house helps, nannies, cooks, and domestic staff
                                            sourced through our nationwide recruitment system.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">3</span>
                                    <div>
                                        <h6 class="mb-1">Choose Live-In or Live-Out Staff</h6>
                                        <p class="small mb-0 text-muted">You are free to select the exact type of house
                                            help you need — live-in, Live-Out, Semi-Live Out or Daily service — based on
                                            your lifestyle and personal comfort.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">4</span>
                                    <div>
                                        <h6 class="mb-1">Fast Staff Assignment</h6>
                                        <p class="small mb-0 text-muted">Within 24 hours to 96 hours we assign a suitable
                                            and qualified staff close to your location — fast and stress-free.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">5</span>
                                    <div>
                                        <h6 class="mb-1">Free Staff Replacement</h6>
                                        <p class="small mb-0 text-muted">If your assigned staff is not delivering value,
                                            you have the right to request a replacement at no extra charge.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">6</span>
                                    <div>
                                        <h6 class="mb-1">Backup Support Staff</h6>
                                        <p class="small mb-0 text-muted">If your primary staff is unavailable, we send a
                                            backup assistant to step in — ensuring your home remains organized without
                                            interruption. You have up to 3 changes within a year.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">7</span>
                                    <div>
                                        <h6 class="mb-1">Free Monthly Deep Cleaning</h6>
                                        <p class="small mb-0 text-muted">Members who choose cleaning receive one
                                            professional deep cleaning monthly — worth more than the membership itself.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">8</span>
                                    <div>
                                        <h6 class="mb-1">Priority Customer Service</h6>
                                        <p class="small mb-0 text-muted">Registered members receive faster support,
                                            priority replacements, and rapid response to domestic emergencies.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">9</span>
                                    <div>
                                        <h6 class="mb-1">Peace of Mind & Professional Handling</h6>
                                        <p class="small mb-0 text-muted">We manage the staff, their performance,
                                            discipline, welfare, and accountability — you only enjoy the service.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="reason-item mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-success me-3 mt-1">10</span>
                                    <div>
                                        <h6 class="mb-1">House Help Family Network</h6>
                                        <p class="small mb-0 text-muted">Access to discounts, domestic care education, home
                                            support consults, and member-only programs. You are a valued member of a
                                            structured home-support ecosystem.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Value Proposition -->
                    <div class="alert alert-success mt-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-gem fa-2x me-3 text-success"></i>
                            <div>
                                <h6 class="mb-1">For just ₦10,000 per year, you are buying:</h6>
                                <p class="mb-1"><strong>Safety, Convenience, Reliability, Backup Support, Clean,
                                        Organized Home Life</strong></p>
                                <p class="mb-0 small">Zero stress in managing staff. This is not a fee — it is peace of
                                    mind.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>I Understand - Continue to Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const form = document.getElementById('service-request-form');
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('progress');
            const progressLabel = document.getElementById('progress-label');
            const packageSelect = document.getElementById('package');
            const step2 = document.getElementById('step-2');
            const apartmentSelect = document.getElementById('apartment_type');
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const orderSummaryContent = document.getElementById('order-summary-content');
            const paymentStatusAlert = document.getElementById('payment-status-alert');

            // Payment elements
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const bankReferenceDisplay = document.getElementById('bank-reference-display');
            const bankReferenceInput = document.getElementById('bank_reference_input');

            // Static Registration Fee
            const REGISTRATION_FEE = 10000;

            // Dynamic pricing from hidden inputs
            const SERVICE_CHARGE = parseFloat(document.getElementById('service-charge')?.value) || 10000;
            const EXTRA_SERVICE_COST = parseFloat(document.getElementById('extra-service-cost')?.value) || 12000;

            // State Management
            let currentStep = 0;
            let hasPRS = false;
            const totalSteps = 7;
            let paymentCompleted = false;

            // Service day checkboxes collection
            const serviceDayCheckboxes = Array.from(document.querySelectorAll('input[name="service_days[]"]'));

            // Generate unique bank reference
            function generateBankReference() {
                const ref = 'REF_' + Math.random().toString(36).substr(2, 8).toUpperCase();
                if (bankReferenceDisplay) bankReferenceDisplay.textContent = ref;
                if (bankReferenceInput) bankReferenceInput.value = ref;
                return ref;
            }

            initializeForm();

            function initializeForm() {
                updateProgress();
                updateCosts();
                setupEventListeners();
                const initialDays = parseInt(packageSelect?.selectedOptions[0]?.dataset.days) || 0;
                setServiceDayLimit(initialDays);
                updateStep2Requirement();
                setupPaymentMethodListeners();

                // Generate bank reference on page load
                generateBankReference();
            }

            function setupEventListeners() {
                // Service checkbox changes
                serviceCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const serviceItem = this.closest('.service-item');
                        serviceItem?.classList.toggle('selected', this.checked);
                        checkPRSSelection();
                        validateServices();
                        updateCosts();
                        updateStep2Requirement();
                    });
                });

                // Apartment type changes
                apartmentSelect?.addEventListener('change', function() {
                    updateCosts();
                    clearApartmentError();
                });

                // Package selection changes
                packageSelect?.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const daysAllowed = parseInt(selectedOption.dataset.days) || 0;
                    document.getElementById('days_per_week').value = daysAllowed;
                    document.getElementById('percentage').value = selectedOption.dataset.percentage || 0;
                    setServiceDayLimit(daysAllowed);
                    updateCosts();
                });

                // Service day checkboxes
                serviceDayCheckboxes.forEach(cb => {
                    cb.addEventListener('change', onServiceDayChange);
                });

                // Navigation buttons
                nextBtn.addEventListener('click', goToNextStep);
                prevBtn.addEventListener('click', goToPrevStep);

                // Form submission validation
                form.addEventListener('submit', function(e) {
                    if (!validateAllSteps()) {
                        e.preventDefault();
                        showNotification('Please fill in all required fields before submitting.', 'error');
                    } else if (!paymentCompleted && currentStep === 6) {
                        e.preventDefault();
                        showNotification(
                            'Please complete the registration fee payment to submit your request.',
                            'error');
                    }
                });
            }

            function setupPaymentMethodListeners() {
                // Payment method selection
                paymentMethodRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Hide all payment method details
                        document.querySelectorAll('.payment-method-details').forEach(detail => {
                            detail.style.display = 'none';
                        });

                        const selectedMethod = this.value;
                        const detailsElement = document.getElementById(
                            `${selectedMethod}-payment-details`);
                        if (detailsElement) {
                            detailsElement.style.display = 'block';
                        }

                        // Regenerate bank reference when bank transfer is selected
                        if (selectedMethod === 'bank_transfer') {
                            generateBankReference();
                        }
                    });
                });

                // Initialize with bank transfer details visible
                document.getElementById('bank_transfer-payment-details').style.display = 'block';
            }

            function updateCosts() {
                const selectedServices = Array.from(serviceCheckboxes).filter(chk => chk.checked);

                if (selectedServices.length === 0) {
                    return;
                }

                const calculation = calculateCosts(selectedServices);

                // Only update summary if payment is completed
                if (paymentCompleted) {
                    updateOrderSummary(calculation);
                }
            }

            function calculateCosts(selectedServices) {
                const effectiveCosts = {};
                let apartmentCost = 0;

                if (hasPRS && apartmentSelect?.value) {
                    const selectedOption = apartmentSelect.options[apartmentSelect.selectedIndex];
                    apartmentCost = parseFloat(selectedOption.dataset.cost) || 0;
                }

                // Build effective costs
                selectedServices.forEach(service => {
                    const serviceId = service.value;
                    const serviceType = service.dataset.type;
                    const baseCost = parseFloat(service.dataset.cost) || 0;

                    if (serviceType === 'PRS') {
                        effectiveCosts[serviceId] = apartmentCost || baseCost;
                    } else {
                        effectiveCosts[serviceId] = baseCost;
                    }
                });

                // Determine main (base) service - highest cost
                const serviceIds = Object.keys(effectiveCosts);
                let mainServiceId = serviceIds[0];
                let highestCost = effectiveCosts[mainServiceId];

                for (let i = 1; i < serviceIds.length; i++) {
                    const serviceId = serviceIds[i];
                    const cost = effectiveCosts[serviceId];
                    if (cost > highestCost) {
                        highestCost = cost;
                        mainServiceId = serviceId;
                    }
                }

                const baseCost = highestCost;

                let extraContributionTotal = 0.0;

                selectedServices.forEach(service => {
                    if (service.value === mainServiceId) {
                        return; // Skip base service
                    }

                    const serviceType = service.dataset.type;
                    const serviceBaseCost = parseFloat(service.dataset.cost) || 0;

                    console.log(`Processing extra service: ${serviceType}, Base Cost: ${serviceBaseCost}`);

                    switch (serviceType) {
                        case 'PRS':
                            const prsFullCost = apartmentCost || serviceBaseCost;
                            extraContributionTotal += prsFullCost / 2;
                            console.log(`PRS extra: ${prsFullCost / 2}`);
                            break;

                        case 'PMS':
                            extraContributionTotal += serviceBaseCost / 2;
                            console.log(`PMS extra: ${serviceBaseCost / 2}`);
                            break;

                        case 'PSS':
                            extraContributionTotal += EXTRA_SERVICE_COST;
                            console.log(`PSS extra: ${EXTRA_SERVICE_COST}`);
                            break;

                        default:
                            extraContributionTotal += EXTRA_SERVICE_COST;
                            console.log(`Default extra: ${EXTRA_SERVICE_COST}`);
                            break;
                    }
                });

                console.log(`Base Cost: ${baseCost}`);
                console.log(`Extra Contribution: ${extraContributionTotal}`);
                console.log(`EXTRA_SERVICE_COST: ${EXTRA_SERVICE_COST}`);

                const selectedPackage = packageSelect.options[packageSelect.selectedIndex];
                const packageCost = parseFloat(selectedPackage?.dataset.cost || 0);

                const totalServiceCost = baseCost + packageCost + extraContributionTotal;
                const grandTotal = totalServiceCost + SERVICE_CHARGE;

                console.log(`Total Service Cost: ${totalServiceCost}`);
                console.log(`Grand Total: ${grandTotal}`);

                return {
                    baseCost,
                    packageCost,
                    extraServiceCost: extraContributionTotal,
                    apartmentCost,
                    totalServiceCost,
                    grandTotal,
                    mainServiceId,
                    selectedServices: selectedServices.map(s => ({
                        id: s.value,
                        name: s.parentElement.querySelector('label').textContent.trim(),
                        cost: effectiveCosts[s.value],
                        type: s.dataset.type
                    }))
                };
            }

            function updateOrderSummary(calculation) {
                const selectedDays = Array.from(serviceDayCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                // Debug information
                console.log('=== CALCULATION DEBUG ===');
                console.log('Selected Services:', calculation.selectedServices);
                console.log('Base Cost:', calculation.baseCost);
                console.log('Extra Service Cost:', calculation.extraServiceCost);
                console.log('Package Cost:', calculation.packageCost);
                console.log('Total Service Cost:', calculation.totalServiceCost);
                console.log('Service Charge:', SERVICE_CHARGE);
                console.log('Grand Total:', calculation.grandTotal);
                console.log('Main Service ID:', calculation.mainServiceId);
                console.log('========================');

                orderSummaryContent.innerHTML = `
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Selected Services</th>
                            <td>
                                ${calculation.selectedServices.map(service => 
                                    `${service.name} (${service.type})`
                                ).join('<br>')}
                            </td>
                        </tr>
                        <tr hidden>
                            <th>Main Service</th>
                            <td>${calculation.selectedServices.find(s => s.id === calculation.mainServiceId)?.name || 'N/A'}</td>
                        </tr>
                        <tr>
                            <th>Service Days</th>
                            <td>${selectedDays.join(', ')}</td>
                        </tr>
                        <tr>
                            <th>Package</th>
                            <td>${packageSelect.options[packageSelect.selectedIndex]?.textContent || 'Not selected'}</td>
                        </tr>
                        <tr hidden>
                            <th>Base Service Cost</th>
                            <td>${formatCurrency(calculation.baseCost)}</td>
                        </tr>
                        <tr hidden>
                            <th>Extra Services Cost</th>
                            <td>${formatCurrency(calculation.extraServiceCost)}</td>
                        </tr>
                        <tr hidden>
                            <th>Package Cost</th>
                            <td>${formatCurrency(calculation.packageCost)}</td>
                        </tr>
                        <tr>
                            <th>Total Service Cost</th>
                            <td>${formatCurrency(calculation.totalServiceCost)}</td>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <td>${formatCurrency(SERVICE_CHARGE)}</td>
                        </tr>
                        <tr class="table-success">
                            <th><strong>Grand Total</strong></th>
                            <td><strong>${formatCurrency(calculation.grandTotal)}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-info mt-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Note:</strong> This total includes your selected services and service charge. 
                        A separate registration fee of ${formatCurrency(REGISTRATION_FEE)} is required to activate your service.
                    </small>
                </div>
            `;
            }

            function formatCurrency(amount) {
                if (!amount || isNaN(amount)) amount = 0;
                return amount.toLocaleString('en-NG', {
                    style: 'currency',
                    currency: 'NGN',
                    minimumFractionDigits: 2
                });
            }

            function updateProgress() {
                const progressPercentage = ((currentStep + 1) / totalSteps) * 100;
                progressBar.style.width = `${progressPercentage}%`;
                progressLabel.textContent = `Step ${currentStep + 1} of ${totalSteps}`;

                prevBtn.disabled = currentStep === 0;
                nextBtn.style.display = currentStep === totalSteps - 1 ? 'none' : 'inline-block';
                submitBtn.style.display = currentStep === totalSteps - 1 ? 'inline-block' : 'none';

                // Only show submit button if payment is completed
                if (currentStep === totalSteps - 1) {
                    if (paymentCompleted) {
                        submitBtn.style.display = 'inline-block';
                        nextBtn.style.display = 'none';
                    } else {
                        submitBtn.style.display = 'none';
                        nextBtn.style.display = 'inline-block';
                    }
                }
            }

            function goToNextStep() {
                if (!validateStep(currentStep)) return;

                // Special handling for step 5 (moving to step 6 - payment)
                if (currentStep === 4) {
                    steps[currentStep].classList.remove('active');
                    currentStep++;
                    steps[currentStep].classList.add('active');
                    updateProgress();
                    scrollToFormTop();

                    // Show the reasons modal automatically when entering step 6
                    const reasonsModal = new bootstrap.Modal(document.getElementById('reasonsModal'));
                    reasonsModal.show();
                    return;
                }

                // Special handling for step 6 (payment)
                if (currentStep === 5) {
                    if (!validatePaymentStep()) {
                        return;
                    }

                    // Simulate payment processing
                    processPayment().then(success => {
                        if (success) {
                            paymentCompleted = true;
                            steps[currentStep].classList.remove('active');
                            currentStep++;
                            steps[currentStep].classList.add('active');
                            updateProgress();
                            updateCosts(); // This will now update the summary
                            scrollToFormTop();
                        }
                    });
                    return;
                }

                steps[currentStep].classList.remove('active');

                if (currentStep === 0 && !hasPRS) {
                    currentStep = 2;
                } else {
                    currentStep++;
                }

                steps[currentStep].classList.add('active');
                updateProgress();
                scrollToFormTop();
            }

            function goToPrevStep() {
                steps[currentStep].classList.remove('active');

                if (currentStep === 2 && !hasPRS) {
                    currentStep = 0;
                } else {
                    currentStep--;
                }

                steps[currentStep].classList.add('active');
                updateProgress();
                scrollToFormTop();
            }

            function validatePaymentStep() {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;

                if (!paymentMethod) {
                    showNotification('Please select a payment method.', 'error');
                    return false;
                }

                if (!document.getElementById('payment_terms').checked) {
                    showNotification('You must accept the payment terms.', 'error');
                    return false;
                }

                // Additional validation based on payment method
                switch (paymentMethod) {
                    case 'card':
                        if (!validateCardDetails()) return false;
                        break;
                    case 'bank_transfer':
                        if (!validateBankTransferDetails()) return false;
                        break;
                    case 'wallet':
                        if (!validateWalletDetails()) return false;
                        break;
                }

                return true;
            }

            function validateCardDetails() {
                const cardNumber = document.querySelector('input[name="card_number"]')?.value;
                const cardExpiry = document.querySelector('input[name="card_expiry"]')?.value;
                const cardCvv = document.querySelector('input[name="card_cvv"]')?.value;
                const cardholderName = document.querySelector('input[name="cardholder_name"]')?.value;

                if (!cardNumber || !cardExpiry || !cardCvv || !cardholderName) {
                    showNotification('Please fill in all card details.', 'error');
                    return false;
                }

                // Basic card validation
                if (!/^\d{13,19}$/.test(cardNumber.replace(/\s/g, ''))) {
                    showNotification('Please enter a valid card number.', 'error');
                    return false;
                }

                if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(cardExpiry)) {
                    showNotification('Please enter a valid expiry date (MM/YY).', 'error');
                    return false;
                }

                if (!/^\d{3,4}$/.test(cardCvv)) {
                    showNotification('Please enter a valid CVV.', 'error');
                    return false;
                }

                return true;
            }

            function validateBankTransferDetails() {
                const transferDate = document.querySelector('input[name="transfer_date"]').value;
                const bankReceipt = document.querySelector('input[name="bank_receipt"]').files[0];

                if (!transferDate) {
                    showNotification('Please select the transfer date.', 'error');
                    return false;
                }

                if (!bankReceipt) {
                    showNotification('Please upload your bank transfer receipt.', 'error');
                    return false;
                }

                // Check if transfer date is not in the future
                const selectedDate = new Date(transferDate);
                const today = new Date();
                if (selectedDate > today) {
                    showNotification('Transfer date cannot be in the future.', 'error');
                    return false;
                }

                return true;
            }

            function validateWalletDetails() {
                const walletPin = document.querySelector('input[name="wallet_pin"]')?.value;

                if (!walletPin) {
                    showNotification('Please enter your wallet PIN.', 'error');
                    return false;
                }

                if (!/^\d{6}$/.test(walletPin)) {
                    showNotification('Please enter a valid 6-digit wallet PIN.', 'error');
                    return false;
                }

                // Check wallet balance (you would typically make an AJAX call here)
                const walletBalance = {{ Auth::user()->wallet_balance ?? 0 }};
                if (walletBalance < 10000) {
                    showNotification('Insufficient wallet balance. Please top up or choose another payment method.',
                        'error');
                    return false;
                }

                return true;
            }

            function processPayment() {
                return new Promise((resolve) => {
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')
                        .value;

                    // Show processing message
                    showNotification('Processing payment...', 'info');

                    // Simulate API call delay
                    setTimeout(() => {
                        // In real implementation, you would make an actual API call to your payment gateway
                        // For demo purposes, we'll assume all payments are successful

                        let message = '';
                        switch (paymentMethod) {
                            case 'card':
                                message = 'Card payment processed successfully!';
                                break;
                            case 'bank_transfer':
                                message =
                                    'Bank transfer details submitted. Waiting for admin approval.';
                                break;
                            case 'wallet':
                                message = 'Wallet payment processed successfully!';
                                break;
                        }

                        showNotification(message, 'success');
                        resolve(true);
                    }, 2000);
                });
            }

            function scrollToFormTop() {
                window.scrollTo({
                    top: form.offsetTop - 50,
                    behavior: 'smooth'
                });
            }

            function showNotification(message, type = 'info') {
                const existing = document.getElementById('sr-notification');
                if (existing) existing.remove();

                const div = document.createElement('div');
                div.id = 'sr-notification';
                div.className = 'sr-notification p-2 rounded shadow-sm';
                div.style.cssText = `
                position: fixed;
                right: 16px;
                bottom: 16px;
                z-index: 2000;
                background: ${type === 'error' ? '#f8d7da' : type === 'success' ? '#d1edff' : '#d1ecf1'};
                color: ${type === 'error' ? '#721c24' : type === 'success' ? '#155724' : '#0c5460'};
                border: 1px solid ${type === 'error' ? '#f5c6cb' : type === 'success' ? '#c3e6cb' : '#bee5eb'};
            `;
                div.textContent = message;
                document.body.appendChild(div);

                setTimeout(() => div.remove(), 3500);
            }

            function checkPRSSelection() {
                hasPRS = Array.from(serviceCheckboxes).some(cb => cb.checked && cb.dataset.type === 'PRS');
            }

            function validateServices() {
                const checkedServices = document.querySelectorAll('.service-checkbox:checked');
                const serviceError = document.getElementById('service-error');
                const isValid = checkedServices.length > 0;
                serviceError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validateServiceDays() {
                const checkedDays = document.querySelectorAll('input[name="service_days[]"]:checked');
                const daysError = document.getElementById('days-error');
                const isValid = checkedDays.length > 0;
                daysError.style.display = isValid ? 'none' : 'block';
                return isValid;
            }

            function validateApartmentSelection() {
                if (!apartmentSelect.value) {
                    apartmentSelect.classList.add('is-invalid');
                    const apartmentError = document.getElementById('apartment-error');
                    if (apartmentError) {
                        apartmentError.style.display = 'block';
                    }
                    return false;
                }
                return true;
            }

            function validateTerms() {
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    termsCheckbox.classList.add('is-invalid');
                    return false;
                }
                termsCheckbox.classList.remove('is-invalid');
                return true;
            }

            function validateRequiredFields(step) {
                const requiredFields = step.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');

                        if (field.type === 'email' && !validateEmail(field.value)) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        }

                        if (field.name === 'phone' && !validatePhone(field.value)) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                });

                return isValid;
            }

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function validatePhone(phone) {
                return /^\d{11}$/.test(phone);
            }

            function updateStep2Requirement() {
                if (hasPRS) {
                    step2.classList.add('required');
                    apartmentSelect.required = true;
                    showApartmentHelpText('Required for PRS services', 'warning');
                } else {
                    step2.classList.remove('required');
                    apartmentSelect.required = false;
                    showApartmentHelpText('This step is only required when PRS services are selected.', 'muted');
                    clearApartmentError();
                    apartmentSelect.value = "";
                }
            }

            function showApartmentHelpText(message, type) {
                let apartmentHelpText = document.getElementById('apartment-help-text');
                if (!apartmentHelpText) {
                    apartmentHelpText = document.createElement('div');
                    apartmentHelpText.id = 'apartment-help-text';
                    apartmentHelpText.className = `form-text text-${type}`;
                    apartmentSelect.parentNode.appendChild(apartmentHelpText);
                }
                apartmentHelpText.innerHTML = `<i class="fas fa-info-circle text-${type}"></i> ${message}`;
                apartmentHelpText.className = `form-text text-${type}`;
            }

            function clearApartmentError() {
                const apartmentError = document.getElementById('apartment-error');
                if (apartmentError) {
                    apartmentError.style.display = 'none';
                }
                apartmentSelect?.classList.remove('is-invalid');
            }

            function setServiceDayLimit(daysAllowed) {
                form.dataset.daysAllowed = daysAllowed;

                if (!daysAllowed || daysAllowed <= 0) {
                    serviceDayCheckboxes.forEach(cb => cb.disabled = false);
                    updateServiceDayState();
                    return;
                }

                const currentlyChecked = serviceDayCheckboxes.filter(c => c.checked);
                if (currentlyChecked.length > daysAllowed) {
                    const toUncheck = currentlyChecked.slice(daysAllowed);
                    toUncheck.forEach(cb => cb.checked = false);
                    showNotification(`Package allows only ${daysAllowed} day(s). Extra days were removed.`, 'info');
                }

                updateServiceDayState();
            }

            function onServiceDayChange() {
                const daysAllowed = parseInt(form.dataset.daysAllowed) || 0;
                if (!daysAllowed || daysAllowed <= 0) {
                    updateServiceDayState();
                    return;
                }
                const checkedCount = serviceDayCheckboxes.filter(cb => cb.checked).length;

                if (checkedCount >= daysAllowed) {
                    serviceDayCheckboxes.forEach(cb => {
                        if (!cb.checked) cb.disabled = true;
                    });
                } else {
                    serviceDayCheckboxes.forEach(cb => cb.disabled = false);
                }
                updateCosts();
            }

            function updateServiceDayState() {
                const daysAllowed = parseInt(form.dataset.daysAllowed) || 0;
                const checkedCount = serviceDayCheckboxes.filter(cb => cb.checked).length;

                if (!daysAllowed || daysAllowed <= 0) {
                    serviceDayCheckboxes.forEach(cb => cb.disabled = false);
                    return;
                }

                if (checkedCount >= daysAllowed) {
                    serviceDayCheckboxes.forEach(cb => {
                        if (!cb.checked) cb.disabled = true;
                    });
                } else {
                    serviceDayCheckboxes.forEach(cb => cb.disabled = false);
                }
            }

            function validateAllSteps() {
                for (let i = 0; i < steps.length; i++) {
                    if (!validateStep(i)) {
                        setTimeout(() => steps[i].scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        }), 100);
                        return false;
                    }
                }
                return true;
            }

            function validateStep(stepIndex) {
                const step = steps[stepIndex];
                let isValid = true;

                switch (stepIndex) {
                    case 0:
                        isValid = validateServices();
                        break;
                    case 1:
                        if (hasPRS) {
                            isValid = validateApartmentSelection();
                        }
                        break;
                    case 3:
                        isValid = validateServiceDays();
                        break;
                    case 6: // Step 7 - terms
                        isValid = validateTerms();
                        break;
                }

                if (stepIndex !== 1 && stepIndex !== 6) {
                    isValid = validateRequiredFields(step) && isValid;
                }

                return isValid;
            }
        });
    </script>
@endsection
