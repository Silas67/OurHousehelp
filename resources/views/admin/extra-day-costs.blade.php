@extends('layouts.adminmain')

@section('extraDayCosts')
<div class="container pb-3 py-5">
    <h1 class="text-center">Manage Extra Day Cost</h1>

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
            <h5 class="card-title">
                {{ $extraDayCost ? 'Update Extra Day Cost' : 'Create Extra Day Cost' }}
            </h5>

            <form method="POST" action="{{ route('admin.extra-day-costs.createOrUpdate') }}">
                @csrf

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cost (₦)</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 25%;">
                                    <input type="number" name="cost" step="0.01" class="form-control"
                                           value="{{ $extraDayCost->cost ?? '12000' }}" required />
                                </td>
                                <td>
                                    <input type="text" name="description" class="form-control"
                                           value="{{ $extraDayCost->description ?? '' }}" placeholder="Optional note" />
                                </td>
                                <td style="width: 20%;">
                                    <button type="submit" class="btn btn-success">
                                        {{ $extraDayCost ? 'Update' : 'Create' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
