@if (Auth::check())
    @if ($hasApprovedPayment && !$isSubscriptionExpired)
        <span>
            <strong>Number of Viewed</strong><br>
            <small class="text-muted">
                You have used
                <span class="badge bg-danger">
                    {{ $searchCount }}
                </span> out of your
                <span class="badge bg-primary">
                    {{ $subscriptionLimit }}
                </span> views of your current subscription
            </small>
        </span>
    @elseif ($pendingPayment)
        <div>
            <span>
                🔒 Your account is currently pending approval. If you require
                immediate
                assistance, please contact our support team.
                <br>
                Call us at:
                <a href="tel:+2349167696525">+234 916 7696 525</a> or
                <a href="tel:+2349091144474">+234 909 1144 474</a>
            </span>
        </div>
    @else
        <span>
            <strong>For LiveIn Services, Please Subscribe to View More Detail</strong><br>
            <small class="mb-5">
                You have used all your allowed views.
                <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-lock"></i> Subscribe Now
                </a>
            </small>
        </span>
    @endif
@endif
<br>
