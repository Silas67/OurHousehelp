@extends('layouts.main')
@section('here')
    <div class="container mt-5 py-5">
        <div class="row justify-content-center py-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header recentapp" style="background: linear-gradient(135deg, #0F6E56, #1D9E75); font-size:24px; padding-top:8px">Our Househelp</div>

                    @if (Auth::user()->account_type === 'client')
                        <div class="card-body text-center">

                            {{-- ── FREE CREDIT WELCOME BANNER ── --}}
                            @if(Auth::user()->getCreditBalance() > 0 && !session('free_credit_shown_' . Auth::id()))
                                @php session(['free_credit_shown_' . Auth::id() => true]); @endphp
                                <div class="alert alert-dismissible fade show mb-4 text-start" role="alert"
                                    style="background: linear-gradient(135deg, #0F6E56, #1D9E75); border: none; border-radius: 12px; color: white;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="font-size: 2.5rem; flex-shrink: 0;">🎁</div>
                                        <div>
                                            <strong style="font-size: 16px;">Welcome Gift from OurHouseHelp!</strong><br>
                                            <span style="opacity: 0.9; font-size: 14px;">
                                                We've added <strong>1 free booking credit</strong> to your account to get you started.
                                                Use it to view a staff profile and make your first booking — on us!
                                            </span>
                                            <div class="mt-2">
                                                <a href="{{ route('booking.service-selection') }}"
                                                    class="btn btn-sm me-2"
                                                    style="background: white; color: #0F6E56; font-weight: 600; border-radius: 6px;">
                                                    <i class="fas fa-clipboard-list me-1"></i> Book Now
                                                </a>
                                                <a href="{{ route('credits.index') }}"
                                                    class="btn btn-sm"
                                                    style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.4); border-radius: 6px;">
                                                    <i class="fas fa-coins me-1"></i> View Credits
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h5>Welcome {{ Auth::user()->name }}!</h5>
                            We provide professional and reliable house help services tailored to meet your unique needs. Our
                            highly-trained staff ensure that your home remains clean, organized, and well-maintained.
                            Whether
                            you need help with cleaning, cooking, or childcare, we've got you covered!</p>
                            <p>Where would you like to go next?</p>
                            @include('partials._disclaimer')
                            <div class="mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    Go to Dashboard
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                    Go Home
                                </a>
                                <a href="/#services" class="btn btn-success">
                                    Our Services
                                </a>
                            </div>
                        </div>
                    @elseif (Auth::user()->account_type === 'applicant' || Auth::user()->account_type === 'staff')
                        <div class="card-body text-center">
                            <h3>Welcome, {{ Auth::user()->name }}!</h3>
                            <p>We're excited to have you as part of our team, providing trusted and dedicated house help
                                services. Our goal is to make a difference in clients' homes by offering reliable
                                support that makes their lives easier.</p>
                            <p>Explore your dashboard to manage your profile, connect with clients, and make the most of
                                the opportunities ahead. Ready to dive in?</p>
                            @include('partials._disclaimer')
                            <div class="mt-4">
                                <a href="{{ route('applicantdashboard') }}" class="btn btn-primary">
                                    Go to Dashboard
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                    Go to Home
                                </a>
                                <a href="/#services" class="btn btn-success">
                                    Our Services
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="card-body text-center">
                            <h3>Welcome, {{ $user->name }}!</h3>

                            <p>{{ $randomQuote }}</p>
                            <hr>

                            @if (Auth::check())
                                @if (in_array(Auth::user()->user_role, [1, 2]))
                                    <p>Where would you like to go next?</p>
                                @else
                                    <p class="text-danger">You have registered as an admin. Please wait for an approval</p>
                                @endif
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('admindashboard') }}" class="btn btn-primary">
                                    Go to Dashboard
                                </a>
                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                    Go to Home
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection