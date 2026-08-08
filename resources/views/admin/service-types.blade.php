@extends('layouts.adminmain')

@section('serviceTypes')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Service Types</h1>

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
                <h5 class="card-title">Edit Service Types</h5>

                @if ($serviceTypes->isEmpty())
                    <p class="text-center">No services available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Service Name</th>
                                    <th>Cost (₦)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviceTypes as $type)
                                    <tr>
                                        <td>{{ $type->code }}</td>
                                        <td>{{ $type->name }}</td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.service-types.update', $type->id) }}"
                                                class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="cost" step="0.01"
                                                    value="{{ $type->cost }}" class="form-control form-control-sm"
                                                    style="max-width: 200px;">
                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
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
