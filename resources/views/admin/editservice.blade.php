@extends('layouts.adminmain')

@section('editservice')
    <div class="container pb-3 py-5">
        <h1>Edit Service - {{ $service->service_name }}</h1>
    </div>

    <section class="container py-5 bg-white">

        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="form-style">
            @csrf
            @method('PUT')

            <h2 class="form-title">Edit Service</h2>


            <fieldset class="form_fieldset">
                <legend>Service Details</legend>

                <!-- Service Name -->
                <div class="form-group">
                    <label for="service_name" class="fw-bold">Service Name</label>
                    <input type="text" name="service_name" id="service_name" class="form-control"
                        value="{{ old('service_name', $service->service_name) }}" required>
                    @error('service_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description of Service -->
                <div class="form-group">
                    <label for="about_service" class="fw-bold">Description</label>
                    <textarea name="about_service" id="about_service" class="form-control" rows="3" maxlength="150"
                        oninput="updateCountdown()">{{ old('about_service', $service->about_service) }}</textarea>
                    <small id="characterCountdown" class="form-text text-muted">150 characters remaining</small>
                    @error('about_service')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    function updateCountdown() {
                        const textarea = document.getElementById('about_service');
                        const countdown = document.getElementById('characterCountdown');
                        const remaining = 150 - textarea.value.length;
                        countdown.textContent = `${remaining} characters remaining`;
                    }
                </script>

            </fieldset>
            
            <!-- Icon Class -->
            <div class="form-group">
                <label for="icon_class" class="fw-bold">Icon Class</label>
                <select name="icon_class" id="icon_class" class="form-control">
                    <option value="fas fa-plus" {{ $service->icon_class == 'fas fa-plus' ? 'selected' : '' }}>Plus</option>
                    <option value="fas fa-utensils" {{ $service->icon_class == 'fas fa-utensils' ? 'selected' : '' }}>
                        Utensils</option>
                    <option value="fas fa-child" {{ $service->icon_class == 'fas fa-child' ? 'selected' : '' }}>Child
                    </option>
                    <option value="fas fa-running" {{ $service->icon_class == 'fas fa-running' ? 'selected' : '' }}>Running
                    </option>
                    <option value="fas fa-tshirt" {{ $service->icon_class == 'fas fa-tshirt' ? 'selected' : '' }}>T-Shirt
                    </option>
                    <option value="fas fa-spray-can" {{ $service->icon_class == 'fas fa-spray-can' ? 'selected' : '' }}>
                        Spray Can</option>
                    <option value="fas fa-car" {{ $service->icon_class == 'fas fa-car' ? 'selected' : '' }}>Driver</option>
                    <option value="fas fa-wrench" {{ $service->icon_class == 'fas fa-wrench' ? 'selected' : '' }}>Plumber
                    </option>
                    <option value="fas fa-bolt" {{ $service->icon_class == 'fas fa-bolt' ? 'selected' : '' }}>Electrician
                    </option>
                    <option value="fas fa-home" {{ $service->icon_class == 'fas fa-home' ? 'selected' : '' }}>House Help
                    </option>
                </select>
            </div>


            
            <!-- Hours of Work -->
            <div class="form-group py-2">
                <label for="hours_of_work" class="fw-bold">Hours of Work</label><br>
                <small class="text-danger fw-bold">Some services do not require time of work. Eg. Driver, Mechanic</small>
                <input type="number" name="hours_of_work" id="hours_of_work" class="form-control" min="0"
                    value="{{ old('hours_of_work', $service->hours_of_work) }}">
                @error('hours_of_work')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <div style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0;">
                <h5>Select the Type of Service</h5>
            
                <!-- Premium Max Service -->
                <div class="service-option py-2">
                    <label for="service_max" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_max" value="PMS" class="toggle-input"
                            onchange="toggleBaseCost()" {{ $service->service_type == 'PMS' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        Premium Max Service (PMS)
                    </label>
                    <small class="description-text">For premium max services like nanny, etc.</small>
                </div>
            
                <!-- Premium Special Service -->
                <div class="service-option py-2">
                    <label for="service_special" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_special" value="PSS" class="toggle-input"
                            onchange="toggleBaseCost()" {{ $service->service_type == 'PSS' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        Premium Special Service (PSS)
                    </label>
                    <small class="description-text">For special services like a driver, etc.</small>
                </div>
            
                <!-- Premium Regular Service -->
                <div class="service-option py-2">
                    <label for="service_regular" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_regular" value="PRS" class="toggle-input"
                            onchange="toggleBaseCost()" {{ $service->service_type == 'PRS' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                        Premium Regular Services (PRS)
                    </label>
                    <small class="description-text">For regular services like laundry, cleaning, etc.</small>
                </div>
            </div>
            

            <!-- Base Cost Input -->
            <div class="form-group py-2" id="base_cost_container" style="display: none;">
                <label for="base_cost" class="fw-bold">Base Cost (₦)</label>
                <input type="number" name="base_cost" id="base_cost" class="form-control" min="0"
                    placeholder="Enter base cost for the service" value="{{ old('base_cost', $service->base_cost) }}">
                @error('base_cost')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <script>
                function toggleBaseCost() {
                    const selectedType = document.querySelector('input[name="service_type"]:checked');
                    const baseCostContainer = document.getElementById('base_cost_container');

                    if (selectedType && (selectedType.value === 'PMS' || selectedType.value === 'PSS')) {
                        baseCostContainer.style.display = 'block';
                    } else {
                        baseCostContainer.style.display = 'none';
                    }
                }
            </script>


<!-- 'Number of Apartment?' -->
<div class="form-group py-2" id="srad_container" style="display: none;">
    <label for="number_of_apartment" class="fw-bold">Service Requiring Apartment Details (SRAD)</label><br>
    <div class="custom-switch">
        <!-- Hidden field ensures a value of 0 is sent when the checkbox is unchecked -->
        <input type="hidden" name="number_of_apartment" value="0">
        <input type="checkbox" name="number_of_apartment" id="number_of_apartment" value="1"
            class="checkbox-input" {{ $service->number_of_apartment == 1 ? 'checked' : '' }}>
        <label for="number_of_apartment" class="switch-label"></label>
        <small> off / on - If service requires details like No. of Rooms, Parlour, etc, put on</small>
    </div>
</div>




            <!-- Apartment Details -->
            <h5 id="apartment_details" style="display: none;">This Service will be charged from Apartment Details</h5>

            <hr>



            <div class="form-group">
                <label for="dos_and_donts" class="fw-bold">Note/Warning Inforation (optional)</label>
                <br><small>Add warning notes for clients. Dos and Don'ts</small>
                <textarea name="dos_and_donts" id="dos_and_donts" class="form-control" rows="7"
                    placeholder="Add warning notes for clients. Dos and Don'ts">{{ old('dos_and_donts', $service->dos_and_donts) }}</textarea>
                @error('dos_and_donts')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <br>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Service</button>

        </form>
    </section>   
@endsection
