<div class="modal fade" id="users{{ $user->id }}" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title" id="userModalLabel{{ $user->id }}">

                    <i class="fas fa-user-circle me-2"></i>User Profile: {{ $user->name }}

                </h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <div class="container-fluid">

                    <!-- Profile Header Section -->

                    <div class="row mb-4">

                        <div class="col-md-3 text-center">

                            <div class="profile-image-container mb-3">

                                @if($user->profile_photo_path)

                                        <img src="{{ asset('public/build/assets/image/profile_photo/' . $user->profile_photo_path) }}"

                                         class="img-thumbnail rounded-circle shadow" 

                                         alt="Profile Photo"

                                         style="min-width: 150px; min-height: 150px; object-fit: cover;">

                                @else

                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary"

                                         style="width: 150px; height: 150px;">

                                        <i class="fas fa-user fa-3x"></i>

                                    </div>

                                @endif

                            </div>

                            <div class="badge bg-{{ $user->availability ? 'success' : 'danger' }} mb-2">

                                {{ $user->availability ? 'Available' : 'Not Available' }}

                            </div>

                        </div>

                        <div class="col-md-9">

                            <h2 class="mb-1">{{ $user->name }} {{ $user->middle_name }} {{ $user->last_name }}</h2>

                            <div class="d-flex align-items-center mb-2">

                                <span class="badge bg-info me-2 text-capitalize">{{ $user->account_type }}</span>

                                @if($user->applicant_type)

                                    <span class="badge bg-secondary text-capitalize">{{ $user->applicant_type }}</span>

                                @endif

                            </div>

                            

                            <div class="row g-2">

                                <div class="col-md-6">

                                    <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i> {{ $user->email }}</p>

                                    <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i> {{ $user->phone ?? 'N/A' }}</p>

                                </div>

                                <div class="col-md-6">

                                    <p class="mb-1"><i class="fas fa-id-card me-2 text-muted"></i> NIN: {{ $user->nin ?? 'N/A' }}</p>

                                    <p class="mb-1"><i class="fas fa-birthday-cake me-2 text-muted"></i> DOB: {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('M d, Y') : 'N/A' }}</p>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- Main Content Tabs -->

                    <ul class="nav nav-tabs mb-4" id="userProfileTabs" role="tablist">

                        <li class="nav-item" role="presentation">

                            <button class="nav-link active text-dark" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal{{ $user->id }}" type="button" role="tab">

                                <i class="fas fa-user me-1"></i> Personal

                            </button>

                        </li>

                        <li class="nav-item" role="presentation">

                            <button class="nav-link text-dark" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact{{ $user->id }}" type="button" role="tab">

                                <i class="fas fa-address-book me-1"></i> Contact

                            </button>

                        </li>

                        <li class="nav-item" role="presentation">

                            <button class="nav-link text-dark" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents{{ $user->id }}" type="button" role="tab">

                                <i class="fas fa-file-alt me-1"></i> Documents

                            </button>

                        </li>

                        <li class="nav-item" role="presentation">

                            <button class="nav-link text-dark" id="guarantors-tab" data-bs-toggle="tab" data-bs-target="#guarantors{{ $user->id }}" type="button" role="tab">

                                <i class="fas fa-user-shield me-1"></i> Guarantors

                            </button>

                        </li>

                    </ul>



                    <div class="tab-content" id="userProfileTabsContent">

                        <!-- Personal Information Tab -->

                        <div class="tab-pane fade show active" id="personal{{ $user->id }}" role="tabpanel">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="card mb-3">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>

                                        </div>

                                        <div class="card-body">

                                            <dl class="row mb-0">

                                                <dt class="col-sm-5">Gender</dt>

                                                <dd class="col-sm-7 text-capitalize">{{ $user->gender ?? 'N/A' }}</dd>



                                                <dt class="col-sm-5">Years of Experience</dt>

                                                <dd class="col-sm-7">{{ $user->years_of_experience ?? 'N/A' }}</dd>



                                                <dt class="col-sm-5">State of Origin</dt>

                                                <dd class="col-sm-7">{{ $user->state_of_origin ?? 'N/A' }}</dd>



                                                <dt class="col-sm-5">LGA of Origin</dt>

                                                <dd class="col-sm-7">{{ $user->lga_of_origin ?? 'N/A' }}</dd>

                                            </dl>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="card mb-3">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Residence Information</h6>

                                        </div>

                                        <div class="card-body">

                                            <dl class="row mb-0">

                                                <dt class="col-sm-5">State of Residence</dt>

                                                <dd class="col-sm-7">{{ $user->state_of_residence ?? 'N/A' }}</dd>



                                                <dt class="col-sm-5">Country</dt>

                                                <dd class="col-sm-7">{{ $user->country ?? 'N/A' }}</dd>



                                                <dt class="col-sm-5">Residence Country</dt>

                                                <dd class="col-sm-7">{{ $user->country_of_residence ?? 'N/A' }}</dd>

                                            </dl>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            

                            <div class="card">

                                <div class="card-header bg-light">

                                    <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>Biography</h6>

                                </div>

                                <div class="card-body">

                                    <p>{{ $user->bio ?? 'No biography provided' }}</p>

                                </div>

                            </div>

                        </div>



                        <!-- Contact Information Tab -->

                        <div class="tab-pane fade" id="contact{{ $user->id }}" role="tabpanel">

                            <div class="card">

                                <div class="card-header bg-light">

                                    <h6 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Details</h6>

                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6">

                                            <h6 class="text-muted mb-3">Primary Contact</h6>

                                            <p><i class="fas fa-phone me-2"></i> {{ $user->phone ?? 'N/A' }}</p>

                                            <p><i class="fas fa-envelope me-2"></i> {{ $user->email }}</p>

                                        </div>

                                        <div class="col-md-6">

                                            <h6 class="text-muted mb-3">Address</h6>

                                            <address>

                                                {{ $user->address ?? 'N/A' }}<br>

                                                {{ $user->landmark ? $user->landmark.'<br>' : '' }}

                                                {{ $user->state_of_residence ?? '' }}

                                            </address>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- Documents Tab -->

                        <div class="tab-pane fade" id="documents{{ $user->id }}" role="tabpanel">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>ID Card</h6>

                                        </div>

                                        <div class="card-body text-center">

                                            @if($user->id_card)

                                                <img src="{{ asset('public/build/assets/image/idcards/' .$user->id_card) }}" 

                                                     class="img-fluid rounded border" 

                                                     alt="ID Card"

                                                     style="max-height: 400px;">

                                                <div class="mt-3">

                                                    <a href="{{ asset('public/build/assets/image/idcards/' .$user->id_card) }}" 

                                                       class="btn btn-sm btn-outline-primary" 

                                                       target="_blank" download>

                                                        <i class="fas fa-download me-1"></i> Download

                                                    </a>

                                                </div>

                                            @else

                                                <div class="py-4 text-muted">

                                                    <i class="fas fa-file-image fa-3x mb-2"></i>

                                                    <p>No ID card uploaded</p>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="card">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-file-contract me-2"></i>Other Documents</h6>

                                        </div>

                                        <div class="card-body">

                                            @if($user->documents && count($user->documents) > 0)

                                                <ul class="list-group list-group-flush">

                                                    @foreach($user->documents as $document)

                                                        <li class="list-group-item d-flex justify-content-between align-items-center">

                                                            {{ $document->name }}

                                                            <a href="{{ asset('public/build/assets/image/idcards/' .$document->path) }}" 

                                                               class="btn btn-sm btn-outline-secondary" 

                                                               target="_blank">

                                                                <i class="fas fa-eye me-1"></i> View

                                                            </a>

                                                        </li>

                                                    @endforeach

                                                </ul>

                                            @else

                                                <div class="text-center py-4 text-muted">

                                                    <i class="fas fa-folder-open fa-3x mb-2"></i>

                                                    <p>No additional documents</p>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- Guarantors Tab -->

                        <div class="tab-pane fade" id="guarantors{{ $user->id }}" role="tabpanel">

                            <div class="row">

                                <!-- Guarantor 1 -->

                                <div class="col-md-6">

                                    <div class="card mb-4">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Guarantor 1</h6>

                                        </div>

                                        <div class="card-body">

                                            @if($user->guarantors->count() > 0)

                                                @foreach($user->guarantors as $guarantor)

                                                    <div class="mb-3">

                                                        <h5>{{ $guarantor->guarantor1_first_name }} {{ $guarantor->guarantor1_last_name }}</h5>

                                                        <p class="text-muted">{{ $guarantor->guarantor1_relationship }} | {{ $guarantor->guarantor1_occupation }}</p>

                                                        

                                                        <div class="row g-2 mb-3">

                                                            <div class="col-12">

                                                                <p><i class="fas fa-phone me-2"></i> {{ $guarantor->guarantor1_phone }}</p>

                                                            </div>

                                                            <div class="col-12">

                                                                <p><i class="fas fa-envelope me-2"></i> {{ $guarantor->guarantor1_email ?? 'N/A' }}</p>

                                                            </div>

                                                            <div class="col-12">

                                                                <address class="mb-4">

                                                                    <i class="fas fa-map-marker-alt me-2"></i> {{ $guarantor->guarantor1_address }}

                                                                </address>

                                                            </div>

                                                            <div class="col-12">

                                                                <div class="mb-0">

                                                                    <i class="fas fa-id-card me-2"></i> {{ $guarantor->guarantor1_nin }}

                                                                </div>

                                                            </div>

                                                        </div>

                                                        

                                                        <div class="row g-2">

                                                            @if($guarantor->guarantor1_passport_path)

                                                                <div class="col-md-6 text-center">

                                                                    <img src="{{ asset('storage/'.$guarantor->guarantor1_passport_path) }}" 

                                                                         class="img-thumbnail mb-2" 

                                                                         alt="Passport"

                                                                         style="max-height: 120px;">

                                                                    <p class="small text-muted mb-0">Passport Photo</p>

                                                                </div>

                                                            @endif

                                                            @if($guarantor->guarantor1_signature_path)

                                                                <div class="col-md-6 text-center">

                                                                    <img src="{{ asset('storage/'.$guarantor->guarantor1_signature_path) }}" 

                                                                         class="img-thumbnail mb-2" 

                                                                         alt="Signature"

                                                                         style="max-height: 120px;">

                                                                    <p class="small text-muted mb-0">Signature</p>

                                                                </div>

                                                            @endif

                                                        </div>

                                                    </div>

                                                @endforeach

                                            @else

                                                <div class="text-center py-4 text-muted">

                                                    <i class="fas fa-user-slash fa-3x mb-2"></i>

                                                    <p>No guarantor information available</p>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                                

                                <!-- Guarantor 2 -->

                                <div class="col-md-6">

                                    <div class="card">

                                        <div class="card-header bg-light">

                                            <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Guarantor 2</h6>

                                        </div>

                                        <div class="card-body">

                                            @if($user->guarantor2s->count() > 0)

                                                @foreach($user->guarantor2s as $guarantor2)

                                                    <div class="mb-3">

                                                        <h5>{{ $guarantor2->guarantor2_first_name }} {{ $guarantor2->guarantor2_last_name }}</h5>

                                                        <p class="text-muted">{{ $guarantor2->guarantor2_relationship }} | {{ $guarantor2->guarantor2_occupation }}</p>

                                                        

                                                        <div class="row g-2 mb-3">

                                                            <div class="col-12">

                                                                <p><i class="fas fa-phone me-2"></i> {{ $guarantor2->guarantor2_phone }}</p>

                                                            </div>

                                                            <div class="col-12">

                                                                <p><i class="fas fa-envelope me-2"></i> {{ $guarantor2->guarantor2_email ?? 'N/A' }}</p>

                                                            </div>

                                                            <div class="col-12">

                                                                <address class="mb-4">

                                                                    <i class="fas fa-map-marker-alt me-2"></i> {{ $guarantor2->guarantor2_address }}

                                                                </address>

                                                            </div>

                                                            <div class="col-12">

                                                                <div class="mb-0">

                                                                    <i class="fas fa-id-card me-2"></i> {{ $guarantor->guarantor1_nin }}

                                                                </div>

                                                            </div>
                                                        </div>

                                                        

                                                        <div class="row g-2">

                                                            @if($guarantor2->guarantor2_passport_path)

                                                                <div class="col-md-6 text-center">

                                                                    <img src="{{ asset('storage/'.$guarantor2->guarantor2_passport_path) }}" 

                                                                         class="img-thumbnail mb-2" 

                                                                         alt="Passport"

                                                                         style="max-height: 120px;">

                                                                    <p class="small text-muted mb-0">Passport Photo</p>

                                                                </div>

                                                            @endif

                                                            @if($guarantor2->guarantor2_signature_path)

                                                                <div class="col-md-6 text-center">

                                                                    <img src="{{ asset('storage/'.$guarantor2->guarantor2_signature_path) }}" 

                                                                         class="img-thumbnail mb-2" 

                                                                         alt="Signature"

                                                                         style="max-height: 120px;">

                                                                    <p class="small text-muted mb-0">Signature</p>

                                                                </div>

                                                            @endif

                                                        </div>

                                                    </div>

                                                @endforeach

                                            @else

                                                <div class="text-center py-4 text-muted">

                                                    <i class="fas fa-user-slash fa-3x mb-2"></i>

                                                    <p>No second guarantor information available</p>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                            

                            <div class="text-end mt-3">

                                <a href="{{ route('guarantors.archived', $user->id) }}" class="btn btn-outline-secondary">

                                    <i class="fas fa-archive me-1"></i> View Archived Records

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    <i class="fas fa-times me-1"></i> Close

                </button>

                <a href="

                {{ route('admin.HouseHelp.edit', $user->id) }}

                 " class="btn btn-primary">

                    <i class="fas fa-edit me-1"></i> Edit Profile

                </a>

            </div>

        </div>

    </div>

</div>