@props(['bookings' => null])

@php
    /**
     * Persistent entry point back into an in-progress booking.
     *
     * Without this, a client who navigated away from their shortlist had no
     * route back - the dashboard only offered "book a service" and the
     * bookings list never linked to a shortlist. The notification email was
     * the only way in.
     */
    $resumable = $bookings ?? (
        auth()->check()
            ? \App\Models\ServiceRequest::resumable()
                ->where('user_id', auth()->id())
                ->latest()
                ->take(3)
                ->get()
            : collect()
    );
@endphp

@once
    <style>
        .resume-card {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            background: #EEF6FF;
            border: 1px solid #BBD8F7;
            border-left: 4px solid #1c4b91;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 14px;
        }
        .resume-card.is-ready {
            background: #E9F7F0;
            border-color: #A9D9BF;
            border-left-color: #17683f;
        }
        .resume-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #1c4b91;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .resume-card.is-ready .resume-icon { background: #17683f; }
        .resume-body { flex: 1 1 240px; min-width: 0; }
        .resume-title { font-weight: 600; font-size: 15px; margin: 0; color: #14243d; }
        .resume-meta { font-size: 13px; color: #5b6b80; margin: 2px 0 0; }
        .resume-action { flex-shrink: 0; }
        @media (max-width: 575px) {
            .resume-action, .resume-action .btn { width: 100%; }
        }
    </style>
@endonce

@foreach ($resumable as $booking)
    @php
        $accepted = $booking->shortlistedStaff()
            ->whereIn('status', ['accepted', 'shortlisted', 'selected'])
            ->count();
        $isReady = $accepted > 0 || $booking->matching_status === 'payment_pending';
    @endphp

    <div class="resume-card {{ $isReady ? 'is-ready' : '' }}">
        <div class="resume-icon">
            <i class="fas {{ $isReady ? 'fa-user-check' : 'fa-hourglass-half' }}" aria-hidden="true"></i>
        </div>

        <div class="resume-body">
            <p class="resume-title">{{ $booking->resumeLabel() }}</p>
            <p class="resume-meta">
                {{ $booking->service_name_list ?: 'Service request' }}
                @if ($booking->preferred_city) &middot; {{ $booking->preferred_city }} @endif
                &middot; booked {{ $booking->created_at->diffForHumans() }}
            </p>
        </div>

        <div class="resume-action d-flex flex-column gap-1">
            <a href="{{ $booking->resumeUrl() }}"
               class="btn {{ $isReady ? 'btn-success' : 'btn-outline-primary' }}">
                {{ $isReady ? 'Continue' : 'Check progress' }}
                <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>

            {{-- An in-flight booking blocks starting a new one, so cancelling has to
                 be reachable from here too - not only from inside the flow. --}}
            @if ($booking->payment_status !== 'Paid')
                <a href="{{ route('booking.staff-matching', $booking->id) }}"
                   class="btn btn-link btn-sm text-danger p-0"
                   style="font-size:12.5px;text-decoration:none;">
                    Cancel booking
                </a>
            @endif
        </div>
    </div>
@endforeach
