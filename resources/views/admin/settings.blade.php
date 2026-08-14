@extends('layouts.adminmain')

@section('settings')
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-md-6">
            <h2>Settings</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admindashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Admin Dashboard
            </a>
        </div>
    </div>

    <section class="container py-5 bg-white">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('liveout_bonus.update') }}" method="POST" class="form-style" id="bonusForm">
            @csrf

            <h2 class="form-title">Set Bonus Skilled Service Slots</h2>

            <fieldset class="form_fieldset">
                <legend>Bonus Configuration</legend>

                <!-- Bonus Slot Count -->
                <div class="form-group">
                    <label class="form_label fw-bold" for="bonus">Bonus Slots for Live-out Clients</label>
                    <input type="number" class="form-control" id="bonus" name="bonus"
                        placeholder="Number of skilled service slots" min="0" value="{{ old('bonus', $bonus) }}"
                        required>
                    @error('bonus')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary form_button mt-3">Save Bonus Slots</button>
        </form>
    </section>

    <div class="container py-4">
        <h2>Bonus Usage Logs</h2>
        <div class="table-responsive">
<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Coupon</th>
                    <th>User</th>
                    <th>Service Request</th>
                    <th>Description</th>
                    <th>Used At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usages as $usage)
                    <tr>
                        <td>{{ $usage->coupon }}</td>
                        <td>{{ $usage->user?->name ?? 'Guest' }}</td>
                        <td>#{{ $usage->service_request_id }}</td>
                        <td>{{ $usage->description }}</td>
                        <td>{{ $usage->used_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>

        {{ $usages->links() }}
    </div>
@endsection
