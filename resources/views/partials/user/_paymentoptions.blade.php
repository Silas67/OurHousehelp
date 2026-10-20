
<section class="about-section payment-page" id="about">
    <div class="container">

        {{-- Page Header --}}
        <div class="row text-center mb-4">
            <div class="col-lg-8 offset-lg-2">
                <div class="payment-header">
                    <span class="payment-eyebrow">LIVE-IN STAFF SERVICES</span>
                    <h2>Select a Payment Plan</h2>
                    <span class="payment-title-line"></span>
                    <p>
                        Choose a subscription plan that suits your needs and
                        unlock access to our Live-In Staff services.
                    </p>
                </div>
            </div>
        </div>

       

        {{-- Payment Plans Heading --}}
       

        {{-- Payment Plans --}}
        <div class="payment-plan-section">
            <div class="row justify-content-center">
                @foreach ($plans as $plan)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="payment-plan-card">

                            <div class="plan-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>

                            <h4 class="plan-name">
                                {{ $plan->plan_name }}
                            </h4>

                            <div class="plan-price">
                                <span class="currency">₦</span>{{ number_format($plan->cost, 2) }}
                            </div>

                            @if ($plan->about)
                                <p class="plan-description">
                                    {{ $plan->about }}
                                </p>
                            @endif

                            <div class="plan-divider"></div>

                            <div class="plan-details">
                                <div class="plan-detail">
                                    <i class="fas fa-clock"></i>
                                    <span>
                                        <strong>Duration</strong>
                                        <span>
                                            @switch($plan->duration)
                                                @case(1)
                                                    One Day
                                                    @break
                                                @case(7)
                                                    One Week
                                                    @break
                                                @case(30)
                                                    One Month
                                                    @break
                                                @case(90)
                                                    Three Months
                                                    @break
                                                @case(180)
                                                    Six Months
                                                    @break
                                                @case(365)
                                                    One Year
                                                    @break
                                                @default
                                                    {{ $plan->duration }} Days
                                            @endswitch
                                        </span>
                                    </span>
                                </div>

                                <div class="plan-detail">
                                    <i class="fas fa-eye"></i>
                                    <span>
                                        <strong>Search Views</strong>
                                        <span>{{ $plan->num_searches }}</span>
                                    </span>
                                </div>

                                <div class="plan-detail">
                                    <i class="fas fa-user-tie"></i>
                                    <span>
                                        <strong>Service</strong>
                                        <span>Live-In Staff only</span>
                                    </span>
                                </div>
                            </div>

                            <div class="plan-notice">
                                <i class="fas fa-info-circle"></i>
                                Valid for Live-In Staff services only.
                            </div>

                            <a class="btn select-plan-btn"
                               href="{{ route('payments.accountdetail', ['plan_id' => $plan->id]) }}">
                                Select {{ $plan->plan_name }}
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

         {{-- Important Subscription Warning --}}
        <div class="subscription-warning mb-5" role="alert">
            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

            <div class="warning-content">
                <h5>Important: Please Read Before Subscribing</h5>
                <p>
                    <strong>This subscription applies only to Live-In Staff services.</strong>
                    It does not cover Live-Out Staff, Special Duties, or any other
                    services offered on this platform. Please ensure you are
                    subscribing for the correct service before making payment.
                </p>
            </div>
        </div>

        {{-- Coupon Section --}}
        <div class="coupon-card mb-5">
            <div class="coupon-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>

            <div class="coupon-content">
                <h4>Have a Coupon?</h4>
                <p>
                    Enter the phone number used for your Live-Out request
                    to check whether you qualify for a coupon.
                </p>

                <form method="POST"
                      action="{{ route('validate.coupon') }}"
                      onsubmit="showVerifying(this)"
                      class="coupon-form">
                    @csrf

                    <div class="coupon-input-wrap">
                        <label for="coupon-phone">Phone Number</label>
                        <input
                            type="text"
                            id="coupon-phone"
                            name="phone"
                            class="form-control"
                            placeholder="Enter your registered phone number"
                            required
                        >
                    </div>

                    <button type="submit"
                            class="btn coupon-submit-btn"
                            id="coupon-submit-btn">
                        <i class="fas fa-check-circle me-2"></i>
                        Apply Coupon
                    </button>
                </form>
            </div>
        </div>

        {{-- Coupon Feedback --}}
        @if (session('error'))
            <div class="alert alert-danger payment-alert">
                <i class="fas fa-times-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success payment-alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

       

    </div>
</section>

<style>
    .payment-page {
        padding: 75px 0;
        background: #f8faf9;
        color: #183b32;
    }

    .payment-header .payment-eyebrow,
    .plans-eyebrow {
        display: inline-block;
        color: #27815f;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
        margin-bottom: 12px;
    }

    .payment-header h2 {
        color: #173e32;
        font-size: clamp(28px, 4vw, 40px);
        font-weight: 800;
        margin-bottom: 14px;
    }

    .payment-title-line {
        display: block;
        width: 65px;
        height: 4px;
        border-radius: 5px;
        background: #6739C9;
        margin: 0 auto 18px;
    }

    .payment-header p,
    .plans-heading p {
        color: #687a73;
        line-height: 1.8;
    }

    /* Subscription warning */
    .subscription-warning {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding: 24px 26px;
        background: #fff8e7;
        border: 1px solid #f0d48a;
        
        border-radius: 12px;
    }

    .warning-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        color: #9b6a00;
        background: #ffedbd;
        border-radius: 50%;
        font-size: 20px;
    }

    .warning-content h5 {
        margin: 2px 0 8px;
        color: #795300;
        font-weight: 800;
        font-size: 17px;
    }

    .warning-content p {
        color: #765f32;
        font-size: 14px;
        line-height: 1.8;
        margin: 0;
    }

    /* Coupon card */
    .coupon-card {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding: 28px;
        background: #fff;
        border: 1px solid #e7ece9;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(20, 55, 43, 0.05);
    }

    .coupon-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 52px;
        width: 52px;
        height: 52px;
        background: #f0eaff;
        color: #6739C9;
        border-radius: 13px;
        font-size: 23px;
    }

    .coupon-content {
        flex: 1;
        min-width: 0;
    }

    .coupon-content h4 {
        color: #183b32;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .coupon-content p {
        color: #718078;
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .coupon-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .coupon-input-wrap {
        flex: 1;
    }

    .coupon-input-wrap label {
        display: block;
        color: #344d43;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .coupon-input-wrap .form-control {
        min-height: 48px;
        border: 1px solid #dce5df;
        border-radius: 9px;
        padding: 12px 14px;
        box-shadow: none;
    }

    .coupon-input-wrap .form-control:focus {
        border-color: #6739C9;
        box-shadow: 0 0 0 3px rgba(103, 57, 201, 0.1);
    }

    .coupon-submit-btn {
        min-height: 48px;
        padding: 12px 20px;
        border-radius: 9px;
        background: #6739C9;
        color: #fff;
        font-weight: 700;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .coupon-submit-btn:hover {
        background: #5129a8;
        color: #fff;
    }

    /* Plan heading */
    .plans-heading {
        margin-top: 65px;
    }

    .plans-heading h3 {
        color: #183b32;
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    /* Plan cards */
    .payment-plan-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 30px 25px;
        background: #fff;
        border: 1px solid #e4ebe6;
        border-radius: 16px;
        box-shadow: 0 8px 26px rgba(20, 55, 43, 0.045);
        transition: transform 0.25s ease, box-shadow 0.25s ease,
                    border-color 0.25s ease;
    }

    .payment-plan-card:hover {
        transform: translateY(-5px);
        border-color: #b9a3ec;
        box-shadow: 0 16px 35px rgba(54, 35, 102, 0.1);
    }

    .plan-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        margin: 0 auto 18px;
        color: #6739C9;
        background: #f0eaff;
        border-radius: 14px;
        font-size: 22px;
    }

    .plan-name {
        color: #183b32;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 14px;
    }

    .plan-price {
        color: #173e32;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .plan-price .currency {
        font-size: 20px;
        vertical-align: 4px;
        margin-right: 2px;
    }

    .plan-description {
        min-height: 44px;
        color: #78857e;
        font-size: 13px;
        line-height: 1.7;
        margin: 12px 0 0;
    }

    .plan-divider {
        height: 1px;
        background: #edf0ed;
        margin: 22px 0;
    }

    .plan-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 24px;
        text-align: left;
    }

    .plan-detail {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .plan-detail > i {
        width: 20px;
        margin-top: 3px;
        color: #6739C9;
        text-align: center;
    }

    .plan-detail > span {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .plan-detail strong {
        color: #344d43;
        font-size: 13px;
    }

    .plan-detail span span {
        color: #758179;
        font-size: 13px;
    }

    .plan-notice {
        padding: 11px;
        margin-top: auto;
        margin-bottom: 18px;
        color: #5c468c;
        background: #f6f2ff;
        border-radius: 8px;
        font-size: 12px;
        line-height: 1.6;
    }

    .plan-notice i {
        margin-right: 5px;
    }

    .select-plan-btn {
        display: block;
        width: 100%;
        padding: 13px 15px;
        color: #fff;
        background: #6739C9;
        border: 1px solid #6739C9;
        border-radius: 9px;
        font-weight: 700;
        transition: background 0.2s ease;
    }

    .select-plan-btn:hover,
    .select-plan-btn:focus {
        color: #fff;
        background: #5129a8;
        border-color: #5129a8;
    }

    .payment-alert {
        max-width: 1000px;
        margin: 0 auto 20px;
        border-radius: 10px;
    }

    @media (max-width: 767px) {
        .payment-page {
            padding: 45px 0;
        }

        .subscription-warning {
            padding: 18px;
            gap: 12px;
        }

        .warning-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
            font-size: 17px;
        }

        .warning-content h5 {
            font-size: 15px;
        }

        .warning-content p {
            font-size: 13px;
        }

        .coupon-card {
            flex-direction: column;
            padding: 22px;
            gap: 14px;
        }

        .coupon-form {
            flex-direction: column;
            align-items: stretch;
        }

        .coupon-submit-btn {
            width: 100%;
        }

        .plans-heading {
            margin-top: 45px;
        }

        .plans-heading h3 {
            font-size: 25px;
        }

        .payment-plan-card {
            padding: 25px 22px;
        }
    }

    .payment-page {
        --payment-primary: #0B4A3B;
        --payment-primary-hover: #07382D;
        --payment-secondary: #27815F;
        --payment-pale: #EAF5EF;
        --payment-border: #CFE4D6;
    }

    /* Page title underline */
    .payment-title-line {
        background: var(--payment-primary);
    }

    /* Coupon icon */
    .coupon-icon {
        background: var(--payment-pale);
        color: var(--payment-primary);
    }

    /* Coupon input focus */
    .coupon-input-wrap .form-control:focus {
        border-color: var(--payment-primary);
        box-shadow: 0 0 0 3px rgba(11, 74, 59, 0.10);
    }

    /* Coupon button */
    .coupon-submit-btn {
        background: var(--payment-primary);
        border-color: var(--payment-primary);
        color: #fff;
    }

    .coupon-submit-btn:hover,
    .coupon-submit-btn:focus {
        background: var(--payment-primary-hover);
        border-color: var(--payment-primary-hover);
        color: #fff;
    }

    /* Payment plan card hover */
    .payment-plan-card:hover {
        border-color: var(--payment-border);
        box-shadow: 0 16px 35px rgba(20, 55, 43, 0.10);
    }

    /* Payment plan icons */
    .plan-icon {
        color: var(--payment-primary);
        background: var(--payment-pale);
    }

    /* Plan detail icons */
    .plan-detail > i {
        color: var(--payment-secondary);
    }

    /* Plan service notice */
    .plan-notice {
        color: var(--payment-primary);
        background: var(--payment-pale);
        border: 1px solid var(--payment-border);
    }

    /* Select plan button */
    .select-plan-btn {
        background: var(--payment-primary);
        border-color: var(--payment-primary);
        color: #fff;
    }

    .select-plan-btn:hover,
    .select-plan-btn:focus {
        background: var(--payment-primary-hover);
        border-color: var(--payment-primary-hover);
        color: #fff;
    }

    /* Keyboard accessibility */
    .coupon-submit-btn:focus-visible,
    .select-plan-btn:focus-visible {
        outline: 3px solid rgba(39, 129, 95, 0.35);
        outline-offset: 3px;
    }

</style>

<script>
    function showVerifying(form) {
        const button = form.querySelector('#coupon-submit-btn');

        if (button) {
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Verifying...';
        }
    }
</script>
