@extends('layouts.main')

@section('reset_password')
<section id="forgot-password" class="py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">

                    <!-- Card Header -->
                    <div class="card-header text-center text-white" style="background-color:#0a8332;">
                        <h4 class="mb-0">{{ __('Reset Your Password') }}</h4>
                    </div>

                    <div class="card-body p-4">
                        <!-- Informational text -->
                        <p class="text-center text-muted mb-4">
                            {{ __('Enter your email and new password below to reset your password.') }}
                        </p>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Validation Errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Reset Password Form -->
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control" name="email" 
                                       value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control" 
                                       name="password_confirmation" required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn text-white" style="background-color:#0a8332;">
                                    Reset Password
                                </button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                                Back to Login
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
