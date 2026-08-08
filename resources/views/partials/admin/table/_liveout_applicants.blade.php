<div class="table-responsive">
    <table class="table table-hover" id="liveout-applicants-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                {{-- <th>Email</th> --}}
                {{-- <th>Phone</th> --}}
                <th>Gender</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applicants as $index => $user)
                <tr>
                    <td>{{ $index + $applicants->firstItem() }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->middle_name ?? 'N/A' }}</td>
                    <td>{{ $user->last_name }}</td>
                    {{-- <td>{{ $user->email }}</td> --}}
                    {{-- <td>{{ $user->phone ?? 'N/A' }}</td> --}}
                    <td>{{ $user->gender ?? 'N/A' }}</td>
                    <td>
                        <span
                            class="badge bg-{{ $user->approval_status === 'approved'
                                ? 'success'
                                : ($user->approval_status === 'pending'
                                    ? 'warning text-dark'
                                    : 'danger') }}">
                            {{ ucfirst($user->approval_status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @include('partials.admin.buttons._applicant_actions', ['user' => $user])
                    </td>
                </tr>

                <!-- Modal Dialog Scrollable -->
                @include('partials.admin.modals._applicantInfo')
                <!-- End Modal Dialog Scrollable -->
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4">No live-out applicants found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($applicants->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $applicants->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
