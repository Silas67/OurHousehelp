@extends('layouts.adminmain')

@section('registrationPayments')
    <div class="container">
        <h3>Registration Payments</h3>
        <div class="table-responsive">
<table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount (₦)</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Paid At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                        <td>₦{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'secondary' : 'warning')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->transaction_reference }}</td>
                        <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : '—' }}</td>
                        <td>
                            <form action="{{ route('admin.payments.complete', $payment->id) }}" method="POST"
                                class="d-inline">@csrf
                                <button class="btn btn-success btn-sm">Complete</button>
                            </form>
                            <form action="{{ route('admin.payments.fail', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Fail</button>
                            </form>
                            <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST"
                                class="d-inline">@csrf
                                <button class="btn btn-secondary btn-sm">Refund</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>

        {{ $payments->links() }}
    </div>
@endsection
