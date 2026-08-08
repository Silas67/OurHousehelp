@extends('layouts.adminmain')

@section('discount')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Service Discounts</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete Service Discounts</h5>

                @if ($discounts->isEmpty())
                    <p>No discounts available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Number of Services</th>
                                    <th scope="col">Discount (%)</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $discount)
                                    <tr>
                                        <form action="{{ route('discount.update', $discount->id) }}" method="POST">
                                            @csrf

                                            <td>
                                                <input readonly class="form-control" type="number" name="number_of_services"
                                                    value="{{ $discount->number_of_services }}" required>
                                            </td>

                                            <td>
                                                <input class="form-control" type="number" name="discount_percent"
                                                    value="{{ $discount->discount_percent }}" step="0.01" min="0"
                                                    max="100" required>
                                            </td>


                                            <td>
                                                <input class="form-control" type="text" name="description"
                                                    value="{{ $discount->description }}">
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <!-- Update button -->
                                                    <button type="submit"
                                                        class="btn btn-primary btn-sm me-1">Update</button>
                                                </div>
                                            </td>
                                        </form>

                                        <td>
                                            <!-- Delete button -->
                                            <form action="{{ route('discount.destroy', $discount->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this discount?');">
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
            return confirm("Are you sure you want to delete this discount?");
        }
    </script>
@endsection
