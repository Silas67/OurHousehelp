@extends('layouts.main')
@section('selectpackage')
    <div class="container mt-5">
        <h2>Select a Package</h2>
        <form action="{{ route('processpackage') }}" method="POST"> <!-- Replace 'processpackage' with the desired action route -->
            @csrf
            <div class="form-group">
                <label for="package">Choose a Package:</label>

                <select id="packageSelect" class="form-control">
                    <option value="">Select a Package</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->name }}">{{ $package->name }}</option>
                    @endforeach
                </select>

            </div>
            <button type="submit" class="btn btn-primary mt-3">Proceed</button>
        </form>
    </div>
@endsection
