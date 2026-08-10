@extends('layouts.main')

@section('skilledworkerservice')
    <div class="container py-4">
        <!-- Coupon Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body bg-success text-white rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white"><i class="fas fa-ticket-alt me-2 text-white"></i> Your Coupon Status</h4>
                        <div class="d-flex gap-4">
                            <p class="mb-0"><i class="fas fa-phone me-1 text-white"></i>
                                {{ session('active_coupon_phone') }}</p>
                            <p class="mb-0"><i class="fas fa-search me-1 text-white"></i> {{ $remainingSlots }} search
                                slots remaining
                            </p>
                        </div>
                    </div>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#couponHelpModal">
                        <i class="fas fa-question-circle me-1"></i> Help
                    </button>
                </div>
            </div>
        </div>

        <!-- Help Modal -->
        <div class="modal fade" id="couponHelpModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">How Coupon Slots Work</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Each search uses one of your available slots. You can unlock full profiles of workers you're
                            interested in.</p>
                        <p><strong>Remaining slots:</strong> {{ $remainingSlots }}</p>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i> Need more slots? Contact our support team.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Find Skilled Professionals</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Connect with verified professionals including electricians, plumbers, artisans, and more for your home or
                business needs.
            </p>
        </div>

        <!-- Search Panel -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-filter me-2"></i> Refine Your Search</h5>
                <form method="GET" action="{{ route('couponSkilledWorkerServices') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Skill/Service</label>
                            <select name="skill" class="form-select">
                                <option value="">All Skills</option>
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->skill_name }}"
                                        {{ request('skill') == $skill->skill_name ? 'selected' : '' }}>
                                        {{ $skill->skill_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Location</label>
                            <select name="state_of_residence" class="form-select">
                                <option value="">All States</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state }}"
                                        {{ request('state_of_residence') == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Local Government</label>
                            <input type="text" name="lga_of_origin" class="form-control" placeholder="Enter LGA"
                                value="{{ request('lga_of_origin') }}">
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('couponSkilledWorkerServices') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Search Professionals
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        @if ($workers->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <h4 class="mb-3">No Professionals Found</h4>
                    <p class="text-muted">We couldn't find any professionals matching your criteria.</p>
                    <a href="{{ route('couponSkilledWorkerServices') }}" class="btn btn-outline-primary">
                        <i class="fas fa-undo me-1"></i> Clear Filters
                    </a>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($workers as $worker)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                            <!-- Profile Header -->
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <div class="justify-content-between align-items-start">
                                    <div>
                                        <span class="badge bg-success bg-opacity-10 text-success mb-2">
                                            <i class="fas fa-check-circle me-1"></i> Verified
                                        </span>
                                        <h5 class="mb-1">{{ $worker->name }} {{ $worker->last_name }}</h5>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $worker->state_of_residence }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Body -->
                            <div class="card-body pt-0">
                                <!-- Profile Image -->
                                <div class="text-center mb-3">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ $worker->profile_photo_path ? asset('public/build/assets/image/profile_photo/' . $worker->profile_photo_path) : asset('build/assets/image/slider1.jpg') }}"
                                            class="rounded-circle border border-3 border-white shadow-sm"
                                            style="width: 100px; height: 100px; object-fit: cover;" loading="lazy" decoding="async">
                                        <span
                                            class="position-absolute bottom-0 end-0 bg-white p-1 rounded-circle shadow-sm">
                                            <i class="fas fa-certificate text-success"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Skills -->
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-2">SKILLS</h6>
                                    <div class="flex-wrap gap-2">
                                        @if (is_array($worker->specialties))
                                            @foreach ($worker->specialties as $specialty)
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-success rounded-pill px-3 py-1">
                                                    <h5>{{ trim($specialty) }}</h5>
                                                </span>
                                            @endforeach
                                        @else
                                            @foreach (explode(',', $worker->specialties) as $specialty)
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-success rounded-pill px-3 py-1">
                                                    <h5>{{ trim($specialty) }}</h5>
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Experience -->
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-2">EXPERIENCE</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-briefcase text-success"></i>
                                        </div>
                                        <div>
                                            <p
                                                class="mb-0 fw-bold {{ $worker->years_of_experience ? '' : 'text-muted fst-italic' }}">
                                                {{ $worker->years_of_experience ? $worker->years_of_experience . ' years experience' : 'No experience information' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Locked Information -->
                                <div class="bg-light p-3 rounded-2 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-lock text-muted me-2"></i>
                                        <span class="small text-muted">Contact Information</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lock text-muted me-2"></i>
                                        <span class="small text-muted">Full Bio & Work History</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-white border-top-0 pt-0">
                                <form method="POST" action="{{ route('unlock.worker', $worker->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 py-2"
                                        @if ($remainingSlots <= 0) disabled @endif>
                                        <i class="fas fa-unlock me-2 text-white"></i> Unlock Full Profile ( with 1 Slot)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    {{ $workers->links() }}
                </nav>
            </div>
        @endif

        <!-- Results Count -->
        @if (!$workers->isEmpty())
            <div class="text-center text-muted small mt-3">
                Showing {{ $workers->firstItem() }} to {{ $workers->lastItem() }} of {{ $workers->total() }}
                professionals
            </div>
        @endif
    </div>

    <style>
        .hover-shadow {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .form-select,
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }
    </style>
@endsection
