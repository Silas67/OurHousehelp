
@extends('layouts.main')
@section('ourLiveInServices')

@php
    $isSkilledWorkerPage = Route::currentRouteName() === 'ourSkilledWorkerServices';
    $serviceTitle = $isSkilledWorkerPage ? 'Skilled Workers' : 'Live-In Staff';
    $hasActiveSubscription = Auth::check()
        && isset($hasApprovedPayment)
        && $hasApprovedPayment;
@endphp


    <section class="livein-page">
        <div class="container">

            {{-- Page Hero --}}
            <div class="livein-hero">
                <div class="livein-hero-content" style=" text-align:left; padding: 12px; border-radius: 10px;">
                    <span class="livein-eyebrow">
                        <i class="fas fa-house-user me-2"></i>
                        OUR SERVICES
                    </span>

                    <h1>Find the Right Help for Your Home</h1>

                    <p>
                        Discover Live-In Staff who can support your household's
                        daily needs. Search available profiles, compare experience,
                        and find someone who suits your preferences.
                    </p>

                    <div class="livein-hero-tags">
                        <span><i class="fas fa-search me-2"></i>Find Staff</span>
                        <span><i class="fas fa-user-check me-2"></i>Review Profiles</span>
                        <span><i class="fas fa-home me-2"></i>Live-In Support</span>
                    </div>
                </div>

                <div class="livein-hero-decoration" aria-hidden="true">
                    <i class="fas fa-house-user"></i>
                </div>
            </div>

            {{-- Important Subscription Warning --}}
            <div class="livein-subscription-warning" role="alert">
                <div class="livein-warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div>
                    <h5>Important: Live-In Staff Subscription Only</h5>
                    <p>
                        Any subscription purchased through this Live-In section
                        applies <strong>only to Live-In Staff services</strong>.
                        It does not cover Live-Out Staff, Special Duties, or other
                        services. Please confirm that you are purchasing the
                        correct subscription before proceeding to payment.
                    </p>
                </div>
            </div>

            {{-- Service Information --}}
            <div class="livein-intro-card">
                <div class="livein-intro-icon">
                    <i class="fas fa-heart"></i>
                </div>

                <div class="livein-intro-content">
                    <h2>Tailored Live-In Support for Your Home</h2>

                    <span class="livein-section-line"></span>

                    <p>
                        Our House Help offers live-in services designed to meet
                        your household needs. Clients are responsible for the
                        complete vetting process, including verification with
                        two guarantors, ensuring flexibility and personalized
                        support. Applicants and clients operate independently,
                        allowing for a customized arrangement.
                    </p>

                    <p class="mb-0">
                        Clients also have the right to train their staff to better
                        suit their preferences and requirements, ensuring an
                        enhanced service experience.
                    </p>
                </div>
            </div>

            {{-- Staff Search and Results --}}
            <div class="livein-directory" style="text-align:center; padding: 12px; border-radius: 10px;">
                <div class="livein-directory-heading">
                    <div class="livein-directory-ico">
                        <!-- <i class="fas fa-users"></i> -->
                    </div>

                    <div>
                        <span class="livein-eyebrow">STAFF DIRECTORY</span>
                        <h2>Explore Available Staff</h2>
                        <p>
                            @if ($isSkilledWorkerPage)
                                Filter by skill, state of residence, or LGA to discover workers
                                that match your requirements.
                            @else
                                Filter by gender and state of residence to discover LiveIn staffs
                                who match your household requirements.
                            @endif
                        </p>
                    </div>
                </div>

                @include('partials._searchForHousehelpsorskills')
            </div>

        </div>
    </section>

    <style>
        .livein-page {
            padding: 55px 0 75px;
            background: #f7f9f8;
            color: #183b32;
        }

        .livein-page * {
            box-sizing: border-box;
        }

        .livein-hero {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            overflow: hidden;
            min-height: 270px;
            padding: 45px;
            margin-bottom: 26px;
            background: linear-gradient(120deg, #123e31 0%, #1d5c47 68%, #28745a 100%);
            border-radius: 22px;
            color: #fff;
        }

        .livein-hero-content {
            position: relative;
            z-index: 1;
            max-width: 720px;
            
        }

        .livein-eyebrow {
            display: inline-block;
            margin-bottom: 12px;
            color: #6739C9;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            
        }

        .livein-hero .livein-eyebrow {
            color: #d9caff;
            
        }

        .livein-hero h1 {
            max-width: 650px;
            margin: 0 0 15px;
            color: #fff;
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 800;
            line-height: 1.2;
        }

        .livein-hero-content > p {
            max-width: 650px;
            margin-bottom: 22px;
            color: rgba(255, 255, 255, .85);
            font-size: 15px;
            line-height: 1.8;
        }

        .livein-hero-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .livein-hero-tags span {
            padding: 9px 12px;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 30px;
            background: rgba(255, 255, 255, .09);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
        }

        .livein-hero-decoration {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 150px;
            width: 150px;
            height: 150px;
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 35px;
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .88);
            font-size: 75px;
            transform: rotate(4deg);
        }

        .livein-subscription-warning {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 22px 24px;
            margin-bottom: 26px;
            background: #fff8e7;
            border: 1px solid #f0d48a;
            border-left: 5px solid #d99a13;
            border-radius: 12px;
        }

        .livein-warning-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 42px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffedbd;
            color: #926200;
            font-size: 18px;
        }

        .livein-subscription-warning h5 {
            margin: 1px 0 8px;
            color: #795300;
            font-size: 16px;
            font-weight: 800;
        }

        .livein-subscription-warning p {
            margin: 0;
            color: #765f32;
            font-size: 13px;
            line-height: 1.8;
        }

        .livein-intro-card {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 30px;
            margin-bottom: 50px;
            border: 1px solid #e5ebe7;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 28px rgba(20, 55, 43, .04);
        }

        .livein-intro-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 50px;
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: #f0eaff;
            color: #6739C9;
            font-size: 22px;
        }

        .livein-intro-content {
            flex: 1;
            min-width: 0;
        }

        .livein-intro-content h2,
        .livein-directory-heading h2 {
            margin: 0 0 13px;
            color: #183b32;
            font-size: clamp(22px, 3vw, 29px);
            font-weight: 800;
        }

        .livein-section-line {
            display: block;
            width: 55px;
            height: 4px;
            margin-bottom: 17px;
            border-radius: 4px;
            background: #6739C9;
        }

        .livein-intro-content p {
            color: #6b7c73;
            font-size: 14px;
            line-height: 1.9;
        }

        .livein-directory {
            padding: 30px;
            border: 1px solid #e5ebe7;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 8px 28px rgba(20, 55, 43, .04);
        }

        .livein-directory-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding-bottom: 22px;
            margin-bottom: 25px;
            border-bottom: 1px solid #edf0ed;
        }

        .livein-directory-heading .livein-eyebrow {
            margin-bottom: 8px;
        }

        .livein-directory-heading h2 {
            margin-bottom: 8px;
        }

        .livein-directory-heading p {
            margin: 0;
            color: #758179;
            font-size: 14px;
        }

        .livein-directory-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 52px;
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #eaf5ef;
            color: #1d6b4d;
            font-size: 22px;
        }

        @media (max-width: 767px) {
            .livein-page {
                padding: 30px 0 45px;
            }

            .livein-hero {
                min-height: auto;
                padding: 28px 23px;
                border-radius: 16px;
            }

            .livein-hero-decoration {
                display: none;
            }

            .livein-hero h1 {
                font-size: 30px;
            }

            .livein-hero-content > p {
                font-size: 14px;
            }

            .livein-subscription-warning {
                gap: 12px;
                padding: 17px;
            }

            .livein-warning-icon {
                flex-basis: 36px;
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .livein-subscription-warning h5 {
                font-size: 14px;
                line-height: 1.5;
            }

            .livein-intro-card {
                flex-direction: column;
                padding: 22px;
                margin-bottom: 32px;
            }

            .livein-directory {
                padding: 20px 15px;
                border-radius: 14px;
            }

            .livein-directory-heading {
                align-items: flex-start;
            }

            .livein-directory-icon {
                display: none;
            }
        }
    </style>
@endsection
