@extends('layouts.adminmain')

@section('admindashboard')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Corporate Inquiries</h3>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Status filter tabs --}}
        <div class="mb-3 d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.corporate-inquiries') }}"
                class="btn btn-sm {{ request('status') ? 'btn-outline-secondary' : 'btn-dark' }}">
                All <span class="badge bg-secondary ms-1">{{ $statusCounts->sum() }}</span>
            </a>
            @foreach(['new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted', 'closed' => 'Closed', 'lost' => 'Lost'] as $key => $label)
                <a href="{{ route('admin.corporate-inquiries', ['status' => $key]) }}"
                    class="btn btn-sm {{ request('status') === $key ? 'btn-dark' : 'btn-outline-secondary' }}">
                    {{ $label }} <span class="badge bg-secondary ms-1">{{ $statusCounts[$key] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Staff Needed</th>
                                <th>Types</th>
                                <th>Status</th>
                                <th>Received</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                                <tr>
                                    <td class="fw-semibold">{{ $inquiry->company_name }}</td>
                                    <td>
                                        {{ $inquiry->contact_name }}
                                        <br>
                                        <small class="text-muted">{{ $inquiry->work_email }}</small>
                                    </td>
                                    <td>{{ $inquiry->staff_count }}</td>
                                    <td>
                                        <small>{{ collect($inquiry->staff_types)->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))->implode(', ') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'new' => 'primary',
                                                'contacted' => 'info',
                                                'quoted' => 'warning',
                                                'closed' => 'success',
                                                'lost' => 'secondary',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$inquiry->status] ?? 'secondary' }}">
                                            {{ ucfirst($inquiry->status) }}
                                        </span>
                                    </td>
                                    <td><small>{{ $inquiry->created_at->format('M d, Y') }}</small></td>
                                    <td>
                                        <a href="{{ route('admin.corporate-inquiries.show', $inquiry->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No corporate inquiries yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $inquiries->links() }}
        </div>
    </div>
@endsection
