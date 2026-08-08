<div class="container">
    <!-- Progress bar -->
    <div class="progress">
        <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
            aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <form id="progressiveForm" action="/apply-nanny-housekeeping" method="POST">
        @csrf
        <!-- Step 1: Personal Information -->
        <div class="form-step active">
            <h5 class="mt-3">Personal Information</h5>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 2: Package Information -->
        <div class="form-step">
            <h5 class="mt-3">Package Information</h5>
            <hr>
            <div class="form-group">
                <label for="state">Select State</label>
                <select class="form-control" id="state" name="state" required>
                    <option value="">Choose Your State</option>
                    @include('partials._states')
                </select>
            </div>
            <div class="form-group">
                <label for="address">Home Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 3: Choose Service Days -->
        <div class="form-step">
            <h5 class="mt-3">Choose Service Days</h5>
            <hr>
            <div class="row">
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
                </div>
                <div class="col-6 d-flex flex-column align-items-center">
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
                </div>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>

        <!-- Step 4: Review & Submit -->
        <div class="form-step">
            <h5 class="mt-3">Review & Submit</h5>
            <hr>
            <p>Please review your information before submitting.</p>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<!-- JavaScript for Progress Bar and Navigation -->
<script>
    const formSteps = document.querySelectorAll('.form-step');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const progressBar = document.getElementById('progress-bar');
    let currentStep = 0;

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < formSteps.length - 1) {
                formSteps[currentStep].classList.remove('active');
                currentStep++;
                formSteps[currentStep].classList.add('active');
                updateProgressBar();
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 0) {
                formSteps[currentStep].classList.remove('active');
                currentStep--;
                formSteps[currentStep].classList.add('active');
                updateProgressBar();
            }
        });
    });

    function updateProgressBar() {
        const progress = (currentStep / (formSteps.length - 1)) * 100;
        progressBar.style.width = progress + '%';
    }

    updateProgressBar(); // Initialize progress bar on page load
</script>

<style>
    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .progress {
        height: 20px;
        margin-bottom: 20px;
    }

    .progress-bar {
        transition: width 0.3s;
    }
</style>

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
