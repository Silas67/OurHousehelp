<div class="modal fade ios-modal" id="viewprofile{{ $househelp->id }}" tabindex="-1" 
     aria-labelledby="viewprofileLabel{{ $househelp->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md"> <!-- Changed to modal-md -->
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="viewprofileLabel{{ $househelp->id }}">
                    <i class="fas fa-user-circle me-2"></i>
                    {{ $househelp->name }} {{ $househelp->last_name }}'s Profile
                </h5>
                <button type="button" class="btn-close ios-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('partials._subscribe')
                <div class="row">
                    <!-- Profile Photo Column - Now centered above content -->
                    <div class="col-12 text-center mb-3">
                        <img src="{{ asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) }}"
                            alt="{{ $househelp->name }}'s photo" 
                            class="img-fluid rounded-circle border border-3 border-primary ios-modal-img"
                            style="width: 120px; height: 120px; object-fit: cover;">
                            
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark">
                                @for ($i = 0; $i < $househelp->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for ($i = 0; $i < 5 - $househelp->rating; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                                ({{ $househelp->totalCount ?? 0 }})
                            </span>
                            
                            <span class="badge bg-{{ $househelp->availability ? 'success' : 'danger' }} ms-1">
                                {{ $househelp->availability ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Details Column - Now single column layout -->
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong><i class="fas fa-venus-mars me-2"></i>Gender:</strong> 
                                            {{ ucfirst($househelp->gender) }}
                                        </p>
                                        @if($househelp->applicant_type == 'skill')
                                        <p><strong><i class="fas fa-tools me-2"></i>Specialty:</strong> 
                                            {{ $househelp->specialties }}
                                        </p>
                                        @else
                                        <p><strong><i class="fas fa-briefcase me-2"></i>Experience:</strong> 
                                            {{ $househelp->years_of_experience }} years
                                        </p>
                                        @endif
                                        <p><strong><i class="fas fa-location-dot me-2"></i>Location:</strong> 
                                            {{ $househelp->state_of_residence }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($househelp->activeRequest && $hasApprovedPayment)
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Details</h6>
                            </div>
                            <div class="card-body p-3">
                                @if($househelp->applicant_type != 'skill')
                                <p><strong><i class="fas fa-birthday-cake me-2"></i>Age:</strong> 
                                    {{ \Carbon\Carbon::parse($househelp->dob)->age }} years
                                </p>
                                <p><strong><i class="fas fa-map-marked-alt me-2"></i>Origin:</strong> 
                                    {{ $househelp->state_of_origin }}
                                </p>
                                @endif
                                
                                <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> 
                                    {{ $househelp->phone }}
                                </p>
                                <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                    <a href="mailto:{{ $househelp->email }}" class="text-primary">
                                        {{ $househelp->email }}
                                    </a>
                                </p>
                                <p><strong><i class="fas fa-home me-2"></i>Address:</strong> 
                                    {{ $househelp->address }}
                                </p>
                            </div>
                        </div>
                        @elseif($househelp->activeRequest)
                        <div class="alert alert-warning py-2">
                            <i class="fas fa-lock me-2"></i> Complete payment to view contact details
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light py-2">
                <div class="d-flex justify-content-between w-100">
                    <button type="button" class="btn btn-sm btn-outline-secondary ios-modal-close" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    
                    @if ($hasApprovedPayment)
                        <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}" 
                           class="btn btn-sm btn-primary ios-modal-btn">
                            <i class="fas fa-user-circle me-1"></i> Full Profile
                        </a>
                    @elseif ($pendingPayment)
                        <button class="btn btn-sm btn-primary ios-modal-btn" disabled>
                            <i class="fas fa-hourglass-half me-1"></i> Pending
                        </button>
                    @else
                        <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary ios-modal-btn">
                            <i class="fa-solid fa-lock me-1"></i> Subscribe
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* iOS Modal Fixes */
    .ios-modal {
        -webkit-overflow-scrolling: touch;
    }
    
    .ios-modal .modal-dialog {
        margin: 1.75rem auto;
        max-height: 85vh;
    }
    
    .ios-modal .modal-content {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Optimized Medium Modal Styling */
    .modal-md {
        max-width: 600px;
    }
    
    .modal-body {
        padding: 1.25rem;
    }
    
    .modal-body p {
        margin-bottom: 0.6rem;
        font-size: 0.95rem;
    }
    
    .modal-body strong {
        min-width: 100px;
        display: inline-block;
        font-weight: 500;
    }
    
    .ios-modal-img {
        width: 100px;
        height: 100px;
    }
    
    @media (max-width: 576px) {
        .modal-md {
            margin: 0.5rem auto;
            max-width: 95%;
        }
        
        .modal-body strong {
            min-width: 90px;
        }
        
        .ios-modal-img {
            width: 80px;
            height: 80px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // iOS-specific modal handling
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || 
                 (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    
    if (isIOS) {
        const modal = document.querySelector('#viewprofile{{ $househelp->id }}');
        
        if (modal) {
            // Store scroll position when opening
            modal.addEventListener('show.bs.modal', function() {
                document.body.style.top = `-${window.scrollY}px`;
                document.body.classList.add('ios-modal-open');
            });
            
            // Restore scroll position when closing
            modal.addEventListener('hidden.bs.modal', function() {
                const scrollY = document.body.style.top;
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.classList.remove('ios-modal-open');
                window.scrollTo(0, parseInt(scrollY || '0') * -1);
            });
        }
    }
});
</script>