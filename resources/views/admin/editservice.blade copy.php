@extends('layouts.adminmain')

@section('editservice')

    <div class="container pb-3 py-5">
        <h1>Edit Service</h1>
    </div>

    <section class="container py-5 bg-white">

        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="form-style" id="serviceForm">
            @csrf
            @method('PUT') <!-- Use PUT method for updating -->

            <h2 class="form-title">Edit Service</h2>

            <fieldset class="form_fieldset">
                <legend>Service Details</legend>

                <!-- Service Name -->
                <div class="form-group">
                    <label class="form_label fw-bold" for="service_name">Service Name</label>
                    <input type="text" class="form-control" id="service_name" name="service_name"
                        value="{{ old('service_name', $service->service_name) }}" placeholder="The name of the service" required>
                    @error('service_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- About Service -->
                <div class="form-group">
                    <label class="form_label fw-bold mt-3" for="about_service">Description</label>
                    <textarea class="form-control" id="about_service" name="about_service" rows="3"
                        placeholder="Short hint about the service for Admin purpose" required>{{ old('about_service', $service->about_service) }}</textarea>
                    @error('about_service')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <!-- Icon Class -->
            <div class="form-group">
                <label for="icon_class" class=" fw-bold">Icon <small>(Select an icon to represent the service)</small></label>
                <select name="icon_class" id="icon_class" class="form-control">
                    <option value="fas fa-plus" {{ $service->icon_class === 'fas fa-plus' ? 'selected' : '' }}>Plus (Default)</option>
                    <option value="fas fa-utensils" {{ $service->icon_class === 'fas fa-utensils' ? 'selected' : '' }}>Utensils</option>
                    <option value="fas fa-child" {{ $service->icon_class === 'fas fa-child' ? 'selected' : '' }}>Child</option>
                    <option value="fas fa-running" {{ $service->icon_class === 'fas fa-running' ? 'selected' : '' }}>Running</option>
                    <option value="fas fa-tshirt" {{ $service->icon_class === 'fas fa-tshirt' ? 'selected' : '' }}>T-Shirt</option>
                    <option value="fas fa-spray-can" {{ $service->icon_class === 'fas fa-spray-can' ? 'selected' : '' }}>Spray Can</option>
                    <option value="fas fa-car" {{ $service->icon_class === 'fas fa-car' ? 'selected' : '' }}>Driver</option>
                    <option value="fas fa-wrench" {{ $service->icon_class === 'fas fa-wrench' ? 'selected' : '' }}>Plumber</option>
                    <option value="fas fa-bolt" {{ $service->icon_class === 'fas fa-bolt' ? 'selected' : '' }}>Electrician</option>
                    <option value="fas fa-home" {{ $service->icon_class === 'fas fa-home' ? 'selected' : '' }}>House Help</option>
                </select>
            </div>

            <!-- Hours of Work -->
            <div class="form-group py-2">
                <label for="hours_of_work" class="fw-bold">Hours of Work</label>
                <input type="number" name="hours_of_work" id="hours_of_work" class="form-control" min="0"
                    value="{{ old('hours_of_work', $service->hours_of_work) }}" placeholder="Number of Hours for the service">
                @error('hours_of_work')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Premium Service -->
            <div class="form-group py-2">
                <label for="premium" class=" fw-bold">Premium Service?</label><br>
                <div class="custom-switch">
                    <input type="checkbox" name="premium" id="premium" value="1" class="checkbox-input"
                        {{ $service->premium ? 'checked' : '' }}>
                    <label for="premium" class="switch-label"></label><small> off / on - for premium services like Nanny, etc</small>
                </div>
            </div>

            <!-- 'Number of Apartment?' -->
            <div class="form-group py-2">
                <label for="number_of_apartment" class=" fw-bold">Number of Apartment?</label><br>
                <div class="custom-switch">
                    <input type="hidden" name="number_of_apartment" value="0">
                    <input type="checkbox" name="number_of_apartment" id="number_of_apartment" value="1" class="checkbox-input"
                        {{ $service->number_of_apartment ? 'checked' : '' }}>
                    <label for="number_of_apartment" class="switch-label"></label><small> off / on - If service requires apartment, put on</small>
                </div>
            </div>

            <!-- Packages and Costs -->
            <div class="form-group py-2">
                <label for="package_cost_table" class=" fw-bold">Packages and Costs</label><br><small>For services that do not require apartment, put in cost for each package</small>

                @if ($packages->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        No packages available. Please create packages first before setting costs.
                    </div>
                @else
                    <table class="table table-bordered" id="package_cost_table">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Cost (₦)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($service->servicePackageCosts as $package)
                                <tr>
                                    <td>
                                        <span>{{ $package->name }}</span>
                                        <input type="hidden" name="package_name[]" value="{{ $package->name }}">
                                    </td>
                                    <td>
                                        <input type="number" name="package_cost[]" class="form-control package-cost-input" min="0"
                                            value="{{ old('package_cost.' . $loop->index, $package->cost) }}" placeholder="Enter cost for {{ $package->name }}" required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Special Services -->
            <div class="form-group py-2">
                <label for="special_services" class="fw-bold">Special Service?</label><br>
                <div class="custom-switch">
                    <input type="checkbox" name="special_services" id="special_services" value="1" class="checkbox-input"
                        {{ $service->special_services ? 'checked' : '' }}>
                    <label for="special_services" class="switch-label"></label>
                    <small> off / on - If the services is a special service like driver, etc put on</small>
                </div>
            </div>

            <!-- Note/Warning Information -->
            <div class="form-group">
                <label for="dos_and_donts" class="fw-bold">Note/Warning Information (optional)</label>
                <br><small>Add warning notes for clients. Dos and Don'ts</small>
                <textarea name="dos_and_donts" id="dos_and_donts" class="form-control"
                    placeholder="Add warning notes for clients. Dos and Don'ts">{{ old('dos_and_donts', $service->dos_and_donts) }}</textarea>
                @error('dos_and_donts')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <br>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary form_button">Update Service</button>

        </form>
    </section>

@endsection
