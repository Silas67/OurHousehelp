@include('partials.modals._ModalforLogout')

<div class="sidebar ohh-sidebar" id="sidebar">
    <div class="p-0 d-flex flex-column" style="height:100vh; overflow-y:auto;">

        {{-- ── SIDEBAR HEADER ── --}}
        <div class="ohh-sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="ohh-sidebar-avatar">
                    @if(Auth::user())
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div style="min-width:0;flex:1;">
                    @if(Auth::user())
                        <div class="ohh-sidebar-name text-truncate">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                        </div>
                        <div class="ohh-sidebar-email text-truncate">
                            {{ Auth::user()->email }}
                        </div>
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="ohh-sidebar-badge">Client</span>
                            <span class="ohh-sidebar-badge ohh-sidebar-badge-gold">
                                <i class="fas fa-coins"></i>
                                <span id="creditBalanceLive">{{ Auth::user()->getCreditBalance() }}</span> credits
                            </span>
                        </div>
                    @endif
                </div>
                <!-- <button id="close-sidebar" class="ohh-sidebar-close flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button> -->
            </div>
        </div>

        {{-- ── NAV LINKS ── --}}
        <ul class="nav flex-column ohh-sidebar-nav">

            {{-- Section: Main --}}
            <li class="nav-item">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Main
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="fas fa-house"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    Notifications
                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                        <span class="ohh-sidebar-count">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('booking.*') ? 'active' : '' }}" href="{{ route('booking.service-selection') }}">
                    <i class="fas fa-clipboard-list"></i>Book a Service
                </a>
            </li>

            {{-- Credits --}}
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('credits.*') ? 'active' : '' }}" href="{{ route('credits.index') }}">
                    <i class="fas fa-coins ohh-sidebar-icon-gold"></i>
                    My Credits
                    <span class="ohh-sidebar-count ohh-sidebar-count-gold">
                        {{ Auth::user()->getCreditBalance() }}
                    </span>
                </a>
            </li>

            {{-- Section: Live-Out --}}
            <li class="nav-item mt-2">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Live-Out
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link ohh-sidebar-link-parent" href="#liveoutSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-concierge-bell"></i>
                    Live-Out Househelp
                    <i class="fas fa-chevron-down ms-auto ohh-sidebar-chevron"></i>
                </a>
                <ul class="nav flex-column collapse ohh-sidebar-submenu" id="liveoutSubmenu"
                    @if(Route::is('premium') || Route::is('bookings') || Route::is('registerandpayment')) class="show" @endif>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('premium') ? 'active' : '' }}" href="{{ route('premium') }}">
                            <i class="fas fa-crown"></i>Live-Out Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('bookings') ? 'active' : '' }}" href="{{ route('bookings') }}">
                            <i class="fas fa-calendar-check"></i>Services Requested
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('registerandpayment') ? 'active' : '' }}" href="{{ route('registerandpayment') }}">
                            <i class="fas fa-receipt"></i>Register &amp; Payment
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Section: Live-In --}}
            <li class="nav-item mt-2">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Live-In
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link ohh-sidebar-link-parent" href="#liveinSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-users"></i>
                    Live-In Househelps
                    <i class="fas fa-chevron-down ms-auto ohh-sidebar-chevron"></i>
                </a>
                <ul class="nav flex-column collapse ohh-sidebar-submenu" id="liveinSubmenu"
                    @if(Route::is('househelps') || Route::is('liveinjobrequest') || Route::is('paymenthistory') || Route::is('payments.makepayment')) class="show" @endif>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('househelps') ? 'active' : '' }}" href="{{ route('househelps') }}">
                            <i class="fas fa-users"></i>Live-In Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('liveinjobrequest') ? 'active' : '' }}" href="{{ route('liveinjobrequest') }}">
                            <i class="fas fa-house-chimney"></i>Services Requested
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('paymenthistory') ? 'active' : '' }}" href="{{ route('paymenthistory') }}">
                            <i class="fas fa-receipt"></i>Payment History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="ohh-sidebar-link ohh-sidebar-sublink {{ Route::is('payments.makepayment') ? 'active' : '' }}" href="{{ route('payments.makepayment') }}">
                            <i class="fas fa-credit-card"></i>Make Payments
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Section: Account --}}
            <li class="nav-item mt-2">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Account
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('clientmessage') ? 'active' : '' }}" href="{{ route('clientmessage') }}">
                    <i class="fas fa-envelope"></i>
                    Messages
                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                        <span class="ohh-sidebar-count">{{ $unreadMessagesCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('clientprofile') ? 'active' : '' }}" href="{{ route('clientprofile') }}">
                    <i class="fas fa-user"></i>Profile
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item ohh-sidebar-logout-row">
                 <a class="ohh-sidebar-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="fas fa-sign-out-alt me-2"></i>Log Out
                 </a>
            </li>
            <form id="logout-form-nav" action="{{ route('logout') }}" method="POST">
                @csrf
            </form>

        </ul>
    </div>
</div>

<style>
    .ohh-sidebar {
        background: #FEFCF8;
    }

    /* HEADER - matches .ohh-dashboard-hero exactly */
    .ohh-sidebar-header {
        position: relative;
        overflow: hidden;
        padding: 22px 18px;
        background:
            radial-gradient(circle at 85% 20%, rgba(167, 120, 46, 0.22), transparent 45%),
            linear-gradient(135deg, #0B4A3B, #0F6E56);
    }

    .ohh-sidebar-header::after {
        content: "";
        position: absolute;
        width: 140px;
        height: 140px;
        right: -50px;
        bottom: -70px;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .ohh-sidebar-header > div {
        position: relative;
        z-index: 1;
    }

    .ohh-sidebar-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(156,227,203,0.4);
        color: #9ce3cb;
        font-weight: 700;
        font-size: 17px;
    }

    .ohh-sidebar-name {
        color: #fff;
        font-weight: 700;
        font-size: 14px;
    }

    .ohh-sidebar-email {
        color: rgba(255,255,255,0.62);
        font-size: 11px;
    }

    .ohh-sidebar-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        background: rgba(255,255,255,0.14);
        color: rgba(255,255,255,0.85);
    }

    .ohh-sidebar-badge-gold {
        background: rgba(169, 120, 46, 0.35);
        color: #F0DCB0;
    }

    .ohh-sidebar-badge-gold i {
        font-size: 9px;
    }

    .ohh-sidebar-close {
        border: 0;
        background: transparent;
        color: rgba(255,255,255,0.75);
        padding: 0;
    }

    .ohh-sidebar-close:hover {
        color: #fff;
    }

    /* NAV */
    .ohh-sidebar-nav {
        padding: 14px 10px;
    }

    .ohh-sidebar-kicker {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #A9782E;
        padding: 10px 10px 6px;
    }

    .ohh-sidebar-kicker-line {
        width: 16px;
        height: 1px;
        background: #A9782E;
        opacity: 0.5;
    }

    .ohh-sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        margin-bottom: 2px;
        border-radius: 9px;
        color: #3D4A44;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .ohh-sidebar-link i {
        width: 16px;
        font-size: 14px;
        color: #7B857F;
        transition: color 0.15s ease;
    }

    .ohh-sidebar-link:hover {
        background: #F1EFE7;
        color: #18352D;
        text-decoration: none;
    }

    .ohh-sidebar-link.active {
        background: #E6F0EB;
        color: #0B4A3B;
        font-weight: 700;
    }

    .ohh-sidebar-link.active i,
    .ohh-sidebar-link:hover i {
        color: #0F6E56;
    }

    .ohh-sidebar-icon-gold {
        color: #A9782E !important;
    }

    .ohh-sidebar-count {
        margin-left: auto;
        background: #C0392B;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 20px;
    }

    .ohh-sidebar-count-gold {
        background: #A9782E;
    }

    .ohh-sidebar-link-parent {
        cursor: pointer;
    }

    .ohh-sidebar-chevron {
        font-size: 10px;
        color: #A6ADA8;
        transition: transform 0.2s ease;
    }

    .ohh-sidebar-link-parent[aria-expanded="true"] .ohh-sidebar-chevron,
    .ohh-sidebar-link-parent:not(.collapsed) .ohh-sidebar-chevron {
        transform: rotate(180deg);
    }

    .ohh-sidebar-submenu {
        padding-left: 14px;
    }

    .ohh-sidebar-sublink {
        font-size: 13px;
        padding-top: 7px;
        padding-bottom: 7px;
    }

    .ohh-sidebar-logout-row {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #EDE7D8;
    }

    .ohh-sidebar-logout {
        color: #B3554A;
        text-align: left;
    }

    .ohh-sidebar-logout i {
        color: #B3554A;
    }

    .ohh-sidebar-logout:hover {
        background: #FBEEEC;
        color: #97392F;
    }

    .ohh-sidebar-logout:hover i {
        color: #97392F;
    }

    @media (min-width: 768px) and (max-width: 1100px) {
        .thiscard {
            width: 50%;
        }

        .thiscard .card {
            min-height: 240px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-open submenu for active route
    var activeLink = document.querySelector('.ohh-sidebar-link.active');
    if (activeLink) {
        var submenu = activeLink.closest('.collapse');
        if (submenu) {
            submenu.classList.add('show');
        }
        
    }
});
</script>