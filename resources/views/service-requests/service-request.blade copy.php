@extends('layouts.main')

@section('service_request')
    <style>
        /* All previous CSS styles from earlier implementation */
        .service-request-wizard {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50px;
            right: 50px;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: #479c18;
            color: white;
            transform: scale(1.1);
        }

        .step.completed .step-number {
            background: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
            font-weight: 500;
        }

        .step.active .step-label {
            color: #479c18;
            font-weight: 600;
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .service-item {
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .service-item:hover {
            border-color: #479c18;
            background-color: rgba(71, 156, 24, 0.05);
        }

        .service-item.selected {
            border-color: #479c18;
            background-color: rgba(71, 156, 24, 0.1);
        }

        .summary-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid #479c18;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #479c18;
            font-size: 1.1rem;
        }

        .registration-prompt {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
        }

        .registration-benefits {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .benefit-item i {
            color: #479c18;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .day-checkbox {
            position: relative;
        }

        .day-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .day-checkbox label {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            display: block;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .day-checkbox input:checked+label {
            background: #479c18;
            color: white;
            border-color: #479c18;
        }

        .day-checkbox input:disabled+label {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .user-status-banner {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>

    <div class="container py-5">
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- User Status Banner -->
        @auth
            @if (Auth::user()->is_registered)
                <div class="user-status-banner">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h5>
                            <p class="mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Your registration is active. You can proceed with your service request.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <div class="service-request-wizard">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Services</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Apartment</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Package</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Schedule</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-number">5</div>
                    <div class="step-label">Summary</div>
                </div>
            </div>

            <form action="{{ route('service-request.save') }}" method="POST" id="serviceRequestForm">
                @csrf

                <input type="hidden" name="days_per_week" id="days_per_week" value="">

                <!-- Step 1: Services Selection -->
                <div class="form-step active" id="step-1">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-list me-2"></i>Step 1: Select Services
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Choose the services you need. You can select multiple services.</p>

                            <div id="services-container">
                                @foreach ($allServices as $service)
                                    <div class="service-item" data-service-id="{{ $service->id }}">
                                        <div class="form-check">
                                            <input class="form-check-input service-checkbox" type="checkbox"
                                                name="serviceName[]" value="{{ $service->id }}"
                                                id="service{{ $service->id }}" data-type="{{ $service->service_type }}"
                                                data-cost="{{ $service->base_cost ?? 0 }}">
                                            <label
                                                class="form-check-label w-100 d-flex justify-content-between align-items-center"
                                                for="service{{ $service->id }}">
                                                <div>
                                                    <i
                                                        class="{{ $service->icon_class ?? 'fas fa-cogs' }} me-2 text-success"></i>
                                                    <strong>{{ $service->service_name }}</strong>
                                                    @if ($service->base_cost)
                                                        <span
                                                            class="badge bg-success ms-2">₦{{ number_format($service->base_cost, 2) }}</span>
                                                    @endif
                                                </div>
                                                @if ($service->dos_and_donts)
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#termsModal{{ $service->id }}">
                                                        <i class="fas fa-file-alt me-1"></i> View Terms
                                                    </button>
                                                @endif
                                            </label>
                                        </div>

                                        @if ($service->description)
                                            <div class="mt-2 ps-4">
                                                <small class="text-muted">{{ $service->description }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Terms Modal -->
                                    @if ($service->dos_and_donts)
                                        <div class="modal fade" id="termsModal{{ $service->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-file-contract me-2"></i>
                                                            {{ $service->service_name }} - Terms & Conditions
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! $service->dos_and_donts !!}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-success"
                                                            data-bs-dismiss="modal">
                                                            <i class="fas fa-check me-1"></i> I Understand
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="invalid-feedback" id="service-error" style="display: none;">
                                Please select at least one service
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Apartment Type -->
                <div class="form-step" id="step-2">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-home me-2"></i>Step 2: Apartment Information
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Select your apartment type (required for Property & Realty
                                Services).</p>

                            <div class="mb-3">
                                <label for="apartment_type" class="form-label">Apartment Type</label>
                                <select class="form-select" id="apartment_type" name="apartment_type">
                                    <option value="">-- Select Apartment Type --</option>
                                    @foreach ($apartments as $apartment)
                                        <option value="{{ $apartment->id }}" data-cost="{{ $apartment->cost }}">
                                            {{ $apartment->name }} - ₦{{ number_format($apartment->cost, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="apartment-error" style="display: none;">
                                    Please select an apartment type for PRS services
                                </div>
                                <div id="apartment-help-text" class="form-text"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Package Selection -->
                <div class="form-step" id="step-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-box me-2"></i>Step 3: Select Package
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Choose your service package frequency.</p>

                            <div class="mb-3">
                                <label for="package" class="form-label">Service Package <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="package" name="package" required>
                                    <option value="">-- Select a Package --</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" data-cost="{{ $package->cost }}"
                                            data-days="{{ $package->days_per_week }}">
                                            {{ $package->name }} - {{ $package->days_per_week }} time(s) per week -
                                            ₦{{ number_format($package->cost, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a service package</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Service Days -->
                <div class="form-step" id="step-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-calendar-check me-2"></i>Step 4: Schedule Service Days
                            </h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Select the days you want service (based on your package selection).
                            </p>

                            <div class="row" id="service-days-container">
                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <div class="col-md-4 mb-3">
                                        <div class="day-checkbox">
                                            <input type="checkbox" name="service_days[]" id="day_{{ strtolower($day) }}"
                                                value="{{ $day }}">
                                            <label for="day_{{ strtolower($day) }}" class="w-100">
                                                <i class="fas fa-calendar-day me-2"></i>{{ $day }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="invalid-feedback" id="days-error" style="display: none;">
                                Please select at least one service day
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Summary -->
                <div class="form-step" id="step-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-success">
                                <i class="fas fa-clipboard-check me-2"></i>Step 5: Summary & Registration
                            </h4>
                        </div>
                        <div class="card-body">
                            <!-- Order Summary -->
                            <div id="order-summary" class="summary-card mb-4">
                                <h5 class="mb-3">Service Request Summary</h5>
                                <div id="summary-content">
                                    <div class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">Loading summary...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Registration Prompt (Only show if user is not registered) -->
                            @if (!auth()->check() || (auth()->check() && !Auth::user()->is_registered))
                                <div class="registration-prompt">
                                    <div class="text-center mb-4">
                                        <i class="fas fa-lock fa-3x text-warning mb-3"></i>
                                        <h4 class="text-warning">Registration Required</h4>
                                        <p class="mb-3">To complete and activate your service request, you need to
                                            register with us.</p>

                                        <div class="registration-benefits mb-4">
                                            <h5 class="mb-3 text-primary">
                                                <i class="fas fa-crown me-2"></i>Benefits of Registration
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Verified & Background Checked Staff</span>
                                                    </div>
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Free Staff Replacements</span>
                                                    </div>
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Backup Support Staff</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Priority Customer Support</span>
                                                    </div>
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Monthly Deep Cleaning (for cleaning services)</span>
                                                    </div>
                                                    <div class="benefit-item">
                                                        <i class="fas fa-check-circle"></i>
                                                        <span>Peace of Mind Guarantee</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mb-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Registration Fee:</strong> ₦10,000 per year (One-time payment for full
                                            year access)
                                        </div>

                                        @if (auth()->check())
                                            <!-- Already have account but not registered -->
                                            <a href="{{ route('service-request.registration') }}"
                                                class="btn btn-warning btn-lg">
                                                <i class="fas fa-user-check me-2"></i> Complete Registration Now
                                            </a>
                                        @else
                                            <!-- New user -->
                                            <a href="{{ route('service-request.personal-info') }}"
                                                class="btn btn-warning btn-lg">
                                                <i class="fas fa-user-plus me-2"></i> Register & Continue
                                            </a>
                                            <p class="mt-3 small text-muted">
                                                Already have an account?
                                                <a href="{{ route('login') }}" class="text-primary">Login here</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms"
                                        required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="{{ route('t&c') }}" target="_blank"
                                            class="text-success">Terms and Conditions</a>
                                        and understand that this service request is subject to approval.
                                    </label>
                                    <div class="invalid-feedback">You must accept the terms and conditions</div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            @if (auth()->check() && Auth::user()->is_registered)
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Ready to submit!</strong> You're registered and can submit your service request
                                    immediately.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Note:</strong> Your service request will be saved but will not be processed
                                    until you complete registration.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn btn-secondary" id="prev-btn" disabled>
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="next-btn">
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                        <i class="fas fa-paper-plane me-2"></i>
                        @if (auth()->check() && Auth::user()->is_registered)
                            Submit Service Request
                        @else
                            Save & Register
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const form = document.getElementById('serviceRequestForm');
            const steps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            // Form elements
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const apartmentSelect = document.getElementById('apartment_type');
            const packageSelect = document.getElementById('package');
            const daysPerWeekInput = document.getElementById('days_per_week');
            const dayCheckboxes = document.querySelectorAll('input[name="service_days[]"]');
            const termsCheckbox = document.getElementById('terms');

            // Summary elements
            const summaryContent = document.getElementById('summary-content');

            // State
            let currentStep = 0;
            const totalSteps = 5;
            let hasPRS = false;
            let selectedPackageDays = 0;

            // Constants
            const SERVICE_CHARGE = 10000;
            const EXTRA_SERVICE_COST = 12000;
            const REGISTRATION_FEE = 10000;

            // Check user status
            const userIsRegistered = {{ auth()->check() && Auth::user()->is_registered ? 'true' : 'false' }};

            // Initialize
            initializeForm();

            function initializeForm() {
                updateStepIndicator();
                setupEventListeners();
                checkPRSSelection();
                updateApartmentRequirement();
                updateSummary();
            }

            function setupEventListeners() {
                // Service checkbox changes
                serviceCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const serviceItem = this.closest('.service-item');
                        serviceItem?.classList.toggle('selected', this.checked);
                        checkPRSSelection();
                        updateApartmentRequirement();
                        updateSummary();
                    });
                });

                // Apartment selection
                apartmentSelect?.addEventListener('change', updateSummary);

                // Package selection
                packageSelect?.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    selectedPackageDays = parseInt(selectedOption.dataset.days) || 0;
                    daysPerWeekInput.value = selectedPackageDays;
                    updateDaySelectionLimit();
                    updateSummary();
                });

                // Day checkboxes
                dayCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateDaySelectionLimit();
                        updateSummary();
                    });
                });

                // Navigation buttons
                prevBtn.addEventListener('click', goToPrevStep);
                nextBtn.addEventListener('click', goToNextStep);

                // Form submission
                form.addEventListener('submit', function(e) {
                    if (!validateAllSteps()) {
                        e.preventDefault();
                        showNotification('Please fill in all required fields.', 'error');
                    } else {
                        // Update submit button text based on user status
                        if (!userIsRegistered) {
                            submitBtn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-2"></i> Saving & Redirecting...';
                        } else {
                            submitBtn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
                        }
                        submitBtn.disabled = true;
                    }
                });
            }

            function checkPRSSelection() {
                hasPRS = Array.from(serviceCheckboxes).some(cb =>
                    cb.checked && cb.dataset.type === 'PRS'
                );
            }

            function updateApartmentRequirement() {
                const helpText = document.getElementById('apartment-help-text');
                if (hasPRS) {
                    if (helpText) {
                        helpText.innerHTML =
                            '<i class="fas fa-exclamation-circle text-warning"></i> Required for Property & Realty Services';
                        helpText.className = 'form-text text-warning';
                    }
                } else {
                    if (helpText) {
                        helpText.innerHTML =
                            '<i class="fas fa-info-circle text-muted"></i> Only required if you selected PRS services';
                        helpText.className = 'form-text text-muted';
                    }
                }
            }

            function updateDaySelectionLimit() {
                const checkedCount = Array.from(dayCheckboxes).filter(cb => cb.checked).length;

                if (selectedPackageDays > 0) {
                    // Disable all checkboxes first
                    dayCheckboxes.forEach(cb => {
                        cb.disabled = false;
                        cb.parentElement.classList.remove('disabled');
                    });

                    // If max days reached, disable unchecked ones
                    if (checkedCount >= selectedPackageDays) {
                        dayCheckboxes.forEach(cb => {
                            if (!cb.checked) {
                                cb.disabled = true;
                                cb.parentElement.classList.add('disabled');
                            }
                        });
                    }
                } else {
                    // Enable all if no limit
                    dayCheckboxes.forEach(cb => {
                        cb.disabled = false;
                        cb.parentElement.classList.remove('disabled');
                    });
                }
            }

            function updateStepIndicator() {
                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('active', 'completed');
                    if (index === currentStep) {
                        indicator.classList.add('active');
                    } else if (index < currentStep) {
                        indicator.classList.add('completed');
                    }
                });
            }

            function goToNextStep() {
                if (!validateStep(currentStep)) {
                    return;
                }

                // Update summary before showing last step
                if (currentStep === totalSteps - 2) {
                    updateSummary();
                }

                steps[currentStep].classList.remove('active');
                currentStep++;
                steps[currentStep].classList.add('active');

                updateNavigation();
                updateStepIndicator();
                scrollToTop();
            }

            function goToPrevStep() {
                steps[currentStep].classList.remove('active');
                currentStep--;
                steps[currentStep].classList.add('active');

                updateNavigation();
                updateStepIndicator();
                scrollToTop();
            }

            function updateNavigation() {
                prevBtn.disabled = currentStep === 0;

                if (currentStep === totalSteps - 1) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-block';
                } else {
                    nextBtn.style.display = 'inline-block';
                    submitBtn.style.display = 'none';
                }
            }

            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function validateStep(stepIndex) {
                switch (stepIndex) {
                    case 0: // Services
                        return validateServices();
                    case 1: // Apartment
                        return validateApartment();
                    case 2: // Package
                        return validatePackage();
                    case 3: // Days
                        return validateDays();
                    case 4: // Summary
                        return validateTerms();
                    default:
                        return true;
                }
            }

            function validateServices() {
                const checkedServices = Array.from(serviceCheckboxes).filter(cb => cb.checked);
                const serviceError = document.getElementById('service-error');
                const isValid = checkedServices.length > 0;

                if (!isValid) {
                    serviceError.style.display = 'block';
                    serviceError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    serviceError.style.display = 'none';
                }

                return isValid;
            }

            function validateApartment() {
                if (!hasPRS) return true;

                const isValid = apartmentSelect.value !== '';
                const apartmentError = document.getElementById('apartment-error');

                if (!isValid) {
                    apartmentError.style.display = 'block';
                    apartmentSelect.classList.add('is-invalid');
                    apartmentSelect.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    apartmentError.style.display = 'none';
                    apartmentSelect.classList.remove('is-invalid');
                }

                return isValid;
            }

            function validatePackage() {
                const isValid = packageSelect.value !== '';

                if (!isValid) {
                    packageSelect.classList.add('is-invalid');
                    packageSelect.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    packageSelect.classList.remove('is-invalid');
                }

                return isValid;
            }

            function validateDays() {
                const checkedDays = Array.from(dayCheckboxes).filter(cb => cb.checked);
                const daysError = document.getElementById('days-error');
                const isValid = checkedDays.length > 0;

                if (!isValid) {
                    daysError.style.display = 'block';
                    daysError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    daysError.style.display = 'none';
                }

                return isValid;
            }

            function validateTerms() {
                const isValid = termsCheckbox.checked;

                if (!isValid) {
                    termsCheckbox.classList.add('is-invalid');
                    termsCheckbox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    termsCheckbox.classList.remove('is-invalid');
                }

                return isValid;
            }

            function validateAllSteps() {
                for (let i = 0; i < totalSteps; i++) {
                    if (!validateStep(i)) {
                        // Go to the step with error
                        steps[currentStep].classList.remove('active');
                        currentStep = i;
                        steps[currentStep].classList.add('active');
                        updateNavigation();
                        updateStepIndicator();
                        scrollToTop();
                        return false;
                    }
                }
                return true;
            }

            function updateSummary() {
                // Get selected services
                const selectedServices = Array.from(serviceCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => ({
                        id: cb.value,
                        name: cb.closest('.service-item').querySelector('strong').textContent,
                        type: cb.dataset.type,
                        cost: parseFloat(cb.dataset.cost) || 0
                    }));

                // Get apartment cost if selected
                let apartmentCost = 0;
                let apartmentName = 'Not selected';
                if (apartmentSelect.value && hasPRS) {
                    const selectedOption = apartmentSelect.options[apartmentSelect.selectedIndex];
                    apartmentCost = parseFloat(selectedOption.dataset.cost) || 0;
                    apartmentName = selectedOption.textContent.split(' - ')[0];
                }

                // Get package cost
                let packageCost = 0;
                let packageName = 'Not selected';
                if (packageSelect.value) {
                    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                    packageCost = parseFloat(selectedOption.dataset.cost) || 0;
                    packageName = selectedOption.textContent.split(' - ')[0];
                }

                // Get selected days
                const selectedDays = Array.from(dayCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                // Calculate costs
                let baseCost = 0;
                let extraCost = 0;

                if (selectedServices.length > 0) {
                    // Find main service (highest cost)
                    const mainService = selectedServices.reduce((prev, current) =>
                        (prev.cost > current.cost) ? prev : current, {
                            cost: 0
                        }
                    );
                    baseCost = mainService.cost;

                    // Calculate extra service costs
                    selectedServices.forEach(service => {
                        if (service.id === mainService.id) return;

                        switch (service.type) {
                            case 'PRS':
                                extraCost += apartmentCost / 2;
                                break;
                            case 'PMS':
                                extraCost += service.cost / 2;
                                break;
                            case 'PSS':
                                extraCost += EXTRA_SERVICE_COST;
                                break;
                            default:
                                extraCost += EXTRA_SERVICE_COST;
                                break;
                        }
                    });
                }

                const totalServiceCost = baseCost + packageCost + extraCost;
                const grandTotal = totalServiceCost + SERVICE_CHARGE + (userIsRegistered ? 0 : REGISTRATION_FEE);

                // Format currency
                const formatCurrency = (amount) => {
                    return new Intl.NumberFormat('en-NG', {
                        style: 'currency',
                        currency: 'NGN',
                        minimumFractionDigits: 2
                    }).format(amount);
                };

                // Build summary HTML
                let summaryHTML = '';

                if (selectedServices.length > 0) {
                    summaryHTML += `
                <div class="summary-item">
                    <span>Selected Services:</span>
                    <span>${selectedServices.length} service(s)</span>
                </div>`;
                }

                if (hasPRS) {
                    summaryHTML += `
                <div class="summary-item">
                    <span>Apartment Type:</span>
                    <span>${apartmentName}</span>
                </div>`;
                }

                summaryHTML += `
            <div class="summary-item">
                <span>Service Package:</span>
                <span>${packageName}</span>
            </div>
            <div class="summary-item">
                <span>Service Days:</span>
                <span>${selectedDays.length > 0 ? selectedDays.join(', ') : 'Not selected'}</span>
            </div>
            <div class="summary-item">
                <span>Service Cost:</span>
                <span>${formatCurrency(totalServiceCost)}</span>
            </div>
            <div class="summary-item">
                <span>Service Charge:</span>
                <span>${formatCurrency(SERVICE_CHARGE)}</span>
            </div>`;

                // Add registration fee if not registered
                if (!userIsRegistered) {
                    summaryHTML += `
                <div class="summary-item" style="border-top: 2px dashed #ffc107;">
                    <span><strong class="text-warning">Registration Fee*:</strong></span>
                    <span><strong class="text-warning">${formatCurrency(REGISTRATION_FEE)}</strong></span>
                </div>`;
                }

                summaryHTML += `
            <div class="summary-item">
                <span><strong>Total Amount:</strong></span>
                <span><strong>${formatCurrency(grandTotal)}</strong></span>
            </div>`;

                // Add note about registration
                if (!userIsRegistered) {
                    summaryHTML += `
                <div class="alert alert-warning mt-3 mb-0 p-2">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        *Registration fee of ₦10,000 required for service activation. 
                        This is a yearly fee for complete peace of mind service.
                    </small>
                </div>`;
                }

                summaryContent.innerHTML = summaryHTML;
            }

            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className =
                    `alert alert-${type === 'error' ? 'danger' : 'info'} alert-dismissible fade show`;
                notification.style.cssText =
                    'position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px;';
                notification.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

                // Add to body
                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }
        });
    </script>
@endsection
