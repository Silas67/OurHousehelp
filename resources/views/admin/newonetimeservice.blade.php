@extends('layouts.adminmain')

@section('newonetimeservice')
    <div class="container pb-3 py-5">
        <h1>Create One-Time Service</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('newonetimeservice.store') }}" method="POST" class="form-style">
            @csrf

            <h2 class="form-title">One-Time Service Details</h2>

            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Service Details</legend>

                <!-- Apartment size dropdown -->
                <div class="form-group">
                    <label class="form_label" for="apartment-size">Select Apartment Size</label>
                    <select class="form-control" id="apartment-size" name="apartment_size" required>
                        <option value="" disabled selected>Select apartment size</option>
                        <option value="1-2 bedroom apartment">1-2 Bedroom Apartment</option>
                        <option value="3 bedroom apartment">3 Bedroom Apartment</option>
                        <option value="4 bedroom apartment">4 Bedroom Apartment</option>
                        <option value="5 bedroom apartment">5 Bedroom Apartment</option>
                    </select>
                    @error('apartment_size')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price range inputs -->
                <div class="form-group">
                    <label class="form_label" for="price-range">Price Range</label>
                    <div class="row">
                        <div class="col">
                            <input type="number" class="form-control" id="price-min" name="price_min" placeholder="Minimum price" required>
                            @error('price_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" id="price-max" name="price_max" placeholder="Maximum price" required>
                            @error('price_max')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>
            <br>

            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">2</span> Additional Information</legend>

                <!-- Details text area -->
                <div class="form-group">
                    <label class="form_label" for="details">Additional Details</label>
                    <textarea class="form-control" id="details" name="details" rows="4" placeholder="Provide any specific instructions or details here..." required></textarea>
                    @error('details')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary form_button">Submit Request</button>
        </form>
    </section>
@endsection
