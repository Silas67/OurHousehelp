@extends('layouts.main')

@section('signup')
    <section id="register" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">

                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2">{{ __('Create a New Househelp Account') }}</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Name -->
                                <div class="form-group row pt-5">
                                    <label for="name"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Middle Name -->
                                <div class="form-group row">
                                    <label for="middle_name"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="middle_name" type="text"
                                            class="form-control @error('middle_name') is-invalid @enderror"
                                            name="middle_name" value="{{ old('middle_name') }}" required
                                            autocomplete="middle_name" autofocus>
                                        @error('middle_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="form-group row">
                                    <label for="last_name"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>
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
                                        class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                                    <div class="col-md-6">
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


                                <!-- Email Address -->
                                <div class="form-group row">
                                    <label for="email"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="form-group row">
                                    <label for="phone"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="form-group row">
                                    <label for="dob"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}</label>
                                    <div class="col-md-6">
                                        <input id="dob" type="date"
                                            class="form-control @error('dob') is-invalid @enderror" name="dob"
                                            value="{{ old('dob') }}" required>
                                        @error('dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address of Residence -->
                                <div class="form-group row">
                                    <label for="address"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Address of Residence') }}</label>
                                    <div class="col-md-6">
                                        <input id="address" type="text"
                                            class="form-control @error('address') is-invalid @enderror" name="address"
                                            value="{{ old('address') }}" required>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- State of Origin -->
                                <div class="form-group row">
                                    <label for="state_of_origin"
                                        class="col-md-4 col-form-label text-md-end">{{ __('State of Origin') }}</label>
                                    <div class="col-md-6">
                                        <input id="state_of_origin" type="text"
                                            class="form-control @error('state_of_origin') is-invalid @enderror"
                                            name="state_of_origin" value="{{ old('state_of_origin') }}" required>
                                        @error('state_of_origin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- State of Residence -->
                                <div class="form-group row">
                                    <label for="state_of_residence"
                                        class="col-md-4 col-form-label text-md-end">{{ __('State of Residence') }}</label>
                                    <div class="col-md-6">
                                        <input id="state_of_residence" type="text"
                                            class="form-control @error('state_of_residence') is-invalid @enderror"
                                            name="state_of_residence" value="{{ old('state_of_residence') }}" required>
                                        @error('state_of_residence')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Profile Picture Upload -->
                                <div class="form-group row">
                                    <label for="profile_photo"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Upload Picture') }}</label>
                                    <div class="col-md-6">
                                        <input id="profile_photo" type="file"
                                            class="form-control @error('profile_photo') is-invalid @enderror"
                                            name="profile_photo">
                                        @error('profile_photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <!-- National Identification Number (NIN) -->
                                <div class="form-group row">
                                    <label for="nin"
                                        class="col-md-4 col-form-label text-md-end">{{ __('NIN') }}</label>
                                    <div class="col-md-6">
                                        <input id="nin" type="text"
                                            class="form-control @error('nin') is-invalid @enderror" name="nin"
                                            value="{{ old('nin') }}" required>
                                        @error('nin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Services (Checkboxes) -->
                                <div class="form-group row mb-3">
                                    <div class="col-md-10 offset-md-1">
                                        <div class="alert alert-info">
                                            <strong>Important:</strong> To become a staff member with Our House Help, you must be able to perform
                                            <strong>Cleaning, Laundry, and Errands</strong>. The more services you can perform, the higher your
                                            earning opportunities.
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $mandatoryServices = ['Cleaning', 'Laundry', 'Errands'];
                                @endphp

                                @foreach ($services as $service)
                                    @php
                                        $serviceName = ucfirst($service->service_name);
                                        $isMandatory = collect($mandatoryServices)->contains(function ($m) use ($serviceName) {
                                            return stripos($serviceName, $m) !== false;
                                        });
                                        $isChecked = $isMandatory || in_array($serviceName, old('specialties', $applicant?->specialties ?? []));
                                    @endphp
                                    <div class="col-md-4 mb-4">
                                        <div class="card text-center p-3">
                                            <input type="checkbox" name="specialties[]"
                                                value="{{ $serviceName }}"
                                                id="{{ $serviceName }}" class="mb-2"
                                                {{ $isChecked ? 'checked' : '' }}
                                                {{ $isMandatory ? 'disabled' : '' }}>
                                            @if($isMandatory)
                                                <input type="hidden" name="specialties[]" value="{{ $serviceName }}">
                                            @endif
                                            <label for="{{ $serviceName }}" class="font-weight-bold">
                                                {{ $serviceName }}
                                                @if($isMandatory)
                                                    <span class="badge bg-success" style="font-size: 10px;">Required</span>
                                                @endif
                                            </label>
                                            <i class="fas fa-plus fa-2x mb-2"></i>
                                            <p>{{ ucfirst($service->about_service) }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Hidden Field for Account Type -->
                                <input type="hidden" name="account_type" value="applicant">

                                <!-- Hidden Field for Applicant Type -->
                                <input type="hidden" name="applicant_type" value="live-in">

                                <!-- Password -->
                                <div class="form-group row">
                                    <label for="password"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6 position-relative">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">
                                        <i class="fas fa-eye password-toggle"
                                            style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);"
                                            onclick="togglePassword('password')"></i>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group row">
                                    <label for="password-confirm"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6 position-relative">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required
                                            autocomplete="new-password">
                                        <i class="fas fa-eye password-toggle"
                                            style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);"
                                            onclick="togglePassword('password-confirm')"></i>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary" style="width: 250px">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Already Registered? -->
                            <div class="form-group row mt-3">
                                <div class="col-md-6 offset-md-4">
                                    <p>
                                        {{ __('Already registered?') }}
                                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
