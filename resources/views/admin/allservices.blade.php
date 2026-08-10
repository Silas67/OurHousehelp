@extends('layouts.adminmain')

@section('services')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Service Details</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete Service Details</h5>

                @if ($services->isEmpty())
                    <p class="text-center">No services available at the moment.</p>
                @else
                    <!-- Scrollable Table -->
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service Name</th>
                                    <th>Basic</th>
                                    <th>Description</th>
                                    <th>Icon</th>
                                    <th>Hours of Work</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>{{ $service->id }}</td>
                                        <td>{{ $service->service_name }}</td>
                                        <td>{{ $service->base_cost }}</td>
                                        <td>{{ $service->about_service }}</td>
                                        <td><i class="{{ $service->icon_class }}"></i></td>
                                        <td>{{ $service->hours_of_work ?? 'N/A' }}</td>
                                        <td class="d-flex justify-content-start">
                                            <!-- View Details Button (Triggers Modal) -->
                                            <button type="button" class="btn btn-sm btn-info me-2" data-bs-toggle="modal"
                                                data-bs-target="#serviceDetailsModal-{{ $service->id }}">
                                                View
                                            </button>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.services.edit', $service->id) }}"
                                                class="btn btn-sm btn-primary me-2">
                                                Edit
                                            </a>

                                            <!-- Delete Button with Confirmation -->
                                            <form action="{{ route('admin.services.delete', $service->id) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this service?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal for Service Details -->
                                    <div class="modal fade" id="serviceDetailsModal-{{ $service->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="serviceDetailsModalLabel-{{ $service->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="serviceDetailsModalLabel-{{ $service->id }}">
                                                        Service Details - {{ $service->service_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Service Details -->
                                                    <p><strong>Service Name:</strong> {{ $service->service_name }}</p>
                                                    <p><strong>Description:</strong> {{ $service->about_service }}</p>
                                                    <p><strong>Icon:</strong> <i class="{{ $service->icon_class }}"></i>
                                                    </p>
                                                    <p><strong>Basic Cost:</strong>
                                                        {{ $service->base_cost ?? 'N/A' }}</p>
                                                        <p><strong>Hours of Work:</strong>
                                                            {{ $service->hours_of_work ?? 'N/A' }}</p>
                                                    <p><strong>Service Type:</strong>
                                                        {{ $service->service_type}}</p>
                                                    <p><strong>Number of Apartments Required:</strong>
                                                        {{ $service->number_of_apartment ? 'Yes' : 'No' }}</p>
                                                    <p><strong>Special Service:</strong>
                                                        {{ $service->special_services ? 'Yes' : 'No' }}</p>
                                                    <p><strong>Note/Warning:</strong> {{ $service->dos_and_donts }}</p>

                                                     <!--Package Costs -->
                                                    @foreach ($service->servicePackageCosts as $package)
                                                        <p><strong>Package:</strong> {{ $package->name }} -
                                                            <strong>Cost:</strong> {{ number_format($package->cost, 2) }}
                                                        </p>
                                                    @endforeach


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
