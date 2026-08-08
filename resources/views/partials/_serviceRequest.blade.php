<div class="container">
    <!-- Progress bar -->
    <div class="progress mt-4">
        <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
            aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <form action="{{ route('services.submitRequest', ['id' => $service->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <!-- Step 1: Important questions -->
        <div class="form-step active important-questions">

            <h5 class="mt-3">Important Question!</h5>
            <span>Please select Yes or No</span>
            <hr>

            <!-- Is this form on behalf of someone else? -->
            <div class="form-group">
                <label class="text-danger">Is this Order on behalf of someone else? <span
                        class="text-danger">*</span></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="onBehalfYes" name="on_behalf" value="yes"
                        required>
                    <label class="form-check-label" for="onBehalfYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="onBehalfNo" name="on_behalf" value="no"
                        required>
                    <label class="form-check-label" for="onBehalfNo">No</label>
                </div>

                <!-- Instructional Note -->
                <p id="instructionNote" class="mt-2 text-danger"></p>
                <p class="text-danger mt-2 error-message"></p> <!-- Inline error message -->
            </div>

            <hr hidden>

            <!-- Service type -->
            <div class="form-group" hidden>
                <h5 for="applicant_type">Preferred Service Type<span class="text-danger">*</span></h5>
                <select class="form-control" id="applicant_type" name="applicant_type" required>
                    <option value="">Select Staff Type</option>
                    <option value="live-in">Live In</option>
                    <option value="live-out" selected>Live Out</option>
                    <!-- <option value="no_preference">No Preference</option> -->
                </select>
            </div>
            {{-- <div >
                <div class="col-md-12 py-3">
                    <div class="form-group">
                        <h5 for="applicant_type">Preferred Service Type - Can't be changed</h5>
                        <input type="text" class="form-control" name="applicant_type" id="applicant_type" value="live-out"
                            readonly>
                    </div>
                </div>
            </div> --}}

            <hr>
            <button type="button" class="btn btn-primary next-step">Next</button>
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
    // <!-- ID Card Upload with Image Preview -->
    // Make images clickable to open file dialog
    document.getElementById("passport_preview").onclick = function() {
        document.getElementById("passport").click();
    };

    document.getElementById("id_card_preview").onclick = function() {
        document.getElementById("id_card").click();
    };

    // Preview the selected image
    function previewImage(event, previewId) {
        const preview = document.getElementById(previewId);
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.style.display = "none";
        }
    }
</script>

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
