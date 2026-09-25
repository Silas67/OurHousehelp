@include('partials.modals._ModalforLogout')

<div class="sidebar" id="sidebar">
    <div class="p-0 d-flex flex-column" style="height:100vh; overflow-y:auto;">

        {{-- ── SIDEBAR HEADER ── --}}
        <div class="p-3" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:46px;height:46px;background:rgba(255,255,255,0.2);color:white;font-weight:700;font-size:18px;border:2px solid rgba(255,255,255,0.35);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div style="min-width:0;flex:1;">
                    <div class="fw-bold text-white text-truncate" style="font-size:14px;">
                        {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                    </div>
                    <div class="text-truncate" style="font-size:11px;color:rgba(255,255,255,0.7);">
                        {{ Auth::user()->email }}
                    </div>
                    <div class="mt-1">
                        @if(Auth::user()->approval_status === 'approved')
                            <span class="badge" style="background:rgba(10,88,202,0.8);font-size:10px;">
                                <i class="fas fa-check-circle me-1" style="font-size:9px;"></i>Staff
                            </span>
                        @else
                            <span class="badge" style="background:rgba(186,117,23,0.8);font-size:10px;">
                                <i class="fas fa-clock me-1" style="font-size:9px;"></i>Applicant
                            </span>
                        @endif
                        @if(Auth::user()->availability)
                            <span class="badge ms-1" style="background:rgba(255,255,255,0.2);font-size:10px;">
                                <i class="fas fa-circle me-1" style="font-size:7px;color:#9FE1CB;"></i>Available
                            </span>
                        @else
                            <span class="badge ms-1" style="background:rgba(255,255,255,0.1);font-size:10px;color:rgba(255,255,255,0.5);">
                                <i class="fas fa-circle me-1" style="font-size:7px;"></i>Unavailable
                            </span>
                        @endif
                    </div>
                </div>
                <button id="close-sidebar" class="btn btn-link text-white p-0 flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Push Notification Toggle --}}
            <div class="px-3 py-2">
                <div class="d-flex align-items-center justify-content-between bg-transparent rounded p-2">
                    <label class="mb-0 small fw-semibold text-white" for="push-toggle-radio">
                        🔔 Job Notifications
                    </label>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="push-toggle-radio">
                    </div>
                </div>
            </div>
        </div>

        

        {{-- ── NAV LINKS ── --}}
        <ul class="nav flex-column p-2">

            {{-- Section: Main --}}
            <li class="nav-item px-2 py-1">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Main</div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="/">
                    <i class="fas fa-house" style="margin-right:10px;width:16px;"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicantdashboard') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicantdashboard') }}">
                    <i class="fas fa-tachometer-alt" style="margin-right:10px;width:16px;"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('notifications.index') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell" style="margin-right:10px;width:16px;"></i>
                    Notifications
                    @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
            </li>

            {{-- Section: Jobs --}}
            <li class="nav-item px-2 py-1 mt-2">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Jobs</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicant.shortlistOffers') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicant.shortlistOffers') }}">
                    <i class="fas fa-star" style="margin-right:10px;width:16px;color:#BA7517;"></i>
                    Shortlist Offers
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
            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicant.positions') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicant.positions') }}">
                    <i class="fas fa-briefcase" style="margin-right:10px;width:16px;"></i>
                    Job Positions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicant.liveOutServiceRequests') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicant.liveOutServiceRequests') }}">
                    <i class="fas fa-clipboard-list" style="margin-right:10px;width:16px;"></i>
                    Live-Out Requests
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicant.specialDuties') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicant.specialDuties') }}">
                    <i class="fas fa-tasks" style="margin-right:10px;width:16px;"></i>
                    Special Duties
                </a>
            </li>

            {{-- Section: Account --}}
            <li class="nav-item px-2 py-1 mt-2">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.08em;color:#adb5bd;font-weight:600;">Account</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicantprofile') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicantprofile', ['id' => Auth::user()->id]) }}">
                    <i class="fas fa-user" style="margin-right:10px;width:16px;"></i>My Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('applicantterms') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('applicantterms') }}">
                    <i class="fas fa-book" style="margin-right:10px;width:16px;"></i>Terms & Conditions
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item mt-2" style="border-top:1px solid rgba(255,255,255,0.1);padding-top:8px;">
                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                     <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                        <i class="fas fa-sign-out-alt me-2"></i>Log Out
                    </a>
                </form>
            </li>

        </ul>
    </div>
</div>

<script src="{{ asset('js/push-notifications.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        var submenu = activeLink.closest('.collapse');
        if (submenu) submenu.classList.add('show');
    }
});
</script>