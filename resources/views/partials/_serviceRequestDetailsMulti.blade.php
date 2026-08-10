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

        <!-- Select Package ............................................ working -->
        <div class="form-group">
            <label for="package">Choose a Package</label>
            <select class="form-control" id="package" name="package" required>
                <option value="" data-cost="" data-days="" data-hours-of-work="">Choose a Package</option>
                @foreach ($packages as $package)
                    @php
                        $packageCost = $servicePackageCosts->firstWhere('name', $package->name);
                    @endphp
                    <option value="{{ $package->name }}" data-cost="{{ $packageCost->cost ?? '0.00' }}"
                        data-days="{{ $package->days_per_week }}"
                        data-hours-of-work="{{ $service->hours_of_work }}"
                        data-working-hours-start="{{ json_encode([
                            $package->working_hours_start_1,
                            $package->working_hours_start_2,
                            $package->working_hours_start_3,
                            $package->working_hours_start_4,
                            $package->working_hours_start_5,
                        ]) }}">
                        {{ $package->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <!-- Package Cost -->
        @if (!Route::is('multiple'))
            @if (!empty($service) && !$service->number_of_apartment)
                <div class="form-group">
                    <label for="packageCost">Package Cost</label>
                    <input type="text" id="packageCost" class="form-control" name="package_cost" readonly
                        value="{{ old('package_cost', $calculatedCost ?? 0) }}">
                </div>
                {{-- @else
        <p>No specific service selected.</p> --}}
            @endif
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.service-checkbox');
                const totalHoursElement = document.getElementById('totalHours');
                const packageCostInput = document.getElementById('packageCost');

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', calculateTotalHoursAndCost);
                });

                function calculateTotalHoursAndCost() {
                    let totalHours = 0;
                    let selectedServices = 0;
                    let packageCost = 0;
                    let isValidServiceSelected = false;

                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            const hoursOfWork = parseInt(checkbox.value);
                            const isNumberOfApartment = checkbox.dataset.numberOfApartment ===
                                'false'; // Ensure the data attribute reflects PHP condition

                            // Include only valid services
                            if (hoursOfWork > 0 && isNumberOfApartment) {
                                totalHours += hoursOfWork;
                                selectedServices++;
                                isValidServiceSelected = true; // Mark as valid service selected
                            }
                        }
                    });

                    // Apply work time adjustments
                    if (selectedServices === 1) {
                        totalHours = 4; // Adjust for 1 selection
                        packageCost = 0; // Example package cost for 1 selection
                    } else if (selectedServices === 2) {
                        totalHours = 6; // Adjust for 2 selections
                        packageCost = 0; // Example package cost for 2 selections
                    } else if (selectedServices >= 3) {
                        totalHours = 9; // Adjust for 3 or more selections
                        packageCost = 0; // Example package cost for 3 or more selections
                    }

                    // Update total hours and package cost display
                    totalHoursElement.textContent = totalHours.toFixed(2);
                    packageCostInput.value = isValidServiceSelected ? packageCost.toFixed(2) :
                        0; // Show 0 if no valid service is selected
                }
            });
        </script>


        <!-- Choose Days for Service ................................................................ -->
        <div class="form-group">
            <!-- Countdown Display -->
            <span class="text-danger" id="countdown">Choose Days for Service<span class="text-danger">*</span></span>
            <div class="row justify-content-center">
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="monday" name="service_days[]"
                            value="Monday">
                        <label class="form-check-label" for="monday">Monday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tuesday" name="service_days[]"
                            value="Tuesday">
                        <label class="form-check-label" for="tuesday">Tuesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="wednesday" name="service_days[]"
                            value="Wednesday">
                        <label class="form-check-label" for="wednesday">Wednesday</label>
                    </div>

                </div>
                <div class="col-6 d-flex flex-column align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="thursday" name="service_days[]"
                            value="Thursday">
                        <label class="form-check-label" for="thursday">Thursday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="friday" name="service_days[]"
                            value="Friday">
                        <label class="form-check-label" for="friday">Friday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="saturday" name="service_days[]"
                            value="Saturday">
                        <label class="form-check-label" for="saturday">Saturday</label>
                    </div>
                    <div class="form-check" hidden>
                        <input class="form-check-input" type="checkbox" id="sunday" name="service_days[]"
                            value="Sunday">
                        <label class="form-check-label" for="sunday">Sunday</label>
                    </div>
                </div>
            </div>
        </div>


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
    @if (!empty($service) && $service->number_of_apartment)
        <!-- Step 6: Apartment Details -->
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
                                oninput="calculateTotal('{{ $details->name }}', {{ $details->cost }})">
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
                        <strong>₦<span id="grand_total">0</span></strong>
                    </div>
                </div>
            </div>
            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

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

<!-- Step 6: Review & Submit -->
{{-- <div class="form-step review-submit">
    <h5 class="mt-3">Review & Submit</h5>
    <hr>


    <!-- Ground-Total -->
    <div class="form-group">
        <label for="total-cost">Ground Total Cost</label>
        <input type="hidden" class="form-control" name="grand_cost" id="total-cost" value="" readonly>
        <h4 id="total-cost-display"></h4> <!-- Display formatted value here -->
    </div>


    <!-- Additional Details -->
    <div class="form-group">
        <label for="details">Additional Details (Optional)</label>
        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
    </div>



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
    <!-- Error Message Placeholder - For Overall Errors -->
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
    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->


    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="submit" id="next" class="btn btn-primary btn-block" id="submitBtn">Order Now</button>

</div> --}}

<!-- Step 6: Review & Submit -->
{{-- <div class="form-step review-submit">
    <h5 class="mt-3">Review & Submit</h5>
    <hr>

    <!-- Financial Summary -->
    <div class="financial-summary">
        <h6>Financial Summary</h6>

        <!-- Total Service Cost -->
        <div class="form-group">
            <label for="total-service-cost">Total Service Cost</label>
            <input type="hidden" class="form-control" name="total_service_cost" id="total-service-cost" value="{{ $totalServiceCost }}" readonly>
            <h4 id="total-service-cost-display">₦{{ number_format($totalServiceCost, 2) }}</h4>

        </div>

        <!-- Discount Applied -->
        <div class="form-group">
            <label for="discount-percent">Discount Applied</label>
            <input type="hidden" class="form-control" name="discount_percent" id="discount-percent" value="{{ $discountPercent * 100 }}" readonly>
            <h4 id="discount-percent-display">{{ $discountPercent * 100 }}%</h4>
        </div>

        <!-- Discounted Cost -->
        <div class="form-group">
            <label for="discounted-cost">Discounted Cost</label>
            <input type="hidden" class="form-control" name="discounted_cost" id="discounted-cost" value="{{ $discountedCost }}" readonly>
            <h4 id="discounted-cost-display">₦{{ number_format($discountedCost, 2) }}</h4>
        </div>

        <!-- Apartment Costs -->
        <div class="form-group">
            <label for="apartment-costs">Apartment Costs</label>
            <input type="hidden" class="form-control" name="apartment_costs" id="apartment-costs" value="{{ $apartmentCosts }}" readonly>
            <h4 id="apartment-costs-display">₦{{ number_format($apartmentCosts, 2) }}</h4>
        </div>

        <!-- Grand Total -->
        <div class="form-group">
            <label for="grand-total">Grand Total</label>
            <input type="hidden" class="form-control" name="grand_cost" id="grand-total" value="{{ $grandTotal }}" readonly>
            <h4 id="grand-total-display">₦{{ number_format($grandTotal, 2) }}</h4>
        </div>
    </div>

    <hr>

    <!-- Additional Details -->
    <div class="form-group">
        <label for="details">Additional Details (Optional)</label>
        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
    </div>

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

    <!-- Error Message Placeholder - For Overall Errors -->
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
    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="submit" id="submitBtn" class="btn btn-primary btn-block">Order Now</button>
</div> --}}


<!-- Step 6: Review & Submit -->
<div class="form-step review-submit">
    <h5 class="mt-3">Review & Submit</h5>
    <hr>

    <!-- sample of  Financial Summary -->
    <div class="financial-summary" hidden>
        <h6>Financial Summary</h6>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Subtotal</th>
                    <td>
                        <input type="hidden" name="subtotal" id="subtotal" value="">
                        <span id="subtotal-display"></span>
                    </td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>
                        <input type="hidden" name="discount" id="discount" value="">
                        <span id="discount-display"></span>
                    </td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td>
                        <input type="hidden" name="tax" id="tax" value="">
                        <span id="tax-display"></span>
                    </td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <td>
                        <input type="hidden" name="grand_cost" id="total-cost" value="" readonly>
                        <h4 id="total-cost-display"></h4> <!-- Display formatted value here -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Additional Details -->
    {{-- <div class="form-group">
        <label for="details">Additional Details (Optional)</label>
        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
    </div> --}}
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

    <!-- Error Message Placeholder - For Overall Errors -->
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
    <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

    <button type="button" class="btn btn-secondary prev-step">Previous</button>
    <button type="submit" id="next" class="btn btn-primary btn-block" id="submitBtn">Order Now</button>
</div>


<!-- // Example calculations (replace with real logic) -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let subtotal = 0; // Example value
        let discount = 0; // Example value
        let tax = 0; // Example value
        let grandTotal = subtotal - discount + tax;

        // Update hidden inputs and display elements
        document.getElementById('subtotal').value = subtotal;
        document.getElementById('subtotal-display').textContent = `₦${subtotal.toFixed(2)}`;

        document.getElementById('discount').value = discount;
        document.getElementById('discount-display').textContent = `₦${discount.toFixed(2)}`;

        document.getElementById('tax').value = tax;
        document.getElementById('tax-display').textContent = `₦${tax.toFixed(2)}`;

        document.getElementById('total-cost').value = grandTotal;
        document.getElementById('total-cost-display').textContent = `₦${grandTotal.toFixed(2)}`;
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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
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
</script> --}}


{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.service-checkbox');
        const totalHoursElement = document.getElementById('totalHours');
        const packageCostInput = document.getElementById('packageCost');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotalHoursAndCost);
        });

        function calculateTotalHoursAndCost() {
            let totalHours = 0;
            let selectedServices = 0;
            let totalCost = 0; // To hold the total cost for services that do not require apartment details
            let isValidServiceSelected = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const hoursOfWork = parseInt(checkbox.value);
                    const isNumberOfApartment = checkbox.dataset.numberOfApartment ===
                    'false'; // Check if service requires apartment

                    // Include only valid services
                    if (hoursOfWork > 0 && isNumberOfApartment) {
                        totalHours += hoursOfWork;
                        selectedServices++;
                        isValidServiceSelected = true; // Mark as valid service selected

                        // Add the cost for services that do not require apartment details
                        const serviceCost = parseFloat(checkbox.dataset.cost || 0);
                        totalCost += serviceCost;
                    }
                }
            });

            // Apply work time adjustments
            if (selectedServices === 1) {
                totalHours = 4; // Adjust for 1 selection
            } else if (selectedServices === 2) {
                totalHours = 6; // Adjust for 2 selections
            } else if (selectedServices >= 3) {
                totalHours = 9; // Adjust for 3 or more selections
            }

            // Update total hours and package cost display
            totalHoursElement.textContent = totalHours.toFixed(2);
            packageCostInput.value = isValidServiceSelected ? totalCost.toFixed(2) :
            0; // Show total cost for services without apartment details
        }
    });
</script> --}}
