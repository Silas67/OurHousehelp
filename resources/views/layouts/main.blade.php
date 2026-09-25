<!DOCTYPE html>
<html lang="en">

@include('partials._head')
@include('partials.modals._ModalforLogout')

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div>
            <div class="loader"></div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>


    <!-- Professional Navbar with Desktop Dropdown and Mobile Sliding Menu -->
    <nav class="navbar navbar-expand-lg ohh-navbar">
        <div class="container-fluid ">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                <x-img width="150" src="build/assets/image/logo/ohh-log-b.png" alt="Our House Help" loading="eager"
                    class="d-none d-md-block" />
                <x-img width="100" src="build/assets/image/logo/ohh-log-b.png" alt="Our House Help" loading="eager"
                    class="d-md-none" />
            </a>

            <!-- Mobile Menu Toggle (only shows on mobile) -->
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Desktop Menu (shows on lg screens and up) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome-index') }}/#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome-index') }}/#services">Services</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome-index') }}/#gallery">Gallery</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome-index') }}/#contact">Contact</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('packages') }}">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">FAQs</a></li>
                </ul>

                <!-- Dashboard Links (Desktop) -->
                @if (Auth::check())
                    <div class="d-flex align-items-center ms-3">
                    <!-- Profile Dropdown (Desktop) -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-profile-circle me-2"
                                    style="width:32px;height:32px;background-color:#2c6d08;color:#fff;border-radius:50%;display:flex;justify-content:center;align-items:center;font-weight:bold;">
                                    {{ strtoupper(Auth::user()->name[0]) }}
                                </div>
                                <span style="font-size:16px;">{{ Auth::user()->name }}</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser" style="min-width:260px;">

                                {{-- User identity header --}}
                                <li class="px-3 py-2" style="border-bottom:1px solid #f0f0f0;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:38px;height:38px;background:#0F6E56;color:white;font-weight:600;font-size:16px;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div class="fw-semibold text-truncate" style="font-size:14px;max-width:160px;">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                                            <div class="text-muted text-truncate" style="font-size:11px;max-width:160px;">{{ Auth::user()->email }}</div>
                                            @if(in_array(Auth::user()->user_role, [1, 2]))
                                                <span class="badge mt-1" style="background:#6f42c1;font-size:10px;">Admin</span>
                                            @elseif(Auth::user()->account_type == 'client')
                                                <span class="badge mt-1" style="background:#0F6E56;font-size:10px;">Client</span>
                                            @elseif(Auth::user()->account_type == 'applicant')
                                                <span class="badge mt-1" style="background:#BA7517;font-size:10px;">Applicant</span>
                                            @elseif(Auth::user()->account_type == 'staff')
                                                <span class="badge mt-1" style="background:#0a58ca;font-size:10px;">Staff</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>

                                {{-- Dashboard link based on role --}}
                                @if(in_array(Auth::user()->user_role, [1, 2]))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admindashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                        </a>
                                    </li>
                                @elseif(Auth::user()->account_type == 'client')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('booking.service-selection') }}">
                                            <i class="fas fa-clipboard-list me-2"></i>Book a Service
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('clientprofile') }}">
                                            <i class="fas fa-user-cog me-2"></i>My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('paymenthistory') }}">
                                            <i class="fas fa-history me-2"></i>Payment History
                                        </a>
                                    </li>
                                @elseif(Auth::user()->account_type == 'applicant' || Auth::user()->account_type == 'staff')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('applicantdashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('applicantprofile', Auth::user()->id) }}">
                                            <i class="fas fa-user me-2"></i>My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('applicant.shortlistOffers') }}">
                                            <i class="fas fa-bell me-2"></i>Shortlist Offers
                                            @php
                                                $pendingOffers = \App\Models\StaffShortlist::where('staff_id', Auth::id())
                                                    ->where('status', 'notified')
                                                    ->count();
                                            @endphp
                                            @if($pendingOffers > 0)
                                                <span class="badge bg-danger ms-auto">{{ $pendingOffers }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif

                                {{-- Credits for clients --}}
                                @if(Auth::user()->account_type == 'client')
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('credits.index') }}">
                                            <i class="fas fa-coins text-warning"></i>
                                            Credits: <strong>{{ auth()->user()->getCreditBalance() }}</strong>
                                            <span class="badge bg-warning text-dark ms-auto">Buy More</span>
                                        </a>
                                    </li>
                                @endif

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"      data-bs-target="#logoutConfirmModal">
                                         <i class="fas fa-sign-out-alt me-2"></i>Log Out
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>


                        {{-- @if (in_array(Auth::user()->user_role, [1, 2]))
                            <a class="btn btn-warning btn-sm ms-2" href="{{ route('admindashboard') }}" style="border-radius: 40px; font-size:12px;">
                                <i class="fas fa-tacometer-alt me-1"></i>Dashboard
                            </a>
                        @elseif (Auth::user()->account_type == 'client')
                            <a class="btn btn-warning btn-sm me-2" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        @elseif (Auth::user()->account_type == 'applicant' || Auth::user()->account_type == 'staff')
                            <a class="btn btn-warning btn-sm me-2" href="{{ route('applicantdashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>{{ Auth::user()->approval_status === 'approved' ? 'Staff' : 'Applicant' }} Dashboard
                            </a>
                        @else
                            <a class="btn btn-warning btn-sm me-2" href="/">
                                <i class="fas fa-exclamation-circle me-1"></i>Pending Approval
                            </a>
                        @endif --}}
                    </div>
                @else
                    <div class="d-flex ms-3">
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        {{-- <a class="nav-link" href="{{ route('househelpapply') }}">Apply as
                            Househelp</a>
                        <a class="nav-link" href="{{ route('applyasaskillworker') }}">Apply as Skilled
                            Worker</a> --}}
                    </div>


                @endif
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Offcanvas - only shows on mobile) -->
    <div class="offcanvas offcanvas-start ohh-mobile-sidebar d-lg-none" tabindex="-1" id="mobileMenu"
        aria-labelledby="mobileMenuLabel">
        {{-- ── MOBILE SIDEBAR HEADER ── --}}
        <div class="offcanvas-header" style="background: linear-gradient(135deg, #0F6E56, #1D9E75); padding: 16px;">
            @if (Auth::check())
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:46px;height:46px;background:rgba(255,255,255,0.2);color:white;font-weight:700;font-size:18px;border:2px solid rgba(255,255,255,0.4);">
                        {{ strtoupper(Auth::user()->name[0]) }}
                    </div>
                    <div style="min-width:0;flex:1;">
                        <div class="fw-bold text-white text-truncate" style="font-size:15px;">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-truncate" style="font-size:11px;color:rgba(255,255,255,0.7);">{{ Auth::user()->email }}</div>
                        <div class="mt-1">
                            @if(in_array(Auth::user()->user_role, [1, 2]))
                                <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Admin</span>
                            @elseif(Auth::user()->account_type == 'client')
                                <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Client</span>
                            @elseif(Auth::user()->account_type == 'applicant')
                                <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Applicant</span>
                            @elseif(Auth::user()->account_type == 'staff')
                                <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Staff</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <h5 class="offcanvas-title text-white mb-0" id="mobileMenuLabel">Menu</h5>
            @endif
            <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        {{-- ── MOBILE SIDEBAR BODY ── --}}
        <div class="offcanvas-body px-0 py-0">
            <ul class="navbar-nav">

                {{-- Role-specific quick links --}}
                @if (Auth::check())
                    <li class="nav-item px-3 pt-3 pb-1">
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">My Account</div>
                    </li>

                    @if(in_array(Auth::user()->user_role, [1, 2]))
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('admindashboard') }}">
                                <i class="fas fa-tachometer-alt" style="color:#0F6E56;width:16px;"></i>Admin Dashboard
                            </a>
                        </li>
                    @elseif(Auth::user()->account_type == 'client')
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt" style="color:#0F6E56;width:16px;"></i>My Dashboard
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="#bookingSubmenu" data-bs-toggle="collapse">
                                <i class="fas fa-clipboard-list" style="color:#0F6E56;width:16px;"></i>
                                Book a Staff
                                <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                            </a>
                            <ul class="nav flex-column collapse ps-3" id="bookingSubmenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('booking.*') ? 'active' : '' }} d-flex align-items-center gap-2" 
                                        href="{{ route('ourLiveInServices') }}" style="color:inherit;">
                                        <i class="fas fa-home" style="color:#0F6E56;width:16px;"></i>
                                        Live-In Staff
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('househelps') ? 'active' : '' }} d-flex align-items-center gap-2" 
                                        href="{{ route('booking.service-selection') }}" style="color:inherit;">
                                        <i class="fas fa-walking" style="color:#0F6E56;width:16px;"></i>
                                        Live-Out Staff
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('clientprofile') }}">
                                <i class="fas fa-user-cog" style="color:#0F6E56;width:16px;"></i>My Profile
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('credits.index') }}">
                                <i class="fas fa-coins" style="color:#BA7517;width:16px;"></i>
                                Credits: <strong>{{ auth()->user()->getCreditBalance() }}</strong>
                                <span class="badge ms-auto" style="background:#BA7517;font-size:9px;">Buy</span>
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('paymenthistory') }}">
                                <i class="fas fa-history" style="color:#0F6E56;width:16px;"></i>Payment History
                            </a>
                        </li>
                    @elseif(Auth::user()->account_type == 'applicant' || Auth::user()->account_type == 'staff')
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('applicantdashboard') }}">
                                <i class="fas fa-tachometer-alt" style="color:#0F6E56;width:16px;"></i>
                                {{ Auth::user()->approval_status === 'approved' ? 'Staff' : 'Applicant' }} Dashboard
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('applicantprofile', Auth::user()->id) }}">
                                <i class="fas fa-user" style="color:#0F6E56;width:16px;"></i>My Profile
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link d-flex align-items-center gap-2" href="{{ route('applicant.shortlistOffers') }}">
                                <i class="fas fa-bell" style="color:#0F6E56;width:16px;"></i>Shortlist Offers
                            </a>
                        </li>
                    @endif

                    <li class="nav-item px-3 pt-3 pb-1">
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Navigate</div>
                    </li>
                @endif

                {{-- Main nav links --}}
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="/"><i class="fas fa-home" style="color:#0F6E56;width:16px;"></i>Home</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('welcome-index') }}/#about"><i class="fas fa-info-circle" style="color:#0F6E56;width:16px;"></i>About</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('welcome-index') }}/#services"><i class="fas fa-concierge-bell" style="color:#0F6E56;width:16px;"></i>Services</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('welcome-index') }}/#gallery"><i class="fas fa-images" style="color:#0F6E56;width:16px;"></i>Gallery</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('welcome-index') }}/#contact"><i class="fas fa-envelope" style="color:#0F6E56;width:16px;"></i>Contact</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('packages') }}"><i class="fas fa-box-open" style="color:#0F6E56;width:16px;"></i>Packages</a></li>
                <li class="nav-item px-2"><a class="nav-link d-flex align-items-center gap-2" href="{{ route('faq') }}"><i class="fas fa-question-circle" style="color:#0F6E56;width:16px;"></i>FAQs</a></li>

                {{-- Join Us — hidden for logged-in clients --}}
                @if(!Auth::check() || (Auth::check() && Auth::user()->account_type !== 'client'))
                    <li class="nav-item px-3 pt-3 pb-1">
                        <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Join Us</div>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('househelpapply') }}">
                            <i class="fas fa-broom" style="color:#0F6E56;width:16px;"></i>Apply as Househelp
                        </a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('applyasaskillworker') }}">
                            <i class="fas fa-tools" style="color:#0F6E56;width:16px;"></i>Apply as Skilled Worker
                        </a>
                    </li>
                @endif

                {{-- Bottom actions --}}
                <li class="nav-item px-2 mt-2" style="border-top:1px solid #f0f0f0;padding-top:12px!important;">
                    @if (Auth::check())
                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                            <i class="fas fa-sign-out-alt me-2"></i>Log Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <a class="nav-link d-flex align-items-center gap-2" href="{{ route('login') }}" style="color:#0F6E56;">
                            <i class="fas fa-sign-in-alt" style="width:16px;"></i>Login
                        </a>
                    @endif
                </li>

            </ul>
        </div>
    </div>

    <!-- CSS Styles -->
    <style>
       /* =========================================================
   OHH PREMIUM NAVBAR
   ========================================================= */

        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        #preloader {
            margin: 0 !important;
        }

        .ohh-navbar {
            min-height: 76px;
            padding: 0.7rem 1.5rem;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid #ebe5d9;
            box-shadow: 0 4px 18px rgba(22, 36, 31, 0.05);
            position: relative;
            z-index: 1030;
        }

        .ohh-navbar .container-fluid {
            max-width: 1380px;
        }

        .ohh-navbar .navbar-brand {
            display: flex;
            align-items: center;
            margin-right: 38px;
        }

        .ohh-navbar .navbar-brand img {
            object-fit: contain;
        }

        .ohh-navbar .navbar-nav {
            gap: 4px;
        }

        .ohh-navbar .navbar-nav .nav-link {
            position: relative;
            padding: 10px 13px;
            border-radius: 9px;
            color: #43514b;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .ohh-navbar .navbar-nav .nav-link:hover {
            color: #0F6E56;
            background: #F4F1E9;
        }

        .ohh-navbar .navbar-nav .nav-link::after {
            display: none;
        }


        /* Profile */
        .ohh-navbar .dropdown > a {
            color: #18352D;
            padding: 5px 9px;
            border-radius: 12px;
            transition: 0.2s ease;
        }

        .ohh-navbar .dropdown > a:hover {
            background: #F5F1E8;
        }

        .user-profile-circle {
            background: #0F6E56 !important;
            box-shadow: 0 2px 7px rgba(15, 110, 86, 0.2);
        }


        /* Desktop dropdown */
        .ohh-navbar .dropdown-menu {
            margin-top: 12px;
            padding: 8px;
            border: 1px solid #E5DED0;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 18px 45px rgba(22, 36, 31, 0.12) !important;
        }

        .ohh-navbar .dropdown-item {
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 13px;
            color: #43514b;
            transition: 0.2s ease;
        }

        .ohh-navbar .dropdown-item:hover {
            background: #F5F1E8;
            color: #0F6E56;
            transform: none;
        }


        /* =========================================================
        MOBILE SIDEBAR
        ========================================================= */

        .ohh-mobile-sidebar {
            width: min(350px, 88vw);
            background: #fff;
            color: #18352D;
            border: 0;
            box-shadow: 10px 0 35px rgba(22, 36, 31, 0.12);
        }


        /* Sidebar header */
        .ohh-mobile-sidebar .offcanvas-header {
            position: relative;
            padding: 22px 20px;
            background: linear-gradient(135deg, #0B4A3B, #0F6E56) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .ohh-mobile-sidebar .offcanvas-header .btn-close {
            opacity: 0.9;
        }

        .ohh-mobile-sidebar .offcanvas-title {
            color: #fff !important;
        }


        /* Sidebar body */
        .ohh-mobile-sidebar .offcanvas-body {
            background: #fff;
            padding: 12px 12px 20px !important;
        }


        /* Section labels */
        .ohh-mobile-sidebar .nav-item > div {
            padding: 17px 12px 7px !important;
            color: #A9782E !important;
            font-size: 9px !important;
            font-weight: 700 !important;
            letter-spacing: 0.17em !important;
        }


        /* Links */
        .ohh-mobile-sidebar .nav-link {
            display: flex;
            align-items: center;
            min-height: 46px;
            margin: 2px 0;
            padding: 11px 13px;
            border-radius: 11px;
            color: #43514b !important;
            font-size: 14px;
            font-weight: 500;
            transition:
                background 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .ohh-mobile-sidebar .nav-link:hover {
            background: #F5F1E8;
            color: #0F6E56 !important;
            transform: translateX(2px);
        }


        /* Icons */
        .ohh-mobile-sidebar .nav-link i {
            flex-shrink: 0;
            font-size: 14px;
        }


        /* Account highlight */
        .ohh-mobile-sidebar
        .nav-link[href*="dashboard"] {
            background: #EEF6F1;
            color: #0F6E56 !important;
            font-weight: 650;
        }

        .ohh-mobile-sidebar
        .nav-link[href*="dashboard"]:hover {
            background: #E5F0EA;
        }


        /* Credits */
        .ohh-mobile-sidebar
        .nav-link[href*="credits"] {
            background: #FBF6E9;
        }

        .ohh-mobile-sidebar
        .nav-link[href*="credits"]:hover {
            background: #F7EFDD;
        }


        /* Logout */
        .ohh-mobile-sidebar
        .text-danger {
            color: #A94442 !important;
        }

        .ohh-mobile-sidebar
        .text-danger:hover {
            background: #FAEEEE;
            color: #963B39 !important;
        }


        /* Nested booking menu */
        .ohh-mobile-sidebar
        #bookingSubmenu {
            margin: 3px 0 8px;
            padding-left: 10px !important;
            border-left: 1px solid #E5DED0;
        }

        .ohh-mobile-sidebar
        #bookingSubmenu .nav-link {
            min-height: 42px;
            font-size: 13px;
        }


        /* Active nested route */
        .ohh-mobile-sidebar
        #bookingSubmenu .nav-link.active {
            background: #EEF6F1;
            color: #0F6E56 !important;
            font-weight: 650;
        }


        /* Sidebar divider */
        .ohh-mobile-sidebar
        .nav-item[style*="border-top"] {
            margin-top: 15px !important;
            padding-top: 13px !important;
            border-color: #ECE7DE !important;
        }


        /* =========================================================
        MOBILE NAVBAR TOGGLER
        ========================================================= */

        .ohh-navbar .navbar-toggler {
            width: 44px;
            height: 44px;
            padding: 0;
            border: 1px solid #E2DCCF;
            border-radius: 11px;
            background: #fff;
            box-shadow: 0 3px 10px rgba(22,36,31,0.04);
        }

        .ohh-navbar .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(15, 110, 86, 0.10);
        }

        .ohh-navbar .navbar-toggler-icon {
            width: 19px;
            height: 19px;
        }


        /* =========================================================
        MOBILE
        ========================================================= */

        @media (max-width: 991.98px) {

            .ohh-navbar {
                min-height: 68px;
                padding: 0.55rem 1rem;
            }

            .ohh-navbar .navbar-brand {
                margin-right: auto;
            }

            .ohh-navbar .navbar-brand img {
                max-height: 40px;
            }
        }
            </style>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @include('partials._pages')

    <footer class="footer ">
        <div class="bottom-footer text-center">
            <div class="container">
                <div class="bor_top">
                    <p>
                        <a href="/">Our House Help &copy; {{ date('Y') }}</a>
                        <span style="color: white; font-weight:inherit">Designed by Our House Help Services:
                            {{-- <a href="tel:+2347033700129">+2347033700129</a> --}}
                            <a href="{{ route('admin.register') }}">(Admin)</a>
                        </span> <a href="{{ route('our.terms.conditions') }}">
                            <p>Please read our Terms and Conditions before proceeding </p>
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Form Submission</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- @if (request()->routeIs('orderreceipt'))
                        @if (session('success'))
                            <div class="alert alert-success">

                                <p>Please proceed to make your payment to finalize the process.</p>
                                <p style="margin-bottom: 0px"><strong>Account Number:</strong> </p>
                                <h5>0051480410</h5>
                                <p style="margin-bottom: 0px"><strong>Account Name:</strong></p>
                                <h5> Pik and Crest Dynamic Concepts LTD</h5>
                                <p style="margin-bottom: 0px"><strong>Bank:</strong></p>
                                <h5> GT Bank</h5>
                                <p>Ensure to include your order ID <strong>{{ $order->id }}</strong> as the payment
                                    reference.</p>

                                <p>After making the payment, kindly send the payment confirmation or receipt to our
                                    customer
                                    service via
                                    email at <br><strong><a
                                            href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a></strong> or
                                    call
                                    <strong>
                                        <a href="tel:+2349167696525">+234 916 7696 525</a>,
                                        <a href="tel:+2347066663868">+234 706 6663 868</a>
                                    </strong>.
                                </p>
                            </div>
                        @endif
                    @endif --}}

                </div>
                <div class="modal-footer">
                    @if (request()->routeIs('orderreceipt'))
                        @php
                            // Decode serviceName if it's a JSON string
                            $services = is_string($order->serviceName)
                                ? json_decode($order->serviceName, true)
                                : $order->serviceName;

                            // Check if $services is an array, and convert it to a comma-separated string
                            $serviceNames = is_array($services) ? implode(', ', $services) : $services;
                        @endphp

                        <a href="https://api.whatsapp.com/send?phone=2349167696525&text={{ urlencode('I placed an order with Details: ID: ' . $order->id . ', Service: ' . $serviceNames . ', Grand Total: ₦' . number_format($order->discounted_cost, 2)) }}"
                            target="_blank" class="btn btn-success">
                            Share to our WhatsApp
                        </a>
                    @endif
                    @if (request()->routeIs('orderreceipt'))
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Vertically centered Modal-->


    <style>
        .content-container {
            min-height: 80vh;
        }
        .dropdown-item:hover {
            scale: 0.95;
             background-color: rgba(255, 255, 255, 0.1);
            
        }
    </style>
    
    <script>
        // Native Bootstrap 5 API. This previously used jQuery
        // ($('#feedbackModal').modal('show')), which Bootstrap 5 removed support
        // for, so the feedback modal never actually opened.
        @if (session('success') || $errors->any())
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('feedbackModal');
                if (el && window.bootstrap) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        @endif
    </script>

    <script>
        // Wait until the page has fully loaded before hiding the preloader
        window.addEventListener('load', function() {
            // Guarded: this layout has #preloader but no #main-content, so the
            // second line threw "Cannot read properties of null" on every page
            // using layouts.main.
            var preloader = document.getElementById('preloader');
            if (preloader) preloader.style.display = 'none';

            var main = document.getElementById('main-content');
            if (main) main.style.display = 'block';
        });
    </script>

    {{-- Bootstrap JS ships via @vite in the head partial. --}}
    <script src="{{ asset('build/assets/js/script.js') }}"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>
</body>

</html>