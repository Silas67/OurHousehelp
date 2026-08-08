@extends('layouts.adminmain')

@section('allpaymentplan')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Payment Plans</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete Payment Plans</h5>

                @if ($paymentPlans->isEmpty())
                    <p>No payment plans available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Plan Type</th>
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Cost</th>
                                    <th scope="col">About</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">No. of Searches</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentPlans as $plan)
                                    <tr>
                                        <form action="{{ route('admin.payment_plans.paymentplanupdate', $plan->id) }}"
                                            method="POST">

                                            @csrf
                                            <td>{{ $plan->id }}</td>
                                            <td>
                                                {{-- <input class="form-control" type="text" name="plan_type"
                                                    value="{{ ucfirst($plan->plan_type) }}" required> --}}
                                                <select class="form-control" id="plan_type" name="plan_type" required>
                                                    <option value="">Select Plan Type</option>
                                                    <option value="househelp">Househelp Plan</option>
                                                    <option value="skilled">Skilled Workers Plan</option>                        
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" name="plan_name"
                                                    value="{{ ucfirst($plan->plan_name) }}" required>
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" step="0.01" name="cost"
                                                    value="{{ $plan->cost }}" required>
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="about">{{ ucfirst($plan->about) }}</textarea>
                                            </td>
                                            <td>
                                                <select class="form-control" id="duration" name="duration" required>
                                                    <option value="1" {{ $plan->duration == 1 ? 'selected' : '' }}>
                                                        Daily Plan</option>
                                                    <option value="7" {{ $plan->duration == 7 ? 'selected' : '' }}>One
                                                        Week Plan</option>
                                                    <option value="30" {{ $plan->duration == 30 ? 'selected' : '' }}>One
                                                        Month Plan</option>
                                                    <option value="90" {{ $plan->duration == 90 ? 'selected' : '' }}>
                                                        Three Months Plan</option>
                                                    <option value="180" {{ $plan->duration == 180 ? 'selected' : '' }}>
                                                        Six Months Plan</option>
                                                    <option value="365" {{ $plan->duration == 365 ? 'selected' : '' }}>
                                                        One Year Plan</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control" type="number" name="num_searches"
                                                    value="{{ $plan->num_searches }}" required>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary btn-sm me-1">Update</button>
                                            </td>
                                        </form>
                                        <td>
                                            <form action="{{ route('paymentplan.paymentplansdestroy', $plan->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this payment plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>                                            
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- End Scrollable Table -->
                @endif
            </div>
        </div>
    </div>
@endsection
