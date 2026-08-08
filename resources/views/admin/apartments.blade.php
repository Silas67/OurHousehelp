@extends('layouts.adminmain')

@section('apartments')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Apartment Details</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete Apartment Details</h5>

                @if ($apartments->isEmpty())
                    <p>No apartments available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Cost (₦)</th>
                                    <th scope="col">About</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($apartments as $apartment)
                                    <tr>
                                        <form action="{{ route('apartments.update', $apartment->id) }}" method="POST">
                                            @csrf

                                            <td>{{ $apartment->id }}</td>

                                            <td>
                                                <input class="form-control" type="text" name="name" value="{{ ucfirst($apartment->name) }}" required>
                                            </td>

                                            <td>
                                                <input class="form-control" type="number" name="cost" value="{{ $apartment->cost }}" step="0.01" min="0" required>
                                            </td>

                                            <td>
                                                <input class="form-control" type="text" name="about" value="{{ ucfirst($apartment->about) }}" required>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <!-- Update button -->
                                                    <button type="submit" class="btn btn-primary btn-sm me-1">Update</button>
                                                </div>
                                            </td>
                                        </form>

                                        <td>
                                            <!-- Delete button -->
                                            <form action="{{ route('apartments.destroy', $apartment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this apartment?');">
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

    <script>
        function confirmDeletion(id) {
            return confirm("Are you sure you want to delete this apartment?");
        }
    </script>
@endsection
