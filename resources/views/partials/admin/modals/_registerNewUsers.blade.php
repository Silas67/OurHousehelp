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

 <div class="modal fade" id="registerNew" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-l modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header bg-primary text-white">
                 <h5 class="modal-title" id="registerNew">
                     <i class="fas fa-user-circle me-2"></i>Register New
                     @if (Route::is('adminstaffs'))
                         Admin
                     @elseif (Route::is('adminclients'))
                         Client
                     @elseif (Route::is('adminskilledworkers'))
                         Skilled Worker
                     @elseif (Route::is('adminapplicants'))
                         House Help
                     @endif
                 </h5>
                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="container">
                     <div class="row justify-content-center">

                         <form method="POST" action="{{ route('admin.create.househelp') }}"
                             enctype="multipart/form-data">
                             @csrf

                             <!-- Profile Photo -->
                             @if (Route::is('adminstaffs') || Route::is('adminskilledworkers') || Route::is('adminapplicants'))
                                 <div class="form-group row">
                                     <label for="profile_photo_path" class="col-md-4 col-form-label text-md-end">
                                         {{ __('Photo') }}<span class="text-danger">*</span>
                                     </label>
                                     <div class="col-md-8">
                                         <div class="mb-3 align-item-start position-relative">
                                             <img id="profile-photo-preview"
                                                 src="{{ asset('build/assets/image/slider1.jpg') }}" alt="Profile Photo"
                                                 class="img-thumbnail rounded-circle"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                         </div>

                                         <input type="file" id="profile_photo_path" name="profile_photo_path"
                                             class="form-control @error('profile_photo_path') is-invalid @enderror"
                                             accept="image/jpeg,image/png,image/jpg"
                                             onchange="previewProfilePhoto(this)">
                                         <small class="text-muted">Allowed formats: jpg, png, jpeg. Max size:
                                             2MB.</small>
                                         <div id="profile-photo-error" class="invalid-feedback d-none"></div>
                                         @error('profile_photo_path')
                                             <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div>

                                 <script>
                                     function previewProfilePhoto(input) {
                                         const preview = document.getElementById('profile-photo-preview');
                                         const errorDiv = document.getElementById('profile-photo-error');
                                         const overlay = document.getElementById('profile-photo-overlay');
                                         const maxSize = 2 * 1024 * 1024; // 2MB in bytes

                                         // Reset previous errors
                                         errorDiv.classList.add('d-none');
                                         input.classList.remove('is-invalid');

                                         if (input.files && input.files[0]) {
                                             const file = input.files[0];

                                             // Validate file type
                                             const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                             if (!validTypes.includes(file.type)) {
                                                 showError('Invalid file type. Please upload a JPG, JPEG, or PNG image.');
                                                 return;
                                             }

                                             // Validate file size
                                             if (file.size > maxSize) {
                                                 showError('File size exceeds 2MB limit.');
                                                 return;
                                             }

                                             // Preview the image
                                             const reader = new FileReader();

                                             reader.onload = function(e) {
                                                 preview.src = e.target.result;
                                                 overlay.classList.remove('d-none');
                                             }

                                             reader.readAsDataURL(file);
                                         } else {
                                             // Reset to default if no file selected
                                             preview.src = "{{ asset('build/assets/image/slider1.jpg') }}";
                                             overlay.classList.add('d-none');
                                         }

                                         function showError(message) {
                                             errorDiv.textContent = message;
                                             errorDiv.classList.remove('d-none');
                                             input.classList.add('is-invalid');
                                             input.value = ''; // Clear the invalid file
                                             preview.src = "{{ asset('build/assets/image/slider1.jpg') }}";
                                             overlay.classList.add('d-none');
                                         }
                                     }

                                     // Add hover effect to show camera icon
                                     const previewContainer = document.querySelector('.align-item-start');
                                     const overlay = document.getElementById('profile-photo-overlay');

                                     previewContainer.addEventListener('mouseenter', () => {
                                         if (document.getElementById('profile_photo_path').files.length === 0) {
                                             overlay.classList.remove('d-none');
                                         }
                                     });

                                     previewContainer.addEventListener('mouseleave', () => {
                                         if (document.getElementById('profile_photo_path').files.length === 0) {
                                             overlay.classList.add('d-none');
                                         }
                                     });

                                     // Click on image to trigger file input
                                     previewContainer.addEventListener('click', () => {
                                         document.getElementById('profile_photo_path').click();
                                     });
                                 </script>

                                 <style>
                                     .img-thumbnail {
                                         cursor: pointer;
                                         transition: all 0.3s ease;
                                     }

                                     .img-thumbnail:hover {
                                         opacity: 0.9;
                                     }

                                     #profile-photo-overlay {
                                         transition: all 0.3s ease;
                                         cursor: pointer;
                                     }
                                 </style>
                             @endif
                             <!-- Date of Registration -->
                             <div class="form-group row">
                                 <label for="name"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Date') }}</label>
                                 <div class="col-md-6">
                                     <input id="date" type="text" class="form-control"
                                         placeholder="Today's Date" readonly>
                                 </div>
                                 <script>
                                     const options = {
                                         weekday: 'short',
                                         year: 'numeric',
                                         month: 'short',
                                         day: 'numeric'
                                     };
                                     const today = new Date().toLocaleDateString('en-US', options);
                                     document.getElementById('date').value = today;
                                 </script>
                             </div>

                             <!-- Account Type -->

                             @if (Route::is('adminstaffs'))
                                 <input type="hidden" name="account_type" value="admin">
                             @elseif (Route::is('adminclients'))
                                 <input type="hidden" name="account_type" value="client">
                             @elseif (Route::is('adminskilledworkers'))
                                 <input type="hidden" name="account_type" value="applicant">
                                 <input type="hidden" name="applicant_type" value="skill">
                             @elseif (Route::is('adminapplicants'))
                                 <input type="hidden" name="account_type" value="applicant">
                                 <!-- Applicant Type (initially hidden if not 'applicant') -->
                                 <div class="form-group row pt-3" id="applicant_type_container">
                                     <label for="applicant_type" class="col-md-4 col-form-label text-md-end">
                                         {{ __('Applicant Type') }}<span class="text-danger">*</span>
                                     </label>
                                     <div class="col-md-8">
                                         <select id="applicant_type" name="applicant_type"
                                             class="form-control @error('applicant_type') is-invalid @enderror"
                                             {{ old('account_type') == 'applicant' ? 'required' : '' }}>
                                             <option value="" disabled selected>Select type</option>
                                             <option value="live-out"
                                                 {{ old('applicant_type') == 'live-out' ? 'selected' : '' }}>Live-Out
                                             </option>
                                             <option value="live-in"
                                                 {{ old('applicant_type') == 'live-in' ? 'selected' : '' }}>Live-In
                                             </option>
                                             <option value="semi-live-in"
                                                 {{ old('applicant_type') == 'semi-live-in' ? 'selected' : '' }}>
                                                 Semi Live-In</option>
                                         </select>
                                         <small class="text-danger error-message d-none">This field is required.</small>
                                         @error('applicant_type')
                                             <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div>
                             @endif
                             <!-- Name -->
                             <div class="form-group row">
                                 <label for="name"
                                     class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <input id="name" type="text"
                                         class="form-control @error('name') is-invalid @enderror" name="name"
                                         value="{{ old('name') }}" required autocomplete="name" autofocus
                                         oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                     <small class="text-danger error-message d-none">This field
                                         is
                                         required.</small>
                                     @error('name')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                             </div>

                             <!-- Middle Name -->
                             <div class="form-group row mb-3">
                                 <label for="middle_name"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>
                                 <div class="col-md-8">
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
                             <div class="form-group row mb-3">
                                 <label for="last_name"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <input id="last_name" type="text"
                                         class="form-control @error('last_name') is-invalid @enderror"
                                         name="last_name" value="{{ old('last_name') }}" required
                                         oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')">
                                     <small class="text-danger error-message d-none">This field
                                         is
                                         required.</small>
                                     @error('last_name')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                             </div>

                             <!-- gender -->
                             <div class="form-group row">
                                 <label for="gender"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                         name="gender" required>
                                         <option value="">Select Gender</option>
                                         <option value="male">Male
                                         </option>
                                         <option value="female">
                                             Female</option>
                                     </select>
                                     @error('gender')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                             </div>


                             <!-- Date of Birth (for only househelps) -->
                             <div class="form-group row mb-3">
                                 <label for="dob"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <input id="dob" type="date"
                                         class="form-control @error('dob') is-invalid @enderror" name="dob"
                                         value="{{ old('dob', $user->dob ?? '') }}" required
                                         max="{{ date('Y-m-d', strtotime('-18 years')) }}">
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



                             <!-- Email Address -->
                             <div class="form-group row mb-3">
                                 <label for="email"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <input id="email" type="email"
                                         class="form-control @error('email') is-invalid @enderror" name="email"
                                         value="{{ old('email') }}" required>
                                     @error('email')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                             </div>

                             <!-- Phone -->
                             <div class="form-group row mb-3">
                                 <label for="phone"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                     <input id="phone" type="phone"
                                         class="form-control @error('phone') is-invalid @enderror" name="phone"
                                         value="{{ old('phone') }}" minlength="11" maxlength="11" required>
                                     @error('phone')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                 </div>
                             </div>

                             @if (Route::is('adminskilledworkers'))


                                 <!-- Skill Selection (for only skilled workers and house help) -->
                                 {{-- <div class="form-group row mb-3">
                                     <label class="col-md-4 col-form-label text-md-end">
                                         {{ __('Skills') }}<span class="text-danger">*</span>
                                     </label>
                                     <div class="col-md-8">
                                         <div class="skills-checkbox-group @error('specialties') is-invalid @enderror">
                                             @foreach ($skills as $skill)
                                                 <div class="form-check mb-2">
                                                     <input class="form-check-input" type="checkbox"
                                                         name="specialties[]" id="skill-{{ $skill->id }}"
                                                         value="{{ $skill->skill_name }}"
                                                         {{ is_array(old('specialties')) && in_array($skill->skill_name, old('specialties')) ? 'checked' : '' }}>
                                                     <label class="form-check-label" for="skill-{{ $skill->id }}">
                                                         {{ $skill->skill_name }}
                                                         @if ($skill->about_skill)
                                                             <small
                                                                 class="text-muted d-block">{{ $skill->about_skill }}</small>
                                                         @endif
                                                     </label>
                                                 </div>
                                             @endforeach
                                         </div>

                                         <small class="text-danger error-message d-none">Please select at least one
                                             skill.</small>

                                         @error('specialties')
                                             <span class="invalid-feedback d-block" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div> --}}
                                 <div class="form-group row mb-3">
                                     <label for="skill" class="col-md-4 col-form-label text-md-end">
                                         {{ __('Skills') }}<span class="text-danger">*</span>
                                     </label>
                                     <div class="col-md-8">
                                         <select id="skill" name="specialties[]"
                                             class="form-control @error('specialties') is-invalid @enderror" required
                                             multiple>
                                             @foreach ($skills as $skill)
                                                 <option value="{{ $skill->skill_name }}"
                                                     {{ is_array(old('specialties')) && in_array($skill->skill_name, old('specialties')) ? 'selected' : '' }}
                                                     data-description="{{ $skill->about_skill }}"
                                                     data-icon="{{ $skill->icon_class }}">
                                                     {{ $skill->skill_name }}
                                                 </option>
                                             @endforeach
                                         </select>
                                         <small class="text-muted">Hold Ctrl/Cmd to select multiple skills</small>
                                         <small class="text-danger error-message d-none">This field is
                                             required.</small>

                                         <!-- Skill Description (optional display) -->
                                         <div id="skill-description" class="mt-2 p-2 bg-light rounded"
                                             style="display: none;">
                                             <small class="text-muted" id="skill-description-text"></small>
                                         </div>

                                         @error('specialties')
                                             <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div>
                             @elseif(Route::is('adminapplicants'))
                                 <hr>
                                 <!-- Select your specialties -->
                                 <div>
                                     <p>Select your specialties<span class="text-danger">*</span></p>
                                     <div class="row">
                                         @foreach ($services as $service)
                                             <div class="col-md-4 mb-4 d-flex align-items-stretch">
                                                 <div class="text-center">
                                                     <input type="checkbox" name="specialties[]"
                                                         value="{{ ucfirst($service->service_name) }}"
                                                         id="{{ ucfirst($service->service_name) }}" class="mb-2"
                                                         {{ in_array(ucfirst($service->service_name), $applicant->specialties ?? []) ? 'checked' : '' }}>
                                                     <label for="{{ ucfirst($service->service_name) }}"
                                                         class="font-weight-bold" style="font-weight: bolder">
                                                         {{ ucfirst($service->service_name) }}
                                                     </label>
                                                 </div>
                                             </div>
                                         @endforeach
                                     </div>
                                 </div>
                                 <hr>


                                 <!-- State of Origin (for only househelps) -->
                                 <div class="form-group row mb-3">
                                     <label for="state_of_origin"
                                         class="col-md-4 col-form-label text-md-end">{{ __('State of Origin') }}<span
                                             class="text-danger">*</span></label>
                                     <div class="col-md-8">
                                         {{-- <input id="state_of_origin" type="text"
                                               class="form-control @error('state_of_origin') is-invalid @enderror" name="state_of_origin"
                                               value="{{ old('state_of_origin') }}"> --}}
                                         <select id="state_of_origin" name="state_of_origin"
                                             class="form-control @error('state_of_origin') is-invalid @enderror"
                                             required>
                                             <option value="">Select State of Origin</option>
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
                                                     {{ old('state_of_origin', Auth::user()->state_of_origin) == $state ? 'selected' : '' }}>
                                                     {{ $state }}
                                                 </option>
                                             @endforeach
                                         </select>
                                         @error('state_of_origin')
                                             <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div>
                             @endif

                             <!-- Country of Residence -->
                             <div class="form-group row mb-3">
                                 <label for="country_of_residence"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Country') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8">
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
                             <div class="form-group row mb-3">
                                 <label for="state_of_residence" class="col-md-4 col-form-label text-md-end">
                                     {{ __('State') }}<span class="text-danger">*</span>
                                 </label>
                                 <div class="col-md-8">
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

                             @if (Route::is('adminskilledworkers') || Route::is('adminapplicants'))
                                 <!-- LGA of Origin -->
                                 <div class="form-group row mb-3">
                                     <label for="lga_of_origin"
                                         class="col-md-4 col-form-label text-md-end">{{ __('LGA') }}<span
                                             class="text-danger">*</span></label>
                                     <div class="col-md-8">
                                         <input id="lga_of_origin" type="text"
                                             class="form-control @error('lga_of_origin') is-invalid @enderror"
                                             name="lga_of_origin"
                                             value="{{ old('lga_of_origin', $user->lga_of_origin ?? '') }}" required>
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
                                 <div class="form-group row mb-3">
                                     <label for="address"
                                         class="col-md-4 col-form-label text-md-end">{{ __('Address') }}<span
                                             class="text-danger">*</span></label>
                                     <div class="col-md-8">
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
                                 <div class="form-group row mb-3">
                                     <label for="landmark"
                                         class="col-md-4 col-form-label text-md-end">{{ __('Landmark') }}<span
                                             class="text-danger">*</span></label>
                                     <div class="col-md-8">
                                         <input id="landmark" type="text"
                                             class="form-control @error('landmark') is-invalid @enderror"
                                             name="landmark" value="{{ old('landmark', $user->landmark ?? '') }}"
                                             required>
                                         <small class="text-danger error-message d-none">This field
                                             is required.</small>
                                         @error('landmark')
                                             <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                             </span>
                                         @enderror
                                     </div>
                                 </div>
                             @endif

                             <!-- Terms -->
                             @if (Route::is('adminstaffs') || Route::is('adminclients'))
                                 <div class="form-group row py-3 bg-primary" hidden>
                                     <div class="col-md-6 offset-md-4">
                                         <div class="form-check">
                                             <input
                                                 class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                                 type="checkbox" name="terms_accepted" id="terms_accepted" required
                                                 checked value="1">
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
                             @elseif (Route::is('adminskilledworkers') || Route::is('adminapplicants'))
                                 <div class="form-group row py-3 bg-primary">
                                     <div class="col-md-6 offset-md-4">
                                         <div class="form-check">
                                             <input
                                                 class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                                 type="checkbox" name="terms_accepted" id="terms_accepted" required
                                                 {{ old('terms_accepted') ? 'checked' : '' }} value="1">
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
                             @endif




                             <!-- Password -->
                             <div class="form-group row mb-3">
                                 <label for="password"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Password') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8 position-relative">
                                     <input id="password" type="password"
                                         class="form-control @error('password') is-invalid @enderror" name="password"
                                         value="{{ old('password', '12345678') }}" required
                                         autocomplete="new-password">
                                     <i class="fas fa-eye-slash password-toggle"
                                         style="cursor: pointer; position: absolute; right: 15px; top: 25%; transform: translateY(-50%);"
                                         onclick="togglePasswordVisibility('password', this)"></i>
                                     @error('password')
                                         <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                         </span>
                                     @enderror
                                     <small class="form-text text-muted">Default password: 12345678 (can be
                                         changed)</small>
                                 </div>
                             </div>

                             <!-- Confirm Password -->
                             <div class="form-group row mb-3">
                                 <label for="password-confirm"
                                     class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}<span
                                         class="text-danger">*</span></label>
                                 <div class="col-md-8 position-relative">
                                     <input id="password-confirm" type="password" class="form-control"
                                         name="password_confirmation"
                                         value="{{ old('password_confirmation', '12345678') }}" required>
                                     <i class="fas fa-eye-slash password-toggle"
                                         style="cursor: pointer; position: absolute; right: 15px; top: 30%; transform: translateY(-50%);"
                                         onclick="togglePasswordVisibility('password-confirm', this)"></i>
                                 </div>
                             </div>

                             <script>
                                 function togglePasswordVisibility(inputId, iconElement) {
                                     const input = document.getElementById(inputId);
                                     if (input.type === 'password') {
                                         input.type = 'text';
                                         iconElement.classList.remove('fa-eye-slash');
                                         iconElement.classList.add('fa-eye');
                                     } else {
                                         input.type = 'password';
                                         iconElement.classList.remove('fa-eye');
                                         iconElement.classList.add('fa-eye-slash');
                                     }
                                 }

                                 // Optional: Clear default password when user focuses on field
                                 document.getElementById('password').addEventListener('focus', function() {
                                     if (this.value === '12345678') {
                                         this.value = '';
                                     }
                                 });

                                 document.getElementById('password-confirm').addEventListener('focus', function() {
                                     if (this.value === '12345678') {
                                         this.value = '';
                                     }
                                 });
                             </script>

                             <!-- Register Button -->
                             <div class="form-group row mb-0">
                                 <div class="col-md-6 offset-md-4">
                                     <button type="submit" class="btn btn-primary" style="width: 250px">
                                         {{ __('Register') }}
                                     </button>
                                 </div>
                             </div>
                         </form>

                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                     <i class="fas fa-times me-1"></i> Close
                 </button>
             </div>
         </div>
     </div>
 </div>
