@extends('layouts.main')

@section('liveouts')
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: rgb(253, 214, 214);
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .popup-content {
            position: relative;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .close-button:hover {
            color: black;
        }

        #popup-message {
            font-size: 1em;
            color: #333;
        }

        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            position: relative;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }


        .progress-bar::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: #e0e0e0;
            transform: translateY(-50%);
            z-index: 0;
        }

        .progress {
            position: absolute;
            top: 50%;
            left: 0;
            height: 4px;
            background: #4caf50;
            width: 0%;
            transition: width 0.3s;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            width: 10px;
            height: 10px;
            background: #e0e0e0;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        .step.active {
            background: #4caf50;
            color: #fff;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }
    </style>
    
    <section id="onetime" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2"> {{ __('Apply as a Househelp') }} </h5>
                        </div>

                        <div class="card-body container">
                            <div id="error-popup" class="popup">
                                <div class="popup-content">
                                    <span id="close-popup" class="close-button">&times;</span>
                                    <p id="popup-message"></p>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress" id="progress"></div>
                                <div class="step active">1</div>
                                <div class="step">2</div>
                                <div class="step">3</div>
                                <div class="step">4</div>
                                <div class="step">5</div>
                                <div class="step">6</div>
                            </div>
                        <form method="POST" action="{{ route('applicants.register') }}" enctype="multipart/form-data"
                                id="progress-form">
                                @csrf

                                <div class="form-section">
                                    <!-- Personal Infomation -->
                                    <div class="form-step active">
                                        <div>
                                            <p class="display-6 py-3">Personal Infomation</p>
                                            <!-- Name -->
                                            <div class="form-group row">
                                                <label for="name"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" required
                                                        autocomplete="name" autofocus
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
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
                                                        name="middle_name" value="{{ old('middle_name') }}"
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
                                                        name="last_name" value="{{ old('last_name') }}" required
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
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
                                                <div class="col-md-6" required>
                                                    <select id="gender" name="gender"
                                                        class="form-control @error('gender') is-invalid @enderror" required>
                                                        <option value="" disabled selected>Select your gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('gender')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- Date of Birth -->
                                            <div class="form-group row mt-3">
                                                <label for="dob"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="dob" type="date"
                                                        class="form-control @error('dob') is-invalid @enderror"
                                                        name="dob" value="{{ old('dob', Auth::user()->dob ?? '') }}"
                                                        required>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('dob')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                    <small class="form-text text-muted">Note: You must be at least 18 years
                                                        old.</small>
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
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email"
                                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="form-group row mt-3">
                                                <label for="phone"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input required id="phone" type="phone"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone"
                                                        value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                                        maxlength="11" minlength="11"
                                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    <div id="phone-error" class="text-danger mt-2"
                                                        style="display: none;">
                                                    </div>
                                                    @error('phone')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- Applicant Type -->
                                            <div class="form-group row pt-3" hidden>
                                                <label for="applicant_type"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Applicant Type') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <select id="applicant_type" name="applicant_type"
                                                        class="form-control @error('applicant_type') is-invalid @enderror"
                                                        required>
                                                        <option value="" disabled selected>Select type</option>
                                                        <option value="live-in" selected>Live-In</option>
                                                        <option value="live-out">Live-Out</option>
                                                        <option value="semi-live-in">Semi Live-In</option>
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('applicant_type')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>


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

                                            <!-- Country of origin-->
                                            <div class="form-group row mt-3">
                                                <label for="country"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Country of Origin') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <select id="country" name="country"
                                                        class="form-control @error('country') is-invalid @enderror"
                                                        required>
                                                        <small class="text-danger error-message d-none">This field is
                                                            required.</small>
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country }}"
                                                                {{ old('country', Auth::user()->country ?? 'Nigeria') == $country ? 'selected' : '' }}>
                                                                {{ $country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('country')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- State of Origin -->
                                            <div class="form-group row pt-3">
                                                <label for="state_of_origin" class="col-md-4 col-form-label text-md-end">
                                                    {{ __('State of Origin') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="state_of_origin"
                                                        class="form-control @error('state_of_origin') is-invalid @enderror"
                                                        name="state_of_origin">
                                                        <option value="">-- Select State of Origin --</option>
                                                        <option value="Abia"
                                                            {{ old('state_of_origin') == 'Abia' ? 'selected' : '' }}>Abia
                                                        </option>
                                                        <option value="Adamawa"
                                                            {{ old('state_of_origin') == 'Adamawa' ? 'selected' : '' }}>
                                                            Adamawa
                                                        </option>
                                                        <option value="Akwa Ibom"
                                                            {{ old('state_of_origin') == 'Akwa Ibom' ? 'selected' : '' }}>
                                                            Akwa
                                                            Ibom
                                                        </option>
                                                        <option value="Anambra"
                                                            {{ old('state_of_origin') == 'Anambra' ? 'selected' : '' }}>
                                                            Anambra
                                                        </option>
                                                        <option value="Bauchi"
                                                            {{ old('state_of_origin') == 'Bauchi' ? 'selected' : '' }}>
                                                            Bauchi
                                                        </option>
                                                        <option value="Bayelsa"
                                                            {{ old('state_of_origin') == 'Bayelsa' ? 'selected' : '' }}>
                                                            Bayelsa
                                                        </option>
                                                        <option value="Benue"
                                                            {{ old('state_of_origin') == 'Benue' ? 'selected' : '' }}>Benue
                                                        </option>
                                                        <option value="Borno"
                                                            {{ old('state_of_origin') == 'Borno' ? 'selected' : '' }}>Borno
                                                        </option>
                                                        <option value="Cross River"
                                                            {{ old('state_of_origin') == 'Cross River' ? 'selected' : '' }}>
                                                            Cross
                                                            River
                                                        </option>
                                                        <option value="Delta"
                                                            {{ old('state_of_origin') == 'Delta' ? 'selected' : '' }}>Delta
                                                        </option>
                                                        <option value="Ebonyi"
                                                            {{ old('state_of_origin') == 'Ebonyi' ? 'selected' : '' }}>
                                                            Ebonyi
                                                        </option>
                                                        <option value="Edo"
                                                            {{ old('state_of_origin') == 'Edo' ? 'selected' : '' }}>Edo
                                                        </option>
                                                        <option value="Ekiti"
                                                            {{ old('state_of_origin') == 'Ekiti' ? 'selected' : '' }}>Ekiti
                                                        </option>
                                                        <option value="Enugu"
                                                            {{ old('state_of_origin') == 'Enugu' ? 'selected' : '' }}>Enugu
                                                        </option>
                                                        <option value="Gombe"
                                                            {{ old('state_of_origin') == 'Gombe' ? 'selected' : '' }}>Gombe
                                                        </option>
                                                        <option value="Imo"
                                                            {{ old('state_of_origin') == 'Imo' ? 'selected' : '' }}>Imo
                                                        </option>
                                                        <option value="Jigawa"
                                                            {{ old('state_of_origin') == 'Jigawa' ? 'selected' : '' }}>
                                                            Jigawa
                                                        </option>
                                                        <option value="Kaduna"
                                                            {{ old('state_of_origin') == 'Kaduna' ? 'selected' : '' }}>
                                                            Kaduna
                                                        </option>
                                                        <option value="Kano"
                                                            {{ old('state_of_origin') == 'Kano' ? 'selected' : '' }}>Kano
                                                        </option>
                                                        <option value="Katsina"
                                                            {{ old('state_of_origin') == 'Katsina' ? 'selected' : '' }}>
                                                            Katsina
                                                        </option>
                                                        <option value="Kebbi"
                                                            {{ old('state_of_origin') == 'Kebbi' ? 'selected' : '' }}>Kebbi
                                                        </option>
                                                        <option value="Kogi"
                                                            {{ old('state_of_origin') == 'Kogi' ? 'selected' : '' }}>Kogi
                                                        </option>
                                                        <option value="Kwara"
                                                            {{ old('state_of_origin') == 'Kwara' ? 'selected' : '' }}>Kwara
                                                        </option>
                                                        <option value="Lagos"
                                                            {{ old('state_of_origin') == 'Lagos' ? 'selected' : '' }}>Lagos
                                                        </option>
                                                        <option value="Nasarawa"
                                                            {{ old('state_of_origin') == 'Nasarawa' ? 'selected' : '' }}>
                                                            Nasarawa
                                                        </option>
                                                        <option value="Niger"
                                                            {{ old('state_of_origin') == 'Niger' ? 'selected' : '' }}>Niger
                                                        </option>
                                                        <option value="Ogun"
                                                            {{ old('state_of_origin') == 'Ogun' ? 'selected' : '' }}>Ogun
                                                        </option>
                                                        <option value="Ondo"
                                                            {{ old('state_of_origin') == 'Ondo' ? 'selected' : '' }}>Ondo
                                                        </option>
                                                        <option value="Osun"
                                                            {{ old('state_of_origin') == 'Osun' ? 'selected' : '' }}>Osun
                                                        </option>
                                                        <option value="Oyo"
                                                            {{ old('state_of_origin') == 'Oyo' ? 'selected' : '' }}>Oyo
                                                        </option>
                                                        <option value="Plateau"
                                                            {{ old('state_of_origin') == 'Plateau' ? 'selected' : '' }}>
                                                            Plateau
                                                        </option>
                                                        <option value="Rivers"
                                                            {{ old('state_of_origin') == 'Rivers' ? 'selected' : '' }}>
                                                            Rivers
                                                        </option>
                                                        <option value="Sokoto"
                                                            {{ old('state_of_origin') == 'Sokoto' ? 'selected' : '' }}>
                                                            Sokoto
                                                        </option>
                                                        <option value="Taraba"
                                                            {{ old('state_of_origin') == 'Taraba' ? 'selected' : '' }}>
                                                            Taraba
                                                        </option>
                                                        <option value="Yobe"
                                                            {{ old('state_of_origin') == 'Yobe' ? 'selected' : '' }}>Yobe
                                                        </option>
                                                        <option value="Zamfara"
                                                            {{ old('state_of_origin') == 'Zamfara' ? 'selected' : '' }}>
                                                            Zamfara
                                                        </option>
                                                        <option value="FCT"
                                                            {{ old('state_of_origin') == 'FCT' ? 'selected' : '' }}>FCT
                                                        </option>
                                                    </select>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('state_of_origin')
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
                                                        required>{{ old('address') }}</textarea>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('address')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Profile Photo -->
                                            <div class="form-group row">
                                                <label for="profile_photo_path"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Photo') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">

                                                    <div class="mb-3 align-item-start">
                                                        <x-img src="build/assets/image/slider1.jpg" id="profile-photo-preview" alt="Profile Photo" class="img-thumbnail" style="width: 150px; height: 150px;" loading="lazy" decoding="async" />
                                                    </div>

                                                    <input type="file" id="profile_photo_path"
                                                        name="profile_photo_path"
                                                        class="form-control @error('profile_photo_path') is-invalid @enderror"
                                                        accept="image/*"
                                                        onchange="previewImage('profile_photo_path', 'profile-photo-preview')"
                                                        required>
                                                        <small class="text-muted">Allowed formats: jpg, png, jpeg. Max size: 2MB.</small>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('profile_photo_path')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- ID Card -->
                                            <div class="form-group row mt-3">
                                                <label for="id_card"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('ID Card') }}</label>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <x-img src="build/assets/image/slider1.jpg" id="idcard-preview" alt="ID Card" class="img-thumbnail" style="width: 250px; height: 150px;" loading="lazy" decoding="async" />
                                                    </div>
                                                    <input type="file" id="id_card" name="id_card"
                                                        class="form-control @error('id_card') is-invalid @enderror"
                                                        accept="image/*"
                                                        onchange="previewImage('id_card', 'idcard-preview')">
                                                        <small class="text-muted">Allowed formats: jpg, png, jpeg. Max size: 2MB.</small>
                                                    @error('id_card')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="form-group row mt-3">
                                                <label for="country_of_residence"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Country of Residence') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <select id="country_of_residence" name="country_of_residence"
                                                        class="form-control @error('country_of_residence') is-invalid @enderror"
                                                        required>
                                                        <small class="text-danger error-message d-none">This field is
                                                            required.</small>
                                                        <option value="">Select Country</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country }}"
                                                                {{ old('country_of_residence', Auth::user()->country ?? 'Nigeria') == $country ? 'selected' : '' }}>
                                                                {{ $country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('country_of_residence')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- State of Residence -->
                                            <div class="form-group row pt-3">
                                                <label for="state_of_residence"
                                                    class="col-md-4 col-form-label text-md-end">
                                                    {{ __('State of Residence') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="state_of_residence"
                                                        class="form-control @error('state_of_residence') is-invalid @enderror"
                                                        name="state_of_residence">
                                                        <option value="">-- Select State of Residence --</option>
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
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
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
                                                    class="col-md-4 col-form-label text-md-end">{{ __('LGA of Origin of Residence') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="lga_of_origin" type="text"
                                                        class="form-control @error('lga_of_origin') is-invalid @enderror"
                                                        name="lga_of_origin"
                                                        value="{{ old('lga_of_origin', $user->lga_of_origin ?? '') }}"
                                                        required>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
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
                                                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
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
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('landmark')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <!-- Years of Experience -->
                                            <div class="form-group row pt-3">
                                                <label for="years_of_experience"
                                                    class="col-md-4 col-form-label text-md-end">
                                                    {{ __('Years of Experience') }}<span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-6">
                                                    <select id="years_of_experience"
                                                        class="form-control @error('years_of_experience') is-invalid @enderror"
                                                        name="years_of_experience" required>
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
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('years_of_experience')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- NIN -->
                                            <div class="form-group row pt-3">
                                                <label for="nin"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('NIN') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input id="nin" type="text"
                                                        class="form-control @error('nin') is-invalid @enderror"
                                                        name="nin" minlength="11" maxlength="11"
                                                        value="{{ old('nin', $user->nin ?? '') }}" required
                                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    @error('nin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>



                                            <!-- Bio -->
                                            <div class="form-group row pt-3">
                                                <label for="bio"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('About You') }}<span
                                                        class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio" rows="3">{{ old('bio') }}</textarea>
                                                    @error('bio')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="button" class="next btn btn-primary btn-sm"
                                            style="width: 250px">Next</button>
                                    </div>

                                    <!-- Select your specialties -->
                                    <div class="form-step">
                                        <div>
                                            <p class="display-6 py-3">Select your specialties</p>
                                            <div class="text-center mb-40">
                                                <p class="mt-2">
                                                    <small>
                                                        <strong class="text-danger"> Tip: </strong><br>
                                                        Select more specialties to increase your visibility in searches.
                                                        <br>
                                                        The
                                                        more skills you
                                                        offer, the greater your chances of being noticed by potential
                                                        clients!
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="row">
                                                @foreach ($services as $service)
                                                    <div class="col-md-4 mb-4 d-flex align-items-stretch">
                                                        <div class="card text-center p-3 h-100">
                                                            <input type="checkbox" name="specialties[]"
                                                                value="{{ ucfirst($service->service_name) }}"
                                                                id="{{ ucfirst($service->service_name) }}"
                                                                class="mb-2"
                                                                {{ in_array(ucfirst($service->service_name), $applicant->specialties ?? []) ? 'checked' : '' }}>
                                                            <label for="{{ ucfirst($service->service_name) }}" class="fw-bold" style="font-weight: bolder">
                                                                {{ ucfirst($service->service_name) }}
                                                            </label>
                                                            <i class="fas fa-plus fa-2x mb-2"></i>
                                                            <p>{{ ucfirst($service->about_service) }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                        <hr>
                                        <button type="button" class="prev btn btn-secondary btn-sm"
                                            style="width: 250px">Previous</button>
                                        <button type="button" class="next btn btn-primary btn-sm"
                                            style="width: 250px">Next</button>
                                    </div>

                                    <!-- Fill Guarantors' Information Gurantor One -->
                                    <div class="form-step">
                                        <div>
                                            <p class="display-6 py-3">Fill Guarantors' Information</p>

                                            <h5>Gurantor One</h5>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_first_name" class="form-label">First
                                                        Name:</label>
                                                    <input type="text" name="guarantor1_first_name"
                                                        id="guarantor1_first_name" class="form-control"
                                                        placeholder="Enter First Name" required
                                                        value="{{ old('guarantor1_first_name', $guarantor1->guarantor1_first_name ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_last_name" class="form-label">Last
                                                        Name:</label>
                                                    <input type="text" name="guarantor1_last_name"
                                                        id="guarantor1_last_name" class="form-control"
                                                        placeholder="Enter Last Name" required
                                                        value="{{ old('guarantor1_last_name', $guarantor1->guarantor1_last_name ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_phone" class="form-label">Phone Number:</label>
                                                    <input type="tel" name="guarantor1_phone" id="guarantor1_phone"
                                                        class="form-control" placeholder="Enter Phone Number" required
                                                        maxlength="11" minlength="11"
                                                        value="{{ old('guarantor1_phone', $guarantor1->guarantor1_phone ?? '') }}"
                                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    <div id="guarantor1-phone-error" class="text-danger mt-2"
                                                        style="display: none;">
                                                    </div>
                                                </div>


                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_email" class="form-label">Email
                                                        (Optional):</label>
                                                    <input type="email" name="guarantor1_email" id="guarantor1_email"
                                                        class="form-control" placeholder="Enter Email Address"
                                                        value="{{ old('email', $guarantor1->email ?? '') }}"
                                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_relationship"
                                                        class="form-label">Relationship:</label>
                                                    <input type="text" name="guarantor1_relationship"
                                                        id="guarantor1_relationship" class="form-control"
                                                        placeholder="Enter Relationship" required
                                                        value="{{ old('guarantor1_relationship', $guarantor1->guarantor1_relationship ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor1_occupation"
                                                        class="form-label">Occupation:</label>
                                                    <input type="text" name="guarantor1_occupation"
                                                        id="guarantor1_occupation" class="form-control"
                                                        placeholder="Enter Occupation" required
                                                        value="{{ old('guarantor1_occupation', $guarantor1->guarantor1_occupation ?? '') }}">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="guarantor1_address" class="form-label">Address:</label>
                                                <input type="text" name="guarantor1_address" id="guarantor1_address"
                                                    class="form-control" placeholder="Enter Full Address" required
                                                    value="{{ old('guarantor1_address', $guarantor1->guarantor1_address ?? '') }}">
                                                <small class="text-danger error-message d-none">This field is
                                                    required.</small>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="guarantor1_passport" class="form-label">Passport
                                                        Photo:</label>
                                                    <img id="guarantor1-passport-preview"
                                                        src="{{ old('guarantor1_passport_path', isset($guarantor1->guarantor1_passport_path) ? \App\Support\SecureDocument::url('passports', $guarantor1->guarantor1_passport_path) : asset('build/assets/image/slider1.jpg')) }}"
                                                        alt="Passport Photo" class="img-thumbnail d-block mb-2"
                                                        style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                                                    <input type="file" name="guarantor1_passport_path"
                                                        id="guarantor1_passport" class="form-control" accept="image/*"
                                                        onchange="previewImage('guarantor1_passport', 'guarantor1-passport-preview')"
                                                        required>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="guarantor1_signature" class="form-label">ID Card:</label>
                                                    <img id="guarantor1-signature-preview"
                                                        src="{{ old('guarantor1_signature_path', isset($guarantor1->guarantor1_signature_path) ? \App\Support\SecureDocument::url('signatures', $guarantor1->guarantor1_signature_path) : asset('build/assets/image/slider1.jpg')) }}"
                                                        alt="ID Card" class="img-thumbnail d-block mb-2"
                                                        style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                                                    <input type="file" name="guarantor1_signature_path"
                                                        id="guarantor1_signature" class="form-control" accept="image/*"
                                                        onchange="previewImage('guarantor1_signature', 'guarantor1-signature-preview')">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="button" class="prev btn btn-secondary btn-sm"
                                            style="width: 250px">Previous</button>
                                        <button type="button" class="next btn btn-primary btn-sm"
                                            style="width: 250px">Next</button>
                                    </div>

                                    <!-- Fill Guarantors' Information Gurantor two-->
                                    <div class="form-step">
                                        <div>
                                            <p class="display-6 py-3">Fill Guarantors' Information</p>
                                            <h5>Gurantor Two</h5>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_first_name" class="form-label">First
                                                        Name:</label>
                                                    <input type="text" name="guarantor2_first_name"
                                                        id="guarantor2_first_name" class="form-control"
                                                        placeholder="Enter First Name" required
                                                        value="{{ old('guarantor2_first_name', $guarantor2->guarantor2_first_name ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_last_name" class="form-label">Last
                                                        Name:</label>
                                                    <input type="text" name="guarantor2_last_name"
                                                        id="guarantor2_last_name" class="form-control"
                                                        placeholder="Enter Last Name" required
                                                        value="{{ old('guarantor2_last_name', $guarantor2->guarantor2_last_name ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_phone" class="form-label">Phone Number:</label>
                                                    <input type="tel" name="guarantor2_phone" id="guarantor2_phone"
                                                        class="form-control" placeholder="Enter Phone Number" required
                                                        maxlength="11" minlength="11"
                                                        value="{{ old('guarantor2_phone', $guarantor2->guarantor2_phone ?? '') }}"
                                                        oninput="this.value = this.value.replace(/\D/g, '').replace(/\s/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    <div id="guarantor2-phone-error" class="text-danger mt-2"
                                                        style="display: none;">
                                                    </div>
                                                </div>



                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_email" class="form-label">Email
                                                        (Optional):</label>
                                                    <input type="email" name="guarantor2_email" id="guarantor2_email"
                                                        class="form-control" placeholder="Enter Email Address"
                                                        value="{{ old('guarantor2_email', $guarantor2->guarantor2_email ?? '') }}"
                                                        oninput="this.value = this.value.replace(/\s/g, '')">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_relationship"
                                                        class="form-label">Relationship:</label>
                                                    <input type="text" name="guarantor2_relationship"
                                                        id="guarantor2_relationship" class="form-control"
                                                        placeholder="Enter Relationship" required
                                                        value="{{ old('guarantor2_relationship', $guarantor2->guarantor2_relationship ?? '') }}"
                                                        oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="guarantor2_occupation"
                                                        class="form-label">Occupation:</label>
                                                    <input type="text" name="guarantor2_occupation"
                                                        id="guarantor2_occupation" class="form-control"
                                                        placeholder="Enter Occupation" required
                                                        value="{{ old('guarantor2_occupation', $guarantor2->guarantor2_occupation ?? '') }}">
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="guarantor2_address" class="form-label">Address:</label>
                                                <input type="text" name="guarantor2_address" id="guarantor2_address"
                                                    class="form-control" placeholder="Enter Full Address" required
                                                    value="{{ old('aguarantor2_address', $guarantor2->guarantor2_address ?? '') }}">
                                                <small class="text-danger error-message d-none">This field is
                                                    required.</small>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="guarantor2_passport" class="form-label">Passport
                                                        Photo:</label>
                                                    <img id="guarantor2-passport-preview"
                                                        src="{{ old('guarantor2_passport_path', isset($guarantor2->guarantor2_passport_path) ? \App\Support\SecureDocument::url('passports', $guarantor2->guarantor2_passport_path) : asset('build/assets/image/slider1.jpg')) }}"
                                                        alt="Passport Photo" class="img-thumbnail d-block mb-2"
                                                        style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                                                    <input type="file" name="guarantor2_passport_path"
                                                        id="guarantor2_passport" class="form-control" accept="image/*"
                                                        onchange="previewImage('guarantor2_passport', 'guarantor2-passport-preview')"
                                                        required>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="guarantor2_signature" class="form-label">ID Card:</label>
                                                    <img id="guarantor2-signature-preview"
                                                        src="{{ old('guarantor2_signature_path', isset($guarantor2->guarantor2_signature_path) ? \App\Support\SecureDocument::url('signatures', $guarantor2->guarantor2_signature_path) : asset('build/assets/image/slider1.jpg')) }}"
                                                        alt="ID Card" class="img-thumbnail d-block mb-2"
                                                        style="width: 150px; height: 150px;" loading="lazy" decoding="async">
                                                    <input type="file" name="guarantor2_signature_path"
                                                        id="guarantor2_signature" class="form-control" accept="image/*"
                                                        onchange="previewImage('guarantor2_signature', 'guarantor2-signature-preview')">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="button" class="prev btn btn-secondary btn-sm"
                                            style="width: 250px">Previous</button>
                                        <button type="button" class="next btn btn-primary btn-sm"
                                            style="width: 250px">Next</button>
                                    </div>

                                    <!-- Applicant Terms & Conditions -->
                                    <div class="form-step">
                                        <div>
                                            <p class="display-6 py-3">Applicant Terms & Conditions</p>
                                            <p class="intro-text">Welcome to Our Team!</p>
                                            <p class="intro-text">
                                                Please read these Terms and Conditions carefully. By agreeing to work with
                                                us,
                                                you
                                                acknowledge and accept the conditions outlined below.
                                            </p>
                                            <div class="applicant-terms  text-md-start">
                                                <h4 class="subsection-title">1. Introduction</h4>
                                                <p class="subsection-text">
                                                    These Terms and Conditions apply to all individuals working with us. By
                                                    accepting an
                                                    offer to work with us or using our services, you agree to comply with
                                                    these
                                                    terms.
                                                    If you disagree with any part of these terms, please inform us
                                                    immediately.
                                                </p>

                                                <h4 class="subsection-title">2. Employee Obligations</h4>
                                                <p class="subsection-text">As an employee, you agree to:</p>
                                                <ul>
                                                    <li>Provide accurate and complete information.</li><br>
                                                    <li>Keep your personal and employment details updated.</li><br>
                                                    <li>Maintain the confidentiality of any login credentials or sensitive
                                                        information.</li><br>
                                                    <li>Use our services and resources responsibly and not for any unlawful
                                                        purpose.</li><br>
                                                </ul>

                                                <h4 class="subsection-title">3. Privacy Policy</h4>
                                                <p class="subsection-text">
                                                    We value your privacy. Please review our <a
                                                        href="{{ route('applicantprivacy') }}" target="_blank">Privacy
                                                        Policy</a> to
                                                    understand how we collect, use, and safeguard your personal information.
                                                </p>

                                                <h4 class="subsection-title">4. Limitation of Liability</h4>
                                                <p class="subsection-text">
                                                    We will not be held liable for any indirect, incidental, or
                                                    consequential
                                                    damages
                                                    arising from your use of our services. We strive to ensure a safe
                                                    working
                                                    environment, but we cannot be responsible for external factors outside
                                                    our
                                                    control.
                                                </p>

                                                <h4 class="subsection-title">5. Modifications to Terms</h4>
                                                <p class="subsection-text">
                                                    We reserve the right to modify these Terms and Conditions at any time.
                                                    Any
                                                    updates
                                                    will be effective immediately upon being posted on this page. Continued
                                                    use
                                                    of
                                                    our
                                                    services after changes indicates acceptance of the new terms.
                                                </p>

                                                <h4 class="subsection-title">6. Termination</h4>
                                                <p class="subsection-text">
                                                    We may suspend or terminate access to services without prior notice in
                                                    cases
                                                    of
                                                    policy violations or any breach of these terms.
                                                </p>

                                                <h4 class="subsection-title">7. Governing Law</h4>
                                                <p class="subsection-text">
                                                    These Terms and Conditions are governed by the laws of [Your
                                                    Jurisdiction].
                                                    Any
                                                    disputes arising from these terms will be subject to the exclusive
                                                    jurisdiction
                                                    of
                                                    the courts in [Your Jurisdiction].
                                                </p>

                                                <h4 class="subsection-title">8. Contact Us</h4>
                                                <p class="subsection-text">
                                                    For any questions or concerns regarding these terms, please do not
                                                    hesitate
                                                    to
                                                    <a href="/#contact" target="_blank">contact us</a>.
                                                </p>
                                            </div>




                                        </div>
                                        <hr>
                                        <button type="button" class="prev btn btn-secondary btn-sm"
                                            style="width: 250px">Previous</button>
                                        <button type="button" class="next btn btn-primary btn-sm"
                                            style="width: 250px">Next</button>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-step">
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
                                                        required>
                                                    <small class="text-danger error-message d-none">This field is
                                                        required.</small>
                                                    <label class="form-check-label" for="terms_accepted">
                                                        {{ __('I accept the terms and conditions') }}
                                                        <span class="text-danger">*</span></label>
                                                    @error('terms_accepted')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="form-group row mb-0 pt-3">
                                                <div class="text-center">
                                                    <hr>
                                                    <button type="button" class="prev btn btn-secondary btn-sm"
                                                        style="width: 250px">Previous</button>
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                        style="width: 250px">
                                                        {{ __('Register') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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

    {{-- <script>
        const progress = document.getElementById('progress');
        const steps = document.querySelectorAll('.step');
        const formSteps = document.querySelectorAll('.form-step');
        const nextButtons = document.querySelectorAll('.next');
        const prevButtons = document.querySelectorAll('.prev');

        let currentStep = 0;

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                currentStep++;
                updateFormSteps();
                updateProgressBar();
                scrollToTop(); // Scroll to the top when next is clicked
            });
        });

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                currentStep--;
                updateFormSteps();
                updateProgressBar();
                scrollToTop(); // Scroll to the top when previous is clicked
            });
        });

        function updateFormSteps() {
            formSteps.forEach((formStep, index) => {
                formStep.classList.toggle('active', index === currentStep);
            });
        }

        function updateProgressBar() {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index <= currentStep);
            });
            const progressPercentage = (currentStep / (steps.length - 1)) * 100;
            progress.style.width = `${progressPercentage}%`;
        }

        function scrollToTop() {
            document.querySelector('.container').scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script> --}}

    {{-- <script>
        const progress = document.getElementById('progress');
        const steps = document.querySelectorAll('.step');
        const formSteps = document.querySelectorAll('.form-step');
        const nextButtons = document.querySelectorAll('.next');
        const prevButtons = document.querySelectorAll('.prev');

        let currentStep = 0;

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const currentFormStep = formSteps[currentStep];
                if (validateFields(currentFormStep)) {
                    currentStep++;
                    updateFormSteps();
                    updateProgressBar();
                    scrollToTop();
                }
            });
        });

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                currentStep--;
                updateFormSteps();
                updateProgressBar();
                scrollToTop();
            });
        });

        function updateFormSteps() {
            formSteps.forEach((formStep, index) => {
                formStep.classList.toggle('active', index === currentStep);
            });
        }

        function updateProgressBar() {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index <= currentStep);
            });
            const progressPercentage = (currentStep / (steps.length - 1)) * 100;
            progress.style.width = `${progressPercentage}%`;
        }

        function scrollToTop() {
            document.querySelector('.container').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function validateFields(formStep) {
            const inputs = formStep.querySelectorAll('input[required]');
            let allValid = true;

            inputs.forEach((input) => {
                const errorElement = input.nextElementSibling;
                if (!input.value.trim()) {
                    allValid = false;
                    if (!errorElement || !errorElement.classList.contains('error-message')) {
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = 'This field is required.';
                        input.parentElement.appendChild(errorMessage);
                    }
                } else {
                    if (errorElement && errorElement.classList.contains('error-message')) {
                        errorElement.remove();
                    }
                }
            });

            return allValid;
        }
    </script> --}}

    <script>
        const progress = document.getElementById('progress');
        const steps = document.querySelectorAll('.step');
        const formSteps = document.querySelectorAll('.form-step');
        const nextButtons = document.querySelectorAll('.next');
        const prevButtons = document.querySelectorAll('.prev');
        const popup = document.getElementById('error-popup');
        const popupMessage = document.getElementById('popup-message');
        const popupCloseButton = document.getElementById('close-popup');

        let currentStep = 0;

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const currentFormStep = formSteps[currentStep];
                if (validateFields(currentFormStep)) {
                    currentStep++;
                    updateFormSteps();
                    updateProgressBar();
                    scrollToTop();
                }
            });
        });

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                currentStep--;
                updateFormSteps();
                updateProgressBar();
                scrollToTop();
            });
        });

        popupCloseButton.addEventListener('click', () => {
            popup.style.display = 'none';
        });

        function updateFormSteps() {
            formSteps.forEach((formStep, index) => {
                formStep.classList.toggle('active', index === currentStep);
            });
        }

        function updateProgressBar() {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index <= currentStep);
            });
            const progressPercentage = (currentStep / (steps.length - 1)) * 100;
            progress.style.width = `${progressPercentage}%`;
        }

        function scrollToTop() {
            document.querySelector('.container').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function validateFields(formStep) {
            const inputs = formStep.querySelectorAll('input[required]');
            let allValid = true;

            inputs.forEach((input) => {
                if (!input.value.trim()) {
                    allValid = false;
                }
            });

            if (!allValid) {
                showPopup('Please fill out all required fields.');
            }

            return allValid;
        }

        function showPopup(message) {
            popupMessage.textContent = message;
            popup.style.display = 'block';
        }
    </script>
@endsection
