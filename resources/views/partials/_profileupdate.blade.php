<div class="card-body">

    @php

        $isClient = Auth::user()->account_type === 'client';

    @endphp



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

            'FCT Abuja',

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



    <form action="{{ route('applicantprofile.update', ['id' => $user->id]) }}" method="POST"

        enctype="multipart/form-data">

        @csrf

        @if (in_array(Auth::user()->account_type, ['applicant', 'staff']))

            <!-- Profile Photo -->

            <div class="form-group row">

                <label for="profile_photo_path"

                    class="col-md-4 col-form-label text-md-right">{{ __('Profile Photo') }}<span

                        class="text-danger">*</span></label>

                <div class="col-md-6">



                    <div class="mb-3">

                        @if (Auth::user()->profile_photo_path)

                            <img id="profile-photo-preview"

                                src="{{ asset('public/build/assets/image/profile_photo/' . Auth::user()->profile_photo_path) }}"

                                alt="Profile Photo" class="img-thumbnail" style="width: 150px; height: 150px;" loading="lazy" decoding="async">

                        @else

                            <x-img src="build/assets/image/slider1.jpg" id="profile-photo-preview" alt="Profile Photo" class="img-thumbnail" style="width: 150px; height: 150px;" loading="lazy" decoding="async" />

                        @endif

                    </div>



                    <input type="file" id="profile_photo_path" name="profile_photo_path"

                        class="form-control @error('profile_photo_path') is-invalid @enderror" accept="image/*"

                        onchange="previewImage('profile_photo_path', 'profile-photo-preview')" required>

                    @error('profile_photo_path')

                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                    @enderror

                </div>

            </div>

        @else

        @endif

        <!-- Name -->

        <div class="form-group row mt-3">

            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"

                    name="name" value="{{ Auth::user()->name }}" required readonly>

                @error('name')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- Middle Name -->

        <div class="form-group row mt-3">

            <label for="middle_name" class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input type="text" class="form-control" id="middle_name" name="middle_name"

                    value="{{ old('middle_name', Auth::user()->middle_name) }}" required>

                @error('middle_name')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- Last Name -->

        <div class="form-group row mt-3">

            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input type="text" class="form-control" id="last_name" name="last_name"

                    value="{{ old('last_name', Auth::user()->last_name) }}" required>

                @error('last_name')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- Email -->

        <div class="form-group row mt-3">

            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"

                    name="email" value="{{ Auth::user()->email }}" required readonly>

                @error('email')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- Phone -->

        <div class="form-group row mt-3">

            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"

                    name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required maxlength="11"

                    minlength="11">

                <div id="phone-error" class="text-danger mt-2" style="display: none;"></div>

                @error('phone')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>


        <!-- Date of Birth -->

        <div class="form-group row mt-3">

            <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}

                @if (!$isClient)

                    <span class="text-danger">*</span>

                @endif

            </label>

            <div class="col-md-6">

                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror"

                    name="dob"

                    value="{{ old('dob', Auth::user()->dob ? Auth::user()->dob->format('Y-m-d') : '') }}"

                    @if (!$isClient) required @endif>

                @error('dob')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

                <small class="form-text text-muted">Note: You must be at least 18 years old.</small>

            </div>

        </div>


        <!-- Gender -->

        <div class="form-group row mt-3">

            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender"

                    required>

                    <option value="">Select Gender</option>

                    <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Male

                    </option>

                    <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>

                        Female</option>

                </select>

                @error('gender')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>


        {{-- Marital Status --}}
        @if(in_array(Auth::user()->account_type, ['applicant', 'staff']))
        <div class="form-group row mt-3">
            <label for="marital_status" class="col-md-4 col-form-label text-md-right">
                {{ __('Marital Status') }}<span class="text-danger">*</span>
            </label>
            <div class="col-md-6">
                <select class="form-control @error('marital_status') is-invalid @enderror"
                    id="marital_status" name="marital_status" required>
                    <option value="">Select Marital Status</option>
                    <option value="single" {{ old('marital_status', Auth::user()->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="married" {{ old('marital_status', Auth::user()->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                    <option value="divorced" {{ old('marital_status', Auth::user()->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="widowed" {{ old('marital_status', Auth::user()->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                </select>
                @error('marital_status')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        {{-- Religion  --}}
        <div class="form-group row mt-3">
            <label for="religion" class="col-md-4 col-form-label text-md-right">
                {{ __('Religion') }}<span class="text-danger">*</span>
            </label>
            <div class="col-md-6">
                <select name="religion" class="form-control @error('religion') is-invalid @enderror"
                    id="religion" required>
                    <option value="">Select religion</option>
                    <option value="christianity"
                        {{ strtolower(old('religion', Auth::user()->religion)) == 'christianity' ? 'selected' : '' }}>
                        Christianity
                    </option>
                    <option value="islam"
                        {{ strtolower(old('religion', Auth::user()->religion)) == 'islam' ? 'selected' : '' }}>
                        Islam
                    </option>
                    <option value="traditional"
                        {{ strtolower(old('religion', Auth::user()->religion)) == 'traditional' ? 'selected' : '' }}>
                        Traditional
                    </option>
                    <option value="other"
                        {{ strtolower(old('religion', Auth::user()->religion)) == 'other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
                @error('religion')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>


        {{-- Qualification --}}
        <div class="form-group row mt-3">
            <label for="qualification" class="col-md-4 col-form-label text-md-right">
                {{ __('Highest Qualification') }}<span class="text-danger">*</span>
            </label>
            <div class="col-md-6">
                <select class="form-control @error('qualification') is-invalid @enderror"
                    id="qualification" name="qualification" required>
                    <option value="">Select Qualification</option>
                    <option value="No formal education" {{ old('qualification', Auth::user()->qualification) == 'No formal education' ? 'selected' : '' }}>No Formal Education</option>
                    <option value="Primary School" {{ old('qualification', Auth::user()->qualification) == 'Primary School' ? 'selected' : '' }}>Primary School</option>
                    <option value="Junior Secondary (JSS3)" {{ old('qualification', Auth::user()->qualification) == 'Junior Secondary (JSS3)' ? 'selected' : '' }}>Junior Secondary (JSS3)</option>
                    <option value="Senior Secondary (WAEC/NECO)" {{ old('qualification', Auth::user()->qualification) == 'Senior Secondary (WAEC/NECO)' ? 'selected' : '' }}>Senior Secondary (WAEC/NECO)</option>
                    <option value="OND/NCE" {{ old('qualification', Auth::user()->qualification) == 'OND/NCE' ? 'selected' : '' }}>OND/NCE</option>
                    <option value="HND/BSc" {{ old('qualification', Auth::user()->qualification) == 'HND/BSc' ? 'selected' : '' }}>HND/BSc</option>
                    <option value="Postgraduate" {{ old('qualification', Auth::user()->qualification) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                </select>
                @error('qualification')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        @endif


        {{-- Country of Origin --}}
        <div class="form-group row mt-3">

            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Country of Origin') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <select id="country" name="country" class="form-control @error('country') is-invalid @enderror"

                    required>

                    <option value="">Select Country</option>

                    @foreach ($countries as $country)

                        <option value="{{ $country }}"

                            {{ old('country', Auth::user()->country ?? 'Nigeria') == $country ? 'selected' : '' }}>

                            {{ $country }}

                        </option>

                    @endforeach

                </select>

                @error('country')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- State of Origin -->
        <div class="form-group row mt-3">

            <label for="state_of_origin" class="col-md-4 col-form-label text-md-right">{{ __('State of Origin') }}

                @if (!$isClient)

                    <span class="text-danger">*</span>

                @endif

            </label>

            <div class="col-md-6">

                <select id="state_of_origin" name="state_of_origin"

                    class="form-control @error('state_of_origin') is-invalid @enderror"

                    @if (!$isClient) required @endif>

                    <option value="">Select State of Origin</option>



                    @foreach ($states as $state)

                        <option value="{{ $state }}"

                            {{ old('state_of_origin', Auth::user()->state_of_origin) == $state ? 'selected' : '' }}>

                            {{ $state }}

                        </option>

                    @endforeach

                </select>

                @error('state_of_origin')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        {{-- <!-- Local Government Area of State of Origin -->
        <div class="form-group row mt-3">

            <label for="lga_of_origin"

                class="col-md-4 col-form-label text-md-right">{{ __('Local Government Area of State of Origin') }}<span

                    class="text-danger">*</span>

            </label>

            <div class="col-md-6">

                <input id="lga_of_origin" type="text"

                    class="form-control @error('lga_of_origin') is-invalid @enderror" name="lga_of_origin"

                    value="{{ old('lga_of_origin', Auth::user()->lga_of_origin ?? '') }}" required>

                @error('lga_of_origin')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div> --}}


        <div class="form-group row mt-3">

            <label for="lga_of_origin" class="col-md-4 col-form-label text-md-right">

                {{ __('Local Government Area of State of Origin') }}

                @if (!$isClient)

                    <span class="text-danger">*</span>

                @endif

            </label>



            <div class="col-md-6">

                <input id="lga_of_origin" type="text"

                    class="form-control @error('lga_of_origin') is-invalid @enderror" name="lga_of_origin"

                    value="{{ old('lga_of_origin', Auth::user()->lga_of_origin ?? '') }}"

                    @if (!$isClient) required @endif>



                @error('lga_of_origin')

                    <span class="invalid-feedback" role="alert">

                        <strong>{{ $message }}</strong>

                    </span>

                @enderror

            </div>

        </div>







        <div class="form-group row mt-3">

            <label for="country_of_residence"

                class="col-md-4 col-form-label text-md-right">{{ __('Country of Residence') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <select id="country_of_residence" name="country_of_residence"

                    class="form-control @error('country_of_residence') is-invalid @enderror" required>

                    <option value="">Select Country</option>

                    @foreach ($countries as $country_of_residence)

                        <option value="{{ $country_of_residence }}"

                            {{ old('country_of_residence', Auth::user()->country_of_residence ?? 'Nigeria') == $country_of_residence ? 'selected' : '' }}>

                            {{ $country_of_residence }}

                        </option>

                    @endforeach

                </select>

                @error('country_of_residence')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- State of Residence -->

        <div class="form-group row mt-3">

            <label for="state_of_residence"

                class="col-md-4 col-form-label text-md-right">{{ __('State of Residence') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <select id="state_of_residence" name="state_of_residence"

                    class="form-control @error('state_of_residence') is-invalid @enderror" required>

                    <option value="">Select State of Residence</option>

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

                            'FCT Abuja',

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

                            {{ old('state_of_residence', Auth::user()->state_of_residence) == $state ? 'selected' : '' }}>

                            {{ $state }}

                        </option>

                    @endforeach

                </select>

                @error('state_of_residence')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- State of Residence Address -->

        <div class="form-group row mt-3">

            <label for="address"

                class="col-md-4 col-form-label text-md-right">{{ __('State of Residence Address') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"

                    name="address" value="{{ old('address', Auth::user()->address ?? '') }}" required>

                @error('address')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- State of Residence Address landmark-->

        <div class="form-group row mt-3">

            <label for="landmark"

                class="col-md-4 col-form-label text-md-right">{{ __('Residence Address Landmark') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input id="landmark" type="text" class="form-control @error('landmark') is-invalid @enderror"

                    name="landmark" value="{{ old('landmark', Auth::user()->landmark ?? '') }}" required>

                @error('landmark')

                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                @enderror

            </div>

        </div>



        <!-- Years of Experience -->

        @if (in_array(Auth::user()->account_type, ['applicant', 'staff']))

            <div class="form-group row mt-3">

                <label for="years_of_experience"

                    class="col-md-4 col-form-label text-md-right">{{ __('Years of Experience') }}<span

                        class="text-danger">*</span></label>

                <div class="col-md-6">

                    <select id="years_of_experience" name="years_of_experience"

                        class="form-control @error('years_of_experience') is-invalid @enderror" required>

                        <option value="">Select Years of Experience</option>

                        <option value="Less than 3 years"

                            {{ old('years_of_experience', Auth::user()->years_of_experience) == 'Less than 3 years' ? 'selected' : '' }}>

                            Less than 3 years</option>

                        <option value="Over 3 years"

                            {{ old('years_of_experience', Auth::user()->years_of_experience) == 'Over 3 years' ? 'selected' : '' }}>

                            Over 3 years</option>

                    </select>

                    @error('years_of_experience')

                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                    @enderror

                </div>

            </div>

        @else

            <div class="form-group row mt-3" hidden>

                <label for="years_of_experience"

                    class="col-md-4 col-form-label text-md-right">{{ __('Years of Experience') }}</label>



                <div class="col-md-6">

                    <input type="text" class="form-control" id="years_of_experience" name="years_of_experience"

                        value="Client" readonly>

                    @error('years_of_experience')

                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                    @enderror

                </div>

            </div>

        @endif



        <!-- National Identification Number (NIN) -->

        @if (in_array(Auth::user()->account_type, ['applicant', 'staff']))

        <div class="form-group row mt-3">

            <label for="nin"

                class="col-md-4 col-form-label text-md-right">{{ __('National Identification Number (NIN) ') }}<span

                    class="text-danger">*</span></label>

            <div class="col-md-6">

                <input type="text" class="form-control" id="nin" name="nin" maxlength="11"

                    minlength="11" placeholder="Enter 11 digits NIN" required oninput="validateNIN()"

                    value="{{ old('nin', Auth::user()->nin ?? '') }}">

                <span id="nin-error" class="text-danger" style="display: none;">NIN must be exactly 11 digits

                    and contain only numbers.</span>

            </div>

            <div class="d-flex align-items-center gap-2">

                {{-- <h4 class="mb-0">{{ Auth::user()->name }}</h4> --}}

                <x-nin-badge :user="Auth::user()" />

            </div>



        </div>

        @else

            <div class="form-group row mt-3">

                <label for="nin"

                    class="col-md-4 col-form-label text-md-right">{{ __('National Identification Number (NIN) ') }}

                </label>

                <div class="col-md-6">

                    <input type="text" class="form-control" id="nin" name="nin" maxlength="11"

                        minlength="11" placeholder="Enter 11 digits NIN" required oninput="validateNIN()"

                        value="00000000000">

                    <span id="nin-error" class="text-danger" style="display: none;">NIN must be exactly 11 digits

                        and contain only numbers.</span>

                </div>

            </div>

            <small class="form-text text-muted">Not required.</small>

        @endif



        <!-- Availability -->

        @if (in_array(Auth::user()->account_type, ['applicant', 'staff']))

            <div class="form-group row mt-3">

                <label for="availability" class="col-md-4 col-form-label text-md-right">

                    {{ __('Availability:') }}<span class="text-danger">*</span>

                </label>

                <div class="col-md-6">

                    <label class="switch">

                        <input type="checkbox" id="availability-toggle" {{ $user->availability ? 'checked' : '' }}

                            onchange="toggleAvailability({{ $user->id }})">

                        <span class="slider round"></span>

                    </label>

                    <small id="availability-note"

                        class="form-text {{ $user->availability ? 'text-muted' : 'text-danger' }}">

                        Note: If the switch is off, you will not appear in search results for clients.

                    </small>

                </div>

            </div>



            <script>

                function toggleAvailability(userId) {

                    const availabilityToggle = document.getElementById('availability-toggle');

                    const availabilityNote = document.getElementById('availability-note');



                    // Update the class based on the switch state

                    if (availabilityToggle.checked) {

                        availabilityNote.classList.remove('text-danger');

                        availabilityNote.classList.add('text-muted');

                    } else {

                        availabilityNote.classList.remove('text-muted');

                        availabilityNote.classList.add('text-danger');

                    }



                    // Add logic here to handle autosave of the availability state via AJAX or form submission

                    // e.g., an AJAX call to save the availability state without refreshing the page

                }

            </script>



            @if (!Route::is('here'))

                <div class="form-group row mt-3">

                    <label for="nin" class="col-md-4 col-form-label text-md-right">

                        {{ __('Average Rating:') }}

                    </label>

                    <div class="col-md-6">

                        <span id="average-rating">{{ number_format($averageRating, 1) ?? 'No ratings yet' }}</span>

                        of <span id="total-count">{{ $totalCount }}</span>



                        <div class="star-rating mt-2">

                            @for ($i = 1; $i <= 5; $i++)

                                @if ($i <= floor($averageRating))

                                    <i class="fas fa-star" style="color: #FFD700;"></i> <!-- Filled star -->

                                @elseif ($i > floor($averageRating) && $i < ceil($averageRating))

                                    <i class="fas fa-star-half-alt" style="color: #FFD700;"></i> <!-- Half star -->

                                @else

                                    <i class="far fa-star" style="color: #FFD700;"></i> <!-- Empty star -->

                                @endif

                            @endfor

                        </div>

                    </div>

                </div>

            @endif



            <!-- ID card -->



            <div class="form-group row mt-3">

                <label for="id_card" class="col-md-4 col-form-label text-md-right">{{ __('ID Card') }}</label>

                <div class="col-md-6">

                    <div class="mb-3">

                        @if (Auth::user()->id_card)

                            <img id="idcard-preview"

                                src="{{ asset('public/build/assets/image/idcards/' . Auth::user()->id_card) }}"

                                alt="ID Card" class="img-thumbnail" style="width: 250px; height: 150px;" loading="lazy" decoding="async">

                        @else

                            <x-img src="build/assets/image/slider1.jpg" id="idcard-preview" alt="ID Card" class="img-thumbnail" style="width: 250px; height: 150px;" loading="lazy" decoding="async" />

                        @endif

                    </div>

                    <input type="file" id="id_card" name="id_card"

                        class="form-control @error('id_card') is-invalid @enderror" accept="image/*"

                        onchange="previewImage('id_card', 'idcard-preview')">

                    @error('id_card')

                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>

                    @enderror

                </div>

            </div>

        @endif







        <!-- Submit Button -->

        <div class="form-group row mt-4">

            <div class="col-md-6 offset-md-4">

                <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>

            </div>

        </div>

    </form>

</div>

<script>

    document.getElementById('availability-toggle').addEventListener('change', function() {

        const availability = this.checked ? 1 : 0;



        // Use fetch to auto-save the toggle state

        fetch('/user/update-availability', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token for security

                },

                body: JSON.stringify({

                    availability: availability

                })

            })

            .then(response => response.json())

            .then(data => {

                if (data.success) {

                    console.log('Availability updated successfully');

                } else {

                    console.error('Failed to update availability');

                }

            })

            .catch(error => {

                console.error('Error:', error);

            });

    });

</script>



<script>

    document.addEventListener("DOMContentLoaded", function() {

        const phoneInput = document.getElementById("phone");

        const phoneError = document.getElementById("phone-error");



        // Event listener for input validation

        phoneInput.addEventListener("input", function() {

            const phoneValue = phoneInput.value;



            // Check if the input length is exactly 11 digits

            if (phoneValue.length > 11 || phoneValue.length < 11) {

                phoneError.style.display = "block";

                phoneError.textContent = "Phone number must be exactly 11 digits.";

            } else {

                phoneError.style.display = "none";

            }



            // Prevent typing non-numeric characters

            if (!/^\d*$/.test(phoneValue)) {

                phoneInput.value = phoneValue.replace(/\D/g, ""); // Remove non-numeric characters

            }

        });

    });

</script>