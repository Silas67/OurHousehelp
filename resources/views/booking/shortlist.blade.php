@extends('layouts.usermain')

@section('booking-shortlist')

@php
    $isClientManaged = $serviceRequest->isClientManaged();
@endphp

<div class="ohh-shortlist-page">

    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="ohh-shortlist-hero">

        <div class="ohh-shortlist-hero-content">

            <div class="ohh-eyebrow">
                <span class="ohh-eyebrow-line"></span>
                STAFF MATCHING
            </div>

            <h1>
                We've found<br>
                <span>your matches.</span>
            </h1>

            <p>
                Review the staff available for your request and choose
                the person you'd like to move forward with.
            </p>

        </div>


        <div class="ohh-match-summary">

            <div class="ohh-match-summary-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>
                <strong>
                    <span id="staffCount">{{ $shortlist->count() }}</span>
                    {{ $shortlist->count() === 1 ? 'match' : 'matches' }}
                </strong>

                <span>
                    Suitable staff for your request
                </span>
            </div>

        </div>

    </section>


    {{-- =========================================================
        PROGRESS
    ========================================================== --}}
    <div class="ohh-booking-progress">

        <div class="ohh-progress-track"></div>

        <div class="ohh-progress-step completed">
            <span class="ohh-progress-number">
                <i class="fas fa-check"></i>
            </span>

            <div>
                <strong>Service</strong>
                <small>Request submitted</small>
            </div>
        </div>


        <div class="ohh-progress-step completed">
            <span class="ohh-progress-number">
                <i class="fas fa-check"></i>
            </span>

            <div>
                <strong>Staff Matched</strong>
                <small>Suitable staff found</small>
            </div>
        </div>


        <div class="ohh-progress-step active">
            <span class="ohh-progress-number">
                3
            </span>

            <div>
                <strong>Select Staff</strong>
                <small>Choose your preferred staff</small>
            </div>
        </div>


        @unless($isClientManaged)

            <div class="ohh-progress-step">
                <span class="ohh-progress-number">
                    4
                </span>

                <div>
                    <strong>Payment</strong>
                    <small>Complete booking</small>
                </div>
            </div>

        @endunless

    </div>


    {{-- =========================================================
        INTRO / INSTRUCTIONS
    ========================================================== --}}
    <section class="ohh-shortlist-heading">

        <div style="display: flex; justify-content: center; align-items: flex-start; flex-direction: column;">

            <span class="ohh-section-kicker">
                YOUR MATCHES
            </span>

            <h2>
                {{ $isClientManaged
                    ? 'Staff matching your request'
                    : 'Available staff'
                }}
            </h2>

            <p>
                @if($isClientManaged)
                    These staff members match your requirements.
                    You can view their profiles and contact them directly.
                    Viewing full contact details costs 1 credit.
                @else
                    These staff members are available for your request.
                    Select the staff member you'd like us to verify and
                    move forward with.
                @endif
            </p>

        </div>


        @if($isClientManaged)

            <div class="ohh-credit-card">

                <div class="ohh-credit-icon">
                    <i class="fas fa-coins"></i>
                </div>

                <div class="ohh-credit-info">

                    <span>
                        AVAILABLE CREDITS
                    </span>

                    <strong>
                        {{ auth()->user()->getCreditBalance() }}
                    </strong>

                </div>

                <a
                    href="{{ route('credits.index') }}"
                    class="ohh-credit-button">

                    Buy Credits

                </a>

            </div>

        @endif

    </section>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}
    @if(session('info'))

        <div class="ohh-alert ohh-alert-info">

            <div class="ohh-alert-icon">
                <i class="fas fa-info"></i>
            </div>

            <div>
                {{ session('info') }}
            </div>

            <button
                type="button"
                data-bs-dismiss="alert"
                aria-label="Close">

                <i class="fas fa-xmark"></i>

            </button>

        </div>

    @endif


    @if(session('success'))

        <div class="ohh-alert ohh-alert-success">

            <div class="ohh-alert-icon">
                <i class="fas fa-check"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>

            <button
                type="button"
                data-bs-dismiss="alert"
                aria-label="Close">

                <i class="fas fa-xmark"></i>

            </button>

        </div>

    @endif


    {{-- =========================================================
        LIVE STATUS
    ========================================================== --}}
    @unless($isClientManaged)

        <div class="ohh-live-status">

            <span class="ohh-live-dot"></span>
            <span>
                Live matching — checking for new staff every 10 seconds
            </span>
            <i
                class="fas fa-rotate"
                aria-hidden="true"></i>

        </div>

    @endunless


    {{-- =========================================================
        STAFF GRID
    ========================================================== --}}
    @if($shortlist->count() > 0)

        <section class="ohh-staff-section">

            <div
                class="ohh-staff-grid"
                id="staffGrid">

                @foreach($shortlist as $shortlistItem)

                    <div class="ohh-staff-grid-item">

                        @include('booking._staff-card', [
                            'shortlistItem' => $shortlistItem,
                            'serviceRequest' => $serviceRequest
                        ])

                    </div>

                @endforeach

            </div>

        </section>

    @else

        {{-- EMPTY STATE --}}
        <section class="ohh-empty-state">

            <div class="ohh-empty-icon">
                <i class="fas fa-user-clock"></i>
            </div>

            <span class="ohh-section-kicker">
                MATCHING IN PROGRESS
            </span>

            <h2>
                We're still finding the right staff.
            </h2>

            <p>
                We're checking for suitable staff based on your
                requirements. This page will update automatically
                as new matches become available.
            </p>

            @unless($isClientManaged)

                <div class="ohh-empty-live">
                    <span class="ohh-live-dot"></span>
                    Waiting for new matches
                </div>

            @endunless

        </section>

    @endif


    {{-- =========================================================
        CONTINUE
    ========================================================== --}}
    @if($serviceRequest->selected_staff_id)

        <section class="ohh-next-step">

            <div class="ohh-next-step-content">

                <div class="ohh-next-icon">
                    <i class="fas fa-circle-check"></i>
                </div>

                <div>

                    <strong>
                        Staff selected
                    </strong>

                    <span>
                        Continue to review the next step of your booking.
                    </span>

                </div>

            </div>


            <a
                href="{{ route('booking.trust-assurance', $serviceRequest->id) }}"
                class="ohh-next-button">

                Continue

                <i class="fas fa-arrow-right"></i>

            </a>

        </section>

    @endif


    {{-- =========================================================
        CLIENT MANAGED INFO
    ========================================================== --}}
    @if($isClientManaged)

        <div class="ohh-security-note">

            <i class="fas fa-shield-halved"></i>

            <span>
                All staff are verified. Contact details unlock after
                you view a profile and use 1 credit.
            </span>

        </div>

    @endif


    {{-- =========================================================
        CANCEL
    ========================================================== --}}
    <div class="ohh-cancel-area">

        <button
            type="button"
            class="ohh-cancel-link"
            data-bs-toggle="modal"
            data-bs-target="#cancelOrderModal">

            <i class="fas fa-circle-xmark"></i>

            Cancel this booking

        </button>

    </div>

</div>


{{-- =============================================================
    CANCEL MODAL
============================================================= --}}
<div
    class="modal fade"
    id="cancelOrderModal"
    tabindex="-1"
    aria-labelledby="cancelOrderModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content ohh-cancel-modal">

            <div class="modal-body">

                <div class="ohh-cancel-modal-icon">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>


                <h3 id="cancelOrderModalLabel">
                    Cancel this booking?
                </h3>


                <p class="ohh-cancel-modal-intro">
                    Before you cancel, please understand what will happen.
                </p>


                <div class="ohh-cancel-list">

                    <div class="ohh-cancel-item">

                        <div class="ohh-cancel-item-icon credit">
                            <i class="fas fa-coins"></i>
                        </div>

                        <div>
                            <strong>
                                1 credit will be refunded
                            </strong>

                            <span>
                                The credit used for this booking will be
                                returned to your account.
                            </span>
                        </div>

                    </div>


                    <div class="ohh-cancel-item">

                        <div class="ohh-cancel-item-icon notice">
                            <i class="fas fa-bell-slash"></i>
                        </div>

                        <div>
                            <strong>
                                Staff will be notified
                            </strong>

                            <span>
                                Staff who accepted or were notified will
                                be told that the job has been cancelled.
                            </span>
                        </div>

                    </div>


                    <div class="ohh-cancel-item">

                        <div class="ohh-cancel-item-icon danger">
                            <i class="fas fa-trash"></i>
                        </div>

                        <div>
                            <strong>
                                The booking will be cancelled
                            </strong>

                            <span>
                                You will need to start a new booking if
                                you want to find staff again.
                            </span>
                        </div>

                    </div>

                </div>


                <div class="ohh-cancel-warning">

                    <i class="fas fa-circle-info"></i>

                    <span>
                        Just waiting for more staff? Close this dialog.
                        Your booking is still active.
                    </span>

                </div>


                <div class="ohh-cancel-actions">

                    <button
                        type="button"
                        class="ohh-cancel-keep"
                        data-bs-dismiss="modal">

                        <i class="fas fa-arrow-left"></i>

                        Keep my booking

                    </button>


                    <button
                        type="button"
                        class="ohh-cancel-confirm"
                        id="confirmCancelBtn">

                        <i class="fas fa-circle-xmark"></i>

                        Yes, cancel booking

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

    /* =========================================================
    PAGE
    ========================================================= */

    .ohh-shortlist-page {
        max-width: 1180px;
        margin: 0 auto;
        padding: 6px 24px 70px;
        color: #16241F;
    }


    /* =========================================================
    HERO
    ========================================================= */

    .ohh-shortlist-hero {
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
                circle at 85% 18%,
                rgba(167,120,46,.18),
                transparent 34%
            ),
            linear-gradient(
                135deg,
                #0B4A3B,
                #0F6E56
            );

        color: #fff;
    }

    .ohh-shortlist-hero::after {
        content: "";

        position: absolute;

        width: 290px;
        height: 290px;

        right: -120px;
        bottom: -145px;

        border: 1px solid rgba(255,255,255,.08);

        border-radius: 50%;
    }

    .ohh-shortlist-hero-content {
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

    .ohh-shortlist-hero h1 {
        margin: 0;

        font-family: Georgia, serif;

        font-size: clamp(2.3rem, 5vw, 3.8rem);
        line-height: 1.03;

        font-weight: 500;
    }

    .ohh-shortlist-hero h1 span {
        color: #9CE3CB;
    }

    .ohh-shortlist-hero p {
        max-width: 520px;

        margin: 17px 0 0;

        color: rgba(255,255,255,.75);

        font-size: 15px;
        line-height: 1.7;
    }


    /* MATCH SUMMARY */

    .ohh-match-summary {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 13px;

        min-width: 225px;

        padding: 15px 17px;

        border: 1px solid rgba(255,255,255,.13);
        border-radius: 14px;

        background: rgba(255,255,255,.08);

        backdrop-filter: blur(7px);
    }

    .ohh-match-summary-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #9CE3CB;
        color: #0B4A3B;
    }

    .ohh-match-summary strong {
        display: block;

        font-size: 14px;

        margin-bottom: 3px;
    }

    .ohh-match-summary strong span {
        color: #9CE3CB;
    }

    .ohh-match-summary > div:last-child > span {
        display: block;

        color: rgba(255,255,255,.62);

        font-size: 10px;
    }


    /* =========================================================
    PROGRESS
    ========================================================= */

    .ohh-booking-progress {
        position: relative;

        display: grid;
        grid-template-columns: repeat(4, 1fr);

        gap: 8px;

        margin-bottom: 42px;
    }

    .ohh-progress-track {
        position: absolute;

        top: 18px;
        left: 7%;
        right: 7%;

        height: 1px;

        background: #DDE5E0;

        z-index: 0;
    }

    .ohh-progress-step {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;

        gap: 9px;

        padding: 3px 6px;

        background: #FAFCFB;
    }

    .ohh-progress-number {
        width: 36px;
        height: 36px;

        flex: 0 0 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #EEF2F0;
        color: #7C8781;

        font-size: 11px;
        font-weight: 700;
    }

    .ohh-progress-step strong {
        display: block;

        color: #707B75;

        font-size: 11px;
    }

    .ohh-progress-step small {
        display: block;

        margin-top: 2px;

        color: #9AA39F;

        font-size: 9px;
    }

    .ohh-progress-step.completed
    .ohh-progress-number {
        background: #DDEFE7;
        color: #0F6E56;
    }

    .ohh-progress-step.completed strong {
        color: #507265;
    }

    .ohh-progress-step.active
    .ohh-progress-number {
        background: #0F6E56;
        color: #fff;
    }

    .ohh-progress-step.active strong {
        color: #0B4A3B;
    }


    /* =========================================================
    HEADING
    ========================================================= */

    .ohh-shortlist-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;

        gap: 25px;

        margin-bottom: 20px;
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

    .ohh-shortlist-heading h2 {
        margin: 0;

        color: #0B4A3B;

        font-family: Georgia, serif;

        font-size: 27px;
        font-weight: 500;
    }

    .ohh-shortlist-heading p {
        max-width: 720px;

        margin: 8px 0 0;

        color: #707A75;

        font-size: 12px;
        line-height: 1.7;
    }


    /* =========================================================
    CREDITS
    ========================================================= */

    .ohh-credit-card {
        display: flex;
        align-items: center;

        gap: 11px;

        min-width: 265px;

        padding: 11px 12px;

        border: 1px solid #E2E8E4;
        border-radius: 14px;

        background: #fff;

        box-shadow: 0 5px 16px rgba(22,36,31,.04);
    }

    .ohh-credit-icon {
        width: 39px;
        height: 39px;

        flex: 0 0 39px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #F5EDDF;
        color: #A9782E;

        font-size: 14px;
    }

    .ohh-credit-info {
        flex: 1;
    }

    .ohh-credit-info span {
        display: block;

        color: #858E89;

        font-size: 8px;
        font-weight: 700;

        letter-spacing: .1em;
    }

    .ohh-credit-info strong {
        display: block;

        margin-top: 2px;

        color: #18352D;

        font-size: 16px;
    }

    .ohh-credit-button {
        padding: 8px 11px;

        border: 1px solid #D8C49E;
        border-radius: 8px;

        color: #8C6527;

        background: #FCF8EF;

        font-size: 10px;
        font-weight: 700;

        text-decoration: none;

        transition: .2s ease;
    }

    .ohh-credit-button:hover {
        color: #fff;
        background: #A9782E;
    }


    /* =========================================================
    ALERTS
    ========================================================= */

    .ohh-alert {
        display: flex;
        align-items: center;

        gap: 11px;

        margin-bottom: 17px;
        padding: 12px 14px;

        border: 1px solid;
        border-radius: 11px;

        font-size: 11px;
    }

    .ohh-alert button {
        margin-left: auto;

        border: 0;

        background: transparent;

        color: inherit;

        opacity: .55;
    }

    .ohh-alert-info {
        border-color: #D9E9E4;

        background: #F2F9F6;

        color: #44675B;
    }

    .ohh-alert-success {
        border-color: #D5E8DA;

        background: #F2F8F3;

        color: #427653;
    }

    .ohh-alert-icon {
        width: 25px;
        height: 25px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 25px;

        border-radius: 50%;

        background: #fff;

        font-size: 10px;
    }


    /* =========================================================
    LIVE
    ========================================================= */

    .ohh-live-status {
        display: flex;
        justify-content: center;
        align-items: center;

        gap: 7px;

        margin-bottom: 18px;

        color: #7B8580;

        font-size: 10px;
    }

    .ohh-live-dot {
        width: 7px;
        height: 7px;

        display: inline-block;

        border-radius: 50%;

        background: #29A16A;

        box-shadow:
            0 0 0 4px rgba(41,161,106,.10);

        animation: ohh-pulse 1.7s infinite;
    }

    .ohh-live-status i {
        margin-left: 3px;

        color: #9BA59F;

        font-size: 9px;
    }

    @keyframes ohh-pulse {

        0% {
            box-shadow:
                0 0 0 0 rgba(41,161,106,.22);
        }

        70% {
            box-shadow:
                0 0 0 5px rgba(41,161,106,0);
        }

        100% {
            box-shadow:
                0 0 0 0 rgba(41,161,106,0);
        }

    }


    /* =========================================================
    STAFF GRID
    ========================================================= */

    .ohh-staff-section {
        margin-bottom: 25px;
    }

    .ohh-staff-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 18px;
    }

    .ohh-staff-grid-item {
        min-width: 0;
    }


    /*
    * Your existing booking._staff-card partial remains untouched.
    * These rules make the existing card visually fit the new page.
    */

    .ohh-staff-grid-item .staff-card {
        height: 100%;

        overflow: hidden;

        border: 1px solid #DCE7E1 !important;

        border-radius: 18px !important;

        background: #fff;

        box-shadow:
            0 5px 18px rgba(22,36,31,.045) !important;

        transition:
            transform .23s ease,
            box-shadow .23s ease,
            border-color .23s ease;
    }

    .ohh-staff-grid-item .staff-card:hover {
        transform: translateY(-4px);

        border-color: #B6CBBF !important;

        box-shadow:
            0 15px 32px rgba(22,36,31,.09) !important;
    }


    /* =========================================================
    EMPTY STATE
    ========================================================= */

    .ohh-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;

        padding: 62px 25px;

        margin-bottom: 25px;

        border: 1px solid #E1E8E4;
        border-radius: 19px;

        background:
            linear-gradient(
                145deg,
                #F7FBF9,
                #fff
            );

        text-align: center;
    }

    .ohh-empty-icon {
        width: 64px;
        height: 64px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 18px;

        border-radius: 18px;

        background: #E6F0EB;
        color: #0F6E56;

        font-size: 23px;
    }

    .ohh-empty-state h2 {
        margin: 0;

        color: #18352D;

        font-family: Georgia, serif;

        font-size: 25px;
        font-weight: 500;
    }

    .ohh-empty-state p {
        max-width: 520px;

        margin: 10px auto 0;

        color: #747E79;

        font-size: 12px;
        line-height: 1.7;
    }

    .ohh-empty-live {
        display: flex;
        align-items: center;

        gap: 8px;

        margin-top: 20px;

        color: #688077;

        font-size: 10px;
        font-weight: 700;
    }


    /* =========================================================
    NEXT STEP
    ========================================================= */

    .ohh-next-step {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 20px;

        margin-top: 22px;
        padding: 19px 22px;

        border: 1px solid #DCE8E2;
        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                #F2FAF7,
                #fff
            );
    }

    .ohh-next-step-content {
        display: flex;
        align-items: center;

        gap: 12px;
    }

    .ohh-next-icon {
        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 43px;

        border-radius: 12px;

        background: #E1F2E9;
        color: #0F6E56;

        font-size: 17px;
    }

    .ohh-next-step-content strong {
        display: block;

        margin-bottom: 2px;

        color: #18352D;

        font-size: 13px;
    }

    .ohh-next-step-content span {
        display: block;

        color: #78837D;

        font-size: 10px;
    }

    .ohh-next-button {
        display: inline-flex;
        align-items: center;

        gap: 10px;

        padding: 12px 18px;

        border-radius: 10px;

        background: #0F6E56;
        color: #fff;

        font-size: 12px;
        font-weight: 700;

        text-decoration: none;

        transition: .2s ease;
    }

    .ohh-next-button:hover {
        color: #fff;

        transform: translateY(-2px);

        background: #0B4A3B;

        box-shadow: 0 8px 18px rgba(15,110,86,.15);
    }

    .ohh-next-button i {
        font-size: 10px;
    }


    /* =========================================================
    SECURITY NOTE
    ========================================================= */

    .ohh-security-note {
        display: flex;
        justify-content: center;
        align-items: center;

        gap: 8px;

        margin: 18px auto;

        color: #7C8781;

        font-size: 10px;

        text-align: center;
    }

    .ohh-security-note i {
        color: #0F6E56;
    }


    /* =========================================================
    CANCEL
    ========================================================= */

    .ohh-cancel-area {
        display: flex;
        justify-content: center;

        margin-top: 23px;
    }

    .ohh-cancel-link {
        display: inline-flex;
        align-items: center;

        gap: 6px;

        border: 0;

        background: transparent;

        color: #A16666;

        font-size: 10px;
        font-weight: 700;

        transition: .2s ease;
    }

    .ohh-cancel-link:hover {
        color: #963E3E;
    }

    .ohh-cancel-link i {
        font-size: 11px;
    }


    /* =========================================================
    CANCEL MODAL
    ========================================================= */

    .ohh-cancel-modal {
        overflow: hidden;

        border: 0;

        border-radius: 20px;

        box-shadow:
            0 25px 70px rgba(20,36,30,.18);
    }

    .ohh-cancel-modal .modal-body {
        padding: 31px;
    }

    .ohh-cancel-modal-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0 auto 17px;

        border-radius: 16px;

        background: #FCEFEF;
        color: #B65B5B;

        font-size: 20px;
    }

    .ohh-cancel-modal h3 {
        margin: 0;

        color: #263830;

        font-family: Georgia, serif;

        font-size: 24px;
        font-weight: 500;

        text-align: center;
    }

    .ohh-cancel-modal-intro {
        margin: 9px 0 22px;

        color: #7B8580;

        font-size: 11px;

        text-align: center;
    }

    .ohh-cancel-list {
        display: flex;
        flex-direction: column;

        gap: 13px;
    }

    .ohh-cancel-item {
        display: flex;

        gap: 11px;

        padding: 12px;

        border-radius: 11px;

        background: #F8FAF9;
    }

    .ohh-cancel-item-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex: 0 0 34px;

        border-radius: 10px;

        font-size: 12px;
    }

    .ohh-cancel-item-icon.credit {
        background: #F5EDDF;
        color: #A9782E;
    }

    .ohh-cancel-item-icon.notice {
        background: #FFF6DF;
        color: #B27B27;
    }

    .ohh-cancel-item-icon.danger {
        background: #FCEFEF;
        color: #B65B5B;
    }

    .ohh-cancel-item strong {
        display: block;

        margin-bottom: 2px;

        color: #34423C;

        font-size: 11px;
    }

    .ohh-cancel-item span {
        display: block;

        color: #7D8782;

        font-size: 10px;

        line-height: 1.5;
    }

    .ohh-cancel-warning {
        display: flex;

        gap: 8px;

        margin-top: 16px;
        padding: 11px 12px;

        border: 1px solid #EBDDBE;
        border-radius: 10px;

        background: #FBF7ED;

        color: #7E7055;

        font-size: 10px;

        line-height: 1.5;
    }

    .ohh-cancel-warning i {
        margin-top: 2px;

        color: #A9782E;
    }

    .ohh-cancel-actions {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 9px;

        margin-top: 21px;
    }

    .ohh-cancel-keep,
    .ohh-cancel-confirm {
        min-height: 44px;

        border-radius: 10px;

        font-size: 11px;
        font-weight: 700;
    }

    .ohh-cancel-keep {
        border: 1px solid #D7E0DB;

        background: #fff;

        color: #4F6159;
    }

    .ohh-cancel-confirm {
        border: 0;

        background: #A65353;

        color: #fff;
    }

    .ohh-cancel-keep i,
    .ohh-cancel-confirm i {
        margin-right: 5px;
    }

    .ohh-cancel-keep:hover {
        background: #F5F8F6;
    }

    .ohh-cancel-confirm:hover {
        background: #8F4343;
    }


    /* =========================================================
    TABLET
    ========================================================= */

    @media (max-width: 991.98px) {

        .ohh-staff-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .ohh-shortlist-hero {
            padding: 38px 35px;
        }

        .ohh-shortlist-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .ohh-credit-card {
            width: 100%;
            max-width: 360px;
        }

    }


    /* =========================================================
    MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {

        .ohh-shortlist-page {
            padding: 18px 16px 50px;
        }


        /* HERO */

        .ohh-shortlist-hero {
            flex-direction: column;

            align-items: flex-start;

            padding: 32px 24px;

            border-radius: 18px;
        }

        .ohh-shortlist-hero h1 {
            font-size: 2.35rem;
        }

        .ohh-match-summary {
            width: 100%;
            min-width: 0;
        }


        /* PROGRESS */

        .ohh-booking-progress {
            grid-template-columns: repeat(4, 1fr);

            gap: 4px;

            margin-bottom: 32px;
        }

        .ohh-progress-track {
            display: none;
        }

        .ohh-progress-step {
            flex-direction: column;

            justify-content: center;

            gap: 4px;

            padding: 0;

            text-align: center;
        }

        .ohh-progress-number {
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


        /* HEADING */

        .ohh-shortlist-heading h2 {
            font-size: 24px;
        }


        /* STAFF */

        .ohh-staff-grid {
            grid-template-columns: 1fr;

            gap: 14px;
        }


        /* LIVE */

        .ohh-live-status {
            justify-content: flex-start;

            line-height: 1.5;
        }


        /* CONTINUE */

        .ohh-next-step {
            flex-direction: column;

            align-items: stretch;
        }

        .ohh-next-button {
            justify-content: center;

            width: 100%;
        }


        /* MODAL */

        .ohh-cancel-modal .modal-body {
            padding: 24px 19px;
        }

        .ohh-cancel-actions {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 400px) {

        .ohh-shortlist-page {
            padding-left: 12px;
            padding-right: 12px;
        }

        .ohh-shortlist-hero {
            padding: 28px 19px;
        }

        .ohh-shortlist-hero h1 {
            font-size: 2.1rem;
        }

        .ohh-credit-card {
            flex-wrap: wrap;
        }

        .ohh-credit-button {
            width: 100%;

            text-align: center;
        }

    }

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       CANCEL BOOKING
    ====================================================== */

    const cancelBtn =
        document.getElementById('confirmCancelBtn');


    if (cancelBtn) {

        cancelBtn.addEventListener(
            'click',
            function () {

                const btn = this;


                btn.disabled = true;


                btn.innerHTML =
                    '<i class="fas fa-spinner fa-spin me-1"></i>' +
                    ' Cancelling...';


                fetch(
                    '{{ route("booking.cancel-order", $serviceRequest->id) }}',
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
                                'application/json',

                        },

                    }
                )

                .then(r => r.json())

                .then(data => {

                    if (data.success) {

                        window.location.href =
                            data.redirect;

                    } else {

                        alert(
                            data.message ||
                            'Unable to cancel this booking.'
                        );


                        btn.disabled = false;


                        btn.innerHTML =
                            '<i class="fas fa-circle-xmark me-1"></i>' +
                            ' Yes, cancel booking';

                    }

                })

                .catch(() => {

                    alert(
                        'Something went wrong. Please try again.'
                    );


                    btn.disabled = false;


                    btn.innerHTML =
                        '<i class="fas fa-circle-xmark me-1"></i>' +
                        ' Yes, cancel booking';

                });

            }
        );

    }


    /* =====================================================
       PREVENT BACK NAVIGATION
    ====================================================== */

    function noBack() {

        window.history.forward();

    }


    /*
     * Preserve the previous behavior.
     * Calling this once is enough for this page.
     */

    noBack();


    /* =====================================================
       LIVE STAFF POLLING
    ====================================================== */

    @unless($isClientManaged)

        const refreshTimer =
            setInterval(function () {

                fetch(
                    window.location.href,
                    {
                        headers: {
                            'X-Requested-With':
                                'XMLHttpRequest'
                        }
                    }
                )

                .then(r => r.text())

                .then(html => {

                    const doc =
                        new DOMParser()
                            .parseFromString(
                                html,
                                'text/html'
                            );


                    const newGrid =
                        doc.getElementById(
                            'staffGrid'
                        );


                    const newCount =
                        doc.getElementById(
                            'staffCount'
                        );


                    const currentCount =
                        document.getElementById(
                            'staffCount'
                        );


                    if (
                        newGrid &&
                        newCount &&
                        currentCount
                    ) {

                        const newNum =
                            parseInt(
                                newCount.textContent
                            );


                        const currentNum =
                            parseInt(
                                currentCount.textContent
                            );


                        if (
                            Number.isFinite(newNum) &&
                            newNum > currentNum
                        ) {

                            const currentGrid =
                                document.getElementById(
                                    'staffGrid'
                                );


                            if (currentGrid) {

                                currentGrid.innerHTML =
                                    newGrid.innerHTML;

                            }

                            currentCount.textContent =
                                newNum;
                        }
                    }

                })

                .catch(() => {});

            }, 10000);

    @endunless

});

</script>

@endsection