@extends('layouts.adminmain')

@section('adminskilledworkers')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Skilled Workers</h1>
    </div>

    <!-- User Count Summary -->
    @include('partials.admin._usercountsummary')

    <!-- Search Skilled Workers -->
    <div class="container-fluid py-3" style="background-color: #bdfac7;">
        <div class="card">
            <div class="card-body">
                <!-- Header with Search -->
                <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 row">
                    <h5 class="mb- mb-md-0 col-md-4">See all Skilled Workers</h5>
                    <div class="ms-md-auto w-100 w-md-auto col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="search-users"
                                placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <!-- User Table -->
                <div id="user-table">
                    @if ($skilledworkers->isEmpty())
                        <p class="text-center py-4">No users available at the moment.</p>
                    @else
                        <!-- Responsive Table Container -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="users-table">
                                <thead class="d-none d-md-table-header-group">
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Last Name</th>
                                        <th>State of Residence</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="users-body">
                                    @php $counter = 1; @endphp
                                    @foreach ($skilledworkers as $user)
                                        <tr class="user-row">
                                            <!-- Mobile-first table cells -->
                                            <td data-label="No." class="fw-bold">{{ $counter++ }}</td>
                                            <td data-label="Name">{{ $user->name }}</td>
                                            <td data-label="Last Name">{{ $user->last_name }}</td>
                                            <td data-label="state_of_residence">{{ $user->state_of_residence ?? 'N/A' }}
                                            </td>
                                            <td data-label="Date" class="text-nowrap">
                                                {{ $user->created_at->format('M d, Y') }}</td>
                                            <td data-label="Action" class="text-center">
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#users{{ $user->id }}">View</button>

                                                @include('partials.admin.modals._archieveUsersButton')


                                                <!-- Suspend User Modal -->
                                                <div class="modal fade" id="suspendUser{{ $user->id }}" tabindex="-1"
                                                    aria-labelledby="suspendModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="suspendModalLabel">Suspend User
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to suspend {{ $user->name }}?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('users.suspend', $user->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    {{-- @method('POST') --}}
                                                                    <button type="submit"
                                                                        class="btn btn-warning">Suspend</button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Suspend User Modal -->
                                                <!-- Modal Dialog Scrollable -->
                                                <div class="modal fade" id="users{{ $user->id }}" tabindex="-1"
                                                    aria-labelledby="previewModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="previewModalLabel">User Profile
                                                                    Details</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <!-- Profile Header Section -->
                                                                <div class="d-flex align-items-start mb-4">
                                                                    <!-- Profile Picture - Larger and with shadow -->
                                                                    <div class="me-4 text-center">
                                                                        @if ($user->profile_photo_path)
                                                                            <img src="{{ asset('build/assets/image/profile_photo/' . $user->profile_photo_path) }}"
                                                                                alt="Profile Photo"
                                                                                class="rounded-circle shadow-lg border border-3 border-primary"
                                                                                width="150" height="150"
                                                                                onerror="this.onerror=null;this.src='{{ asset('build/assets/image/slider1.jpg') }}'">
                                                                        @else
                                                                            <img src="{{ asset('build/assets/image/slider1.jpg') }}"
                                                                                alt="Profile Photo"
                                                                                class="rounded-circle shadow-lg border border-3 border-primary"
                                                                                width="150" height="150">
                                                                        @endif
                                                                        <div class="mt-2">
                                                                            <span class="badge bg-info text-dark fs-6">
                                                                                {{ ucfirst($user->applicant_type) }} Worker
                                                                            </span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- User Info -->
                                                                    <div class="flex-grow-1">
                                                                        <h2 class="mb-2 text-primary">{{ $user->name }}
                                                                            {{ $user->middle_name }}
                                                                            {{ $user->last_name }} <br>
                                                                            <span class="text-dark">
                                                                                {{ is_array($user->specialties) ? implode(', ', $user->specialties) : $user->specialties }}
                                                                            </span>

                                                                        </h2>

                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                                                            <span
                                                                                class="text-break">{{ $user->email }}</span>
                                                                        </div>

                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <i class="fas fa-phone me-2 text-muted"></i>
                                                                            <span>{{ $user->phone ?? 'N/A' }}</span>
                                                                        </div>

                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="fas fa-birthday-cake me-2 text-muted"></i>
                                                                            <span>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('M d, Y') : 'N/A' }}</span>
                                                                            <span class="mx-2">•</span>
                                                                            <i
                                                                                class="fas fa-venus-mars me-2 text-muted"></i>
                                                                            <span>{{ ucfirst($user->gender) ?? 'N/A' }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Information Sections -->
                                                                <div class="row g-4">
                                                                    <!-- Contact Information -->
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-primary">
                                                                            <div class="card-header bg-light">
                                                                                <h5 class="mb-0"><i
                                                                                        class="fas fa-address-card me-2"></i>
                                                                                    Contact Information</h5>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="mb-3">
                                                                                    <h6 class="text-muted mb-2"><i
                                                                                            class="fas fa-map-marker-alt me-2"></i>
                                                                                        Address</h6>
                                                                                    <p class="ps-4">
                                                                                        {{ $user->address ?? 'N/A' }}</p>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <h6 class="text-muted mb-2"><i
                                                                                            class="fas fa-map-marked-alt me-2"></i>
                                                                                        Location Details</h6>
                                                                                    <ul class="list-unstyled ps-4">
                                                                                        <li class="mb-1">
                                                                                            <strong>Country:</strong>
                                                                                            {{ $user->country_of_residence ?? 'N/A' }}
                                                                                        </li>
                                                                                        <li class="mb-1">
                                                                                            <strong>State:</strong>
                                                                                            {{ $user->state_of_residence ?? 'N/A' }}
                                                                                        </li>
                                                                                        <li>
                                                                                            <strong>Landmark:</strong>
                                                                                            {{ $user->landmark ?? 'N/A' }}
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Additional Information -->
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100 border-primary">
                                                                            <div class="card-header bg-light">
                                                                                <h5 class="mb-0"><i
                                                                                        class="fas fa-info-circle me-2"></i>
                                                                                    Additional Details</h5>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <!-- Add any additional user information here -->
                                                                                <div class="alert alert-info">
                                                                                    <i class="fas fa-user-tag me-2"></i>
                                                                                    Registered as
                                                                                    {{ ucfirst($user->applicant_type) }}
                                                                                    Worker
                                                                                </div>

                                                                                <!-- Example: Add verification status if available -->
                                                                                <div
                                                                                    class="d-flex align-items-center mb-3">
                                                                                    <div class="me-3">
                                                                                        <i
                                                                                            class="fas fa-shield-alt fa-2x text-{{ $user->verified ? 'success' : 'secondary' }}"></i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <h6 class="mb-0">Account Status
                                                                                        </h6>
                                                                                        <span class="text-muted">
                                                                                            {{ $user->verified ? 'Verified' : 'Not Verified' }}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Example: Add registration date -->
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="me-3">
                                                                                        <i
                                                                                            class="fas fa-calendar-alt fa-2x text-primary"></i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <h6 class="mb-0">Member Since
                                                                                        </h6>
                                                                                        <span class="text-muted">
                                                                                            {{ $user->created_at->format('M d, Y') }}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-outline-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    <i class="fas fa-times me-1"></i> Close
                                                                </button>
                                                                {{-- <button type="button" class="btn btn-primary">
                                                                    <i class="fas fa-download me-1"></i> Download Profile
                                                                </button> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Add this CSS for better image display -->
                                                <style>
                                                    .profile-img-container {
                                                        position: relative;
                                                        width: 150px;
                                                        height: 150px;
                                                    }

                                                    .profile-img-container img {
                                                        object-fit: cover;
                                                        width: 100%;
                                                        height: 100%;
                                                    }
                                                </style>
                                                <!-- End Modal Dialog Scrollable -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                            <p class="mb-2 mb-md-0 text-muted small">
                                Showing {{ $skilledworkers->firstItem() }} to {{ $skilledworkers->lastItem() }} of
                                {{ $skilledworkers->total() }} entries
                            </p>
                            <nav class="mt-2 mt-md-0">
                                {{ $skilledworkers->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals remain unchanged -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchLiveInUsers = document.getElementById('search-livein-users');
            const searchUsers = document.getElementById('search-users');

            // Function to filter users based on input
            function filterUsers(input, rows) {
                const filter = input.value.toLowerCase();
                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                    // Check if any of the fields match the search input
                    if (
                        name.includes(filter) ||
                        lastName.includes(filter) ||
                        status.includes(filter)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Event listener for Live-Out Applicants search
            if (searchLiveInUsers) {
                searchLiveInUsers.addEventListener('keyup', () => {
                    const liveInUserRows = document.querySelectorAll('#users-body .user-row');
                    filterUsers(searchLiveInUsers, liveInUserRows);
                });
            }

            // Event listener for all Applicants search
            if (searchUsers) {
                searchUsers.addEventListener('keyup', () => {
                    const userRows = document.querySelectorAll('#users-body .user-row');
                    filterUsers(searchUsers, userRows);
                });
            }
        });
    </script>


@endsection
