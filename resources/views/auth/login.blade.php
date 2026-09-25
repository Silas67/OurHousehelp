@extends('layouts.main')
@section('login')
    <section id="onetime" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 rounded-4">
                    <div class="card shadow-lg border rounded-4">

                        <div class="card-header text-center text-white rounded-top-4" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
                            <h5 class="py-2">{{ __('Login to Your Account') }}</h5>
                            
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="form-group row pt-5">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-6 position-relative">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password">
                                        <i class="fas fa-eye password-toggle" style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); padding-right:12px;" onclick="togglePassword('password')"></i>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Remember Me Checkbox -->
                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check" style="text-align: left">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Login Button -->
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn w-100 py-2 fw-semibold"
                                        style="background: linear-gradient(135deg, #0F6E56, #1D9E75); color:white; border-radius:8px;">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </div>

                                <hr>
                                <!-- Forgot Your Password & register -->
                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link links" href="{{ route('password.request') }}" style="font-size: 12px;">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif

                                        <br/>
                                        
                                        <h6 class="text-muted fw-bold" style="font-size: 12px;">
                                            Don't have an account? <a href="{{ route('register') }}" style="color:#0F6E56; font-size: 16px;">Register here</a>
                                        </h6>

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
