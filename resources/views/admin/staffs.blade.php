@extends('layouts.adminmain')

@section('staffs')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Staffs</h1>
    </div>

    <!-- User Count Summary -->
    @include('partials.admin._usercountsummary')

    <!-- staffs ..................................................... -->
    @if (Auth::user()->user_role == 2)
        <div class="container py-3" style="background-color: #fac1bd;">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h5>See all Staffs</h5>
                        <div class="ms-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-admin-users"
                                    placeholder="Search admin users...">
                            </div>
                        </div>
                    </div>

                    <div id="admin-user-table">
                        @if ($admin->isEmpty())
                            <p>No admin users available at the moment.</p>
                        @else
                            <table class="table table-striped" id="admin-users-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>

                                        <th>Email</th>
                                        <th>Account Type</th>

                                        <th>Gender</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-users-body">
                                    @php $counter = 1; @endphp
                                    @foreach ($admin as $user)
                                        <tr class="admin-user-row">
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $user->name }}</td>

                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->user_role == 0)
                                                    Staff
                                                @elseif ($user->user_role == 1)
                                                    Admin
                                                @elseif ($user->user_role == 2)
                                                    Super Admin
                                                @endif
                                            </td>

                                            <td>{{ ucfirst($user->gender) }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#admin-users{{ $user->id }}">Preview</button>
                                                @include('partials.admin.modals._archieveUsersButton')

                                            </td>
                                        </tr>
                                        <!-- Modal Dialog Scrollable -->
                                        <div class="modal fade" id="admin-users{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="previewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="previewModalLabel">Staff
                                                            Profile
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Profile Picture and Basic Info -->
                                                        <div class="d-flex align-items-center mb-4">


                                                            @if ($user->profile_photo_path)
                                                                <img src="{{ $user->profile_photo_path ? asset('build/assets/image/profile_photo/' . $user->profile_photo_path) : 'default-avatar.png' }}"
                                                                    alt="Profile Photo" class="rounded-circle me-3"
                                                                    width="100" height="100" loading="lazy" decoding="async">
                                                            @else
                                                                <x-img src="build/assets/image/slider1.jpg" id="profile-photo-preview" alt="Profile Photo" class="img-thumbnail" style="width: 150px; height: 150px;" loading="lazy" decoding="async" />
                                                            @endif
                                                            <div>
                                                                <h3 class="mb-0">{{ $user->name }}
                                                                    {{ $user->middle_name }}
                                                                    {{ $user->last_name }}</h3>
                                                                <p class="text-muted mb-1">
                                                                    {{ ucfirst($user->account_type) }}</p>
                                                                <p class="text-muted">{{ $user->email }}</p>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <!-- Role Assignment (Only visible to admins) -->
                                                        @if (Auth::user()->user_role >= 1)
                                                            <!-- Only Super Admin or Admin can see this -->
                                                            <h6><strong>Assign Role</strong></h6>
                                                            <form action="{{ route('admin.updateRole', $user->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="role{{ $user->id }}"
                                                                        class="form-label">User
                                                                        Role</label>
                                                                    <select id="role{{ $user->id }}" name="role"
                                                                        class="form-select">
                                                                        <option value="0"
                                                                            {{ $user->user_role == 0 ? 'selected' : '' }}>
                                                                            Staff</option>
                                                                        <option value="1"
                                                                            {{ $user->user_role == 1 ? 'selected' : '' }}>
                                                                            Admin</option>
                                                                        <option value="2"
                                                                            {{ $user->user_role == 2 ? 'selected' : '' }}>
                                                                            Super Admin
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Role</button>
                                                            </form>
                                                            <hr>
                                                        @endif

                                                        <!-- Additional Information Sections -->
                                                        <h6><strong>Personal Information</strong></h6>
                                                        <!-- Rest of the modal content as before -->
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

                            <div class="d-block text-center card-footer">
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $admin->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <br>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchUsers = document.getElementById('search-users');
            const searchAdminUsers = document.getElementById('search-admin-users');

            // Function to filter users based on input
            function filterUsers(input, rows) {
                const filter = input.value.toLowerCase();
                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const middleName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const lastName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    const accountType = row.querySelector('td:nth-child(6)').textContent
                        .toLowerCase(); // Account Type
                    const phone = row.querySelector('td:nth-child(7)').textContent.toLowerCase(); // Phone
                    const gender = row.querySelector('td:nth-child(8)').textContent.toLowerCase(); // Gender

                    // Check if any of the fields match the search input
                    if (
                        name.includes(filter) ||
                        middleName.includes(filter) ||
                        lastName.includes(filter) ||
                        email.includes(filter) ||
                        accountType.includes(filter) ||
                        phone.includes(filter) ||
                        gender.includes(filter)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Event listener for user search
            searchUsers.addEventListener('keyup', () => {
                const userRows = document.querySelectorAll('#users-body .user-row');
                filterUsers(searchUsers, userRows);
            });

            // Event listener for admin user search
            searchAdminUsers.addEventListener('keyup', () => {
                const adminUserRows = document.querySelectorAll('#admin-users-body .admin-user-row');
                filterUsers(searchAdminUsers, adminUserRows);
            });
        });
    </script>



@endsection
