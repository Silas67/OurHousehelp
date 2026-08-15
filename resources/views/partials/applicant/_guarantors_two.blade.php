<div class="row">
    <br>

    <!-- Repeat similar design for Guarantor 2 -->

    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Guarantor 2</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('applicant.submitGuarantor2') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_first_name" class="form-label">First Name:</label>
                            <input type="text" name="guarantor2_first_name" id="guarantor2_first_name" class="form-control"
                                placeholder="Enter First Name" required
                                value="{{ old('guarantor2_first_name', $guarantor2->guarantor2_first_name ?? '') }}">
                        </div>

                        <input type="hidden" name="guarantor2_number" value="2">

                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_last_name" class="form-label">Last Name:</label>
                            <input type="text" name="guarantor2_last_name" id="guarantor2_last_name" class="form-control"
                                placeholder="Enter Last Name" required
                                value="{{ old('guarantor2_last_name', $guarantor2->guarantor2_last_name ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_phone" class="form-label">Phone Number:</label>
                            <input type="tel" name="guarantor2_phone" id="guarantor2_phone" class="form-control"
                                placeholder="Enter Phone Number" required maxlength="11" minlength="11"
                                value="{{ old('guarantor2_phone', $guarantor2->guarantor2_phone ?? '') }}">
                            <div id="guarantor2-phone-error" class="text-danger mt-2" style="display: none;"></div>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_email" class="form-label">Email (Optional):</label>
                            <input type="email" name="guarantor2_email" id="guarantor2_email" class="form-control"
                                placeholder="Enter Email Address" value="{{ old('guarantor2_email', $guarantor2->guarantor2_email ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_relationship" class="form-label">Relationship:</label>
                            <input type="text" name="guarantor2_relationship" id="guarantor2_relationship" class="form-control"
                                placeholder="Enter Relationship" required
                                value="{{ old('guarantor2_relationship', $guarantor2->guarantor2_relationship ?? '') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="guarantor2_occupation" class="form-label">Occupation:</label>
                            <input type="text" name="guarantor2_occupation" id="guarantor2_occupation" class="form-control"
                                placeholder="Enter Occupation" required
                                value="{{ old('guarantor2_occupation', $guarantor2->guarantor2_occupation ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                        <label for="guarantor2_address" class="form-label">Address:</label>
                        <input type="text" name="guarantor2_address" id="guarantor2_address" class="form-control"
                            placeholder="Enter Full Address" required
                            value="{{ old('guarantor2_address', $guarantor2->guarantor2_address ?? '') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                        <label for="guarantor2_nin" class="form-label">NIN:</label>
                        <input type="text" name="guarantor2_nin" id="guarantor2_nin" class="form-control"
                            placeholder="Enter National Identification Number" required
                            value="{{ old('guarantor2_nin', $guarantor2->guarantor2_nin ?? '') }}" minlength="11" maxlength="11" oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                        </div>
                        
                    </div>
                    

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="guarantor2_passport" class="form-label">Passport Photo:</label>
                            <img id="guarantor2-passport-preview"
                                src="{{ old('guarantor2_passport_path', isset($guarantor2->guarantor2_passport_path) ? asset('build/assets/image/passports/' . $guarantor2->guarantor2_passport_path) : asset('build/assets/image/slider1.jpg')) }}"
                                alt="Passport Photo" class="img-thumbnail d-block mb-2"
                                style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                            <input type="file" name="guarantor2_passport_path" id="guarantor2_passport" class="form-control"
                                accept="image/*"
                                onchange="previewImage('guarantor2_passport', 'guarantor2-passport-preview')">
                        </div>

                        <div class="col-md-6">
                            <label for="guarantor2_signature" class="form-label">ID Card:</label>
                            <img id="guarantor2-signature-preview"
                                src="{{ old('guarantor2_signature_path', isset($guarantor2->guarantor2_signature_path) ? asset('build/assets/image/signatures/' . $guarantor2->guarantor2_signature_path) : asset('build/assets/image/slider1.jpg')) }}"
                                alt="ID Card" class="img-thumbnail d-block mb-2"
                                style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                            <input type="file" name="guarantor2_signature_path" id="guarantor2_signature"
                                class="form-control" accept="image/*"
                                onchange="previewImage('guarantor2_signature', 'guarantor2-signature-preview')">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        @if ($user->guarantors_forms_uploaded_2)
                            Submitted
                        @else
                            Submit Guarantor 2
                        @endif
                    </button>
                </form>

                {{-- @if ($user->guarantors_forms_uploaded_2)
                    <a href="{{ route('applicant.downloadGuarantor2Pdf', ['user_id' => $applicant->id]) }}"
                        class="btn btn-success mt-3 w-100">Download Confirmation PDF</a>
                @endif --}}
            </div>
        </div>
    </div>

</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const guarantor2PhoneInput = document.getElementById("guarantor2_phone");
        const guarantor2PhoneError = document.getElementById("guarantor2-phone-error");

        // Event listener for input validation
        guarantor2PhoneInput.addEventListener("input", function() {
            const phoneValue = guarantor2PhoneInput.value;

            // Check if the input length is exactly 11 digits
            if (phoneValue.length > 11 || phoneValue.length < 11) {
                guarantor2PhoneError.style.display = "block";
                guarantor2PhoneError.textContent = "Phone number must be exactly 11 digits.";
            } else {
                guarantor2PhoneError.style.display = "none";
            }

            // Prevent typing non-numeric characters
            if (!/^\d*$/.test(phoneValue)) {
                guarantor2PhoneInput.value = phoneValue.replace(/\D/g, ""); // Remove non-numeric characters
            }
        });
    });
</script>
