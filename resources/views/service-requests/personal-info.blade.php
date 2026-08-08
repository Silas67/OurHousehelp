@extends('layouts.main')

@section('personal-info')
    <style>
        .registration-info-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            border-left: 5px solid #479c18;
        }

        .registration-info-box h5 {
            color: #479c18;
            margin-bottom: 20px;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="mb-0 text-white">Personal Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('service-request.save-personal-info') }}" method="POST">
                            @csrf
                            <!-- Personal info fields only -->
                            <!-- Step 1: Registration (Only for guests) -->
                            {{-- @if (!auth()->check()) --}}
                            <div class="form-step active" id="step-1-content">
                                <div class="registration-step">
                                    {{-- <div class="registration-icon">
                                            <i class="fas fa-user-plus"></i>
                                        </div> --}}

                                    {{-- <h4 class="mb-4" style="color: #479c18;">
                                            <i class="fas fa-user-edit me-2"></i> Create Your Account
                                        </h4> --}}

                                    <div class="registration-info-box">
                                        <h5>How Registration Works</h5>
                                        <div class="text-start">
                                            <p class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Your
                                                account will be created automatically using your email and phone number
                                            </p>
                                            <p class="mb-3"><i class="fas fa-key text-success me-2"></i> Your phone
                                                number
                                                will be your initial password</p>
                                            <p class="mb-3"><i class="fas fa-envelope text-success me-2"></i> You'll
                                                receive login details after registration</p>
                                            <p class="mb-0"><i class="fas fa-shield-alt text-success me-2"></i> Your
                                                information is saved automatically when you click "Next"</p>
                                        </div>
                                    </div>

                                    <p class="text-muted mb-4">
                                        Please provide your details below to create your account and continue with your
                                        service request.
                                    </p>

                                    <!-- Registration Form Fields -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required
                                                value="{{ old('name') }}" placeholder="Enter your first name">
                                            <div class="invalid-feedback">Please enter your first name.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Middle Name (Optional)</label>
                                            <input type="text" name="middle_name" class="form-control"
                                                value="{{ old('middle_name') }}" placeholder="Enter your middle name">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" required
                                                value="{{ old('last_name') }}" placeholder="Enter your last name">
                                            <div class="invalid-feedback">Please enter your last name.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" required
                                                value="{{ old('email') }}" placeholder="your.email@example.com">
                                            <div class="invalid-feedback">Please enter a valid email address.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" name="phone" class="form-control" required
                                                pattern="\d{11}" title="11-digit Nigerian phone number"
                                                value="{{ old('phone') }}" placeholder="08012345678">
                                            <div class="form-text">
                                                <i class="fas fa-key me-1"></i> This will be your initial password
                                            </div>
                                            <div class="invalid-feedback">Please enter a valid 11-digit phone number.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">-- Select Gender --</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Female
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">Please select your gender.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Country of Residence <span
                                                    class="text-danger">*</span></label>
                                            <select name="country_of_residence" class="form-select" required>
                                                <option value="">-- Select Country --</option>
                                                @include('partials._countries')
                                            </select>
                                            <div class="invalid-feedback">Please select your country of residence.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">State of Residence <span
                                                    class="text-danger">*</span></label>
                                            <select name="state_of_residence" class="form-select" required>
                                                <option value="">-- Select State --</option>
                                                @include('partials._states')
                                            </select>
                                            <div class="invalid-feedback">Please select your state of residence.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3" hidden>
                                            <label class="form-label">On Behalf Of Someone?</label>
                                            <select name="on_behalf" class="form-select">
                                                <option value="no" {{ old('on_behalf') == 'no' ? 'selected' : '' }}>
                                                    No, for myself</option>
                                                <option value="yes" {{ old('on_behalf') == 'yes' ? 'selected' : '' }}>
                                                    Yes, for someone else</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Date of Birth (Optional)</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <select name="dob_month" class="form-select">
                                                        <option value="">Month</option>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('dob_month') == $i ? 'selected' : '' }}>
                                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select name="dob_day" class="form-select">
                                                        <option value="">Day</option>
                                                        @for ($i = 1; $i <= 31; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('dob_day') == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" required
                                            value="{{ old('address') }}" placeholder="Enter your complete address">
                                        <div class="invalid-feedback">Please enter your address.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Landmark <span class="text-danger">*</span></label>
                                        <input type="text" name="landmark" class="form-control" required
                                            value="{{ old('landmark') }}"
                                            placeholder="e.g., Near shopping mall, Opposite bank">
                                    </div>

                                    <!-- File uploads (Optional) -->
                                    <div class="row mb-4" hidden>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Passport Photo (Optional)</label>
                                            <input type="file" name="passport" class="form-control" accept="image/*">
                                            <div class="form-text">JPG, PNG, JPEG, Max: 2MB</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ID Card (Optional)</label>
                                            <input type="file" name="id_card" class="form-control" accept="image/*">
                                            <div class="form-text">JPG, PNG, JPEG, Max: 2MB</div>
                                        </div>
                                    </div>

                                    <!-- Login Option for Registered Users -->
                                    <div class="login-option">
                                        <p class="text-muted mb-1">Already have an account?
                                            <a href="{{ route('login') }}" style="text-weight: bold"> Login to Your
                                                Account</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                            <button type="submit" class="btn btn-primary">Save & Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
