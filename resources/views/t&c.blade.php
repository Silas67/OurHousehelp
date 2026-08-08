@extends('layouts.main')

@section('t&c')
    <div class="container py-5">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-dark mb-3">Terms and Conditions</h1>
            <p class="lead text-muted">Please review our terms and conditions carefully before using our services</p>            
        </div>

        <hr class="my-5">

        <!-- Services Selection Section -->
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h4 class="card-title mb-0 d-flex align-items-center">
                            <i class="fas fa-list-check text-success me-2"></i>
                            Available Services
                        </h4>
                    </div>
                    <div class="card-body">
                        @forelse ($allServices as $service)
                            <div class="service-item card mb-3 border hover-shadow transition-all">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                <div class="service-icon me-3">
                                                    <i class="{{ $service->icon_class ?? 'fas fa-cogs' }} fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 fw-semibold">{{ $service->service_name }}</h5>
                                                    @if($service->description)
                                                        <p class="text-muted mb-0 small">{{ Str::limit($service->description, 120) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="button" class="btn btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#serviceModal-{{ $service->id }}">
                                                <i class="fas fa-eye me-1"></i>
                                                View Terms
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Service Details -->
                            <div class="modal fade" id="serviceModal-{{ $service->id }}" tabindex="-1"
                                aria-labelledby="serviceModalLabel-{{ $service->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-success text-white">
                                            <div class="modal-title d-flex align-items-center">
                                                <i class="{{ $service->icon_class ?? 'fas fa-cogs' }} me-2 text-white" ></i>
                                                <div>
                                                    <h5 class="mb-0">{{ ucfirst($service->service_name) }}</h5>
                                                    <small class="opacity-75">Terms and Conditions</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" 
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            @if($service->dos_and_donts)
                                                <div class="terms-content">
                                                    {!! $service->dos_and_donts !!}
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No additional details available for this service.</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1 text-white" ></i>
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                                <i class="fas fa-check me-1 text-white" ></i>
                                                I Understand
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Services Available</h5>
                                <p class="text-muted">There are currently no services to display.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection