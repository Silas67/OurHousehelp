@extends('layouts.applicantmain')

@section('applicantdashboard')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="fw-bold mb-1">Service Request Offers</h3>
            <p class="text-muted mb-0">New jobs matched to your profile. Accept quickly — first to accept gets the job!</p>
        </div>
        <span class="badge bg-danger fs-6 py-2 px-3" id="pendingCount" style="{{ $offers->where('status', 'notified')->count() > 0 ? '' : 'display:none' }}">
            <i class="fas fa-bell me-1"></i>
            {{ $offers->where('status', 'notified')->count() }} New
        </span>
    </div>

    <hr class="mb-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

     @php
        use App\Services\StaffSalaryService;
        $salaryService = new StaffSalaryService();
    
    @endphp

    @forelse($offers as $offer)
    @php
        $sr = $offer->serviceRequest;
        $isNew = $offer->status === 'notified';
        $staffSalary = $salaryService->getStaffSalary($sr);
        $services = is_array($sr->serviceName)
            ? implode(', ', $sr->serviceName)
            : implode(', ', json_decode($sr->serviceName, true) ?? []);
        $applicantType = ucfirst(str_replace('-', ' ', $sr->applicant_type ?? 'live-out'));
        $grandCost = $sr->grand_cost ? '₦' . number_format($sr->grand_cost, 2) : 'TBD';
        $duration = $sr->duration_weeks ? $sr->duration_weeks . ' weeks' : 'TBD';
        $startDate = $sr->preferred_start_date ? $sr->preferred_start_date->format('M d, Y') : 'TBD';
    @endphp

    <div class="card shadow-sm mb-4 {{ $isNew ? 'border-success border-2' : '' }}"
         style="{{ $isNew ? 'background: #f0fff4;' : '' }}">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        {{ $services }}
                        @if($isNew)
                            <span class="badge bg-success ms-2 animate-pulse">New Job!</span>
                        @elseif($offer->status === 'selected')
                            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-star me-1"></i>You were selected!</span>
                        @elseif($offer->status === 'accepted')
                            <span class="badge bg-primary ms-2">Accepted</span>
                        @elseif($offer->status === 'shortlisted')
                            <span class="badge bg-info ms-2">Shortlisted for client</span>
                        @elseif($offer->status === 'order_cancelled')
                            <div class="badge bg-danger ms-2">
                                Cancelled
                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="This job was cancelled by the client."></i>
                            </div>
                        @elseif($offer->status === 'expired')
                            <span class="badge bg-secondary ms-2">Expired</span>
                        @endif
                    </h5>
                    <small class="text-muted">Request #{{ $sr->id }} &bull; Match Score: <span class="fw-semibold text-success">{{ $offer->match_score }}%</span></small>
                </div>
                <div class="text-end">
                    <div class="fs-4 fw-bold text-success">₦{{ number_format($staffSalary) }}/month</div>
                    <small class="text-muted">Your salary</small>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 rounded p-2"><i class="fas fa-concierge-bell text-primary"></i></div>
                        <div>
                            <div class="small text-muted">Service</div>
                            <div class="fw-semibold">{{ $services }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-info bg-opacity-10 rounded p-2"><i class="fas fa-home text-info"></i></div>
                        <div>
                            <div class="small text-muted">Type</div>
                            <div class="fw-semibold">{{ $applicantType }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-warning bg-opacity-10 rounded p-2"><i class="fas fa-map-marker-alt text-warning"></i></div>
                        <div>
                            <div class="small text-muted">Location</div>
                            <div class="fw-semibold">{{ $sr->preferred_city }}, {{ $sr->state_of_residence }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 rounded p-2"><i class="fas fa-calendar-check text-success"></i></div>
                        <div>
                            <div class="small text-muted">Start Date</div>
                            <div class="fw-semibold">{{ $startDate }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-danger bg-opacity-10 rounded p-2"><i class="fas fa-hourglass-half text-danger"></i></div>
                        <div>
                            <div class="small text-muted">Duration</div>
                            <div class="fw-semibold">{{ $duration }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-secondary bg-opacity-10 rounded p-2"><i class="fas fa-calendar-week text-secondary"></i></div>
                        <div>
                            <div class="small text-muted">Days/Week</div>
                            <div class="fw-semibold">{{ $sr->days_per_week ?? 'TBD' }} days</div>
                        </div>
                    </div>
                </div>

                @if($sr->service_days)
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 rounded p-2"><i class="fas fa-calendar-alt text-primary"></i></div>
                        <div>
                            <div class="small text-muted">Preferred Days</div>
                            <div class="fw-semibold" style="font-size:13px;">{{ $sr->service_days }}</div>
                        </div>
                    </div>
                </div>
                @endif

                @if($sr->working_hour)
                <div class="col-sm-6 col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 rounded p-2"><i class="fas fa-clock text-success"></i></div>
                        <div>
                            <div class="small text-muted">Working Hours</div>
                            <div class="fw-semibold">{{ $sr->working_hour }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @php $isClientManaged = $sr->management_plan === 'client-managed'; @endphp
            <div class="rounded p-2 mb-3 d-flex align-items-center gap-2" style="background: {{ $isClientManaged ? '#fff8e1' : '#e8f4fd' }}; border-left: 3px solid {{ $isClientManaged ? '#f59e0b' : '#3b82f6' }};">
                <i class="fas fa-{{ $isClientManaged ? 'user' : 'building' }}" style="color: {{ $isClientManaged ? '#f59e0b' : '#3b82f6' }};"></i>
                <small>
                    @if($isClientManaged)
                        <strong>Client Managed</strong> — The client will pay your ₦{{ number_format($staffSalary) }} salary directly every month.
                    @else
                        <strong>Company Managed</strong> — OurHouseHelp will pay your ₦{{ number_format($staffSalary) }} salary every month.
                    @endif
                </small>
            </div>

            @if($sr->address)
            <div class="bg-light rounded p-2 mb-3">
                <small class="text-muted"><i class="fas fa-map me-1"></i>Address: </small>
                <small class="fw-semibold">{{ $sr->address }}{{ $sr->landmark ? ' (Near ' . $sr->landmark . ')' : '' }}</small>
            </div>
            @endif

            @if($offer->match_reasons)
            <div class="mb-3">
                @foreach($offer->match_reasons as $reason)
                    <span class="badge bg-light text-dark border me-1">
                        <i class="fas fa-check-circle text-success me-1"></i>{{ $reason }}
                    </span>
                @endforeach
            </div>
            @endif

            @if($isNew)
            <div class="d-flex gap-3 mt-2">
                <form action="{{ route('shortlist.accept', $offer->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="fas fa-check-circle me-2"></i>Accept this Job
                    </button>
                </form>
                <form action="{{ route('shortlist.decline', $offer->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 py-2">
                        <i class="fas fa-times-circle me-2"></i>Decline
                    </button>
                </form>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-bolt text-warning me-1"></i>
                Respond quickly — first staff to accept gets assigned to this job!
            </small>
            @elseif($offer->status === 'selected')
            <div class="alert alert-warning mb-0">
                <i class="fas fa-star me-2"></i>
                <strong>Congratulations!</strong> The client has selected you for this job. The admin will be in touch with next steps.
            </div>
            @elseif($offer->status === 'accepted')
            <div class="alert alert-primary mb-0">
                <i class="fas fa-clock me-2"></i>
                You accepted this offer. Waiting for the client to make their selection.
            </div>
            @elseif($offer->status === 'order_cancelled')
            <div class="alert alert-danger mb-0">
                <i class="fas fa-ban me-2"></i>
                This order was cancelled by the client.
            </div>
            @elseif($offer->status === 'expired')
            <div class="alert alert-secondary mb-0">
                <i class="fas fa-info-circle me-2"></i>
                This job was taken by another staff member.
            </div>
            @endif

        </div>
    </div>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No offers at the moment</h5>
            <p class="text-muted">New service requests matching your profile will appear here.</p>
        </div>
    </div>
    @endforelse

    <div class="mt-3">{{ $offers->links() }}</div>
</div>

<audio id="notifSound" src="{{ asset('sounds/notification.ogg') }}" preload="auto"></audio>

<style>
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
.animate-pulse { animation: pulse 1.5s infinite; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pendingCount = {{ $offers->where('status', 'notified')->count() }};

    if (pendingCount > 0) {
        const sound = document.getElementById('notifSound');
        if (sound) sound.play().catch(e => console.log('Autoplay blocked:', e));
    }

    let lastCount = pendingCount;
    setInterval(function () {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newBadge = doc.getElementById('pendingCount');
            if (newBadge) {
                const newCount = parseInt(newBadge.textContent.trim()) || 0;
                if (newCount > lastCount) {
                    const sound = document.getElementById('notifSound');
                    if (sound) sound.play().catch(() => {});
                    window.location.reload();
                }
                lastCount = newCount;
            }
        }).catch(() => {});
    }, 30000);
});
</script>
@endsection