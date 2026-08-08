@extends('layouts.adminmain')

@section('applicants')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Applicants</h1>
    </div>

    <!-- User Count Summary -->
    @include('partials.admin._usercountsummary')
    <style>
        .alert {
            position: relative;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .btn-close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 1.25rem 1rem;
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 15000);
        });
    </script>
    <!-- Live-Out Applicants -->
    <div class="container py-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <h5>Live-Out Applicants</h5>
                    <div class="ms-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-livein-users"
                                placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <div id="user-table">
                    @if ($approvedusers->isEmpty())
                        <p>No users available at the moment.</p>
                    @else
                        <table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Last Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-body">
                                @php $counter = 1; @endphp
                                @foreach ($approvedusers as $user)
                                    <tr class="user-row">
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>
                                            <span
                                                class="badge 
                                @if ($user->approval_status === 'approved') bg-success 
                                @elseif($user->approval_status === 'pending') bg-warning text-dark 
                                @elseif($user->approval_status === 'rejected') bg-danger @endif">
                                                {{ ucfirst($user->approval_status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @if ($user->approval_status !== 'approved')
                                                    <form action="{{ route('admin.approve.user', $user->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Approve">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.unapprove.user', $user->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                            title="Remove Approval">
                                                            <i class="fas fa-times"></i> Unapprove
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.archieve.user', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Archieve User">
                                                        <i class="fas fa-cancel"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('users.delete', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                                        hidden>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>


                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#users{{ $user->id }}"><i
                                                        class="fas fa-eye"></i></button>

                                                <!-- Modal Dialog Scrollable -->
                                                @include('partials.admin.modals._applicantInfo')
                                                <!-- End Modal Dialog Scrollable -->

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination for Approved Users -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $approvedusers->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- Live-In Applicants -->
    <div class="container-fluid py-3" style="background-color: #bdfac7;">
        <div class="card">
            <div class="card-body">
                <!-- Header with Search -->
                <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 gap-2">
                    <h5>Live-In Applicants</h5>
                    <div class="ms-md-auto w-50 w-md-auto">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="search-users"
                                placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <!-- User Table -->
                <div id="user-table">
                    @if ($users->isEmpty())
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
                                    @foreach ($users as $user)
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

                                                <!-- Suspend User Modal -->
                                                <div class="modal fade" id="suspendUser{{ $user->id }}" tabindex="-1"
                                                    aria-labelledby="suspendModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="suspendModalLabel">Suspend
                                                                    User
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
                                                @include('partials.admin.modals._applicantInfo')
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
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                {{ $users->total() }} entries
                            </p>
                            <nav class="mt-2 mt-md-0">
                                {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
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
