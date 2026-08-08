<div class="sidebar" id="sidebar">
    <div class="p-0 d-flex flex-column" style="height:100vh; overflow-y:auto;">

        {{-- ── SIDEBAR HEADER ── --}}
        <div class="p-3" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:46px;height:46px;background:rgba(255,255,255,0.2);color:white;font-weight:700;font-size:18px;border:2px solid rgba(255,255,255,0.35);">
                    @if(Auth::user())
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div style="min-width:0;flex:1;">
                    @if(Auth::user())
                        <div class="fw-bold text-white text-truncate" style="font-size:14px;">
                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                        </div>
                        <div class="text-truncate" style="font-size:11px;color:rgba(255,255,255,0.7);">
                            {{ Auth::user()->email }}
                        </div>
                        <div class="mt-1 d-flex align-items-center gap-2">
                            <span class="badge" style="background:rgba(255,255,255,0.2);font-size:10px;">
                                Client
                            </span>
                            <span class="badge d-flex align-items-center gap-1" style="background:rgba(186,117,23,0.8);font-size:10px;">
                                <i class="fas fa-coins" style="font-size:9px;"></i>
                                <span id="creditBalanceLive">{{ Auth::user()->getCreditBalance() }}</span> credits
                            </span>
                        </div>
                    @endif
                </div>
                <button id="close-sidebar" class="btn btn-link text-white p-0 flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- ── NAV LINKS ── --}}
        <ul class="nav flex-column p-2">

            {{-- Section: Main --}}
            <li class="nav-item px-2 py-1">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Main</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('get-started') ? 'active' : '' }} d-flex align-items-center" href="{{ route('get-started') }}">
                    <i class="fas fa-house" style="margin-right:10px;width:16px;"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }} d-flex align-items-center" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt" style="margin-right:10px;width:16px;"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('notifications.index') ? 'active' : '' }} d-flex align-items-center" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell" style="margin-right:10px;width:16px;"></i>
                    Notifications
                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('booking.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('booking.service-selection') }}">
                    <i class="fas fa-clipboard-list" style="margin-right:10px;width:16px;"></i>Book a Service
                </a>
            </li>

            {{-- Credits --}}
            <li class="nav-item">
                <a class="nav-link {{ Route::is('credits.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('credits.index') }}">
                    <i class="fas fa-coins" style="margin-right:10px;width:16px;color:#BA7517;"></i>
                    My Credits
                    <span class="badge ms-auto" style="background:#BA7517;">
                        {{ Auth::user()->getCreditBalance() }}
                    </span>
                </a>
            </li>

            {{-- Section: Live-Out --}}
            <li class="nav-item px-2 py-1 mt-2">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Live-Out</div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="#liveoutSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-concierge-bell" style="margin-right:10px;width:16px;"></i>
                    Live-Out Househelp
                    <i class="fas fa-chevron-down ms-auto submenu-icon" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="liveoutSubmenu"
                    @if(Route::is('premium') || Route::is('bookings') || Route::is('registerandpayment')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('premium') ? 'active' : '' }} d-flex align-items-center" href="{{ route('premium') }}">
                            <i class="fas fa-crown" style="margin-right:10px;width:16px;"></i>Live-Out Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('bookings') ? 'active' : '' }} d-flex align-items-center" href="{{ route('bookings') }}">
                            <i class="fas fa-calendar-check" style="margin-right:10px;width:16px;"></i>Services Requested
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('registerandpayment') ? 'active' : '' }} d-flex align-items-center" href="{{ route('registerandpayment') }}">
                            <i class="fas fa-receipt" style="margin-right:10px;width:16px;"></i>Register & Payment
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Section: Live-In --}}
            <li class="nav-item px-2 py-1 mt-2">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Live-In</div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="#liveinSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-users" style="margin-right:10px;width:16px;"></i>
                    Live-In Househelps
                    <i class="fas fa-chevron-down ms-auto submenu-icon" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="liveinSubmenu"
                    @if(Route::is('househelps') || Route::is('liveinjobrequest') || Route::is('paymenthistory') || Route::is('payments.makepayment')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('househelps') ? 'active' : '' }} d-flex align-items-center" href="{{ route('househelps') }}">
                            <i class="fas fa-users" style="margin-right:10px;width:16px;"></i>Live-In Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('liveinjobrequest') ? 'active' : '' }} d-flex align-items-center" href="{{ route('liveinjobrequest') }}">
                            <i class="fas fa-house-chimney" style="margin-right:10px;width:16px;"></i>Services Requested
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('paymenthistory') ? 'active' : '' }} d-flex align-items-center" href="{{ route('paymenthistory') }}">
                            <i class="fas fa-receipt" style="margin-right:10px;width:16px;"></i>Payment History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('payments.makepayment') ? 'active' : '' }} d-flex align-items-center" href="{{ route('payments.makepayment') }}">
                            <i class="fas fa-credit-card" style="margin-right:10px;width:16px;"></i>Make Payments
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Section: Account --}}
            <li class="nav-item px-2 py-1 mt-2">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Account</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('clientmessage') ? 'active' : '' }} d-flex align-items-center" href="{{ route('clientmessage') }}">
                    <i class="fas fa-envelope" style="margin-right:10px;width:16px;"></i>
                    Messages
                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadMessagesCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('clientprofile') ? 'active' : '' }} d-flex align-items-center" href="{{ route('clientprofile') }}">
                    <i class="fas fa-user" style="margin-right:10px;width:16px;"></i>Profile
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item mt-2" style="border-top:1px solid rgba(255,255,255,0.1);padding-top:8px;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 d-flex align-items-center border-0 bg-transparent text-danger">
                        <i class="fas fa-sign-out-alt" style="margin-right:10px;width:16px;"></i>Logout
                    </button>
                </form>
            </li>

        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-open submenu for active route
    var activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        var submenu = activeLink.closest('.collapse');
        if (submenu) {
            submenu.classList.add('show');
        }
    }
});
</script>