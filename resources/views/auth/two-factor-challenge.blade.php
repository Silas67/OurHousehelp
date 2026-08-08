@extends('layouts.main')
@section('two_factor_auth')
    <section id="two-factor-auth" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">

                        <div class="card-header text-center text-white" style="background-color:#0a8332">
                            <h5 class="py-2">{{ __('Two-Factor Authentication') }}</h5>
                        </div>

                        <div class="card-body">
                            <div x-data="{ recovery: false }">
                                <!-- Auth Code Instruction -->
                                <div class="mb-4 text-center text-gray-600" x-show="! recovery">
                                    {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                                </div>

                                <!-- Recovery Code Instruction -->
                                <div class="mb-4 text-center text-gray-600" x-cloak x-show="recovery">
                                    {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                                </div>

                                <!-- Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Form -->
                                <form method="POST" action="{{ route('two-factor.login') }}">
                                    @csrf

                                    <!-- Authentication Code Input -->
                                    <div class="form-group" x-show="! recovery">
                                        <label for="code">{{ __('Code') }}</label>
                                        <input id="code" type="text" inputmode="numeric" name="code"
                                            class="form-control" autofocus x-ref="code" autocomplete="one-time-code">
                                    </div>

                                    <!-- Recovery Code Input -->
                                    <div class="form-group" x-cloak x-show="recovery">
                                        <label for="recovery_code">{{ __('Recovery Code') }}</label>
                                        <input id="recovery_code" type="text" name="recovery_code"
                                            class="form-control" x-ref="recovery_code" autocomplete="one-time-code">
                                    </div>

                                    <!-- Toggle Between Auth Code and Recovery Code -->
                                    <div class="mt-3 text-center">
                                        <button type="button" class="btn btn-link" x-show="! recovery"
                                            x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                                            {{ __('Use a recovery code') }}
                                        </button>

                                        <button type="button" class="btn btn-link" x-cloak x-show="recovery"
                                            x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                                            {{ __('Use an authentication code') }}
                                        </button>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary" style="width: 250px">
                                            {{ __('Log in') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
