@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">Replacement Requests</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">All Replacement Requests ({{ $replacements->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Booking</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($replacements as $replacement)
                        <tr class="{{ $replacement->status === 'pending' ? 'table-warning' : '' }}">
                            <td>{{ $replacement->id }}</td>
                            <td>{{ $replacement->user?->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.booking.detail', $replacement->service_request_id) }}" class="text-primary">
                                    #{{ $replacement->service_request_id }}
                                </a>
                            </td>
                            <td>
                                <small>{{ Str::limit($replacement->reason, 80) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $replacement->status === 'pending' ? 'warning' : ($replacement->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($replacement->status) }}
                                </span>
                            </td>
                            <td><small>{{ $replacement->requested_at?->format('M d, H:i') }}</small></td>
                            <td>
                                @if($replacement->status === 'pending')
                                <div class="d-flex gap-1">
                                    <form action="{{ route('admin.replacements.update-status', $replacement->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Approve this replacement?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.replacements.update-status', $replacement->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Reject this replacement?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                    <small class="text-muted">{{ $replacement->resolved_at?->format('M d') }}</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No replacement requests yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $replacements->links() }}
    </div>
</div>
@endsection
