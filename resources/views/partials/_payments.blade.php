<div class="container mt-5">
    <!-- Page Header -->
    <div class="text-center mb-4">
        <h2>Payment History</h2>
        <p class="lead">Review your past payments and transactions.</p>
    </div>

    <!-- Payment History Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    @if (in_array(Auth::user()->user_role, [1, 2]))
                        <th>User</th>
                    @endif
                    {{-- <th>Service</th> --}}
                    <th>Plan</th> <!-- New Column -->
                    {{-- <th>No. of Searches</th> --}}
                    <th>Amount</th>
                    {{-- <th>Payment Method</th> --}}
                    <th>Confirmation</th>
                    <th>Payment Date</th>
                    <th>Expiry Date</th> <!-- New Column -->
                    <th>Receipt</th>
                    @if (in_array(Auth::user()->user_role, [1, 2]))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if (in_array(Auth::user()->user_role, [1, 2]))
                            <td>{{ $payment->user->name ?? 'N/A' }}</td>
                        @endif
                        {{-- <td>{{ $payment->about_payment ?? 'N/A' }}</td> --}}
                        <td>{{ $payment->paymentPlan->plan_name ?? 'N/A' }} <br> <span
                                class="badge bg-secondary">{{ $payment->plan_type ?? 'N/A' }}</span>
                        </td> <!-- Display Plan Name -->
                        {{-- <td>{{ $payment->num_searches ?? 'N/A' }}</td> --}}
                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                        {{-- <td>{{ ucfirst($payment->payment_method) }}</td> --}}
                        <td>
                            @if (Route::is('paymenthistory'))
                                {{ ucfirst($payment->status) }}
                            @else
                                @if ($payment->isExhausted())
                                    <span class="badge bg-secondary">Exhausted</span>
                                @elseif ($payment->status == 'approved' && $payment->expiry_date && now()->greaterThan($payment->expiry_date))
                                    <span class="badge bg-secondary">Expired</span>
                                @elseif ($payment->status == 'approved')
                                    <span class="badge bg-success">Active</span>
                                @elseif ($payment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($payment->status == 'failed')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            @endif

                        </td>
                        <td>{{ $payment->created_at->format('d M, Y') }}</td>
                        <td>
                            @if ($payment->expiry_date)
                                {{ $payment->expiry_date->format('d M, Y') }}
                            @else
                                <span class="text-muted">Not approved</span>
                            @endif
                        </td>
                        <td>
                            {{-- @if ($payment->receipt_path) --}}
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#receiptModal{{ $payment->id }}">
                                Preview
                            </button>
                            {{-- @else
                                <span class="text-muted">No receipt uploaded</span>
                            @endif --}}
                        </td>
                        {{-- @if (in_array(Auth::user()->user_role, [1, 2]))
                            <td>
                                @if ($payment->status == 'pending')
                                    <form action="{{ route('payments.approve', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('payments.cancel', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                @elseif($payment->status == 'approved')
                                    <form action="{{ route('payments.approve', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-success">Approved</button>
                                    </form>
                                    <form action="{{ route('payments.cancel', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                @elseif ($payment->status == 'failed')
                                    <form action="{{ route('payments.approve', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('payments.cancel', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-danger">Cancelled</button>
                                    </form>
                                @endif
                            </td>
                        @endif --}}

                        @if (in_array(Auth::user()->user_role, [1, 2]))
                            <td>
                                {{-- Approve Button --}}
                                @if ($payment->status !== 'approved')
                                    <form action="{{ route('payments.approve', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-success" disabled>Approved</button>
                                @endif

                                {{-- Unapprove Button (sets back to pending) --}}
                                @if ($payment->status !== 'pending')
                                    <form action="{{ route('payments.unapprove', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">Unapprove</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-warning" disabled>Pending</button>
                                @endif

                                {{-- Cancel Button --}}
                                @if ($payment->status !== 'failed')
                                    <form action="{{ route('payments.cancel', $payment->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger" disabled>Cancelled</button>
                                @endif
                            </td>
                        @endif

                    </tr>

                    <!-- Receipt Modal for Each Payment -->
                    {{-- @if ($payment->receipt_path) --}}

                    <div class="modal fade" id="receiptModal{{ $payment->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="receiptModalLabel{{ $payment->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <!-- Modal Header with Logo -->
                                <div class="modal-header bg-light">
                                    <div class="d-flex align-items-center w-100">
                                        <x-img src="build/assets/image/logo/ohh-log.png" alt="Company Logo" height="50" loading="lazy" decoding="async" />
                                        <h5 class="modal-title mx-auto fw-bold">Payment Receipt</h5>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body with Receipt Content -->
                                <div class="modal-body p-4">
                                    <div class="border p-3">
                                        <!-- Receipt Header -->
                                        <div class="text-center mb-4">
                                            <h6 class="text-uppercase text-secondary">Transaction Summary</h6>
                                            <h4 class="fw-bold">Payment #{{ $payment->id }}</h4>
                                            <p class="text-muted">Date:
                                                {{ $payment->created_at->format('d M, Y') }}
                                            </p>
                                        </div>

                                        <!-- Payment Details -->
                                        <div class="row text-start">
                                            <div class="col-md-6">
                                                <p><strong>User:</strong> {{ $payment->user->name ?? 'N/A' }}</p>
                                                <p><strong>Service:</strong> {{ $payment->about_payment ?? 'N/A' }}
                                                </p>
                                                <p><strong>Plan:</strong>
                                                    {{ $payment->paymentPlan->plan_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Amount Paid:</strong> <span
                                                        class="text-success">₦{{ number_format($payment->amount, 2) }}</span>
                                                </p>
                                                <p><strong>Payment Method:</strong>
                                                    {{ ucfirst($payment->payment_method) }}</p>
                                                <p><strong>Status:</strong>
                                                    @if (Route::is('paymenthistory'))
                                                        {{ ucfirst($payment->status) }}
                                                    @else
                                                        <span
                                                            class="badge badge-{{ $payment->status == 'approved' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Receipt Image or File -->
                                        @if ($payment->receipt_path)
                                            <div class="mt-4 text-center">
                                                <h6 class="text-uppercase text-secondary">Receipt</h6>
                                                @if (Str::endsWith($payment->receipt_path, ['.jpg', '.jpeg', '.png', '.gif']))
                                                    <img src="{{ asset('public/build/assets/image/receipt/' . $payment->receipt_path) }}"
                                                        alt="Receipt Image" class="img-fluid border shadow-sm" loading="lazy" decoding="async">
                                                @elseif (Str::endsWith($payment->receipt_path, '.pdf'))
                                                    <embed
                                                        src="{{ asset('public/build/assets/image/receipt/' . $payment->receipt_path) }}"
                                                        type="application/pdf" width="100%" height="400px">
                                                @else
                                                    <a href="{{ asset('public/build/assets/image/receipt/' . $payment->receipt_path) }}"
                                                        class="btn btn-outline-primary btn-sm" download>
                                                        Download Receipt
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="mt-4 text-muted">No receipt uploaded</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Modal Footer with Print Button -->
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="printReceipt({{ $payment->id }})">
                                        <i class="fas fa-print"></i> Print Receipt
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function printReceipt(paymentId) {
                            let modalContent = document.querySelector(`#receiptModal${paymentId} .modal-body`).innerHTML;
                            let printWindow = window.open('', '', 'width=800, height=600');
                            printWindow.document.write(`
                                    <html>
                                    <head>
                                        <title>Payment Receipt</title>
                                        <style>
                                            body { font-family: Arial, sans-serif; padding: 20px; }
                                            .border { border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
                                            .text-center { text-align: center; }
                                            .text-secondary { color: #6c757d; }
                                            .fw-bold { font-weight: bold; }
                                            img { max-width: 100%; height: auto; }
                                            .badge { padding: 5px 10px; font-size: 14px; border-radius: 5px; }
                                            .bg-success { background-color: #28a745; color: #fff; }
                                            .bg-warning { background-color: #ffc107; color: #000; }
                                            .bg-danger { background-color: #dc3545; color: #fff; }
                                        </style>
                                    </head>
                                    <body>
                                        ${modalContent}
                                    </body>
                                    </html>
                                `);
                            printWindow.document.close();
                            printWindow.print();
                        }
                    </script>
                    {{-- @endif --}}
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No payments available</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $payments->links('pagination::bootstrap-4') }}
    </div>
</div>
