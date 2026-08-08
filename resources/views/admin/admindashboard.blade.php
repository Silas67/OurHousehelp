@extends('layouts.adminmain')
@section('admindashboard')
    <h2>Welcome {{ Auth::user()->name }}</h2>
    <p>Your main control panel for managing the application.</p>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('confirmedOrder') }}">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Confirmed Orders</h5>
                        <p class="card-text display-6">{{ $confirmedCount }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('activeOrder') }}">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Active Orders</h5>
                        <p class="card-text display-6">{{ $activeCount }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('pendingOrder') }}">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Pending Orders</h5>
                        <p class="card-text display-6">{{ $pendingCount }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Booking Flow Stats -->
    <div class="row mt-4">
        <div class="col-md-4">
            <a href="{{ route('admin.bookings') }}" class="text-decoration-none">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">New Bookings</h5>
                        <p class="card-text display-6">{{ \App\Models\ServiceRequest::whereNotNull('matching_status')->count() }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.bookings') }}?status=payment_pending" class="text-decoration-none">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Pending Payments</h5>
                        <p class="card-text display-6">{{ \App\Models\BookingPayment::where('status', 'pending_approval')->count() }}</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.management-plans') }}" class="text-decoration-none">
                <div class="card text-white" style="background-color: #8e44ad;">
                    <div class="card-body">
                        <h5 class="card-title">Management Plans</h5>
                        <p class="card-text display-6">{{ \App\Models\ManagementPlan::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="mt-4">
        <div class="d-flex">
            <h3>Recent Orders</h3>
            <div class="ms-auto">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-orders" placeholder="Search orders...">
                </div>
            </div>
        </div>

        @include('partials.admin.table._pendingOrderTable')
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
