@extends('layouts.main')
@section('multiple')
    <section class="about-section" id="about">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Multiple Selection Services</h2>
                        <span class="b-line"></span>
                        <p>
                            At Our House Help, we offer a range of monthly services tailored to meet your household needs:
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-container single-services">
                <div class="about-card">

                    <!-- Card 1: Cleaning & Housekeeping -->
                    <i class="fas fa-list fa-3x mb-3"></i>
                    <!-- Progress bar -->
                    <div class="progress mt-4">
                        <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <form action="{{ route('apply-serviceRequest') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Important questions -->
                        <div class="form-step active extra-days">
                            <h5 class="mt-3">Important Question!</h5>
                            <span>Please select Yes or No</span>
                            <hr>


                            <!-- Is this form on behalf of someone else? -->
                            <div class="form-group">
                                <label class="text-danger">Is this Order on behalf of someone else? <span
                                        class="text-danger">*</span></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="onBehalfYes" name="on_behalf"
                                        value="yes" required>
                                    <label class="form-check-label" for="onBehalfYes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="onBehalfNo" name="on_behalf"
                                        value="no" required>
                                    <label class="form-check-label" for="onBehalfNo">No</label>
                                </div>

                                <!-- Instructional Note -->
                                <p id="instructionNote" class="mt-2 text-danger"></p>
                            </div>

                            <hr hidden>

                            <!-- Preferred Staff Gender -->
                            <div class="form-group" hidden>
                                <h5 for="applicant_type">Preferred Staff Type<span class="text-danger">*</span></h5>
                                <select class="form-control" id="applicant_type" name="applicant_type" required>
                                    <option value="">Select Staff Type</option>
                                    <option value="live-in">Live In</option>
                                    <option value="live-out" selected>Live Out</option>
                                    <!-- <option value="no_preference">No Preference</option> -->
                                </select>
                            </div>

                            <script>
                                // JavaScript to handle the change in radio button selection
                                document.addEventListener('DOMContentLoaded', function() {
                                    const yesRadio = document.getElementById('onBehalfYes');
                                    const noRadio = document.getElementById('onBehalfNo');
                                    const instructionNote = document.getElementById('instructionNote');

                                    // Function to update the instructional note
                                    function updateInstructionNote() {
                                        if (yesRadio.checked) {
                                            instructionNote.textContent =
                                                "Please ensure you have authorization to act on behalf of this person; then proceed with the person's details.";
                                        } else if (noRadio.checked) {
                                            instructionNote.textContent = "Proceed with your details only as this order is for yourself.";
                                        } else {
                                            instructionNote.textContent = ""; // Clear note if neither is selected
                                        }
                                    }

                                    // Event listeners for radio button changes
                                    yesRadio.addEventListener('change', updateInstructionNote);
                                    noRadio.addEventListener('change', updateInstructionNote);
                                });
                            </script>
                            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>


                        <!-- Step 2: Select Services -->
                        <div class="form-step apartment-details">
                            <!-- Select Package -->
                            <div>
                                <h5 class="mt-3">Choose Package</h5>
                                <hr>
                                <div class="form-group">
                                    <label for="package">Choose a Package</label>
                                    <select class="form-control" id="package" name="package" required>
                                        <option value="" data-cost="0.00" data-days="N/A" data-hours-of-work="N/A">
                                            Choose a Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" data-cost="{{ $package->cost ?? '0.00' }}"
                                                data-days="{{ $package->days_per_week }}"
                                                data-hours-of-work="{{ $package->hours_of_work ?? 'N/A' }}"
                                                data-working-hours-start="{{ json_encode([
                                                    $package->working_hours_start_1 ?? 'N/A',
                                                    $package->working_hours_start_2 ?? 'N/A',
                                                    $package->working_hours_start_3 ?? 'N/A',
                                                    $package->working_hours_start_4 ?? 'N/A',
                                                    $package->working_hours_start_5 ?? 'N/A',
                                                ]) }}">
                                                {{ $package->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <script>
                                // Function to handle package selection changes
                                function handlePackageSelection() {
                                    const packageSelect = document.getElementById('package');
                                    const selectedOption = packageSelect.selectedOptions[0];

                                    if (selectedOption) {
                                        // Get package data attributes
                                        const packageCost = selectedOption.getAttribute('data-cost') || '0.00';
                                        const days = selectedOption.getAttribute('data-days') || 'N/A';
                                        const hoursOfWork = selectedOption.getAttribute('data-hours-of-work') || 'N/A';
                                        const workingHoursStart = selectedOption.getAttribute('data-working-hours-start') || '[]';

                                        // Update the respective input fields or display elements
                                        document.getElementById('packageCost').value = packageCost;
                                        document.getElementById('totalDays').textContent = days;
                                        document.getElementById('workingHours').textContent = hoursOfWork;

                                        try {
                                            const workingHours = JSON.parse(workingHoursStart);
                                            document.getElementById('workingHoursStart').textContent = workingHours.join(', ');
                                        } catch (error) {
                                            document.getElementById('workingHoursStart').textContent = 'Invalid data';
                                        }
                                    }
                                }

                                // Attach the function to the 'change' event of the package select dropdown
                                document.getElementById('package').addEventListener('change', handlePackageSelection);
                            </script>

                            <hr>

                            <!-- Select Services Section -->
                            <h5>Select Services</h5>
                            <div class="services-list">
                                @foreach ($allservices as $service)
                                    <div class="service-item">
                                        <!-- Checkbox for Service -->
                                        <input type="checkbox" id="service{{ $service->id }}" name="serviceName[]"
                                            value="{{ $service->id }}" class="service-checkbox"
                                            data-cost="{{ $service->base_cost ?? '0.00' }}"
                                            data-hours-of-work="{{ $service->hours_of_work }}" />
                                        <label for="service{{ $service->id }}">
                                            <i class="{{ $service->icon_class ?? 'fas fa-cogs' }}"></i>
                                            {{ ucfirst($service->service_name) }}
                                        </label>
                                        <span class="working-hours" hidden>Work Hours: {{ $service->hours_of_work }}</span>

                                        <!-- About Service Button -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#serviceModal-{{ $service->id }}">
                                            About Service
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

                                {{-- @foreach ($apartment as $service)
                                    <div class="service-item">
                                        <!-- Checkbox for Service -->
                                        <input type="checkbox" id="service{{ $service->id }}" name="serviceName[]"
                                            value="{{ $service->id }}" class="service-checkbox"
                                            data-cost="{{ $service->base_cost ?? '0.00' }}"
                                            data-hours-of-work="{{ $service->hours_of_work }}" />
                                        <label for="service{{ $service->id }}">
                                            <i class="{{ $service->icon_class ?? 'fas fa-cogs' }}"></i>
                                            {{ ucfirst($service->service_name) }}
                                        </label>
                                        <span class="working-hours" hidden>Work Hours: {{ $service->hours_of_work }}</span>

                                        <!-- About Service Button -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#serviceModal-{{ $service->id }}">
                                            About Service
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
                                @endforeach --}}
                            </div>

                            <!-- Apartment Services Section -->
                            <div id="apartment-details" style="display: none;">
                                <h5 class="mt-3">Apartment Details</h5>
                                <hr>
                                @foreach ($apartmentdetails as $details)
                                    <div class="col-md-12">
                                        <div class="form-group row d-flex align-items-center">
                                            <label class="col-8 text-end" for="feature_{{ $details->name }}">
                                                {{ ucfirst($details->name) }}
                                            </label>
                                            <div class="col-4 text-start">
                                                <input type="number" class="form-control feature-input"
                                                    id="feature_{{ $details->name }}"
                                                    name="features[{{ $details->name }}]" min="0"
                                                    placeholder="Type number" data-cost="{{ $details->cost }}"
                                                    value="{{ old('features.' . $details->name, $features[$details->name] ?? 0) }}"
                                                    oninput="calculateTotal('{{ $details->name }}', {{ $details->cost }})" />
                                            </div>
                                            <span hidden>Total: ₦<span id="total_{{ $details->name }}">0</span></span>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-md-12">
                                    <div class="form-group row d-flex align-items-center">
                                        <label class="col-5 text-end"><strong>Apartment Total</strong></label>
                                        <div class="col-7 text-start">
                                            <strong>₦<span id="grand_total">0</span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cost Details Section -->
                            <div id="cost-details" style="display: none;">
                                <hr>
                                <div class="col-md-12 py-3">
                                    <div class="form-group">
                                        <h5 for="package-cost">Cost of Selected Services</h5>
                                        <input type="text" class="form-control" name="cost" id="package-cost"
                                            value="" readonly />
                                    </div>
                                </div>
                            </div>
                            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->







                            <!-- Choose Days for Service ................................................................ -->
                            <hr>
                            <h5>Select Days for Service</h5>
                            <div class="form-group">
                                <!-- Countdown Display -->
                                <span class="text-danger" id="countdown">Choose Days for Service<span
                                        class="text-danger">*</span></span>
                                <div class="row justify-content-center">
                                    <div class="col-6 d-flex flex-column align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="monday"
                                                name="service_days[]" value="Monday">
                                            <label class="form-check-label" for="monday">Monday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tuesday"
                                                name="service_days[]" value="Tuesday">
                                            <label class="form-check-label" for="tuesday">Tuesday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="wednesday"
                                                name="service_days[]" value="Wednesday">
                                            <label class="form-check-label" for="wednesday">Wednesday</label>
                                        </div>

                                    </div>
                                    <div class="col-6 d-flex flex-column align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="thursday"
                                                name="service_days[]" value="Thursday">
                                            <label class="form-check-label" for="thursday">Thursday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="friday"
                                                name="service_days[]" value="Friday">
                                            <label class="form-check-label" for="friday">Friday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="saturday"
                                                name="service_days[]" value="Saturday">
                                            <label class="form-check-label" for="saturday">Saturday</label>
                                        </div>
                                        <div class="form-check" hidden>
                                            <input class="form-check-input" type="checkbox" id="sunday"
                                                name="service_days[]" value="Sunday">
                                            <label class="form-check-label" for="sunday">Sunday</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Display Total Working Hours ............................................................. -->
                            <hr>
                            <div>
                                <h5>Total Working Hours: <span id="totalWorkingHours">0.00</span> hours</h5>
                            </div>


                            <!-- Display Selected Service Count -->
                            <div class="form-group">
                                <label for="service-count">Selected Services Count: </label>
                                <span id="service-count">0</span>
                            </div>

                            <!-- Display Discount Percentage -->
                            <div class="form-group">
                                <label for="discount-percentage">Discount Percentage: </label>
                                <span id="discount-percentage">0%</span>
                            </div>

                            @if (!Route::is('multiple'))
                                <p>Hours of Work: <span id="hoursOfWork">{{ $service->hours_of_work }}</span> hours</p>
                            @else
                                <p hidden>Total Hours of Work: <span id="totalHours">0</span> hours</p>
                            @endif


                            <hr>

                            <!-- Step 6: Apartment Details -->



                            <!-- Navigation Buttons -->
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" id="next" class="btn btn-primary next-step">Next</button>
                        </div>

                        @include('partials._serviceRequestDetails')
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const checkboxes = document.querySelectorAll('.service-checkbox');
                        const totalWorkingHoursElement = document.getElementById('totalWorkingHours');
                        const selectedServiceCountElement = document.getElementById('service-count'); // For service count
                        const discountPercentageElement = document.getElementById('discount-percentage'); // For discount

                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', calculateTotalHours);
                        });

                        function calculateTotalHours() {
                            let totalWorkingHours = 0;
                            let selectedServices = 0;

                            checkboxes.forEach(checkbox => {
                                if (checkbox.checked) {
                                    const hoursOfWork = parseFloat(checkbox.dataset.hoursOfWork) || 0;

                                    // Include only services with valid work hours
                                    if (hoursOfWork > 0) {
                                        selectedServices++;
                                    }
                                }
                            });

                            // Apply work time adjustments
                            if (selectedServices === 1) {
                                totalWorkingHours = 4; // For 1 service
                            } else if (selectedServices === 2) {
                                totalWorkingHours = 6; // For 2 services
                            } else if (selectedServices >= 3) {
                                totalWorkingHours = 9; // For 3 or more services
                            }

                            // Update total working hours
                            totalWorkingHoursElement.textContent = totalWorkingHours.toFixed(2);

                            // Update selected services count
                            selectedServiceCountElement.textContent = selectedServices;

                            // Update discount percentage (example logic)
                            const discountPercentage = selectedServices >= 3 ? 10 : 0;
                            discountPercentageElement.textContent = `${discountPercentage}%`;
                        }
                    });
                </script>

                {{-- service request and progress bar..................................................................... --}}
                <script>
                    const formSteps = document.querySelectorAll('.form-step'); // All steps
                    const nextButtons = document.querySelectorAll('.next-step'); // Next buttons
                    const prevButtons = document.querySelectorAll('.prev-step'); // Previous buttons
                    const progressBar = document.getElementById('progress-bar'); // Progress bar
                    let currentStep = 0; // Current step index

                    // Update the progress bar
                    function updateProgressBar() {
                        const progress = ((currentStep + 1) / formSteps.length) * 100; // Calculate progress percentage
                        progressBar.style.width = progress + '%'; // Update progress bar width
                        progressBar.setAttribute('aria-valuenow', progress.toFixed(0)); // Update ARIA value
                    }

                    // Validate required fields in the current step
                    function validateStep(stepIndex) {
                        let isValid = true; // Assume the step is valid
                        const currentFields = formSteps[stepIndex].querySelectorAll('[required]'); // All required fields
                        const errorMessages = formSteps[stepIndex].querySelectorAll('.error-message'); // Error message placeholders

                        // Clear existing error messages
                        errorMessages.forEach(error => {
                            error.textContent = '';
                        });

                        // Check each required field
                        currentFields.forEach(field => {
                            if (field.type === 'radio') {
                                // Radio button validation
                                const radioGroup = document.querySelectorAll(`[name="${field.name}"]`);
                                if (![...radioGroup].some(radio => radio.checked)) {
                                    const error = field.closest('.form-group').querySelector('.error-message');
                                    if (error) error.textContent = 'Please select an option.';
                                    isValid = false;
                                }
                            } else if (field.type === 'select-one' && field.value === '') {
                                // Dropdown validation
                                const error = field.closest('.form-group').querySelector('.error-message');
                                if (error) error.textContent = 'Please select an option.';
                                isValid = false;
                            } else if (field.value.trim() === '') {
                                // General text input validation
                                const error = field.closest('.form-group').querySelector('.error-message');
                                if (error) error.textContent = 'This field is required.';
                                isValid = false;
                            }
                        });

                        return isValid;
                    }

                    // Navigate to the next step
                    nextButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            if (validateStep(currentStep)) {
                                // Validation passed
                                if (currentStep < formSteps.length - 1) {
                                    formSteps[currentStep].classList.remove('active'); // Hide current step
                                    currentStep++; // Move to next step
                                    formSteps[currentStep].classList.add('active'); // Show next step
                                    updateProgressBar(); // Update progress bar
                                }
                            } else {
                                alert('Please complete all required fields before proceeding.');
                            }
                        });
                    });

                    // Navigate to the previous step
                    prevButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            if (currentStep > 0) {
                                formSteps[currentStep].classList.remove('active'); // Hide current step
                                currentStep--; // Move to previous step
                                formSteps[currentStep].classList.add('active'); // Show previous step
                                updateProgressBar(); // Update progress bar
                            }
                        });
                    });

                    // Initialize progress bar
                    updateProgressBar();
                </script>

                <script>
                    // Get the radio buttons and submit button
                    const onBehalfYes = document.getElementById('onBehalfYes');
                    const onBehalfNo = document.getElementById('onBehalfNo');
                    const submitBtn = document.getElementById('submitBtn');

                    // Add event listeners to the radio buttons
                    onBehalfYes.addEventListener('change', () => {
                        submitBtn.disabled = false; // Enable button if any radio is selected
                    });

                    onBehalfNo.addEventListener('change', () => {
                        submitBtn.disabled = false; // Enable button if any radio is selected
                    });
                </script>


            </div>
        </div>


        <script>
            // Declare variables in the global scope to use them in the external script
            window.Laravel = {
                csrfToken: '{{ csrf_token() }}',
                getDiscountRoute: '{{ route('get-discount') }}'
            };
        </script>
    </section>
@endsection
