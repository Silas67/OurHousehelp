@extends('layouts.main')

@section('workerdetail')
    <div class="container py-4">
        <!-- Coupon Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-success text-white rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white"><i class="fas fa-ticket-alt me-2 text-white"></i> Your Coupon Status</h4>
                        <div class="d-flex gap-4">
                            <p class="mb-0"><i class="fas fa-phone me-1 text-white"></i> {{ session('active_coupon_phone') }}</p>
                            <p class="mb-0"><i class="fas fa-search me-1 text-white"></i> {{ $remainingSlots }} search slots remaining
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('couponSkilledWorkerServices') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Worker Profile Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <!-- Header with name and quick actions -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="mb-1">{{ $worker->name }}  {{ $worker->last_name }}</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                <i class="fas fa-check-circle me-1"></i> Verified
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-success">
                                <i class="fas fa-bolt me-1"></i> Available
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Profile Photo Column -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="position-relative">
                            @if ($worker->profile_photo_path)
                                <img src="{{ $worker->profile_photo_path ? asset('public/build/assets/image/profile_photo/' . $worker->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"
                                    class="img-fluid rounded-3 border" alt="{{ $worker->name }}"
                                    style="max-height: 300px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light p-5 text-center rounded-3 d-flex align-items-center justify-content-center"
                                    style="height: 300px;">
                                    <div>
                                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                        <p class="mt-2 mb-0">No Photo Available</p>
                                    </div>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-white p-2 rounded-circle shadow-sm">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>

                        <!-- Quick Contact Card -->
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-id-card me-2"></i> Contact</h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:{{ $worker->phone }}"
                                            class="text-decoration-none">{{ $worker->phone }}</a>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $worker->state_of_residence }}, {{ $worker->country_of_residence }}
                                    </li>
                                    <li>
                                        <i class="fas fa-transgender me-2"></i>
                                        {{ ucfirst($worker->gender) }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Details Column -->
                    <div class="col-md-8">
                        <!-- Skills & Experience -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-user-tie me-2"></i> Professional Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted small mb-1">Specialization</h6>
                                        {{-- <div class="d-flex flex-wrap gap-2"> --}}
                                            @if (is_array($worker->specialties))
                                                @foreach ($worker->specialties as $specialty)
                                                    <span class="badge bg-primary bg-opacity-10 text-success rounded-pill">
                                                        <h4>{{ trim($specialty) }}</h4>
                                                    </span>
                                                @endforeach
                                            @else
                                                @foreach (explode(',', $worker->specialties) as $specialty)
                                                    <span class="badge bg-primary bg-opacity-10 text-success rounded-pill">
                                                        {{ trim($specialty) }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        {{-- </div> --}}
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-muted small mb-1">Experience</h6>
                                        <p class="mb-0">
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                {{ $worker->years_of_experience }} years
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted small mb-1">Date of Birth</h6>
                                        <p class="mb-0">
                                            {{ $worker->dob ? \Carbon\Carbon::parse($worker->dob)->format('M d, Y') : 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-user-circle me-2"></i> About</h5>
                                <div class="bg-light p-3 rounded-2">
                                    @if ($worker->bio)
                                        {{ $worker->bio }}
                                    @else
                                        <p class="text-muted mb-0">No bio information provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Work History -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3"><i class="fas fa-briefcase me-2"></i> Work History</h5>
                                <div class="bg-light p-3 rounded-2">
                                    @if ($worker->work_history)
                                        {{ $worker->work_history }}
                                    @else
                                        <p class="text-muted mb-0">No work history provided</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('couponSkilledWorkerServices') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Workers List
            </a>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" hidden>
                    <i class="fas fa-download me-2"></i> Download Profile
                </button>
                <button class="btn btn-primary" hidden>
                    <i class="fas fa-paper-plane me-2  text-white"></i> Contact Worker
                </button>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-weight: 500;
        }
    </style>

@endsection
