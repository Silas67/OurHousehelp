@extends('layouts.adminmain')

@section('showRegistrationPayments')

    <div class="container-fluid py-4">
        <!-- Page Header with Breadcrumb -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admindashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registration Payments</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-1 mt-2 text-gray-800">
                    <i class="fas fa-credit-card me-2 text-primary"></i>Registration Payments
                </h1>
                <p class="text-muted mb-0">Manage client registration payments and approvals</p>
            </div>
            {{-- <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterSection">
                <i class="fas fa-filter me-2"></i>Filters
            </button>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('export', ['format' => 'csv']) }}">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('export', ['format' => 'excel']) }}">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('export', ['format' => 'pdf']) }}">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a></li>
                </ul>
            </div>
        </div> --}}
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4" hidden>
            <!-- Total Payments -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-start border-primary border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase text-primary fw-semibold mb-2">Total Payments</h6>
                                <h3 class="fw-bold mb-2">₦{{ number_format($totalAmount, 2) }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary-subtle text-primary me-2">
                                        <i class="fas fa-exchange-alt me-1"></i>{{ $totalPayments }} Transactions
                                    </span>
                                    <small class="text-muted">All time</small>
                                </div>
                            </div>
                            <div class="bg-primary-subtle p-3 rounded-circle">
                                <i class="fas fa-money-bill-wave text-primary fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Payments -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-start border-success border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase text-success fw-semibold mb-2">Completed</h6>
                                <h3 class="fw-bold mb-2">{{ $completedPayments }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success-subtle text-success me-2">
                                        ₦{{ number_format($completedAmount, 2) }}
                                    </span>
                                    <small class="text-muted">Amount</small>
                                </div>
                            </div>
                            <div class="bg-success-subtle p-3 rounded-circle">
                                <i class="fas fa-check-circle text-success fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Approval -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-start border-warning border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase text-warning fw-semibold mb-2">Pending Approval</h6>
                                <h3 class="fw-bold mb-2">{{ $pendingApprovalPayments }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning-subtle text-warning me-2">
                                        ₦{{ number_format($pendingApprovalAmount, 2) }}
                                    </span>
                                    <small class="text-muted">Awaiting review</small>
                                </div>
                            </div>
                            <div class="bg-warning-subtle p-3 rounded-circle">
                                <i class="fas fa-clock text-warning fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Failed/Refunded -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-start border-danger border-4 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase text-danger fw-semibold mb-2">Failed/Refunded</h6>
                                <h3 class="fw-bold mb-2">{{ $failedRefundedPayments }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger-subtle text-danger me-2">
                                        ₦{{ number_format($failedRefundedAmount, 2) }}
                                    </span>
                                    <small class="text-muted">Amount</small>
                                </div>
                            </div>
                            <div class="bg-danger-subtle p-3 rounded-circle">
                                <i class="fas fa-times-circle text-danger fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section (Collapsible) -->
        <div class="collapse {{ request()->hasAny(['search', 'status', 'method', 'date_from', 'date_to']) ? 'show' : '' }}"
            id="filterSection" hidden>
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body p-4">
                    <form action="{{ route('registrationPayments') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <h5 class="fw-semibold mb-3">
                                    <i class="fas fa-filter me-2 text-primary"></i>Filter & Search
                                </h5>
                            </div>

                            <!-- Search -->
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Name, email, reference..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <label class="form-label fw-medium">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="pending_approval"
                                        {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval
                                    </option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                        Refunded</option>
                                </select>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-2">
                                <label class="form-label fw-medium">Payment Method</label>
                                <select name="method" class="form-select">
                                    <option value="">All Methods</option>
                                    <option value="bank_transfer"
                                        {{ request('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Card
                                    </option>
                                    <option value="wallet" {{ request('method') == 'wallet' ? 'selected' : '' }}>Wallet
                                    </option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div class="col-md-2">
                                <label class="form-label fw-medium">From Date</label>
                                <input type="date" name="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-medium">To Date</label>
                                <input type="date" name="date_to" class="form-control"
                                    value="{{ request('date_to') }}">
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-check me-2"></i>Apply Filters
                                    </button>
                                    <a href="{{ route('registrationPayments') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Clear All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-semibold mb-0">Payment Records</h5>
                        <p class="text-muted mb-0 small">Showing {{ $payments->total() }} total payments</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-cog me-1"></i>Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('registrationPayments') }}">
                                        <i class="fas fa-sync me-2"></i>Refresh
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('export', ['format' => 'csv']) }}">
                                        <i class="fas fa-file-csv me-2"></i>Export CSV
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('export', ['format' => 'pdf']) }}" hidden>
                                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <span>User</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'user_name', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom" hidden>
                                    <div class="d-flex align-items-center">
                                        <span>Amount</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'amount', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom" hidden>
                                    <div class="d-flex align-items-center">
                                        <span>Method</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'payment_method', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <span>Status</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'status', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom" hidden>
                                    <div class="d-flex align-items-center">
                                        <span>Reference</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'transaction_reference', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <span>Date</span>
                                        <a href="{{ route('registrationPayments', array_merge(request()->all(), ['sort' => 'paid_at', 'order' => request('order', 'asc') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="ms-2 text-decoration-none">
                                            <i class="fas fa-sort"></i>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3 px-4 border-bottom text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr class="border-bottom">
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            @if ($payment->user->passport)
                                                <img src="{{ \App\Support\SecureDocument::url('passports', $payment->user->passport) }}"
                                                    alt="{{ $payment->user->name }}" class="rounded-circle me-3"
                                                    width="40" height="40">
                                            @else
                                                <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <span
                                                        class="text-secondary fw-medium">{{ substr($payment->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $payment->user->name }}</div>
                                                <small class="text-muted">{{ $payment->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4" hidden>
                                        <span class="fw-bold">₦{{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-4" hidden>
                                        <span class="badge bg-info-subtle text-info">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $statusColors = [
                                                'completed' => 'success',
                                                'pending_approval' => 'warning',
                                                'failed' => 'danger',
                                                'refunded' => 'secondary',
                                            ];
                                            $color = $statusColors[$payment->status] ?? 'info';
                                        @endphp
                                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4" hidden>
                                        <code class="text-muted small">{{ $payment->transaction_reference }}</code>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if ($payment->paid_at)
                                            <div>{{ $payment->paid_at->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $payment->paid_at->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <!-- View Details Button -->
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal{{ $payment->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Approval Actions -->
                                            @if ($payment->status === 'pending_approval' || ($payment->status === 'pending' && $payment->bank_receipt))
                                                <form action="{{ route('approve', $payment) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        onclick="return confirm('Approve this payment?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject Modal Trigger -->
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $payment->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- Refund Button (if applicable) -->
                                            @if (in_array($payment->status, ['completed', 'pending_approval']))
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#refundModal{{ $payment->id }}">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif

                                            <!-- Send Receipt -->
                                            {{-- <form action="{{ route('send-receipt', $payment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                    onclick="return confirm('Send receipt to client?')" hidden>
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        </form> --}}

                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $payment->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Details Modal for each payment -->
                                <div class="modal fade" id="detailsModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Payment Details</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <!-- Payment Details -->
                                                    <div class="col-md-6">
                                                        <h6 class="fw-semibold mb-3">Payment Information</h6>
                                                        <dl class="row mb-3">
                                                            <dt class="col-sm-5">Reference:</dt>
                                                            <dd class="col-sm-7">
                                                                <code>{{ $payment->transaction_reference }}</code></dd>

                                                            <dt class="col-sm-5">Amount:</dt>
                                                            <dd class="col-sm-7 fw-bold">
                                                                ₦{{ number_format($payment->amount, 2) }}</dd>

                                                            <dt class="col-sm-5">Method:</dt>
                                                            <dd class="col-sm-7">
                                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                            </dd>

                                                            <dt class="col-sm-5">Status:</dt>
                                                            <dd class="col-sm-7">
                                                                <span
                                                                    class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            </dd>

                                                            <dt class="col-sm-5">Paid At:</dt>
                                                            <dd class="col-sm-7">
                                                                @if ($payment->paid_at)
                                                                    {{ $payment->paid_at->format('d M Y h:i A') }}
                                                                @else
                                                                    —
                                                                @endif
                                                            </dd>
                                                        </dl>

                                                        @if ($payment->payment_method === 'bank_transfer')
                                                            <div class="border-top pt-3">
                                                                <h6 class="fw-semibold mb-3">Bank Transfer Details</h6>
                                                                <dl class="row">
                                                                    <dt class="col-sm-5">Bank Reference:</dt>
                                                                    <dd class="col-sm-7">
                                                                        {{ $payment->bank_reference ?? '—' }}</dd>

                                                                    @if ($payment->transfer_date)
                                                                        <dt class="col-sm-5">Transfer Date:</dt>
                                                                        <dd class="col-sm-7">
                                                                            {{ $payment->transfer_date->format('d M Y') }}
                                                                        </dd>
                                                                    @endif

                                                                    @if ($payment->bank_receipt)
                                                                        <dt class="col-sm-5">Receipt:</dt>
                                                                        <dd class="col-sm-7">
                                                                            <a href="{{ \App\Support\SecureDocument::url('receipts', $payment->bank_receipt) }}"
                                                                                target="_blank"
                                                                                class="btn btn-sm btn-outline-primary">
                                                                                <i
                                                                                    class="fas fa-download me-1"></i>Download
                                                                            </a>
                                                                        </dd>
                                                                    @endif
                                                                </dl>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- User Details -->
                                                    <div class="col-md-6 border-start">
                                                        <h6 class="fw-semibold mb-3">User Information</h6>
                                                        <div class="d-flex align-items-center mb-3">
                                                            @if ($payment->user->passport)
                                                                <img src="{{ \App\Support\SecureDocument::url('passports', $payment->user->passport) }}"
                                                                    class="rounded-circle me-3" width="60"
                                                                    height="60">
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-0">{{ $payment->user->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $payment->user->email }}</small>
                                                            </div>
                                                        </div>

                                                        <dl class="row">
                                                            <dt class="col-sm-4">Phone:</dt>
                                                            <dd class="col-sm-8">{{ $payment->user->phone ?? '—' }}</dd>

                                                            <dt class="col-sm-4">Address:</dt>
                                                            <dd class="col-sm-8">{{ $payment->user->address ?? '—' }}</dd>

                                                            <dt class="col-sm-4">Country:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $payment->user->country_of_residence ?? '—' }}</dd>

                                                            <dt class="col-sm-4">State:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $payment->user->state_of_residence ?? '—' }}</dd>

                                                            <dt class="col-sm-4">Registered:</dt>
                                                            <dd class="col-sm-8">
                                                                @if ($payment->user->is_registered)
                                                                    <span
                                                                        class="badge bg-success-subtle text-success">Yes</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning">No</span>
                                                                @endif
                                                            </dd>
                                                        </dl>

                                                        <!-- Documents -->
                                                        <div class="border-top pt-3 mt-3">
                                                            <h6 class="fw-semibold mb-3">Documents</h6>
                                                            <div class="d-flex gap-2">
                                                                @if ($payment->user->passport)
                                                                    <a href="{{ \App\Support\SecureDocument::url('passports', $payment->user->passport) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-id-card me-1"></i>Passport
                                                                    </a>
                                                                @endif
                                                                @if ($payment->user->id_card)
                                                                    <a href="{{ \App\Support\SecureDocument::url('idcards', $payment->user->id_card) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-file me-1"></i>ID Card
                                                                    </a>
                                                                @endif
                                                                @if (!$payment->user->passport && !$payment->user->id_card)
                                                                    <span class="text-muted">No documents uploaded</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button onclick="window.print()" class="btn btn-primary">
                                                    <i class="fas fa-print me-2"></i>Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal for each payment -->
                                <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Reject Payment</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('reject', $payment) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Are you sure you want to reject this payment?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection <span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="3" required placeholder="Provide reason for rejection..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Refund Modal for each payment -->
                                <div class="modal fade" id="refundModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-warning">Refund Payment</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('refund', $payment) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Are you sure you want to refund this payment?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Refund Amount <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">₦</span>
                                                            <input type="number" name="amount" class="form-control"
                                                                step="0.01" max="{{ $payment->amount }}"
                                                                value="{{ $payment->amount }}" required>
                                                        </div>
                                                        <small class="text-muted">Maximum:
                                                            ₦{{ number_format($payment->amount, 2) }}</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Refund <span
                                                                class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="3" required placeholder="Provide reason for refund..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">Process Refund</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal for each payment -->
                                <div class="modal fade" id="editModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Payment</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('update', $payment) }}" method="POST">
                                                @csrf
                                                {{-- @method('PUT') --}}
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Amount</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">₦</span>
                                                            <input type="number" name="amount" class="form-control"
                                                                step="0.01" value="{{ $payment->amount }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="pending_approval"
                                                                {{ $payment->status == 'pending_approval' ? 'selected' : '' }}>
                                                                Pending Approval</option>
                                                            <option value="completed"
                                                                {{ $payment->status == 'completed' ? 'selected' : '' }}>
                                                                Completed</option>
                                                            <option value="failed"
                                                                {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed
                                                            </option>
                                                            <option value="refunded"
                                                                {{ $payment->status == 'refunded' ? 'selected' : '' }}>
                                                                Refunded</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Admin Notes</label>
                                                        <textarea name="notes" class="form-control" rows="3" placeholder="Add any notes...">{{ $payment->notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center">
                                        <div class="py-5">
                                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No payments found</h5>
                                            <p class="text-muted">No payment records match your search criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($payments->hasPages())                
                <style>
                    /* Reduce size of pagination links */
                    .pagination .page-link {
                        padding: 0.25rem 0.5rem;
                        /* smaller than default 0.5rem 0.75rem */
                        font-size: 0.875rem;
                        /* slightly smaller text */
                    }
                </style>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .table th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            border-bottom-width: 1px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        .bg-primary-subtle {
            background-color: #e8f4fd;
        }

        .bg-success-subtle {
            background-color: #e7f7ef;
        }

        .bg-warning-subtle {
            background-color: #fff8e6;
        }

        .bg-danger-subtle {
            background-color: #fef0f0;
        }

        .bg-info-subtle {
            background-color: #e8f6f8;
        }

        .bg-secondary-subtle {
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
    </style>
@endsection
