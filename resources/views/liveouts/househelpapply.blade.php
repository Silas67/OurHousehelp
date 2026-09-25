@extends('layouts.main')

@section('liveins')

@php
    // Files parked by an earlier failed submit (see App\Support\PendingUpload).
    $kept_id_card = \App\Support\PendingUpload::stored('pending_id_card');
    $kept_guarantor1_passport_path = \App\Support\PendingUpload::stored('pending_g1_passport');
    $kept_guarantor2_passport_path = \App\Support\PendingUpload::stored('pending_g2_passport');
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
                            <div class="step-panel registration-section" id="step-1">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user me-1" style="color:#0F6E56;"></i>
                                    <strong>Basic Information</strong>
                                </p>

                                <div class="row g-3">
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required
                                            oninput="this.value=this.value.replace(/[^a-zA-Z ]/g,'')">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                            <div class="step-panel registration-section" id="step-2">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-map-marker-alt me-1" style="color:#0F6E56;"></i>
                                    <strong>Residential Location</strong>
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

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Residential Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                            value="{{ old('address') }}" required placeholder="House No, Street, Area" onblur="validateField(this, 'Residential address')">
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ══ STEP 3: PROFESSIONAL ══ --}}
                            <div class="step-panel registration-section" id="step-3">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-briefcase me-1" style="color:#0F6E56;"></i>
                                    <strong>Work Information</strong>
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
                                            style="cursor:{{ $isLocked ? 'not-allowed' : 'pointer' }};transition:all 0.50s;">
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
                            <div class="step-panel registration-section" id="step-6">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-lock me-1" style="color:#0F6E56;"></i>
                                    <strong>Account</strong>
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
                                                <div class="mb-1">
                                                    <strong>Name:</strong>
                                                    <span id="s-name">—</span>
                                                </div>

                                                <div class="mb-1">
                                                    <strong>Email:</strong>
                                                    <span id="s-email">—</span>
                                                </div>

                                                <div class="mb-1">
                                                    <strong>Phone:</strong>
                                                    <span id="s-phone">—</span>
                                                </div>

                                                <div class="mb-1">
                                                    <strong>State:</strong>
                                                    <span id="s-state">—</span>
                                                </div>

                                                <div>
                                                    <strong>NIN:</strong>
                                                    <span id="s-nin">—</span>
                                                </div>
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
                            <div class="step-panel registration-section" id="step-4">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user-shield me-1" style="color:#0F6E56;"></i>
                                    <strong>Verification — Guarantor 1</strong>
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

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor1_address"
                                            class="form-control @error('guarantor1_address') is-invalid @enderror"
                                            value="{{ old('guarantor1_address') }}"
                                            placeholder="Guarantor's residential address" required>
                                        @error('guarantor1_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 offset-md-3">
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
                                        <div class="form-text">JPG/PNG.</div>
                                        @error('guarantor1_passport_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ══ STEP 5: GUARANTOR 2 ══ --}}
                            <div class="step-panel registration-section" id="step-5">
                                <p class="text-muted small mb-4" style="border-left:3px solid #0F6E56; padding-left:10px;">
                                    <i class="fas fa-user-shield me-1" style="color:#0F6E56;"></i>
                                    <strong>Verification — Guarantor 2</strong>
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

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                        <input type="text" name="guarantor2_address"
                                            class="form-control @error('guarantor2_address') is-invalid @enderror"
                                            value="{{ old('guarantor2_address') }}"
                                            placeholder="Guarantor's residential address" required>
                                        @error('guarantor2_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6 offset-md-3">
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
                                        <div class="form-text">JPG/PNG.</div>
                                        @error('guarantor2_passport_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="registration-submit text-center mt-4 pt-4 border-top">
                                <button type="submit" class="btn px-5 py-2" id="submitBtn"
                                    style="background:#0F6E56;color:white;font-weight:500;">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
    .registration-section {
        display: block !important;
        margin-bottom: 2rem;
    }

    #registerForm {
        display: flex;
        flex-direction: column;
    }

    /* Visual order: Basic → Location → Work → Guarantors → Account */
    #registerForm #step-1 { order: 1; }
    #registerForm #step-2 { order: 2; }
    #registerForm #step-3 { order: 3; }
    #registerForm #step-4 { order: 4; }
    #registerForm #step-5 { order: 5; }
    #registerForm #step-6 { order: 6; }
    #registerForm .registration-submit { order: 7; }

    .registration-section + .registration-section {
        padding-top: 1rem;
        border-top: 1px solid #eef1f4;
    }

    .registration-section-header h5 { color:#14243d; }

    /* The current registration collects only the fields shown on this page. */
    /* Navigation is no longer used on the one-page form. */
    #prevBtn, #nextBtn { display:none !important; }

    .form-control:focus, .form-select:focus {
        border-color:#0F6E56;
        box-shadow:0 0 0 3px rgba(15,110,86,0.1);
    }
    .specialty-card:hover:not(.bg-light) {
        border-color:#0F6E56 !important;
        background:#f0fff4;
    }
    .specialty-card input:checked ~ div .fw-semibold { color:#0F6E56; }

    @media (max-width: 767px) {
        .registration-section { margin-bottom: 1.5rem; }
    }
</style>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        // Specialty cards remain interactive on the single page.
        document.querySelectorAll('.specialty-card').forEach(card => {
            card.addEventListener('click', function (e) {
                if (e.target.tagName === 'INPUT') return;
                const cb = this.querySelector('input[type="checkbox"]');
                if (!cb || cb.disabled) return;
                cb.checked = !cb.checked;
                this.style.borderColor = cb.checked ? '#0F6E56' : '';
                this.style.background = cb.checked ? '#f0fff4' : '';
            });
        });

        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('submit', function () {
                const btn = document.getElementById('submitBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Registering...';
                }
            });
        }

        // Live duplicate checks for the fields we still collect.
        checkDuplicate('email-field', 'email', 'Email address');
        checkDuplicate('phone-field', 'phone', 'Phone number');
        checkDuplicate('nin-field', 'nin', 'NIN');

        // Wire up the live summary
        ['name', 'last_name', 'email', 'phone', 'state_of_residence', 'nin'].forEach(fieldName => {
            const el = document.querySelector(`[name="${fieldName}"]`);
            if (el) {
                el.addEventListener('input', updateSummary);
                el.addEventListener('change', updateSummary);
            }
        });
        updateSummary();
    });

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

    function previewIdCard(input) {
        if (input.files?.[0] && input.files[0].type.startsWith('image/')) {
            const r = new FileReader();
            r.onload = e => {
                const p = document.getElementById('idcard-preview');
                if (p) { p.src = e.target.result; p.style.display = 'block'; }
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function previewPhoto(input) {
        if (input.files?.[0]) {
            const r = new FileReader();
            r.onload = e => {
                const p = document.getElementById('photoPreview');
                if (p) { p.src = e.target.result; p.style.display = 'block'; }
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function togglePwd(id) {
        const f = document.getElementById(id);
        const i = f?.nextElementSibling;
        if (!f) return;
        f.type = f.type === 'password' ? 'text' : 'password';
        if (i) {
            i.classList.toggle('fa-eye');
            i.classList.toggle('fa-eye-slash');
        }
    }

    function validateAge(field) {
        const dob = new Date(field.value);
        const today = new Date();
        const age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        const isUnder18 = age < 18 || (age === 18 && monthDiff < 0) ||
            (age === 18 && monthDiff === 0 && today.getDate() < dob.getDate());

        field.parentElement.querySelector('.dob-error')?.remove();
        field.classList.remove('is-invalid', 'is-valid');

        const msg = document.createElement('div');
        msg.className = 'dob-error small mt-1';
        if (isUnder18) {
            field.classList.add('is-invalid');
            msg.classList.add('text-danger');
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>You must be at least 18 years old to register.';
        } else {
            field.classList.add('is-valid');
            msg.classList.add('text-success');
            msg.innerHTML = '<i class="fas fa-check-circle me-1"></i>Age verified.';
        }
        field.parentElement.appendChild(msg);
    }

    function validateGender(field) {
        field.parentElement.querySelector('.gender-error')?.remove();
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
        field.parentElement.querySelector('.select-error')?.remove();
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
        field.parentElement.querySelector('.field-error')?.remove();
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
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            field.classList.add('is-invalid');
            field.value = '';
            const sizeMB = (file.size / 1024 / 1024).toFixed(1);
            const msg = document.createElement('div');
            msg.className = 'file-error text-danger small mt-1';
            msg.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>File is too large (' + sizeMB + 'MB). Maximum allowed size is 10MB. Please compress your image and try again.';
            field.parentElement.appendChild(msg);
            return;
        }
        field.classList.add('is-valid');
    }

    function updateSummary() {
        const firstName =
            document.querySelector('[name="name"]')?.value || '';

        const lastName =
            document.querySelector('[name="last_name"]')?.value || '';

        document.getElementById('s-name').textContent =
            `${firstName} ${lastName}`.trim() || '—';

        document.getElementById('s-email').textContent =
            document.querySelector('[name="email"]')?.value || '—';

        document.getElementById('s-phone').textContent =
            document.querySelector('[name="phone"]')?.value || '—';

        document.getElementById('s-state').textContent =
            document.querySelector('[name="state_of_residence"]')?.value || '—';

        document.getElementById('s-nin').textContent =
            (document.querySelector('[name="nin"]')?.value || '—')
                .replace(/./g, (c, i) => i < 3 || i > 8 ? c : '*');
    }
</script>

@endsection