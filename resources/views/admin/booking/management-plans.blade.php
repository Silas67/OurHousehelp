@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Management Plans</h3>
        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Bookings
        </a>
    </div>

    <!-- Existing Plans -->
    <div class="row g-4 mb-4">
        @foreach($plans as $plan)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $plan->name }}</h5>
                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.management-plans.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $plan->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" class="form-control" value="{{ $plan->label }}"
                                placeholder="e.g. MOST CHOSEN BY FAMILIES">
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Base Price (N)</label>
                                <input type="number" name="base_price" class="form-control" value="{{ $plan->base_price }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Max Price (N)</label>
                                <input type="number" name="max_price" class="form-control" value="{{ $plan->max_price }}"
                                    placeholder="Optional">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Features (one per line)</label>
                            <textarea name="features" class="form-control" rows="5">{{ implode("\n", $plan->features_list) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $plan->sort_order }}">
                            <small class="text-muted">1 = shown first (Premium), 2 = middle, 3 = last</small>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                id="active_{{ $plan->id }}" {{ $plan->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="active_{{ $plan->id }}">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add New Plan -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Add New Plan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.management-plans.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="e.g. enterprise" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Enterprise Plan" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Base Price (N)</label>
                        <input type="number" name="base_price" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Max Price (N)</label>
                        <input type="number" name="max_price" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="4" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Features (one per line)</label>
                        <textarea name="features" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Create Plan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
