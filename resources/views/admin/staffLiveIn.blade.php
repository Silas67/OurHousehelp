@extends('layouts.adminmain')

@section('staffLiveIn')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Approved In-House Applicants</h1>
    </div>


    <!-- approved applicants -->
    <div class="container py-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <h5>Approved Live-Out Applicants</h5>
                    <div class="ms-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-livein-users"
                                placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <div id="user-table">
                    @if ($users->isEmpty())
                        <p>No users available at the moment.</p>
                    @else
                        <div class="table-responsive">
<table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Satus</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="users-body">
                                @php $counter = 1; @endphp
                                @foreach ($approvedusers as $user)
                                    <tr class="user-row">
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->approval_status }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#approvedusers{{ $user->id }}">
                                                    Preview
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Dialog Scrollable -->
                                    @include('partials.admin.modals._applicantsDetail')
                                @endforeach
                            </tbody>
                        </table>
</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search-livein-users').addEventListener('keyup', function() {
            let searchQuery = this.value.toLowerCase();
            let rows = document.querySelectorAll('#users-body .user-row');
            rows.forEach(row => {
                let name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                let phone = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                let approvalStatus = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                if (name.includes(searchQuery) || phone.includes(searchQuery) || approvalStatus.includes(
                        searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

@endsection
