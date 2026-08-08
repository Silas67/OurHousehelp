@extends('layouts.main')

@section('multipleSelectedServices')
    <style>
        /* Previous CSS styles remain the same */
        .service-request-wizard {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .service-request-wizard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #479c18 0%, #28a745 100%);
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-step.active {
            display: block;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            padding: 0 20px;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 20px;
            right: 20px;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
            border-radius: 3px;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            padding: 0 10px;
        }

        .step-number {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-weight: bold;
            border: 4px solid white;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .step.active .step-number {
            background: #479c18;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(71, 156, 24, 0.3);
        }

        .step.completed .step-number {
            background: #28a745;
            color: white;
        }

        .step.completed .step-number::after {
            content: '✓';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }

        .step-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .step.active .step-label {
            color: #479c18;
            font-weight: 600;
            transform: translateY(2px);
        }

        .service-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            border-color: #479c18;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover::before {
            background: #479c18;
        }

        .service-card.selected {
            border-color: #479c18;
            background: linear-gradient(to bottom right, rgba(71, 156, 24, 0.03), rgba(71, 156, 24, 0.08));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(71, 156, 24, 0.15);
        }

        .service-card.selected::before {
            background: #479c18;
        }

        .service-card input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .service-card .select-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 24px;
            height: 24px;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .service-card.selected .select-indicator {
            background: #479c18;
            border-color: #479c18;
        }

        .service-card.selected .select-indicator::after {
            content: '✓';
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

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

        .registration-required-alert {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }

        .user-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            z-index: 10;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .guest-badge {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #212529;
        }

        .registered-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .unregistered-badge {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, #479c18 0%, #28a745 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(71, 156, 24, 0.3);
        }

        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(71, 156, 24, 0.4);
            color: white;
        }

        .btn-custom-secondary {
            background: white;
            border: 2px solid #479c18;
            color: #479c18;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom-secondary:hover {
            background: #479c18;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(71, 156, 24, 0.2);
        }

        .bank-details {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            border-left: 4px solid #479c18;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .service-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .service-icon-prs {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .service-icon-pms {
            background: linear-gradient(135deg, #007bff 0%, #00bcd4 100%);
            color: white;
        }

        .service-icon-pss {
            background: linear-gradient(135deg, #6f42c1 0%, #9c27b0 100%);
            color: white;
        }

        .service-icon-default {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .service-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .feature-badge {
            background: rgba(71, 156, 24, 0.1);
            color: #479c18;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Registration First Step */
        .registration-step {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .registration-icon {
            font-size: 80px;
            color: #479c18;
            margin-bottom: 30px;
        }

        .registration-info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #479c18;
        }

        .registration-info-box h5 {
            color: #479c18;
            margin-bottom: 20px;
        }

        .login-option {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        /* Auto-save indicator */
        .save-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #479c18;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(71, 156, 24, 0.3);
            display: none;
            z-index: 1000;
            animation: slideInUp 0.3s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(100%);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .service-request-wizard {
                padding: 25px 20px;
            }

            .step-indicator {
                padding: 0;
            }

            .step-indicator::before {
                left: 15%;
                right: 15%;
            }

            .step {
                flex: 0 0 33.333%;
                margin-bottom: 20px;
            }

            .step-label {
                font-size: 11px;
            }

            .service-card {
                padding: 20px 15px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }

        /* Notification styling */
        .service-request-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            border: none;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <!-- Auto-save indicator -->
    <div class="save-indicator" id="saveIndicator">
        <span class="loading-spinner" id="saveSpinner"></span>
        <span id="saveText">Saving your information...</span>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="service-request-wizard">
                    <!-- User Status Indicator -->
                    <div class="position-relative">
                        @auth
                            @if (auth()->user()->is_registered)
                                <span class="user-type-badge registered-badge">
                                    <i class="fas fa-check-circle me-1"></i> Registered Member
                                </span>
                            @else
                                <span class="user-type-badge unregistered-badge">
                                    <i class="fas fa-exclamation-circle me-1"></i> Not Registered
                                </span>
                            @endif
                        @else
                            <span class="user-type-badge guest-badge">
                                <i class="fas fa-user-clock me-1"></i> Guest User
                            </span>
                        @endauth
                    </div>

                    <h2 class="text-center mb-4" style="color: #479c18;">
                        <i class="fas fa-hands-helping me-2"></i> Create Service Request
                    </h2>

                    <p class="text-center text-muted mb-5">
                        Complete the following steps to request our professional services
                    </p>

                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Registration</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Services</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Apartment</div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Package</div>
                        </div>
                        <div class="step" data-step="5">
                            <div class="step-number">5</div>
                            <div class="step-label">Schedule</div>
                        </div>
                        <div class="step" data-step="6">
                            <div class="step-number">6</div>
                            <div class="step-label">Payment</div>
                        </div>
                        <div class="step" data-step="7">
                            <div class="step-number">7</div>
                            <div class="step-label">Review</div>
                        </div>
                    </div>

                    <!-- Registration Required Alert for Guests -->
                    @if (!auth()->check())
                        <div class="alert registration-required-alert mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lock fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-2">Registration Required</h5>
                                    <p class="mb-0">You need to register with us before making a service request. Your
                                        registration will be created automatically using your email and phone number.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('service_requests.store') }}" method="post" enctype="multipart/form-data"
                        id="serviceRequestForm">
                        @csrf

                        <!-- Hidden fields -->
                        <input type="hidden" name="save_and_continue" value="0" id="save_and_continue">
                        <input type="hidden" name="user_journey" value="{{ auth()->check() ? 'registered' : 'guest' }}">
                        <input type="hidden" id="service-charge" value="{{ $serviceCharge ?? 10000 }}">
                        <input type="hidden" id="extra-service-cost" value="{{ $extraServiceCost ?? 12000 }}">
                        <input type="hidden" id="proceed_to_payment" name="proceed_to_payment" value="0">
                        <input type="hidden" name="partial_registration" value="0">
                        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->id() ?? '' }}">

                        <!-- Hidden fields -->

                        <!-- Step 1: Registration (Only for guests) -->
                        @if (!auth()->check())
                            <div class="form-step active" id="step-1-content">
                                <div class="registration-step">
                                    <div class="registration-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>

                                    <h4 class="mb-4" style="color: #479c18;">
                                        <i class="fas fa-user-edit me-2"></i> Create Your Account
                                    </h4>

                                    <div class="registration-info-box">
                                        <h5>How Registration Works</h5>
                                        <div class="text-start">
                                            <p class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Your
                                                account will be created automatically using your email and phone number</p>
                                            <p class="mb-3"><i class="fas fa-key text-success me-2"></i> Your phone number
                                                will be your initial password</p>
                                            <p class="mb-3"><i class="fas fa-envelope text-success me-2"></i> You'll
                                                receive login details after registration</p>
                                            <p class="mb-0"><i class="fas fa-shield-alt text-success me-2"></i> Your
                                                information is saved automatically when you click "Next"</p>
                                        </div>
                                    </div>

                                    <p class="text-muted mb-4">
                                        Please provide your details below to create your account and continue with your
                                        service request.
                                    </p>

                                    <!-- Registration Form Fields -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required
                                                value="{{ old('name') }}" placeholder="Enter your first name">
                                            <div class="invalid-feedback">Please enter your first name.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Middle Name (Optional)</label>
                                            <input type="text" name="middle_name" class="form-control"
                                                value="{{ old('middle_name') }}" placeholder="Enter your middle name">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" required
                                                value="{{ old('last_name') }}" placeholder="Enter your last name">
                                            <div class="invalid-feedback">Please enter your last name.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" required
                                                value="{{ old('email') }}" placeholder="your.email@example.com">
                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="phone" class="form-control" required
                                                pattern="\d{11}" title="11-digit Nigerian phone number"
                                                value="{{ old('phone') }}" placeholder="08012345678">
                                            <div class="form-text">
                                                <i class="fas fa-key me-1"></i> This will be your initial password
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">-- Select Gender --</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Female
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">Please select your gender.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country of Residence <span
                                                    class="text-danger">*</span></label>
                                            <select name="country_of_residence" class="form-select" required>
                                                <option value="">-- Select Country --</option>
                                                @include('partials._countries')
                                            </select>
                                            <div class="invalid-feedback">Please select your country of residence.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">State of Residence <span
                                                    class="text-danger">*</span></label>
                                            <select name="state_of_residence" class="form-select" required>
                                                <option value="">-- Select State --</option>
                                                @include('partials._states')
                                            </select>
                                            <div class="invalid-feedback">Please select your state of residence.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">On Behalf Of Someone?</label>
                                            <select name="on_behalf" class="form-select">
                                                <option value="no" {{ old('on_behalf') == 'no' ? 'selected' : '' }}>
                                                    No, for myself</option>
                                                <option value="yes" {{ old('on_behalf') == 'yes' ? 'selected' : '' }}>
                                                    Yes, for someone else</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth (Optional)</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <select name="dob_month" class="form-select">
                                                        <option value="">Month</option>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('dob_month') == $i ? 'selected' : '' }}>
                                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select name="dob_day" class="form-select">
                                                        <option value="">Day</option>
                                                        @for ($i = 1; $i <= 31; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('dob_day') == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" required
                                            value="{{ old('address') }}" placeholder="Enter your complete address">
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Landmark (Optional)</label>
                                        <input type="text" name="landmark" class="form-control"
                                            value="{{ old('landmark') }}"
                                            placeholder="e.g., Near shopping mall, Opposite bank">
                                    </div>

                                    <!-- File uploads (Optional) -->
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Passport Photo (Optional)</label>
                                            <input type="file" name="passport" class="form-control" accept="image/*">
                                            <div class="form-text">JPG, PNG, JPEG, Max: 2MB</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ID Card (Optional)</label>
                                            <input type="file" name="id_card" class="form-control" accept="image/*">
                                            <div class="form-text">JPG, PNG, JPEG, Max: 2MB</div>
                                        </div>
                                    </div>

                                    <!-- Login Option for Registered Users -->
                                    <div class="login-option">
                                        <p class="text-muted mb-3">Already have an account?</p>
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-sign-in-alt me-2"></i> Login to Your Account
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Services Selection -->
                        <div class="form-step @if (auth()->check()) active @elseif(!auth()->check()) @endif"
                            id="step-2-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-list-check me-2"></i> Select Your Services
                            </h4>

                            <div class="alert alert-info mb-4">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Tip:</strong> You can select multiple services. The service with highest cost
                                becomes your base service.
                            </div>

                            <div class="row">
                                @foreach ($allServices as $service)
                                    @php
                                        // Determine icon wrapper class based on service type
                                        $iconClass = 'service-icon-default';
                                        if ($service->service_type === 'PRS') {
                                            $iconClass = 'service-icon-prs';
                                        } elseif ($service->service_type === 'PMS') {
                                            $iconClass = 'service-icon-pms';
                                        } elseif ($service->service_type === 'PSS') {
                                            $iconClass = 'service-icon-pss';
                                        }

                                        // Get service features if available
                                        $features = [];
                                        if ($service->requires_apartment) {
                                            $features[] = 'Apartment Required';
                                        }
                                        if ($service->is_premium ?? false) {
                                            $features[] = 'Premium';
                                        }
                                        if ($service->is_weekly ?? false) {
                                            $features[] = 'Weekly';
                                        }
                                        if ($service->is_daily ?? false) {
                                            $features[] = 'Daily';
                                        }
                                    @endphp

                                    <div class="col-md-6 mb-4">
                                        <div class="service-card" data-service-id="{{ $service->id }}">
                                            <!-- Checkbox (hidden) -->
                                            <input type="checkbox" id="service{{ $service->id }}" name="serviceName[]"
                                                value="{{ $service->id }}" class="service-checkbox"
                                                data-cost="{{ $service->base_cost }}"
                                                data-type="{{ $service->service_type }}"
                                                {{ in_array($service->id, old('serviceName', [])) ? 'checked' : '' }}>

                                            <!-- Selection indicator -->
                                            <div class="select-indicator"></div>

                                            <!-- Service icon -->
                                            <div class="{{ $iconClass }} service-icon-wrapper">
                                                <i class="{{ $service->icon_class ?? 'fas fa-cogs' }}"></i>
                                            </div>

                                            <!-- Service name and type -->
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="mb-1">{{ $service->service_name }}</h5>
                                                    <span class="badge"
                                                        style="background: {{ $service->service_type === 'PRS' ? '#28a745' : ($service->service_type === 'PMS' ? '#007bff' : '#6f42c1') }}">
                                                        {{ $service->service_type }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Service description -->
                                            <p class="text-muted mb-3">
                                                {{ $service->description ?? 'Professional service for your home needs' }}
                                            </p>

                                            <!-- Service features -->
                                            @if (!empty($features))
                                                <div class="service-features">
                                                    @foreach ($features as $feature)
                                                        <span class="feature-badge">{{ $feature }}</span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Cost and action buttons -->
                                            <div class="d-flex justify-content-between align-items-center mt-4">
                                                <div>
                                                    <span class="h4"
                                                        style="color: #479c18;">₦{{ number_format($service->base_cost, 2) }}</span>
                                                    <span class="text-muted small">/base cost</span>
                                                </div>

                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#serviceModal-{{ $service->id }}">
                                                        <i class="fas fa-info-circle me-1"></i> Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service Details Modal -->
                                    <div class="modal fade" id="serviceModal-{{ $service->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #479c18; color: white;">
                                                    <h5 class="modal-title">
                                                        <i class="{{ $service->icon_class ?? 'fas fa-cogs' }} me-2"></i>
                                                        {{ $service->service_name }} - Complete Details
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="{{ $iconClass }} service-icon-wrapper mx-auto">
                                                                <i
                                                                    class="{{ $service->icon_class ?? 'fas fa-cogs' }} fa-2x"></i>
                                                            </div>
                                                            <div class="text-center mt-3">
                                                                <span
                                                                    class="badge bg-{{ $service->service_type === 'PRS' ? 'success' : ($service->service_type === 'PMS' ? 'primary' : 'purple') }} px-3 py-2">
                                                                    {{ $service->service_type }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h5>Service Overview</h5>
                                                            <p>{{ $service->description ?? 'No description available.' }}
                                                            </p>

                                                            <h6 class="mt-4">Base Cost</h6>
                                                            <p class="h4 text-success">
                                                                ₦{{ number_format($service->base_cost, 2) }}</p>

                                                            @if ($service->dos_and_donts)
                                                                <h6 class="mt-4">Terms & Conditions</h6>
                                                                <div class="bg-light p-3 rounded">
                                                                    {!! $service->dos_and_donts !!}
                                                                </div>
                                                            @endif

                                                            @if (!empty($features))
                                                                <h6 class="mt-4">Features</h6>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($features as $feature)
                                                                        <span
                                                                            class="badge bg-info">{{ $feature }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-success"
                                                        onclick="selectService({{ $service->id }})">
                                                        <i class="fas fa-check me-2"></i> Select This Service
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="alert alert-danger mb-0 mt-4" id="services-error" style="display: none;">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Required:</strong> Please select at least one service to continue.
                            </div>

                            <!-- Continue without services option -->
                            @if (!auth()->check())
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x me-3"></i>
                                        <div>
                                            <h6>Want to just register now and add services later?</h6>
                                            <p class="mb-0">You can register now without selecting services and come back
                                                later to complete your service request.</p>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="continue-without-services">
                                                    <i class="fas fa-user-plus me-1"></i> Register without services
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>


                        <!-- Step 3: Apartment Type -->
                        <div class="form-step" id="step-3-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-building me-2"></i> Apartment Details
                            </h4>

                            <div class="alert alert-warning mb-4" id="apartment-alert" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Required:</strong> Please select an apartment type for PRS services.
                            </div>

                            <div class="alert alert-info mb-4" id="apartment-info" style="display: none;">
                                <i class="fas fa-info-circle me-2"></i>
                                You have selected PRS services. Please choose your apartment type.
                            </div>

                            <div class="alert alert-success mb-4" id="apartment-not-needed" style="display: block;">
                                <i class="fas fa-check-circle me-2"></i>
                                No apartment selection needed as you haven't selected PRS services.
                            </div>

                            <div class="row" id="apartment-selection" style="display: none;">
                                <div class="col-md-8 mx-auto">
                                    <div class="card">
                                        <div class="card-header" style="background: #f8f9fa;">
                                            <h5 class="mb-0">Select Apartment Type</h5>
                                        </div>
                                        <div class="card-body">
                                            <label class="form-label">Choose your apartment type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="apartment_type"
                                                name="apartment_type">
                                                <option value="">-- Choose Apartment Type --</option>
                                                @foreach ($apartments as $apartment)
                                                    <option value="{{ $apartment->id }}"
                                                        data-cost="{{ $apartment->cost }}"
                                                        {{ old('apartment_type') == $apartment->id ? 'selected' : '' }}>
                                                        {{ $apartment->name }} - ₦{{ number_format($apartment->cost, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                The apartment cost will be included in your service calculation.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Package Selection -->
                        <div class="form-step" id="step-4-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-box-open me-2"></i> Select Service Package
                            </h4>

                            <div class="alert alert-info mb-4">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Choose how often you'd like our services. Packages are based on weekly frequency.
                            </div>

                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="card">
                                        <div class="card-header" style="background: #f8f9fa;">
                                            <h5 class="mb-0">Frequency Package</h5>
                                        </div>
                                        <div class="card-body">
                                            <label class="form-label">Select your preferred frequency <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="package" name="package"
                                                required>
                                                <option value="">-- Select Frequency Package --</option>
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}" data-cost="{{ $package->cost }}"
                                                        data-days="{{ $package->days_per_week }}"
                                                        data-percentage="{{ $package->percentage }}"
                                                        {{ old('package') == $package->id ? 'selected' : '' }}>
                                                        {{ $package->name }} - {{ $package->days_per_week }} time(s) per
                                                        week
                                                        @if ($package->cost > 0)
                                                            (+₦{{ number_format($package->cost, 2) }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                This determines how many days per week our team will provide service.
                                            </div>

                                            <input type="hidden" name="days_per_week" id="days_per_week"
                                                value="{{ old('days_per_week') }}">
                                            <input type="hidden" name="percentage" id="percentage"
                                                value="{{ old('percentage') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Service Days -->
                        <div class="form-step" id="step-5-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-calendar-day me-2"></i> Select Service Days
                            </h4>

                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Select the specific days for your service. You can select up to <span
                                    id="max-days-indicator">0</span> days based on your package.
                            </div>

                            <div class="row" id="days-selection">
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input service-day-checkbox" type="checkbox"
                                                name="service_days[]" id="day_{{ $day }}"
                                                value="{{ $day }}"
                                                {{ in_array($day, old('service_days', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label day-label" for="day_{{ $day }}">
                                                <i class="far fa-calendar-check me-2"></i> {{ $day }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="alert alert-warning mb-0 mt-4" id="days-error" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Required:</strong> Please select at least one service day.
                            </div>

                            <div class="alert alert-danger mb-0 mt-4" id="days-limit-error" style="display: none;">
                                <i class="fas fa-ban me-2"></i>
                                <strong>Limit Exceeded:</strong> You can only select <span id="max-days">0</span> days
                                based on your package.
                            </div>
                        </div>

                        <!-- Step 6: Registration Fee Payment -->
                        <div class="form-step" id="step-6-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-credit-card me-2"></i> Registration Fee
                            </h4>

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

                                <input type="hidden" name="proceed_to_payment" value="0">
                            @else
                                <!-- Payment Choice Section -->
                                <div class="payment-choice-section">
                                    <div class="alert alert-warning mb-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="mb-1">Registration Required</h5>
                                                <p class="mb-0">A one-time yearly registration fee of <strong
                                                        class="h5">₦10,000.00</strong> is required to activate your
                                                    service request.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="mb-4 text-center">When would you like to pay?</h5>

                                    <div class="row justify-content-center">
                                        <div class="col-md-5 mb-4">
                                            <div class="payment-option-card active" data-payment-choice="now">
                                                <div class="mb-3">
                                                    <i class="fas fa-credit-card fa-3x" style="color: #28a745;"></i>
                                                </div>
                                                <h4 class="mb-2">Pay Now</h4>
                                                <p class="text-muted mb-3">Complete registration immediately and activate
                                                    your service request.</p>
                                                <div class="mt-3">
                                                    <span class="h3 text-success">₦10,000.00</span>
                                                    <div class="text-success small">One-time yearly fee</div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="badge bg-success p-2">Recommended</div>
                                                </div>
                                                <input type="radio" name="payment_choice" value="now"
                                                    id="pay_now" checked>
                                            </div>
                                        </div>

                                        <div class="col-md-5 mb-4">
                                            <div class="payment-option-card" data-payment-choice="later">
                                                <div class="mb-3">
                                                    <i class="fas fa-clock fa-3x" style="color: #6c757d;"></i>
                                                </div>
                                                <h4 class="mb-2">Pay Later</h4>
                                                <p class="text-muted mb-3">Create service request now, complete
                                                    registration payment later.</p>
                                                <div class="mt-3">
                                                    <span class="h3 text-muted">₦10,000.00</span>
                                                    <div class="text-muted small">Pay within 7 days</div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="badge bg-warning p-2">Pending</div>
                                                </div>
                                                <input type="radio" name="payment_choice" value="later"
                                                    id="pay_later">
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
                                                            Dynamic Concepts LTD</p>
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

                                                <div class="mb-3">
                                                    <label class="form-label">Upload Receipt <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" name="bank_receipt"
                                                        accept=".jpg,.jpeg,.png,.pdf" required>
                                                    <div class="form-text">Upload a clear image or PDF of your bank
                                                        transfer receipt (Max: 5MB)</div>
                                                </div>

                                                <!-- Payment Method Selection -->
                                                <div class="mb-3">
                                                    <label class="form-label">Payment Method <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" name="payment_method" id="payment_method"
                                                        required>
                                                        <option value="bank_transfer" selected>Bank Transfer</option>
                                                        <option value="card">Credit/Debit Card</option>
                                                        <option value="wallet">Wallet</option>
                                                    </select>
                                                </div>

                                                <!-- Payment fields for different methods (conditionally shown) -->
                                                <div id="bank_transfer_fields" style="display: block;">
                                                    <!-- Bank transfer fields already shown above -->
                                                </div>



                                                <div id="card_fields" style="display: none;">
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
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Terms -->
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="payment_terms"
                                                id="payment_terms" required>
                                            <label class="form-check-label" for="payment_terms">
                                                I understand that the registration fee of ₦10,000.00 is required to activate
                                                my service request. I authorize the processing of this payment.
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Pay Later Notice -->
                                    <div id="pay-later-notice" class="alert alert-info mt-4" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock fa-2x me-3"></i>
                                            <div>
                                                <h5 class="mb-2">Pay Later Option Selected</h5>
                                                <p class="mb-0">You can complete your service request now and make the
                                                    registration payment within 7 days. You'll receive a payment link via
                                                    email.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Step 7: Review & Submit -->
                        <div class="form-step" id="step-7-content">
                            <h4 class="mb-4" style="color: #479c18;">
                                <i class="fas fa-clipboard-check me-2"></i> Review & Submit
                            </h4>

                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-1">Final Review</h5>
                                        <p class="mb-0">Please review all details carefully before submitting your
                                            service request.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header"
                                            style="background: linear-gradient(135deg, #479c18 0%, #28a745 100%); color: white;">
                                            <h6 class="mb-0"><i class="fas fa-list me-2"></i> Selected Services</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="review-services">
                                                <div class="text-center py-4">
                                                    <i class="fas fa-list-alt fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">No services selected</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header"
                                            style="background: linear-gradient(135deg, #007bff 0%, #00bcd4 100%); color: white;">
                                            <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Schedule</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="review-schedule">
                                                <div class="text-center py-4">
                                                    <i class="fas fa-calendar fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">Schedule details will appear here</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header"
                                            style="background: linear-gradient(135deg, #6f42c1 0%, #9c27b0 100%); color: white;">
                                            <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i> Personal Details
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="review-personal">
                                                <div class="text-center py-4">
                                                    <i class="fas fa-user fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">Personal details will appear here</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header"
                                            style="background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%); color: white;">
                                            <h6 class="mb-0"><i class="fas fa-calculator me-2"></i> Cost Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <div id="cost-summary">
                                                <div class="text-center py-4">
                                                    <i class="fas fa-calculator fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">Cost calculation will appear here</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Status -->
                            <div class="card mb-4">
                                <div class="card-header"
                                    style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); color: white;">
                                    <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i> Registration Status</h6>
                                </div>
                                <div class="card-body">
                                    <div id="review-registration">
                                        <div class="text-center py-4">
                                            <i class="fas fa-credit-card fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Registration details will appear here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ route('t&c') }}" target="_blank"
                                        class="text-decoration-none">Terms and Conditions</a> and understand that all
                                    information provided is accurate.
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-custom-primary btn-lg px-5 py-3" id="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Service Request
                                </button>
                            </div>
                        </div>


                        <!-- Navigation Buttons -->
                        <div class="action-buttons">
                            <button type="button" class="btn btn-custom-secondary" id="prev-btn">
                                <i class="fas fa-arrow-left me-2"></i> Previous
                            </button>
                            <button type="button" class="btn btn-custom-primary" id="next-btn">
                                Next <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Modal -->
    <div class="modal fade" id="benefitsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #479c18 0%, #28a745 100%); color: white;">
                    <h4 class="modal-title">
                        <i class="fas fa-crown me-2"></i>Registration Benefits - Why Choose Us?
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('partials._registration-benefits')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Got It, Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form wizard
            const form = document.getElementById('serviceRequestForm');
            const steps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const continueWithoutServicesBtn = document.getElementById('continue-without-services');

            // Check if user data is already saved from session
            let userDataSaved = {{ session('user_data_saved') ? 'true' : 'false' }};
            let savedUserId = {{ session('user_id') ?? 'null' }};

            // State management
            @if (auth()->check() || session('user_data_saved'))
                let currentStep = 2; // Start at step 2 if logged in or data saved
            @else
                let currentStep = 1; // Start at step 1 for guests
            @endif

            let totalSteps = 7;
            let hasPRS = false;
            let paymentChoice = 'now';
            let selectedServices = [];
            let maxDaysAllowed = 0;

            // Initialize form
            initForm();

            function initForm() {
                setupEventListeners();
                updateStepDisplay();
                generateBankReference();

                // Set initial payment choice
                updatePaymentChoice('now');

                // Check if user is already registered
                @if (auth()->check() && auth()->user()->is_registered)
                    paymentChoice = 'later';
                    updatePaymentChoice('later');
                @endif

                // If user data is saved from session, update UI
                if (userDataSaved) {
                    showNotification('Your information has been saved. You can continue with service selection.',
                        'success');
                }
            }

            function setupEventListeners() {
                // Service card click handler
                document.querySelectorAll('.service-card').forEach(card => {
                    card.addEventListener('click', function(e) {
                        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' ||
                            e.target.closest('button') || e.target.closest('a')) {
                            return;
                        }

                        const checkbox = this.querySelector('.service-checkbox');
                        if (checkbox) {
                            checkbox.checked = !checkbox.checked;
                            handleServiceSelection(checkbox);
                        }
                    });
                });

                // Service checkbox change handler
                document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        handleServiceSelection(this);
                    });

                    if (checkbox.checked) {
                        handleServiceSelection(checkbox);
                    }
                });

                // Continue without services button
                if (continueWithoutServicesBtn) {
                    continueWithoutServicesBtn.addEventListener('click', function() {
                        if (confirm(
                                'Are you sure you want to register without selecting services? You can add services later from your dashboard.'
                                )) {
                            // Mark as partial registration
                            document.querySelector('input[name="partial_registration"]').value = '1';
                            // Set payment choice to later for partial registration
                            updatePaymentChoice('later');

                            // Clear payment validation errors before submission
                            clearPaymentValidationErrors();

                            // Show loading state
                            submitBtn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                            submitBtn.disabled = true;

                            // Submit form immediately
                            form.submit();
                        }
                    });
                }

                // Apartment selection
                document.getElementById('apartment_type')?.addEventListener('change', function() {
                    validateStep(3);
                });

                // Package selection
                document.getElementById('package')?.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const daysAllowed = parseInt(selectedOption?.dataset.days) || 0;

                    document.getElementById('days_per_week').value = daysAllowed;
                    document.getElementById('percentage').value = selectedOption?.dataset.percentage || 0;

                    maxDaysAllowed = daysAllowed;
                    document.getElementById('max-days-indicator').textContent = maxDaysAllowed;
                    document.getElementById('max-days').textContent = maxDaysAllowed;

                    updateServiceDaysValidation();
                    validateStep(4);
                });

                // Service days checkboxes
                document.querySelectorAll('.service-day-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateServiceDaysValidation();
                    });
                });

                // Payment choice cards
                document.querySelectorAll('.payment-option-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const choice = this.dataset.paymentChoice;
                        updatePaymentChoice(choice);
                    });
                });

                // Payment method change handler
                document.getElementById('payment_method')?.addEventListener('change', function() {
                    handlePaymentMethodChange();
                });

                // Navigation buttons
                prevBtn.addEventListener('click', goToPrevStep);
                nextBtn.addEventListener('click', handleNextStep);

                // Form submission
                form.addEventListener('submit', function(e) {
                    // Check if this is a save and continue request
                    const isSaveAndContinue = document.getElementById('save_and_continue')?.value === '1';
                    const isPartialRegistration = document.querySelector(
                        'input[name="partial_registration"]')?.value === '1';

                    console.log('Form submission:', {
                        isSaveAndContinue,
                        isPartialRegistration,
                        paymentChoice
                    });

                    // For save and continue or partial registration, disable payment validation
                    if (isSaveAndContinue || isPartialRegistration) {
                        console.log('Save and continue or partial registration - skipping full validation');

                        // Clear payment validation errors
                        clearPaymentValidationErrors();

                        // Show loading state
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                        submitBtn.disabled = true;

                        // Allow form submission to proceed
                        return;
                    }

                    // Check registration status before final submission
                    const registrationRequired = checkRegistrationRequired();
                    const paymentChoice = document.querySelector('input[name="payment_choice"]:checked')
                        ?.value;

                    if (registrationRequired && !paymentChoice) {
                        e.preventDefault();
                        showNotification(
                            'Please complete your registration payment selection before submitting.',
                            'error');
                        currentStep = 6;
                        updateStepDisplay();
                        scrollToTop();
                        return;
                    }

                    // If user chose "pay later", show confirmation
                    if (paymentChoice === 'later') {
                        if (!confirm(
                                'You have chosen to pay later. Your service request will be created but will not be activated until you complete the ₦10,000 registration payment within 7 days. Do you want to continue?'
                                )) {
                            e.preventDefault();
                            return;
                        }
                    }

                    // For normal submission, validate all steps
                    if (!validateAllSteps()) {
                        e.preventDefault();
                        showNotification('Please complete all required fields before submitting.', 'error');
                    } else {
                        // Normal submission
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
                        submitBtn.disabled = true;
                    }
                });
            }

            function handleNextStep() {
                console.log('Next button clicked, current step:', currentStep);

                if (currentStep === 1 && !userDataSaved) {
                    console.log('Step 1: Saving personal details (save and continue)');

                    // Validate Step 1 only (personal details)
                    if (!validateStep(1)) {
                        showNotification('Please complete all required fields.', 'error');
                        return;
                    }

                    // Clear any previous validation errors
                    clearPaymentValidationErrors();

                    // Set the save_and_continue flag
                    document.getElementById('save_and_continue').value = '1';
                    console.log('Set save_and_continue flag to 1');

                    // Show loading state
                    nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
                    nextBtn.disabled = true;

                    // Submit the form to save user and login
                    console.log('Submitting form for save and continue...');
                    setTimeout(() => {
                        form.submit();
                    }, 100);
                    return;
                }

                // Check registration requirement before allowing step 7
                if (currentStep === 6) {
                    const registrationRequired = checkRegistrationRequired();

                    if (registrationRequired) {
                        // Check if payment choice is selected
                        const paymentChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;

                        if (!paymentChoice) {
                            showNotification('Please select a payment option to continue.', 'error');
                            return false;
                        }

                        // If payment choice is "now", validate payment details
                        if (paymentChoice === 'now') {
                            if (!validatePayment()) {
                                return false;
                            }
                        }
                    }
                }

                // For other steps, validate and proceed
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateStepDisplay();
                        scrollToTop();
                    }
                }
            }

            function clearPaymentValidationErrors() {
                // Remove any payment-related validation errors
                const paymentFields = [
                    'payment_method',
                    'payment_terms',
                    'card_number',
                    'card_expiry',
                    'card_cvv',
                    'cardholder_name',
                    'bank_reference',
                    'transfer_date',
                    'bank_receipt',
                    'wallet_pin'
                ];

                paymentFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.classList.remove('is-invalid');
                        // Remove required attribute for save_and_continue and partial registration
                        field.removeAttribute('required');
                        // Also remove any parent error messages
                        const errorContainer = field.closest('.form-group') || field.closest('.mb-3');
                        if (errorContainer) {
                            const errorDiv = errorContainer.querySelector('.invalid-feedback');
                            if (errorDiv) {
                                errorDiv.remove();
                            }
                        }
                    }
                });

                // Also clear any global error messages
                const errorAlerts = document.querySelectorAll('.alert.alert-danger');
                errorAlerts.forEach(alert => {
                    if (alert.textContent.includes('payment') ||
                        alert.textContent.includes('Payment') ||
                        alert.textContent.includes('card') ||
                        alert.textContent.includes('bank')) {
                        alert.remove();
                    }
                });
            }

            function goToPrevStep() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                    scrollToTop();
                }
            }

            function handleServiceSelection(checkbox) {
                const card = checkbox.closest('.service-card');
                const isChecked = checkbox.checked;

                card.classList.toggle('selected', isChecked);

                const serviceId = checkbox.value;
                if (isChecked) {
                    if (!selectedServices.includes(serviceId)) {
                        selectedServices.push(serviceId);
                    }
                } else {
                    selectedServices = selectedServices.filter(id => id !== serviceId);
                }

                checkPRSSelection();
                validateStep(currentStep);
            }

            function checkPRSSelection() {
                hasPRS = false;
                document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
                    if (checkbox.dataset.type === 'PRS') {
                        hasPRS = true;
                    }
                });

                const apartmentAlert = document.getElementById('apartment-alert');
                const apartmentInfo = document.getElementById('apartment-info');
                const apartmentNotNeeded = document.getElementById('apartment-not-needed');
                const apartmentSelection = document.getElementById('apartment-selection');

                if (hasPRS) {
                    if (apartmentAlert) apartmentAlert.style.display = 'none';
                    if (apartmentInfo) apartmentInfo.style.display = 'block';
                    if (apartmentNotNeeded) apartmentNotNeeded.style.display = 'none';
                    if (apartmentSelection) apartmentSelection.style.display = 'block';
                } else {
                    if (apartmentAlert) apartmentAlert.style.display = 'none';
                    if (apartmentInfo) apartmentInfo.style.display = 'none';
                    if (apartmentNotNeeded) apartmentNotNeeded.style.display = 'block';
                    if (apartmentSelection) apartmentSelection.style.display = 'none';
                }
            }

            // Add this function to check registration status
            function checkRegistrationRequired() {
                @if (auth()->check() && auth()->user()->is_registered)
                    // User is already registered, no payment needed
                    return false;
                @else
                    // User needs registration - check payment choice
                    const paymentChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;

                    // If user hasn't made a payment choice yet (shouldn't happen if they followed steps)
                    if (!paymentChoice) {
                        return true; // Registration is required
                    }

                    // If user chose "pay later" or hasn't paid, registration is still pending
                    return paymentChoice === 'later' || paymentChoice === null;
                @endif
            }

            function updateStepDisplay() {
                // Hide all steps
                steps.forEach(step => step.classList.remove('active'));

                // Show current step
                const currentStepElement = document.getElementById(`step-${currentStep}-content`);
                if (currentStepElement) {
                    currentStepElement.classList.add('active');
                }

                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('active', 'completed');
                    if (index + 1 === currentStep) {
                        indicator.classList.add('active');
                    } else if (index + 1 < currentStep) {
                        indicator.classList.add('completed');
                    }
                });

                // Update navigation buttons
                prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-block';

                if (currentStep === totalSteps) {
                    nextBtn.style.display = 'none';
                    if (submitBtn) submitBtn.style.display = 'inline-block';
                } else {
                    nextBtn.style.display = 'inline-block';
                    if (submitBtn) submitBtn.style.display = 'none';
                }

                // Skip apartment step if no PRS services
                if (currentStep === 3 && !hasPRS) {
                    setTimeout(() => {
                        currentStep++;
                        updateStepDisplay();
                    }, 100);
                    return;
                }

                // Check registration requirement before allowing step 7
                if (currentStep === 7) {
                    const registrationRequired = checkRegistrationRequired();

                    if (registrationRequired) {
                        // Show registration required message and redirect to step 6
                        showRegistrationRequiredAlert();

                        // Auto-redirect to step 6 after a brief delay
                        setTimeout(() => {
                            currentStep = 6;
                            updateStepDisplay();
                            scrollToTop();
                        }, 3000);

                        return; // Don't update review since we're redirecting
                    }

                    // If registration is not required, update the review
                    updateReview();
                }
            }

            // Add this function to show registration required alert
            function showRegistrationRequiredAlert() {
                // Remove any existing alerts
                const existingAlert = document.querySelector('#registration-required-review-alert');
                if (existingAlert) existingAlert.remove();

                const alertDiv = document.createElement('div');
                alertDiv.id = 'registration-required-review-alert';
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-2">Registration Required</h5>
                    <p class="mb-0">You need to complete your registration before you can submit the service request.</p>
                    <p class="mb-0 mt-2"><strong>Redirecting to payment step in 3 seconds...</strong></p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

                // Insert at the beginning of step 7 content
                const step7Content = document.getElementById('step-7-content');
                if (step7Content) {
                    step7Content.insertBefore(alertDiv, step7Content.firstChild);
                }
            }

            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function validateStep(stepNumber) {
                let isValid = true;

                switch (stepNumber) {
                    case 1: // Registration step
                        isValid = validateRegistration();
                        break;
                    case 2: // Services selection
                        isValid = validateServices();
                        break;
                    case 3: // Apartment selection
                        isValid = validateApartment();
                        break;
                    case 4: // Package selection
                        isValid = validatePackage();
                        break;
                    case 5: // Service days
                        isValid = validateServiceDays();
                        break;
                    case 6: // Payment
                        isValid = validatePayment();
                        break;
                    case 7: // Terms
                        isValid = validateTerms();
                        break;
                }

                return isValid;
            }

            function validateAllSteps() {
                // Don't validate payment for save_and_continue or partial registration
                const isSaveAndContinue = document.getElementById('save_and_continue')?.value === '1';
                const isPartialRegistration = document.querySelector('input[name="partial_registration"]')
                    ?.value === '1';

                if (isSaveAndContinue || isPartialRegistration) {
                    console.log('Skipping full validation for save_and_continue or partial registration');
                    return true;
                }

                for (let i = 1; i <= totalSteps; i++) {
                    if (!validateStep(i)) {
                        currentStep = i;
                        updateStepDisplay();
                        return false;
                    }
                }
                return true;
            }

            function validateRegistration() {
                const requiredFields = document.querySelectorAll('#step-1-content [required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');

                        if (field.type === 'email' && !isValidEmail(field.value)) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        }

                        if (field.name === 'phone' && !isValidPhone(field.value)) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                });

                return isValid;
            }

            function validateServices() {
                // If user is saving partial registration (no services), allow it
                const partialRegistration = document.querySelector('input[name="partial_registration"]')?.value ===
                    '1';
                if (partialRegistration) return true;

                const selectedCount = document.querySelectorAll('.service-checkbox:checked').length;
                const isValid = selectedCount > 0;

                const errorElement = document.getElementById('services-error');
                if (errorElement) {
                    errorElement.style.display = isValid ? 'none' : 'block';
                }

                return isValid;
            }

            function validateApartment() {
                if (!hasPRS) return true;

                const apartmentSelect = document.getElementById('apartment_type');
                const isValid = apartmentSelect && apartmentSelect.value !== '';

                const apartmentAlert = document.getElementById('apartment-alert');
                if (apartmentAlert) {
                    apartmentAlert.style.display = isValid ? 'none' : 'block';
                }

                return isValid;
            }

            function validatePackage() {
                const packageSelect = document.getElementById('package');
                const isValid = packageSelect && packageSelect.value !== '';

                if (!isValid) {
                    showNotification('Please select a service package.', 'warning');
                }

                return isValid;
            }

            function updateServiceDaysValidation() {
                const selectedDays = document.querySelectorAll('.service-day-checkbox:checked').length;
                const daysError = document.getElementById('days-error');
                const limitError = document.getElementById('days-limit-error');

                if (selectedDays === 0) {
                    if (daysError) daysError.style.display = 'block';
                    if (limitError) limitError.style.display = 'none';
                    return false;
                } else {
                    if (daysError) daysError.style.display = 'none';
                }

                if (maxDaysAllowed > 0 && selectedDays > maxDaysAllowed) {
                    if (limitError) limitError.style.display = 'block';
                    return false;
                } else {
                    if (limitError) limitError.style.display = 'none';
                }

                return true;
            }

            function validateServiceDays() {
                return updateServiceDaysValidation();
            }

            function validatePayment() {
                // Skip payment validation for save_and_continue or partial registration
                const isSaveAndContinue = document.getElementById('save_and_continue')?.value === '1';
                const isPartialRegistration = document.querySelector('input[name="partial_registration"]')
                    ?.value === '1';

                if (isSaveAndContinue || isPartialRegistration) {
                    console.log('Skipping payment validation for save_and_continue or partial registration');
                    return true;
                }

                @if (auth()->check() && auth()->user()->is_registered)
                    return true;
                @endif

                // Get the selected payment choice
                const selectedPaymentChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;

                if (!selectedPaymentChoice) {
                    showNotification('Please select when you would like to pay for registration.', 'warning');
                    return false;
                }

                if (selectedPaymentChoice === 'now') {
                    // Validate payment details
                    const paymentMethod = document.querySelector('#payment_method')?.value;
                    if (!paymentMethod) {
                        showNotification('Please select a payment method.', 'warning');
                        return false;
                    }

                    // Validate based on payment method
                    let isValid = true;

                    switch (paymentMethod) {
                        case 'bank_transfer':
                            const bankRef = document.querySelector('input[name="bank_reference"]')?.value;
                            const transferDate = document.querySelector('input[name="transfer_date"]')?.value;
                            const bankReceipt = document.querySelector('input[name="bank_receipt"]')?.files[0];

                            if (!bankRef || !transferDate || !bankReceipt) {
                                showNotification('Please complete all bank transfer details.', 'warning');
                                isValid = false;
                            }
                            break;

                        case 'card':
                            const cardNumber = document.querySelector('input[name="card_number"]')?.value;
                            const cardExpiry = document.querySelector('input[name="card_expiry"]')?.value;
                            const cardCVV = document.querySelector('input[name="card_cvv"]')?.value;
                            const cardholderName = document.querySelector('input[name="cardholder_name"]')?.value;

                            if (!cardNumber || !cardExpiry || !cardCVV || !cardholderName) {
                                showNotification('Please complete all card details.', 'warning');
                                isValid = false;
                            }
                            break;

                        case 'wallet':
                            const walletPin = document.querySelector('input[name="wallet_pin"]')?.value;
                            if (!walletPin) {
                                showNotification('Please enter your wallet PIN.', 'warning');
                                isValid = false;
                            }
                            break;
                    }

                    // Validate payment terms
                    const paymentTerms = document.querySelector('#payment_terms')?.checked;
                    if (!paymentTerms) {
                        showNotification('Please accept the payment terms.', 'warning');
                        isValid = false;
                    }

                    return isValid;
                }

                // For "pay later", registration is still pending but can proceed to review
                // User will need to complete payment later
                showNotification(
                    'Note: Your registration payment is pending. You will need to complete payment within 7 days to activate your service request.',
                    'info');
                return true;
            }

            function validateTerms() {
                const termsCheckbox = document.getElementById('terms');
                if (!termsCheckbox.checked) {
                    showNotification('Please accept the terms and conditions.', 'warning');
                    return false;
                }
                return true;
            }

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function isValidPhone(phone) {
                return /^\d{11}$/.test(phone);
            }

            function updatePaymentChoice(choice) {
                paymentChoice = choice;

                document.querySelectorAll('.payment-option-card').forEach(card => {
                    card.classList.remove('active');
                    if (card.dataset.paymentChoice === choice) {
                        card.classList.add('active');
                    }
                });

                const radio = document.querySelector(`input[value="${choice}"]`);
                if (radio) {
                    radio.checked = true;
                }

                document.getElementById('proceed_to_payment').value = choice === 'now' ? '1' : '0';

                const paymentDetails = document.getElementById('payment-details');
                const payLaterNotice = document.getElementById('pay-later-notice');

                if (choice === 'now') {
                    if (paymentDetails) paymentDetails.style.display = 'block';
                    if (payLaterNotice) payLaterNotice.style.display = 'none';
                    generateBankReference();

                    // Make payment fields required for "Pay Now"
                    setPaymentFieldsRequired(true);
                } else {
                    if (paymentDetails) paymentDetails.style.display = 'none';
                    if (payLaterNotice) payLaterNotice.style.display = 'block';

                    // Remove required attribute for "Pay Later"
                    setPaymentFieldsRequired(false);
                }
            }

            function setPaymentFieldsRequired(isRequired) {
                const paymentFields = [
                    'payment_method',
                    'payment_terms',
                    'card_number',
                    'card_expiry',
                    'card_cvv',
                    'cardholder_name',
                    'bank_reference',
                    'transfer_date',
                    'bank_receipt',
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

            function handlePaymentMethodChange() {
                const paymentMethod = document.querySelector('#payment_method')?.value;

                // Hide all payment method specific fields
                const bankFields = document.getElementById('bank_transfer_fields');
                const cardFields = document.getElementById('card_fields');
                const walletFields = document.getElementById('wallet_fields');

                if (bankFields) bankFields.style.display = 'none';
                if (cardFields) cardFields.style.display = 'none';
                if (walletFields) walletFields.style.display = 'none';

                // Show the selected payment method fields
                if (paymentMethod) {
                    const selectedFields = document.getElementById(paymentMethod + '_fields');
                    if (selectedFields) {
                        selectedFields.style.display = 'block';
                    }
                }
            }

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

            function updateReview() {
                updateServicesReview();
                updateScheduleReview();
                updatePersonalReview();
                updateCostSummary();
                updateRegistrationReview();
            }

            function updateServicesReview() {
                const selectedServices = Array.from(document.querySelectorAll('.service-checkbox:checked'))
                    .map(cb => {
                        const card = cb.closest('.service-card');
                        return card ? card.querySelector('h5').textContent : '';
                    })
                    .filter(Boolean);

                const reviewServices = document.getElementById('review-services');

                if (selectedServices.length > 0) {
                    reviewServices.innerHTML = selectedServices.map(service => `
                <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <div>${service}</div>
                </div>
            `).join('');
                } else {
                    reviewServices.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">No services selected</p>
                </div>
            `;
                }
            }

            function updateScheduleReview() {
                const packageSelect = document.getElementById('package');
                const selectedPackage = packageSelect?.options[packageSelect.selectedIndex];
                const selectedDays = Array.from(document.querySelectorAll('.service-day-checkbox:checked'))
                    .map(cb => cb.value);

                const reviewSchedule = document.getElementById('review-schedule');

                let scheduleHTML = '';

                if (selectedPackage) {
                    scheduleHTML += `
                <div class="mb-3">
                    <strong>Package:</strong><br>
                    ${selectedPackage.textContent}
                </div>
            `;
                }

                if (selectedDays.length > 0) {
                    scheduleHTML += `
                <div class="mb-3">
                    <strong>Service Days:</strong><br>
                    ${selectedDays.join(', ')}
                </div>
            `;
                }

                if (selectedPackage || selectedDays.length > 0) {
                    reviewSchedule.innerHTML = scheduleHTML;
                } else {
                    reviewSchedule.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">No schedule selected</p>
                </div>
            `;
                }
            }

            function updatePersonalReview() {
                const name = document.querySelector('input[name="name"]')?.value || '';
                const lastName = document.querySelector('input[name="last_name"]')?.value || '';
                const email = document.querySelector('input[name="email"]')?.value || '';
                const phone = document.querySelector('input[name="phone"]')?.value || '';
                const gender = document.querySelector('select[name="gender"]')?.options[document.querySelector(
                    'select[name="gender"]').selectedIndex]?.text || '';
                const address = document.querySelector('input[name="address"]')?.value || '';

                const reviewPersonal = document.getElementById('review-personal');

                let personalHTML = '';

                if (name || lastName) {
                    personalHTML += `
                <div class="mb-2">
                    <strong>Name:</strong> ${name} ${lastName}
                </div>
            `;
                }

                if (email) {
                    personalHTML += `
                <div class="mb-2">
                    <strong>Email:</strong> ${email}
                </div>
            `;
                }

                if (phone) {
                    personalHTML += `
                <div class="mb-2">
                    <strong>Phone:</strong> ${phone}
                </div>
            `;
                }

                if (gender) {
                    personalHTML += `
                <div class="mb-2">
                    <strong>Gender:</strong> ${gender}
                </div>
            `;
                }

                if (address) {
                    personalHTML += `
                <div class="mb-2">
                    <strong>Address:</strong> ${address}
                </div>
            `;
                }

                if (personalHTML) {
                    reviewPersonal.innerHTML = personalHTML;
                } else {
                    reviewPersonal.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-3"></i>
                    <p class="text-muted">No personal details provided</p>
                </div>
            `;
                }
            }

            function updateCostSummary() {
                const costSummary = document.getElementById('cost-summary');

                costSummary.innerHTML = `
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span>Service Costs:</span>
                    <span>₦0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Package Cost:</span>
                    <span>₦0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Registration Fee:</span>
                    <span>₦10,000.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Estimated Total:</strong>
                    <strong>₦10,000.00</strong>
                </div>
            </div>
            <div class="alert alert-info small">
                <i class="fas fa-info-circle me-1"></i>
                Final costs will be calculated after submission based on your selections.
            </div>
        `;
            }

            function updateRegistrationReview() {
                const reviewRegistration = document.getElementById('review-registration');

                @if (auth()->check() && auth()->user()->is_registered)
                    reviewRegistration.innerHTML = `
                <div class="alert alert-success mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <strong>Already Registered</strong><br>
                            <small>Your registration is active. No additional payment required.</small>
                        </div>
                    </div>
                </div>
            `;
                @else
                    const paymentChoice = document.querySelector('input[name="payment_choice"]:checked')?.value;

                    if (paymentChoice === 'now') {
                        const paymentMethod = document.querySelector('#payment_method')?.value;
                        reviewRegistration.innerHTML = `
                    <div class="alert alert-warning mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-credit-card fa-2x me-3"></i>
                            <div>
                                <strong>Pay Now - ₦10,000.00</strong><br>
                                <small>Registration fee will be paid via ${paymentMethod || 'selected payment method'}. Your service will be activated immediately after payment confirmation.</small>
                            </div>
                        </div>
                    </div>
                `;
                    } else if (paymentChoice === 'later') {
                        reviewRegistration.innerHTML = `
                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div>
                                <strong>Pay Later - ₦10,000.00</strong><br>
                                <small><strong>Important:</strong> Your service request will be created but <strong>will not be activated</strong> until registration payment is completed within 7 days.</small>
                            </div>
                        </div>
                    </div>
                `;
                    } else {
                        reviewRegistration.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <strong>Registration Required</strong><br>
                                <small>You need to complete registration payment before your service request can be activated.</small>
                            </div>
                        </div>
                    </div>
                `;
                    }
                @endif
            }

            // Helper function to select service from modal
            window.selectService = function(serviceId) {
                const checkbox = document.getElementById(`service${serviceId}`);
                if (checkbox) {
                    checkbox.checked = true;
                    handleServiceSelection(checkbox);
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        `serviceModal-${serviceId}`));
                    if (modal) modal.hide();
                    showNotification('Service added to your selection!', 'success');
                }
            };

            function showNotification(message, type = 'info') {
                const existing = document.querySelector('.service-request-notification');
                if (existing) existing.remove();

                const alertClass = {
                    'success': 'alert-success',
                    'error': 'alert-danger',
                    'warning': 'alert-warning',
                    'info': 'alert-info'
                } [type] || 'alert-info';

                const icon = {
                    'success': 'check-circle',
                    'error': 'exclamation-circle',
                    'warning': 'exclamation-triangle',
                    'info': 'info-circle'
                } [type] || 'info-circle';

                const div = document.createElement('div');
                div.className = `service-request-notification alert ${alertClass} alert-dismissible fade show`;
                div.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${icon} me-2"></i>
                <div>${message}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

                document.body.appendChild(div);

                setTimeout(() => {
                    if (div.parentNode) {
                        div.remove();
                    }
                }, 5000);
            }
        });
    </script>
@endsection
