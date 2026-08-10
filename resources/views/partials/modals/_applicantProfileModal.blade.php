<div class="modal fade modal-lg" id="viewprofile{{ $househelp->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle me-2"></i>
                    {{ $househelp->name }} {{ $househelp->last_name }}'s Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('partials._subscribe')
                <br>
                <div class="row">
                    <!-- Profile Photo Column -->
                    <div class="col-md-4 text-center mb-3">
                        <img src="{{ asset('public/build/assets/image/profile_photo/' . $househelp->profile_photo_path) }}"
                            alt="{{ $househelp->name }}'s photo" 
                            class="img-fluid rounded-circle border border-3 border-primary"
                            style="width: 180px; height: 180px; object-fit: cover;" loading="lazy" decoding="async">
                            
                        <!-- Rating Badge -->
                        <div class="mt-3">
                            <span class="badge bg-warning text-dark">
                                @for ($i = 0; $i < $househelp->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for ($i = 0; $i < 5 - $househelp->rating; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                                ({{ $househelp->totalCount ?? 0 }})
                            </span>
                            
                            <span class="badge bg-{{ $househelp->availability ? 'success' : 'danger' }}">
                                {{ $househelp->availability ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-venus-mars me-2"></i>Gender:</strong> 
                                            {{ ucfirst($househelp->gender) }}
                                        </p>
                                    </div>
                                    
                                    @if($househelp->applicant_type == 'skill')
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-tools me-2"></i>Specialty:</strong> 
                                            {{ $househelp->specialties }}
                                        </p>
                                    </div>
                                    @else
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-briefcase me-2"></i>Experience:</strong> 
                                            {{ $househelp->years_of_experience }}
                                        </p>
                                    </div>
                                    @endif

                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-location-dot me-2"></i>State:</strong> 
                                            {{ $househelp->state_of_residence }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($househelp->activeRequest && $hasApprovedPayment)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($househelp->applicant_type != 'skill')
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-birthday-cake me-2"></i>Date of Birth:</strong> 
                                            {{ $househelp->dob }}
                                        </p>
                                        <p><strong><i class="fas fa-map-marked-alt me-2"></i>State of Origin:</strong> 
                                            {{ $househelp->state_of_origin }}
                                        </p>
                                    </div>
                                    @endif
                                    
                                    <div class="col-md-6">
                                        <p><strong><i class="fas fa-map-marker-alt me-2"></i>State of Residence:</strong> 
                                            {{ $househelp->state_of_residence }}
                                        </p>
                                        <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> 
                                            {{ $househelp->phone }}
                                        </p>
                                    </div>
                                    
                                    <div class="col-12 mt-2">
                                        <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> 
                                            <a href="mailto:{{ $househelp->email }}" class="text-primary">
                                                {{ $househelp->email }}
                                            </a>
                                        </p>
                                        <p><strong><i class="fas fa-home me-2"></i>Address:</strong> 
                                            {{ $househelp->address }}
                                        </p>
                                        @if($househelp->landmark)
                                        <p><strong><i class="fas fa-map-pin me-2"></i>Landmark:</strong> 
                                            {{ $househelp->landmark }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($househelp->activeRequest)
                        <div class="alert alert-warning">
                            <i class="fas fa-lock me-2"></i> Complete your payment to view full contact details
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                @if ($hasApprovedPayment)
                    <a href="{{ route('applicant.show', ['id' => $househelp->id]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-user-circle me-1 text-white"></i> Full Profile ({{ $remainingSearches }} left)
                    </a>
                @elseif ($pendingPayment)
                    <button class="btn btn-primary" disabled>
                        <i class="fas fa-hourglass-half me-1"></i> Pending Approval
                    </button>
                @else
                {{-- @if ($househelp->applicant_type == 'skill')
                    <a href="{{ route('payments.paymentoptionskilledworkers') }}" class="btn btn-primary">
                        <i class="fas fa-unlock me-1 text-white"></i> Click here to Subscribe then View More
                    </a>
                @else --}}
                    {{-- <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-lock"></i>  Click here to Subscribe and then See More Detail
                    </a> --}}
                    <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-lock"></i>  Choose a Payment Plan to Continue
                    </a>
                {{-- @endif --}}
                    
                @endif
                
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>