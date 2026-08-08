@extends('layouts.applicantmain')
@section('applicantdashboard')
    <div class="container mt-5">
        <!-- Welcome Section -->
        <div class="text-center mb-5">
            <h1>Welcome,
                {{ Auth::user()->name }}
            </h1>
            @if(Auth::user()->approval_status === 'approved')
                <span class="badge bg-success mb-2">Staff</span>
            @else
                <span class="badge bg-warning mb-2">Applicant</span>
            @endif
            <p class="lead">We're glad to have you. Below is a summary of your application activities.</p>
        </div>

        {{-- ── PROFILE COMPLETION BANNER ── --}}
        @php $completion = Auth::user()->profileCompletionData(); @endphp
        @if($completion['percentage'] < 100 && Auth::user()->approval_status !== 'approved')
        <div class="alert mb-4 p-0 border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #0F6E56, #1D9E75); padding: 16px 20px;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <strong style="color: white; font-size: 15px;">
                            📋 Complete Your Profile — {{ $completion['percentage'] }}% done
                        </strong>
                        <div style="color: rgba(255,255,255,0.85); font-size: 13px;">
                            {{ $completion['completed'] }} of {{ $completion['total'] }} steps completed.
                            A complete profile gets you matched faster.
                        </div>
                    </div>
                    <a href="{{ route('applicantprofile', ['id' => Auth::user()->id]) }}" class="btn btn-sm"
                        style="background: white; color: #0F6E56; font-weight: 600; border-radius: 8px; white-space: nowrap;">
                        Complete Now
                    </a>
                </div>
                {{-- Progress bar --}}
                <div class="mt-3" style="background: rgba(255,255,255,0.2); border-radius: 999px; height: 8px;">
                    <div style="background: white; width: {{ $completion['percentage'] }}%; height: 8px; border-radius: 999px; transition: width 0.4s ease;"></div>
                </div>
            </div>
            {{-- Checklist --}}
            <div style="background: white; padding: 12px 20px;">
                <div class="row g-2">
                    @foreach($completion['steps'] as $step)
                    <div class="col-6 col-md-4">
                        <a href="{{ $step['url'] }}" class="text-decoration-none">
                            <div class="d-flex align-items-center gap-2 p-2 rounded"
                                style="background: {{ $step['done'] ? '#f0faf6' : '#fff8f0' }}; border: 1px solid {{ $step['done'] ? '#c3e6d8' : '#fde8c8' }};">
                                <i class="{{ $step['icon'] }}"
                                    style="color: {{ $step['done'] ? '#0F6E56' : '#f59e0b' }}; font-size: 14px; width: 16px;"></i>
                                <span style="font-size: 12px; color: #333;">
                                    @if($step['done'])
                                        <s class="text-muted">{{ $step['label'] }}</s>
                                    @else
                                        {{ $step['label'] }}
                                    @endif
                                </span>
                                @if($step['done'])
                                    <i class="fas fa-check-circle ms-auto" style="color: #0F6E56; font-size: 12px;"></i>
                                @else
                                    <i class="fas fa-circle ms-auto" style="color: #d1d5db; font-size: 12px;"></i>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Applications Section -->
        <div class="card mb-4">
            <div class="card-header text-white recentapp">
                <h5>Recent Applications</h5>
            </div>
            <div class="card-body">
                {{-- <p class="text-center">You have no recent applications.</p> --}}
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>Job Search Visibility</strong><br>
                            <small class="text-muted">If the switch is off, you will not appear in search results for
                                clients.</small>
                        </span>
                        <span class="badge {{ $availability ? 'bg-primary' : 'bg-danger' }}">
                            {{ $availability ? 'Enabled' : 'Disabled' }} <!-- Display 'Enabled' or 'Disabled' -->
                        </span>
                    </li>


                </ul>
            </div>
        </div>

        <!-- Dashboard Quick Links -->
        <div class="row">
            <div class="col-md-4">
                <a href="{{ route('applicant.positions') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4">
                    <div class="card-body">
                        <i class="fas fa-briefcase fa-3x mb-3"></i>
                        <h5 class="card-title">View Job Requests</h5>
                        <p class="card-text">Browse available job request and Accept.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('applicant.specialDuties') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4">
                    <div class="card-body">
                        <i class="fas fa-tasks fa-3x mb-3"></i>
                        <h5 class="card-title">Special Duties</h5>
                        <p class="card-text">View and manage your assigned special duties.</p>
                    </div>
                </a>
            </div>


            <div class="col-md-4">
                <a href="{{ route('applicantprofile', ['id' => $user->id]) }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4">
                    <div class="card-body">
                        <i class="fas fa-user fa-3x mb-3"></i>
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">Update your personal information and preferences.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection