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

        /* Continue and cancel sit together on one baseline, centred as a pair. */
        .resume-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .resume-dismiss {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8496ab;
            text-decoration: none;
            font-size: 13px;
            line-height: 1;
            transition: background-color .15s, color .15s;
        }
        .resume-dismiss:hover,
        .resume-dismiss:focus-visible {
            background: #dc3545;
            color: #fff;
        }
        .resume-dismiss:focus-visible {
            outline: 2px solid #dc3545;
            outline-offset: 2px;
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
        @media (max-width: 575px) {
            .resume-card { justify-content: center; text-align: center; }
            .resume-body { text-align: center; }
            .resume-action { width: 100%; }
            .resume-action .btn { flex: 1 1 auto; }
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

        <div class="resume-action">
            <a href="{{ $booking->resumeUrl() }}"
               class="btn {{ $isReady ? 'btn-success' : 'btn-outline-primary' }}">
                {{ $isReady ? 'Continue' : 'Check progress' }}
                <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>

            {{-- An in-flight booking blocks starting a new one, so cancelling has to
                 be reachable from here too - not only from inside the flow. This
                 navigates to the confirmation rather than cancelling outright, so a
                 stray click cannot destroy a booking. --}}
            @if ($booking->canBeCancelled())
                <a href="{{ route('booking.staff-matching', $booking->id) }}"
                   class="resume-dismiss"
                   title="Cancel this booking"
                   aria-label="Cancel this booking">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    </div>
@endforeach
