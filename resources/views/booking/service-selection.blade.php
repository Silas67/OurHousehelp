@extends('layouts.usermain')
@section('booking-service-selection')

<div class="ohh-booking-page">

     @guest
        <div class="ohh-guest-banner">
            <i class="fas fa-user-circle"></i>
            Browsing as a guest — we'll ask you to sign in or register once you're ready to find staff.
        </div>
    @endguest

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <section class="ohh-booking-hero">
        <div class="ohh-booking-hero-content">

            <div class="ohh-eyebrow">
                <span class="ohh-eyebrow-line"></span>
                BOOK A SERVICE
            </div>

            <h1>
                Tell us what<br>
                <span>you need.</span>
            </h1>

            <p>
                Complete the details below and we'll help you find
                the right staff for your home.
            </p>

        </div>

        <div class="ohh-booking-hero-badge">
            <div class="ohh-hero-badge-icon">
                <i class="fas fa-shield-heart"></i>
            </div>

            <div>
                <strong>Trusted staff matching</strong>
                <span>Vetted candidates selected for your needs</span>
            </div>
        </div>
    </section>


    {{-- =========================================================
        BOOKING PROGRESS
    ========================================================== --}}
    <div class="ohh-progress-wrapper">

        <div class="ohh-progress-line"></div>

        <div class="ohh-progress-step active">
            <span class="ohh-step-number">1</span>
            <div>
                <strong>Select Service</strong>
                <small>Tell us what you need</small>
            </div>
        </div>

        <div class="ohh-progress-step">
            <span class="ohh-step-number">2</span>
            <div>
                <strong>Staff Matching</strong>
                <small>We'll find suitable staff</small>
            </div>
        </div>

        <div class="ohh-progress-step">
            <span class="ohh-step-number">3</span>
            <div>
                <strong>Choose Plan</strong>
                <small>Review your options</small>
            </div>
        </div>

        <div class="ohh-progress-step">
            <span class="ohh-step-number">4</span>
            <div>
                <strong>Payment</strong>
                <small>Complete your booking</small>
            </div>
        </div>

    </div>


    @include('partials.modals._ModalforBeforeYouProceed')
    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form
        action="{{ route('booking.save-service-selection') }}"
        method="POST"
        id="bookingForm">

        @csrf


        {{-- =====================================================
            01 — SERVICES
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">
                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">STEP 01</span>
                    <h2>What service do you need?</h2>
                </div>

                <span class="ohh-section-caption">
                    Select one or more services
                </span>
            </div>

            <div class="ohh-services-grid">

                @foreach($services as $service)

                    <div class="ohh-service-option">

                        <input
                            class="service-checkbox"
                            type="checkbox"
                            name="services[]"
                            value="{{ $service->id }}"
                            id="service_{{ $service->id }}"
                            data-cost="{{ $service->base_cost }}"
                            {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                        >

                        <label
                            for="service_{{ $service->id }}"
                            class="ohh-service-select-card">

                            <div class="ohh-service-select-top">

                                <div class="ohh-service-icon">
                                    <i class="{{ $service->icon_class ?? 'fas fa-broom' }}"></i>
                                </div>

                                <span class="ohh-check-icon">
                                    <i class="fas fa-check"></i>
                                </span>

                            </div>

                            <div class="ohh-service-select-body">

                                <h3>
                                    {{ $service->service_name }}
                                </h3>

                                @if($service->about_service)
                                    <p class="service-about">
                                        {{ $service->about_service }}
                                    </p>
                                @endif

                            </div>

                        </label>

                    </div>

                @endforeach

            </div>

            @error('services')
                <div class="ohh-error-message">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
            @enderror

        </section>


        {{-- =====================================================
            02 — SERVICE TYPE
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">
                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">STEP 02</span>
                    <h2>How should your staff work?</h2>
                </div>
            </div>

            <div class="ohh-choice-grid">

                <label class="ohh-choice-card">

                    <input
                        type="radio"
                        name="applicant_type"
                        value="live-out"
                        id="liveOut"
                        {{ old('applicant_type', 'live-out') === 'live-out' ? 'checked' : '' }}
                    >

                    <div class="ohh-choice-card-inner">

                        <div class="ohh-choice-icon">
                            <i class="fas fa-person-walking-arrow-right"></i>
                        </div>

                        <div class="ohh-choice-content">
                            <div class="ohh-choice-title">
                                Live-Out
                                <span class="ohh-choice-check">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>

                            <p style="font-size: 10px;">
                                Staff comes to your home during agreed
                                working hours and returns home daily.
                            </p>
                        </div>

                    </div>

                </label>


                <label class="ohh-choice-card">

                    <input
                        type="radio"
                        name="applicant_type"
                        value="semi-live-in"
                        id="semiLiveIn"
                        {{ old('applicant_type') === 'semi-live-in' ? 'checked' : '' }}
                    >

                    <div class="ohh-choice-card-inner">

                        <div class="ohh-choice-icon ohh-icon-gold">
                            <i class="fas fa-house-user"></i>
                        </div>

                        <div class="ohh-choice-content">
                            <div class="ohh-choice-title">
                                Semi Live-In
                                <span class="ohh-choice-check">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>

                            <p>
                                Staff works 5–6 days and returns home
                                on agreed off days.
                            </p>
                        </div>

                    </div>

                </label>

            </div>

        </section>


        {{-- =====================================================
            03 — LOCATION
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">
                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">STEP 03</span>
                    <h2>Where will the service take place?</h2>
                </div>
            </div>

            <div class="ohh-form-card">

                <div class="ohh-form-grid">

                    <div class="ohh-field">

                        <label>
                            State
                            <span>*</span>
                        </label>

                        <div class="ohh-input-wrap">
                            <i class="fas fa-location-dot" style="color: #0B6623;"></i>

                            <select
                                name="state"
                                class="form-select"
                                required>
                                <option value="">Select state</option>

                                @foreach($nigerianStates as $state)
                                    <option
                                        value="{{ $state }}"
                                        {{ old('state') == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        @error('state')
                            <div class="ohh-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="ohh-field">

                        <label>
                            Area / City
                            <span>*</span>
                        </label>

                        <div class="ohh-input-wrap">
                            <i class="fas fa-map-pin" style="color: #FF3B30;"></i>

                            <select
                                name="city"
                                id="citySelect"
                                class="form-select"
                                required>

                                <option value="">
                                    Select a state first
                                </option>

                            </select>
                        </div>

                        @error('city')
                            <div class="ohh-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="ohh-field full-width">

                        <label>
                            Full Address
                            <span>*</span>
                        </label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-house" style="color: #007AFF;"></i>

                            <input
                                type="text"
                                name="address"
                                class="form-control"
                                placeholder="Enter your street address"
                                value="{{ old('address') }}"
                                required>

                        </div>

                        @error('address')
                            <div class="ohh-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            04 — PREFERENCES
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">
                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">OPTIONAL</span>
                    <h2>Staff preferences</h2>
                </div>

                <span class="ohh-section-caption">
                    Tell us about your preferences
                </span>
            </div>

            <div class="ohh-form-card">

                <div class="ohh-form-grid">

                    <div class="ohh-field">

                        <label>Religion</label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-praying-hands" style="color: #7B9EB0;"></i>

                            <select name="religion" class="form-select">

                                <option value="">
                                    Religion (Optional)
                                </option>

                                <option
                                    value="Christian"
                                    {{ old('religion') === 'Christian' ? 'selected' : '' }}>
                                    Christian
                                </option>

                                <option
                                    value="Muslim"
                                    {{ old('religion') === 'Muslim' ? 'selected' : '' }}>
                                    Muslim
                                </option>

                                <option
                                    value="Other"
                                    {{ old('religion') === 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="ohh-field">

                        <label>Age Range</label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-user-clock" style="color: #007AFF;"></i>

                            <select name="age_range" class="form-select">

                                <option value="">
                                    Age Range (Optional)
                                </option>

                                <option
                                    value="18-25"
                                    {{ old('age_range') === '18-25' ? 'selected' : '' }}>
                                    18–25
                                </option>

                                <option
                                    value="26-35"
                                    {{ old('age_range') === '26-35' ? 'selected' : '' }}>
                                    26–35
                                </option>

                                <option
                                    value="36-45"
                                    {{ old('age_range') === '36-45' ? 'selected' : '' }}>
                                    36–45
                                </option>

                                <option
                                    value="46+"
                                    {{ old('age_range') === '46+' ? 'selected' : '' }}>
                                    46+
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            05 — SCHEDULE
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">

                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">STEP 04</span>
                    <h2>When do you need your staff?</h2>
                </div>

            </div>

            <div class="ohh-form-card">

                <div class="ohh-form-grid">

                    <div class="ohh-field">

                        <label>
                            Preferred Start Date
                            <span>*</span>
                        </label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-calendar-days" style="color: #007AFF;"></i>

                            <input
                                type="date"
                                name="preferred_start_date"
                                class="form-control"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('preferred_start_date') }}"
                                required>

                        </div>

                        @error('preferred_start_date')
                            <div class="ohh-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="ohh-field">

                        <label>
                            Duration
                            <span>*</span>
                        </label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-hourglass-half" style="color: #e6ba3c;"></i>

                            <select
                                name="duration_weeks"
                                class="form-select"
                                id="durationSelect"
                                required>

                                <option value="">
                                    Select duration
                                </option>

                                <option
                                    value="1"
                                    {{ old('duration_weeks') == 1 ? 'selected' : '' }}>
                                    One-off — single visit
                                </option>

                                <option
                                    value="4"
                                    {{ old('duration_weeks') == 4 ? 'selected' : '' }}>
                                    4 weeks — 1 month
                                </option>

                                <option
                                    value="8"
                                    {{ old('duration_weeks') == 8 ? 'selected' : '' }}>
                                    8 weeks — 2 months (5% off)
                                </option>

                                <option
                                    value="12"
                                    {{ old('duration_weeks') == 12 ? 'selected' : '' }}>
                                    12 weeks — 3 months (8% off)
                                </option>

                            </select>

                        </div>

                        @error('duration_weeks')
                            <div class="ohh-field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            APARTMENT TYPE
        ====================================================== --}}
        @if($apartments->count() > 0)

            <section
                class="ohh-form-section d-none"
                id="apartmentSection">

                <div class="ohh-section-heading">

                    <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;" >
                        <span class="ohh-section-kicker">
                            SERVICE DETAILS
                        </span>

                        <h2>What type of apartment is it?</h2>
                    </div>

                </div>

                <div class="ohh-form-card">

                    <div class="ohh-field">

                        <label>
                            Apartment Type
                        </label>

                        <div class="ohh-input-wrap">

                            <i class="fas fa-building"></i>

                            <select
                                name="apartment_type"
                                class="form-select">

                                <option value="">
                                    Select apartment type
                                </option>

                                @foreach($apartments as $apt)

                                    <option
                                        value="{{ $apt->id }}"
                                        data-cost="{{ $apt->cost }}"
                                        {{ old('apartment_type') == $apt->id ? 'selected' : '' }}>

                                        {{ $apt->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

            </section>

        @endif


        {{-- =====================================================
            06 — PACKAGE
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">

                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;"   >
                    <span class="ohh-section-kicker">SERVICE FREQUENCY</span>
                    <h2>Choose your package</h2>
                </div>

                <span class="ohh-section-caption">
                    How often should your staff work?
                </span>

            </div>

            <div class="ohh-package-grid">

                @foreach($packages as $pkg)

                    <label class="ohh-package-card">

                        <input
                            type="radio"
                            name="package"
                            value="{{ $pkg->id }}"
                            data-cost="{{ $pkg->cost }}"
                            {{ old('package') == $pkg->id ? 'checked' : '' }}>

                        <div class="ohh-package-inner">

                            <div class="ohh-package-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>

                            <div class="ohh-package-content">

                                <strong>
                                    {{ $pkg->name }}
                                </strong>

                                <span>
                                    {{ $pkg->days_per_week }} days per week
                                </span>

                            </div>

                            <span class="ohh-package-check">
                                <i class="fas fa-check"></i>
                            </span>

                        </div>

                    </label>

                @endforeach

            </div>

            {{-- Hidden select retained for your existing JavaScript --}}
            <select
                name="package"
                id="packageSelect"
                class="d-none"
                required>

                <option value="">
                    Choose a package
                </option>

                @foreach($packages as $pkg)

                    <option
                        value="{{ $pkg->id }}"
                        data-cost="{{ $pkg->cost }}"
                        {{ old('package') == $pkg->id ? 'selected' : '' }}>

                        {{ $pkg->name }} -
                        {{ $pkg->days_per_week }} days/week

                    </option>

                @endforeach

            </select>

            @error('package')
                <div class="ohh-error-message">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
            @enderror

        </section>


        {{-- =====================================================
            07 — WORKING DAYS / HOURS
        ====================================================== --}}
        <section
            class="ohh-form-section"
            id="serviceDaysSection">

            <div class="ohh-section-heading">

                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">OPTIONAL</span>
                    <h2>Preferred working schedule</h2>
                </div>

                <span class="ohh-section-caption">
                    You can agree the final schedule with staff after matching
                </span>

            </div>

            <div class="ohh-form-card">

                <div class="ohh-form-grid">

                    <div class="ohh-field">

                        <label>
                            Preferred Service Days
                        </label>

                        <div class="ohh-days-grid">

                            @foreach([
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                            ] as $day)

                                <label class="ohh-day-option">

                                    <input
                                        class="service-day-check"
                                        type="checkbox"
                                        name="service_days[]"
                                        value="{{ $day }}"
                                        id="day{{ $day }}"
                                        {{ is_array(old('service_days')) && in_array($day, old('service_days')) ? 'checked' : '' }}
                                    >

                                    <span>
                                        {{ substr($day, 0, 3) }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>


                    <div class="ohh-field">

                        <label>
                            Preferred Working Hours
                        </label>

                        <div class="ohh-time-grid">

                            <div>

                                <label class="ohh-mini-label">
                                    Start Time
                                </label>

                                <div class="ohh-input-wrap">

                                    <i class="fas fa-clock"></i>

                                    <input
                                        type="time"
                                        name="working_hour_start"
                                        class="form-control"
                                        value="{{ old('working_hour_start', '08:00') }}">

                                </div>

                            </div>


                            <div>

                                <label class="ohh-mini-label">
                                    End Time
                                </label>

                                <div class="ohh-input-wrap">

                                    <i class="fas fa-clock"></i>

                                    <input
                                        type="time"
                                        name="working_hour_end"
                                        class="form-control"
                                        value="{{ old('working_hour_end', '17:00') }}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
            08 — MANAGEMENT PLAN
        ====================================================== --}}
        <section class="ohh-form-section">

            <div class="ohh-section-heading">

                <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">
                    <span class="ohh-section-kicker">
                        MANAGEMENT
                    </span>

                    <h2>Who should manage your staff?</h2>
                </div>

                <span class="ohh-section-caption">
                    Choose the level of support you want
                </span>

            </div>


            <div class="ohh-management-grid">

                {{-- CLIENT MANAGED --}}
                <label
                    class="ohh-management-card"
                    id="planCard_client">

                    <input
                        class="management-plan-radio"
                        type="radio"
                        name="management_plan"
                        value="client-managed"
                        id="plan_client"
                        data-cost="0"
                        checked>

                    <div class="ohh-management-inner">

                        <div class="ohh-management-top">

                            <div class="ohh-management-icon">
                                <i class="fas fa-user-check"></i>
                            </div>

                            <span class="ohh-free-badge">
                                FREE
                            </span>

                        </div>

                        <h3>
                            I'll manage the staff myself
                        </h3>

                        <p>
                            You handle scheduling, communication and
                            day-to-day management directly with the staff.
                        </p>

                        <div class="ohh-feature-list">

                            <div>
                                <i class="fas fa-check"></i>
                                Direct communication with staff
                            </div>

                            <div>
                                <i class="fas fa-check"></i>
                                You handle scheduling
                            </div>

                            <div>
                                <i class="fas fa-check"></i>
                                You resolve disputes yourself
                            </div>

                        </div>

                        <div class="ohh-select-indicator">
                            <span></span>
                            Select this plan
                        </div>

                    </div>

                </label>


                {{-- COMPANY MANAGED --}}
                <label
                    class="ohh-management-card"
                    id="planCard_company">

                    <input
                        class="management-plan-radio"
                        type="radio"
                        name="management_plan"
                        value="company-managed"
                        id="plan_company"
                        data-cost="5000">

                    <div class="ohh-management-inner">

                        <div class="ohh-management-top">

                            <div class="ohh-management-icon ohh-icon-gold">
                                <i class="fas fa-shield-heart"></i>
                            </div>

                            <span class="ohh-managed-badge">
                                FULL SUPPORT
                            </span>

                        </div>

                        <h3>
                            Managed by OurHouseHelp
                        </h3>

                        <p>
                            We handle the management process while you
                            focus on enjoying reliable service.
                        </p>

                        <div class="ohh-feature-list">

                            <div>
                                <i class="fas fa-check"></i>
                                Dedicated account manager
                            </div>

                            <div>
                                <i class="fas fa-check"></i>
                                Free staff replacements
                            </div>

                            <div>
                                <i class="fas fa-check"></i>
                                Quality monitoring & mediation
                            </div>

                            <div>
                                <i class="fas fa-check"></i>
                                Emergency support
                            </div>

                        </div>

                        <div class="ohh-select-indicator">
                            <span></span>
                            Select this plan
                        </div>

                    </div>

                </label>

            </div>


            @error('management_plan')
                <div class="ohh-error-message">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ $message }}
                </div>
            @enderror

        </section>


        {{-- =====================================================
            COST SUMMARY
        ====================================================== --}}
        <section class="ohh-summary-section">

            <div class="ohh-summary-header">

                <div>
                    <span class="ohh-section-kicker">
                        ESTIMATE
                    </span>

                    <h2>
                        Your service estimate
                    </h2>

                    <p>
                        Your final amount will be confirmed after staff matching.
                    </p>
                </div>

                <div class="ohh-summary-lock">
                    <i class="fas fa-lock"></i>
                    Secure estimate
                </div>

            </div>


            {{-- COMPANY MANAGED --}}
            <div
                id="companyManagedCost"
                class="ohh-cost-panel">

                <div class="ohh-cost-row">

                    <span>Base Service Cost</span>

                    <strong id="baseCostDisplay">
                        ₦0
                    </strong>

                </div>

                <div class="ohh-cost-row">

                    <span>Extra Services</span>

                    <strong id="extraCostDisplay">
                        ₦0
                    </strong>

                </div>

                <div class="ohh-cost-row">

                    <span>Package Cost</span>

                    <strong id="packageCostDisplay">
                        ₦0
                    </strong>

                </div>


                <div
                    class="ohh-cost-row d-none"
                    id="managementPlanRow">

                    <span>
                        Platform Management Fee
                    </span>

                    <strong id="managementPlanCostDisplay">
                        ₦0
                    </strong>

                </div>


                <div
                    class="ohh-cost-row d-none"
                    id="discountRow">

                    <span>
                        Duration Discount
                    </span>

                    <strong
                        id="discountDisplay"
                        class="ohh-discount-value">
                        ₦0
                    </strong>

                </div>


                <div class="ohh-cost-divider"></div>


                <div class="ohh-total-row">

                    <div>
                        <span id="grandTotalLabel">
                            Monthly Total
                        </span>

                        <small>
                            Estimated booking cost
                        </small>
                    </div>

                    <strong id="grandTotalDisplay">
                        ₦0
                    </strong>

                </div>

            </div>


            {{-- CLIENT MANAGED --}}
            <div
                id="clientManagedCost"
                class="ohh-salary-panel"
                style="display:none;">

                <div class="ohh-salary-header">

                    <div>

                        <span class="ohh-section-kicker">
                            DIRECT STAFF PAYMENT
                        </span>

                        <h3>
                            Staff Salary Summary
                        </h3>

                        <small id="salaryDaysLabel">
                            Select a package to see salary
                        </small>

                    </div>

                    <strong id="staffSalaryDisplay">
                        ₦0/month
                    </strong>

                </div>


                <div id="salaryBreakdown"></div>


                <div class="ohh-salary-note">

                    <i class="fas fa-circle-info"></i>

                    <span>
                        You pay this directly to your staff every month.
                        No payment to OurHouseHelp beyond your booking credit.
                    </span>

                </div>

            </div>

        </section>


        {{-- =====================================================
            URGENT REQUEST
        ====================================================== --}}
        <section class="ohh-urgent-card">

            <div class="ohh-urgent-icon">
                <i class="fas fa-bolt"></i>
            </div>

            <div class="ohh-urgent-content">

                <span>
                    NEED STAFF URGENTLY?
                </span>

                <h3>
                    Don't want to complete the full form?
                </h3>

                <p>
                    Submit an urgent request and our team will
                    contact you directly.
                </p>

            </div>


            <div class="ohh-urgent-action">

                <a
                    href="{{ route('booking.urgent-request') }}"
                    class="ohh-urgent-btn">

                    <i class="fas fa-bolt"></i>
                    I Need Staff Urgently

                </a>

                <small>
                    Or call us directly:
                    <strong>08126237436</strong>
                </small>

            </div>

        </section>


        {{-- =====================================================
            SUBMIT
        ====================================================== --}}
        <div class="ohh-submit-area">

            <div class="ohh-submit-copy">

                <div class="ohh-submit-icon">
                    <i class="fas fa-user-shield"></i>
                </div>

                <div>
                    <strong>
                        Ready to find your staff?
                    </strong>

                    <span>
                        We'll use your preferences to match you with suitable candidates.
                    </span>
                </div>

            </div>


            <button
                type="submit"
                class="ohh-submit-btn"
                id="submitBtn">

                <span>
                    Find Staff for Me
                </span>

                <i class="fas fa-arrow-right"></i>

            </button>

        </div>

    </form>

</div>


<style>

    .ohh-guest-banner {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #FFF8E7;
        border: 1px solid #F0DCA0;
        color: #6B5416;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .ohh-guest-banner i {
        color: #A9782E;
        font-size: 14px;
    }

    /* =========================================================
    PAGE
    ========================================================= */

    .ohh-booking-page {
        max-width: 1180px;
        margin: 0 auto;
        padding: 6px 24px 70px;
        color: #16241F;
    }


    /* =========================================================
    HERO
    ========================================================= */

    .ohh-booking-hero {
        position: relative;
        overflow: hidden;

        display: flex;
        justify-content: space-between;
        align-items: flex-end;

        gap: 30px;

        padding: 48px 52px;
        margin-bottom: 30px;

        border-radius: 22px;

        background:
            radial-gradient(
                circle at 85% 20%,
                rgba(167,120,46,0.18),
                transparent 35%
            ),
            linear-gradient(
                135deg,
                #0B4A3B,
                #0F6E56
            );

        color: #fff;
    }

    .ohh-booking-hero::after {
        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

        right: -110px;
        bottom: -140px;

        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .ohh-booking-hero-content {
        position: relative;
        z-index: 2;
        text-align: left;
    }

    .ohh-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;

        margin-bottom: 15px;

        color: #9CE3CB;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: .18em;
    }

    .ohh-eyebrow-line {
        width: 28px;
        height: 1px;
        background: #9CE3CB;
    }

    .ohh-booking-hero h1 {
        margin: 0;

        font-family: Georgia, serif;

        font-size: clamp(2.3rem, 5vw, 3.8rem);
        line-height: 1.03;

        font-weight: 500;
    }

    .ohh-booking-hero h1 span {
        color: #9CE3CB;
    }

    .ohh-booking-hero p {
        max-width: 520px;

        margin: 17px 0 0;

        color: rgba(255,255,255,.75);

        font-size: 15px;
        line-height: 1.7;
    }


    /* HERO BADGE */

    .ohh-booking-hero-badge {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        gap: 13px;

        min-width: 265px;

        padding: 15px 17px;

        border: 1px solid rgba(255,255,255,.12);
        border-radius: 14px;

        background: rgba(255,255,255,.08);

        backdrop-filter: blur(6px);
    }

    .ohh-hero-badge-icon {
        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #9CE3CB;
        color: #0B4A3B;
    }

    .ohh-booking-hero-badge strong {
        display: block;

        margin-bottom: 3px;

        font-size: 13px;
    }

    .ohh-booking-hero-badge span {
        display: block;

        color: rgba(255,255,255,.65);

        font-size: 11px;
        line-height: 1.5;
    }


    /* =========================================================
    PROGRESS
    ========================================================= */

    .ohh-progress-wrapper {
        position: relative;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 40px;
    }

    .ohh-progress-line {
        position: absolute;
        top: 21px;
        left: 6%;
        right: 6%;
        height: 1px;
        background: #DDE5E0;
        z-index: 0;
    }

    .ohh-progress-step {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: left;
        justify-content: left;
        gap: 10px;
        padding: 4px 8px;
        background: #FAFCFB;
        color: #7B857F;
    }

    .ohh-step-number {
        width: 35px;
        height: 35px;
        flex: 0 0 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #EEF2F0;
        color: #7A837E;

        font-size: 12px;
        font-weight: 700;
    }

    .ohh-progress-step strong {
        display: block;

        color: #68746E;

        font-size: 12px;
    }

    .ohh-progress-step small {
        display: block;

        margin-top: 2px;

        font-size: 10px;
    }

    .ohh-progress-step.active .ohh-step-number {
        background: #0F6E56;
        color: #fff;
    }

    .ohh-progress-step.active strong {
        color: #0B4A3B;
    }


    /* =========================================================
    FORM SECTIONS
    ========================================================= */

    .ohh-form-section {
        margin-bottom: 47px;
    }

    .ohh-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;

        margin-bottom: 19px;
    }

    .ohh-section-kicker {
        display: block;

        margin-bottom: 7px;

        color: #A9782E;

        font-size: 10px;
        font-weight: 700;

        letter-spacing: .17em;
        text-transform: uppercase;
    }

    .ohh-section-heading h2 {
        margin: 0;

        font-family: Georgia, serif;

        font-size: 28px;
        line-height: 1.15;

        font-weight: 500;

        color: #0B4A3B;
    }

    .ohh-section-caption {
        color: #7B857F;

        font-size: 12px;
    }


    /* =========================================================
    SERVICES
    ========================================================= */

    .ohh-services-grid {
        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 15px;
    }

    .ohh-service-option {
        position: relative;
    }

    .ohh-service-option input {
        position: absolute;

        opacity: 0;
        pointer-events: none;
    }

    .ohh-service-select-card {
        position: relative;

        display: flex;
        flex-direction: column;

        min-height: 200px;

        padding: 22px;

        border: 1px solid #DCE7E1;
        border-radius: 17px;

        background: #fff;

        cursor: pointer;

        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease,
            background .22s ease;
    }

    .ohh-service-select-card:hover {
        transform: translateY(-3px);

        border-color: #AFC8BC;

        box-shadow: 0 12px 28px rgba(22,36,31,.07);
    }

    .ohh-service-option input:checked
    + .ohh-service-select-card {
        border-color: #0F6E56;

        background:
            linear-gradient(
                145deg,
                #EAF8F3,
                #fff
            );

        box-shadow: 0 10px 26px rgba(15,110,86,.09);
    }

    .ohh-service-select-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ohh-service-icon {
        width: 47px;
        height: 47px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #E6F0EB;
        color: #0F6E56;

        font-size: 17px;
    }

    .ohh-check-icon {
        width: 27px;
        height: 27px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        border: 1px solid #DCE4E0;

        color: transparent;

        font-size: 11px;

        transition: .2s ease;
    }

    .ohh-service-option input:checked
    + .ohh-service-select-card
    .ohh-check-icon {
        background: #0F6E56;
        border-color: #0F6E56;
        color: #fff;
    }

    .ohh-service-select-body {
        margin-top: 25px;
    }

    .ohh-service-select-body h3 {
        margin: 0 0 8px;

        color: #18352D;

        font-size: 17px;
        font-weight: 700;
    }

    .ohh-service-select-body p {
        margin: 0;

        color: #6F7974;

        font-size: 12px;
        line-height: 1.65;
    }


    /* =========================================================
    CHOICE CARDS
    ========================================================= */

    .ohh-choice-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 16px;
    }

    .ohh-choice-card {
        position: relative;

        display: block;

        cursor: pointer;
    }

    .ohh-choice-card > input {
        position: absolute;

        opacity: 0;
    }

    .ohh-choice-card-inner {
        display: flex;

        gap: 16px;

        padding: 23px;

        border: 1px solid #DCE7E1;
        border-radius: 17px;

        background: #fff;

        transition: .2s ease;
    }

    .ohh-choice-card:hover
    .ohh-choice-card-inner {
        border-color: #AFC8BC;
        box-shadow: 0 10px 24px rgba(22,36,31,.06);
    }

    .ohh-choice-card > input:checked
    + .ohh-choice-card-inner {
        border-color: #0F6E56;
        background: #F2FAF7;
    }

    .ohh-choice-icon {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: #E6F0EB;
        color: #0F6E56;
    }

    .ohh-icon-gold {
        background: #F5EDDF;
        color: #A9782E;
    }

    .ohh-choice-title {
        display: flex;
        justify-content: space-between;
        align-items: center;

        color: #18352D;

        font-size: 16px;
        font-weight: 700;
    }

    .ohh-choice-content p {
        margin: 8px 0 0;

        color: #707A75;

        font-size: 12px;
        line-height: 1.65;
    }

    .ohh-choice-check {
        width: 22px;
        height: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #D9E2DE;
        border-radius: 50%;

        color: transparent;

        font-size: 9px;
    }

    .ohh-choice-card > input:checked
    + .ohh-choice-card-inner
    .ohh-choice-check {
        background: #0F6E56;
        border-color: #0F6E56;
        color: #fff;
    }


    /* =========================================================
    FORM CARD
    ========================================================= */

    .ohh-form-card {
        padding: 25px;

        border: 1px solid #E0E8E3;
        border-radius: 17px;

        background: #fff;
    }

    .ohh-form-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 20px;
    }

    .ohh-field.full-width {
        grid-column: 1 / -1;
    }

    .ohh-field > label,
    .ohh-mini-label {
        display: block;

        margin-bottom: 8px;

        color: #33463F;

        font-size: 12px;
        font-weight: 700;
    }

    .ohh-field > label span {
        color: #B85C5C;
    }

    .ohh-input-wrap {
        position: relative;
    }

    .ohh-input-wrap > i {
        position: absolute;

        top: 50%;
        left: 14px;

        transform: translateY(-50%);

        z-index: 2;

        color: #7A8D84;

        font-size: 12px;

        pointer-events: none;
    }

    .ohh-input-wrap .form-control,
    .ohh-input-wrap .form-select {
        min-height: 48px;

        padding-left: 38px;

        border: 1px solid #DDE6E1;
        border-radius: 11px;

        background: #FBFCFB;

        color: #263A33;

        font-size: 13px;

        box-shadow: none;
    }

    .ohh-input-wrap .form-control:focus,
    .ohh-input-wrap .form-select:focus {
        border-color: #0F6E56;

        background: #fff;

        box-shadow: 0 0 0 3px rgba(15,110,86,.08);
    }


    /* =========================================================
    PACKAGES
    ========================================================= */

    .ohh-package-grid {
        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 14px;
    }

    .ohh-package-card {
        position: relative;

        cursor: pointer;
    }

    .ohh-package-card input {
        position: absolute;
        opacity: 0;
    }

    .ohh-package-inner {
        display: flex;
        align-items: center;

        gap: 13px;

        min-height: 88px;

        padding: 17px;

        border: 1px solid #DCE7E1;
        border-radius: 15px;

        background: #fff;

        transition: .2s ease;
    }

    .ohh-package-card:hover
    .ohh-package-inner {
        border-color: #B7CBBF;
        box-shadow: 0 8px 20px rgba(22,36,31,.05);
    }

    .ohh-package-card input:checked
    + .ohh-package-inner {
        border-color: #0F6E56;

        background: #F2FAF7;
    }

    .ohh-package-icon {
        width: 39px;
        height: 39px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 39px;

        border-radius: 11px;

        background: #EAF0ED;

        color: #0F6E56;

        font-size: 13px;
    }

    .ohh-package-content strong {
        display: block;

        color: #18352D;

        font-size: 13px;
    }

    .ohh-package-content span {
        display: block;

        margin-top: 3px;

        color: #7A837E;

        font-size: 11px;
    }

    .ohh-package-check {
        margin-left: auto;

        width: 22px;
        height: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #DCE4E0;
        border-radius: 50%;

        color: transparent;

        font-size: 9px;
    }

    .ohh-package-card input:checked
    + .ohh-package-inner
    .ohh-package-check {
        background: #0F6E56;
        border-color: #0F6E56;
        color: #fff;
    }


    /* =========================================================
    DAYS
    ========================================================= */

    .ohh-days-grid {
        display: flex;
        flex-wrap: wrap;

        gap: 8px;
    }

    .ohh-day-option {
        cursor: pointer;
    }

    .ohh-day-option input {
        position: absolute;
        opacity: 0;
    }

    .ohh-day-option span {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        min-width: 49px;
        height: 40px;

        padding: 0 9px;

        border: 1px solid #DCE5E0;
        border-radius: 9px;

        background: #FBFCFB;

        color: #6F7974;

        font-size: 11px;
        font-weight: 700;

        transition: .2s ease;
    }

    .ohh-day-option:hover span {
        border-color: #B9C9C1;
    }

    .ohh-day-option input:checked + span {
        border-color: #0F6E56;

        background: #0F6E56;

        color: #fff;
    }

    .ohh-time-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 10px;
    }

    .ohh-mini-label {
        color: #78837D;
        font-size: 10px;
        font-weight: 600;
    }


    /* =========================================================
    MANAGEMENT
    ========================================================= */

    .ohh-management-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 17px;
    }

    .ohh-management-card {
        position: relative;

        cursor: pointer;
    }

    .ohh-management-card > input {
        position: absolute;
        opacity: 0;
    }

    .ohh-management-inner {
        height: 100%;

        padding: 25px;

        border: 1px solid #DCE7E1;
        border-radius: 18px;

        background: #fff;

        transition: .2s ease;
    }

    .ohh-management-card:hover
    .ohh-management-inner {
        border-color: #B8C9C0;

        box-shadow: 0 12px 28px rgba(22,36,31,.07);
    }

    .ohh-management-card > input:checked
    + .ohh-management-inner {
        border: 2px solid #0F6E56;

        background:
            linear-gradient(
                145deg,
                #F2FAF7,
                #fff
            );
    }

    .ohh-management-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ohh-management-icon {
        width: 47px;
        height: 47px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #E6F0EB;
        color: #0F6E56;
    }

    .ohh-free-badge,
    .ohh-managed-badge {
        padding: 6px 9px;

        border-radius: 20px;

        font-size: 9px;
        font-weight: 700;
        letter-spacing: .08em;
    }

    .ohh-free-badge {
        background: #E9F5EF;
        color: #2B7B59;
    }

    .ohh-managed-badge {
        background: #F5EDDF;
        color: #A9782E;
    }

    .ohh-management-inner h3 {
        margin: 21px 0 8px;

        color: #18352D;

        font-size: 17px;
    }

    .ohh-management-inner > p {
        margin: 0;

        color: #717B76;

        font-size: 12px;
        line-height: 1.65;
    }

    .ohh-feature-list {
        display: flex;
        flex-direction: column;

        gap: 9px;

        margin-top: 18px;
    }

    .ohh-feature-list div {
        display: flex;
        align-items: center;

        gap: 8px;

        color: #68756F;

        font-size: 11px;
    }

    .ohh-feature-list i {
        color: #0F6E56;

        font-size: 9px;
    }

    .ohh-select-indicator {
        display: flex;
        align-items: center;

        gap: 8px;

        margin-top: 20px;

        color: #8A948F;

        font-size: 10px;
        font-weight: 700;
    }

    .ohh-select-indicator span {
        width: 15px;
        height: 15px;

        border: 1px solid #CAD5D0;
        border-radius: 50%;
    }

    .ohh-management-card > input:checked
    + .ohh-management-inner
    .ohh-select-indicator {
        color: #0F6E56;
    }

    .ohh-management-card > input:checked
    + .ohh-management-inner
    .ohh-select-indicator span {
        border: 4px solid #0F6E56;
    }


    /* =========================================================
    SUMMARY
    ========================================================= */

    .ohh-summary-section {
        overflow: hidden;

        margin-bottom: 28px;

        border: 1px solid #E1E7E3;
        border-radius: 19px;

        background: #fff;

        box-shadow: 0 8px 24px rgba(22,36,31,.04);
    }

    .ohh-summary-header {
        display: flex;

        justify-content: space-between;
        align-items: center;

        gap: 20px;

        padding: 25px 27px;

        background: #F7F4EC;

        border-bottom: 1px solid #E9E3D8;
    }

    .ohh-summary-header h2 {
        margin: 0;

        font-family: Georgia, serif;

        color: #0B4A3B;

        font-size: 25px;
        font-weight: 500;
    }

    .ohh-summary-header p {
        margin: 6px 0 0;

        color: #7A817D;

        font-size: 11px;
    }

    .ohh-summary-lock {
        display: flex;
        align-items: center;

        gap: 7px;

        color: #68756F;

        font-size: 10px;
        font-weight: 700;
    }

    .ohh-summary-lock i {
        color: #0F6E56;
    }

    .ohh-cost-panel {
        padding: 23px 27px;
    }

    .ohh-cost-row {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 20px;

        padding: 8px 0;

        color: #6E7873;

        font-size: 12px;
    }

    .ohh-cost-row strong {
        color: #31423B;

        font-size: 12px;
    }

    .ohh-discount-value {
        color: #2D8A62 !important;
    }

    .ohh-cost-divider {
        height: 1px;

        margin: 13px 0;

        background: #E5EBE7;
    }

    .ohh-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 20px;
    }

    .ohh-total-row span {
        display: block;

        color: #18352D;

        font-size: 14px;
        font-weight: 700;
    }

    .ohh-total-row small {
        display: block;

        margin-top: 3px;

        color: #89928E;

        font-size: 10px;
    }

    .ohh-total-row strong {
        color: #0F6E56;

        font-size: 25px;
        font-weight: 700;
    }


    /* SALARY */

    .ohh-salary-panel {
        padding: 25px 27px;
    }

    .ohh-salary-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 20px;

        padding-bottom: 20px;

        border-bottom: 1px solid #E5EBE7;
    }

    .ohh-salary-header h3 {
        margin: 0 0 4px;

        color: #18352D;

        font-size: 18px;
    }

    .ohh-salary-header small {
        color: #7D8782;
        font-size: 11px;
    }

    .ohh-salary-header > strong {
        color: #2D8A62;
        font-size: 25px;
    }

    .ohh-salary-note {
        display: flex;
        gap: 9px;

        margin-top: 16px;
        padding: 12px 14px;

        border-radius: 10px;

        background: #F3F7F5;

        color: #707B76;

        font-size: 10px;
        line-height: 1.55;
    }

    .ohh-salary-note i {
        color: #0F6E56;
    }


    /* =========================================================
    URGENT CARD
    ========================================================= */

    .ohh-urgent-card {
        display: flex;
        align-items: center;

        gap: 16px;

        margin-bottom: 22px;
        padding: 21px 23px;

        border: 1px solid #E7DCC8;
        border-radius: 17px;

        background: #F8F3E8;
    }

    .ohh-urgent-icon {
        width: 45px;
        height: 45px;

        flex: 0 0 45px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: #fff;

        color: #A9782E;

        font-size: 16px;
    }

    .ohh-urgent-content {
        flex: 1;
    }

    .ohh-urgent-content span {
        display: block;

        margin-bottom: 3px;

        color: #A9782E;

        font-size: 9px;
        font-weight: 700;
        letter-spacing: .13em;
    }

    .ohh-urgent-content h3 {
        margin: 0 0 3px;

        color: #403B31;

        font-size: 15px;
    }

    .ohh-urgent-content p {
        margin: 0;

        color: #7A746A;

        font-size: 11px;
    }

    .ohh-urgent-action {
        text-align: right;
    }

    .ohh-urgent-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 10px 15px;

        border-radius: 9px;

        background: #A9782E;
        color: #fff;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition: .2s ease;
    }

    .ohh-urgent-btn:hover {
        color: #fff;

        transform: translateY(-1px);

        background: #8E6323;
    }

    .ohh-urgent-action small {
        display: block;

        margin-top: 7px;

        color: #7B746A;

        font-size: 9px;
    }


    /* =========================================================
    SUBMIT
    ========================================================= */

    .ohh-submit-area {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 20px;

        padding: 23px 25px;

        border: 1px solid #DDE8E2;
        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #F2FAF7,
            #FFFFFF
        );
    }

    .ohh-submit-copy {
        display: flex;
        align-items: center;

        gap: 13px;
    }

    .ohh-submit-icon {
        width: 43px;
        height: 43px;

        flex: 0 0 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #E3F2EB;

        color: #0F6E56;

        font-size: 14px;
    }

    .ohh-submit-copy strong {
        display: block;

        margin-bottom: 3px;

        color: #18352D;

        font-size: 13px;
    }

    .ohh-submit-copy span {
        display: block;

        color: #77827C;

        font-size: 10px;
    }

    .ohh-submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 11px;

        min-width: 205px;

        padding: 14px 20px;

        border: 0;
        border-radius: 10px;

        background: #0F6E56;
        color: #fff;

        font-size: 13px;
        font-weight: 700;

        transition: .2s ease;
    }

    .ohh-submit-btn:hover {
        transform: translateY(-2px);

        background: #0B4A3B;

        box-shadow: 0 9px 20px rgba(15,110,86,.17);
    }

    .ohh-submit-btn i {
        font-size: 11px;

        transition: .2s ease;
    }

    .ohh-submit-btn:hover i {
        transform: translateX(3px);
    }


    /* =========================================================
    ERROR
    ========================================================= */

    .ohh-error-message,
    .ohh-field-error {
        color: #B74D4D;

        font-size: 11px;
    }

    .ohh-error-message {
        display: flex;
        align-items: center;

        gap: 6px;

        margin-top: 9px;
    }

    .ohh-field-error {
        margin-top: 6px;
    }


    /* =========================================================
    RESPONSIVE — TABLET
    ========================================================= */

    @media (max-width: 991.98px) {

        .ohh-booking-hero {
            padding: 38px 35px;
        }

        .ohh-services-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .ohh-package-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .ohh-management-grid {
            grid-template-columns: 1fr;
        }

    }


    /* =========================================================
    RESPONSIVE — MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {

        .ohh-booking-page {
            padding: 18px 16px 50px;
        }


        /* HERO */

        .ohh-booking-hero {
            flex-direction: column;

            align-items: flex-start;

            padding: 32px 24px;

            border-radius: 18px;
        }

        .ohh-booking-hero h1 {
            font-size: 2.35rem;
        }

        .ohh-booking-hero-badge {
            width: 100%;
            min-width: 0;
        }


        /* PROGRESS */

        .ohh-progress-wrapper {
            grid-template-columns: repeat(4, 1fr);

            gap: 5px;

            margin-bottom: 33px;
        }

        .ohh-progress-line {
            display: none;
        }

        .ohh-progress-step {
            flex-direction: column;
            justify-content: center;
            text-align: center;

            gap: 5px;

            padding: 0;
        }

        .ohh-step-number {
            width: 32px;
            height: 32px;
            flex-basis: 32px;
        }

        .ohh-progress-step strong {
            font-size: 9px;
        }

        .ohh-progress-step small {
            display: none;
        }


        /* HEADINGS */

        .ohh-section-heading {
            flex-direction: column;

            align-items: flex-start;

            gap: 5px;
        }

        .ohh-section-heading h2 {
            font-size: 24px;
        }


        /* SERVICES */

        .ohh-services-grid {
            grid-template-columns: repeat(2, 1fr);

            gap: 10px;
        }

        .ohh-service-select-card {
            min-height: 175px;

            padding: 16px;
        }

        .ohh-service-select-body {
            margin-top: 18px;
        }

        .ohh-service-select-body h3 {
            font-size: 14px;
        }

        .ohh-service-select-body p {
            font-size: 11px;
        }


        /* CHOICE */

        .ohh-choice-grid {
            grid-template-columns: 1fr;
        }


        /* FORM */

        .ohh-form-card {
            padding: 18px;
        }

        .ohh-form-grid {
            grid-template-columns: 1fr;
        }

        .ohh-field.full-width {
            grid-column: auto;
        }


        /* PACKAGE */

        .ohh-package-grid {
            grid-template-columns: 1fr;
        }


        /* MANAGEMENT */

        .ohh-management-inner {
            padding: 20px;
        }


        /* SUMMARY */

        .ohh-summary-header {
            flex-direction: column;

            align-items: flex-start;

            padding: 20px;
        }

        .ohh-cost-panel,
        .ohh-salary-panel {
            padding: 20px;
            flex-direction: column;
        }

        .ohh-salary-header{
             flex-direction: column;
            align-items: center;
        }

        .ohh-total-row strong,
        .ohh-salary-header > strong {
            font-size: 21px;
        }


        /* URGENT */

        .ohh-urgent-card {
            align-items: flex-start;

            flex-wrap: wrap;
        }

        .ohh-urgent-action {
            width: 100%;

            text-align: left;

            margin-left: 61px;
        }


        /* SUBMIT */

        .ohh-submit-area {
            flex-direction: column;

            align-items: stretch;
        }

        .ohh-submit-copy {
            align-items: flex-start;
        }

        .ohh-submit-btn {
            width: 100%;
        }

    }


    @media (max-width: 400px) {

        .ohh-services-grid {
            grid-template-columns: 1fr;
        }

        .ohh-service-select-card {
            min-height: 155px;
        }

        .ohh-time-grid {
            grid-template-columns: 1fr;
        }

        .ohh-urgent-action {
            margin-left: 0;
        }

    }

</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('bookingForm');

        const serviceCheckboxes =
            document.querySelectorAll('.service-checkbox');

        const packageSelect =
            document.getElementById('packageSelect');


        /* =====================================================
        STATE / CITY
        ====================================================== */

        const stateCities = {

            'Abia': [
                'Aba',
                'Umuahia',
                'Ohafia',
                'Arochukwu',
                'Isiala Ngwa',
                'Osisioma'
            ],

            'Adamawa': [
                'Yola',
                'Jimeta',
                'Mubi',
                'Numan',
                'Ganye',
                'Madagali'
            ],

            'Akwa Ibom': [
                'Uyo',
                'Eket',
                'Ikot Ekpene',
                'Oron',
                'Abak',
                'Ikot Abasi'
            ],

            'Anambra': [
                'Awka',
                'Onitsha',
                'Nnewi',
                'Ekwulobia',
                'Aguata',
                'Ihiala'
            ],

            'Bauchi': [
                'Bauchi',
                'Azare',
                'Misau',
                "Jama'are",
                'Katagum',
                'Dass'
            ],

            'Bayelsa': [
                'Yenagoa',
                'Ogbia',
                'Brass',
                'Nembe',
                'Sagbama',
                'Ekeremor'
            ],

            'Benue': [
                'Makurdi',
                'Gboko',
                'Otukpo',
                'Katsina-Ala',
                'Vandeikya',
                'Oturkpo'
            ],

            'Borno': [
                'Maiduguri',
                'Biu',
                'Bama',
                'Dikwa',
                'Gubio',
                'Konduga'
            ],

            'Cross River': [
                'Calabar',
                'Ikom',
                'Ogoja',
                'Obudu',
                'Ugep',
                'Akamkpa'
            ],

            'Delta': [
                'Asaba',
                'Warri',
                'Sapele',
                'Ughelli',
                'Agbor',
                'Effurun',
                'Ozoro'
            ],

            'Ebonyi': [
                'Abakaliki',
                'Afikpo',
                'Onueke',
                'Ezzamgbo',
                'Ishielu',
                'Ohaukwu'
            ],

            'Edo': [
                'Benin City',
                'Auchi',
                'Ekpoma',
                'Uromi',
                'Irrua',
                'Sabongida-Ora'
            ],

            'Ekiti': [
                'Ado-Ekiti',
                'Ikere-Ekiti',
                'Oye-Ekiti',
                'Ikole-Ekiti',
                'Ijero-Ekiti',
                'Efon-Alaaye'
            ],

            'Enugu': [
                'Enugu',
                'Nsukka',
                'Agbani',
                'Oji River',
                'Udi',
                'Awgu'
            ],

            'FCT': [
                'Asokoro',
                'Maitama',
                'Garki',
                'Wuse',
                'Wuse 2',
                'Gwarinpa',
                'Jabi',
                'Utako',
                'Kubwa',
                'Lugbe',
                'Karu',
                'Nyanya',
                'Mpape',
                'Lifecamp',
                'Katampe',
                'Durumi',
                'Gudu',
                'Apo',
                'Lokogoma',
                'Galadimawa',
                'Dawaki',
                'Bwari',
                'Kurudu',
                'Karmo',
                'Idu',
                'Gwagwalada',
                'Kuje',
                'Abaji',
                'Kwali',
                'Dei-Dei',
                'Dutse',
                'Jikwoyi',
                'Orozo',
                'Pyakasa',
                'Zuba',
                'Kaura',
                'Mabushi',
                'Guzape',
                'Wuye',
                'Central Area'
            ],

            'Gombe': [
                'Gombe',
                'Kumo',
                'Billiri',
                'Kaltungo',
                'Dukku',
                'Bajoga'
            ],

            'Imo': [
                'Owerri',
                'Orlu',
                'Okigwe',
                'Oguta',
                'Mbaise',
                'Nkwerre'
            ],

            'Jigawa': [
                'Dutse',
                'Hadejia',
                'Gumel',
                'Kazaure',
                'Birnin Kudu',
                'Ringim'
            ],

            'Kaduna': [
                'Kaduna',
                'Zaria',
                'Kafanchan',
                'Kagoro',
                'Kachia',
                'Birnin Gwari'
            ],

            'Kano': [
                'Kano',
                'Fagge',
                'Tarauni',
                'Nassarawa',
                'Ungogo',
                'Dala',
                'Wudil',
                'Bichi'
            ],

            'Katsina': [
                'Katsina',
                'Daura',
                'Funtua',
                'Malumfashi',
                'Dutsin-Ma',
                'Kankia'
            ],

            'Kebbi': [
                'Birnin Kebbi',
                'Argungu',
                'Yauri',
                'Zuru',
                'Jega',
                'Bagudo'
            ],

            'Kogi': [
                'Lokoja',
                'Okene',
                'Idah',
                'Kabba',
                'Ankpa',
                'Ajaokuta'
            ],

            'Kwara': [
                'Ilorin',
                'Offa',
                'Omu-Aran',
                'Jebba',
                'Lafiagi',
                'Pategi'
            ],

            'Lagos': [
                'Ikeja',
                'Victoria Island',
                'Lekki',
                'Ikoyi',
                'Ajah',
                'Surulere',
                'Yaba',
                'Gbagada',
                'Maryland',
                'Ogba',
                'Magodo',
                'Ikorodu',
                'Epe',
                'Badagry',
                'Oshodi',
                'Isolo',
                'Mushin',
                'Apapa',
                'Festac',
                'Amuwo-Odofin',
                'Alimosho',
                'Agege',
                'Ojodu',
                'Berger',
                'Ilupeju',
                'Ogudu',
                'Kosofe',
                'Sangotedo',
                'Oregun',
                'Omole'
            ],

            'Nasarawa': [
                'Lafia',
                'Keffi',
                'Akwanga',
                'Nasarawa',
                'Doma',
                'Toto'
            ],

            'Niger': [
                'Minna',
                'Bida',
                'Suleja',
                'Kontagora',
                'New Bussa',
                'Lapai'
            ],

            'Ogun': [
                'Abeokuta',
                'Sagamu',
                'Ijebu-Ode',
                'Ota',
                'Ilaro',
                'Ifo',
                'Sango-Otta',
                'Mowe',
                'Ibafo'
            ],

            'Ondo': [
                'Akure',
                'Ondo',
                'Owo',
                'Ikare',
                'Ore',
                'Okitipupa'
            ],

            'Osun': [
                'Osogbo',
                'Ile-Ife',
                'Ilesa',
                'Ede',
                'Iwo',
                'Ejigbo'
            ],

            'Oyo': [
                'Ibadan',
                'Ogbomoso',
                'Oyo',
                'Iseyin',
                'Saki',
                'Eruwa',
                'Igbo-Ora'
            ],

            'Plateau': [
                'Jos',
                'Bukuru',
                'Pankshin',
                'Shendam',
                'Langtang',
                'Barkin Ladi'
            ],

            'Rivers': [
                'Port Harcourt',
                'Obio-Akpor',
                'Eleme',
                'Bonny',
                'Degema',
                'Ahoada',
                'Omoku'
            ],

            'Sokoto': [
                'Sokoto',
                'Tambuwal',
                'Bodinga',
                'Gwadabawa',
                'Illela',
                'Wamakko'
            ],

            'Taraba': [
                'Jalingo',
                'Wukari',
                'Bali',
                'Takum',
                'Serti',
                'Gembu'
            ],

            'Yobe': [
                'Damaturu',
                'Potiskum',
                'Gashua',
                'Nguru',
                'Geidam',
                'Buni Yadi'
            ],

            'Zamfara': [
                'Gusau',
                'Kaura Namoda',
                'Talata Mafara',
                'Anka',
                'Maru',
                'Bungudu'
            ]

        };


        const stateSelect =
            document.querySelector('[name="state"]');

        const citySelect =
            document.getElementById('citySelect');

        const oldCity =
            @json(old('city', ''));


        if (stateSelect && citySelect) {

            stateSelect.addEventListener('change', function () {

                const selectedState = this.value;

                citySelect.innerHTML = '';

                if (selectedState) {

                    const cities =
                        stateCities[selectedState] || [];

                    citySelect.innerHTML =
                        '<option value="">Select your area</option>';

                    cities.forEach(function (city) {

                        const opt =
                            document.createElement('option');

                        opt.value = city;
                        opt.textContent = city;

                        if (city === oldCity) {
                            opt.selected = true;
                        }

                        citySelect.appendChild(opt);

                    });

                } else {

                    citySelect.innerHTML =
                        '<option value="">Select a state first</option>';

                }

            });


            if (stateSelect.value) {
                stateSelect.dispatchEvent(
                    new Event('change')
                );
            }

        }


        /* =====================================================
        APARTMENT
        ====================================================== */

        function toggleApartmentField() {

            const apartmentSection =
                document.getElementById('apartmentSection');

            const isCleaningSelected =
                Array.from(serviceCheckboxes).some(cb => {

                    if (!cb.checked) {
                        return false;
                    }

                    const card =
                        cb.closest('.ohh-service-option');

                    if (!card) {
                        return false;
                    }

                    const label =
                        card.querySelector('.ohh-service-select-body h3');

                    return label &&
                        label.textContent.trim().toLowerCase()
                        === 'cleaning service';

                });


            if (apartmentSection) {

                apartmentSection.classList.toggle(
                    'd-none',
                    !isCleaningSelected
                );

            }

        }


        /* =====================================================
        SERVICE SELECTION
        ====================================================== */

        serviceCheckboxes.forEach(cb => {

            cb.addEventListener('change', function () {

                toggleApartmentField();

                updateCostDisplay();

            });

        });


        toggleApartmentField();


        /* =====================================================
        PACKAGE CARD -> HIDDEN SELECT
        ====================================================== */

        const packageCards =
            document.querySelectorAll('.ohh-package-card input');


        packageCards.forEach(radio => {

            radio.addEventListener('change', function () {

                if (packageSelect) {
                    packageSelect.value = this.value;
                }

                updateCostDisplay();

            });

        });


        if (
            packageSelect &&
            packageSelect.value
        ) {

            const matchingPackage =
                document.querySelector(
                    '.ohh-package-card input[value="' +
                    packageSelect.value +
                    '"]'
                );

            if (matchingPackage) {
                matchingPackage.checked = true;
            }

        }


        /* =====================================================
        MANAGEMENT PLAN
        ====================================================== */

        const planCards =
            document.querySelectorAll('.ohh-management-card');

        const planRadios =
            document.querySelectorAll('.management-plan-radio');


        function updatePlanHighlight() {

            planCards.forEach(card => {

                card.classList.remove(
                    'selected-management'
                );

            });


            const selected =
                document.querySelector(
                    '.management-plan-radio:checked'
                );


            if (selected) {

                const card =
                    selected.closest(
                        '.ohh-management-card'
                    );

                if (card) {
                    card.classList.add(
                        'selected-management'
                    );
                }

            }


            updateCostDisplay();

        }


        planRadios.forEach(radio => {

            radio.addEventListener(
                'change',
                updatePlanHighlight
            );

        });


        updatePlanHighlight();


        /* =====================================================
        COST CALCULATION
        ====================================================== */

        function updateCostDisplay() {

            const checkedServices =
                document.querySelectorAll(
                    '.service-checkbox:checked'
                );


            if (
                checkedServices.length === 0 ||
                !packageSelect ||
                !packageSelect.value
            ) {
                return;
            }


            const serviceIds =
                Array.from(checkedServices)
                    .map(cb => cb.value);


            const packageId =
                packageSelect.value;


            const apartmentSelectEl =
                document.querySelector(
                    '[name="apartment_type"]'
                );


            const apartmentType =
                apartmentSelectEl
                    ? apartmentSelectEl.value
                    : null;


            const durationSelectEl =
                document.querySelector(
                    '[name="duration_weeks"]'
                );


            const durationWeeks =
                durationSelectEl &&
                durationSelectEl.value
                    ? parseInt(durationSelectEl.value)
                    : 4;


            const selectedPlan =
                document.querySelector(
                    '.management-plan-radio:checked'
                );


            const managementPlan =
                selectedPlan
                    ? selectedPlan.value
                    : 'client-managed';


            const companyCard =
                document.getElementById(
                    'companyManagedCost'
                );


            const clientCard =
                document.getElementById(
                    'clientManagedCost'
                );


            if (companyCard) {

                companyCard.style.display =
                    managementPlan === 'client-managed'
                        ? 'none'
                        : 'block';

            }


            if (clientCard) {

                clientCard.style.display =
                    managementPlan === 'client-managed'
                        ? 'block'
                        : 'none';

            }


            fetch(
                '{{ route("booking.calculate-cost") }}',
                {
                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,

                        'Accept':
                            'application/json'

                    },

                    body: JSON.stringify({

                        services: serviceIds,

                        package: packageId,

                        apartment_type: apartmentType,

                        management_plan: managementPlan,

                        duration_weeks: durationWeeks

                    })

                }
            )

            .then(response => response.json())

            .then(data => {

                const isOneOff =
                    data.is_one_off || false;

                const months =
                    Number(data.months || 1);


                /* =================================================
                CLIENT MANAGED
                ================================================== */

                if (
                    managementPlan ===
                    'client-managed'
                ) {

                    const staffSalary =
                        Number(
                            data.staff_salary || 0
                        );

                    const breakdown =
                        data.breakdown || [];


                    const salaryEl =
                        document.getElementById(
                            'staffSalaryDisplay'
                        );


                    if (salaryEl) {

                        salaryEl.textContent =
                            isOneOff

                                ? '₦' +
                                staffSalary.toLocaleString() +
                                ' (one-off)'

                                : '₦' +
                                staffSalary.toLocaleString() +
                                '/month';

                    }


                    const daysEl =
                        document.getElementById(
                            'salaryDaysLabel'
                        );


                    if (daysEl) {

                        const pkg =
                            document.querySelector(
                                '#packageSelect option:checked'
                            );

                        daysEl.textContent =
                            isOneOff

                                ? 'Single visit'

                                : (
                                    pkg
                                        ? pkg.textContent.trim()
                                        : ''
                                );

                    }


                    let breakdownHtml =
                        '<div class="mt-3 pt-3 border-top">';


                    breakdownHtml +=
                        '<small class="text-muted d-block mb-2 fw-semibold">' +
                        'Salary breakdown:' +
                        '</small>';


                    breakdown.forEach(function (item) {

                        const label =
                            item.type === 'main'

                                ? item.service +
                                ' (main)'

                                : item.service +
                                ' (×0.5)';


                        breakdownHtml +=
                            '<div class="d-flex justify-content-between mb-1">' +

                                '<small class="text-muted">' +
                                    label +
                                '</small>' +

                                '<small>₦' +
                                    Number(item.cost)
                                        .toLocaleString() +
                                '</small>' +

                            '</div>';

                    });


                    if (
                        !isOneOff &&
                        Number(data.package_cost) > 0
                    ) {

                        breakdownHtml +=
                            '<div class="d-flex justify-content-between mb-1">' +

                                '<small class="text-muted">' +
                                    'Frequency (package)' +
                                '</small>' +

                                '<small>₦' +
                                    Number(data.package_cost)
                                        .toLocaleString() +
                                '</small>' +

                            '</div>';

                    }


                    breakdownHtml +=
                        '<div class="d-flex justify-content-between border-top mt-2 pt-2">' +

                            '<small class="fw-bold">' +
                                (
                                    isOneOff
                                        ? 'One-off visit price'
                                        : 'Monthly staff salary'
                                ) +
                            '</small>' +

                            '<small class="fw-bold text-success">₦' +
                                staffSalary.toLocaleString() +
                            '</small>' +

                        '</div>';


                    if (
                        !isOneOff &&
                        months > 1
                    ) {

                        breakdownHtml +=
                            '<div class="d-flex justify-content-between mt-2 pt-2 border-top">' +

                                '<small class="text-muted">' +
                                    'Over ' +
                                    months +
                                    ' months' +
                                '</small>' +

                                '<small class="text-muted">' +
                                    '₦' +
                                    staffSalary.toLocaleString() +
                                    ' × ' +
                                    months +
                                    ' = ' +
                                    '<span class="fw-semibold">' +
                                        '₦' +
                                        (
                                            staffSalary *
                                            months
                                        ).toLocaleString() +
                                    '</span>' +
                                '</small>' +

                            '</div>';


                        breakdownHtml +=
                            '<small class="text-muted d-block mt-2">' +
                                'You pay your staff directly each month.' +
                            '</small>';

                    }


                    breakdownHtml += '</div>';


                    const breakdownEl =
                        document.getElementById(
                            'salaryBreakdown'
                        );


                    if (breakdownEl) {
                        breakdownEl.innerHTML =
                            breakdownHtml;
                    }


                    return;

                }


                /* =================================================
                COMPANY MANAGED
                ================================================== */

                const clientCost =
                    Number(data.client_cost || 0);

                const clientTotal =
                    Number(data.client_total || 0);

                const platformFee =
                    Number(data.platform_fee || 0);

                const discountPct =
                    Number(
                        data.discount_percentage || 0
                    );

                const discountAmt =
                    Number(
                        data.discount_amount || 0
                    );


                document.getElementById(
                    'baseCostDisplay'
                ).textContent =
                    '₦' +
                    Number(data.base_cost)
                        .toLocaleString();


                document.getElementById(
                    'extraCostDisplay'
                ).textContent =
                    '₦' +
                    Number(data.extra_service_cost)
                        .toLocaleString();


                document.getElementById(
                    'packageCostDisplay'
                ).textContent =
                    isOneOff

                        ? '₦0 (one-off)'

                        : '₦' +
                        Number(data.package_cost)
                            .toLocaleString();


                /* PLATFORM FEE */

                const planRow =
                    document.getElementById(
                        'managementPlanRow'
                    );

                const planCostDisplay =
                    document.getElementById(
                        'managementPlanCostDisplay'
                    );


                if (
                    planRow &&
                    planCostDisplay
                ) {

                    if (platformFee > 0) {

                        planRow.classList.remove(
                            'd-none'
                        );

                        planRow.style.removeProperty(
                            'display'
                        );

                        planCostDisplay.textContent =
                            '₦' +
                            platformFee.toLocaleString() +
                            '/month (20% fee)';

                    } else {

                        planRow.style.display =
                            'none';

                    }

                }


                /* DISCOUNT */

                const discountRow =
                    document.getElementById(
                        'discountRow'
                    );


                if (discountRow) {

                    if (
                        discountPct > 0 &&
                        !isOneOff &&
                        months > 1
                    ) {

                        discountRow.classList.remove(
                            'd-none'
                        );

                        discountRow.style.removeProperty(
                            'display'
                        );


                        const discountDisplay =
                            document.getElementById(
                                'discountDisplay'
                            );


                        if (discountDisplay) {

                            discountDisplay.textContent =
                                '-₦' +
                                discountAmt.toLocaleString() +
                                ' (' +
                                discountPct +
                                '% off total for ' +
                                months +
                                ' months)';

                        }

                    } else {

                        discountRow.style.display =
                            'none';

                    }

                }


                /* GRAND TOTAL */

                const grandTotalDisplay =
                    document.getElementById(
                        'grandTotalDisplay'
                    );

                const grandTotalLabel =
                    document.getElementById(
                        'grandTotalLabel'
                    );


                const estimatedTotal =
                    months > 1
                        ? clientTotal
                        : clientCost;


                if (isOneOff) {

                    if (grandTotalLabel) {

                        grandTotalLabel.textContent =
                            'One-off Total';

                    }

                    if (grandTotalDisplay) {

                        grandTotalDisplay.textContent =
                            '₦' +
                            estimatedTotal.toLocaleString();

                    }

                } else if (months > 1) {

                    const suffix =
                        discountPct > 0

                            ? ' (after ' +
                            discountPct +
                            '% discount)'

                            : '';


                    if (grandTotalLabel) {

                        grandTotalLabel.textContent =
                            'Total for ' +
                            months +
                            ' months' +
                            suffix;

                    }


                    if (grandTotalDisplay) {

                        grandTotalDisplay.textContent =
                            '₦' +
                            estimatedTotal.toLocaleString();

                    }

                } else {

                    if (grandTotalLabel) {

                        grandTotalLabel.textContent =
                            'Monthly Total';

                    }


                    if (grandTotalDisplay) {

                        grandTotalDisplay.textContent =
                            '₦' +
                            estimatedTotal.toLocaleString();

                    }

                }

            })

            .catch(err => {

                console.error(
                    'Cost calculation error:',
                    err
                );

            });

        }


        /* =====================================================
        LISTENERS
        ====================================================== */

        serviceCheckboxes.forEach(
            cb => cb.addEventListener(
                'change',
                updateCostDisplay
            )
        );


        if (packageSelect) {

            packageSelect.addEventListener(
                'change',
                function () {

                    const matchingPackage =
                        document.querySelector(
                            '.ohh-package-card input[value="' +
                            this.value +
                            '"]'
                        );

                    if (matchingPackage) {
                        matchingPackage.checked = true;
                    }

                    updateCostDisplay();

                }
            );

        }


        const apartmentSelectEl =
            document.querySelector(
                '[name="apartment_type"]'
            );


        if (apartmentSelectEl) {

            apartmentSelectEl.addEventListener(
                'change',
                updateCostDisplay
            );

        }


        const durationSelectEl =
            document.querySelector(
                '[name="duration_weeks"]'
            );


        if (durationSelectEl) {

            durationSelectEl.addEventListener(
                'change',
                updateCostDisplay
            );

        }


        // =====================================================
        // COMPANY-MANAGED BEFORE PROCEED FLOW
        // =====================================================

        const companyManagedModalEl =
            document.getElementById('companyManagedBeforeProceedModal');

        const confirmCompanyManagedBtn =
            document.getElementById('confirmCompanyManagedBtn');

        const companyManagedModal =
            companyManagedModalEl && window.bootstrap
                ? new bootstrap.Modal(companyManagedModalEl)
                : null;

        let allowBookingSubmit = false;


        // -----------------------------------------------------
        // FORM SUBMIT
        // -----------------------------------------------------

        if (form) {

            form.addEventListener('submit', function (event) {

                /*
                * Native browser validation happens before this submit
                * handler fires, so required fields have already passed.
                */

                const selectedManagementPlan =
                    document.querySelector(
                        '.management-plan-radio:checked'
                    );

                const managementPlan =
                    selectedManagementPlan
                        ? selectedManagementPlan.value
                        : 'client-managed';


                // -------------------------------------------------
                // COMPANY MANAGED
                // -------------------------------------------------

                if (
                    managementPlan === 'company-managed' &&
                    !allowBookingSubmit
                ) {

                    event.preventDefault();


                    if (companyManagedModal) {

                        companyManagedModal.show();

                    } else {

                        /*
                        * Safety fallback in case Bootstrap's JS is
                        * unavailable.
                        */
                        allowBookingSubmit = true;

                        form.submit();

                    }

                    return;

                }


                    // -------------------------------------------------
                    // NORMAL / CONFIRMED SUBMISSION
                    // -------------------------------------------------

                    const submitBtn =
                        document.getElementById('submitBtn');


                    if (submitBtn) {

                        submitBtn.disabled = true;

                        submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i>' +
                            '<span>Finding staff...</span>';

                    }

                });

            }


                // =====================================================
                // CONFIRM COMPANY-MANAGED MODAL
                // =====================================================

                if (confirmCompanyManagedBtn) {

                    confirmCompanyManagedBtn.addEventListener(
                        'click',
                        function () {

                            allowBookingSubmit = true;


                            const btn = this;


                            btn.disabled = true;


                            btn.innerHTML =
                                '<i class="fas fa-spinner fa-spin"></i>' +
                                '<span>Starting your booking...</span>';


                            if (companyManagedModal) {
                                companyManagedModal.hide();
                            }


                            /*
                            * form.submit() bypasses the submit event, preventing
                            * the company-managed modal from opening a second time.
                            */
                            setTimeout(function () {

                                HTMLFormElement.prototype.submit.call(form);

                            }, 150);

                        }
                    );

                }

    });
</script>

@endsection