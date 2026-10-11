
@extends('layouts.applicantmain')

@section('applicantdashboard')
@php
    $applicant = Auth::user();
    $completion = $applicant->profileCompletionData();
    $isApproved = $applicant->approval_status === 'approved';
@endphp

<div class="ohh-applicant-dashboard">

    {{-- Welcome Hero --}}
    <section class="ohh-applicant-hero">
        <div class="ohh-hero-content">
            <div class="ohh-eyebrow">
                <i class="fas fa-shield-alt" style="color: #AB89F5;"></i>
                OURHOUSEHELP CAREERS
            </div>

            <h1>Welcome back, {{ $applicant->name }}</h1>
            <p>
                Manage your profile, explore job opportunities and keep
                your application up to date.
            </p>

            <div class="ohh-hero-meta">
                <span class="ohh-status-pill {{ $isApproved ? 'is-approved' : 'is-applicant' }}">
                    <i class="fas {{ $isApproved ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $isApproved ? 'Approved Staff' : 'Applicant Account' }}
                </span>

                <span class="ohh-hero-note">
                    <i class="fas fa-user-check" style="color: #AB89F5;"></i>
                    Your next opportunity starts here
                </span>
            </div>
        </div>

        <div class="ohh-hero-decoration">
            <i class="fas fa-user-tie" style="color: #AB89F5;"></i>
        </div>
    </section>

    {{-- Profile Completion --}}
    @if($completion['percentage'] < 100 && !$isApproved)
        <section class="ohh-completion-card">
            <div class="ohh-completion-top">
                <div class="ohh-completion-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="ohh-section-kicker">YOUR PROFILE</div>
                    <h2>Complete your profile</h2>
                    <p>
                        {{ $completion['completed'] }} of {{ $completion['total'] }}
                        steps completed. A complete profile helps you get ready
                        for suitable opportunities.
                    </p>
                </div>

                <div class="ohh-completion-percent">
                    {{ $completion['percentage'] }}%
                </div>
            </div>

            <div class="ohh-progress-track">
                <div class="ohh-progress-fill"
                     style="width: {{ $completion['percentage'] }}%">
                </div>
            </div>

            <div class="ohh-checklist">
                @foreach($completion['steps'] as $step)
                    <a href="{{ $step['url'] }}"
                       class="ohh-checklist-item {{ $step['done'] ? 'is-done' : '' }}">
                        <span class="ohh-check-icon">
                            <i class="{{ $step['done'] ? 'fas fa-check' : $step['icon'] }}"></i>
                        </span>
                        <span>{{ $step['label'] }}</span>
                        <i class="fas fa-arrow-right ohh-check-arrow"></i>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('applicantprofile', ['id' => $applicant->id]) }}"
               class="ohh-primary-button">
                Complete Profile
                <i class="fas fa-arrow-right"></i>
            </a>
        </section>
    @endif

    {{-- Availability --}}
    <section class="ohh-availability-card">
        <div class="ohh-availability-icon">
            <i class="fas fa-eye"></i>
        </div>

        <div class="ohh-availability-copy">
            <h3>Job Search Visibility</h3>
            <p>
                Control whether clients can see you when searching for suitable staff.
            </p>
        </div>

        <span class="ohh-availability-status {{ $availability ? 'is-enabled' : 'is-disabled' }}">
            <span class="ohh-status-dot"></span>
            {{ $availability ? 'Enabled' : 'Disabled' }}
        </span>
    </section>

    {{-- Quick Links --}}
    <div class="ohh-dashboard-heading">
        <div>
            <div class="ohh-section-kicker">YOUR WORKSPACE</div>
            <h2>Manage your account</h2>
            <p>Everything you need to manage your work and profile.</p>
        </div>
    </div>

    <div class="ohh-quick-links">
        <a href="{{ route('applicant.positions') }}" class="ohh-action-card">
            <div class="ohh-action-icon icon-green">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="ohh-action-content">
                <h3>Job Requests</h3>
                <p>Explore available job requests and accept suitable opportunities.</p>
            </div>
            <span class="ohh-action-arrow"><i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="{{ route('applicant.specialDuties') }}" class="ohh-action-card">
            <div class="ohh-action-icon icon-gold">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="ohh-action-content">
                <h3>Special Duties</h3>
                <p>View and manage your assigned special duties.</p>
            </div>
            <span class="ohh-action-arrow"><i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="{{ route('applicantprofile', ['id' => $applicant->id]) }}"
           class="ohh-action-card">
            <div class="ohh-action-icon icon-dark">
                <i class="fas fa-user-edit"></i>
            </div>
            <div class="ohh-action-content">
                <h3>My Profile</h3>
                <p>Update your personal information and preferences.</p>
            </div>
            <span class="ohh-action-arrow"><i class="fas fa-arrow-right"></i></span>
        </a>
    </div>

    <div class="ohh-dashboard-footer">
        <i class="fas fa-lock"></i>
        Your OurHouseHelp account helps you manage your application and work opportunities.
    </div>
</div>

<style>
    .ohh-applicant-dashboard {
        --ohh-green: #0B4A3B;
        --ohh-green-light: #0F6E56;
        --ohh-gold: #C49A45;
        --ohh-cream: #F7F6F0;
        --ohh-text: #20332D;
        --ohh-muted: #74817B;
        max-width: 1240px;
        margin: 0 auto;
        padding: 28px 22px 48px;
        color: var(--ohh-text);
    }

    .ohh-applicant-dashboard * {
        box-sizing: border-box;
    }

    .ohh-applicant-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        overflow: hidden;
        padding: 38px 40px;
        margin-bottom: 26px;
        border-radius: 22px;
        color: #fff;
        background: linear-gradient(120deg, #4A239E, #7E4AED);
        box-shadow: 0 12px 30px rgba(11, 74, 59, .12);
    }

    .ohh-hero-content {
        position: relative;
        z-index: 1;
        max-width: 690px;
    }

    .ohh-eyebrow,
    .ohh-section-kicker {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.6px;
    }

    .ohh-eyebrow {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
        color: #E5C77F;
    }

    .ohh-applicant-hero h1 {
        margin: 0 0 12px;
        font-size: clamp(26px, 3vw, 38px);
        line-height: 1.2;
        font-weight: 750;
        color: #fff;
    }

    .ohh-applicant-hero p {
        max-width: 590px;
        margin: 0;
        color: rgba(255,255,255,.78);
        line-height: 1.7;
        font-size: 14px;
    }

    .ohh-hero-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-top: 23px;
    }

    .ohh-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .ohh-status-pill.is-approved {
        background: rgba(216, 255, 232, .14);
        color: #C7F5D8;
        border: 1px solid rgba(199,245,216,.3);
    }

    .ohh-status-pill.is-applicant {
        background: rgba(255, 222, 160, .14);
        color: #FFE0A0;
        border: 1px solid rgba(255,224,160,.3);
    }

    .ohh-hero-note {
        color: rgba(255,255,255,.72);
        font-size: 12px;
    }

    .ohh-hero-decoration {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 142px;
        height: 142px;
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        color: rgba(230,199,127,.9);
        font-size: 60px;
    }

    .ohh-completion-card,
    .ohh-availability-card {
        background: #fff;
        border: 1px solid #E8EDE9;
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(20,50,38,.04);
    }

    .ohh-completion-card {
        padding: 25px;
        margin-bottom: 25px;
    }

    .ohh-completion-top {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .ohh-completion-icon,
    .ohh-availability-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #EAF5EF;
        color: var(--ohh-green);
        font-size: 20px;
    }

    .ohh-section-kicker {
        color: var(--ohh-green-light);
        margin-bottom: 5px;
    }

    .ohh-completion-top h2,
    .ohh-dashboard-heading h2 {
        margin: 0 0 6px;
        font-size: 21px;
        font-weight: 750;
        color: var(--ohh-green);
    }

    .ohh-completion-top p,
    .ohh-dashboard-heading p {
        margin: 0;
        color: var(--ohh-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .ohh-completion-percent {
        margin-left: auto;
        font-size: 24px;
        font-weight: 800;
        color: var(--ohh-green);
    }

    .ohh-progress-track {
        height: 8px;
        margin: 22px 0;
        overflow: hidden;
        border-radius: 20px;
        background: #EAF0EC;
    }

    .ohh-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #0F6E56, #C49A45);
        transition: width .4s ease;
    }

    .ohh-checklist {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }

    .ohh-checklist-item {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
        padding: 12px;
        border: 1px solid #E7ECE8;
        border-radius: 11px;
        color: #47564F;
        font-size: 12px;
        text-decoration: none;
        transition: border-color .2s, background .2s;
    }

    .ohh-checklist-item:hover {
        border-color: #A5CDBB;
        background: #F5FAF7;
        color: var(--ohh-green);
    }

    .ohh-checklist-item.is-done {
        background: #F0F8F3;
        border-color: #D3E8DB;
    }

    .ohh-check-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 25px;
        height: 25px;
        border-radius: 8px;
        background: #EDF1EE;
        color: #7C8982;
    }

    .ohh-checklist-item.is-done .ohh-check-icon {
        background: #D8EDE0;
        color: var(--ohh-green);
    }

    .ohh-check-arrow {
        margin-left: auto;
        color: #A3ADA6;
        font-size: 10px;
    }

    .ohh-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 11px 18px;
        border-radius: 10px;
        background: var(--ohh-green);
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: background .2s, transform .2s;
    }

    .ohh-primary-button:hover {
        background: #07372C;
        color: #fff;
        transform: translateY(-1px);
    }

    .ohh-availability-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 22px 24px;
        margin-bottom: 34px;
    }

    .ohh-availability-copy {
        flex: 1;
    }

    .ohh-availability-copy h3 {
        margin: 0 0 5px;
        font-size: 16px;
        font-weight: 750;
        color: var(--ohh-green);
    }

    .ohh-availability-copy p {
        margin: 0;
        font-size: 13px;
        line-height: 1.5;
        color: var(--ohh-muted);
    }

    .ohh-availability-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .ohh-availability-status.is-enabled {
        background: #EAF7EE;
        color: #197044;
    }

    .ohh-availability-status.is-disabled {
        background: #FFF0F0;
        color: #B42318;
    }

    .ohh-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .ohh-dashboard-heading {
        margin-bottom: 18px;
    }

    .ohh-quick-links {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .ohh-action-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-width: 0;
        padding: 23px 20px;
        border: 1px solid #E6ECE8;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(20,50,38,.035);
        text-decoration: none;
        color: var(--ohh-text);
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }

    .ohh-action-card:hover {
        transform: translateY(-4px);
        border-color: #B6D5C4;
        box-shadow: 0 12px 28px rgba(20,50,38,.09);
        color: var(--ohh-text);
    }

    .ohh-action-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        font-size: 19px;
    }

    .icon-green {
        background: #E7F4EC;
        color: #0B6A4C;
    }

    .icon-gold {
        background: #FBF1DB;
        color: #9B711F;
    }

    .icon-dark {
        background: #EAF0F2;
        color: #355A64;
    }

    .ohh-action-content {
        flex: 1;
        min-width: 0;
    }

    .ohh-action-content h3 {
        margin: 2px 0 8px;
        color: var(--ohh-green);
        font-size: 15px;
        font-weight: 750;
    }

    .ohh-action-content p {
        margin: 0;
        color: var(--ohh-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .ohh-action-arrow {
        margin-top: 5px;
        color: #9AA79F;
        font-size: 12px;
        transition: color .2s, transform .2s;
    }

    .ohh-action-card:hover .ohh-action-arrow {
        color: var(--ohh-green);
        transform: translateX(3px);
    }

    .ohh-dashboard-footer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        margin-top: 30px;
        color: #87938C;
        text-align: center;
        font-size: 12px;
    }

    .ohh-dashboard-footer i {
        color: var(--ohh-green-light);
    }

    @media (max-width: 991px) {
        .ohh-applicant-dashboard {
            padding: 22px 18px 38px;
        }

        .ohh-quick-links {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ohh-checklist {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ohh-hero-decoration {
            width: 105px;
            height: 105px;
            font-size: 44px;
        }
    }

    @media (max-width: 575px) {
        .ohh-applicant-dashboard {
            padding: 16px 12px 30px;
        }

        .ohh-applicant-hero {
            padding: 27px 22px;
            border-radius: 17px;
        }

        .ohh-hero-decoration {
            display: none;
        }

        .ohh-applicant-hero h1 {
            font-size: 27px;
        }

        .ohh-hero-note {
            width: 100%;
        }

        .ohh-completion-card {
            padding: 19px 15px;
        }

        .ohh-completion-top {
            align-items: flex-start;
            gap: 11px;
        }

        .ohh-completion-icon {
            width: 39px;
            height: 39px;
            font-size: 17px;
        }

        .ohh-completion-top h2,
        .ohh-dashboard-heading h2 {
            font-size: 18px;
        }

        .ohh-completion-percent {
            font-size: 19px;
        }

        .ohh-checklist {
            grid-template-columns: 1fr;
        }

        .ohh-availability-card {
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 18px;
        }

        .ohh-availability-status {
            margin-left: 63px;
        }

        .ohh-quick-links {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .ohh-action-card {
            padding: 18px 15px;
        }

        .ohh-dashboard-footer {
            align-items: flex-start;
            line-height: 1.6;
        }
    }
</style>
@endsection
