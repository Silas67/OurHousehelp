@props([
    'staff',
    // 'full' shows every check including outstanding ones (profile page);
    // 'compact' shows only checks that passed (shortlist card).
    'mode' => 'compact',
])

@php
    /**
     * Only signals actually populated in this database are asserted here.
     *
     * id_verified, address_confirmed and background_checked exist as columns but
     * are 0 for all 161 staff - nothing writes them and there is no admin screen
     * to set them. Claiming those checks would be a false trust signal, so they
     * are listed as outstanding in 'full' mode and omitted entirely in 'compact'.
     */
    $checks = [
        [
            'label' => 'NIN on file',
            'icon'  => 'fa-id-card',
            'done'  => filled($staff->nin ?? null),
            'help'  => 'National Identity Number recorded',
        ],
        [
            'label' => 'ID document',
            'icon'  => 'fa-image',
            'done'  => filled($staff->id_card ?? null),
            'help'  => 'Photo ID uploaded to the file',
        ],
        [
            'label' => 'Guarantor 1',
            'icon'  => 'fa-user-shield',
            'done'  => (bool) ($staff->guarantors_forms_uploaded ?? false),
            'help'  => 'First guarantor form submitted',
        ],
        [
            'label' => 'Guarantor 2',
            'icon'  => 'fa-user-shield',
            'done'  => (bool) ($staff->guarantors_forms_uploaded_2 ?? false),
            'help'  => 'Second guarantor form submitted',
        ],
        [
            'label' => 'Admin approved',
            'icon'  => 'fa-circle-check',
            'done'  => ($staff->approval_status ?? null) === 'approved',
            'help'  => 'Reviewed and approved by our team',
        ],
    ];

    $passed = array_values(array_filter($checks, fn ($c) => $c['done']));
    $shown  = $mode === 'full' ? $checks : $passed;
    $score  = count($passed);
    $total  = count($checks);
@endphp

@once
    <style>
        .trust-badge-row {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            justify-content: center;
        }
        .trust-badges--full .trust-badge-row { justify-content: flex-start; }

        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            line-height: 1.4;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .trust-badge.is-done {
            background: #e8f5ee;
            border-color: #a9d9bf;
            color: #17683f;
        }
        .trust-badge.is-pending {
            background: #f4f4f5;
            border-color: #dcdce0;
            color: #71717a;
        }
        .trust-badge i { font-size: 9px; }

        .trust-summary {
            display: flex;
            align-items: baseline;
            gap: 7px;
            margin-bottom: 8px;
        }
        .trust-count {
            font-size: 17px;
            font-weight: 700;
            color: #17683f;
            font-variant-numeric: tabular-nums;
        }
        .trust-count-label { font-size: 12px; color: #6c757d; }

        /* Match reasons: these come from the scoring engine and explain WHY a
           staff member surfaced. They were rendered at 9px in near-invisible
           grey; they are the most persuasive thing on the card. */
        .match-reason {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10.5px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eef4ff;
            border: 1px solid #c3d6f5;
            color: #1c4b91;
            white-space: nowrap;
        }
    </style>
@endonce

@if (!empty($shown))
    <div class="trust-badges {{ $mode === 'full' ? 'trust-badges--full' : '' }}">

        @if ($mode === 'full')
            <div class="trust-summary">
                <span class="trust-count">{{ $score }}/{{ $total }}</span>
                <span class="trust-count-label">verification checks complete</span>
            </div>
        @endif

        <div class="trust-badge-row">
            @foreach ($shown as $check)
                <span class="trust-badge {{ $check['done'] ? 'is-done' : 'is-pending' }}"
                      title="{{ $check['help'] }}">
                    <i class="fas {{ $check['done'] ? $check['icon'] : 'fa-clock' }}" aria-hidden="true"></i>
                    <span>{{ $check['label'] }}</span>
                </span>
            @endforeach
        </div>
    </div>
@endif
