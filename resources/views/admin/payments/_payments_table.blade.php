@foreach ($payments as $payment)
<tr data-payment-id="{{ $payment->id }}">
    <td>
        <div class="d-flex align-items-center">
            @if($payment->user->passport)
                <img src="{{ asset('storage/' . $payment->user->passport) }}" 
                     alt="{{ $payment->user->name }}" 
                     class="user-avatar me-3">
            @else
                <div class="user-avatar bg-secondary text-white d-flex align-items-center justify-content-center me-3">
                    {{ substr($payment->user->name, 0, 1) }}
                </div>
            @endif
            <div>
                <strong>{{ $payment->user->name }}</strong>
                <div class="text-muted small">{{ $payment->user->email }}</div>
            </div>
        </div>
    </td>
    <td class="fw-bold">₦{{ number_format($payment->amount, 2) }}</td>
    <td>
        <span class="badge bg-info">
            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
        </span>
    </td>
    <td>
        <span class="payment-badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'secondary' : 'warning')) }}">
            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
        </span>
    </td>
    <td><code>{{ $payment->transaction_reference }}</code></td>
    <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : '—' }}</td>
    <td class="table-actions">
        <div class="d-flex">
            <button class="btn btn-info btn-sm action-btn view-details" 
                    data-bs-toggle="modal" 
                    data-bs-target="#detailsModal"
                    data-payment-id="{{ $payment->id }}">
                <i class="fas fa-eye"></i>
            </button>
            
            @if($payment->status === 'pending_approval' || ($payment->status === 'pending' && $payment->bank_receipt))
                <button class="btn btn-success btn-sm action-btn approve-payment" 
                        data-payment-id="{{ $payment->id }}">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-danger btn-sm action-btn reject-payment" 
                        data-bs-toggle="modal" 
                        data-bs-target="#rejectModal"
                        data-payment-id="{{ $payment->id }}">
                    <i class="fas fa-times"></i>
                </button>
            @endif
            
            @if(in_array($payment->status, ['completed', 'pending_approval']))
                <button class="btn btn-secondary btn-sm action-btn refund-payment" 
                        data-bs-toggle="modal" 
                        data-bs-target="#refundModal"
                        data-payment-id="{{ $payment->id }}">
                    <i class="fas fa-undo"></i>
                </button>
            @endif
            
            <button class="btn btn-primary btn-sm action-btn send-receipt" 
                    data-payment-id="{{ $payment->id }}">
                <i class="fas fa-envelope"></i>
            </button>
            
            <button class="btn btn-warning btn-sm action-btn edit-payment" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editModal"
                    data-payment-id="{{ $payment->id }}">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </td>
</tr>
@endforeach

@if($payments->isEmpty())
<tr>
    <td colspan="7" class="text-center py-4">
        <i class="fas fa-search fa-2x text-muted mb-3"></i>
        <p class="text-muted">No payments found matching your criteria.</p>
    </td>
</tr>
@endif