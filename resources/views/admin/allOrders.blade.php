@extends('layouts.adminmain')

@section('allOrders')
    <div class="container pb-3 py-5">
        <h1 class="text-center">All Orders</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">

                <div class="d-flex">
                    <h5>View and Manage All Orders</h5>
                    <div class="ms-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-orders" placeholder="Search orders...">
                        </div>
                    </div>
                </div>

                @if ($apartments->isEmpty())
                    <p>No order available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    @include('partials.admin.table._pendingOrderTable')

                    <div class="d-block text-center card-footer">
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    <!-- End Scrollable Table -->
                @endif
            </div>
        </div>
    </div>
    <script>
        document.getElementById('search-orders').addEventListener('input', function() {
            let query = this.value;

            fetch(`/admin/orders/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let ordersBody = document.getElementById('orders-body');
                    ordersBody.innerHTML = '';

                    if (data.orders.length > 0) {
                        data.orders.forEach(order => {
                            let row = `
                                <tr>
                                    <td class="text-center text-muted">${order.id}</td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">${order.name} ${order.last_name}</div>
                                                    <div class="widget-subheading opacity-7">${order.email}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${order.state}</td>
                                    <td class="text-center">${order.package}</td>
                                    <td class="text-center">${order.gender.charAt(0).toUpperCase() + order.gender.slice(1)}</td>
                                    <td class="text-center">₦${parseFloat(order.grand_cost).toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="text-center">
                                        <button type="button" id="PopoverCustomT-${order.id}" class="btn btn-primary btn-sm">Details</button>
                                    </td>
                                </tr>`;
                            ordersBody.innerHTML += row;
                        });
                    } else {
                        ordersBody.innerHTML =
                            `<tr><td colspan="7" class="text-center">No orders found.</td></tr>`;
                    }
                });
        });
    </script>
@endsection
