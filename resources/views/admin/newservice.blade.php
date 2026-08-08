@extends('layouts.adminmain')

@section('newservice')
    <div class="container pb-3 py-5">
        <h1>Create New Service</h1>
    </div>

    <section class="container py-5 bg-white">

        <form action="{{ route('admin.services.store') }}" method="POST" class="form-style" id="serviceForm">
            @csrf

            <h2 class="form-title">Create Service</h2>


            <fieldset class="form_fieldset">
                <legend>Service Details</legend>

                <!-- Service Name -->
                <div class="form-group">
                    <label class="form_label fw-bold" for="service_name">Service Name</label>
                    <input type="text" class="form-control" id="service_name" name="service_name"
                        placeholder="The name of the service" required>
                    @error('service_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Description of Service -->
                <div class="form-group">
                    <label class="form_label fw-bold mt-3" for="about_service">Description </label>
                    <textarea class="form-control" id="about_service" name="about_service" rows="3"
                        placeholder="Short hint about the service for client's to know about the service" required maxlength="150"
                        oninput="updateCountdown()"></textarea>
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
                <label for="icon_class" class=" fw-bold">Icon <small>(Select an icon to represent the
                        service)</small></label>
                <select name="icon_class" id="icon_class" class="form-control">
                    <option value="fas fa-plus" selected>Plus (Default)</option>
                    <option value="fas fa-utensils">Utensils</option>
                    <option value="fas fa-child">Child</option>
                    <option value="fas fa-running">Running</option>
                    <option value="fas fa-tshirt">T-Shirt</option>
                    <option value="fas fa-spray-can">Spray Can</option>
                    <option value="fas fa-car">Driver</option>
                    <option value="fas fa-wrench">Plumber</option>
                    <option value="fas fa-bolt">Electrician</option>
                    <option value="fas fa-home">House Help</option>
                </select>
            </div>

            <!-- Hours of Work -->
            <div class="form-group py-2">
                <label for="hours_of_work" class="fw-bold">Hours of Work</label><br>
                <small class="text-danger fw-bold">Some services do not require time of work. Eg. Driver, Mechanic</small>
                <input type="number" name="hours_of_work" id="hours_of_work" class="form-control" min="0"
                    placeholder="Number of Hours for the service if required">
                @error('hours_of_work')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            
            <div style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0;">
                <h5>Select the Type of Service</h5>

                <!-- Premium Max Service -->
                <div class="service-option py-2">
                    <label for="service_max" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_max" value="PMS" class="toggle-input" onchange="toggleBaseCost()">
                        <span class="toggle-slider"></span>
                        Premium Max Service (PMS)
                    </label>
                    <small class="description-text">For services like nanny, Cook.</small>
                </div>

                <!-- Premium Special Service -->
                <div class="service-option py-2">
                    <label for="service_special" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_special" value="PSS" class="toggle-input" onchange="toggleBaseCost()">
                        <span class="toggle-slider"></span>
                        Premium Special Service (PSS)
                    </label>
                    <small class="description-text">For services like laundry, errand.</small>
                </div>

                <!-- Premium Regular Service -->
                <div class="service-option py-2">
                    <label for="service_regular" class="toggle-label fw-bold">
                        <input type="radio" name="service_type" id="service_regular" value="PRS" class="toggle-input" onchange="toggleBaseCost()">
                        <span class="toggle-slider"></span>
                        Premium Regular Services (PRS)
                    </label>
                    <small class="description-text">For services like cleaning, etc.</small>
                </div>
            </div>

            <!-- Base Cost Input -->
            <div class="form-group py-2" id="base_cost_container" style="display: none;">
                <label for="base_cost" class="fw-bold">Base Cost (₦)</label>
                <input type="number" name="base_cost" id="base_cost" class="form-control" min="0" placeholder="Enter base cost for the service">
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
                        class="checkbox-input">
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
                    placeholder="Add warning notes for clients. Dos and Don'ts"></textarea>
                @error('dos_and_donts')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>


            <br>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary form_button">Create Service</button>

        </form>
    </section>

@endsection
