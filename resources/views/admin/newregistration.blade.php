@extends('layouts.adminmain')
@section('newregistration')
    <section id="register" class="">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">

                        <div class="card-header text-center text-white" style="background-color:#4e504f">
                            <h5 class="py-2">{{ __('Create a New House Help Account') }}</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.create.househelp') }}">
                                @csrf

                                <!-- Name -->
                                <div class="form-group row pt-5 mb-3">
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
                                <div class="form-group row mb-3">
                                    <label for="middle_name"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="middle_name" type="text"
                                            class="form-control @error('middle_name') is-invalid @enderror"
                                            name="middle_name" value="{{ old('middle_name') }}">
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
                                        class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="last_name" type="text"
                                            class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="form-group row mb-3">
                                    <label for="email"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                    <div class="col-md-6">
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
                                        class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                                    <div class="col-md-6">
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

                                <!-- State of Origin -->
                                <div class="form-group row mb-3">
                                    <label for="state_of_origin"
                                        class="col-md-4 col-form-label text-md-end">{{ __('State of Origin') }}</label>
                                    <div class="col-md-6">
                                        {{-- <input id="state_of_origin" type="text"
                                               class="form-control @error('state_of_origin') is-invalid @enderror" name="state_of_origin"
                                               value="{{ old('state_of_origin') }}"> --}}
                                        <select id="state_of_origin" name="state_of_origin"
                                            class="form-control @error('state_of_origin') is-invalid @enderror" required>
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

                                <!-- Password -->
                                <div class="form-group row mb-3">
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
                                <div class="form-group row mb-3">
                                    <label for="password-confirm"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                    <div class="col-md-6 position-relative">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required>
                                        <i class="fas fa-eye password-toggle"
                                            style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);"
                                            onclick="togglePassword('password-confirm')"></i>
                                    </div>
                                </div>

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
            </div>
        </div>
    </section>
@endsection
