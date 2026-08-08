@extends('layouts.main')
@section('receipt')

<section id="receiptSection" class="py-5">
    <div class="container">
        <h2 class="text-center text-success">Thank You! Your Order Has Been Placed</h2>
        <hr>
        <h4 class="mb-4">Order Summary</h4>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Service Type:</strong> {{ $order->apartment_type }} - {{ $order->service_type }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Price Range:</strong> {{ $order->price }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>First Name:</strong> {{ $order->first_name }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Last Name:</strong> {{ $order->last_name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Email:</strong> {{ $order->email }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>State of Residence:</strong> {{ $order->state_of_residence }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Address:</strong> {{ $order->address }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Landmark:</strong> {{ $order->landmark ?? 'N/A' }}</p>
            </div>
        </div>

        @include('partials._nextstepprint')

    </div>
</section>

@endsection
