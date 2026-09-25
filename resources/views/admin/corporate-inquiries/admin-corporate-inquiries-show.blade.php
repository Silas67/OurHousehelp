@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">{{ $inquiry->company_name }}</h3>
        <a href="{{ route('admin.corporate-inquiries') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Inquiries
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-md-8">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Company & Contact</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6"><strong>Company:</strong></div>
                        <div class="col-6">{{ $inquiry->company_name }}</div>
                        <div class="col-6"><strong>Contact Name:</strong></div>
                        <div class="col-6">{{ $inquiry->contact_name }}</div>
                        <div class="col-6"><strong>Email:</strong></div>
                        <div class="col-6">
                            <a href="mailto:{{ $inquiry->work_email }}">{{ $inquiry->work_email }}</a>
                        </div>
                        <div class="col-6"><strong>Phone:</strong></div>
                        <div class="col-6">
                            <a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Staffing Request</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6"><strong>Staff Needed:</strong></div>
                        <div class="col-6">{{ $inquiry->staff_count }}</div>
                        <div class="col-6"><strong>Types:</strong></div>
                        <div class="col-6">
                            {{ collect($inquiry->staff_types)->map(fn($t) => ucfirst(str_replace('_', ' ', $t)))->implode(', ') }}
                        </div>
                        <div class="col-6"><strong>Preferred Start:</strong></div>
                        <div class="col-6">
                            {{ $inquiry->preferred_start_date ? $inquiry->preferred_start_date->format('M d, Y') : 'Not specified' }}
                        </div>
                        <div class="col-6"><strong>Received:</strong></div>
                        <div class="col-6">{{ $inquiry->created_at->format('M d, Y \a\t g:ia') }}</div>
                    </div>

                    @if($inquiry->notes)
                        <hr>
                        <strong>Notes from company:</strong>
                        <p class="mb-0 mt-1">{{ $inquiry->notes }}</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-md-4">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.corporate-inquiries.update-status', $inquiry->id) }}" method="POST">
                        @csrf

                        <label class="form-label fw-semibold">Update Status</label>
                        <select name="status" class="form-select mb-3">
                            @foreach(['new' => 'New', 'contacted' => 'Contacted', 'quoted' => 'Quoted', 'closed' => 'Closed', 'lost' => 'Lost'] as $value => $label)
                                <option value="{{ $value }}" {{ $inquiry->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        <label class="form-label fw-semibold">Internal Notes</label>
                        <textarea name="admin_notes" class="form-control mb-3" rows="4"
                            placeholder="Call notes, quoted amount, next steps...">{{ $inquiry->admin_notes }}</textarea>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check me-1"></i>Save
                        </button>
                    </form>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="mailto:{{ $inquiry->work_email }}" class="btn btn-outline-success">
                    <i class="fas fa-envelope me-1"></i>Email {{ $inquiry->contact_name }}
                </a>
                <a href="tel:{{ $inquiry->phone }}" class="btn btn-outline-success">
                    <i class="fas fa-phone me-1"></i>Call {{ $inquiry->contact_name }}
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
