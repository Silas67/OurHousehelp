@extends('layouts.usermain')

@section('booking-staff-details')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Your Staff Details</h3>
            <p class="text-muted">Payment confirmed - here are the full details of your assigned staff.</p>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white text-center">
                <i class="fas fa-unlock me-2"></i>Full Details Released
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        @if($selectedStaff->profile_photo_path)
                            <img src="{{ asset('storage/' . $selectedStaff->profile_photo_path) }}"
                                class="rounded-circle mb-3" width="150" height="150"
                                style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 150px; height: 150px;">
                                <span class="text-white display-4 fw-bold">
                                    {{ strtoupper(substr($selectedStaff->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        @if($selectedStaff->isFullyVerified())
                            <div><span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>Fully Verified</span></div>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <h4 class="fw-bold mb-3">
                            {{ $selectedStaff->name }}
                            {{ $selectedStaff->middle_name }}
                            {{ $selectedStaff->last_name }}
                        </h4>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <strong>Phone:</strong><br>
                                    <a href="tel:{{ $selectedStaff->phone }}" class="text-decoration-none fs-5">
                                        {{ $selectedStaff->phone }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:{{ $selectedStaff->email }}" class="text-decoration-none">
                                        {{ $selectedStaff->email }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <strong>Location:</strong><br>
                                    {{ $selectedStaff->city ?? '' }}{{ $selectedStaff->city ? ', ' : '' }}{{ $selectedStaff->state_of_residence }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-language text-info me-2"></i>
                                    <strong>Languages:</strong><br>
                                    {{ $selectedStaff->languages_list }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-briefcase text-warning me-2"></i>
                                    <strong>Experience:</strong><br>
                                    {{ $selectedStaff->years_of_experience ?? 'N/A' }} years
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-calendar-check text-success me-2"></i>
                                    <strong>Available From:</strong><br>
                                    {{ $selectedStaff->available_from ? $selectedStaff->available_from->format('M d, Y') : 'Immediately' }}
                                </div>
                            </div>
                        </div>

                        @if($selectedStaff->work_summary)
                        <div class="mt-3 p-3 bg-light rounded">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <strong>About:</strong><br>
                            {{ $selectedStaff->work_summary }}
                        </div>
                        @endif

                        @if($selectedStaff->specialties && count($selectedStaff->specialties) > 0)
                        <div class="mt-3">
                            <strong>Specialties:</strong><br>
                            @foreach($selectedStaff->specialties as $specialty)
                                <span class="badge bg-primary me-1 mb-1">{{ $specialty }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Next Steps</h5>
                @if($serviceRequest->deployment_type === 'self_managed')
                    <div class="alert alert-info">
                        <i class="fas fa-user me-2"></i>
                        <strong>Self-Managed:</strong> You can now contact your staff directly using the details above.
                        Your staff is expected to resume on
                        <strong>{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'your agreed date' }}</strong>.
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-building me-2"></i>
                        <strong>Company Managed:</strong> Our team will deploy your staff and handle all coordination.
                        Expect deployment on
                        <strong>{{ $serviceRequest->preferred_start_date ? $serviceRequest->preferred_start_date->format('M d, Y') : 'your agreed date' }}</strong>.
                    </div>
                @endif

                <div class="text-center">
                    <a href="{{ route('booking.deployment', $serviceRequest->id) }}"
                        class="btn btn-primary btn-lg px-5">
                        View Deployment Details <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
