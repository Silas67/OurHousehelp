@extends('layouts.adminmain')

@section('onetimeservice')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage One-Time Services</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete One-Time Services</h5>

                <!-- Scrollable Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Service ID</th>
                                <th scope="col">Apartment Size</th>
                                <th scope="col">Price Range (₦)</th>
                                <th scope="col">Details</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <form action="{{ route('one-time-service.update', $service->id) }}" method="POST">
                                        @csrf

                                        <td>{{ $service->id }}</td>

                                        <td>
                                            <input class="form-control" type="text" name="apartment_size" value="{{ ucfirst($service->apartment_size) }}" required>
                                        </td>

                                        <td class="d-flex">
                                            <input class="form-control" type="number" name="price_min" value="{{ $service->price_min }}" step="0.01" min="0" required>
                                            <span class="ms-1 me-1">to</span>
                                            <input class="form-control" type="number" name="price_max" value="{{ $service->price_max }}" step="0.01" min="0" required>
                                        </td>

                                        <td>
                                            <input class="form-control" type="text" name="details" value="{{ ucfirst($service->details) }}" required>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <!-- Update button -->
                                                <button type="submit" class="btn btn-primary btn-sm mb-1">Update</button>
                                            </div>
                                        </td>
                                    </form>

                                    <td>
                                        <!-- Delete button -->
                                        <form action="{{ route('onetimeservice.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
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
            </div>
        </div>
    </div>

    <script>
        // Confirm deletion function
        function confirmDeletion() {
            return confirm("Are you sure you want to delete this service?");
        }
    </script>
@endsection
