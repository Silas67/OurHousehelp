@extends('layouts.adminmain')

@section('serviceCharges')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Service Charge</h1>

        @if (session('success'))
            <div class="alert alert-success text-center mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger text-center mt-3">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Service Charge</h5>

                <form action="{{ route('admin.service_charge.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Service Charge Amount (₦)</label>
                        <input type="number" step="0.01" name="amount" class="form-control"
                            value="{{ old('amount', $charge->amount ?? 10000) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control"
                            value="{{ old('currency', $charge->currency ?? 'NGN') }}" required>
                    </div>

                    <button type="submit" class="btn btn-success">Update Charge</button>
                </form>
            </div>
        </div>
    </div>
@endsection
