@extends('layouts.adminmain')



@section('edithousehelp')

    <div class="container-fluid py-4">

        <div class="row">

            <div class="col-12">

                <div class="card mb-4">

                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">Edit House Help Profile</h5>

                        <a href="{{ route('adminapplicants') }}" class="btn btn-light btn-sm">

                            <i class="fas fa-arrow-left me-1"></i> Back to List

                        </a>

                    </div>



                    <div class="card-body">

                        <form method="POST" action="{{ route('admin.HouseHelp.update', $user->id) }}"

                            enctype="multipart/form-data">

                            @csrf

                            @method('PUT')



                            <div class="row">

                                <!-- Left Column -->

                                <div class="col-md-6">

                                    <!-- Profile Section -->

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-user-circle me-2"></i> Profile Information

                                            </h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="text-center mb-4">

                                                <div class="position-relative d-inline-block">

                                                    <img id="profile-preview"

                                                        src="{{ $user->profile_photo_path ? asset('public/build/assets/image/profile_photo/' . $user->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"

                                                        class="rounded-circle shadow" width="150" height="150"

                                                        style="object-fit: cover;">

                                                    <label for="profile_photo_path"

                                                        class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0"

                                                        style="width: 40px; height: 40px;">

                                                        <i class="fas fa-camera"></i>

                                                    </label>

                                                </div>

                                                <input type="file" id="profile_photo_path" name="profile_photo_path"

                                                    class="d-none" accept="image/*">

                                            </div>



                                            <div class="row g-3">

                                                <div class="col-md-4">

                                                    <label for="name" class="form-label">First Name</label>

                                                    <input type="text"

                                                        class="form-control @error('name') is-invalid @enderror"

                                                        id="name" name="name" value="{{ old('name', $user->name) }}"

                                                        required>

                                                    @error('name')

                                                        <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror

                                                </div>



                                                <div class="col-md-4">

                                                    <label for="middle_name" class="form-label">Middle Name</label>

                                                    <input type="text" class="form-control" id="middle_name"

                                                        name="middle_name"

                                                        value="{{ old('middle_name', $user->middle_name) }}">

                                                </div>



                                                <div class="col-md-4">

                                                    <label for="last_name" class="form-label">Last Name</label>

                                                    <input type="text" class="form-control" id="last_name"

                                                        name="last_name" value="{{ old('last_name', $user->last_name) }}">

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="email" class="form-label">Email</label>

                                                    <input type="email"

                                                        class="form-control @error('email') is-invalid @enderror"

                                                        id="email" name="email"

                                                        value="{{ old('email', $user->email) }}" required>

                                                    @error('email')

                                                        <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="phone" class="form-label">Phone</label>

                                                    <input type="text"

                                                        class="form-control @error('phone') is-invalid @enderror"

                                                        id="phone" name="phone"

                                                        value="{{ old('phone', $user->phone) }}">

                                                    @error('phone')

                                                        <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="gender" class="form-label">Gender</label>

                                                    <select class="form-select" id="gender" name="gender">

                                                        <option value="">Select Gender</option>

                                                        <option value="male"

                                                            {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>

                                                            Male</option>

                                                        <option value="female"

                                                            {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>

                                                            Female</option>

                                                        <option value="other"

                                                            {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>

                                                            Other</option>

                                                    </select>

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="dob" class="form-label">Date of Birth</label>

                                                    <input type="date"

                                                        class="form-control @error('dob') is-invalid @enderror"

                                                        id="dob" name="dob"

                                                        value="{{ old('dob', $user->dob ? (is_string($user->dob) ? $user->dob : $user->dob->format('Y-m-d')) : '') }}">

                                                    @error('dob')

                                                        <div class="invalid-feedback">{{ $message }}</div>

                                                    @enderror

                                                </div>



                                                <div class="col-md-12">

                                                    <label for="bio" class="form-label">Bio/Description</label>

                                                    <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- Account Status Section -->

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-cog me-2"></i> Account Status</h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label for="approval_status" class="form-label">Live-Out Approval

                                                        Status</label>

                                                    <select class="form-select" id="approval_status"

                                                        name="approval_status">

                                                        <option value="pending"

                                                            {{ old('approval_status', $user->approval_status) == 'pending' ? 'selected' : '' }}>

                                                            Pending</option>

                                                        <option value="approved"

                                                            {{ old('approval_status', $user->approval_status) == 'approved' ? 'selected' : '' }}>

                                                            Approved</option>

                                                        <option value="rejected"

                                                            {{ old('approval_status', $user->approval_status) == 'rejected' ? 'selected' : '' }}>

                                                            Rejected</option>

                                                    </select>

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="status" class="form-label">Account Status</label>

                                                    <select class="form-select" id="status" name="status">

                                                        <option value="active"

                                                            {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>

                                                            Active</option>

                                                        <option value="inactive"

                                                            {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>

                                                            Inactive</option>

                                                        <option value="archived"

                                                            {{ old('status', $user->status) == 'archived' ? 'selected' : '' }}>

                                                            Archived</option>

                                                    </select>

                                                </div>



                                                <div class="col-md-6">

                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input" type="checkbox" id="availability"

                                                            name="availability" value="1"

                                                            {{ old('availability', $user->availability) ? 'checked' : '' }}>

                                                        <label class="form-check-label" for="availability">Currently

                                                            Available</label>

                                                    </div>

                                                </div>



                                                <div class="col-md-6">

                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input" type="checkbox"

                                                            id="terms_accepted" name="terms_accepted" value="1"

                                                            {{ old('terms_accepted', $user->terms_accepted) ? 'checked' : '' }}>

                                                        <label class="form-check-label" for="terms_accepted">Terms

                                                            Accepted</label>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <!-- Right Column -->

                                <div class="col-md-6">

                                    <!-- Location Information -->

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Location

                                                Information</h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="row g-3">

                                                {{-- <div class="col-md-6">

                                                    <label for="state_of_origin" class="form-label">State of

                                                        Origin</label>

                                                    <input type="text" class="form-control" id="state_of_origin"

                                                        name="state_of_origin"

                                                        value="{{ old('state_of_origin', $user->state_of_origin) }}">

                                                </div> --}}



                                                <div class="col-md-6">

                                                    <label for="state_of_origin" class="form-label">State of

                                                        Origin</label>

                                                    <select class="form-select" id="state_of_origin"

                                                        name="state_of_origin">

                                                        <option value="">Select State</option>

                                                        @php

                                                            $states = [

                                                                'Abia',

                                                                'Adamawa',

                                                                'Akwa Ibom',

                                                                'Anambra',

                                                                'Bauchi',

                                                                'Bayelsa',

                                                                'Benue',

                                                                'Borno',

                                                                'Cross River',

                                                                'Delta',

                                                                'Ebonyi',

                                                                'Edo',

                                                                'Ekiti',

                                                                'Enugu',

                                                                'FCT',

                                                                'Gombe',

                                                                'Imo',

                                                                'Jigawa',

                                                                'Kaduna',

                                                                'Kano',

                                                                'Katsina',

                                                                'Kebbi',

                                                                'Kogi',

                                                                'Kwara',

                                                                'Lagos',

                                                                'Nasarawa',

                                                                'Niger',

                                                                'Ogun',

                                                                'Ondo',

                                                                'Osun',

                                                                'Oyo',

                                                                'Plateau',

                                                                'Rivers',

                                                                'Sokoto',

                                                                'Taraba',

                                                                'Yobe',

                                                                'Zamfara',

                                                            ];

                                                        @endphp

                                                        @foreach ($states as $state)

                                                            <option value="{{ $state }}"

                                                                {{ old('state_of_origin', $user->state_of_origin) == $state ? 'selected' : '' }}>

                                                                {{ $state }}

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="lga_of_origin" class="form-label">LGA of Origin</label>

                                                    <input type="text" class="form-control" id="lga_of_origin"

                                                        name="lga_of_origin"

                                                        value="{{ old('lga_of_origin', $user->lga_of_origin) }}">

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="state_of_residence" class="form-label">State of

                                                        Residence</label>

                                                    <input type="text" class="form-control" id="state_of_residence"

                                                        name="state_of_residence"

                                                        value="{{ old('state_of_residence', $user->state_of_residence) }}">

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="country" class="form-label">Country</label>

                                                    <input type="text" class="form-control" id="country"

                                                        name="country" value="{{ old('country', $user->country) }}">

                                                </div>



                                                <div class="col-md-12">

                                                    <label for="address" class="form-label">Full Address</label>

                                                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>

                                                </div>



                                                <div class="col-md-12">

                                                    <label for="landmark" class="form-label">Landmark</label>

                                                    <input type="text" class="form-control" id="landmark"

                                                        name="landmark" value="{{ old('landmark', $user->landmark) }}">

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- Professional Information -->

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i> Professional

                                                Information</h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label for="applicant_type" class="form-label">Applicant Type</label>

                                                    <select class="form-select" id="applicant_type"

                                                        name="applicant_type">

                                                        <option value="">Select Type</option>

                                                        <option value="live-in"

                                                            {{ old('applicant_type', $user->applicant_type) == 'live-in' ? 'selected' : '' }}>

                                                            Live-In</option>

                                                        <option value="live-out"

                                                            {{ old('applicant_type', $user->applicant_type) == 'live-out' ? 'selected' : '' }}>

                                                            Live-Out</option>

                                                        <option value="semi-live-in"

                                                            {{ old('applicant_type', $user->applicant_type) == 'semi-live-in' ? 'selected' : '' }}>

                                                            Semi Live-In</option>

                                                        <option value="skill"

                                                            {{ old('applicant_type', $user->applicant_type) == 'skill' ? 'selected' : '' }}>

                                                            Skilled Worker</option>

                                                    </select>

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="years_of_experience" class="form-label">Years of

                                                        Experience</label>

                                                    <input type="text" class="form-control" id="years_of_experience"

                                                        name="years_of_experience"

                                                        value="{{ old('years_of_experience', $user->years_of_experience) }}">

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="nin" class="form-label">National ID (NIN)</label>

                                                    <input type="text" class="form-control" id="nin"

                                                        name="nin" value="{{ old('nin', $user->nin) }}">

                                                </div>



                                                <div class="col-md-6">

                                                    <label for="id_card" class="form-label">ID Card</label>

                                                    <input type="file" class="form-control" id="id_card"

                                                        name="id_card">

                                                    @if ($user->id_card)

                                                        <small class="text-muted">Current: <a

                                                                href="{{ \App\Support\SecureDocument::url('idcards', $user->id_card) }}"

                                                                target="_blank">View ID Card</a></small>

                                                    @endif

                                                </div>

                                                @if ($user->account_type === 'applicant' || $user->account_type === 'staff')

                                                    <div class="col-md-12">

                                                        <label for="specialties"

                                                            class="form-label">Specialties/Skills</label>

                                                        <small class="text-muted d-block mb-3">Select more specialties to

                                                            increase your visibility in searches.</small>



                                                        <div class="row g-3">

                                                            @php

                                                                // Safely handle specialties data

                                                                $currentSpecialties = [];



                                                                if (!empty($user->specialties)) {

                                                                    if (is_array($user->specialties)) {

                                                                        $currentSpecialties = $user->specialties;

                                                                    } elseif (is_string($user->specialties)) {

                                                                        $decoded = json_decode(

                                                                            $user->specialties,

                                                                            true,

                                                                        );

                                                                        $currentSpecialties = is_array($decoded)

                                                                            ? $decoded

                                                                            : [];

                                                                    }

                                                                }



                                                                // Ensure it's always an array

                                                                $currentSpecialties = (array) $currentSpecialties;

                                                            @endphp



                                                            @foreach ($services as $service)

                                                                <div class="col-md-4 col-sm-6">

                                                                    <div class="form-check card p-3 h-100">

                                                                        <input class="form-check-input" type="checkbox"

                                                                            name="specialties[]"

                                                                            value="{{ $service->service_name }}"

                                                                            id="specialty_{{ $service->id }}"

                                                                            {{ in_array($service->service_name, $currentSpecialties) ? 'checked' : '' }}>

                                                                        <label class="form-check-label fw-bold"

                                                                            for="specialty_{{ $service->id }}">

                                                                            {{ ucfirst($service->service_name) }}

                                                                        </label>

                                                                    </div>

                                                                </div>

                                                            @endforeach

                                                        </div>



                                                        @error('specialties')

                                                            <div class="invalid-feedback d-block">{{ $message }}</div>

                                                        @enderror

                                                    </div>



                                                    <script>

                                                        document.addEventListener("DOMContentLoaded", function() {

                                                            const form = document.querySelector("form");



                                                            if (form) {

                                                                form.addEventListener("submit", function(event) {

                                                                    const checkboxes = document.querySelectorAll("input[name='specialties[]']:checked");

                                                                    const specialtyError = document.getElementById('specialty-error');



                                                                    if (checkboxes.length === 0) {

                                                                        event.preventDefault();



                                                                        // Create error message if it doesn't exist

                                                                        if (!specialtyError) {

                                                                            const errorDiv = document.createElement('div');

                                                                            errorDiv.id = 'specialty-error';

                                                                            errorDiv.className = 'invalid-feedback d-block';

                                                                            errorDiv.textContent = 'Please select at least one specialty.';



                                                                            const container = document.querySelector('[for="specialties"]').parentNode;

                                                                            container.appendChild(errorDiv);

                                                                        }



                                                                        // Scroll to error

                                                                        document.getElementById('specialty-error').scrollIntoView({

                                                                            behavior: 'smooth',

                                                                            block: 'center'

                                                                        });

                                                                    }

                                                                });

                                                            }

                                                        });

                                                    </script>

                                                @endif

                                            </div>

                                        </div>

                                    </div>



                                    <!-- Form Completion Status -->

                                    <div class="card">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-tasks me-2"></i> Form Completion</h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="row g-3">

                                                <div class="col-md-4">

                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input" type="checkbox"

                                                            id="form_completed" name="form_completed" value="1"

                                                            {{ old('form_completed', $user->form_completed) ? 'checked' : '' }}>

                                                        <label class="form-check-label" for="form_completed">Main Form

                                                            Completed</label>

                                                    </div>

                                                </div>



                                                <div class="col-md-4">

                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input" type="checkbox"

                                                            id="guarantors_forms_uploaded"

                                                            name="guarantors_forms_uploaded" value="1"

                                                            {{ old('guarantors_forms_uploaded', $user->guarantors_forms_uploaded) ? 'checked' : '' }}>

                                                        <label class="form-check-label"

                                                            for="guarantors_forms_uploaded">Guarantor 1 Form</label>

                                                    </div>

                                                </div>



                                                <div class="col-md-4">

                                                    <div class="form-check form-switch">

                                                        <input class="form-check-input" type="checkbox"

                                                            id="guarantors_forms_uploaded_2"

                                                            name="guarantors_forms_uploaded_2" value="1"

                                                            {{ old('guarantors_forms_uploaded_2', $user->guarantors_forms_uploaded_2) ? 'checked' : '' }}>

                                                        <label class="form-check-label"

                                                            for="guarantors_forms_uploaded_2">Guarantor 2 Form</label>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row mt-4">

                                <div class="col-12 text-end">

                                    <button type="submit" class="btn btn-primary px-4">

                                        <i class="fas fa-save me-2"></i> Save Changes

                                    </button>

                                    <a href="{{ route('adminapplicants') }}" class="btn btn-outline-secondary px-4">

                                        <i class="fas fa-times me-2"></i> Cancel

                                    </a>

                                </div>

                            </div>

                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── GUARANTORS SECTION ── --}}
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Guarantors</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            {{-- Guarantor 1 --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2 mb-3">Guarantor 1
                                    @if($guarantor1)
                                        <span class="badge bg-success ms-2">Submitted</span>
                                    @else
                                        <span class="badge bg-danger ms-2">Not Submitted</span>
                                    @endif
                                </h6>
                                @if($guarantor1)
                                    <table class="table table-sm">
                                        <tr><th>Name</th><td>{{ $guarantor1->guarantor1_first_name }} {{ $guarantor1->guarantor1_last_name }}</td></tr>
                                        <tr><th>Phone</th><td>{{ $guarantor1->guarantor1_phone }}</td></tr>
                                        <tr><th>NIN</th><td>{{ $guarantor1->guarantor1_nin ?? 'Not provided' }}</td></tr>
                                        <tr><th>Email</th><td>{{ $guarantor1->guarantor1_email ?? 'N/A' }}</td></tr>
                                        <tr><th>Relationship</th><td>{{ $guarantor1->guarantor1_relationship }}</td></tr>
                                        <tr><th>Occupation</th><td>{{ $guarantor1->guarantor1_occupation }}</td></tr>
                                        <tr><th>Address</th><td>{{ $guarantor1->guarantor1_address }}</td></tr>
                                    </table>
                                    <div class="row mt-2">
                                        @if($guarantor1->guarantor1_passport_path)
                                            <div class="col-6 text-center">
                                                <p class="small text-muted mb-1">Passport Photo</p>
                                                <img src="{{ \App\Support\SecureDocument::url('passports', $guarantor1->guarantor1_passport_path) }}"
                                                    class="img-thumbnail" style="width:120px;height:120px;object-fit:cover;">
                                            </div>
                                        @endif
                                        @if($guarantor1->guarantor1_signature_path)
                                            <div class="col-6 text-center">
                                                <p class="small text-muted mb-1">Signature</p>
                                                <img src="{{ \App\Support\SecureDocument::url('signatures', $guarantor1->guarantor1_signature_path) }}"
                                                    class="img-thumbnail" style="width:120px;height:120px;object-fit:cover;">
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted small">No guarantor 1 information submitted yet.</p>
                                @endif
                            </div>

                            {{-- Guarantor 2 --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold border-bottom pb-2 mb-3">Guarantor 2
                                    @if($guarantor2)
                                        <span class="badge bg-success ms-2">Submitted</span>
                                    @else
                                        <span class="badge bg-warning ms-2">Not Submitted</span>
                                    @endif
                                </h6>
                                @if($guarantor2)
                                    <table class="table table-sm">
                                        <tr><th>Name</th><td>{{ $guarantor2->guarantor2_first_name }} {{ $guarantor2->guarantor2_last_name }}</td></tr>
                                        <tr><th>Phone</th><td>{{ $guarantor2->guarantor2_phone }}</td></tr>
                                        <tr><th>NIN</th><td>{{ $guarantor2->guarantor2_nin ?? 'Not provided' }}</td></tr>
                                        <tr><th>Email</th><td>{{ $guarantor2->guarantor2_email ?? 'N/A' }}</td></tr>
                                        <tr><th>Relationship</th><td>{{ $guarantor2->guarantor2_relationship }}</td></tr>
                                        <tr><th>Occupation</th><td>{{ $guarantor2->guarantor2_occupation }}</td></tr>
                                        <tr><th>Address</th><td>{{ $guarantor2->guarantor2_address }}</td></tr>
                                    </table>
                                    <div class="row mt-2">
                                        @if($guarantor2->guarantor2_passport_path)
                                            <div class="col-6 text-center">
                                                <p class="small text-muted mb-1">Passport Photo</p>
                                                <img src="{{ \App\Support\SecureDocument::url('passports', $guarantor2->guarantor2_passport_path) }}"
                                                    class="img-thumbnail" style="width:120px;height:120px;object-fit:cover;">
                                            </div>
                                        @endif
                                        @if($guarantor2->guarantor2_signature_path)
                                            <div class="col-6 text-center">
                                                <p class="small text-muted mb-1">Signature</p>
                                                <img src="{{ \App\Support\SecureDocument::url('signatures', $guarantor2->guarantor2_signature_path) }}"
                                                    class="img-thumbnail" style="width:120px;height:120px;object-fit:cover;">
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted small">No guarantor 2 information submitted yet.</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Image Preview Script -->

    <script>

        // Profile photo preview

        document.getElementById('profile_photo_path').addEventListener('change', function(e) {

            const preview = document.getElementById('profile-preview');

            const file = e.target.files[0];

            if (file) {

                const reader = new FileReader();

                reader.onload = function(e) {

                    preview.src = e.target.result;

                }

                reader.readAsDataURL(file);

            }

        });



        // Click profile image to trigger file input

        document.getElementById('profile-preview').addEventListener('click', function() {

            document.getElementById('profile_photo_path').click();

        });

    </script>



    <!-- Datepicker Initialization -->

    <script>

        // Initialize datepicker if needed

        document.addEventListener('DOMContentLoaded', function() {

            // You can add datepicker initialization here if using a library

            // Example with flatpickr:

            // flatpickr("#dob", {dateFormat: "Y-m-d"});

        });

    </script>

@endsection

