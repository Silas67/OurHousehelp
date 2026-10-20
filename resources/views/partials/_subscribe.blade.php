
@if (Auth::check())
    @php
        $subscribeHasPayment = isset($hasApprovedPayment) && $hasApprovedPayment;
        $subscribeIsExpired = isset($isSubscriptionExpired) && $isSubscriptionExpired;
        $subscribeIsPending = isset($pendingPayment) && $pendingPayment;

        $subscribeUsed = (int) ($searchCount ?? 0);
        $subscribeLimit = (int) ($subscriptionLimit ?? 0);

        $subscribePercentage = $subscribeLimit > 0
            ? min(100, (int) round(($subscribeUsed / $subscribeLimit) * 100))
            : 0;

        $subscribeRemaining = max(0, $subscribeLimit - $subscribeUsed);

        $subscribeCanSearch = $subscribeHasPayment
            && !$subscribeIsExpired
            && $subscribeRemaining > 0;
    @endphp

    <div class="ohh-subscription-panel">

        {{-- Active subscription with remaining views --}}
        @if ($subscribeCanSearch)
            <div class="ohh-subscription-top">
                <div class="ohh-subscription-symbol ohh-symbol-active">
                    <i class="fas fa-check"></i>
                </div>

                <div class="ohh-subscription-heading">
                    <strong>Subscription Active</strong>
                    <span>Your Live-In Staff search access is active.</span>
                </div>

                <span class="ohh-subscription-badge ohh-badge-active">
                    Active
                </span>
            </div>

            <div class="ohh-usage-section">
                <div class="ohh-usage-labels">
                    <span>Search views used</span>
                    <strong>{{ $subscribeUsed }} <small>of {{ $subscribeLimit }}</small></strong>
                </div>

                <div class="ohh-usage-track"
                     role="progressbar"
                     aria-label="Subscription search views used"
                     aria-valuenow="{{ $subscribePercentage }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <div class="ohh-usage-fill"
                         style="width: {{ $subscribePercentage }}%">
                    </div>
                </div>

                <div class="ohh-usage-footer">
                    <span>
                        <i class="fas fa-search"></i>
                        {{ $subscribeRemaining }} {{ $subscribeRemaining === 1 ? 'view' : 'views' }} remaining
                    </span>

                    <span>{{ $subscribePercentage }}% used</span>
                </div>
            </div>

        {{-- Pending payment --}}
        @elseif ($subscribeIsPending)
            <div class="ohh-subscription-top">
                <div class="ohh-subscription-symbol ohh-symbol-pending">
                    <i class="fas fa-hourglass-half"></i>
                </div>

                <div class="ohh-subscription-heading">
                    <strong>Payment Awaiting Approval</strong>
                    <span>Your subscription is not yet approved.</span>
                </div>

                <span class="ohh-subscription-badge ohh-badge-pending">
                    Pending
                </span>
            </div>

            <p class="ohh-subscription-message">
                Please wait while your payment is reviewed. If you need assistance,
                contact our support team.
            </p>

            <div class="ohh-support-links">
                <span>Need help?</span>
                <a href="tel:+2349167696525">
                    <i class="fas fa-phone-alt"></i> +234 916 7696 525
                </a>
                <a href="tel:+2349091144474">
                    <i class="fas fa-phone-alt"></i> +234 909 1144 474
                </a>
            </div>

        {{-- Expired subscription or exhausted views --}}
        @elseif ($subscribeHasPayment)
            <div class="ohh-subscription-top">
                <div class="ohh-subscription-symbol ohh-symbol-warning">
                    <i class="fas fa-exclamation"></i>
                </div>

                <div class="ohh-subscription-heading">
                    <strong>
                        {{ $subscribeIsExpired ? 'Subscription Expired' : 'Search Limit Reached' }}
                    </strong>

                    <span>
                        {{ $subscribeIsExpired
                            ? 'Your current subscription has expired.'
                            : 'You have used all the search views included in your subscription.' }}
                    </span>
                </div>
            </div>

            @if ($subscribeLimit > 0)
                <div class="ohh-usage-section">
                    <div class="ohh-usage-labels">
                        <span>Total search views used</span>
                        <strong>{{ $subscribeUsed }} <small>of {{ $subscribeLimit }}</small></strong>
                    </div>

                    <div class="ohh-usage-track"
                         role="progressbar"
                         aria-label="Subscription search views used"
                         aria-valuenow="{{ $subscribePercentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <div class="ohh-usage-fill ohh-usage-fill-warning"
                             style="width: {{ $subscribePercentage }}%">
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('payments.paymentoption') }}"
               class="ohh-subscription-action">
                <i class="fas fa-lock"></i>
                Subscribe Again
                <i class="fas fa-arrow-right ohh-action-arrow"></i>
            </a>

        {{-- No approved subscription --}}
        @else
            <div class="ohh-subscription-top">
                <div class="ohh-subscription-symbol ohh-symbol-warning">
                    <i class="fas fa-lock"></i>
                </div>

                <div class="ohh-subscription-heading">
                    <strong>Unlock Full Staff Profiles</strong>
                    <span>Subscribe to access more details about matching staff.</span>
                </div>
            </div>

            <a href="{{ route('payments.paymentoption') }}"
               class="ohh-subscription-action">
                <i class="fas fa-lock"></i>
                View Payment Plans
                <i class="fas fa-arrow-right ohh-action-arrow"></i>
            </a>
        @endif
    </div>

    <style>
        .ohh-subscription-panel {
            width: 100%;
            margin-top: 15px;
            padding: 17px;
            border: 1px solid #e6ddf8;
            border-radius: 12px;
            background: rgba(255, 255, 255, .9);
            color: #344d43;
            text-align: left;
        }

        .ohh-subscription-top {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .ohh-subscription-symbol {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            font-size: 15px;
        }

        .ohh-symbol-active {
            background: #e7f7ec;
            color: #217445;
        }

        .ohh-symbol-pending {
            background: #fff3d6;
            color: #996600;
        }

        .ohh-symbol-warning {
            background: #f0eaff;
            color: #6739C9;
        }

        .ohh-subscription-heading {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .ohh-subscription-heading strong {
            color: #253f33;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
        }

        .ohh-subscription-heading span {
            color: #78847c;
            font-size: 11px;
            line-height: 1.6;
        }

        .ohh-subscription-badge {
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
        }

        .ohh-badge-active {
            background: #e7f7ec;
            color: #217445;
        }

        .ohh-badge-pending {
            background: #fff3d6;
            color: #996600;
        }

        .ohh-usage-section {
            padding-top: 16px;
            margin-top: 15px;
            border-top: 1px solid #eeeaf5;
        }

        .ohh-usage-labels,
        .ohh-usage-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .ohh-usage-labels {
            margin-bottom: 10px;
            color: #697970;
            font-size: 11px;
        }

        .ohh-usage-labels strong {
            color: #253f33;
            font-size: 15px;
            font-weight: 800;
        }

        .ohh-usage-labels strong small {
            color: #88928c;
            font-size: 11px;
            font-weight: 500;
        }

        .ohh-usage-track {
            width: 100%;
            height: 8px;
            overflow: hidden;
            border-radius: 20px;
            background: #ede8f7;
        }

        .ohh-usage-fill {
            height: 100%;
            border-radius: inherit;
            background: #6739C9;
            transition: width .3s ease;
        }

        .ohh-usage-fill-warning {
            background: #d59a23;
        }

        .ohh-usage-footer {
            margin-top: 9px;
            color: #758179;
            font-size: 10px;
        }

        .ohh-usage-footer span:first-child {
            color: #5d478d;
            font-weight: 700;
        }

        .ohh-usage-footer i {
            margin-right: 4px;
        }

        .ohh-subscription-message {
            margin: 13px 0;
            color: #6e776f;
            font-size: 12px;
            line-height: 1.8;
        }

        .ohh-support-links {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 13px;
            padding-top: 12px;
            margin-top: 12px;
            border-top: 1px solid #eeeaf5;
            font-size: 11px;
        }

        .ohh-support-links > span {
            width: 100%;
            color: #758179;
        }

        .ohh-support-links a {
            color: #5c368f;
            font-weight: 700;
            text-decoration: none;
        }

        .ohh-support-links a:hover {
            text-decoration: underline;
        }

        .ohh-subscription-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 43px;
            padding: 11px 14px;
            margin-top: 16px;
            border: 0;
            border-radius: 8px;
            background: #6739C9;
            color: #fff !important;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none !important;
            transition: background .2s ease;
        }

        .ohh-subscription-action:hover {
            background: #5129a8;
        }

        .ohh-action-arrow {
            margin-left: auto;
        }

        @media (max-width: 480px) {
            .ohh-subscription-panel {
                padding: 13px;
            }

            .ohh-subscription-top {
                gap: 9px;
            }

            .ohh-subscription-heading strong {
                font-size: 12px;
            }

            .ohh-subscription-heading span {
                font-size: 10px;
            }

            .ohh-subscription-badge {
                padding: 4px 6px;
                font-size: 9px;
            }

            .ohh-support-links {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endif


