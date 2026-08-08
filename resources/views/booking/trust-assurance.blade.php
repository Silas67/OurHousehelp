@extends('layouts.usermain')

@section('booking-trust-assurance')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Trust & Safety Assurance</h3>
            <p class="text-muted">Your safety is our priority. Here's how we protect you.</p>
        </div>

        <!-- Selected Staff Summary -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center p-4">
                @if($selectedStaff->profile_photo_path)
                    <img src="{{ asset('storage/' . $selectedStaff->profile_photo_path) }}"
                        class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <span class="text-white fs-3 fw-bold">{{ strtoupper(substr($selectedStaff->name, 0, 1)) }}</span>
                    </div>
                @endif
                <h5 class="fw-bold">{{ $selectedStaff->name }} {{ substr($selectedStaff->last_name ?? '', 0, 1) }}.</h5>
                <p class="text-muted mb-0">Your selected staff</p>
            </div>
        </div>

        <!-- Verification Badges -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Verification Status</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded bg-light">
                            @if($selectedStaff->id_verified)
                                <i class="fas fa-id-card fa-2x text-success mb-2"></i>
                                <div class="fw-bold text-success">ID Verified</div>
                            @else
                                <i class="fas fa-id-card fa-2x text-warning mb-2"></i>
                                <div class="fw-bold text-warning">ID Pending</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded bg-light">
                            @if($selectedStaff->background_checked)
                                <i class="fas fa-user-shield fa-2x text-success mb-2"></i>
                                <div class="fw-bold text-success">Background Checked</div>
                            @else
                                <i class="fas fa-user-shield fa-2x text-warning mb-2"></i>
                                <div class="fw-bold text-warning">Background Pending</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 border rounded bg-light">
                            @if($selectedStaff->guarantors_forms_uploaded)
                                <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                <div class="fw-bold text-success">Guarantor Confirmed</div>
                            @else
                                <i class="fas fa-handshake fa-2x text-warning mb-2"></i>
                                <div class="fw-bold text-warning">Guarantor Pending</div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($selectedStaff->address_confirmed)
                <div class="text-center mt-3">
                    <span class="badge bg-success fs-6 py-2 px-3">
                        <i class="fas fa-map-marker-alt me-1"></i>Address Confirmed
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Optional: Request Call -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Want extra assurance?</h5>
                <p class="text-muted">
                    You can request our team to schedule a brief introduction call with the staff
                    before you proceed to payment. This helps build confidence.
                </p>

                @if($serviceRequest->call_with_staff_requested)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Call request submitted. Our team will contact you to schedule.
                    </div>
                @else
                    <div class="d-flex gap-3">
                        <form action="{{ route('booking.request-call', $serviceRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-phone me-2"></i>Request Call with Staff
                            </button>
                        </form>
                        <a href="{{ route('booking.management-plans', $serviceRequest->id) }}"
                            class="btn btn-primary">
                            Skip & Continue <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($serviceRequest->call_with_staff_requested)
        <div class="text-center">
            <a href="{{ route('booking.management-plans', $serviceRequest->id) }}"
                class="btn btn-primary btn-lg px-5">
                Continue to Plans <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
