@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fas fa-tags me-2 text-primary"></i>Pricing & Costs</h2>
            <p class="text-muted mb-0">Manage all platform pricing from one place</p>
        </div>
        <a href="{{ route('admindashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── Management Plans ── --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-shield-alt me-2"></i>
                    <h5 class="mb-0">Management Plans</h5>
                    <span class="badge bg-light text-primary ms-auto">{{ $managementPlans->count() }} plans</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Platform Markup %</strong> — added on top of staff salary for company managed bookings.
                        Client managed = 0% (platform earns via credits only).
                    </p>
                    <div class="row g-3">
                        @foreach($managementPlans as $plan)
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 {{ $plan->is_active ? 'border-primary' : 'border-secondary opacity-75' }}">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $plan->name }}</h6>
                                        <small class="text-muted">{{ $plan->slug }}</small>
                                    </div>
                                    <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <form action="{{ route('admin.pricing.update-management-plan', $plan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Platform Markup %</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" name="platform_markup" min="0" max="100"
                                                    class="form-control"
                                                    value="{{ old('platform_markup', $plan->platform_markup ?? 0) }}"
                                                    {{ $plan->slug === 'client-managed' ? 'readonly' : '' }}>
                                                <span class="input-group-text">%</span>
                                            </div>
                                            @if($plan->slug === 'client-managed')
                                                <small class="text-muted">Always 0% — earns via credits</small>
                                            @else
                                                <small class="text-muted">Added to staff salary</small>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Free Replacements</label>
                                            <input type="number" name="free_replacements" class="form-control form-control-sm"
                                                value="{{ old('free_replacements', $plan->free_replacements ?? 0) }}" min="0">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-semibold">Active</label>
                                            <select name="is_active" class="form-select form-select-sm">
                                                <option value="1" {{ ($plan->is_active ?? true) ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ !($plan->is_active ?? true) ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>
                                        {{-- Keep these hidden for compatibility --}}
                                        <input type="hidden" name="base_price" value="{{ $plan->base_price ?? 0 }}">
                                        <input type="hidden" name="max_price" value="{{ $plan->max_price ?? 0 }}">
                                        <div class="col-12 mt-2">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Services ── --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-concierge-bell me-2"></i>
                    <h5 class="mb-0">Services</h5>
                    <span class="badge bg-light text-success ms-auto">{{ $services->count() }} services</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Base Cost</strong> — what the client pays for company managed bookings (highest service = main, others halved).
                        Cleaning Service base cost is set by apartment type selected during booking.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Type</th>
                                    <th style="width:180px;">Base Cost (₦)</th>
                                    <th style="width:80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $service->service_name }}</div>
                                        <small class="text-muted">{{ Str::limit($service->about_service, 60) }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $service->service_type }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.pricing.update-service', $service->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">₦</span>
                                                <input type="number" step="0.01" name="base_cost"
                                                    class="form-control"
                                                    value="{{ old('base_cost', $service->base_cost) }}"
                                                    {{ $service->service_type === 'PRS' ? 'readonly title=Set by apartment type' : 'required' }}>
                                            </div>
                                            {{-- Keep salary_addon_percentage hidden for compatibility --}}
                                            <input type="hidden" name="salary_addon_percentage" value="{{ $service->salary_addon_percentage ?? 0 }}">
                                            <button type="submit" class="btn btn-success btn-sm"
                                                {{ $service->service_type === 'PRS' ? 'disabled' : '' }}>
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                        @if($service->service_type === 'PRS')
                                            <small class="text-muted d-block mt-1">Set by apartment type</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.services.edit', $service->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Packages ── --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <i class="fas fa-box me-2"></i>
                    <h5 class="mb-0">Package Costs (Frequency Premium)</h5>
                    <span class="badge bg-dark text-warning ms-auto">{{ $packages->count() }} packages</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Package cost is added on top of service cost to account for visit frequency.
                    </p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Package</th>
                                    <th>Days/Week</th>
                                    <th style="width:200px;">Cost (₦)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                <tr>
                                    <td class="fw-semibold">{{ $package->name }}</td>
                                    <td>{{ $package->days_per_week ?? 'N/A' }} days</td>
                                    <td>
                                        <form action="{{ route('admin.pricing.update-package', $package->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">₦</span>
                                                <input type="number" step="0.01" name="cost"
                                                    class="form-control"
                                                    value="{{ old('cost', $package->cost) }}" required>
                                            </div>
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Duration Discounts ── --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="fas fa-percent me-2"></i>
                    <h5 class="mb-0">Duration Discounts</h5>
                    <span class="badge bg-light text-info ms-auto">Reference only</span>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Discounts applied to encourage longer bookings. To change these values, update <code>BookingCostService::$durationDiscounts</code>.
                    </p>
                    <div class="table-responsive" style="max-width: 400px;">
                        <table class="table table-bordered mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Duration</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>1 month (4 weeks)</td><td class="fw-bold">0%</td></tr>
                                <tr><td>2 months (8 weeks)</td><td class="fw-bold text-success">5%</td></tr>
                                <tr><td>3 months (12 weeks)</td><td class="fw-bold text-success">8%</td></tr>
                                <tr><td>6 months (24 weeks)</td><td class="fw-bold text-success">12%</td></tr>
                                <tr><td>12 months (48 weeks)</td><td class="fw-bold text-success">15%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection