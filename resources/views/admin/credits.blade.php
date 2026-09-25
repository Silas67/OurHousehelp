@extends('layouts.adminmain')

@section('admindashboard')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fas fa-coins me-2 text-warning"></i>Credit Management</h2>
            <p class="text-muted mb-0">Manually add credits to clients after confirming payment</p>
        </div>
        <a href="{{ route('admindashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Dashboard
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

        {{-- ── Add Credits Form ── --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Credits to Client</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.credits.add') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Search Client</label>
                            <input type="text" id="clientSearch" class="form-control"
                                placeholder="Type name, email or phone..."
                                autocomplete="off">
                            <div id="clientResults" class="border rounded mt-1 d-none" style="max-height:200px; overflow-y:auto; position:absolute; z-index:100; background:#fff; width:calc(100% - 48px);"></div>
                        </div>

                        <input type="hidden" name="user_id" id="selectedUserId">

                        <div class="mb-3" id="selectedClientDisplay" style="display:none;">
                            <div class="p-3 rounded border" style="background:#f0fff4; border-color:#9FE1CB !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                        style="width:40px; height:40px; font-size:16px; font-weight:600;" id="clientAvatar">A</div>
                                    <div>
                                        <div class="fw-semibold" id="clientName">-</div>
                                        <div class="small text-muted" id="clientEmail">-</div>
                                        <div class="small text-muted">Current balance: <strong class="text-success" id="clientBalance">-</strong> credits</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Credits to Add</label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-success btn-sm credit-preset" data-value="5">+5 (₦10,000)</button>
                                <button type="button" class="btn btn-outline-success btn-sm credit-preset" data-value="8">+8 (₦15,000)</button>
                                <button type="button" class="btn btn-outline-success btn-sm credit-preset" data-value="12">+12 (₦20,000)</button>
                            </div>
                            <input type="number" name="credits" id="creditsInput" class="form-control"
                                placeholder="Or enter custom amount" min="1" max="50" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reference / Note</label>
                            <input type="text" name="reference" class="form-control"
                                placeholder="e.g. GT Bank transfer confirmed — ₦5,000">
                            <small class="text-muted">Optional but recommended for record keeping</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2" id="addCreditsBtn" disabled>
                            <i class="fas fa-plus-circle me-2"></i>Add Credits
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Stats + Recent Transactions ── --}}
        <div class="col-lg-7">

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div class="fs-3 fw-bold text-success">{{ $totalCreditsAdded }}</div>
                        <div class="small text-muted">Total Credits Added</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div class="fs-3 fw-bold text-primary">{{ $totalClients }}</div>
                        <div class="small text-muted">Clients with Credits</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div class="fs-3 fw-bold text-warning">{{ $todayCredits }}</div>
                        <div class="small text-muted">Added Today</div>
                    </div>
                </div>
            </div>

            {{-- ── Pending Receipt Submissions ── --}}
            @if(isset($pendingReceipts) && $pendingReceipts->count() > 0)
                <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #BA7517;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background:#fff8e1;">
                        <h6 class="mb-0">
                            <i class="fas fa-clock me-2" style="color:#BA7517;"></i>
                            Pending Receipt Submissions
                        </h6>
                        <span class="badge" style="background:#BA7517;">{{ $pendingReceipts->count() }} pending</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Credits</th>
                                        <th>Amount Paid</th>
                                        <th>Note</th>
                                        <th>Receipt</th>
                                        <th>Submitted</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReceipts as $receipt)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold" style="font-size:14px;">
                                                {{ $receipt->client_name }} {{ $receipt->last_name }}
                                            </div>
                                            <div class="text-muted" style="font-size:12px;">{{ $receipt->email }}</div>
                                            <div class="text-muted" style="font-size:12px;">{{ $receipt->phone }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $receipt->credits }} credit(s)</span>
                                        </td>
                                        <td>₦{{ number_format((float) str_replace(',', '', $receipt->amount_paid)) }}</td>
                                        <td>
                                            <span class="text-muted small">{{ $receipt->note ?? '—' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.credits.view-receipt') }}?path={{ urlencode($receipt->receipt_path) }}"
                                                class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-muted small">
                                                {{ \Carbon\Carbon::parse($receipt->created_at)->format('M d, g:ia') }}
                                            </span>
                                        </td>
                                        <td>
                                            {{-- Quick approve button --}}
                                            <form action="{{ route('admin.credits.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $receipt->user_id }}">
                                                <input type="hidden" name="credits" value="{{ $receipt->credits }}">
                                                <input type="hidden" name="reference" value="Receipt approved — ₦{{ $receipt->amount_paid }}">
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Add {{ $receipt->credits }} credit(s) to {{ $receipt->client_name }}?')">
                                                    <i class="fas fa-check me-1"></i>Approve
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
            @else
                <div class="alert alert-light border mb-4">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    No pending receipt submissions.
                </div>
            @endif

            {{-- Recent Transactions --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Credit Additions</h6>
                    <span class="badge bg-secondary">Last 20</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Credits</th>
                                    <th>Reference</th>
                                    <th>Added By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $tx)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="font-size:14px;">{{ $tx->user->name ?? 'Unknown' }}</div>
                                        <div class="text-muted" style="font-size:12px;">{{ $tx->user->email ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">+{{ $tx->amount }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ Str::limit($tx->description, 35) }}</span>
                                    </td>
                                    <td>
                                        <span class="small">{{ $tx->added_by_name ?? 'Admin' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $tx->created_at->format('M d, g:ia') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-coins fa-2x mb-2 d-block opacity-25"></i>
                                        No credit transactions yet
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Client Credit Balances --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Client Credit Balances</h6>
                    <input type="text" id="balanceSearch" class="form-control form-control-sm w-auto"
                        placeholder="Search client...">
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:300px; overflow-y:auto;">
                        <table class="table table-hover mb-0 align-middle" id="balanceTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Client</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr>
                                    <td>
                                        <div class="fw-semibold" style="font-size:14px;">{{ $client->name }}</div>
                                        <div class="text-muted" style="font-size:12px;">{{ $client->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $client->getCreditBalance() > 0 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $client->getCreditBalance() }} credits
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-success quick-add"
                                            data-id="{{ $client->id }}"
                                            data-name="{{ $client->name }}"
                                            data-email="{{ $client->email }}"
                                            data-balance="{{ $client->getCreditBalance() }}">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Client search
    const searchInput = document.getElementById('clientSearch');
    const resultsDiv = document.getElementById('clientResults');
    const selectedDisplay = document.getElementById('selectedClientDisplay');
    const addBtn = document.getElementById('addCreditsBtn');

    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        if (q.length < 2) { resultsDiv.classList.add('d-none'); return; }

        fetch(`{{ route('admin.credits.search') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(clients => {
                if (!clients.length) {
                    resultsDiv.innerHTML = '<div class="p-3 text-muted small">No clients found</div>';
                } else {
                    resultsDiv.innerHTML = clients.map(c => `
                        <div class="p-2 border-bottom client-option" style="cursor:pointer;"
                            data-id="${c.id}" data-name="${c.name}" data-email="${c.email}" data-balance="${c.balance}">
                            <div class="fw-semibold" style="font-size:14px;">${c.name}</div>
                            <div class="text-muted" style="font-size:12px;">${c.email} · ${c.balance} credits</div>
                        </div>
                    `).join('');
                }
                resultsDiv.classList.remove('d-none');
            });
    });

    document.addEventListener('click', function(e) {
        const option = e.target.closest('.client-option');
        if (option) selectClient(option.dataset);

        if (!resultsDiv.contains(e.target) && e.target !== searchInput) {
            resultsDiv.classList.add('d-none');
        }
    });

    function selectClient(data) {
        document.getElementById('selectedUserId').value = data.id;
        document.getElementById('clientName').textContent = data.name;
        document.getElementById('clientEmail').textContent = data.email;
        document.getElementById('clientBalance').textContent = data.balance;
        document.getElementById('clientAvatar').textContent = data.name.charAt(0).toUpperCase();
        searchInput.value = data.name;
        selectedDisplay.style.display = 'block';
        resultsDiv.classList.add('d-none');
        addBtn.disabled = false;
    }

    // Credit presets
    document.querySelectorAll('.credit-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('creditsInput').value = this.dataset.value;
            document.querySelectorAll('.credit-preset').forEach(b => b.classList.remove('btn-success'));
            this.classList.add('btn-success');
            this.classList.remove('btn-outline-success');
        });
    });

    // Quick add from balance table
    document.querySelectorAll('.quick-add').forEach(btn => {
        btn.addEventListener('click', function() {
            selectClient(this.dataset);
            document.getElementById('clientSearch').scrollIntoView({behavior:'smooth'});
        });
    });

    // Balance table search
    document.getElementById('balanceSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#balanceTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endsection