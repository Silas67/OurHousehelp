@extends('layouts.main')

@section('selectedpackage')
    <div class="container mt-5">
        <h2>Package Details for: {{ $packageName }}</h2>

        <!-- Display Package Information for all packages with the selected name -->
        @foreach ($packages as $package)
            <div class="card mb-3">
                <div class="card-body">
                    <p><strong>Package Name:</strong> {{ $package->name }}</p>
                    <p><strong>Cost:</strong> ${{ $package->cost }}</p>

                    <h3>Services Included:</h3>
                    <ul>
                        @foreach ($package->service as $service)
                            <li>{{ $service->service_name }} ({{ $service->hours_of_work }} hours)</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

        <a href="{{ route('select-package') }}" class="btn btn-secondary mt-3">Back to Packages</a>
    </div>
@endsection
