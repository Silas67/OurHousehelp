<div class="sidebar" id="sidebar">
    <div class="p-0">

        {{-- ── SIDEBAR HEADER ── --}}
        <div class="p-3" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:46px;height:46px;background:rgba(255,255,255,0.2);color:white;font-weight:700;font-size:18px;border:2px solid rgba(255,255,255,0.35);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div style="min-width:0;flex:1;">
                    <div class="fw-bold text-white text-truncate" style="font-size:14px;">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-truncate" style="font-size:11px;color:rgba(255,255,255,0.7);">
                        {{ Auth::user()->email }}
                    </div>
                    <div class="mt-1">
                        @if(Auth::user()->user_role == 2)
                            <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Super Admin</span>
                        @else
                            <span class="badge" style="background:rgba(255,255,255,0.25);font-size:10px;">Admin</span>
                        @endif
                    </div>
                </div>
                <button id="close-sidebar" class="btn btn-link text-white p-0 flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- ── NAV LINKS ── --}}
        <ul class="nav flex-column p-2">

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="/">
                    <i class="fas fa-house" style="margin-right:10px;width:16px;"></i>Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admindashboard') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('admindashboard') }}">
                    <i class="fas fa-tachometer-alt" style="margin-right:10px;width:16px;"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.cost-calculator') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('admin.cost-calculator') }}">
                    <i class="fas fa-calculator" style="margin-right:10px;width:16px;"></i>Calculator
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('contactmessage') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('contactmessage') }}">
                    <i class="fas fa-envelope" style="margin-right:10px;width:16px;"></i>Messages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.bookings') || Route::is('admin.booking.*') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('admin.bookings') }}">
                    <i class="fas fa-clipboard-list" style="margin-right:10px;width:16px;"></i>Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.locations') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('admin.locations') }}">
                    <i class="fas fa-map-marker-alt" style="margin-right:10px;width:16px;"></i>Locations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.replacements') ? 'active' : '' }} d-flex align-items-center"
                    href="{{ route('admin.replacements') }}">
                    <i class="fas fa-exchange-alt" style="margin-right:10px;width:16px;"></i>Replacements
                </a>
            </li>

            {{-- Orders --}}
            <li class="nav-item {{ Route::is('pendingOrder') || Route::is('confirmedOrder') || Route::is('activeOrder') || Route::is('allOrders') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#ordersSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-box" style="margin-right:10px;width:16px;"></i>Orders
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="ordersSubmenu"
                    @if(Route::is('pendingOrder') || Route::is('confirmedOrder') || Route::is('activeOrder') || Route::is('allOrders')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('pendingOrder') ? 'active' : '' }} d-flex align-items-center" href="{{ route('pendingOrder') }}">
                            <i class="fas fa-hourglass-half" style="margin-right:10px;width:16px;"></i>Pending Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('confirmedOrder') ? 'active' : '' }} d-flex align-items-center" href="{{ route('confirmedOrder') }}">
                            <i class="fas fa-check-circle" style="margin-right:10px;width:16px;"></i>Confirmed Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('activeOrder') ? 'active' : '' }} d-flex align-items-center" href="{{ route('activeOrder') }}">
                            <i class="fas fa-check-double" style="margin-right:10px;width:16px;"></i>Active Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('allOrders') ? 'active' : '' }} d-flex align-items-center" href="{{ route('allOrders') }}">
                            <i class="fas fa-th-list" style="margin-right:10px;width:16px;"></i>All Orders
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Packages --}}
            <li class="nav-item {{ Route::is('newpackage') || Route::is('allpackages') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#packagesSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-archive" style="margin-right:10px;width:16px;"></i>Packages
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="packagesSubmenu"
                    @if(Route::is('newpackage') || Route::is('allpackages')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('newpackage') ? 'active' : '' }} d-flex align-items-center" href="{{ route('newpackage') }}">
                            <i class="fas fa-plus" style="margin-right:10px;width:16px;"></i>Add Package
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('allpackages') ? 'active' : '' }} d-flex align-items-center" href="{{ route('allpackages') }}">
                            <i class="fas fa-list" style="margin-right:10px;width:16px;"></i>Manage Packages
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Services --}}
            <li class="nav-item {{ Route::is('admin.services.create') || Route::is('admin.allservices') || Route::is('admin.skill.create') || Route::is('admin.allskill') || Route::is('newdiscount') || Route::is('discount') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#servicesSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-concierge-bell" style="margin-right:10px;width:16px;"></i>Services
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="servicesSubmenu"
                    @if(Route::is('admin.services.create') || Route::is('admin.allservices') || Route::is('admin.skill.create') || Route::is('admin.allskill') || Route::is('newdiscount') || Route::is('discount')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.services.create') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.services.create') }}">
                            <i class="fas fa-plus" style="margin-right:10px;width:16px;"></i>Create Help Service
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.allservices') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.allservices') }}">
                            <i class="fas fa-th-list" style="margin-right:10px;width:16px;"></i>Manage Help Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.service_charge.index') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.service_charge.index') }}">
                            <i class="fas fa-list" style="margin-right:10px;width:16px;"></i>Service Charge
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.extra-service-cost.index') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.extra-service-cost.index') }}">
                            <i class="fas fa-list" style="margin-right:10px;width:16px;"></i>Extra Service
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.skill.create') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.skill.create') }}">
                            <i class="fas fa-plus" style="margin-right:10px;width:16px;"></i>Create Skill
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.allskill') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.allskill') }}">
                            <i class="fas fa-th-list" style="margin-right:10px;width:16px;"></i>Manage Skills
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('newdiscount') ? 'active' : '' }} d-flex align-items-center" href="{{ route('newdiscount') }}">
                            <i class="fas fa-plus" style="margin-right:10px;width:16px;"></i>Add Discount
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('discount') ? 'active' : '' }} d-flex align-items-center" href="{{ route('discount') }}">
                            <i class="fas fa-list" style="margin-right:10px;width:16px;"></i>Manage Discounts
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Apartments --}}
            <li class="nav-item {{ Route::is('newapartments') || Route::is('apartments') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#apartmentsSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-house" style="margin-right:10px;width:16px;"></i>Apartments
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="apartmentsSubmenu"
                    @if(Route::is('newapartments') || Route::is('apartments')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('newapartments') ? 'active' : '' }} d-flex align-items-center" href="{{ route('newapartments') }}">
                            <i class="fas fa-plus" style="margin-right:10px;width:16px;"></i>Add Apartment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('apartments') ? 'active' : '' }} d-flex align-items-center" href="{{ route('apartments') }}">
                            <i class="fas fa-list" style="margin-right:10px;width:16px;"></i>Manage Apartments
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Users --}}
            <li class="nav-item {{ Route::is('adminapplicants') || Route::is('adminskilledworkers') || Route::is('adminclients') || Route::is('adminstaffs') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#usersSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-user-group" style="margin-right:10px;width:16px;"></i>Users
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="usersSubmenu"
                    @if(Route::is('adminapplicants') || Route::is('adminskilledworkers') || Route::is('adminclients') || Route::is('adminstaffs')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('adminapplicants') ? 'active' : '' }} d-flex align-items-center" href="{{ route('adminapplicants') }}">
                            <i class="fas fa-broom" style="margin-right:10px;width:16px;"></i>House Helps
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('adminskilledworkers') ? 'active' : '' }} d-flex align-items-center" href="{{ route('adminskilledworkers') }}">
                            <i class="fas fa-toolbox" style="margin-right:10px;width:16px;"></i>Skilled Workers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('adminclients') ? 'active' : '' }} d-flex align-items-center" href="{{ route('adminclients') }}">
                            <i class="fas fa-user-tie" style="margin-right:10px;width:16px;"></i>Clients
                        </a>
                    </li>
                    @if(Auth::user()->user_role == 2)
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('adminstaffs') ? 'active' : '' }} d-flex align-items-center" href="{{ route('adminstaffs') }}">
                            <i class="fas fa-user-cog" style="margin-right:10px;width:16px;"></i>Staffs
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            {{-- Financials --}}
            <li class="nav-item {{ Route::is('payments.allpayments') || Route::is('payments.paymentplan') || Route::is('payments.allpaymentplan') || Route::is('admin.credits') ? 'active-parent' : '' }}">
                <a class="nav-link d-flex align-items-center" href="#financialsSubmenu" data-bs-toggle="collapse">
                    <i class="fas fa-chart-line" style="margin-right:10px;width:16px;"></i>Financials
                    <i class="fas fa-chevron-down ms-auto" style="font-size:11px;"></i>
                </a>
                <ul class="nav flex-column collapse ps-3" id="financialsSubmenu"
                    @if(Route::is('payments.allpayments') || Route::is('payments.paymentplan') || Route::is('payments.allpaymentplan') || Route::is('admin.credits') || Route::is('admin.pricing')) class="show" @endif>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('payments.allpayments') ? 'active' : '' }} d-flex align-items-center" href="{{ route('payments.allpayments') }}">
                            <i class="fas fa-money-bill-wave" style="margin-right:10px;width:16px;"></i>All Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.payments.registrationPayments') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.payments.registrationPayments') }}">
                            <i class="fas fa-money-bill-wave" style="margin-right:10px;width:16px;"></i>Registration Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('payments.paymentplan') ? 'active' : '' }} d-flex align-items-center" href="{{ route('payments.paymentplan') }}">
                            <i class="fas fa-money-bill-wave" style="margin-right:10px;width:16px;"></i>Payment Plans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('payments.allpaymentplan') ? 'active' : '' }} d-flex align-items-center" href="{{ route('payments.allpaymentplan') }}">
                            <i class="fas fa-money-bill-wave" style="margin-right:10px;width:16px;"></i>Payment Plan Update
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.pricing') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.pricing') }}">
                            <i class="fas fa-money-bill-wave" style="margin-right:10px;width:16px;"></i>Management Plans
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.credits') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.credits') }}">
                            <i class="fas fa-coins" style="margin-right:10px;width:16px;color:#BA7517;"></i>Credit Management
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.faqs.index') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.faqs.index') }}">
                    <i class="fas fa-question-circle" style="margin-right:10px;width:16px;"></i>FAQ Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('notifications.index') ? 'active' : '' }} d-flex align-items-center" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell" style="margin-right:10px;width:16px;"></i>
                    Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.settings') ? 'active' : '' }} d-flex align-items-center" href="{{ route('admin.settings') }}">
                    <i class="fas fa-cog" style="margin-right:10px;width:16px;"></i>Settings
                </a>
            </li>

            {{-- Logout --}}
            <li class="nav-item mt-2" style="border-top:1px solid rgba(255,255,255,0.1);padding-top:8px;">
                <a class="nav-link text-danger d-flex align-items-center" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt" style="margin-right:10px;width:16px;"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>

        </ul>
    </div>
</div>

<style>
#sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    overflow-y: auto;
    overflow-x: hidden;
    background-color: #343a40;
    z-index: 1050;
    transition: transform 0.3s ease;
    -webkit-overflow-scrolling: touch;
}

/* Hide sidebar off-screen on mobile by default */
@media (max-width: 768px) {
    #sidebar {
        transform: translateX(-100%);
    }
    #sidebar.open {
        transform: translateX(0);
    }
}

/* Scrollbar styling */
#sidebar::-webkit-scrollbar { width: 5px; }
#sidebar::-webkit-scrollbar-track { background: #343a40; }
#sidebar::-webkit-scrollbar-thumb { background: #555; border-radius: 10px; }
#sidebar::-webkit-scrollbar-thumb:hover { background: #888; }

/* Nav link colors */
.nav-link { color: #fff !important; }
.nav-link:hover { background-color: #495057; border-radius: 6px; }
.active-parent > .nav-link { background-color: #0F6E56; border-radius: 6px; }
.nav-link.active { background-color: #0a5240; border-radius: 6px; }

/* Overlay when sidebar open on mobile */
#sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1049;
}
#sidebar-overlay.show { display: block; }
</style>

{{-- Overlay to catch clicks outside sidebar --}}
<div id="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const closeSidebar = document.getElementById('close-sidebar');
    const burgerIcon = document.getElementById('burger-icon');

    // Open sidebar
    if (burgerIcon) {
        burgerIcon.addEventListener('click', function() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // ← prevent body scroll
        });
    }

    // Close sidebar via X button
    if (closeSidebar) {
        closeSidebar.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = ''; // ← restore body scroll
        });
    }

    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    // Auto-open active submenu
    document.querySelectorAll('.nav-link.active').forEach(function(activeLink) {
        var submenu = activeLink.closest('.collapse');
        if (submenu) submenu.classList.add('show');
    });
});
</script>