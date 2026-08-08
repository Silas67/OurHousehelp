@extends('layouts.main')
@section('welcome')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --green-50: #E1F5EE;
        --green-100: #9FE1CB;
        --green-600: #0F6E56;
        --green-800: #085041;
        --amber-50: #FAEEDA;
        --amber-400: #BA7517;
        --cream: #FAF8F4;
        --ink: #1C1C1A;
        --muted: #6B6B67;
    }

    .ohh-page { font-family: 'DM Sans', sans-serif; color: var(--ink); }
    .ohh-display { font-family: 'Playfair Display', serif; }

    /* ── Hero ── */
    .ohh-hero {
        background: var(--cream);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .ohh-hero::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 420px; height: 420px;
        border-radius: 50%;
        background: var(--green-50);
        z-index: 0;
    }
    .ohh-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -60px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: var(--amber-50);
        z-index: 0;
    }
    @media (max-width: 768px) {
    .ohh-hero::before {
        display: none;
    },
    }

    @media (max-width: 768px) {
    .ohh-hero .d-flex.gap-3 {
        justify-content: center;
    }
    .ohh-hero h1,
    .ohh-hero-eyebrow,
    .ohh-hero-sub {
        text-align: center;
    }
    .ohh-hero-sub {
        margin-left: auto;
        margin-right: auto;
    }
    .ohh-hero-stats {
        justify-content: center;
    }
 }

    .ohh-hero .container { position: relative; z-index: 1; }
    .ohh-hero-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--green-50);
        color: var(--green-600);
        font-size: 13px; font-weight: 500; letter-spacing: 0.06em;
        padding: 6px 16px; border-radius: 999px;
        margin-bottom: 28px;
        text-transform: uppercase;
    }
    .ohh-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(38px, 5vw, 64px);
        font-weight: 500; line-height: 1.15;
        color: var(--ink);
        margin-bottom: 24px;
    }
    .ohh-hero h1 em { color: var(--green-600); font-style: italic; }
    .ohh-hero-sub {
        font-size: 17px; color: var(--muted); line-height: 1.7;
        max-width: 520px; margin-bottom: 40px; font-weight: 300;
    }
    .ohh-btn-primary {
        background: var(--green-600); color: #fff;
        padding: 14px 32px; border-radius: 8px;
        font-size: 15px; font-weight: 500;
        text-decoration: none; display: inline-block;
        transition: background 0.2s;
        border: none; cursor: pointer;
    }
    .ohh-btn-primary:hover { background: var(--green-800); color: #fff; }
    .ohh-btn-outline {
        background: transparent; color: var(--ink);
        padding: 14px 32px; border-radius: 8px;
        font-size: 15px; font-weight: 500;
        text-decoration: none; display: inline-block;
        border: 1.5px solid #C5C5BF;
        transition: border-color 0.2s, color 0.2s;
    }
    .ohh-btn-outline:hover { border-color: var(--green-600); color: var(--green-600); }
    .ohh-hero-stats {
        display: flex; gap: 40px; margin-top: 56px;
        padding-top: 40px; border-top: 1px solid #E5E5E0;
    }
    .ohh-stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 32px; font-weight: 500; color: var(--ink);
        line-height: 1;
    }
    .ohh-stat-label { font-size: 13px; color: var(--muted); margin-top: 4px; }

    /* ── Trust bar ── */
    .ohh-trust {
        background: var(--green-600); color: #fff;
        padding: 20px 0;
    }
    .ohh-trust-inner {
        display: flex; align-items: center; justify-content: center;
        gap: 48px; flex-wrap: wrap;
    }
    .ohh-trust-item {
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 400; opacity: 0.9;
    }
    .ohh-trust-item i { font-size: 18px; }

    /* ── About ── */
    .ohh-about { padding: 96px 0; background: #fff; }
    .ohh-section-tag {
        font-size: 12px; font-weight: 500; letter-spacing: 0.1em;
        text-transform: uppercase; color: var(--green-600);
        margin-bottom: 16px; display: block;
    }
    .ohh-section-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(28px, 3.5vw, 44px);
        font-weight: 500; line-height: 1.2; color: var(--ink);
        margin-bottom: 20px;
    }
    .ohh-section-body {
        font-size: 16px; color: var(--muted);
        line-height: 1.8; font-weight: 300;
    }
    .ohh-about-card {
        background: var(--cream); border-radius: 16px;
        padding: 36px; height: 100%;
        border: 1px solid #EAE9E3;
        transition: border-color 0.2s, transform 0.2s;
    }
    .ohh-about-card:hover { border-color: var(--green-100); transform: translateY(-2px); }
    .ohh-about-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: var(--green-50);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px; font-size: 22px; color: var(--green-600);
    }
    .ohh-about-card h4 {
        font-family: 'Playfair Display', serif;
        font-size: 20px; font-weight: 500; margin-bottom: 12px; color: var(--ink);
    }
    .ohh-about-card p { font-size: 15px; color: var(--muted); line-height: 1.7; margin: 0; }

    /* ── Services ── */
    .ohh-services { padding: 96px 0; background: var(--cream); }
    .ohh-service-card {
        background: #fff; border-radius: 16px;
        padding: 32px 28px;
        border: 1px solid #EAE9E3;
        transition: all 0.2s; height: 100%;
    }
    .ohh-service-card:hover {
        border-color: var(--green-600);
        box-shadow: 0 8px 32px rgba(15, 110, 86, 0.08);
        transform: translateY(-3px);
    }
    .ohh-service-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: var(--green-50); display: flex;
        align-items: center; justify-content: center;
        font-size: 24px; color: var(--green-600); margin-bottom: 20px;
    }
    .ohh-service-card h5 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 500; margin-bottom: 10px; color: var(--ink);
    }
    .ohh-service-card p { font-size: 14px; color: var(--muted); line-height: 1.7; margin: 0; }

    /* ── How it works ── */
    .ohh-how { padding: 96px 0; background: #fff; }
    .ohh-step { display: flex; gap: 24px; margin-bottom: 48px; }
    .ohh-step:last-child { margin-bottom: 0; }
    .ohh-step-num {
        flex-shrink: 0;
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--green-600); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 500;
    }
    .ohh-step-body h5 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 500; margin-bottom: 8px; color: var(--ink);
    }
    .ohh-step-body p { font-size: 15px; color: var(--muted); line-height: 1.7; margin: 0; }
    .ohh-how-visual {
        background: var(--green-50); border-radius: 20px;
        padding: 48px 40px; text-align: center;
        border: 1px solid var(--green-100);
    }
    .ohh-how-visual-num {
        font-family: 'Playfair Display', serif;
        font-size: 80px; font-weight: 500; color: var(--green-600);
        line-height: 1; margin-bottom: 12px; opacity: 0.3;
    }
    .ohh-how-visual-text {
        font-size: 14px; color: var(--green-600); font-weight: 500;
        letter-spacing: 0.05em; text-transform: uppercase;
    }

    /* ── Gallery ── */
    .ohh-gallery { padding: 96px 0; background: var(--cream); }
    .ohh-gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: auto;
        gap: 16px;
    }
    .ohh-gallery-item {
        border-radius: 14px; overflow: hidden;
        position: relative; cursor: pointer;
        background: #E5E5E0;
    }
    .ohh-gallery-item img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
        transition: transform 0.4s;
    }
    .ohh-gallery-item:hover img { transform: scale(1.04); }
    .ohh-gallery-item.tall { grid-row: span 2; }
    .ohh-gallery-caption {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(28,28,26,0.75));
        padding: 24px 16px 14px;
        color: #fff; font-size: 13px; font-weight: 500;
        opacity: 0; transition: opacity 0.2s;
    }
    .ohh-gallery-item:hover .ohh-gallery-caption { opacity: 1; }

    /* ── CTA ── */
    .ohh-cta {
        padding: 96px 0; background: var(--green-800);
        position: relative; overflow: hidden; text-align: center;
    }
    .ohh-cta::before {
        content: '';
        position: absolute; top: -100px; left: 50%;
        transform: translateX(-50%);
        width: 600px; height: 600px; border-radius: 50%;
        background: rgba(255,255,255,0.03);
    }
    .ohh-cta h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(32px, 4vw, 52px);
        font-weight: 500; color: #fff; margin-bottom: 16px; line-height: 1.2;
    }
    .ohh-cta p { font-size: 17px; color: rgba(255,255,255,0.65); margin-bottom: 40px; font-weight: 300; }
    .ohh-btn-white {
        background: #fff; color: var(--green-800);
        padding: 14px 36px; border-radius: 8px;
        font-size: 15px; font-weight: 500;
        text-decoration: none; display: inline-block;
        transition: opacity 0.2s;
    }
    .ohh-btn-white:hover { opacity: 0.9; color: var(--green-800); }

    /* ── Contact ── */
    .ohh-contact { padding: 96px 0; background: #fff; }
    .ohh-contact-info { padding-right: 40px; }
    .ohh-contact-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px; font-weight: 500; margin-bottom: 24px; color: var(--ink);
    }
    .ohh-contact-row {
        display: flex; align-items: flex-start; gap: 14px;
        margin-bottom: 20px;
    }
    .ohh-contact-icon {
        width: 40px; height: 40px; border-radius: 10px;
        background: var(--green-50); flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; color: var(--green-600);
    }
    .ohh-contact-detail { font-size: 14px; color: var(--muted); line-height: 1.6; }
    .ohh-contact-detail a { color: var(--green-600); text-decoration: none; }
    .ohh-contact-detail strong { display: block; font-size: 13px; font-weight: 500; color: var(--ink); margin-bottom: 2px; }
    .ohh-form-card {
        background: var(--cream); border-radius: 20px;
        padding: 40px; border: 1px solid #EAE9E3;
    }
    .ohh-form-card .form-control, .ohh-form-card .form-select {
        border: 1px solid #E0E0D8; border-radius: 10px;
        padding: 12px 16px; font-size: 15px; background: #fff;
        font-family: 'DM Sans', sans-serif;
    }
    .ohh-form-card .form-control:focus {
        border-color: var(--green-600);
        box-shadow: 0 0 0 3px rgba(15,110,86,0.1);
        outline: none;
    }
    .ohh-form-card label {
        font-size: 13px; font-weight: 500; color: var(--ink);
        margin-bottom: 6px; display: block;
    }
    .ohh-form-card .form-group { margin-bottom: 16px; }
</style>

<div class="ohh-page">

    {{-- ── HERO ── --}}
    <section class="ohh-hero">
        @php
            $hour = date('H');
            $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
            $icon = $hour < 12 ? '☀️' : ($hour < 18 ? '🌤️' : '🌙');
        @endphp

        <div style="
            position: absolute; top: 24px; left: 24px;
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            padding: 8px 18px;
            font-family: 'DM Sans', sans-serif;
        ">
            <span style="font-size: 16px;">{{ $icon }}</span>
            <span style="font-size: 14px; font-weight: 400; color: #000000; letter-spacing: 0.02em;">
            {{ $greeting }}, <strong style="font-weight: 500;">{{ Auth::user()->name ?? 'Welcome' }}</strong>
            </span>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    
                    <div class="ohh-hero-eyebrow">
                        <i class="fas fa-shield-alt"></i>
                        Trusted Domestic Services in Nigeria
                    </div>
                    <h1>
                        Your Home,<br>
                        <em>Perfectly Cared For</em>
                    </h1>
                    <p class="ohh-hero-sub">
                        We connect you with verified, professional domestic staff — from cleaners and nannies
                        to cooks and drivers. Simple booking, trusted people, peaceful home.
                    </p>
                    <div class="d-flex flex-wrap gap-3 ">
                        <a href="{{ route('get-started') }}" class="ohh-btn-primary">Book a Staff &rarr; </a>

                        <a href="{{ route('welcome-index') }}" class="ohh-btn-outline">Learn More</a>
                    </div>
                    <div class="ohh-hero-stats">
                        <div>
                            <div class="ohh-stat-num">500+</div>
                            <div class="ohh-stat-label">Verified Staff</div>
                        </div>
                        <div>
                            <div class="ohh-stat-num">1,200+</div>
                            <div class="ohh-stat-label">Happy Families</div>
                        </div>
                        <div>
                            <div class="ohh-stat-num">6</div>
                            <div class="ohh-stat-label">Services Offered</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div style="position:relative; padding: 20px;">
                        <img src="{{ asset('build/assets/image/gallery/cleaning 3.jpg') }}"
                            alt="Professional domestic staff"
                            style="width:100%; border-radius:20px; object-fit:cover; height:440px;">
                        <div style="position:absolute; bottom:40px; left:0; background:#fff; border-radius:14px; padding:16px 20px; box-shadow:0 8px 32px rgba(0,0,0,0.1); width:200px;">
                            <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Latest Match</div>
                            <div style="font-size:14px; font-weight:500; color:var(--ink);">Cleaning + Laundry</div>
                            <div style="font-size:12px; color:var(--green-600); margin-top:4px;">
                                <i class="fas fa-check-circle"></i> Staff confirmed in 2hrs
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── TRUST BAR ── --}}
    <div class="ohh-trust">
        <div class="container">
            <div class="ohh-trust-inner">
                <div class="ohh-trust-item">
                    <i class="fas fa-id-card"></i>
                    NIN Verified Staff
                </div>
                <div class="ohh-trust-item">
                    <i class="fas fa-user-shield"></i>
                    Background Checked
                </div>
                <div class="ohh-trust-item">
                    <i class="fas fa-sync-alt"></i>
                    Free Replacements
                </div>
                <div class="ohh-trust-item">
                    <i class="fas fa-headset"></i>
                    Dedicated Support
                </div>
                <div class="ohh-trust-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Abuja & Beyond
                </div>
            </div>
        </div>
    </div>

    {{-- ── ABOUT ── --}}
    <section class="ohh-about" id="about">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <span class="ohh-section-tag">Who We Are</span>
                    <h2 class="ohh-section-heading">
                        Domestic staffing done with <em style="font-style:italic; color:var(--green-600);">care</em>
                    </h2>
                    <p class="ohh-section-body">
                        At OurHouseHelp, we understand that letting someone into your home is a matter of trust.
                        Every staff member on our platform is personally vetted, trained, and insured —
                        so you can focus on what matters most to you.
                    </p>
                    <a href="{{ route('booking.service-selection') }}" class="ohh-btn-primary mt-4 d-inline-block">
                        Get Started
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="ohh-about-card">
                                <div class="ohh-about-icon"><i class="fas fa-home"></i></div>
                                <h4>For Your Home</h4>
                                <p>Reliable domestic services tailored to your household needs — on your schedule, at your pace.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-md-4">
                            <div class="ohh-about-card">
                                <div class="ohh-about-icon"><i class="fas fa-users"></i></div>
                                <h4>Skilled Workers</h4>
                                <p>All our staff are professionals with verified credentials and proven track records.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ohh-about-card">
                                <div class="ohh-about-icon"><i class="fas fa-shield-alt"></i></div>
                                <h4>Safety First</h4>
                                <p>Every applicant undergoes NIN verification, guarantor checks, and background screening.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-md-4">
                            <div class="ohh-about-card">
                                <div class="ohh-about-icon"><i class="fas fa-star"></i></div>
                                <h4>Quality Assured</h4>
                                <p>Our rating and review system ensures you always get the best available staff.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SERVICES ── --}}
    <section class="ohh-services" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ohh-section-tag">What We Offer</span>
                <h2 class="ohh-section-heading">Services built for modern homes</h2>
                <p class="ohh-section-body mx-auto" style="max-width:540px;">
                    From everyday cleaning to specialist care — we have a professional for every need.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-broom"></i></div>
                        <h5>Cleaning Service</h5>
                        <p>Well trained cleaners for apartments of all sizes — weekly or daily visits tailored to you.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-tshirt"></i></div>
                        <h5>Laundry Service</h5>
                        <p>Washing, ironing, folding — handled with care so your clothes are always ready.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-baby"></i></div>
                        <h5>Nanny Service</h5>
                        <p>Trusted, experienced nannies who genuinely care for your children's wellbeing.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-utensils"></i></div>
                        <h5>Cook Service</h5>
                        <p>Skilled cooks who prepare fresh, delicious meals — your menu, your kitchen.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-car"></i></div>
                        <h5>Driver Service</h5>
                        <p>Professional drivers for school runs, errands, and daily commutes. Safe and reliable.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ohh-service-card">
                        <div class="ohh-service-icon"><i class="fas fa-running"></i></div>
                        <h5>Errand Service</h5>
                        <p>Need something done? Our errand staff handle deliveries, pickups, and more.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── HOW IT WORKS ── --}}
    <section class="ohh-how">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="ohh-section-tag">How It Works</span>
                    <h2 class="ohh-section-heading">From request to relief, in three steps</h2>
                    <div class="mt-5">
                        <div class="ohh-step">
                            <div class="ohh-step-num">1</div>
                            <div class="ohh-step-body">
                                <h5>Tell Us What You Need</h5>
                                <p>Select your services, location, and schedule. Our platform finds the best available staff in your area.</p>
                            </div>
                        </div>
                        <div class="ohh-step">
                            <div class="ohh-step-num">2</div>
                            <div class="ohh-step-body">
                                <h5>Review Your Matches</h5>
                                <p>Browse verified staff profiles, check ratings and experience, then choose who fits your home best.</p>
                            </div>
                        </div>
                        <div class="ohh-step">
                            <div class="ohh-step-num">3</div>
                            <div class="ohh-step-body">
                                <h5>They Start, You Relax</h5>
                                <p>Your selected staff begins work. Client managed or fully handled by OurHouseHelp — the choice is yours.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="ohh-how-visual">
                                <div class="ohh-how-visual-num">01</div>
                                <div class="ohh-how-visual-text">Select</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="ohh-how-visual" style="background:#fff; border-color:#EAE9E3;">
                                <div class="ohh-how-visual-num" style="color:var(--amber-400);">02</div>
                                <div class="ohh-how-visual-text" style="color:var(--amber-400);">Match</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="ohh-how-visual" style="background: var(--green-800); border-color: var(--green-800);">
                                <div class="ohh-how-visual-num" style="color:#fff; opacity:0.2;">03</div>
                                <div class="ohh-how-visual-text" style="color:rgba(255,255,255,0.7);">Relax</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── GALLERY ── --}}
    <section class="ohh-gallery" id="gallery">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ohh-section-tag">Gallery</span>
                <h2 class="ohh-section-heading">Our staff in action</h2>
            </div>
            <div class="ohh-gallery-grid">
                <div class="ohh-gallery-item tall" style="min-height:300px;">
                    <img src="{{ asset('build/assets/image/gallery/cleaning 3.jpg') }}" alt="Cleaning Services">
                    <div class="ohh-gallery-caption">Cleaning Services</div>
                </div>
                <div class="ohh-gallery-item" style="min-height:180px;">
                    <img src="{{ asset('build/assets/image/gallery/nanny 2.jpg') }}" alt="Nanny Services">
                    <div class="ohh-gallery-caption">Nanny Services</div>
                </div>
                <div class="ohh-gallery-item" style="min-height:180px;">
                    <img src="{{ asset('build/assets/image/gallery/cook 2.jpg') }}" alt="Cook Services">
                    <div class="ohh-gallery-caption">Cook Services</div>
                </div>
                <div class="ohh-gallery-item" style="min-height:180px;">
                    <img src="{{ asset('build/assets/image/gallery/laundry 2.jpg') }}" alt="Laundry Services">
                    <div class="ohh-gallery-caption">Laundry Services</div>
                </div>
                <div class="ohh-gallery-item" style="min-height:180px;">
                    <img src="{{ asset('build/assets/image/gallery/driver 2.jpg') }}" alt="Driver Services">
                    <div class="ohh-gallery-caption">Driver Services</div>
                </div>
                <div class="ohh-gallery-item" style="min-height:180px;">
                    <img src="{{ asset('build/assets/image/gallery/errand 5.jpg') }}" alt="Errand Services">
                    <div class="ohh-gallery-caption">Errand Services</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA ── --}}
    <section class="ohh-cta">
        <div class="container" style="position:relative; z-index:1;">
    <div class="mx-auto text-center" style="max-width: 600px;">
        <div class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-2 rounded-pill"
            style="background: rgba(255,255,255,0.12); font-size: 13px; color: rgba(255,255,255,0.8); letter-spacing: 0.06em; text-transform: uppercase;">
            <i class="fas fa-briefcase" style="font-size: 11px;"></i>
            Now Hiring Across Abuja
        </div>
        <h2 style="font-family: 'Playfair Display', serif; font-size: clamp(32px, 5vw, 52px); font-weight: 500; color: #fff; line-height: 1.2; margin-bottom: 16px;">
            Turn your skills into a <em style="font-style: italic; color: #9FE1CB;">steady income</em>
        </h2>
        <p style="font-size: 17px; color: rgba(255,255,255,0.65); margin-bottom: 12px; font-weight: 300; line-height: 1.7;">
            Join over <strong style="color: #fff;">140 trusted workers</strong> earning consistently
            through OurHouseHelp. We handle the clients — you focus on the work.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" style="font-size: 13px; color: rgba(255,255,255,0.55);">
            <span><i class="fas fa-check-circle me-1" style="color: #9FE1CB;"></i>Free to join</span>
            <span><i class="fas fa-check-circle me-1" style="color: #9FE1CB;"></i>Verified clients only</span>
            <span><i class="fas fa-check-circle me-1" style="color: #9FE1CB;"></i>Weekly job matches</span>
            <span><i class="fas fa-check-circle me-1" style="color: #9FE1CB;"></i>Payment guaranteed</span>
        </div>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('househelpapply') }}" class="ohh-btn-white d-flex align-items-center gap-2">
                <i class="fas fa-home"></i>
                Apply as Househelp
            </a>
            <a href="{{ route('applyasaskillworker') }}" class="ohh-btn-outline d-flex align-items-center gap-2"
                style="color:#fff; border-color:rgba(255,255,255,0.3);">
                <i class="fas fa-tools"></i>
                Apply as Skilled Worker
            </a>
        </div>
        <p style="font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 20px;">
            No fees. No hidden charges. Just opportunities.
        </p>
    </div>
</div>
    </section>

    {{-- ── CONTACT ── --}}
    <section class="ohh-contact" id="contact">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ohh-section-tag">Get In Touch</span>
                <h2 class="ohh-section-heading">We'd love to hear from you</h2>
            </div>
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="ohh-contact-info">
                        <h3>Contact Information</h3>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-envelope"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Email</strong>
                                <a href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a>
                            </div>
                        </div>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-phone"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Phone</strong>
                                <a href="tel:+2349167696525">+234 916 7696 525</a><br>
                                <a href="tel:+2347066663868">+234 706 6663 868</a>
                            </div>
                        </div>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Address</strong>
                                7 Santana Close Wuse 2, Behind Glo Office, Abuja.
                            </div>
                        </div>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fab fa-whatsapp"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>WhatsApp</strong>
                                <a href="https://wa.me/2348126237436" target="_blank">08126237436</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ohh-form-card">
                        <form id="contact-form" action="{{ route('contact-message.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ohh-form-card form-group" style="padding:0; background:none; border:none;">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="first_name"
                                            value="{{ Auth::user()->name ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ohh-form-card form-group" style="padding:0; background:none; border:none;">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="last_name"
                                            value="{{ Auth::user()->last_name ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ Auth::user()->email ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Phone</label>
                                    <input type="tel" class="form-control" name="phone"
                                        value="{{ Auth::user()->phone ?? '' }}" required>
                                </div>
                                <div class="col-12">
                                    <label>Subject</label>
                                    <input type="text" class="form-control" name="subject" required>
                                </div>
                                <div class="col-12">
                                    <label>Message</label>
                                    <textarea class="form-control" name="message" rows="4" required></textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="ohh-btn-primary w-100">
                                        Send Message &rarr;
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection