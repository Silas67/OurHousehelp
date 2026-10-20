@extends('layouts.main')

@section('here')
    <style>
        .oh-welcome {
            --oh-green: #0f6e56;
            --oh-green-dark: #084c3d;
            --oh-green-light: #eaf6f1;
            --oh-ink: #172b25;
            --oh-muted: #64756f;
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 0 4.5rem;
            color: var(--oh-ink);
            background: #f7faf8;
        }

        .oh-welcome__shell {
            width: 100%;
            margin: 0;
        }

        .oh-welcome__card {
            overflow: visible;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .oh-welcome__hero {
            position: relative;
            overflow: hidden;
            padding: clamp(3rem, 8vw, 6.5rem) max(1.25rem, calc((100vw - 1080px) / 2));
            color: #fff;
            background: linear-gradient(125deg, var(--oh-green-dark) 0%, var(--oh-green) 62%, #269873 100%);
        }

        .oh-welcome__hero::after {
            position: absolute;
            top: -100px;
            right: -65px;
            width: 280px;
            height: 280px;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50%;
            content: "";
            box-shadow: 0 0 0 35px rgba(255,255,255,.045), 0 0 0 75px rgba(255,255,255,.035);
            pointer-events: none;
        }

        .oh-welcome__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.1rem;
            padding: .4rem .75rem;
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            color: #fff;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        .oh-welcome__title {
            position: relative;
            z-index: 1;
            max-width: 650px;
            margin: 0 0 .85rem;
            color: #fff;
            font-size: clamp(2rem, 5vw, 3.15rem);
            font-weight: 750;
            letter-spacing: -.045em;
            line-height: 1.08;
        }

        .oh-welcome__intro {
            position: relative;
            z-index: 1;
            max-width: 610px;
            margin: 0;
            color: rgba(255,255,255,.88);
            font-size: 1.05rem;
            line-height: 1.75;
        }

        .oh-welcome__body {
            width: min(1080px, calc(100% - 3rem));
            margin: 0 auto;
            padding: clamp(2.25rem, 5vw, 4rem) 0 0;
        }

        .oh-welcome__section-title {
            margin: 0 0 .45rem;
            font-size: 1.35rem;
            font-weight: 750;
            letter-spacing: -.025em;
        }

        .oh-welcome__muted {
            margin: 0;
            color: var(--oh-muted);
            line-height: 1.7;
        }

        .oh-welcome__credit {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.2rem;
            border: 1px solid #c8e9db;
            border-radius: 16px;
            background: linear-gradient(110deg, #f0fbf5, #e5f6ed);
        }

        .oh-welcome__credit-icon {
            display: grid;
            flex: 0 0 46px;
            width: 46px;
            height: 46px;
            place-items: center;
            border-radius: 13px;
            background: #d2eee0;
            color: var(--oh-green);
            font-size: 1.25rem;
        }

        .oh-welcome__credit-title {
            margin: 0 0 .3rem;
            color: var(--oh-green-dark);
            font-size: 1rem;
            font-weight: 750;
        }

        .oh-welcome__credit-copy {
            margin: 0;
            color: #3f6255;
            font-size: .92rem;
            line-height: 1.6;
        }

        .oh-welcome__actions {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .oh-welcome__action {
            display: flex;
            min-height: 150px;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.25rem;
            border: 1px solid #e1ebe6;
            border-radius: 16px;
            background: #fff;
            color: var(--oh-ink);
            text-align: left;
            text-decoration: none;
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .oh-welcome__action:hover,
        .oh-welcome__action:focus-visible {
            transform: translateY(-3px);
            border-color: #a7d8c3;
            box-shadow: 0 10px 25px rgba(15, 110, 86, .09);
            color: var(--oh-green-dark);
            text-decoration: none;
        }

        .oh-welcome__action--primary {
            border-color: var(--oh-green);
            background: var(--oh-green);
            color: #fff;
        }

        .oh-welcome__action--primary:hover,
        .oh-welcome__action--primary:focus-visible {
            border-color: var(--oh-green-dark);
            background: var(--oh-green-dark);
            color: #fff;
        }

        .oh-welcome__action-icon {
            display: grid;
            width: 42px;
            height: 42px;
            margin-bottom: 1rem;
            place-items: center;
            border-radius: 12px;
            background: var(--oh-green-light);
            color: var(--oh-green);
            font-size: 1.05rem;
        }

        .oh-welcome__action--primary .oh-welcome__action-icon {
            background: rgba(255,255,255,.16);
            color: #fff;
        }

        .oh-welcome__action-title {
            margin: 0 0 .3rem;
            font-size: 1rem;
            font-weight: 750;
        }

        .oh-welcome__action-copy {
            margin: 0;
            color: var(--oh-muted);
            font-size: .87rem;
            line-height: 1.5;
        }

        .oh-welcome__action--primary .oh-welcome__action-copy {
            color: rgba(255,255,255,.82);
        }

        .oh-welcome__disclaimer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #edf1ef;
            color: var(--oh-muted);
            font-size: .9rem;
            line-height: 1.65;
            text-align: left;
        }

        .oh-welcome__approval {
            margin-top: 1.25rem;
            padding: 1rem 1.1rem;
            border: 1px solid #f2d7a2;
            border-radius: 12px;
            background: #fff8e8;
            color: #7b5210;
            line-height: 1.6;
        }

        @media (max-width: 767.98px) {
            .oh-welcome {
                padding: 0 0 2.5rem;
            }

            .oh-welcome__hero {
                padding: 3rem 1.25rem 3.25rem;
            }

            .oh-welcome__body {
                width: calc(100% - 2.5rem);
                padding: 2rem 0 0;
            }

            .oh-welcome__actions {
                grid-template-columns: 1fr;
                gap: .75rem;
            }

            .oh-welcome__action {
                min-height: auto;
                flex-direction: row;
                align-items: center;
                gap: .9rem;
                padding: 1rem;
            }

            .oh-welcome__action-icon {
                flex: 0 0 42px;
                margin: 0;
            }

            .oh-welcome__action-content {
                min-width: 0;
            }

            .oh-welcome__credit {
                gap: .75rem;
                padding: 1rem;
            }

            .oh-welcome__credit-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .oh-welcome__action {
                transition: none;
            }

            .oh-welcome__action:hover,
            .oh-welcome__action:focus-visible {
                transform: none;
            }
        }
    </style>

    <div class="oh-welcome">
        <div class="oh-welcome__shell">
            <div class="oh-welcome__card">
                @if (Auth::user()->account_type === 'client')
                    <header class="oh-welcome__hero">
                        <div class="oh-welcome__eyebrow">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            Account created
                        </div>
                        <h1 class="oh-welcome__title">Welcome, {{ Auth::user()->name }}.</h1>
                        <p class="oh-welcome__intro">
                            A little more help can make home life easier. Find trusted support for cleaning,
                            cooking, childcare and more — all from your account.
                        </p>
                    </header>

                    <div class="oh-welcome__body">
                        @if (Auth::user()->getCreditBalance() > 0 && !session('free_credit_shown_' . Auth::id()))
                            @php session(['free_credit_shown_' . Auth::id() => true]); @endphp
                            <div class="oh-welcome__credit" role="status">
                                <div class="oh-welcome__credit-icon" aria-hidden="true">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div>
                                    <h2 class="oh-welcome__credit-title">A welcome gift is waiting for you</h2>
                                    <p class="oh-welcome__credit-copy">
                                        You have booking credit available. Use it to view a staff profile and get started
                                        with your first booking.
                                    </p>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <a href="{{ route('booking.service-selection') }}" class="btn btn-sm"
                                           style="background:#0f6e56;color:#fff;border-radius:8px;font-weight:700;padding:.55rem .85rem;">
                                            <i class="fas fa-clipboard-list me-1" aria-hidden="true"></i> Book a service
                                        </a>
                                        <a href="{{ route('credits.index') }}" class="btn btn-sm"
                                           style="border:1px solid #b6dcca;color:#0f6e56;border-radius:8px;font-weight:700;padding:.55rem .85rem;">
                                            <i class="fas fa-coins me-1" aria-hidden="true"></i> View credits
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h2 class="oh-welcome__section-title">Your next step</h2>
                        <p class="oh-welcome__muted">Choose where you would like to go. You can always return here later.</p>

                        <div class="oh-welcome__actions">
                            <a href="{{ route('dashboard') }}" class="oh-welcome__action oh-welcome__action--primary">
                                <span class="oh-welcome__action-icon"><i class="fas fa-th-large" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Go to dashboard</span>
                                    <span class="oh-welcome__action-copy d-block">Manage your account and bookings.</span>
                                </span>
                            </a>
                            <a href="{{ route('get-started') }}" class="oh-welcome__action">
                                <span class="oh-welcome__action-icon"><i class="fas fa-calendar-check" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Find house help</span>
                                    <span class="oh-welcome__action-copy d-block">Explore support for your home.</span>
                                </span>
                            </a>
                            <a href="{{ url('/#services') }}" class="oh-welcome__action">
                                <span class="oh-welcome__action-icon"><i class="fas fa-list-alt" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Explore our services</span>
                                    <span class="oh-welcome__action-copy d-block">See the services we offer.</span>
                                </span>
                            </a>
                        </div>

                        <div class="oh-welcome__disclaimer">
                            @include('partials._disclaimer')
                        </div>
                    </div>

                @elseif (Auth::user()->account_type === 'applicant' || Auth::user()->account_type === 'staff')
                    <header class="oh-welcome__hero">
                        <div class="oh-welcome__eyebrow">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                            Registration received
                        </div>
                        <h1 class="oh-welcome__title">Welcome to the team, {{ Auth::user()->name }}.</h1>
                        <p class="oh-welcome__intro">
                            Your next step is to access your dashboard, review your profile and keep your information up to date
                            as you get ready for opportunities with OurHouseHelp.
                        </p>
                    </header>

                    <div class="oh-welcome__body">
                        <h2 class="oh-welcome__section-title">Let's get you started</h2>
                        <p class="oh-welcome__muted">Your dashboard is the place to manage your profile and follow your progress.</p>

                        <div class="oh-welcome__actions">
                            <a href="{{ route('applicantdashboard') }}" class="oh-welcome__action oh-welcome__action--primary">
                                <span class="oh-welcome__action-icon"><i class="fas fa-th-large" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Open my dashboard</span>
                                    <span class="oh-welcome__action-copy d-block">Manage your profile and account.</span>
                                </span>
                            </a>
                            <a href="{{ url('/') }}" class="oh-welcome__action">
                                <span class="oh-welcome__action-icon"><i class="fas fa-home" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Visit homepage</span>
                                    <span class="oh-welcome__action-copy d-block">Learn more about OurHouseHelp.</span>
                                </span>
                            </a>
                            <a href="{{ url('/#services') }}" class="oh-welcome__action">
                                <span class="oh-welcome__action-icon"><i class="fas fa-list-alt" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Explore our services</span>
                                    <span class="oh-welcome__action-copy d-block">See how our service works.</span>
                                </span>
                            </a>
                        </div>

                        <div class="oh-welcome__disclaimer">
                            @include('partials._disclaimer')
                        </div>
                    </div>

                @else
                    <header class="oh-welcome__hero">
                        <div class="oh-welcome__eyebrow">
                            <i class="fas fa-user-shield" aria-hidden="true"></i>
                            Account welcome
                        </div>
                        <h1 class="oh-welcome__title">Welcome, {{ $user->name }}.</h1>
                        <p class="oh-welcome__intro">{{ $randomQuote }}</p>
                    </header>

                    <div class="oh-welcome__body">
                        @if (Auth::check())
                            @if (in_array(Auth::user()->user_role, [1, 2]))
                                <h2 class="oh-welcome__section-title">Your next step</h2>
                                <p class="oh-welcome__muted">Open your dashboard to continue.</p>
                            @else
                                <div class="oh-welcome__approval" role="status">
                                    <strong>Pending approval</strong><br>
                                    Your administrator registration has been received. Please wait for approval before continuing.
                                </div>
                            @endif
                        @endif

                        <div class="oh-welcome__actions">
                            <a href="{{ route('admindashboard') }}" class="oh-welcome__action oh-welcome__action--primary">
                                <span class="oh-welcome__action-icon"><i class="fas fa-th-large" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Go to dashboard</span>
                                    <span class="oh-welcome__action-copy d-block">Continue to your admin area.</span>
                                </span>
                            </a>
                            <a href="{{ url('/') }}" class="oh-welcome__action">
                                <span class="oh-welcome__action-icon"><i class="fas fa-home" aria-hidden="true"></i></span>
                                <span class="oh-welcome__action-content">
                                    <span class="oh-welcome__action-title d-block">Go to homepage</span>
                                    <span class="oh-welcome__action-copy d-block">Return to OurHouseHelp.</span>
                                </span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (!input || !preview || !input.files || !input.files[0]) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection
