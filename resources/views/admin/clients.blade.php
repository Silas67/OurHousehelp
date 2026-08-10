@extends('layouts.adminmain')

@section('clients')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Clients</h1>
    </div>

    <!-- User Count Summary -->
    @include('partials.admin._usercountsummary')

    <!-- Clients Table Section -->
    <div class="container py-3" style="background-color: #bdc1fa;">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h5 class="mb-3 mb-md-0">All Clients</h5>
                    <div class="w-100 w-md-50">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="search-users" placeholder="Search clients...">
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const searchInput = document.getElementById('search-users');
                        
                        if (searchInput) {
                            searchInput.addEventListener('keyup', function() {
                                const filter = this.value.toLowerCase();
                                const rows = document.querySelectorAll('#users-body tr');
                                
                                rows.forEach(row => {
                                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                                    const lastName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                                    row.style.display = (name.includes(filter) || lastName.includes(filter)) ? '' : 'none';
                                });
                            });
                        }
                    });
                </script>

                <div id="user-table">
                    @if ($clients->isEmpty())
                        <div class="alert alert-info text-center py-3">
                            <i class="fas fa-info-circle me-2"></i>No clients available at the moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle" id="users-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="users-body">
                                    @foreach ($clients as $index => $user)
                                        <tr>
                                            <td>{{ $index + $clients->firstItem() }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->last_name ?? 'N/A' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#userModal{{ $user->id }}"
                                                        title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @include('partials.admin.modals._archieveUsersButton')
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- User Details Modal -->
                                        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="userModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="userModalLabel">Client Details</h5>
                                                        <button type="button" class="btn-close btn-close-white" 
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Profile Section -->
                                                        <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                                                            <div class="me-md-4 mb-3 mb-md-0">
                                                                @if ($user->profile_photo_path)
                                                                    <img src="{{ asset('storage/profile_photos/' . $user->profile_photo_path) }}" 
                                                                        alt="Profile Photo" 
                                                                        class="rounded-circle"
                                                                        width="120" height="120" loading="lazy" decoding="async">
                                                                @else
                                                                    <x-img src="build/assets/image/default-profile.png" alt="Default Profile" class="rounded-circle" width="120" height="120" loading="lazy" decoding="async" />
                                                                @endif
                                                            </div>
                                                            <div class="text-center text-md-start">
                                                                <h3>{{ $user->name }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }}</h3>
                                                                <span class="badge bg-secondary">{{ ucfirst($user->account_type) }}</span>
                                                                <p class="mt-2 mb-1"><i class="fas fa-envelope me-2"></i>{{ $user->email }}</p>
                                                                <p><i class="fas fa-phone me-2"></i>{{ $user->phone ?? 'N/A' }}</p>
                                                            </div>
                                                        </div>

                                                        <!-- Details Sections -->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card mb-3">
                                                                    <div class="card-header bg-light">
                                                                        <strong>Personal Information</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>Gender:</strong> {{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</p>
                                                                        <p><strong>Date of Birth:</strong> {{ $user->dob ? $user->dob->format('M d, Y') : 'N/A' }}</p>
                                                                        <p><strong>NIN:</strong> {{ $user->nin ?? 'N/A' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="card mb-3">
                                                                    <div class="card-header bg-light">
                                                                        <strong>Location Information</strong>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>
                                                                        <p><strong>State of Origin:</strong> {{ $user->state_of_origin ?? 'N/A' }}</p>
                                                                        <p><strong>LGA of Origin:</strong> {{ $user->lga_of_origin ?? 'N/A' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Additional Information -->
                                                        <div class="card mb-3">
                                                            <div class="card-header bg-light">
                                                                <strong>Additional Information</strong>
                                                            </div>
                                                            <div class="card-body">
                                                                <p><strong>Status:</strong> 
                                                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                                                        {{ ucfirst($user->status) }}
                                                                    </span>
                                                                </p>
                                                                <p><strong>Account Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                                                            </div>
                                                        </div>

                                                        <!-- ID Card Section -->
                                                        @if($user->id_card)
                                                        <div class="card">
                                                            <div class="card-header bg-light">
                                                                <strong>ID Verification</strong>
                                                            </div>
                                                            <div class="card-body text-center">
                                                                <img src="{{ \App\Support\SecureDocument::url('idcards', $user->id_card) }}" 
                                                                    alt="ID Card" 
                                                                    class="img-fluid rounded border" 
                                                                    style="max-height: 200px;" loading="lazy" decoding="async">
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ route('guarantors.archived', $user->id) }}" 
                                                           class="btn btn-outline-secondary me-auto">
                                                            <i class="fas fa-archive me-1"></i> Archived Records
                                                        </a>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                            <div class="mb-2 mb-md-0">
                                Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} entries
                            </div>
                            {{ $clients->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection