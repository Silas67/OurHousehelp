@extends('layouts.main')

@section('skillworkers')
    @php
        $countries = [
            'Nigeria',
            'United States',
            'United Kingdom',
            'Canada',
            'Australia',
            'France',
            'Germany',
            'India',
            'China',
            'South Africa',
            'Brazil',
            'Japan',
            'Russia',
            'Mexico',
            'Netherlands',
            'Italy',
            'Spain',
            'Turkey',
            'South Korea',
            'Saudi Arabia',
            'Argentina',
            'Indonesia',
            'Sweden',
            'Belgium',
            'Switzerland',
            'Poland',
            'Norway',
            'Denmark',
            'Ireland',
            'Singapore',
            'Greece',
            'Portugal',
            'Malaysia',
            'Thailand',
            'New Zealand',
            'Philippines',
            'Egypt',
            'Bangladesh',
            'Pakistan',
            'Vietnam',
            'Colombia',
            'UAE',
            'Israel',
            'Qatar',
            'Kuwait',
            'Finland',
            'Czech Republic',
            'Hungary',
            'Romania',
            'Chile',
            'Peru',
            'Ecuador',
            'Venezuela',
            'Morocco',
            'Kenya',
            'Ghana',
            'Algeria',
            'Sri Lanka',
            'Nigeria',
            'Ivory Coast',
            'Angola',
            'Jordan',
            'Cambodia',
            'Zambia',
            'Senegal',
            'Bahrain',
            'Kazakhstan',
            'Mongolia',
            'Uzbekistan',
            'Belarus',
            'Luxembourg',
            'Slovenia',
            'Estonia',
            'Lithuania',
            'Latvia',
            'Malta',
            'Cyprus',
            'Georgia',
            'Armenia',
            'Azerbaijan',
            'Moldova',
            'Botswana',
            'Paraguay',
            'Uruguay',
            'Bolivia',
            'Dominican Republic',
            'Honduras',
            'Guatemala',
            'El Salvador',
            'Jamaica',
            'Trinidad and Tobago',
            'Barbados',
            'Fiji',
            'Papua New Guinea',
            'Mozambique',
            'Malawi',
            'Zimbabwe',
            'Namibia',
            'Rwanda',
            'Tanzania',
            'Uganda',
            'Madagascar',
            'Gabon',
            'Mauritius',
            'Seychelles',
            'Nepal',
            'Bhutan',
            'Maldives',
            'Brunei',
            'Timor-Leste',
            'Laos',
            'Myanmar',
            'Afghanistan',
            'North Korea',
        ];
    @endphp
    <section id="onetime" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2"> {{ __('Apply as a Skilled Worker') }} </h5>
                        </div>



                        <div class="card-body container">
                            <form method="POST" action="{{ route('applicants.registerSkilledWorkers') }}"
                                enctype="multipart/form-data" id="progress-form">
                                @csrf

                                <div class="form-section">
                                    <!-- Personal Information -->
                                    <div class="form-step active">
                                        <div>
                                            <p class="display-6 py-3">Skilled Workers Personal Information</p>

                                            <!-- Date -->
                                            <div class="form-group row">
                                                <label for="dateSkill"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Date') }}</label>
                                                <div class="col-md-6">
                                                    <input id="dateSkill" type="text" class="form-control"
                                                        placeholder="Today's Date" readonly>
                                                </div>
                                            </div>

                                            <!-- Skill Selection -->
                                            <div class="form-group row pt-3">
                                                <label for="skill" class="col-md-4 col-form-label text-md-end">
                                                    {{ __('Skill') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="skill" name="specialties"
                                                        class="form-control @error('specialties') is-invalid @enderror"
                                                        required>
                                                        <option value="" disabled selected>Select your
                                                            skill</option>
                                                        @foreach ($skills as $skill)
                                                            <option value="{{ $skill->skill_name }}"
                                                                {{ old('specialties') == $skill->skill_name ? 'selected' : '' }}
                                                                data-description="{{ $skill->about_skill }}"
                                                                data-icon="{{ $skill->icon_class }}">
                                                                {{ $skill->skill_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>

                                                    <!-- Skill Description (optional display) -->
                                                    <div id="skill-description" class="mt-2 p-2 bg-light rounded"
                                                        style="display: none;">
                                                        <small class="text-muted" id="skill-description-text"></small>
                                                    </div>

                                                    @error('skill_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- Name -->
                                            <div class="form-group row pt-3">
                                                <label for="name"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name', $user->name ?? '') }}"
                                                        required autocomplete="name" autofocus
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Middle Name -->
                                            <div class="form-group row pt-3">
                                                <label for="middle_name"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>
                                                <div class="col-md-6">
                                                    <input id="middle_name" type="text"
                                                        class="form-control @error('middle_name') is-invalid @enderror"
                                                        name="middle_name"
                                                        value="{{ old('middle_name', $user->middle_name ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    @error('middle_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="form-group row pt-3">
                                                <label for="last_name"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="last_name" type="text"
                                                        class="form-control @error('last_name') is-invalid @enderror"
                                                        name="last_name"
                                                        value="{{ old('last_name', $user->last_name ?? '') }}" required
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('last_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Gender -->
                                            <div class="form-group row pt-3">
                                                <label for="gender" class="col-md-4 col-form-label text-md-end">
                                                    {{ __('Gender') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="gender" name="gender"
                                                        class="form-control @error('gender') is-invalid @enderror" required>
                                                        <option value="" disabled selected>Select your
                                                            gender</option>
                                                        <option value="male"
                                                            {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                            Male</option>
                                                        <option value="female"
                                                            {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                            Female</option>
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('gender')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Date of Birth -->
                                            <div class="form-group row pt-3">
                                                <label for="dob"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="dob" type="date"
                                                        class="form-control @error('dob') is-invalid @enderror"
                                                        name="dob" value="{{ old('dob', $user->dob ?? '') }}"
                                                        required max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('dob')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <small class="form-text text-muted">Note: You must be at
                                                        least 18 years old.</small>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group row pt-3">
                                                <label for="email"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email', $user->email ?? '') }}"
                                                        required autocomplete="email"
                                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="form-group row pt-3">
                                                <label for="phone"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="phone" type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                                        required maxlength="11" minlength="11"
                                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- Profile Photo -->
                                            <div class="form-group row pt-3">
                                                <label for="profile_photo_path_skills"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Photo') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <div class="mb-3 align-item-start">
                                                        <img id="profile-photo-preview-skills"
                                                            src="{{ asset('build/assets/image/slider1.jpg') }}"
                                                            alt="Profile Photo" class="img-thumbnail"
                                                            style="width: 150px; height: 150px;">
                                                    </div>
                                                    <input type="file" id="profile_photo_path_skills"
                                                        name="profile_photo_path"
                                                        class="form-control @error('profile_photo_path') is-invalid @enderror"
                                                        accept="image/*" required
                                                        onchange="previewImageSkills(this, 'profile-photo-preview-skills')">
                                                    <small class="text-muted">Allowed formats: jpg, png, jpeg.
                                                        Max size: 2MB.</small>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('profile_photo_path')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- Country of Residence -->
                                            <div class="form-group row pt-3">
                                                <label for="country_of_residence"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Country') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <select id="country_of_residence" name="country_of_residence"
                                                        class="form-control @error('country_of_residence') is-invalid @enderror"
                                                        required>
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country }}"
                                                                {{ old('country_of_residence', $user->country ?? 'Nigeria') == $country ? 'selected' : '' }}>
                                                                {{ $country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('country_of_residence')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- State of Residence -->
                                            <div class="form-group row pt-3">
                                                <label for="state_of_residence"
                                                    class="col-md-4 col-form-label text-md-end">
                                                    {{ __('State') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="state_of_residence" name="state_of_residence"
                                                        class="form-control @error('state_of_residence') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Select State of Residence --
                                                        </option>
                                                        <option value="Abia"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Abia' ? 'selected' : '' }}>
                                                            Abia</option>
                                                        <option value="Adamawa"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Adamawa' ? 'selected' : '' }}>
                                                            Adamawa</option>
                                                        <option value="Akwa Ibom"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Akwa Ibom' ? 'selected' : '' }}>
                                                            Akwa Ibom</option>
                                                        <option value="Anambra"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Anambra' ? 'selected' : '' }}>
                                                            Anambra</option>
                                                        <option value="Bauchi"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Bauchi' ? 'selected' : '' }}>
                                                            Bauchi</option>
                                                        <option value="Bayelsa"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Bayelsa' ? 'selected' : '' }}>
                                                            Bayelsa</option>
                                                        <option value="Benue"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Benue' ? 'selected' : '' }}>
                                                            Benue</option>
                                                        <option value="Borno"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Borno' ? 'selected' : '' }}>
                                                            Borno</option>
                                                        <option value="Cross River"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Cross River' ? 'selected' : '' }}>
                                                            Cross River</option>
                                                        <option value="Delta"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Delta' ? 'selected' : '' }}>
                                                            Delta</option>
                                                        <option value="Ebonyi"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Ebonyi' ? 'selected' : '' }}>
                                                            Ebonyi</option>
                                                        <option value="Edo"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Edo' ? 'selected' : '' }}>
                                                            Edo</option>
                                                        <option value="Ekiti"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Ekiti' ? 'selected' : '' }}>
                                                            Ekiti</option>
                                                        <option value="Enugu"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Enugu' ? 'selected' : '' }}>
                                                            Enugu</option>
                                                        <option value="Gombe"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Gombe' ? 'selected' : '' }}>
                                                            Gombe</option>
                                                        <option value="Imo"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Imo' ? 'selected' : '' }}>
                                                            Imo</option>
                                                        <option value="Jigawa"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Jigawa' ? 'selected' : '' }}>
                                                            Jigawa</option>
                                                        <option value="Kaduna"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Kaduna' ? 'selected' : '' }}>
                                                            Kaduna</option>
                                                        <option value="Kano"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Kano' ? 'selected' : '' }}>
                                                            Kano</option>
                                                        <option value="Katsina"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Katsina' ? 'selected' : '' }}>
                                                            Katsina</option>
                                                        <option value="Kebbi"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Kebbi' ? 'selected' : '' }}>
                                                            Kebbi</option>
                                                        <option value="Kogi"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Kogi' ? 'selected' : '' }}>
                                                            Kogi</option>
                                                        <option value="Kwara"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Kwara' ? 'selected' : '' }}>
                                                            Kwara</option>
                                                        <option value="Lagos"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Lagos' ? 'selected' : '' }}>
                                                            Lagos</option>
                                                        <option value="Nasarawa"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Nasarawa' ? 'selected' : '' }}>
                                                            Nasarawa</option>
                                                        <option value="Niger"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Niger' ? 'selected' : '' }}>
                                                            Niger</option>
                                                        <option value="Ogun"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Ogun' ? 'selected' : '' }}>
                                                            Ogun</option>
                                                        <option value="Ondo"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Ondo' ? 'selected' : '' }}>
                                                            Ondo</option>
                                                        <option value="Osun"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Osun' ? 'selected' : '' }}>
                                                            Osun</option>
                                                        <option value="Oyo"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Oyo' ? 'selected' : '' }}>
                                                            Oyo</option>
                                                        <option value="Plateau"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Plateau' ? 'selected' : '' }}>
                                                            Plateau</option>
                                                        <option value="Rivers"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Rivers' ? 'selected' : '' }}>
                                                            Rivers</option>
                                                        <option value="Sokoto"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Sokoto' ? 'selected' : '' }}>
                                                            Sokoto</option>
                                                        <option value="Taraba"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Taraba' ? 'selected' : '' }}>
                                                            Taraba</option>
                                                        <option value="Yobe"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Yobe' ? 'selected' : '' }}>
                                                            Yobe</option>
                                                        <option value="Zamfara"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'Zamfara' ? 'selected' : '' }}>
                                                            Zamfara</option>
                                                        <option value="FCT - Abuja"
                                                            {{ old('state_of_residence', $user->state_of_residence ?? '') == 'FCT - Abuja' ? 'selected' : '' }}>
                                                            FCT - Abuja</option>
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('state_of_residence')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- LGA of Origin -->
                                            <div class="form-group row pt-3">
                                                <label for="lga_of_origin"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('LGA') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="lga_of_origin" type="text"
                                                        class="form-control @error('lga_of_origin') is-invalid @enderror"
                                                        name="lga_of_origin"
                                                        value="{{ old('lga_of_origin', $user->lga_of_origin ?? '') }}"
                                                        required>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('lga_of_origin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="form-group row pt-3">
                                                <label for="address"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Address') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3"
                                                        required>{{ old('address', $user->address ?? '') }}</textarea>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('address')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Landmark -->
                                            <div class="form-group row pt-3">
                                                <label for="landmark"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Landmark') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="landmark" type="text"
                                                        class="form-control @error('landmark') is-invalid @enderror"
                                                        name="landmark"
                                                        value="{{ old('landmark', $user->landmark ?? '') }}" required>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    @error('landmark')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <!-- Applicant Type -->
                                        <div class="form-group row pt-3" hidden>
                                            <label for="applicant_type"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Applicant Type') }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-6">
                                                <select id="applicant_type" name="applicant_type"
                                                    class="form-control @error('applicant_type') is-invalid @enderror"
                                                    required>
                                                    <option value="skill" selected>Skilled-Worker
                                                    </option>
                                                </select>
                                                <small class="text-danger error-message d-none">This field
                                                    is
                                                    required.</small>
                                                @error('applicant_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group row pt-3">
                                            <label for="password"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-6 position-relative">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password"
                                                    minlength="8">
                                                <small class="text-danger error-message d-none">This field is required.</small>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <small class="form-text text-muted">Minimum 8 characters.</small>
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="form-group row pt-3">
                                            <label for="password_confirmation"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-6 position-relative">
                                                <input id="password_confirmation" type="password"
                                                    class="form-control" name="password_confirmation" required
                                                    autocomplete="new-password" minlength="8">
                                            </div>
                                        </div>

                                        <!-- Terms -->
                                        <div class="form-group row py-3 bg-warning">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                                        type="checkbox" name="terms_accepted" id="terms_accepted"
                                                        required {{ old('terms_accepted') ? 'checked' : '' }}>
                                                    <small class="text-danger error-message d-none">This field
                                                        is required.</small>
                                                    <label class="form-check-label" for="terms_accepted">
                                                        {{ __('I accept the terms and conditions') }} <span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    @error('terms_accepted')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group row mb-0 pt-3">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-sm"
                                                    style="width: 250px" onclick="disableButton(this)">
                                                    {{ __('Register') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <script>
                            // Set today's date
                            document.addEventListener('DOMContentLoaded', function() {
                                // Set current date
                                const optionsSkill = {
                                    weekday: 'short',
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                };
                                const todaySkill = new Date().toLocaleDateString('en-US', optionsSkill);
                                document.getElementById('dateSkill').value = todaySkill;

                                // Skill description display functionality
                                const skillSelect = document.getElementById('skill');
                                const descriptionBox = document.getElementById('skill-description');
                                const descriptionText = document.getElementById('skill-description-text');

                                if (skillSelect) {
                                    skillSelect.addEventListener('change', function() {
                                        const selectedOption = this.options[this.selectedIndex];

                                        if (selectedOption.value && selectedOption.dataset.description) {
                                            descriptionText.textContent = selectedOption.dataset.description;
                                            descriptionBox.style.display = 'block';

                                            // You could also use the icon class if needed
                                            // const iconClass = selectedOption.dataset.icon;
                                            // console.log('Icon class:', iconClass);
                                        } else {
                                            descriptionBox.style.display = 'none';
                                        }
                                    });

                                    // Trigger change event if there's a previously selected value
                                    if (skillSelect.value) {
                                        skillSelect.dispatchEvent(new Event('change'));
                                    }
                                }

                                // Age validation for date of birth
                                const dobInput = document.getElementById('dob');
                                if (dobInput) {
                                    dobInput.addEventListener('change', function() {
                                        const dob = new Date(this.value);
                                        const today = new Date();
                                        const age = today.getFullYear() - dob.getFullYear();
                                        const monthDiff = today.getMonth() - dob.getMonth();

                                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                                            age--;
                                        }

                                        if (age < 18) {
                                            alert('You must be at least 18 years old.');
                                            this.value = '';
                                        }
                                    });
                                }
                            });

                            // Image preview function for skills profile photo
                            function previewImageSkills(input, previewId) {
                                const preview = document.getElementById(previewId);
                                const file = input.files[0];
                                const reader = new FileReader();

                                reader.onloadend = function() {
                                    preview.src = reader.result;
                                }

                                if (file) {
                                    // Validate file size (2MB max)
                                    if (file.size > 2 * 1024 * 1024) {
                                        alert('File size exceeds 2MB limit. Please choose a smaller file.');
                                        input.value = '';
                                        preview.src = "{{ asset('build/assets/image/slider1.jpg') }}";
                                        return;
                                    }

                                    // Validate file type
                                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                    if (!validTypes.includes(file.type)) {
                                        alert('Only JPG, JPEG, and PNG files are allowed.');
                                        input.value = '';
                                        preview.src = "{{ asset('build/assets/image/slider1.jpg') }}";
                                        return;
                                    }

                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = "{{ asset('build/assets/image/slider1.jpg') }}";
                                }
                            }

                            // Form validation and submission
                            function disableButton(button) {
                                // First validate the form
                                const form = document.getElementById('progress-form');
                                let isValid = true;

                                // Check all required fields
                                const requiredFields = form.querySelectorAll('[required]');
                                requiredFields.forEach(field => {
                                    if (!field.value) {
                                        isValid = false;
                                        field.classList.add('is-invalid');
                                        const errorMsg = field.nextElementSibling;
                                        if (errorMsg && errorMsg.classList.contains('error-message')) {
                                            errorMsg.classList.remove('d-none');
                                        }
                                    }
                                });

                                // Check terms checkbox
                                const termsCheckbox = document.getElementById('terms_accepted');
                                if (!termsCheckbox.checked) {
                                    isValid = false;
                                    termsCheckbox.classList.add('is-invalid');
                                    const errorMsg = termsCheckbox.nextElementSibling;
                                    if (errorMsg && errorMsg.classList.contains('error-message')) {
                                        errorMsg.classList.remove('d-none');
                                    }
                                }

                                if (!isValid) {
                                    // Scroll to the first error
                                    const firstError = form.querySelector('.is-invalid');
                                    if (firstError) {
                                        firstError.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                    return false;
                                }

                                // If form is valid, disable button and submit
                                button.disabled = true;
                                button.innerHTML = 'Registering...';
                                form.submit();
                            }
                        </script>




                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            let checkboxes = document.querySelectorAll("input[name='specialties[]']");
            let checkedOne = Array.from(checkboxes).some(checkbox => checkbox.checked);

            if (!checkedOne) {
                event.preventDefault();
                alert("Please select at least one specialty.");
            }
        });
    </script>
@endsection
