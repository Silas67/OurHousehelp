<div class="row">
    <br>

    <!-- Guarantor 1 Card -->
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Guarantor 1</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('applicant.submitGuarantor1') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_first_name" class="form-label">First Name:</label>
                            <input type="text" name="guarantor1_first_name" id="guarantor1_first_name" class="form-control"
                                placeholder="Enter First Name" required
                                value="{{ old('guarantor1_first_name', $guarantor1->guarantor1_first_name ?? '') }}">
                        </div>

                        <input type="hidden" name="guarantor1_number" value="1">

                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_last_name" class="form-label">Last Name:</label>
                            <input type="text" name="guarantor1_last_name" id="guarantor1_last_name" class="form-control"
                                placeholder="Enter Last Name" required
                                value="{{ old('guarantor1_last_name', $guarantor1->guarantor1_last_name ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_phone" class="form-label">Phone Number:</label>
                            <input type="tel" name="guarantor1_phone" id="guarantor1_phone" class="form-control"
                                placeholder="Enter Phone Number" required maxlength="11" minlength="11"
                                value="{{ old('guarantor1_phone', $guarantor1->guarantor1_phone ?? '') }}">
                            <div id="guarantor1-phone-error" class="text-danger mt-2" style="display: none;"></div>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_email" class="form-label">Email (Optional):</label>
                            <input type="email" name="guarantor1_email" id="guarantor1_email" class="form-control"
                                placeholder="Enter Email Address" value="{{ old('guarantor1_email', $guarantor1->guarantor1_email ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_relationship" class="form-label">Relationship:</label>
                            <input type="text" name="guarantor1_relationship" id="guarantor1_relationship" class="form-control"
                                placeholder="Enter Relationship" required
                                value="{{ old('guarantor1_relationship', $guarantor1->guarantor1_relationship ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="guarantor1_occupation" class="form-label">Occupation:</label>
                            <input type="text" name="guarantor1_occupation" id="guarantor1_occupation" class="form-control"
                                placeholder="Enter Occupation" required
                                value="{{ old('guarantor1_occupation', $guarantor1->guarantor1_occupation ?? '') }}">
                        </div>
                    </div>
                
                    <div class="row">
                         <div class="col-md-8 mb-3">
                        <label for="guarantor1_address" class="form-label">Address:</label>
                        <input type="text" name="guarantor1_address" id="guarantor1_address" class="form-control"
                            placeholder="Enter Full Address" required
                            value="{{ old('guarantor1_address', $guarantor1->guarantor1_address ?? '') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                        <label for="guarantor1_nin" class="form-label">NIN:</label>
                        <input type="text" name="guarantor1_nin" id="guarantor1_nin" class="form-control"
                            placeholder="Enter National Identification Number" required
                            value="{{ old('guarantor1_nin', $guarantor1->guarantor1_nin ?? '') }}" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                        </div>

                    </div>
                   

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="guarantor1_passport" class="form-label">Passport Photo:</label>
                            <img id="guarantor1-passport-preview"
                                src="{{ old('guarantor1_passport_path', isset($guarantor1->guarantor1_passport_path) ? \App\Support\SecureDocument::url('passports', $guarantor1->guarantor1_passport_path) : asset('build/assets/image/slider1.jpg')) }}"
                                alt="Passport Photo" class="img-thumbnail d-block mb-2"
                                style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                            <input type="file" name="guarantor1_passport_path" id="guarantor1_passport" class="form-control"
                                accept="image/*"
                                onchange="previewImage('guarantor1_passport', 'guarantor1-passport-preview')">
                        </div>

                        <div class="col-md-6">
                            <label for="guarantor1_signature" class="form-label">ID Card:</label>
                            <img id="guarantor1-signature-preview"
                                src="{{ old('guarantor1_signature_path', isset($guarantor1->guarantor1_signature_path) ? \App\Support\SecureDocument::url('signatures', $guarantor1->guarantor1_signature_path) : asset('build/assets/image/slider1.jpg')) }}"
                                alt="ID Card" class="img-thumbnail d-block mb-2"
                                style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                            <input type="file" name="guarantor1_signature_path" id="guarantor1_signature"
                                class="form-control" accept="image/*"
                                onchange="previewImage('guarantor1_signature', 'guarantor1-signature-preview')">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        @if ($user->guarantors_forms_uploaded)
                            Submitted
                        @else
                            Submit Guarantor 1
                        @endif
                    </button>
                </form>

                {{-- @if ($user->guarantors_forms_uploaded)
                    <a href="{{ route('applicant.downloadGuarantor1Pdf', ['user_id' => $applicant->id]) }}"
                        class="btn btn-success mt-3 w-100">Download Confirmation PDF</a>
                @endif --}}
            </div>
        </div>
    </div>

    <br>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const guarantor1PhoneInput = document.getElementById("guarantor1_phone");
        const guarantor1PhoneError = document.getElementById("guarantor1-phone-error");

        // Event listener for input validation
        guarantor1PhoneInput.addEventListener("input", function() {
            const phoneValue = guarantor1PhoneInput.value;

            // Check if the input length is exactly 11 digits
            if (phoneValue.length > 11 || phoneValue.length < 11) {
                guarantor1PhoneError.style.display = "block";
                guarantor1PhoneError.textContent = "Phone number must be exactly 11 digits.";
            } else {
                guarantor1PhoneError.style.display = "none";
            }

            // Prevent typing non-numeric characters
            if (!/^\d*$/.test(phoneValue)) {
                guarantor1PhoneInput.value = phoneValue.replace(/\D/g,
                ""); // Remove non-numeric characters
            }
        });
    });
</script>
