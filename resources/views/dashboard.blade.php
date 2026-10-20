@extends('layouts.usermain')
@section('userdashboard')
    <div class="ohh-dashboard">

        {{-- ───────────── HERO ───────────── --}}
        <section class="ohh-dashboard-hero">
            <div class="ohh-hero-content">

                <div class="ohh-eyebrow">
                    <span class="ohh-eyebrow-line"></span>
                    YOUR DASHBOARD
                </div>

                <h1 class="text-left">
                    Your home,<br>
                    <span>taken care of.</span>
                </h1>

                <p>
                    Find trusted household professionals, manage your bookings,
                    and get the help you need—all in one place.
                </p>
            </div>

            
        </section>


        {{-- ───────────── RESUME BOOKING ───────────── --}}
        <div class="ohh-resume-section">
            <x-resume-booking />
        </div>


        {{-- ───────────── SERVICES ───────────── --}}
        <section class="ohh-section">

            <div class="ohh-section-heading">
                <div>
                    <span class="ohh-section-kicker">OUR SERVICES</span>
                    <h2>What can we help you with?</h2>
                </div>

                <span class="ohh-section-caption">
                    Choose a service to get started
                </span>
            </div>


            <div class="ohh-services-grid">

                {{-- Live-Out --}}
                <a href="{{ route('service-request.create') }}"
                class="ohh-service-card ohh-service-card-featured">

                    <div class="ohh-card-top">
                        <div class="ohh-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <span class="ohh-card-arrow">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </span>
                    </div>

                    <div class="ohh-card-content">
                        <span class="ohh-service-label">MOST POPULAR</span>

                        <h3>Live-Out House Helps</h3>

                        <p>
                            Professional house helps who work on a
                            schedule and return home daily.
                        </p>
                    </div>

                    <span class="ohh-card-link">
                        Explore service
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>


                {{-- Live-In --}}
                <a href="{{ route('househelps') }}"
                class="ohh-service-card">

                    <div class="ohh-card-top">
                        <div class="ohh-icon">
                            <i class="fas fa-house"></i>
                        </div>

                        <span class="ohh-card-arrow">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </span>
                    </div>

                    <div class="ohh-card-content">
                        <h3>Live-In House Helps</h3>

                        <p>
                            Full-time residential staff who stay in
                            your home and provide daily support.
                        </p>
                    </div>

                    <span class="ohh-card-link">
                        Explore service
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>


                {{-- Semi Live-In --}}
                <div class="ohh-service-card"
                    data-bs-toggle="modal"
                    data-bs-target="#corporateInquiry">

                    <div class="ohh-card-top">
                        <div class="ohh-icon ohh-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>

                        <span class="ohh-card-arrow">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </span>
                    </div>

                    <div class="ohh-card-content">
                        <h3>Corporate Bookings</h3>

                        <p>
                           Staff your executives with vetted drivers, personal
                            assistants, housekeepers or chefs. We handle the
                            search and verification — you approve the hire.
                        </p>
                    </div>

                    <span class="ohh-card-link">
                        See details
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>


                {{-- Skilled Workers --}}
                <a href="{{ route('ourSkilledWorkerServices') }}"
                class="ohh-service-card">

                    <div class="ohh-card-top">
                        <div class="ohh-icon ohh-icon-slate">
                            <i class="fas fa-screwdriver-wrench"></i>
                        </div>

                        <span class="ohh-card-arrow">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </span>
                    </div>

                    <div class="ohh-card-content">
                        <h3>Skilled Workers</h3>

                        <p>
                            Access experienced artisans and technicians
                            for specialised home services.
                        </p>
                    </div>

                    <span class="ohh-card-link">
                        Find an expert
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>

            </div>
        </section>


        {{-- ───────────── ACCOUNT ───────────── --}}
        <section class="ohh-section ohh-account-section">

            <div class="ohh-section-heading">
                <div>
                    <span class="ohh-section-kicker">YOUR ACCOUNT</span>
                    <h2>Manage your account</h2>
                </div>
            </div>

            <div class="ohh-account-grid">

                <a href="{{ route('paymenthistory') }}"
                class="ohh-account-card">

                    <div class="ohh-account-icon">
                        <i class="fas fa-receipt"></i>
                    </div>

                    <div>
                        <h3>Payment History</h3>
                        <p>
                            Review your payments, invoices and transactions.
                        </p>
                    </div>

                    <i class="fas fa-arrow-right ohh-account-arrow"></i>
                </a>


                <a href="{{ route('clientprofile') }}"
                class="ohh-account-card">

                    <div class="ohh-account-icon">
                        <i class="fas fa-user"></i>
                    </div>

                    <div>
                        <h3>My Profile</h3>
                        <p>
                            Update your personal information and preferences.
                        </p>
                    </div>

                    <i class="fas fa-arrow-right ohh-account-arrow"></i>
                </a>

            </div>
        </section>


        {{-- ───────────── SUPPORT ───────────── --}}
        <section class="ohh-support-card">

            <div class="ohh-support-icon">
                <i class="fas fa-headset"></i>
            </div>

            <div class="ohh-support-content">
                <span>NEED ASSISTANCE?</span>
                <h3>We're here to help.</h3>
                <p>
                    Have a question about your booking, payment or account?
                </p>
            </div>

            <button
                type="button"
                class="ohh-support-button"
                data-bs-toggle="modal"
                data-bs-target="#contactModal">
                Contact Support
                <i class="fas fa-arrow-right"></i>
            </button>

        </section>

    </div>

    <!-- Semi Live-in Modal -->
    @include('partials.modals._semiLiveInModal')
    @include('partials.modals._ModalforCorporateInquiry')

    <!-- Contact Us Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="fas fa-headset me-2"></i>Contact Our Support Team
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Support Hours -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">24/7 Support Available</h5>
                                <p class="mb-0">Our support team is available round the clock to assist you with any queries or issues.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone Numbers -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="icon-wrapper bg-success bg-opacity-10 d-inline-flex p-3 rounded-circle">
                                            <i class="fas fa-phone-alt fa-2x text-success"></i>
                                        </div>
                                    </div>
                                    <h4 class="card-title mb-3">Call Us</h4>
                                    
                                    <div class="mb-4">
                                        <h5 class="text-success mb-2">
                                            <i class="fas fa-star me-2"></i>Primary Number
                                        </h5>
                                        <a href="tel:+2349167696525" class="text-decoration-none">
                                            <h3 class="fw-bold text-dark">+234 916 7696 525</h3>
                                        </a>
                                        <div class="mt-3">
                                            <a href="https://wa.me/2349167696525?text=Hello%2C%20I%20need%20assistance%20with..."
                                                target="_blank" class="btn btn-success btn-lg w-100">
                                                <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-top">
                                        <h5 class="text-primary mb-2">
                                            <i class="fas fa-phone me-2"></i>Secondary Number
                                        </h5>
                                        <a href="tel:+2347066663868" class="text-decoration-none">
                                            <h4 class="fw-bold text-dark">+234 706 6663 868</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email & Support -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="icon-wrapper bg-info bg-opacity-10 d-inline-flex p-3 rounded-circle">
                                            <i class="fas fa-envelope fa-2x text-info"></i>
                                        </div>
                                    </div>
                                    <h4 class="card-title mb-3">Email & Support</h4>
                                    
                                    <div class="mb-4">
                                        <h5 class="text-info mb-2">
                                            <i class="fas fa-envelope-open-text me-2"></i>Email Support
                                        </h5>
                                        <a href="mailto:info@ourhousehelp.com" class="text-decoration-none">
                                            <h4 class="fw-bold text-dark">info@ourhousehelp.com</h4>
                                        </a>
                                        <div class="mt-3">
                                            <a href="mailto:info@ourhousehelp.com" class="btn btn-info btn-lg w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Send Email
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-top">
                                        <h5 class="text-dark mb-2">
                                            <i class="fas fa-question-circle me-2"></i>Response Time
                                        </h5>
                                        <div class="alert alert-light">
                                            <p class="mb-0">
                                                <i class="fas fa-bolt text-warning me-1"></i>
                                                <strong>Phone:</strong> Immediate response
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-clock text-warning me-1"></i>
                                                <strong>Email:</strong> Within 2 hours
                                            </p>
                                            <p class="mb-0">
                                                <i class="fab fa-whatsapp text-warning me-1"></i>
                                                <strong>WhatsApp:</strong> Within 15 minutes
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle text-success me-2"></i>
                                What can we help you with?
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Service Booking Assistance</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Payment Issues</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Staff Scheduling</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Account Management</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Complaints & Feedback</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>General Inquiries</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="tel:+2349167696525" class="btn btn-success">
                        <i class="fas fa-phone me-2"></i>Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ohh-dashboard {
            max-width: 1180px;
            margin: 0 auto;
            padding: 4px 24px 70px;
            color: #16241F;
        }

        /* HERO */
        .ohh-dashboard-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 30px;
            padding: 52px 54px;
            margin-bottom: 34px;
            border-radius: 22px;
            background:
                radial-gradient(circle at 85% 20%,
                    rgba(167, 120, 46, 0.18),
                    transparent 35%),
                linear-gradient(135deg, #0B4A3B, #0F6E56);
            color: #fff;
        }

        .ohh-dashboard-hero::after {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -90px;
            bottom: -120px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .ohh-hero-content {
            position: relative;
            z-index: 1;
            text-align: left;
        }

        .ohh-eyebrow,
        .ohh-section-kicker {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .ohh-eyebrow {
            color: #9ce3cb;
            margin-bottom: 16px;
        }

        .ohh-eyebrow-line {
            width: 28px;
            height: 1px;
            background: #009c7b;
        }

        .ohh-dashboard-hero h1 {
            margin: 0;
            font-family: Georgia, serif;
            font-size: clamp(2.3rem, 5vw, 4rem);
            line-height: 1.03;
            font-weight: 500;
        }

        .ohh-dashboard-hero h1 span {
            color: #9ce3cb;
        }

        .ohh-dashboard-hero p {
            max-width: 470px;
            margin: 18px 0 0;
            color: rgba(255,255,255,0.76);
            font-size: 15px;
            line-height: 1.7;
        }

        .ohh-hero-action {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 14px;
            min-width: 190px;
        }

        .ohh-primary-btn {
            width: 190px;
            padding: 14px 20px;
            border: 0;
            border-radius: 11px;
            background: #9ce3cb;
            color: #18352D;
            font-weight: 700;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .ohh-primary-btn:hover {
            transform: translateY(-2px);
            background: #E1C27F;
        }

        .ohh-primary-btn i {
            margin-right: 8px;
        }

        .ohh-support-link {
            border: 0;
            background: transparent;
            color: rgba(255,255,255,0.72);
            font-size: 13px;
            padding: 3px 0;
        }

        .ohh-support-link i {
            margin-right: 6px;
        }

        /* RESUME BOOKING */
        .ohh-resume-section {
            margin-bottom: 48px;
        }

        /* SECTIONS */
        .ohh-section {
            margin-bottom: 52px;
        }

        .ohh-section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 22px;
        }

        .ohh-section-kicker {
            color: #009c7b;
            margin-bottom: 8px;
        }

        .ohh-section-heading h2 {
            margin: 0;
            font-family: Georgia, serif;
            font-size: 28px;
            font-weight: 500;
            color: #0B4A3B;
        }

        .ohh-section-caption {
            color: #7B857F;
            font-size: 13px;
        }

        /* SERVICES */
        .ohh-services-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .ohh-service-card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 275px;
            padding: 27px;
            border: 1px solid #027a6133;
            border-radius: 18px;
            background: #fff;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
        }

        .ohh-service-card:hover {
            transform: translateY(-4px);
            border-color: #B9AA8E;
            box-shadow: 0 14px 35px rgba(22,36,31,0.08);
            color: inherit;
        }

        .ohh-service-card-featured {
            background: linear-gradient(145deg, #d4fcf4, #fff);
            border-color: #027a6133;
        }

        .ohh-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ohh-icon,
        .ohh-account-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #E6F0EB;
            color: #0F6E56;
            font-size: 18px;
        }

        .ohh-icon-gold {
            background: #F5EDDF;
            color: #A9782E;
        }

        .ohh-icon-slate {
            background: #EEF0EF;
            color: #58645F;
        }

        .ohh-card-arrow {
            color: #A6ADA8;
            font-size: 13px;
            transition: 0.2s ease;
        }

        .ohh-service-card:hover .ohh-card-arrow {
            color: #0F6E56;
            transform: translate(2px, -2px);
        }

        .ohh-card-content {
            margin-top: 28px;
        }

        .ohh-service-label {
            display: inline-block;
            margin-bottom: 8px;
            color: #A9782E;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.13em;
        }

        .ohh-card-content h3 {
            margin: 0 0 9px;
            font-size: 20px;
            font-weight: 650;
            color: #18352D;
        }

        .ohh-card-content p {
            max-width: 430px;
            margin: 0;
            color: #69736E;
            font-size: 14px;
            line-height: 1.7;
        }

        .ohh-card-link {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: auto;
            padding-top: 24px;
            color: #0F6E56;
            font-size: 13px;
            font-weight: 700;
        }

        .ohh-card-link i {
            font-size: 11px;
            transition: 0.2s ease;
        }

        .ohh-service-card:hover .ohh-card-link i {
            transform: translateX(4px);
        }

        /* ACCOUNT */
        .ohh-account-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .ohh-account-card {
            display: flex;
            align-items: center;
            gap: 17px;
            padding: 22px;
            border: 1px solid #027a6133;
            border-radius: 16px;
            background: #fff;
            text-decoration: none;
            color: inherit;
            transition: 0.2s ease;
        }

        .ohh-account-card:hover {
            border-color: #B9AA8E;
            box-shadow: 0 10px 25px rgba(22,36,31,0.06);
            color: inherit;
        }

        .ohh-account-card h3 {
            margin: 0 0 5px;
            font-size: 15px;
            font-weight: 700;
            color: #18352D;
        }

        .ohh-account-card p {
            margin: 0;
            color: #7A837E;
            font-size: 13px;
            line-height: 1.5;
        }

        .ohh-account-arrow {
            margin-left: auto;
            color: #A8B0AB;
            font-size: 12px;
        }

        /* SUPPORT */
        .ohh-support-card {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 24px 26px;
            border-radius: 18px;
            background: #F6F2E9;
            border: 1px solid #E6DCC8;
        }

        .ohh-support-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #fff;
            color: #A9782E;
            font-size: 17px;
        }

        .ohh-support-content span {
            display: block;
            margin-bottom: 4px;
            color: #A9782E;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
        }

        .ohh-support-content h3 {
            margin: 0 0 3px;
            font-size: 17px;
            color: #18352D;
        }

        .ohh-support-content p {
            margin: 0;
            color: #6F7974;
            font-size: 13px;
        }

        .ohh-support-button {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 11px 17px;
            border: 1px solid #0F6E56;
            border-radius: 10px;
            background: transparent;
            color: #0F6E56;
            font-size: 13px;
            font-weight: 700;
        }

        .ohh-support-button:hover {
            background: #0F6E56;
            color: #fff;
        }

        /* MOBILE */
        @media (max-width: 768px) {

            .ohh-dashboard {
                padding: 24px 4px 50px;
            }

            .ohh-dashboard-hero {
                flex-direction: column;
                align-items: flex-start;
                padding: 34px 25px;
                border-radius: 18px;
            }

            .ohh-hero-action {
                width: 100%;
                align-items: stretch;
            }

            .ohh-primary-btn {
                width: 100%;
            }

            .ohh-services-grid,
            .ohh-account-grid {
                grid-template-columns: 1fr;
            }

            .ohh-section-heading {
                flex-direction: column;
                align-items: flex-start;
                gap: 7px;
            }

            .ohh-support-card {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .ohh-support-button {
                margin-left: 66px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to modal when opened
            $('#contactModal').on('shown.bs.modal', function () {
                $('.icon-wrapper').addClass('animate__animated animate__pulse');
            });
        });
    </script>
@endsection
