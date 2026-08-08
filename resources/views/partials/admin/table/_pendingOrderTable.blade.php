<div class="table-responsive">
    <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="orders-table">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Name</th>
                <th class="text-center">State</th>
                <th class="text-center">Package</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Cost (₦)</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody id="orders-body">
            @if ($orders->count() > 0)
                @foreach ($orders as $index => $order)
                    @include('partials.admin.modals._ordermodal')
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading">{{ $order->name }} {{ $order->last_name }}</div>
                                        <div class="widget-subheading opacity-7">{{ $order->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $order->state_of_residence }}</td>
                        <td class="text-center">{{ $order->package }}</td>
                        <td class="text-center">{{ ucfirst($order->gender) }}</td>
                        <td class="text-center">₦{{ number_format($order->grand_cost, 2) }}</td>
                        <td class="text-center d-flex align-items-center">
                            <button class="btn btn-sm btn-secondary" type="button"
                                id="PopoverCustomT-{{ $order->id }}" data-bs-toggle="modal"
                                data-bs-target="#detail{{ $order->id }}">
                                <i class="fas fa-info-circle"></i>
                            </button>

                            @if ($order->status == 'pending')
                                <button class="btn btn-sm btn-success ms-1 approve-order-btn"
                                    data-order-id="{{ $order->id }}"
                                    title="Approve & Notify Staff"
                                    onclick="approveServiceRequest({{ $order->id }}, this)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                @include('partials.admin.modals._archieveButton')
                            @elseif ($order->status == 'confirmed')
                                <span class="ps-2 text-success" title="Confirmed"><i
                                        class="fas fa-check-circle"></i> Approved</span>
                                @include('partials.admin.modals._archieveButton')
                            @elseif ($order->status == 'active')
                                <span class="ps-2 text-primary" title="Active"><i class="fas fa-play-circle"></i></span>
                                @include('partials.admin.modals._archieveButton')
                            @elseif ($order->status == 'cancelled')
                                <span class="ps-2 text-danger" title="Cancelled"><i
                                        class="fas fa-times-circle"></i></span>
                                @include('partials.admin.modals._archieveButton')
                            @endif


                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No orders found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Ensure Font Awesome is included -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<script>
function approveServiceRequest(orderId, btn) {
    if (!confirm('Approve this service request? Staff will be notified via email and in-app notification.')) {
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/admin/service-request/${orderId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: 'confirmed' })
    })
    .then(response => response.json())
    .then(data => {
        alert('Service request approved! Staff have been notified.');
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Approve';
        alert('Failed to approve. Please try again.');
    });
}
</script>
