@extends('layouts.adminmain')

@section('manageMultipleServices')
    <div class="container pb-3 py-5">
        <h1>Manage Multiple Services</h1>
    </div>

    <div class="container py-5 bg-white">
        @if ($multipleServices->isEmpty())
            <p class="text-center">No multiple services available.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th> <!-- Column for row numbering -->
                        <th>Selected Services</th>
                        <th>Cost</th>
                        <th>Working Hours</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($multipleServices as $index => $service) <!-- Use $index to maintain row numbering -->
                        <tr>
                            <td>{{ $index + 1 }}</td> <!-- Display the index with proper numbering -->
                            <td>
                                <ul>
                                    @foreach ($service->services as $selectedService)
                                        <li>{{ $selectedService }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $service->cost }}</td>
                            <td>{{ $service->working_hours }}</td>
                            <td>{{ $service->description }}</td>
                            <td class="d-flex">
                                <a href="{{ route('multipleservice.edit', $service->id) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                                <form action="{{ route('multipleservice.destroy', $service->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this service?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
