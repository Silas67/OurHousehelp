@extends('layouts.adminmain')

@section('newdiscount')
    <div class="container pb-3 py-5">
        <h1>Create Service Discount</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('postdiscount.store') }}" method="POST" class="form-style">
            @csrf

            <h2 class="form-title">Service Discount Details</h2>

            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Discount Information</legend>

                <!-- Service Name field -->
                <div class="form-group">
                    <label class="form_label" for="number_of_services">Number of Services</label>
                    <input type="number" class="form-control" id="number_of_services" name="number_of_services" value="{{ old('number_of_services') }}" required>
                    @error('number_of_services')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Discount Percent field -->
                <div class="form-group">
                    <label class="form_label" for="discount_percent">Discount Percent (%)</label>
                    <input type="number" step="0.01" class="form-control" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" min="0" max="100" required>
                    @error('discount_percent')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">2</span> Additional Information</legend>

                <!-- Description field -->
                <div class="form-group">
                    <label class="form_label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary form_button">Create Discount</button>
        </form>

    </section>
@endsection
