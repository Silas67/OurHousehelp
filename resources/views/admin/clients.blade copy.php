@extends('layouts.adminmain')

@section('clients')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Clients</h1>
    </div>

    <!-- User Count Summary -->
    @include('partials.admin._usercountsummary')


    <!-- clients ..................................................... -->
    <div class="container py-3" style="background-color: #bdc1fa;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <h5>See all Clients</h5>
                    <div class="ms-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-users" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const searchInput = document.getElementById('search-users');

                        if (searchInput) {
                            searchInput.addEventListener('keyup', function() {
                                const filter = this.value.toLowerCase();
                                const rows = document.querySelectorAll('#users-body .user-row');

                                rows.forEach(row => {
                                    const text = row.textContent.toLowerCase();
                                    row.style.display = text.includes(filter) ? '' : 'none';
                                });
                            });
                        }
                    });
                </script>


                <div id="user-table">
                    @if ($users->isEmpty())
                        <p>No client available at the moment.</p>
                    @else
                        <table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>

                                    <th>Last Name</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="users-body">
                                @php $counter = 1; @endphp
                                @foreach ($clients as $user)
                                    <tr class="user-row">
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $user->name }}</td>

                                        <td>{{ $user->last_name }}</td>

                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#users{{ $user->id }}">Preview</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Dialog Scrollable -->
                                    <div class="modal fade" id="users{{ $user->id }}" tabindex="-1"
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
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal Dialog Scrollable -->
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                            <p class="mb-0 text-muted">
                                Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of
                                {{ $clients->total() }} entries
                            </p>
                            <nav class="pagination-sm">
                                {{ $clients->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <br>

@endsection
