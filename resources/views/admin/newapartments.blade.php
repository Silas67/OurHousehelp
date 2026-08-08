@extends('layouts.adminmain')

@section('newapartments')
    <div class="container pb-3 py-5">
        <h1>Create New Apartment Detail</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('newapartmentsstore') }}" method="POST" class="form-style">
            @csrf

            <h2 class="form-title">Apartment Details</h2>

            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Apartment Information</legend>

                <!-- Name field -->
                <div class="form-group">
                    <label class="form_label" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Cost field -->
                <div class="form-group">
                    <label class="form_label" for="cost">Cost</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="{{ old('cost') }}" required>
                    @error('cost')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">2</span> Additional Information</legend>

                <!-- About field -->
                <div class="form-group">
                    <label class="form_label" for="about">About</label>
                    <textarea class="form-control" id="about" name="about" rows="4" required>{{ old('about') }}</textarea>
                    @error('about')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary form_button">Create Apartment</button>
        </form>
    </section>
@endsection
