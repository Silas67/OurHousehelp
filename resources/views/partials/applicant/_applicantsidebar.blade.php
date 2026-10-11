@include('partials.modals._ModalforLogout')

@php
    $applicantUser = Auth::user();
    $isApprovedStaff = $applicantUser->approval_status === 'approved';
    $pendingOffers = \App\Models\StaffShortlist::where('staff_id', Auth::id())
        ->where('status', 'notified')
        ->count();
@endphp

<div class="sidebar ohh-sidebar" id="sidebar">
    <div class="p-0 d-flex flex-column" style="height:100vh; overflow-y:auto;">

        {{-- ── SIDEBAR HEADER ── --}}
        <div class="ohh-sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="ohh-sidebar-avatar">
                    {{ strtoupper(substr($applicantUser->name, 0, 1)) }}
                </div>
                <div style="min-width:0;flex:1;">
                    <div class="ohh-sidebar-name text-truncate">
                        {{ $applicantUser->name }} {{ $applicantUser->last_name }}
                    </div>
                    <div class="ohh-sidebar-email text-truncate">
                        {{ $applicantUser->email }}
                    </div>
                    <div class="mt-2 d-flex align-items-center flex-wrap gap-2">
                        @if($isApprovedStaff)
                            <span class="ohh-sidebar-badge ohh-sidebar-badge-approved">
                                <i class="fas fa-check-circle"></i>Staff
                            </span>
                        @else
                            <span class="ohh-sidebar-badge ohh-sidebar-badge-pending">
                                <i class="fas fa-clock"></i>Applicant
                            </span>
                        @endif

                        @if($applicantUser->availability)
                            <span class="ohh-sidebar-badge">
                                <i class="fas fa-circle ohh-dot-on"></i>Available
                            </span>
                        @else
                            <span class="ohh-sidebar-badge ohh-sidebar-badge-muted">
                                <i class="fas fa-circle ohh-dot-off"></i>Unavailable
                            </span>
                        @endif
                    </div>
                </div>
                <button id="close-sidebar" class="ohh-sidebar-close flex-shrink-0" type="button" aria-label="Close sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Push Notification Toggle --}}
            <div class="ohh-sidebar-push">
                <label class="mb-0 fw-semibold" for="push-toggle-radio">
                    <i class="fas fa-bell"></i> Job Notifications
                </label>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="push-toggle-radio">
                </div>
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
                <a class="ohh-sidebar-link" href="/">
                    <i class="fas fa-house"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicantdashboard') ? 'active' : '' }}"
                    href="{{ route('applicantdashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('notifications.index') ? 'active' : '' }}"
                    href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    Notifications
                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                        <span class="ohh-sidebar-count">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
            </li>

            {{-- Section: Jobs --}}
            <li class="nav-item mt-2">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Jobs
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicant.shortlistOffers') ? 'active' : '' }}"
                    href="{{ route('applicant.shortlistOffers') }}">
                    <i class="fas fa-star ohh-sidebar-icon-gold"></i>
                    Shortlist Offers
                    @if($pendingOffers > 0)
                        <span class="ohh-sidebar-count">{{ $pendingOffers }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicant.positions') ? 'active' : '' }}"
                    href="{{ route('applicant.positions') }}">
                    <i class="fas fa-briefcase"></i>Job Positions
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicant.liveOutServiceRequests') ? 'active' : '' }}"
                    href="{{ route('applicant.liveOutServiceRequests') }}">
                    <i class="fas fa-clipboard-list"></i>Live-Out Requests
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicant.specialDuties') ? 'active' : '' }}"
                    href="{{ route('applicant.specialDuties') }}">
                    <i class="fas fa-tasks"></i>Special Duties
                </a>
            </li>

            {{-- Section: Account --}}
            <li class="nav-item mt-2">
                <div class="ohh-sidebar-kicker">
                    <span class="ohh-sidebar-kicker-line"></span>
                    Account
                </div>
            </li>

            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicantprofile') ? 'active' : '' }}"
                    href="{{ route('applicantprofile', ['id' => $applicantUser->id]) }}">
                    <i class="fas fa-user"></i>My Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="ohh-sidebar-link {{ Route::is('applicantterms') ? 'active' : '' }}"
                    href="{{ route('applicantterms') }}">
                    <i class="fas fa-book"></i>Terms &amp; Conditions
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item ohh-sidebar-logout-row">
                <a class="ohh-sidebar-link ohh-sidebar-logout" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="fas fa-sign-out-alt"></i>Log Out
                </a>
            </li>
            <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </ul>
    </div>
</div>

<style>
    /*
     * Applicant theme colours — change these three to retheme the sidebar
     * (e.g. swap to the client green: #0F6E56 / #0B4A3B / #9ce3cb).
     */
    .ohh-sidebar {
        --app-primary: #4F2D9B;
        --app-primary-dark: #2E1A63;
        --app-accent: #C9B3FA;
        --app-tint: #EFEAFB;
        background: #FDFBFF;
    }

    /* HEADER */
    .ohh-sidebar-header {
        position: relative;
        overflow: hidden;
        padding: 22px 18px 16px;
        background:
            radial-gradient(circle at 85% 20%, rgba(201, 179, 250, 0.25), transparent 45%),
            linear-gradient(135deg, var(--app-primary-dark), var(--app-primary));
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
        pointer-events: none;
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
        border: 1px solid rgba(201,179,250,0.45);
        color: var(--app-accent);
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
        color: rgba(255,255,255,0.88);
    }

    .ohh-sidebar-badge i {
        font-size: 9px;
        color: inherit;
    }

    .ohh-sidebar-badge-approved {
        background: rgba(56, 142, 255, 0.35);
        color: #D6E8FF;
    }

    .ohh-sidebar-badge-pending {
        background: rgba(186, 117, 23, 0.4);
        color: #F6DDB0;
    }

    .ohh-sidebar-badge-muted {
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.5);
    }

    .ohh-sidebar-badge i.ohh-dot-on  { font-size: 7px; color: #9FE1CB; }
    .ohh-sidebar-badge i.ohh-dot-off { font-size: 7px; color: rgba(255,255,255,0.45); }

    .ohh-sidebar-close {
        border: 0;
        background: transparent;
        color: rgba(255,255,255,0.75);
        padding: 0;
    }

    .ohh-sidebar-close i { color: inherit; }
    .ohh-sidebar-close:hover { color: #fff; }

    /* Push notification toggle (inside header) */
    .ohh-sidebar-push {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 16px;
        padding: 9px 12px;
        border-radius: 10px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.12);
    }

    .ohh-sidebar-push label {
        color: #fff;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ohh-sidebar-push label i {
        color: var(--app-accent);
        font-size: 12px;
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
        background: #F3F0FA;
        color: var(--app-primary-dark);
        text-decoration: none;
    }

    .ohh-sidebar-link.active {
        background: var(--app-tint);
        color: var(--app-primary-dark);
        font-weight: 700;
    }

    .ohh-sidebar-link.active i,
    .ohh-sidebar-link:hover i {
        color: var(--app-primary);
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

    .ohh-sidebar-logout-row {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #EAE6F2;
    }

    .ohh-sidebar-logout,
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
</style>

<script src="{{ asset('js/push-notifications.js') }}"></script>