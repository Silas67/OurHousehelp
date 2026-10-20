
@php
    $isSkilledWorkerPage = Route::currentRouteName() === 'ourSkilledWorkerServices';
    $serviceTitle = $isSkilledWorkerPage ? 'Skilled Workers' : 'Live-In Staff';
    $hasActiveSubscription = Auth::check()
        && isset($hasApprovedPayment)
        && $hasApprovedPayment;
@endphp

<div class="staff-directory">

    {{-- Search Introduction --}}
    <!-- <div class="staff-search-intro">
        <div class="staff-search-icon">
            <i class="fas {{ $isSkilledWorkerPage ? 'fa-tools' : 'fa-user-tie' }}"></i>
        </div>

        <div>
            <span class="staff-search-hint">
                <i class="fas fa-lightbulb me-1"></i>
                You can search again to discover a different set of up to five workers.
            </span>
        </div>
    </div> -->

    {{-- Subscription Information --}}
   
    @if (!$isSkilledWorkerPage)
        <div class="staff-subscription-notice" role="note">
            <i class="fas fa-info-circle staff-subscription-notice-icon"></i>

            <div class="staff-subscription-notice-content">
                <strong>Live-In Staff Subscription</strong>

                <p>
                    A subscription purchased through this section applies only
                    to Live-In Staff services. Other services require their
                    applicable subscription.
                </p>

                {{-- Subscription status becomes part of this warning --}}
                @if (Auth::check())
                    <div class="staff-subscription-status">
                        @include('partials._subscribe')
                    </div>
                @endif
            </div>
        </div>
    @endif


    {{-- Existing Subscription Component --}}
    

    {{-- Search Filters --}}
    <div class="staff-filter-panel">
        <div class="staff-filter-heading">
            <h5><i class="fas fa-sliders-h me-2"></i>Refine Your Search</h5>
            <span>All filters are optional</span>
        </div>

        @if ($isSkilledWorkerPage)
            <form method="GET" action="{{ route('ourSkilledWorkerServices') }}"
                  class="staff-search-form">

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="staff-specialties">Skill or Specialty</label>
                        <select name="specialties" id="staff-specialties" class="form-select">
                            <option value="">All Skills</option>
                            @foreach ($skills as $skill)
                                <option value="{{ $skill->skill_name }}"
                                    {{ request('specialties') == $skill->skill_name ? 'selected' : '' }}>
                                    {{ $skill->skill_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="staff-state">State of Residence</label>
                        <select name="state_of_residence" id="staff-state" class="form-select">
                            <option value="">All States</option>
                            @foreach ($states as $state)
                                <option value="{{ $state }}"
                                    {{ strtolower(request('state_of_residence', '')) === strtolower($state) ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="staff-lga">LGA</label>
                        <input type="text"
                               name="lga_of_origin"
                               id="staff-lga"
                               class="form-control"
                               placeholder="Enter LGA"
                               value="{{ request('lga_of_origin') }}">
                    </div>

                    <div class="col-6 col-md-6">
                        <button type="submit" class="staff-search-btn w-100">
                            <i class="fas fa-search me-2"></i>
                            Search
                            @if ($hasActiveSubscription && isset($remainingSearches) && $remainingSearches > 0)
                                <span class="staff-search-count">
                                    {{ $remainingSearches }} left
                                </span>
                            @endif
                        </button>
                    </div>

                    <div class="col-6 col-md-6">
                        <a href="{{ route('ourSkilledWorkerServices') }}"
                           class="staff-clear-btn w-100">
                            <i class="fas fa-undo me-2"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        @else
            <form method="GET"
                  action="{{ request()->routeIs('househelps') ? route('househelps') : route('ourLiveInServices') }}"
                  class="staff-search-form">

                <div class="row g-3">
                    <div class="col-12 col-md-5">
                        <label for="livein-gender">Gender</label>
                        <select name="gender" id="livein-gender" class="form-select">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>
                                Male
                            </option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>
                                Female
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <label for="livein-state">State of Residence</label>
                        <select name="state_of_residence" id="livein-state" class="form-select">
                            <option value="">All States</option>
                            @foreach ($states as $state)
                                <option value="{{ $state }}"
                                    {{ strtolower(request('state_of_residence', '')) === strtolower($state) ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-1 staff-filter-action">
                        <button type="submit" class="staff-search-btn w-100" aria-label="Search">
                            <i class="fas fa-search"></i>
                            <span class="d-md-none ms-1">Search</span>
                        </button>
                    </div>

                    <div class="col-6 col-md-1 staff-filter-action">
                        <a href="{{ request()->routeIs('househelps') ? route('househelps') : route('ourLiveInServices') }}"
                           class="staff-clear-btn w-100"
                           aria-label="Clear filters">
                            <i class="fas fa-undo"></i>
                            <span class="d-md-none ms-1">Clear</span>
                        </a>
                    </div>
                </div>
            </form>
        @endif
    </div>

    {{-- Results Heading --}}
    <div class="staff-results-heading">
        <div style="padding: 0 10px;">
            <h4 style="text-align: left;">{{ $serviceTitle }}</h4>
            <p>Review the profiles below and choose the person who meets your needs.</p>
        </div>

        <span class="staff-results-label">
            <i class="fas fa-users me-1"></i>
            {{ $househelps->count() }} shown
        </span>
    </div>

    {{-- Staff Results --}}
    @if ($househelps->isEmpty())
        <div class="staff-empty-state">
            <div class="staff-empty-icon">
                <i class="fas fa-search"></i>
            </div>

            <h5>No Matching {{ $serviceTitle }} Found</h5>

            <p>
                We couldn't find any profiles matching your current search.
                Try changing your filters or clearing them to see more results.
            </p>

            <a href="{{ $isSkilledWorkerPage
                ? route('ourSkilledWorkerServices')
                : (request()->routeIs('househelps') ? route('househelps') : route('ourLiveInServices')) }}"
               class="staff-clear-btn">
                <i class="fas fa-undo me-2"></i>Reset Search
            </a>
        </div>
    @else
        <div class="row g-4 staff-results-grid">
            @foreach ($househelps as $househelp)
                @php
                    $profileImage = $househelp->profile_photo_path
                        ? asset('build/assets/image/profile_photo/' . $househelp->profile_photo_path)
                        : asset('build/assets/image/slider1.jpg');

                    $rating = $househelp->averageRating;
                @endphp

                <div class="col-12 col-md-6 col-xl-4">
                    <article class="staff-profile-card">

                        {{-- Card Header --}}
                        <div class="staff-card-top">
                            <span class="staff-type-label">
                                <i class="fas {{ $isSkilledWorkerPage ? 'fa-tools' : 'fa-house-user' }} me-1"></i>
                                {{ $isSkilledWorkerPage ? 'Skilled Worker' : 'Live-In Staff' }}
                            </span>

                            <span class="staff-rating">
                                <i class="fas fa-star"></i>
                                {{ $rating !== null ? number_format((float) $rating, 1) : 'N/A' }}
                            </span>
                        </div>

                        {{-- Profile Photo --}}
                        <div class="staff-profile-visual">
                            @if ($hasActiveSubscription)
                                <button type="button"
                                        class="staff-photo-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewpictureprofile{{ $househelp->id }}"
                                        aria-label="View photo of {{ $househelp->name }}">
                                    <img src="{{ $profileImage }}"
                                         alt="{{ $househelp->name }}'s profile photo"
                                         loading="lazy"
                                         decoding="async">
                                    <span class="staff-photo-overlay">
                                        <i class="fas fa-expand-alt"></i> View Photo
                                    </span>
                                </button>
                            @else
                                <img src="{{ $profileImage }}"
                                     alt="{{ $househelp->name }}'s profile photo"
                                     loading="lazy"
                                     decoding="async">
                            @endif
                        </div>

                        {{-- Profile Details --}}
                        <div class="staff-card-body">
                            <h5 class="staff-profile-name">{{ $househelp->name }}</h5>

                            <p class="staff-location">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $househelp->state_of_residence ?: 'Location not specified' }}
                            </p>

                            <div class="staff-detail-list">
                                <div class="staff-detail-item">
                                    <span class="staff-detail-icon">
                                        <i class="fas fa-venus-mars"></i>
                                    </span>
                                    <div>
                                        <small>Gender</small>
                                        <strong>
                                            {{ $househelp->gender ? ucfirst($househelp->gender) : 'Not specified' }}
                                        </strong>
                                    </div>
                                </div>

                                @if ($househelp->applicant_type == 'skill')
                                    <div class="staff-detail-item">
                                        <span class="staff-detail-icon">
                                            <i class="fas fa-tools"></i>
                                        </span>
                                        <div>
                                            <small>Specialty</small>
                                            <strong>
                                                @php
                                                    $specialties = $househelp->specialties;
                                                @endphp

                                                @if (is_array($specialties))
                                                    {{ implode(', ', $specialties) }}
                                                @else
                                                    {{ $specialties ?: 'Not specified' }}
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                @else
                                    <div class="staff-detail-item">
                                        <span class="staff-detail-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </span>
                                        <div>
                                            <small>Experience</small>
                                            <strong>
                                                {{ $househelp->years_of_experience ?: 'Not specified' }}
                                                @if (is_numeric($househelp->years_of_experience))
                                                    {{ (float) $househelp->years_of_experience == 1 ? 'year' : 'years' }}
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Availability and Request Status --}}
                            <div class="staff-status-row">
                                <span class="staff-availability {{ $househelp->availability ? 'is-available' : 'is-unavailable' }}">
                                    <span class="staff-status-dot"></span>
                                    {{ $househelp->availability ? 'Available' : 'Not Available' }}
                                </span>

                                @if ($househelp->activeRequest)
                                    <span class="staff-request-status request-sent">
                                        <i class="fas fa-paper-plane me-1"></i> Request Sent
                                    </span>
                                @elseif ($househelp->acceptedRequest)
                                    <span class="staff-request-status request-accepted">
                                        <i class="fas fa-check-circle me-1"></i> Accepted
                                    </span>
                                @else
                                    <span class="staff-request-status request-none">
                                        <i class="far fa-envelope me-1"></i> No Request
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Action --}}
                        <div class="staff-card-footer">
                            @if ($hasActiveSubscription)
                                <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}"
                                   class="staff-primary-action">
                                    <i class="fas fa-user-circle me-2"></i>
                                    View Full Profile

                                    @if (isset($remainingSearches))
                                        <span class="staff-action-count">
                                            {{ $remainingSearches }} left
                                        </span>
                                    @endif
                                </a>
                            @elseif (Auth::check() && isset($pendingPayment) && $pendingPayment)
                                <button type="button" class="staff-pending-action" disabled>
                                    <i class="fas fa-hourglass-half me-2"></i>
                                    Payment Pending Approval
                                </button>
                            @else
                                <a href="{{ route('payments.paymentoption') }}"
                                   class="staff-primary-action">
                                    <i class="fas fa-lock me-2"></i>
                                    Subscribe for Full Access
                                </a>
                            @endif
                        </div>
                    </article>
                </div>

                {{-- Photo Modal --}}
                @if ($hasActiveSubscription)
                    <div class="modal fade"
                         id="viewpictureprofile{{ $househelp->id }}"
                         tabindex="-1"
                         aria-labelledby="viewpictureprofileLabel{{ $househelp->id }}"
                         aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content staff-photo-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title"
                                        id="viewpictureprofileLabel{{ $househelp->id }}">
                                        {{ $househelp->name }} — Profile Photo
                                    </h5>

                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>

                                <div class="modal-body text-center">
                                    <img src="{{ $profileImage }}"
                                         alt="{{ $househelp->name }}'s profile photo"
                                         class="img-fluid staff-modal-image"
                                         loading="lazy"
                                         decoding="async">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($househelps->hasPages())
            <div class="staff-pagination">
                {{ $househelps->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>

<style>
    .staff-directory {
        color: #183b32;
    }

    .staff-search-intro {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 25px;
    }

    .staff-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        background: #eaf5ef;
        color: #1d6b4d;
        border-radius: 13px;
        font-size: 21px;
    }

    .staff-search-intro h4 {
        margin: 0 0 7px;
        color: #183b32;
        font-size: 20px;
        font-weight: 800;
    }

    .staff-search-intro p {
        margin: 0 0 7px;
        color: #718078;
        font-size: 14px;
        line-height: 1.7;
    }

    .staff-search-hint {
        color: #6739C9;
        font-size: 12px;
        font-weight: 600;
    }

    
    .staff-subscription-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 20px;
        margin-bottom: 24px;
        border: 1px solid #d9cafa;
        border-radius: 10px;
        background: #f7f3ff;
        color: #5c468c;
    }

    .staff-subscription-notice-icon {
        margin-top: 3px;
        font-size: 18px;
        flex-shrink: 0;
    }

    .staff-subscription-notice-content {
        flex: 1;
        min-width: 0;
    }

    .staff-subscription-notice-content > strong {
        display: block;
        margin-bottom: 5px;
        color: #4e328e;
        font-size: 14px;
        font-weight: 800;
    }

    .staff-subscription-notice-content > p {
        margin: 0;
        color: #5c468c;
        font-size: 12px;
        line-height: 1.7;
    }

    .staff-subscription-status {
        padding-top: 12px;
        margin-top: 12px;
        border-top: 1px solid #ded3f6;
        color: #4e328e;
        font-size: 13px;
        line-height: 1.8;
    }

    .staff-subscription-status .text-muted {
        color: #776a94 !important;
    }

    .staff-subscription-status .badge {
        margin: 0 3px;
    }

    .staff-subscription-status a {
        color: #6739C9;
        font-weight: 700;
    }

    .staff-subscription-status .btn-primary {
        background: #fff;
        border-color: #5129a8;
    }

    .staff-subscription-status .btn-primary:hover {
        background: #5129a8;
        border-color: #5129a8;
    }


 

    .staff-filter-panel {
        padding: 22px;
        margin-bottom: 35px;
        border: 1px solid #e5ebe7;
        border-radius: 14px;
        background: #f9fbfa;
    }

    .staff-filter-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .staff-filter-heading h5 {
        margin: 0;
        color: #183b32;
        font-size: 15px;
        font-weight: 800;
    }

    .staff-filter-heading > span {
        color: #819087;
        font-size: 11px;
    }

    .staff-search-form label {
        display: block;
        margin-bottom: 7px;
        color: #344d43;
        font-size: 12px;
        font-weight: 700;
    }

    .staff-search-form .form-select,
    .staff-search-form .form-control {
        min-height: 46px;
        border: 1px solid #dce5df;
        border-radius: 8px;
        background-color: #fff;
        color: #344d43;
        font-size: 13px;
        box-shadow: none;
    }

    .staff-search-form .form-select:focus,
    .staff-search-form .form-control:focus {
        border-color: #6739C9;
        box-shadow: 0 0 0 3px rgba(103, 57, 201, .09);
    }

    .staff-filter-action {
        display: flex;
        align-items: flex-end;
    }

    .staff-search-btn,
    .staff-clear-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 46px;
        padding: 10px 14px;
        border: 1px solid transparent;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: background .2s ease, border-color .2s ease;
    }

    .staff-search-btn {
        background: #6739C9;
        color: #fff;
    }

    .staff-search-btn:hover {
        background: #5129a8;
        color: #fff;
    }

    .staff-clear-btn {
        background: #fff;
        color: #46584e;
        border-color: #dce5df;
    }

    .staff-clear-btn:hover {
        background: #edf2ee;
        color: #183b32;
        border-color: #cbd8cf;
    }

    .staff-search-count {
        display: inline-block;
        margin-left: 4px;
        padding: 3px 6px;
        border-radius: 5px;
        background: rgba(255, 255, 255, .17);
        font-size: 10px;
    }

    .staff-results-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 22px;
    }

    .staff-results-heading h4 {
        margin: 0 0 6px;
        color: #183b32;
        font-size: 21px;
        font-weight: 800;
    }

    .staff-results-heading p {
        margin: 0;
        color: #758179;
        font-size: 13px;
        line-height: 1.6;
    }

    .staff-results-label {
        padding: 8px 11px;
        border-radius: 8px;
        background: #eaf5ef;
        color: #1d6b4d;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .staff-profile-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        border: 1px solid #e5ebe7;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 6px 22px rgba(20, 55, 43, .045);
        transition: transform .22s ease, box-shadow .22s ease,
                    border-color .22s ease;
    }

    .staff-profile-card:hover {
        transform: translateY(-4px);
        border-color: #d4c4f5;
        box-shadow: 0 13px 30px rgba(20, 55, 43, .09);
    }

    .staff-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 15px 17px;
        border-bottom: 1px solid #edf0ed;
    }

    .staff-type-label {
        color: #1d6b4d;
        font-size: 11px;
        font-weight: 800;
    }

    .staff-rating {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 7px;
        background: #fff7df;
        color: #735300;
        font-size: 12px;
        font-weight: 800;
    }

    .staff-rating i {
        color: #e3a51b;
    }

    .staff-profile-visual {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 25px 15px 16px;
        background: linear-gradient(180deg, #f8faf9 0%, #fff 100%);
    }

    .staff-profile-visual > img,
    .staff-photo-button img {
        display: block;
        width: 112px;
        height: 112px;
        border: 4px solid #fff;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 0 0 1px #dfe7e1, 0 5px 14px rgba(20, 55, 43, .1);
    }

    .staff-photo-button {
        position: relative;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: transparent;
        cursor: pointer;
    }

    .staff-photo-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border-radius: 50%;
        background: rgba(20, 55, 43, .76);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        opacity: 0;
        transition: opacity .2s ease;
    }

    .staff-photo-button:hover .staff-photo-overlay,
    .staff-photo-button:focus-visible .staff-photo-overlay {
        opacity: 1;
    }

    .staff-card-body {
        flex: 1;
        padding: 10px 20px 20px;
    }

    .staff-profile-name {
        margin: 0 0 8px;
        color: #183b32;
        font-size: 19px;
        font-weight: 800;
        text-align: center;
        overflow-wrap: anywhere;
    }

    .staff-location {
        margin: 0 0 20px;
        color: #758179;
        font-size: 12px;
        text-align: center;
    }

    .staff-location i {
        color: #6739C9;
    }

    .staff-detail-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 17px 0;
        border-top: 1px solid #edf0ed;
        border-bottom: 1px solid #edf0ed;
    }

    .staff-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        min-width: 0;
    }

    .staff-detail-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #f0eaff;
        color: #6739C9;
        font-size: 13px;
    }

    .staff-detail-item > div {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .staff-detail-item small {
        color: #849087;
        font-size: 11px;
    }

    .staff-detail-item strong {
        color: #344d43;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .staff-status-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 17px;
    }

    .staff-availability,
    .staff-request-status {
        display: inline-flex;
        align-items: center;
        padding: 7px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
    }

    .staff-status-dot {
        width: 7px;
        height: 7px;
        margin-right: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .staff-availability.is-available {
        background: #e7f7ec;
        color: #217445;
    }

    .staff-availability.is-unavailable {
        background: #fff0f0;
        color: #b42332;
    }

    .staff-request-status.request-sent {
        background: #edf2ff;
        color: #3657a6;
    }

    .staff-request-status.request-accepted {
        background: #e7f7ec;
        color: #217445;
    }

    .staff-request-status.request-none {
        background: #f1f3f2;
        color: #68766d;
    }

    .staff-card-footer {
        padding: 15px 18px;
        border-top: 1px solid #edf0ed;
        background: #fcfdfc;
    }

    .staff-primary-action,
    .staff-pending-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        width: 100%;
        min-height: 45px;
        padding: 11px 12px;
        border: 1px solid #6739C9;
        border-radius: 8px;
        background: #6739C9;
        color: #fff;
        text-align: center;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: background .2s ease;
    }

    .staff-primary-action:hover {
        background: #5129a8;
        border-color: #5129a8;
        color: #fff;
    }

    .staff-action-count {
        margin-left: 5px;
        padding: 3px 6px;
        border-radius: 5px;
        background: rgba(255, 255, 255, .17);
        font-size: 10px;
    }

    .staff-pending-action {
        border-color: #e7d9a6;
        background: #fff8e7;
        color: #795300;
        cursor: not-allowed;
    }

    .staff-empty-state {
        padding: 55px 25px;
        border: 1px dashed #d6e1d9;
        border-radius: 15px;
        background: #fafcfb;
        text-align: center;
    }

    .staff-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 62px;
        height: 62px;
        margin: 0 auto 18px;
        border-radius: 18px;
        background: #f0eaff;
        color: #6739C9;
        font-size: 24px;
    }

    .staff-empty-state h5 {
        margin-bottom: 10px;
        color: #183b32;
        font-size: 19px;
        font-weight: 800;
    }

    .staff-empty-state p {
        max-width: 480px;
        margin: 0 auto 20px;
        color: #758179;
        font-size: 13px;
        line-height: 1.8;
    }

    .staff-empty-state .staff-clear-btn {
        display: inline-flex;
        width: auto;
    }

    .staff-pagination {
        display: flex;
        justify-content: center;
        margin-top: 35px;
    }

    .staff-photo-modal {
        overflow: hidden;
        border: 0;
        border-radius: 14px;
    }

    .staff-photo-modal .modal-header {
        border-bottom: 1px solid #edf0ed;
    }

    .staff-modal-image {
        max-height: 75vh;
        object-fit: contain;
    }

    @media (max-width: 767px) {
        .staff-search-intro {
            gap: 12px;
        }

        .staff-search-intro h4 {
            font-size: 17px;
        }

        .staff-filter-panel {
            padding: 17px 13px;
        }

        .staff-filter-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .staff-filter-heading > span {
            font-size: 11px;
        }

        .staff-filter-action {
            display: block;
        }

        .staff-results-heading h4 {
            font-size: 18px;
        }

        .staff-results-heading p {
            font-size: 12px;
        }

        .staff-results-label {
            font-size: 10px;
        }

        .staff-card-body {
            padding-right: 17px;
            padding-left: 17px;
        }
    }
</style>
