<div class="table-responsive">
    <table class="table table-hover" id="livein-applicants-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                {{-- <th>Email</th> --}}
                {{-- <th>Phone</th> --}}
                <th>Gender</th>
                {{-- <th>State</th> --}}
                <th>Registered</th>
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
                    {{-- <td>{{ $user->state_of_residence ?? 'N/A' }}</td> --}}
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        @include('partials.admin.buttons._applicant_actions', ['user' => $user])
                    </td>
                </tr>
                <!-- Modal Dialog Scrollable -->
                @include('partials.admin.modals._applicantInfo')
                <!-- End Modal Dialog Scrollable -->
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">No live-in applicants found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($applicants->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Showing {{ $applicants->firstItem() }} to {{ $applicants->lastItem() }} of {{ $applicants->total() }}
                entries
            </div>
            {{ $applicants->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
