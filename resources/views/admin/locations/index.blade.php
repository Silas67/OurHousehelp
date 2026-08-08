@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid">
    <h3 class="fw-bold mb-4">Location Management</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add New Location -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Add New Location</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.locations.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Area Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Wuse">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control" value="FCT" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-1">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">All Locations ({{ $locations->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>State</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $location)
                        <tr>
                            <form action="{{ route('admin.locations.update', $location->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>{{ $location->id }}</td>
                                <td>
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        value="{{ $location->name }}" required>
                                </td>
                                <td>
                                    <input type="text" name="state" class="form-control form-control-sm"
                                        value="{{ $location->state }}" required>
                                </td>
                                <td>
                                    <input type="number" name="sort_order" class="form-control form-control-sm"
                                        value="{{ $location->sort_order }}" min="0" style="width: 80px;">
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" value="1"
                                            class="form-check-input"
                                            {{ $location->is_active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-save"></i>
                                        </button>
                            </form>
                                        <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this location?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No locations yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $locations->links() }}
    </div>
</div>
@endsection
