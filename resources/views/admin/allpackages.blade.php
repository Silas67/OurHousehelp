@extends('layouts.adminmain')

@section('allpackages')
    <div class="container pb-3 py-5">
        <h1 class="text-center">Manage Packages</h1>
    </div>

    <div class="container py-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit or Delete Package Costs</h5>

                <!-- Scrollable Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Package Name</th>
                                <th scope="col">Cost Per Week</th>
                                <th scope="col">Days Per Week</th>
                                <th scope="col">Hint 1</th>
                                <th scope="col">Hint 2</th>
                                <th scope="col">Hint 3</th>
                                <th scope="col">Description</th>
                                <th scope="col">Working Hours (Start & End)</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($packages as $package)
                                <tr>
                                    <form action="{{ route('packages.update', $package->id) }}" method="POST">
                                        @csrf

                                        <td>
                                            <input style="width: 200px;" class="form-control" type="text" name="name"
                                                value="{{ ucfirst($package->name) }}">
                                        </td>

                                        <td>
                                            <input style="width: 150px;" class="form-control" type="number"
                                                name="cost" value="{{ $package->cost }}">
                                        </td>
                                        <td>
                                            <input style="width: 70px;" class="form-control" type="number"
                                                name="days_per_week" value="{{ $package->days_per_week }}" min="1">
                                        </td>
                                        <td>
                                            <input style="width: 300px;" class="form-control" type="text" name="point1"
                                                value="{{ ucfirst($package->point1) }}">
                                        </td>
                                        <td>
                                            <input style="width: 300px;" class="form-control" type="text" name="point2"
                                                value="{{ ucfirst($package->point2) }}">
                                        </td>
                                        <td>
                                            <input style="width: 300px;" class="form-control" type="text" name="point3"
                                                value="{{ ucfirst($package->point3) }}">
                                        </td>
                                        <td>
                                            <input style="width: 300px;" class="form-control" type="text"
                                                name="description" value="{{ ucfirst($package->description) }}">
                                        </td>

                                        <!-- Working Hours Start-->
                                        {{-- <td class=" d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="form-group d-flex align-items-center">
                                                    <input class="form-control me-2" type="time"
                                                        name="working_hours_start_{{ $i }}"
                                                        value="{{ $package->{'working_hours_start_' . $i} }}"
                                                        placeholder="Start" />
                                                </div>
                                            @endfor
                                        </td> --}}
                                        <!-- Working Hours Start -->
                                        <td class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <div class="form-group d-flex align-items-center">
                                                    <input class="form-control me-2" type="time"
                                                        name="working_hours_start_{{ $i }}"
                                                        value="{{ old('working_hours_start_' . $i, $package->{'working_hours_start_' . $i} ?? '') }}"
                                                        placeholder="Start" />
                                                </div>
                                            @endfor
                                        </td>



                                        <td>
                                            <div class="d-flex flex-column">
                                                <button type="submit" class="btn btn-primary btn-sm mb-1">Update</button>
                                    </form>
                                    <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE') <!-- Spoof the DELETE method -->
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                </div>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
            <!-- End Scrollable Table -->
        </div>
    </div>
@endsection
