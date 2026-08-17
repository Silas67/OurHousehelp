@extends('layouts.main')

@section('liveins')

@php
    // Files parked by an earlier failed submit (see App\Support\PendingUpload).
    $kept_id_card = \App\Support\PendingUpload::stored('pending_id_card');
    $kept_guarantor1_passport_path = \App\Support\PendingUpload::stored('pending_g1_passport');
    $kept_guarantor1_signature_path = \App\Support\PendingUpload::stored('pending_g1_signature');
    $kept_guarantor2_passport_path = \App\Support\PendingUpload::stored('pending_g2_passport');
    $kept_guarantor2_signature_path = \App\Support\PendingUpload::stored('pending_g2_signature');
@endphp
@php
$nigerianStates = ['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara','FCT Abuja'];
@endphp

<section id="onetime" class="py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-lg border-0">

                    <div class="card-header text-center text-white py-3" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
                        <h5 class="mb-0">Apply as a Househelp</h5>
                    </div>

                    <div class="card-body p-4">

                        {{-- Step Progress --}}
                        <div class="d-flex align-items-center mb-4 px-2">
                            @foreach([['1','Personal'], ['2','Location'], ['3','Professional'], ['4','Guarantor 1'], ['5','Guarantor 2'], ['6','Account']] as $i => [$num, $label])
                            <div class="d-flex flex-column align-items-center" style="flex-shrink:0;">
                                <div class="step-circle {{ $i === 0 ? 'step-active' : '' }}"
                                    id="circle-{{ $num }}"
                                    style="width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;border:2px solid {{ $i === 0 ? '#0F6E56' : '#dee2e6' }};background:{{ $i === 0 ? '#0F6E56' : 'white' }};color:{{ $i === 0 ? 'white' : '#adb5bd' }};transition:all 0.25s;">
                                    <span class="circle-num">{{ $num }}</span>
                                    <i class="fas fa-check circle-check" style="display:none;font-size:13px;"></i>
                                </div>
                                <div id="label-{{ $num }}" style="font-size:11px;margin-top:4px;color:{{ $i === 0 ? '#0F6E56' : '#adb5bd' }};font-weight:{{ $i === 0 ? '500' : '400' }};transition:all 0.25s;">{{ $label }}</div>
                            </div>
                            @if($i < 5)
                            <div class="step-line" id="line-{{ $num }}" style="flex:1;height:2px;background:#dee2e6;margin:0 8px 20px;transition:background 0.3s;"></div>
                            @endif
                            @endforeach
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('applicants.registerlivein') }}"
                            enctype="multipart/form-data" id="registerForm">
                            @csrf
                            <input type="hidden" name="account_type" value="applicant">
                            <input type="hidden" name="applicant_type" value="live-in">

                            {{-- ══ STEP 1: PERSONAL INFO ══ --}}
                            <div class="step-panel" id="step-1">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 1 of 5 — Personal Information</strong>
                                </p>

                                <div class="row g-3">
                                    {{-- Today's date (display only) --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Date</label>
                                        <input type="text" class="form-control bg-light" id="todayDate" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                                            value="{{ old('middle_name') }}"
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name') }}" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required onchange="validateGender(this)">
                                            <option value="">Select gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" name="dob" id="dob" class="form-control @error('dob') is-invalid @enderror"
                                            value="{{ old('dob', Auth::user()->dob ?? '') }}" required onchange="validateAge(this)"> 
                                        <small class="text-muted">Must be at least 18 years old</small>
                                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Marital Status <span class="text-danger">*</span></label>
                                        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror" required onchange="validateSelect(this, 'marital_status')">
                                            <option value="">Select status</option>
                                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                        @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Religion <span class="text-danger">*</span></label>
                                        <select name="religion" class="form-select @error('religion') is-invalid @enderror" required onchange="validateSelect(this, 'religion')">
                                            <option value="">Select religion</option>
                                            <option value="christianity" {{ old('religion') == 'christianity' ? 'selected' : '' }}>Christianity</option>
                                            <option value="islam" {{ old('religion') == 'islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="traditional" {{ old('religion') == 'traditional' ? 'selected' : '' }}>Traditional</option>
                                            <option value="other" {{ old('religion') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            // A photo parked by an earlier failed submit. The applicant
                                            // does not have to pick it again.
                                            $keptPhoto = \App\Support\PendingUpload::stored('pending_profile_photo');
                                        @endphp

                                        <label class="form-label fw-semibold">
                                            Profile Photo
                                            @unless($keptPhoto)<span class="text-danger">*</span>@endunless
                                        </label>

                                        <input type="file" name="profile_photo_path" class="form-control @error('profile_photo_path') is-invalid @enderror"
                                            accept="image/*" onchange="previewPhoto(this)" @unless($keptPhoto) required @endunless>
                                        @error('profile_photo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                        @if($keptPhoto)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">
                                                    Your photo was saved - you only need to choose a new file if you want to change it.
                                                </small>
                                            </div>
                                        @endif

                                        <img id="photoPreview" src="#" alt="Preview"
                                            style="display:none;width:70px;height:70px;object-fit:cover;border-radius:50%;margin-top:8px;border:3px solid #0F6E56;" loading="lazy" decoding="async">
                                    </div>

                                </div>
                            </div>

                            {{-- ══ STEP 2: CONTACT & LOCATION ══ --}}
                            <div class="step-panel d-none" id="step-2">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-map-marker-alt me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 2 of 5 — Contact & Location</strong>
                                </p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email-field" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required
                                            oninput="this.value=this.value.replace(/\s/g,'')">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" id="phone-field"  class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                            required maxlength="11" minlength="11" placeholder="080XXXXXXXX"
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Residential Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                            value="{{ old('address') }}" required placeholder="House No, Street, Area" onblur="validateField(this, 'Residential address')">
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    {{-- Landmark sits directly under the address it qualifies,
                                         full width, so the origin fields below start a clean row. --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Landmark <span class="text-danger">*</span></label>
                                        <input type="text" name="landmark" class="form-control @error('landmark') is-invalid @enderror"
                                            value="{{ old('landmark', $user->landmark ?? '') }}" required
                                            placeholder="e.g. Near Total Filling Station" onblur="validateField(this, 'Landmark')">
                                        @error('landmark')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Origin: country, state and LGA read as one group, so they
                                         share a row. --}}
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Country of Origin <span class="text-danger">*</span></label>
                                        <select name="country" class="form-select @error('country') is-invalid @enderror" required onchange="validateSelect(this, 'country of origin')">
                                            <option value="">Select country</option>
                                            @foreach(['Nigeria','United States','United Kingdom','Canada','Australia','Ghana','South Africa','Kenya','India','France','Germany','Italy','Spain'] as $c)
                                            <option value="{{ $c }}" {{ old('country', Auth::user()->country ?? 'Nigeria') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">State of Origin <span class="text-danger">*</span></label>
                                        <select name="state_of_origin" class="form-select @error('state_of_origin') is-invalid @enderror" required onchange="validateSelect(this, 'state of origin')">
                                            <option value="">Select state</option>
                                            @foreach($nigerianStates as $state)
                                            <option value="{{ $state }}" {{ old('state_of_origin') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_of_origin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">LGA of Origin <span class="text-danger">*</span></label>
                                        <input type="text" name="lga_of_origin" class="form-control @error('lga_of_origin') is-invalid @enderror"
                                            value="{{ old('lga_of_origin', $user->lga_of_origin ?? '') }}" required
                                            placeholder="e.g. Ikeja, Surulere" onblur="validateField(this, 'LGA of origin')">
                                        @error('lga_of_origin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Residence --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Country of Residence <span class="text-danger">*</span></label>
                                        <select name="country_of_residence" class="form-select @error('country_of_residence') is-invalid @enderror" required onchange="validateSelect(this, 'country of residence')">
                                            <option value="">Select country</option>
                                            @foreach(['Nigeria','United States','United Kingdom','Canada','Australia','Ghana','South Africa','Kenya','India','France','Germany','Italy','Spain'] as $c)
                                            <option value="{{ $c }}" {{ old('country_of_residence', $user->country_of_residence ?? 'Nigeria') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_of_residence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">State of Residence <span class="text-danger">*</span></label>
                                        <select name="state_of_residence" class="form-select @error('state_of_residence') is-invalid @enderror" required onchange="validateSelect(this, 'state of residence')">
                                            <option value="">Select state</option>
                                            @foreach($nigerianStates as $state)
                                            <option value="{{ $state }}" {{ old('state_of_residence', $user->state_of_residence ?? '') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_of_residence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ══ STEP 3: PROFESSIONAL ══ --}}
                            <div class="step-panel d-none" id="step-3">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-briefcase me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 3 of 5 — Professional Details & Specialties</strong>
                                </p>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">NIN (National ID Number) <span class="text-danger">*</span></label>
                                        <input type="text" name="nin" id="nin-field" class="form-control @error('nin') is-invalid @enderror"
                                            value="{{ old('nin', $user->nin ?? '') }}" required
                                            maxlength="11" minlength="11" placeholder="11-digit NIN"
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('nin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Highest Qualification <span class="text-danger">*</span></label>
                                        <select name="qualification" class="form-select @error('qualification') is-invalid @enderror" required>
                                            <option value="">Select qualification</option>
                                            @foreach(['No formal education','Primary School','Junior Secondary (JSS3)','Senior Secondary (WAEC/NECO)','OND/NCE','HND/BSc','Postgraduate'] as $q)
                                            <option value="{{ $q }}" {{ old('qualification') == $q ? 'selected' : '' }}>{{ $q }}</option>
                                            @endforeach
                                        </select>
                                        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Years of Experience <span class="text-danger">*</span></label>
                                        <select id="years_of_experience" name="years_of_experience"
                                            class="form-control @error('years_of_experience') is-invalid @enderror" required onchange="validateSelect(this, 'years of experience')">
                                            <option value="">-- Select Years of Experience --</option>
                                            <option value="Less than 3 years"
                                                {{ old('years_of_experience', $user->years_of_experience ?? '') == 'Less than 3 years' ? 'selected' : '' }}>
                                                Less than 3 years
                                            </option>
                                            <option value="Over 3 years"
                                                {{ old('years_of_experience', $user->years_of_experience ?? '') == 'Over 3 years' ? 'selected' : '' }}>
                                                Over 3 years
                                            </option>
                                        </select>
                                        @error('years_of_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Languages Spoken</label>
                                        <input type="text" name="languages" class="form-control @error('languages') is-invalid @enderror"
                                            value="{{ old('languages') }}" placeholder="e.g. English, Yoruba, Igbo">
                                        @error('languages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">About You / Work Summary <span class="text-danger">*</span></label>
                                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3"
                                            placeholder="Tell clients a bit about yourself, your experience, and what makes you a great househelp..." onblur="validateField(this, 'Work summary')" required>{{ old('bio') }} </textarea>
                                        @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">ID Card Upload</label>
                                        <input type="file" name="id_card" class="form-control @error('id_card') is-invalid @enderror"
                                            accept="image/*,.pdf" onchange="previewIdCard(this)" onchange="validateFile(this, 'ID card')">
                                        @if($kept_id_card)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">Saved - choose a file only if you want to replace it.</small>
                                            </div>
                                        @endif
                                        <small class="text-muted">Upload a clear photo of your NIN slip, driver's licence, voter's card or international passport</small>
                                        @error('id_card')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <img id="idcard-preview" src="#" alt="ID Card Preview"
                                            style="display:none;max-width:200px;margin-top:10px;border-radius:8px;border:2px solid #0F6E56;" loading="lazy" decoding="async">
                                    </div>
                                </div>

                                {{-- Specialties --}}
                                <div class="alert alert-info" style="font-size:13px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Required:</strong> Cleaning, Laundry and Errands are mandatory for all staff.
                                    The more services you offer, the higher your chances of being matched!
                                </div>

                                <label class="form-label fw-semibold mb-3">Select Your Specialties</label>
                                <div class="row g-3">
                                    @foreach($services as $service)
                                    @php
                                        $serviceName = ucfirst($service->service_name);
                                        $isLocked = in_array($service->service_name, ['Cleaning Service', 'Errand Service', 'Laundry Service']);
                                        $isChecked = $isLocked || in_array($serviceName, $applicant->specialties ?? []);
                                    @endphp
                                    <div class="col-md-4 col-6">
                                        <label class="d-flex align-items-center gap-2 p-3 rounded border h-100 specialty-card {{ $isChecked ? 'border-success' : '' }} {{ $isLocked ? 'bg-light' : '' }}"
                                            style="cursor:{{ $isLocked ? 'not-allowed' : 'pointer' }};transition:all 0.15s;">
                                            <input type="checkbox"
                                                name="specialties[]"
                                                value="{{ $serviceName }}"
                                                class="form-check-input mt-0 flex-shrink-0"
                                                {{ $isChecked ? 'checked' : '' }}
                                                {{ $isLocked ? 'disabled' : '' }}>
                                            @if($isLocked)
                                                <input type="hidden" name="specialties[]" value="{{ $serviceName }}">
                                            @endif
                                            <div>
                                                <div class="fw-semibold" style="font-size:13px;">{{ $serviceName }}</div>
                                                @if($isLocked)
                                                    <span class="badge bg-success" style="font-size:9px;">Required</span>
                                                @endif
                                                <div class="text-muted" style="font-size:11px;">{{ Str::limit($service->about_service, 40) }}</div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- ══ STEP 6: ACCOUNT ══ --}}
                            <div class="step-panel d-none" id="step-6">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-lock me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 6 of 6 — Create Your Account</strong>
                                </p>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required autocomplete="new-password" placeholder="Min. 8 characters">
                                            <i class="fas fa-eye position-absolute" style="right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#adb5bd;"
                                                onclick="togglePwd('password')"></i>
                                        </div>
                                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation" id="password-confirm"
                                                class="form-control" required autocomplete="new-password"
                                                placeholder="Repeat password">
                                            <i class="fas fa-eye position-absolute" style="right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#adb5bd;"
                                                onclick="togglePwd('password-confirm')"></i>
                                        </div>
                                    </div>

                                    {{-- Summary --}}
                                    <div class="col-12">
                                        <div class="p-3 rounded" style="background:#f0fff4;border:1px solid #9FE1CB;">
                                            <div class="fw-semibold mb-2" style="color:#0F6E56;font-size:14px;">
                                                <i class="fas fa-check-circle me-2"></i>Review before submitting
                                            </div>
                                            <div class="small text-muted">
                                                <strong>Name:</strong> <span id="s-name">—</span> &bull;
                                                <strong>Email:</strong> <span id="s-email">—</span> &bull;
                                                <strong>State:</strong> <span id="s-state">—</span> &bull;
                                                <strong>NIN:</strong> <span id="s-nin">—</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Terms --}}
                                    <div class="col-12">
                                        <div class="p-3 rounded" style="background:#fff8e1;border:1px solid #ffe082;">
                                            <div class="form-check">
                                                <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                                    type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                                <label class="form-check-label" for="terms_accepted" style="font-size:14px;">
                                                    I have read and accept the
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="color:#0F6E56;">
                                                        Staff Service Agreement
                                                    </a>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                @error('terms_accepted')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ══ STEP 5: GUARANTOR ══ --}}
                            <div class="step-panel d-none" id="step-4">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user-shield me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 4 of 6 — Guarantor 1 Details</strong>
                                </p>
                                <div class="alert alert-info" style="font-size:13px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Your guarantor must be someone who can vouch for your character and conduct — a relative, employer, religious leader, or community member.
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_first_name"
                                            class="form-control @error('guarantor1_first_name') is-invalid @enderror"
                                            value="{{ old('guarantor1_first_name') }}"
                                            placeholder="Guarantor's first name" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('guarantor1_first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_last_name"
                                            class="form-control @error('guarantor1_last_name') is-invalid @enderror"
                                            value="{{ old('guarantor1_last_name') }}"
                                            placeholder="Guarantor's last name" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('guarantor1_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="guarantor1_phone"
                                            class="form-control @error('guarantor1_phone') is-invalid @enderror"
                                            value="{{ old('guarantor1_phone') }}"
                                            placeholder="080XXXXXXXX" maxlength="11" required
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('guarantor1_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">NIN (National ID Number) <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_nin"
                                            class="form-control @error('guarantor1_nin') is-invalid @enderror"
                                            value="{{ old('guarantor1_nin') }}"
                                            placeholder="11-digit NIN" maxlength="11" required
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('guarantor1_nin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address</label>
                                        <input type="email" name="guarantor1_email"
                                            class="form-control @error('guarantor1_email') is-invalid @enderror"
                                            value="{{ old('guarantor1_email') }}"
                                            placeholder="Guarantor's email address" required>
                                        @error('guarantor1_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_relationship"
                                            class="form-control @error('guarantor1_relationship') is-invalid @enderror"
                                            value="{{ old('guarantor1_relationship') }}"
                                            placeholder="e.g. Brother, Pastor, Employer" required>
                                        @error('guarantor1_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_occupation"
                                            class="form-control @error('guarantor1_occupation') is-invalid @enderror"
                                            value="{{ old('guarantor1_occupation') }}"
                                            placeholder="Guarantor's occupation" required>
                                        @error('guarantor1_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_address"
                                            class="form-control @error('guarantor1_address') is-invalid @enderror"
                                            value="{{ old('guarantor1_address') }}"
                                            placeholder="Guarantor's residential address" required>
                                        @error('guarantor1_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Passport Photo <span class="text-danger">*</span></label>
                                        <input type="file" name="guarantor1_passport_path"
                                            class="form-control @error('guarantor1_passport_path') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg" @unless($kept_guarantor1_passport_path) required @endunless
                                            onchange="validateFile(this, 'guarantor passport')">
                                        @if($kept_guarantor1_passport_path)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">Saved - choose a file only if you want to replace it.</small>
                                            </div>
                                        @endif
                                        <div class="form-text">JPG/PNG, max 2MB</div>
                                        @error('guarantor1_passport_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Signature <span class="text-danger">*</span></label>
                                        <input type="file" name="guarantor1_signature_path"
                                            class="form-control @error('guarantor1_signature_path') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg" @unless($kept_guarantor1_signature_path) required @endunless
                                            onchange="validateFile(this, 'guarantor signature')">
                                        @if($kept_guarantor1_signature_path)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">Saved - choose a file only if you want to replace it.</small>
                                            </div>
                                        @endif
                                        <div class="form-text">JPG/PNG, max 2MB</div>
                                        @error('guarantor1_signature_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ══ STEP 5: GUARANTOR 2 ══ --}}
                            <div class="step-panel d-none" id="step-5">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user-shield me-1" style="color:#0F6E56;"></i>
                                    <strong>Step 5 of 6 — Guarantor 2 Details</strong>
                                </p>
                                <div class="alert alert-info" style="font-size:13px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Your second guarantor must be a different person from Guarantor 1 — another relative, employer, religious leader, or community member.
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_first_name"
                                            class="form-control @error('guarantor2_first_name') is-invalid @enderror"
                                            value="{{ old('guarantor2_first_name') }}"
                                            placeholder="Guarantor's first name" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('guarantor2_first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_last_name"
                                            class="form-control @error('guarantor2_last_name') is-invalid @enderror"
                                            value="{{ old('guarantor2_last_name') }}"
                                            placeholder="Guarantor's last name" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('guarantor2_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="guarantor2_phone"
                                            class="form-control @error('guarantor2_phone') is-invalid @enderror"
                                            value="{{ old('guarantor2_phone') }}"
                                            placeholder="080XXXXXXXX" maxlength="11" required
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('guarantor2_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">NIN (National ID Number) <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_nin"
                                            class="form-control @error('guarantor2_nin') is-invalid @enderror"
                                            value="{{ old('guarantor2_nin') }}"
                                            placeholder="11-digit NIN" maxlength="11" required
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('guarantor2_nin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="guarantor2_email"
                                            class="form-control @error('guarantor2_email') is-invalid @enderror"
                                            value="{{ old('guarantor2_email') }}"
                                            placeholder="Guarantor's email address" required>
                                        @error('guarantor2_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_relationship"
                                            class="form-control @error('guarantor2_relationship') is-invalid @enderror"
                                            value="{{ old('guarantor2_relationship') }}"
                                            placeholder="e.g. Sister, Neighbour, Employer" required>
                                        @error('guarantor2_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Occupation <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_occupation"
                                            class="form-control @error('guarantor2_occupation') is-invalid @enderror"
                                            value="{{ old('guarantor2_occupation') }}"
                                            placeholder="Guarantor's occupation" required>
                                        @error('guarantor2_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_address"
                                            class="form-control @error('guarantor2_address') is-invalid @enderror"
                                            value="{{ old('guarantor2_address') }}"
                                            placeholder="Guarantor's residential address" required>
                                        @error('guarantor2_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Passport Photo <span class="text-danger">*</span></label>
                                        <input type="file" name="guarantor2_passport_path"
                                            class="form-control @error('guarantor2_passport_path') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg" @unless($kept_guarantor2_passport_path) required @endunless
                                            onchange="validateFile(this, 'guarantor 2 passport')">
                                        @if($kept_guarantor2_passport_path)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">Saved - choose a file only if you want to replace it.</small>
                                            </div>
                                        @endif
                                        <div class="form-text">JPG/PNG, max 2MB</div>
                                        @error('guarantor2_passport_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Signature <span class="text-danger">*</span></label>
                                        <input type="file" name="guarantor2_signature_path"
                                            class="form-control @error('guarantor2_signature_path') is-invalid @enderror"
                                            accept="image/jpeg,image/png,image/jpg" @unless($kept_guarantor2_signature_path) required @endunless
                                            onchange="validateFile(this, 'guarantor 2 signature')">
                                        @if($kept_guarantor2_signature_path)
                                            <div class="d-flex align-items-center gap-2 mt-2 p-2 rounded"
                                                 style="background:#E1F5EE;border:1px solid #9FE1CB;">
                                                <i class="fas fa-check-circle" style="color:#0F6E56;"></i>
                                                <small style="color:#0F6E56;">Saved - choose a file only if you want to replace it.</small>
                                            </div>
                                        @endif
                                        <div class="form-text">JPG/PNG, max 2MB</div>
                                        @error('guarantor2_signature_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Navigation Buttons --}}
                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4" id="prevBtn"
                                    onclick="changeStep(-1)" style="display:none;">
                                    <i class="fas fa-arrow-left me-2"></i>Previous
                                </button>
                                <div class="ms-auto">
                                    <button type="button" class="btn px-5 py-2" id="nextBtn" onclick="changeStep(1)"
                                        style="background:#0F6E56;color:white;font-weight:500;">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn px-5 py-2 d-none" id="submitBtn"
                                        onclick="this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin me-2\'></i>Registering...';this.form.submit();"
                                        style="background:#0F6E56;color:white;font-weight:500;">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>
                            </div>

                        </form>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Already registered? <a href="{{ route('login') }}" style="color:#0F6E56;">Login here</a>
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Terms Modal --}}
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background:#0F6E56;color:white;">
                <h5 class="modal-title fw-bold">Staff Service Agreement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <p><strong>Between</strong><br><strong>Our House Help Services</strong><br>AND<br><strong>The Staff</strong></p>
                <p>This Agreement governs the placement of the Staff by the Company to work with assigned Clients in roles such as Cleaning, Laundry, Cooking, Errands, Domestic Assistance, and Doorman/Gate Duties.</p>
                <h6 class="fw-bold mt-3">1. Nature of Placement</h6>
                <p>The Staff is placed with Clients through the Company for service support. The Staff remains under the coordination of the Company at all times.</p>
                <h6 class="fw-bold mt-3">2. Conduct & Personal Responsibility</h6>
                <p>The Staff shall conduct themselves professionally, respect Client property and environment, and perform assigned duties responsibly. The Staff shall be personally responsible for misconduct, negligence, theft, damage, disputes, or criminal activity.</p>
                <h6 class="fw-bold mt-3">3. Responsibility for Actions</h6>
                <p>The Staff shall bear full responsibility for any damage, loss, misconduct, or criminal matter. If unresolved, the Staff's <strong>Guarantor(s)</strong> shall assume responsibility.</p>
                <h6 class="fw-bold mt-3">4. Company Support Role</h6>
                <p>The Company may mediate disputes but shall not bear financial or legal responsibility for personal actions of the Staff.</p>
                <h6 class="fw-bold mt-3">5. No Direct Agreement with Client</h6>
                <ul><li>No private employment with Clients</li><li>No direct payment collection</li><li>No private agreements</li></ul>
                <h6 class="fw-bold mt-3">6. Payment Rule</h6>
                <p>All earnings and work arrangements must be processed through the Company.</p>
                <h6 class="fw-bold mt-3">7. Replacement & Reassignment</h6>
                <p>The Company may reassign or replace the Staff based on client request, performance, or conduct concerns.</p>
                <h6 class="fw-bold mt-3">8. Guarantor Responsibility</h6>
                <p>Guarantor(s) shall be responsible for damage, misconduct, unresolved issues, or legal matters arising from Staff actions.</p>
                <h6 class="fw-bold mt-3">9. Exit Rule</h6>
                <p>Any exit must be communicated through the Company. The Staff shall not continue working privately with the Client.</p>
                <h6 class="fw-bold mt-3">10. Commitment to Fair Service</h6>
                <p>The Company supports Staff where necessary. Personal behaviour remains the responsibility of the Staff and their Guarantor(s).</p>
                <h6 class="fw-bold mt-3">11. Acceptance</h6>
                <p>By registering and working through the Company, the Staff agrees to all terms above.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" style="background:#0F6E56;color:white;"
                    data-bs-dismiss="modal"
                    onclick="document.getElementById('terms_accepted').checked=true;">
                    I Accept
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .step-panel { animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .form-control:focus, .form-select:focus { border-color: #0F6E56; box-shadow: 0 0 0 3px rgba(15,110,86,0.1); }
    .specialty-card:hover:not(.bg-light) { border-color: #0F6E56 !important; background: #f0fff4; }
    .specialty-card input:checked ~ div .fw-semibold { color: #0F6E56; }
</style>

<script>

    let currentStep = 1;
    const totalSteps = 6;

    document.getElementById('todayDate').value = new Date().toLocaleDateString('en-US', {weekday:'short',year:'numeric',month:'short',day:'numeric'});

    function changeStep(dir) {
        if (dir === 1 && !validateStep(currentStep)) return;

        markDone(currentStep);
        currentStep += dir;
        if (currentStep < 1) currentStep = 1;

        document.querySelectorAll('.step-panel').forEach(p => p.classList.add('d-none'));
        document.getElementById('step-' + currentStep).classList.remove('d-none');

        setActive(currentStep);

        document.getElementById('prevBtn').style.display = currentStep > 1 ? 'inline-block' : 'none';

        if (currentStep === totalSteps) {
            document.getElementById('nextBtn').classList.add('d-none');
            document.getElementById('submitBtn').classList.remove('d-none');
            updateSummary();
        } else {
            document.getElementById('nextBtn').classList.remove('d-none');
            document.getElementById('submitBtn').classList.add('d-none');
        }

        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function setActive(step) {
        const c = document.getElementById('circle-' + step);
        const l = document.getElementById('label-' + step);
        c.style.background = '#0F6E56';
        c.style.borderColor = '#0F6E56';
        c.style.color = 'white';
        c.querySelector('.circle-num').style.display = 'block';
        c.querySelector('.circle-check').style.display = 'none';
        l.style.color = '#0F6E56';
        l.style.fontWeight = '500';
    }

    function markDone(step) {
        const c = document.getElementById('circle-' + step);
        const l = document.getElementById('label-' + step);
        const line = document.getElementById('line-' + step);
        c.style.background = '#1D9E75';
        c.style.borderColor = '#1D9E75';
        c.querySelector('.circle-num').style.display = 'none';
        c.querySelector('.circle-check').style.display = 'block';
        l.style.color = '#1D9E75';
        if (line) line.style.background = '#1D9E75';
    }

    function validateStep(step) {
        const panel = document.getElementById('step-' + step);
        let valid = true;
        let firstInvalid = null;

        // Remove all previous error messages
        panel.querySelectorAll('.step-error-msg').forEach(e => e.remove());
        panel.querySelectorAll('.is-invalid').forEach(f => f.classList.remove('is-invalid'));

        // Check every field in the panel, not just required ones: a wrongly
        // formatted optional value should be caught here too, rather than
        // surviving to the server and bouncing the whole form back.
        panel.querySelectorAll('input, select, textarea').forEach(f => {
            if (f.disabled || f.type === 'hidden' || f.type === 'button') return;

            const isRequired = f.hasAttribute('required');
            const isEmpty = f.type === 'file' ? f.files.length === 0 : !f.value.trim();

            // Nothing to check on an untouched optional field.
            if (!isRequired && isEmpty) return;

            let problem = null;

            if (isEmpty) {
                problem = getFieldLabel(f) + ' is required.';
            } else if (typeof f.checkValidity === 'function' && !f.checkValidity()) {
                // Native constraint validation covers type=email, minlength,
                // maxlength, pattern, min/max and step.
                problem = f.validationMessage || ('Please check ' + getFieldLabel(f) + '.');
            } else if (f.type === 'email' && !/^[^@\s]+@[^@\s]+\.[^@\s]{2,}$/.test(f.value.trim())) {
                problem = 'Enter a valid email address, e.g. name@example.com.';
            } else if (f.name === 'phone' && !/^0\d{10}$/.test(f.value.trim())) {
                problem = 'Enter an 11-digit phone number starting with 0.';
            } else if (f.name === 'nin' && !/^\d{11}$/.test(f.value.trim())) {
                problem = 'NIN must be exactly 11 digits.';
            }

            if (problem) {
                f.classList.add('is-invalid');
                valid = false;

                const existing = f.parentElement.querySelector('.step-error-msg');
                if (!existing) {
                    const msg = document.createElement('div');
                    msg.className = 'step-error-msg text-danger small mt-1';
                    msg.style.fontWeight = '500';
                    msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + problem;
                    f.parentElement.appendChild(msg);
                }

                if (!firstInvalid) firstInvalid = f;
            }
        });

        // Step 3: check specialties
        if (step === 3) {
            const checked = panel.querySelectorAll('input[name="specialties[]"]:checked');
            if (checked.length === 0) {
                valid = false;
                const specialtySection = panel.querySelector('.row.g-3');
                if (specialtySection) {
                    const existing = specialtySection.querySelector('.step-error-msg');
                    if (!existing) {
                        const msg = document.createElement('div');
                        msg.className = 'step-error-msg text-danger small mt-2 mb-2';
                        msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Please select at least one specialty.';
                        specialtySection.parentElement.insertBefore(msg, specialtySection);
                    }
                }
            }
        }

        if (!valid && firstInvalid) {
            firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
            firstInvalid.focus();

            // Shake the Next button
            const btn = document.getElementById('nextBtn');
            btn.style.transition = 'transform 0.1s';
            let count = 0;
            const shake = setInterval(() => {
                btn.style.transform = count % 2 === 0 ? 'translateX(-4px)' : 'translateX(4px)';
                count++;
                if (count > 5) { clearInterval(shake); btn.style.transform = ''; }
            }, 60);
        }

        return valid;
    }

    function getFieldLabel(field) {
        // Try to find a label for this field
        const id = field.id || field.name;
        let label = document.querySelector('label[for="' + id + '"]');
        if (label) return label.textContent.replace('*', '').trim();

        // Try closest label text
        const wrapper = field.closest('.col-md-4, .col-md-6, .col-12');
        if (wrapper) {
            const lbl = wrapper.querySelector('label');
            if (lbl) return lbl.textContent.replace('*', '').trim();
        }

        // Fallback to field name formatted
        return field.name
            .replace(/_/g, ' ')
            .replace(/\w/g, c => c.toUpperCase());
    }

    function updateSummary() {
        const n = (document.querySelector('[name="name"]')?.value || '') + ' ' + (document.querySelector('[name="last_name"]')?.value || '');
        document.getElementById('s-name').textContent = n.trim() || '—';
        document.getElementById('s-email').textContent = document.querySelector('[name="email"]')?.value || '—';
        document.getElementById('s-state').textContent = document.querySelector('[name="state_of_residence"]')?.value || '—';
        document.getElementById('s-nin').textContent = (document.querySelector('[name="nin"]')?.value || '—').replace(/./g, (c, i) => i < 3 || i > 8 ? c : '*');
    }

    function previewIdCard(input) {
        if (input.files?.[0] && input.files[0].type.startsWith('image/')) {
            const r = new FileReader();
            r.onload = e => {
                const p = document.getElementById('idcard-preview');
                p.src = e.target.result;
                p.style.display = 'block';
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function previewPhoto(input) {
        if (input.files?.[0]) {
            const r = new FileReader();
            r.onload = e => {
                const p = document.getElementById('photoPreview');
                p.src = e.target.result;
                p.style.display = 'block';
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function togglePwd(id) {
        const f = document.getElementById(id);
        const i = f.nextElementSibling;
        f.type = f.type === 'password' ? 'text' : 'password';
        i.classList.toggle('fa-eye');
        i.classList.toggle('fa-eye-slash');
    }

    function checkDuplicate(fieldId, type, labelText) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        let debounceTimer;
        field.addEventListener('blur', function() { runCheck(this, type, labelText); });
        field.addEventListener('input', function() {
            removeDuplicateError(field);
            field.classList.remove('is-invalid', 'is-valid');
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => runCheck(field, type, labelText), 600);
        });
    }

    function runCheck(field, type, labelText) {
        const value = field.value.trim();
        if (!value) return;
        if (type === 'phone' && value.length < 11) return;
        if (type === 'nin' && value.length < 11) return;

        fetch(`/check-availability?type=${type}&value=${encodeURIComponent(value)}`)
            .then(r => r.json())
            .then(data => {
                removeDuplicateError(field);
                if (data.exists) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    const msg = document.createElement('div');
                    msg.className = 'duplicate-error text-danger small mt-1';
                    msg.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>This ${labelText} is already registered.`;
                    field.parentElement.appendChild(msg);
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                    const ok = document.createElement('div');
                    ok.className = 'duplicate-ok text-success small mt-1';
                    ok.innerHTML = `<i class="fas fa-check-circle me-1"></i>${labelText} is available.`;
                    field.parentElement.appendChild(ok);
                }
            })
            .catch(() => {});
    }

    function removeDuplicateError(field) {
        field.parentElement.querySelector('.duplicate-error')?.remove();
        field.parentElement.querySelector('.duplicate-ok')?.remove();
    }

    checkDuplicate('email-field', 'email', 'Email address');
    checkDuplicate('phone-field', 'phone', 'Phone number');
    checkDuplicate('nin-field', 'nin', 'NIN');

    function validateAge(field) {
        const dob = new Date(field.value);
        const today = new Date();
        const age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        const isUnder18 = age < 18 || (age === 18 && monthDiff < 0) || 
                        (age === 18 && monthDiff === 0 && today.getDate() < dob.getDate());

        // Remove any previous error
        const existing = field.parentElement.querySelector('.dob-error');
        if (existing) existing.remove();
        field.classList.remove('is-invalid', 'is-valid');

        if (isUnder18) {
            field.classList.add('is-invalid');
            const msg = document.createElement('div');
            msg.className = 'dob-error text-danger small mt-1';
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>You must be at least 18 years old to register.';
            field.parentElement.appendChild(msg);
        } else {
            field.classList.add('is-valid');
            const ok = document.createElement('div');
            ok.className = 'dob-error text-success small mt-1';
            ok.innerHTML = '<i class="fas fa-check-circle me-1"></i>Age verified.';
            field.parentElement.appendChild(ok);
        }
    }

    function validateGender(field) {
        const existing = field.parentElement.querySelector('.gender-error');
        if (existing) existing.remove();
        field.classList.remove('is-invalid', 'is-valid');

        if (!field.value) {
            field.classList.add('is-invalid');
            const msg = document.createElement('div');
            msg.className = 'gender-error text-danger small mt-1';
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Please select your gender.';
            field.parentElement.appendChild(msg);
        } else {
            field.classList.add('is-valid');
        }
    }

    function validateSelect(field, labelText) {
        const existing = field.parentElement.querySelector('.select-error');
        if (existing) existing.remove();
        field.classList.remove('is-invalid', 'is-valid');

        if (!field.value) {
            field.classList.add('is-invalid');
            const msg = document.createElement('div');
            msg.className = 'select-error text-danger small mt-1';
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Please select your ' + labelText + '.';
            field.parentElement.appendChild(msg);
        } else {
            field.classList.add('is-valid');
        }
    }

    function validateField(field, labelText) {
        const existing = field.parentElement.querySelector('.field-error');
        if (existing) existing.remove();
        field.classList.remove('is-invalid', 'is-valid');

        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            const msg = document.createElement('div');
            msg.className = 'field-error text-danger small mt-1';
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + labelText + ' is required.';
            field.parentElement.appendChild(msg);
        } else {
            field.classList.add('is-valid');
        }
    }

    function validateFile(field, labelText) {
        const existing = field.parentElement.querySelector('.file-error');
        if (existing) existing.remove();
        field.classList.remove('is-invalid', 'is-valid');

            if (field.files.length === 0) {
        field.classList.add('is-invalid');
        const msg = document.createElement('div');
        msg.className = 'file-error text-danger small mt-1';
        msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Please upload your ' + labelText + '.';
        field.parentElement.appendChild(msg);
        return;
    }

    const file = field.files[0];
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes

    if (file.size > maxSize) {
        field.classList.add('is-invalid');
        field.value = ''; // Clear the file
        const sizeMB = (file.size / 1024 / 1024).toFixed(1);
        const msg = document.createElement('div');
        msg.className = 'file-error text-danger small mt-1';
        msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>File is too large (' + sizeMB + 'MB). Maximum allowed size is 2MB. Please compress your image and try again.';
        field.parentElement.appendChild(msg);
        return;
    }

    field.classList.add('is-valid');
    }

    // Specialty card toggle
    document.querySelectorAll('.specialty-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT') return;
            const cb = this.querySelector('input[type="checkbox"]');
            if (cb.disabled) return;
            cb.checked = !cb.checked;
            this.style.borderColor = cb.checked ? '#0F6E56' : '';
            this.style.background = cb.checked ? '#f0fff4' : '';
        });
    });

    // Handle server validation errors — jump to the right step
    @if($errors->any())
        let targetStep = 1;
        @if($errors->has('password') || $errors->has('password_confirmation') || $errors->has('terms_accepted'))
            targetStep = 6;
        @elseif($errors->has('guarantor2_first_name') || $errors->has('guarantor2_last_name') || $errors->has('guarantor2_phone') || $errors->has('guarantor2_nin') || $errors->has('guarantor2_email') || $errors->has('guarantor2_passport_path') || $errors->has('guarantor2_signature_path'))
            targetStep = 5;
        @elseif($errors->has('guarantor1_first_name') || $errors->has('guarantor1_last_name') || $errors->has('guarantor1_phone') || $errors->has('guarantor1_nin') || $errors->has('guarantor1_passport_path') || $errors->has('guarantor1_signature_path'))
            targetStep = 4;
        @elseif($errors->has('nin') || $errors->has('qualification') || $errors->has('years_of_experience') || $errors->has('bio') || $errors->has('id_card'))
            targetStep = 3;
        @elseif($errors->has('email') || $errors->has('phone') || $errors->has('address') || $errors->has('state_of_residence') || $errors->has('state_of_origin'))
            targetStep = 2;
        @endif

        for (let i = 1; i < targetStep; i++) markDone(i);
        currentStep = targetStep;
        document.querySelectorAll('.step-panel').forEach(p => p.classList.add('d-none'));
        document.getElementById('step-' + currentStep).classList.remove('d-none');
        setActive(currentStep);
        if (currentStep > 1) document.getElementById('prevBtn').style.display = 'inline-block';
        if (currentStep === 6) {
            document.getElementById('nextBtn').classList.add('d-none');
            document.getElementById('submitBtn').classList.remove('d-none');
        }
    @endif

</script>

@endsection