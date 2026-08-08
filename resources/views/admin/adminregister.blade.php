@extends('layouts.main')
@section('adminregister')

    <section id="register" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">

                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2">{{ __('Create a New Admin Account') }}</h5>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.register') }}">
                                @csrf

                                <input type="hidden" name="account_type" value="admin">

                                <!-- Name -->
                                <div class="form-group row pt-5">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <input type="hidden" name="user_role" value=0>
                               

                                <!-- Password -->
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                    <div class="col-md-6 position-relative">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        <i class="fas fa-eye password-toggle" style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('password')"></i>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                    <div class="col-md-6 position-relative">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        <i class="fas fa-eye password-toggle" style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('password-confirm')"></i>
                                    </div>
                                </div>

                                <!-- Register Button -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary" style="width: 250px">
                                            {{ __('Register Admin') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Super Password -->
                                <div class="form-group row mt-3">
                                    <label for="super-password" class="col-md-4 col-form-label text-md-end">{{ __('_') }}</label>
                                    <div class="col-md-6 position-relative">
                                        <input id="super-password" type="password" class="form-control" name="super_password" autocomplete="new-password">
                                        <i class="fas fa-eye password-toggle" style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('super-password')"></i>
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



