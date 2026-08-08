@extends('layouts.main')
@section('email_verification')
    <section id="email-verification" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">

                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2">{{ __('Verify Your Email Address') }}</h5>
                        </div>

                        <div class="card-body">
                            <!-- Instructional Text -->
                            <div class="mb-4 text-center text-gray-600">
                                {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive , we will gladly send you another.') }}
                            </div>

                            <!-- Status Message -->
                            @if (session('status') == 'verification-link-sent')
                                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                                </div>
                            @endif

                            <!-- Resend Verification Link Form -->
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" style="width: 250px">
                                        {{ __('Resend Verification Email') }}
                                    </button>
                                </div>
                            </form>

                            <hr>

                            <!-- Profile and Logout Links -->
                            <div class="text-center mt-4">
                                <a class="btn btn-link links" href="{{ route('profile.show') }}">
                                    {{ __('Edit Profile') }}
                                </a>
                                |
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link links">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
