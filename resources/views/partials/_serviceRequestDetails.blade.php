<!-- Step 4: Choose Package -->
@if (!Route::is('multiple'))
    <div class="form-step package-details">
        <h5 class="mt-3">Choose Package</h5>
        @if (!Route::is('multiple'))
            <p>Hours of Work: <span id="hoursOfWork">{{ $service->hours_of_work }}</span> hours</p>
        @else
            <p hidden>Total Hours of Work: <span id="totalHours">0</span> hours</p>
            <!-- Display Total Working Hours -->
        @endif

        <hr>

        <!-- Select Package -->
        <div class="form-group">
            <label for="package">Choose a Package</label>
            <select class="form-control" id="package" name="package" required>
                <option value="" data-cost="" data-percentage="0" data-days="" data-hours-of-work="">Choose a
                    Package</option>
                @foreach ($packages as $package)
                    @php
                        $packageCost = $servicePackageCosts->firstWhere('name', $package->name);
                    @endphp
                    <option value="{{ $package->name }}" data-cost="{{ $packageCost->cost ?? '0.00' }}"
                        data-percentage="{{ $package->percentage }}" data-days="{{ $package->days_per_week }}"
                        data-hours-of-work="{{ $service->hours_of_work }}"
                        data-working-hours-start="{{ json_encode([
                            $package->working_hours_start_1,
                            $package->working_hours_start_2,
                            $package->working_hours_start_3,
                            $package->working_hours_start_4,
                            $package->working_hours_start_5,
                        ]) }}">
                        {{ $package->name }} - {{ $package->days_per_week }}
                        {{ $package->days_per_week > 1 ? 'days' : 'day' }}
                    </option>
                @endforeach
            </select>
        </div>

        @if (!empty($service) && $service->base_cost)
            <!-- Display Calculated Cost -->
            <div id="calculatedCostDisplay" style="display: none;">
                <small>Calculated Cost based on the selected package:</small>
                <strong id="calculatedCost">0.00</strong>
            </div>
            <input type="number" name="package_cost" value="{{ $service->base_cost }}" required readonly
                style="display: none;">
        @endif

        <div class="form-group" style="display: none;">
            <label for="days_per_week">Days Per Week</label>
            <input type="number" class="form-control" id="days_per_week" name="days_per_week" readonly required>
        </div>

        <div class="form-group" style="display: none;">
            <label for="percentage">Discount Percentage</label>
            <input type="text" class="form-control" id="percentage" name="percentage" readonly required>
        </div>

        <!-- Choose Days for Service -->
        <div class="form-group">
            <span class="text-danger" id="countdown">Choose Days for Service<span class="text-danger">*</span></span>
            <div class="row justify-content-center">
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="monday"
                            name="service_days[]" value="Monday">
                        <label class="form-check-label" for="monday">Monday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="tuesday"
                            name="service_days[]" value="Tuesday">
                        <label class="form-check-label" for="tuesday">Tuesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="wednesday"
                            name="service_days[]" value="Wednesday">
                        <label class="form-check-label" for="wednesday">Wednesday</label>
                    </div>
                </div>
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="thursday"
                            name="service_days[]" value="Thursday">
                        <label class="form-check-label" for="thursday">Thursday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="friday"
                            name="service_days[]" value="Friday">
                        <label class="form-check-label" for="friday">Friday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input day-checkbox" type="checkbox" id="saturday"
                            name="service_days[]" value="Saturday">
                        <label class="form-check-label" for="saturday">Saturday</label>
                    </div>
                    <div class="form-check" style="display: none;">
                        <input class="form-check-input day-checkbox" type="checkbox" id="sunday"
                            name="service_days[]" value="Sunday">
                        <label class="form-check-label" for="sunday">Sunday</label>
                    </div>
                </div>
            </div>
            <small id="day-selection-message" class="text-info mt-2"></small>
            <p class="text-danger mt-2 error-message"></p>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const packageSelect = document.getElementById("package");
                const daysPerWeekInput = document.querySelector("input[name='days_per_week']");
                const percentageInput = document.querySelector("input[name='percentage']");
                const dayCheckboxes = document.querySelectorAll(".day-checkbox");
                const daySelectionMessage = document.getElementById("day-selection-message");
                const errorMessage = document.querySelector(".form-group .error-message");

                let allowedDays = 0;
                let selectedDays = 0;

                packageSelect.addEventListener("change", function() {
                    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                    allowedDays = parseInt(selectedOption.getAttribute("data-days"), 10) || 0;

                    // Update hidden inputs
                    if (daysPerWeekInput) daysPerWeekInput.value = allowedDays;
                    if (percentageInput) percentageInput.value = parseFloat(selectedOption.getAttribute(
                        "data-percentage")) || 0;

                    // Reset day selection
                    dayCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                        checkbox.disabled = false;
                    });
                    selectedDays = 0;

                    updateDaySelectionMessage();
                });

                dayCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        if (this.checked) {
                            if (selectedDays < allowedDays) {
                                selectedDays++;
                            } else {
                                this.checked = false;
                                return;
                            }
                        } else {
                            selectedDays--;
                        }

                        // Disable unchecked boxes if max days selected
                        if (selectedDays === allowedDays) {
                            dayCheckboxes.forEach(cb => {
                                if (!cb.checked) cb.disabled = true;
                            });
                        } else {
                            dayCheckboxes.forEach(cb => {
                                cb.disabled = false;
                            });
                        }

                        updateDaySelectionMessage();
                        validateDaySelection();
                    });
                });

                function updateDaySelectionMessage() {
                    if (allowedDays > 0) {
                        daySelectionMessage.textContent = `Selected ${selectedDays} of ${allowedDays} allowed days`;
                    } else {
                        daySelectionMessage.textContent = "Please select a package first";
                    }
                }

                function validateDaySelection() {
                    if (errorMessage) {
                        if (selectedDays < allowedDays) {
                            errorMessage.textContent = `Please select exactly ${allowedDays} days`;
                        } else {
                            errorMessage.textContent = "";
                        }
                    }
                }

                // Initialize form validation for the next button
                const nextButtons = document.querySelectorAll(".next-step");
                nextButtons.forEach(button => {
                    button.addEventListener("click", function() {
                        if (selectedDays !== allowedDays) {
                            if (errorMessage) {
                                errorMessage.textContent =
                                    `Please select exactly ${allowedDays} days before proceeding`;
                            }
                            return false;
                        }
                    });
                });
            });
        </script>

        <!-- Select Working Hours -->
        @if (!Route::is('multiple'))
            <div id="workingHoursContainer" style="display: none;">
                <h6>Available Working Hours</h6>
                <div class="form-group">
                    <select class="form-control" id="workingHours" name="working_hour" required>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
        @endif




        <!-- Cost per package chosen -->
        <div hidden>
            <div class="col-md-12 py-3">
                <div class="form-group">
                    <h5 for="package-cost">Cost of Package</h5>
                    <input type="text" class="form-control" name="cost" id="package-cost" value=""
                        readonly>
                </div>
            </div>
        </div>
        <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

        <br>
        <button type="button" class="btn btn-secondary prev-step">Previous</button>
        <button type="button" id="next" class="btn btn-primary next-step">Next</button>
    </div>
@endif


<!-- Step 5: Extra Days -->
<div class="form-step extra-days">
    <h5 class="mt-3">Selelct gender</h5>
    <hr>

    <!-- Preferred Staff Gender -->
    <div class="form-group">
        <h5 for="gender">Preferred Staff Gender<span class="text-danger">*</span></h5>
        <select class="form-control" id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="no_preference">No Preference</option>
        </select>
    </div>

    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

    <hr>

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" class="btn btn-primary next-step">Next</button>
</div>

@if (!Route::is('multiple'))
    @if (!$service->base_cost)
        <div class="form-step apartment-details">
            <h5 class="mt-3">Apartment Details</h5>
            <hr>
            @foreach ($apartmentdetails as $details)
                <!-- Apartment details -->
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <!-- Apartment name -->
                        <label class="col-8 text-end" for="feature_{{ $details->name }}">
                            {{ ucfirst($details->name) }}
                            <span hidden>(Cost per unit: ₦{{ number_format($details->cost, 2) }})</span>
                        </label>

                        <!-- Input field (associate with cost, not name) -->
                        <div class="col-4 text-start">
                            <input type="number" class="form-control feature-input"
                                id="feature_{{ $details->name }}" name="features[{{ $details->name }}]"
                                min="0" placeholder="Type number" data-cost="{{ $details->cost }}"
                                value="{{ old('features.' . $details->name, isset($features[$details->name]) ? $features[$details->name] : 0) }}"
                                oninput="updateApartmentCost()">
                        </div>
                        <span hidden>Total: ₦<span id="total_{{ $details->name }}">0</span></span>
                    </div>
                </div>
            @endforeach

            <!-- Grand Total Section -->
            <div class="col-md-12">
                <div class="form-group row d-flex align-items-center">
                    <label class="col-5 text-end"><strong>Apartment Total</strong></label>
                    <div class="col-7 text-start">
                        <strong>₦<span id="apartment_total">0</span></strong>
                    </div>
                </div>
            </div>
            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->
            <script>
                // Function to update the grand total
                function updateApartmentCost() {
                    let grandTotal = 0; // Initialize the grand total

                    // Iterate over each input field with the 'feature-input' class
                    document.querySelectorAll('.feature-input').forEach(input => {
                        const quantity = parseFloat(input.value) || 0; // Get the quantity (default to 0 if invalid)
                        const costPerUnit = parseFloat(input.dataset.cost) || 0; // Get the cost per unit

                        // Calculate total cost for the current feature
                        const total = quantity * costPerUnit;

                        // Update the corresponding total span
                        const totalSpan = document.getElementById(`total_${input.id.split('_')[1]}`);
                        if (totalSpan) {
                            totalSpan.textContent = total.toFixed(2); // Display the total with two decimal places
                        }

                        // Add to grand total
                        grandTotal += total;
                    });

                    // Update the grand total in the Apartment Total section
                    document.getElementById('apartment_total').textContent = grandTotal.toFixed(2); // Display grand total
                }

                // Attach event listeners to all inputs
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.feature-input').forEach(input => {
                        input.addEventListener('input', updateApartmentCost);
                    });

                    // Initial calculation on page load
                    updateApartmentCost();
                });
            </script>

            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>
    @endif
@endif


<!-- Step 2: Personal Information -->
<div class="form-step personal-info-section" id="personalInfoSection1">
    <h5 class="mt-3">Personal Information</h5>
    <hr>

    @if (!empty($service) && $service->service_name)
        <input type="hidden" id="serviceName" name="serviceName[]" value="{{ $service->service_name }}">
    @else
        <input type="hidden" id="serviceName" name="serviceName[]" value="Default Service Name">
    @endif



    <!-- Hidden user_id field -->
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

    <!-- First and Last Name -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', Auth::user()->name ?? '') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="{{ old('last_name', Auth::user()->last_name ?? '') }}" required>
            </div>
        </div>
    </div>

    <!-- Email and Phone -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', Auth::user()->email ?? '') }}" required>
                <small id="email-feedback" class="form-text text-danger"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" minlength="11"
                    maxlength="11" pattern="\d{11}" title="Phone number must be exactly 11 digits"
                    value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                <small id="phone-feedback" class="form-text text-danger"></small>
                <small id="phone-note" class="form-text text-muted">Phone number must be 11 digits.</small>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validate email
            document.getElementById('email').addEventListener('blur', function() {
                const email = this.value;
                if (email) {
                    fetch(`/check-availability?type=email&value=${email}`)
                        .then(response => response.json())
                        .then(data => {
                            const feedback = document.getElementById('email-feedback');
                            if (data.exists) {
                                feedback.textContent =
                                    "This email is already in use. If it's your, Please login";
                            } else {
                                feedback.textContent = "";
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Validate phone
            document.getElementById('phone').addEventListener('blur', function() {
                const phone = this.value;
                if (phone) {
                    fetch(`/check-availability?type=phone&value=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            const feedback = document.getElementById('phone-feedback');
                            if (data.exists) {
                                feedback.textContent =
                                    "This phone number is already in use. If it's your, Please login";
                            } else {
                                feedback.textContent = "";
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>


    <script>
        document.getElementById('phone').addEventListener('input', function() {
            const phoneInput = this.value;
            const note = document.getElementById('phone-note');
            if (phoneInput.length < 12) {
                note.textContent =
                    `You have entered ${phoneInput.length} digit(s). Please enter ${11 - phoneInput.length} more digit(s).`;
                note.classList.add('text-danger');
                note.classList.remove('text-success');
            } else if (phoneInput.length === 12 && /^\d+$/.test(phoneInput)) {
                note.textContent = "Phone number is valid.";
                note.classList.add('text-success');
                note.classList.remove('text-danger');
            } else {
                note.textContent = "Phone number must be exactly 11 digits.";
                note.classList.add('text-danger');
                note.classList.remove('text-success');
            }
        });
    </script>


    <!-- Date of Birth -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="dob-month">Date of Birth - Month <span class="text-danger">*</span></label>
                <select class="form-control" id="dob-month" name="dob_month" required>
                    <option value="">Select Month</option>
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}"
                            {{ old('dob_month', Auth::user()->dob_month ?? '') == $month ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="dob-day">Date of Birth - Day <span class="text-danger">*</span></label>
                <select class="form-control" id="dob-day" name="dob_day" required>
                    <option value="">Select Day</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ $i }}"
                            {{ old('dob_day', Auth::user()->dob_day ?? '') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" id="next" class="btn btn-primary next-step">Next</button>
</div>

<!-- Step 3: Contact Information -->
<div class="form-step package-details" id="contactInfoSection1">
    <h5 class="mt-3">Contact Information</h5>
    <hr>
    <!-- Country -->
    <div class="form-group">
        <label for="country_of_residence">Select Country <span class="text-danger">*</span></label>
        <select class="form-control" id="country_of_residence" name="country_of_residence" required>
            <option value="" {{-- {{ old('country_of_residence', Auth::user()->country_of_residence) == '' ? 'selected' : '' }} --}}>
                Choose Country of Residence
            </option>
            @include('partials._countries') <!-- You can include the countries list here if needed -->

        </select>
    </div>



    <!-- State -->
    <div class="form-group">
        <label for="state_of_residence">Select State of Residence <span class="text-danger">*</span></label>
        <select class="form-control" id="state_of_residence" name="state_of_residence" required>
            <option value="" {{-- {{ old('state_of_residence', Auth::user()->state_of_residence) == '' ? 'selected' : '' }} --}}>
                Choose Your State of Residence
            </option>
            @include('partials._states') <!-- You can include the states list here if needed -->

        </select>
    </div>

    <!-- Address and Landmark -->
    <div class="form-group">
        <label for="address">Home Address <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="address" name="address"
            value="{{ old('address', Auth::user()->address ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="landmark">Address Landmark <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="landmark" name="landmark"
            value="{{ old('landmark', Auth::user()->landmark ?? '') }}" required>
    </div>
    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="button" id="next" class="btn btn-primary next-step">Next</button>
</div>


<!-- Step 6: Calculations, Review & Submit -->
<div class="form-step review-submit">
    <h5 class="mt-3">Review & Submit</h5>
    <hr>

    <div class="financial-summary">
        <h6>Financial Summary</h6>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Base Package Cost</th>
                    <td>
                        <span id="base-cost-display">₦{{ number_format($service->base_cost ?? 0, 2) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Extra Days Cost ({{ $service->days_per_week ?? 1 }} days × ₦3,000 × 4 weeks)</th>
                    <td>
                        <span id="extra-days-cost-display">₦0.00</span>
                    </td>
                </tr>
                <tr>
                    <th>Package Total</th>
                    <td>
                        <span id="package-cost-display">₦{{ number_format($service->base_cost ?? 0, 2) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Apartment Features</th>
                    <td>
                        <span id="apartment-cost-display">₦0.00</span>
                    </td>
                </tr>
                <tr>
                    <th>Service Charge</th>
                    <td>
                        <span id="service-charge-display">₦5,000.00</span>
                    </td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td>
                        <h4 id="grand-total-display">₦{{ number_format(($service->base_cost ?? 0) + 5000, 2) }}</h4>
                    </td>
                </tr>
                <tr>
                    <th>Grand Total in Words</th>
                    <td>
                        <h6 id="grand-total-words"></h6>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <input type="hidden" name="details">

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Submission Errors</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorList"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div id="error-message" class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>Please review your information before submitting.</p>
    <p class="text-danger mt-2 error-message"></p>

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Order Now</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Constants from backend logic
    const BASE_COST = {{ $service->base_cost ?? 0 }};
    const EXTRA_DAY_RATE = 3000; // ₦3000 per extra day as per backend
    const WEEKS_IN_MONTH = 4; // 4 weeks in a month as per backend
    const SERVICE_CHARGE = 5000; // Fixed service charge as per backend
    const DEFAULT_DAYS = {{ $service->days_per_week ?? 1 }};

    // DOM elements
    const packageSelect = document.getElementById('package');
    const daysPerWeekInput = document.getElementById('days_per_week');
    const featureInputs = document.querySelectorAll('.feature-input');

    // Initialize calculations
    updateFinancialSummary();

    // Event listeners
    if (packageSelect) {
        packageSelect.addEventListener('change', updateFinancialSummary);
    }

    if (daysPerWeekInput) {
        daysPerWeekInput.addEventListener('change', updateFinancialSummary);
    }

    featureInputs.forEach(input => {
        input.addEventListener('input', updateFinancialSummary);
    });

    function updateFinancialSummary() {
        // Get selected package days (default to package days if not specified)
        const selectedOption = packageSelect?.options[packageSelect.selectedIndex];
        const packageDays = selectedOption ? parseInt(selectedOption.getAttribute('data-days')) : DEFAULT_DAYS;
        const actualDays = daysPerWeekInput ? parseInt(daysPerWeekInput.value) || packageDays : packageDays;

        // Calculate extra days cost (matches backend logic: (days-1) * 3000 * 4)
        const extraDays = Math.max(0, actualDays - 1);
        const extraDaysCost = extraDays * EXTRA_DAY_RATE * WEEKS_IN_MONTH;

        // Calculate apartment features cost (sum of all features)
        let apartmentCost = 0;
        featureInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const cost = parseFloat(input.dataset.cost) || 0;
            apartmentCost += quantity * cost;
        });

        // Calculate totals (matches backend logic exactly)
        const packageTotal = BASE_COST + extraDaysCost;
        const grandTotal = packageTotal + apartmentCost + SERVICE_CHARGE;

        // Format currency with commas and 2 decimal places
        const formatCurrency = (amount) => {
            return '₦' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        };

        // Update display
        document.getElementById('base-cost-display').textContent = formatCurrency(BASE_COST);
        document.getElementById('extra-days-cost-display').textContent = formatCurrency(extraDaysCost);
        document.getElementById('package-cost-display').textContent = formatCurrency(packageTotal);
        document.getElementById('apartment-cost-display').textContent = formatCurrency(apartmentCost);
        document.getElementById('grand-total-display').textContent = formatCurrency(grandTotal);
        document.getElementById('grand-total-words').textContent = numberToWords(Math.floor(grandTotal)) + " Naira Only";
    }

    // Robust number to words conversion (matches backend expectations)
    function numberToWords(num) {
        if (isNaN(num)) return "Zero";
        num = Math.floor(num);
        
        const units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
        const teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", 
                      "Seventeen", "Eighteen", "Nineteen"];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        const scales = ["", "Thousand", "Million", "Billion"];

        if (num === 0) return "Zero";

        function convertLessThanOneThousand(n) {
            if (n === 0) return "";
            if (n < 10) return units[n];
            if (n < 20) return teens[n - 10];
            if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 !== 0 ? " " + units[n % 10] : "");
            return units[Math.floor(n / 100)] + " Hundred" + (n % 100 !== 0 ? " " + convertLessThanOneThousand(n % 100) : "");
        }

        let words = "";
        for (let i = 0; num > 0; i++) {
            if (num % 1000 !== 0) {
                words = convertLessThanOneThousand(num % 1000) + " " + scales[i] + " " + words;
            }
            num = Math.floor(num / 1000);
        }

        return words.trim();
    }
});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const packageSelect = document.getElementById('package');
        const workingHoursContainer = document.getElementById('workingHoursContainer');
        const workingHoursSelect = document.getElementById('workingHours');
        const packageCostInput = document.getElementById('packageCost');

        // Event listener for package selection
        packageSelect.addEventListener('change', function() {
            const selectedOptions = Array.from(packageSelect
                .selectedOptions); // Handle multiple selections

            // Update package cost and working hours
            updatePackageCost(selectedOptions);
            updateWorkingHours(selectedOptions);
        });

        // Function to update package cost
        function updatePackageCost(selectedOptions) {
            if (packageCostInput) {
                let totalCost = 0;

                // Sum up costs of selected packages
                selectedOptions.forEach(option => {
                    const cost = parseFloat(option.getAttribute('data-cost')) || 0;
                    totalCost += cost;
                });

                packageCostInput.value = totalCost > 0 ? totalCost.toFixed(2) : 'N/A';
            }
        }

        // Function to update working hours
        function updateWorkingHours(selectedOptions) {
            // Clear existing options
            workingHoursSelect.innerHTML = '<option value="">Select Working Hours</option>';
            let validWorkingHours = [];

            selectedOptions.forEach(option => {
                const workingHoursStart = JSON.parse(option.getAttribute('data-working-hours-start') ||
                    '[]');
                const hoursOfWork = parseFloat(option.getAttribute('data-hours-of-work'));

                if (workingHoursStart.length > 0 && !isNaN(hoursOfWork)) {
                    // Generate time ranges for valid options
                    workingHoursStart.forEach(start => {
                        if (start) {
                            const [startHour, startMinute] = start.split(':').map(Number);
                            const endHour = startHour + Math.floor(hoursOfWork);
                            const endMinute = startMinute + (hoursOfWork % 1) * 60;

                            // Adjust for overflow
                            const adjustedEndHour = (endHour + Math.floor(endMinute / 60)) % 24;
                            const adjustedEndMinute = endMinute % 60;

                            // Format start and end times
                            const formattedStart = formatTime(startHour, startMinute);
                            const formattedEnd = formatTime(adjustedEndHour, adjustedEndMinute);

                            validWorkingHours.push(`${formattedStart} - ${formattedEnd}`);
                        }
                    });
                }
            });

            // Populate dropdown if there are valid working hours
            if (validWorkingHours.length > 0) {
                validWorkingHours.forEach(timeRange => {
                    const optionElement = document.createElement('option');
                    optionElement.value = timeRange;
                    optionElement.textContent = timeRange;
                    workingHoursSelect.appendChild(optionElement);
                });
                workingHoursContainer.style.display = 'block';
            } else {
                // Hide the working hours dropdown if none are available
                workingHoursContainer.style.display = 'none';
            }
        }

        // Format time as HH:MM AM/PM
        function formatTime(hour, minute) {
            const period = hour >= 12 ? 'PM' : 'AM';
            const adjustedHour = hour % 12 || 12; // Convert to 12-hour format
            return `${String(adjustedHour).padStart(2, '0')}:${String(minute).padStart(2, '0')} ${period}`;
        }
    });
</script>
