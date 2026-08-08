@extends('layouts.main')
@section('multiple')
    @include('partials.modals._ModalforNannyServices')
    @include('partials.modals._ModalforCookServices')
    @include('partials.modals._ModalforCleaningServices')
    @include('partials.modals._ModalforLaundryServices')
    @include('partials.modals._ModalforErrandServices')
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
                <!-- Card 1: Cleaning & Housekeeping -->
                <div class="about-card">

                    <i class="fas fa-list fa-3x mb-3"></i>
                    <!-- Progress bar -->
                    <div class="progress mt-4">
                        <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 0%;"
                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <form action="{{ route('apply-serviceRequest') }}" method="POST" enctype="multipart/form-data">
                        <!-- CSRF Token (required for Laravel) -->
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
                            <h3>Select Services</h3>
                            <!-- Feedback Message -->
                            <div id="service-error" class="text-danger my-2 fw-bold">
                                Please select a service to proceed.
                            </div>


                            <div class="services-list">
                                @foreach ($multipleServices as $service)
                                    <div class="service-item mb-2 p-2 border rounded shadow-sm">
                                        <!-- Radio -->
                                        <input type="radio" id="service{{ $service->id }}" name="serviceName"
                                            value="{{ $service->hours_of_work }}" class="service-radio me-2">
                                        <label for="service{{ $service->id }}" class="d-block">
                                            <span class="d-block fw-bold">
                                                {{ implode(', ', json_decode($service->services)) }}
                                            </span>
                                            <span class="text-muted">
                                                <strong>Cost:</strong> {{ number_format($service->cost, 2) }} |
                                                <strong>Working Hours:</strong> {{ $service->working_hours }} hrs
                                            </span>
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
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title text-white"
                                                        id="serviceModalLabel-{{ $service->id }}">
                                                        Service Details
                                                    </h5>
                                                    <button type="button" class="btn-close text-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 class="text-success fw-bold">Selected Services:</h6>
                                                    <p class="mb-3">
                                                        {{ implode(', ', json_decode($service->services)) }}
                                                    </p>

                                                    <h6 class="text-success fw-bold">Cost:</h6>
                                                    <p class="mb-3">
                                                        N{{ number_format($service->cost, 2) }} (Naira)
                                                    </p>

                                                    <h6 class="text-success fw-bold">Working Hours:</h6>
                                                    <p class="mb-3">
                                                        {{ $service->working_hours }} hrs
                                                    </p>

                                                    <h6 class="text-success fw-bold">Additional Information:</h6>
                                                    <p>
                                                        {{ $service->description ?? 'No additional information provided.' }}
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input hidden type="text" id="packageCost" class="form-control"
                                        name="package_cost" value="{{ $service->cost }}">
                                @endforeach
                            </div>



                            <!-- Navigation Buttons -->
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" id="next" class="btn btn-primary next-step"
                                disabled>Next</button>
                        </div>

                        <script>
                            const serviceRadios = document.querySelectorAll('input[name="serviceName"]');
                            const nextButton = document.getElementById('next');
                            const errorElement = document.getElementById('service-error');

                            // Enable Next button when a radio button is selected
                            serviceRadios.forEach(radio => {
                                radio.addEventListener('change', () => {
                                    nextButton.disabled = false; // Enable the "Next" button
                                    errorElement.style.display = 'none'; // Hide error message
                                });
                            });

                            // Validate on Next button click
                            nextButton.addEventListener('click', function() {
                                const selectedService = document.querySelector('input[name="serviceName"]:checked');
                                if (!selectedService) {
                                    // Show error if no service is selected (just in case)
                                    errorElement.style.display = 'block';
                                    // errorElement.textContent = 'You have not selected any service yet.';
                                    alert('You have not selected any service yet.');
                                }
                            });
                        </script>

                        <!-- Step 4: Choose Package ......................................................-->
                        {{-- <div class="form-step package-details">
                            <h5 class="mt-3">Choose Package</h5>

                            <!-- Select Package ............................................ working -->
                            <div class="form-group">
                                <label for="package">Choose a Package</label>
                                <select class="form-control" id="package" name="package" required>
                                    <option value="" data-cost="" data-days="" data-hours-of-work="">Choose a
                                        Package</option>
                                    @foreach ($packages as $package)
                                        @php
                                            $packageCost = $servicePackageCosts->firstWhere('name', $package->name);
                                        @endphp
                                        <option value="{{ $package->name }}"
                                            data-cost="{{ $packageCost->cost ?? '0.00' }}"
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

                            <!-- Choose Days for Service ................................................................ -->
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


                            <!-- Select Working Hours -->
                            <div>
                                <h6>Available Working Hours</h6>
                                <div class="form-group">
                                    <select class="form-control" id="" name="working_hour" required>
                                        <!-- Options will be populated dynamically for multiple services-->
                                    </select>
                                </div>
                            </div>


                            <!-- Cost per package chosen -->
                            <div hidden>
                                <div class="col-md-12 py-3">
                                    <div class="form-group">
                                        <h5 for="package-cost">Cost of Package</h5>
                                        <input type="text" class="form-control" name="cost" id="package-cost"
                                            value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

                            <br>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" id="next" class="btn btn-primary next-step">Next</button>
                        </div> --}}
                        <div class="form-step package-details">
                            <h5 class="mt-3">Choose Package</h5>

                            <!-- Select Package ............................................ working -->
                            <div class="form-group">
                                <label for="package">Choose a Package</label>
                                <select class="form-control" id="package" name="package" required>
                                    <option value="" data-cost="" data-days="" data-hours-of-work="">Choose a
                                        Package</option>
                                    @foreach ($packages as $package)
                                        @php
                                            $packageCost = $servicePackageCosts->firstWhere('name', $package->name);
                                        @endphp
                                        <option value="{{ $package->name }}"
                                            data-cost="{{ $packageCost->cost ?? '0.00' }}"
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

                            <!-- Choose Days for Service ................................................................ -->
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


                            <!-- Select Working Hours.  when the user chooses the package, here, the
                                                all working_hours_start appear  related to the package choosen -->
                            <div>
                                <h6>Available Working Hours</h6>
                                <div class="form-group">
                                    <select class="form-control" id="working-hours-dropdown" name="working_hour"
                                        required>
                                        <!-- Options will be dynamically populated -->
                                    </select>
                                </div>
                            </div>


                            {{-- <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    const packageSelect = document.getElementById("package");
                                    const workingHoursDropdown = document.getElementById("working-hours-dropdown");

                                    // Function to format time in 12-hour format with AM/PM
                                    function formatTime24To12(time) {
                                        const [hour, minute] = time.split(":").map(Number);
                                        const ampm = hour >= 12 ? "pm" : "am";
                                        const formattedHour = hour % 12 || 12; // Convert 0 to 12 for midnight
                                        return `${formattedHour.toString().padStart(2, "0")}:${minute.toString().padStart(2, "0")}${ampm}`;
                                    }

                                    // Listen for changes in the package selection
                                    packageSelect.addEventListener("change", () => {
                                        // Get the selected option
                                        const selectedOption = packageSelect.options[packageSelect.selectedIndex];

                                        // Clear existing working hours
                                        workingHoursDropdown.innerHTML = "";

                                        // Get the working hours data from the selected option
                                        const workingHoursStart = selectedOption.getAttribute("data-working-hours-start");
                                        const hoursArray = workingHoursStart ? JSON.parse(workingHoursStart) : [];

                                        // Populate the dropdown with formatted working hours
                                        if (hoursArray.length > 0) {
                                            hoursArray.forEach((hour) => {
                                                if (hour) {
                                                    const formattedTime = formatTime24To12(hour);
                                                    const option = document.createElement("option");
                                                    option.value = hour; // Use original value
                                                    option.textContent = formattedTime; // Display formatted value
                                                    workingHoursDropdown.appendChild(option);
                                                }
                                            });
                                        } else {
                                            // Add a default "No working hours available" option
                                            const noHoursOption = document.createElement("option");
                                            noHoursOption.value = "";
                                            noHoursOption.textContent = "No working hours available";
                                            workingHoursDropdown.appendChild(noHoursOption);
                                        }
                                    });
                                });
                            </script> --}}
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    const packageSelect = document.getElementById("package");
                                    const serviceRadios = document.querySelectorAll(".service-radio");
                                    const workingHoursDropdown = document.getElementById("working-hours-dropdown");

                                    // Function to format time in 12-hour format with AM/PM
                                    function formatTime24To12(time) {
                                        const [hour, minute] = time.split(":").map(Number);
                                        const ampm = hour >= 12 ? "PM" : "AM";
                                        const formattedHour = hour % 12 || 12; // Convert 0 to 12 for midnight
                                        return `${formattedHour.toString().padStart(2, "0")}:${minute.toString().padStart(2, "0")} ${ampm}`;
                                    }

                                    // Function to calculate the end time
                                    function calculateEndTime(startTime, hoursToAdd) {
                                        const [hour, minute] = startTime.split(":").map(Number);
                                        const totalMinutes = hour * 60 + minute + Math.round(hoursToAdd * 60);
                                        const endHour = Math.floor(totalMinutes / 60) % 24;
                                        const endMinute = totalMinutes % 60;
                                        const endTime24 = `${endHour.toString().padStart(2, "0")}:${endMinute.toString().padStart(2, "0")}`;
                                        return formatTime24To12(endTime24);
                                    }

                                    // Listen for changes in the package selection
                                    packageSelect.addEventListener("change", () => {
                                        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                                        workingHoursDropdown.innerHTML = ""; // Clear existing options

                                        const workingHoursStart = selectedOption.getAttribute("data-working-hours-start");
                                        const hoursArray = workingHoursStart ? JSON.parse(workingHoursStart) : [];

                                        const selectedService = document.querySelector('input[name="serviceName"]:checked');
                                        const hoursOfWork = selectedService ? parseFloat(selectedService.value) : 0;

                                        // Check if hoursOfWork is valid
                                        if (isNaN(hoursOfWork)) {
                                            const noHoursOption = document.createElement("option");
                                            noHoursOption.value = "";
                                            noHoursOption.textContent = "Please select a service";
                                            workingHoursDropdown.appendChild(noHoursOption);
                                            return;

                                        } else {
                                            // Populate the dropdown with start and end times
                                            if (hoursArray.length > 0) {
                                                hoursArray.forEach((startTime) => {
                                                    if (startTime) {
                                                        const startFormatted = formatTime24To12(startTime);
                                                        const endFormatted = calculateEndTime(startTime, hoursOfWork);
                                                        const option = document.createElement("option");
                                                        option.value = `${startTime}-${endFormatted}`;
                                                        option.textContent = `${startFormatted} - ${endFormatted}`;
                                                        workingHoursDropdown.appendChild(option);
                                                    }
                                                });
                                            } else {
                                                const noHoursOption = document.createElement("option");
                                                noHoursOption.value = "";
                                                noHoursOption.textContent = "No working hours available";
                                                workingHoursDropdown.appendChild(noHoursOption);
                                            }
                                        }

                                    });



                                    // Update working hours dynamically when a service is selected
                                    serviceRadios.forEach((radio) => {
                                        radio.addEventListener("change", () => {
                                            packageSelect.dispatchEvent(new Event(
                                                "change")); // Trigger package change to refresh dropdown
                                        });
                                    });
                                });
                            </script>
                            {{-- <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    const packageSelect = document.getElementById("package");
                                    const serviceRadios = document.querySelectorAll(".service-radio");
                                    const workingHoursDropdown = document.getElementById("working-hours-dropdown");

                                    // Function to format time in 12-hour format with AM/PM
                                    function formatTime24To12(time) {
                                        const [hour, minute] = time.split(":").map(Number);
                                        const ampm = hour >= 12 ? "PM" : "AM";
                                        const formattedHour = hour % 12 || 12; // Convert 0 to 12 for midnight
                                        return `${formattedHour.toString().padStart(2, "0")}:${minute.toString().padStart(2, "0")} ${ampm}`;
                                    }

                                    // Function to calculate the end time
                                    function calculateEndTime(startTime, hoursToAdd) {
                                        const [hour, minute] = startTime.split(":").map(Number);
                                        const totalMinutes = hour * 60 + minute + Math.round(hoursToAdd * 60);
                                        const endHour = Math.floor(totalMinutes / 60) % 24;
                                        const endMinute = totalMinutes % 60;
                                        const endTime24 = `${endHour.toString().padStart(2, "0")}:${endMinute.toString().padStart(2, "0")}`;
                                        return formatTime24To12(endTime24);
                                    }

                                    // Listen for changes in the package selection
                                    packageSelect.addEventListener("change", () => {
                                        const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                                        workingHoursDropdown.innerHTML = ""; // Clear existing options

                                        const workingHoursStart = selectedOption.getAttribute("data-working-hours-start");
                                        const hoursArray = workingHoursStart ? JSON.parse(workingHoursStart) : [];

                                        const selectedService = document.querySelector('input[name="serviceName"]:checked');
                                        const hoursOfWork = selectedService ? parseFloat(selectedService.value) : NaN;

                                        // Check if hoursOfWork is valid
                                        if (isNaN(hoursOfWork)) {
                                            const noHoursOption = document.createElement("option");
                                            noHoursOption.value = "";
                                            noHoursOption.textContent = "Please select a service";
                                            workingHoursDropdown.appendChild(noHoursOption);
                                            return;
                                        }

                                        // Populate the dropdown with start and end times
                                        if (hoursArray.length > 0) {
                                            hoursArray.forEach((startTime) => {
                                                if (startTime) {
                                                    const startFormatted = formatTime24To12(startTime);
                                                    const endFormatted = calculateEndTime(startTime, hoursOfWork);
                                                    const option = document.createElement("option");
                                                    option.value = `${startTime}-${endFormatted}`;
                                                    option.textContent = `${startFormatted} - ${endFormatted}`;
                                                    workingHoursDropdown.appendChild(option);
                                                }
                                            });
                                        } else {
                                            const noHoursOption = document.createElement("option");
                                            noHoursOption.value = "";
                                            noHoursOption.textContent = "No working hours available";
                                            workingHoursDropdown.appendChild(noHoursOption);
                                        }
                                    });

                                    // Update working hours dynamically when a service is selected
                                    serviceRadios.forEach((radio) => {
                                        radio.addEventListener("change", () => {
                                            packageSelect.dispatchEvent(new Event("change")); // Trigger package change to refresh dropdown
                                        });
                                    });
                                });
                            </script> --}}



                            <!-- Cost per package chosen.-->
                            <div hidden>
                                <div class="col-md-12 py-3">
                                    <div class="form-group">
                                        <h5 for="package-cost">Cost of Package</h5>
                                        <input type="text" class="form-control" name="cost" id="package-cost"
                                            value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->

                            <br>
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" id="next" class="btn btn-primary next-step">Next</button>
                        </div>


                        @include('partials._serviceRequestDetails')
                    </form>
                </div>


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
