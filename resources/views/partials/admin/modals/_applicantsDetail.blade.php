<div class="modal fade" id="approvedusers{{ $user->id }}" tabindex="-1"
    aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Profile Picture and Basic Info -->
                <div class="d-flex align-items-center mb-4">
                    @if ($user->profile_photo_path)
                        <img src="{{ $user->profile_photo_path ? asset('build/assets/image/profile_photo/' . $user->profile_photo_path) : 'default-avatar.png' }}"
                            alt="Profile Photo" class="rounded-circle me-3"
                            width="100" height="100">
                    @else
                        <img id="profile-photo-preview"
                            src="{{ asset('build/assets/image/slider1.jpg') }}"
                            alt="Profile Photo" class="img-thumbnail"
                            style="width: 150px; height: 150px;">
                    @endif
                    <div>
                        <h3 class="mb-0">{{ $user->name }}
                            {{ $user->middle_name }} {{ $user->last_name }}</h3>
                        <p class="text-muted mb-1">{{ ucfirst($user->account_type) }}
                        </p>
                        <p class="text-muted">{{ $user->email }}</p>
                        <h3 class="text-muted">Approval Status: {{ $user->approval_status }}</h3>
                    </div>
                </div>
                <hr>

                <!-- Personal Information -->
                <h6><strong>Personal Information</strong></h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Gender:</strong> {{ ucfirst($user->gender) }}</p>
                        <p><strong>Date of Birth:</strong>
                            {{ $user->dob ?? '' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>NIN:</strong> {{ $user->nin ?? 'N/A' }}</p>
                        <p><strong>Years of Experience:</strong>
                            {{ $user->years_of_experience ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr>

                <!-- Contact Information -->
                <h6><strong>Contact Information</strong></h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>State of Origin:</strong>
                            {{ $user->state_of_origin ?? 'N/A' }}</p>
                        <p><strong>State of Residence:</strong>
                            {{ $user->state_of_residence ?? 'N/A' }}</p>
                        <p><strong>LGA of Origin:</strong>
                            {{ $user->lga_of_origin ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr>

                <!-- Biography and Availability -->
                <h6><strong>Additional Information</strong></h6>
                <div class="mb-3">
                    <p><strong>Availability:</strong>
                        {{ $user->availability ? 'Available' : 'Not Available' }}</p>
                    {{-- <p><strong>Bio:</strong> {{ $user->bio ?? 'N/A' }}</p> --}}
                </div>
                <hr>

                <!-- ID Card Preview -->
                <h6><strong>ID Card</strong></h6>
                <div class="text-center mb-3">
                    @if ($user->id_card)
                        <img src="{{ asset('build/assets/image/idcards/' . $user->id_card) }}"
                            alt="ID Card" class="img-fluid rounded"
                            style="max-width: 200px;">
                    @else
                        <p>No ID card uploaded.</p>
                    @endif
                </div>

                <hr>
                <p><a href="{{ route('guarantors.archived', $user->id) }}"
                        class="btn btn-secondary">View Archived Records</a>
                </p>
                <hr>
                <!-- Inside the Modal -->
                <h6><strong>Guarantor 1 Details</strong></h6>

                @foreach ($user->guarantors as $guarantor)
                    <p><strong>Name:</strong> {{ $guarantor->guarantor1_first_name }}
                        {{ $guarantor->guarantor1_last_name }}</p>
                    <p><strong>Phone:</strong> {{ $guarantor->guarantor1_phone }}</p>
                    <p><strong>Email:</strong> {{ $guarantor->guarantor1_email ?? 'N/A' }}</p>
                    <p><strong>Relationship:</strong> {{ $guarantor->guarantor1_relationship }}
                    </p>
                    <p><strong>Occupation:</strong> {{ $guarantor->guarantor1_occupation }}</p>
                    <p><strong>Address:</strong> {{ $guarantor->guarantor1_address }}</p>
                    <div class="text-center">
                        @if ($guarantor->passport_path)
                            <img src="{{ asset('build/assets/image/passports/' . $guarantor->guarantor1_passport_path) }}"
                                alt="Passport Photo" class="img-thumbnail"
                                width="150" height="150">
                        @endif
                        @if ($guarantor->signature_path)
                            <img src="{{ asset('build/assets/image/signatures/' . $guarantor->guarantor1_signature_path) }}"
                                alt="Signature" class="img-thumbnail" width="150"
                                height="150">
                        @endif
                    </div>
                @endforeach

                <hr>

                <h6><strong>Guarantor 2 Details</strong></h6>
                @foreach ($user->guarantor2s as $guarantor2)
                    <p><strong>Name:</strong> {{ $guarantor2->guarantor2_first_name }}
                        {{ $guarantor2->guarantor2_last_name }}</p>
                    <p><strong>Phone:</strong> {{ $guarantor2->guarantor2_phone }}</p>
                    <p><strong>Email:</strong> {{ $guarantor2->guarantor2_email ?? 'N/A' }}</p>
                    <p><strong>Relationship:</strong> {{ $guarantor2->guarantor2_relationship }}
                    </p>
                    <p><strong>Occupation:</strong> {{ $guarantor2->guarantor2_occupation }}</p>
                    <p><strong>Address:</strong> {{ $guarantor2->guarantor2_address }}</p>
                    <div class="text-center">
                        @if ($guarantor2->passport_path)
                            <img src="{{ asset('build/assets/image/passports/' . $guarantor2->guarantor2_passport_path) }}"
                                alt="Passport Photo" class="img-thumbnail"
                                width="150" height="150">
                        @endif
                        @if ($guarantor2->signature_path)
                            <img src="{{ asset('build/assets/image/signatures/' . $guarantor2->guarantor2_signature_path) }}"
                                alt="Signature" class="img-thumbnail" width="150"
                                height="150">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
