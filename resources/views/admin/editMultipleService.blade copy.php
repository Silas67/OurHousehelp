@extends('layouts.adminmain')

@section('editMultipleServices')

    <div class="container pb-3 py-5">
        <h1>Edit Multiple Services</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('multipleservice.update', $multipleService->id) }}" method="POST" class="form-style"
            id="multipleServiceForm">
            @csrf
            @method('PUT') <!-- Specify the HTTP method as PUT for updates -->

            <h2 class="form-title">Edit Multiple Services</h2>

            <!-- Select Services -->
            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Select Services</legend>
                <div class="form-group">
                    <label for="multipleservice" class="form_label">Choose from List of Single Services</label>
                    <ol id="servicesList">
                        @foreach (json_decode($multipleService->services) as $key => $serviceName)
                            <!-- Iterate over existing services -->
                            <li class="py-1">
                                <select class="form-control multipleservice-dropdown" name="multipleservice[]">
                                    <option value="" disabled selected>Select a service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->service_name }}"
                                            {{-- {{ in_array($service->service_name, old('multipleservice', json_decode($multipleService->services))) ? 'selected' : '' }}>
                                            {{ $service->service_name }} --}}
                                            {{ $service->service_name == $serviceName ? 'selected' : '' }}>
                                            {{ $service->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        @endforeach


                        @for ($i = count(json_decode($multipleService->services)); $i < 8; $i++)
                            <!-- If there are less than 8 services, show the remaining empty dropdowns -->
                            <li class="py-1">
                                <select class="form-control multipleservice-dropdown" name="multipleservice[]">
                                    <option value="" disabled selected>Select a service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->service_name }}">
                                            {{ $service->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        @endfor
                    </ol>

                    <div class="py-2">
                        <span id="selectedCount"
                            class="text-success fw-bold">{{ count(json_decode($multipleService->services)) }} service(s)
                            selected</span>
                    </div>

                    @error('multipleservice')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <br>

            <!-- Cost for the Service -->
            <fieldset class="form_fieldset">
                <legend><span class="number">2</span> Cost for the Service</legend>
                <div class="form-group">
                    <label for="cost" class="form_label">Cost for this Category of Multiple Services</label>
                    <input type="number" step="200" class="form-control" id="cost" name="cost"
                        value="{{ old('cost', $multipleService->cost) }}" required>
                    @error('cost')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <br>

            <!-- Additional Information -->
            <fieldset class="form_fieldset">
                <legend><span class="number">3</span> Additional Information</legend>
                <div class="form-group">
                    <label for="description" class="form_label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $multipleService->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <br>

            <button type="submit" class="btn btn-primary form_button">Update Multiple Services</button>
        </form>
    </section>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.multipleservice-dropdown');
            const selectedCountSpan = document.getElementById('selectedCount');

            // Function to update selected count and disable duplicate options
            const updateSelections = () => {
                const selectedValues = Array.from(dropdowns)
                    .map(d => d.value)
                    .filter(v => v !== '');

                // Update the selected count
                selectedCountSpan.textContent =
                    `${selectedValues.length} service${selectedValues.length === 1 ? '' : 's'} selected`;

                // Disable duplicate options in all dropdowns
                dropdowns.forEach(dropdown => {
                    Array.from(dropdown.options).forEach(option => {
                        if (selectedValues.includes(option.value) && dropdown.value !== option
                            .value) {
                            option.disabled = true;
                        } else {
                            option.disabled = false;
                        }
                    });
                });
            };

            // Attach change event listeners to all dropdowns
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', updateSelections);
            });

            // Minimum two services validation (Updated in case of JavaScript disabled)
            document.getElementById('multipleServiceForm').addEventListener('submit', function(e) {
                const dropdowns = document.querySelectorAll('.multipleservice-dropdown');
                const selectedValues = Array.from(dropdowns)
                    .map(d => d.value)
                    .filter(v => v !== '');

                if (selectedValues.length < 2) {
                    e.preventDefault();
                    alert('Please select at least 2 services.');
                }
            });

        });
    </script>
@endsection


<!-- resources/views/admin/editMultipleService.blade.php -->

{{-- @extends('layouts.adminmain')

@section('editMultipleServices')
    <div class="container pb-3 py-5">
        <h1>Edit Multiple Service</h1>
    </div>

    <section class="container py-5 bg-white">
        <!-- resources/views/admin/editMultipleService.blade.php -->

        <form action="{{ route('multipleservice.update', $multipleService->id) }}" method="POST" class="form-style"
            id="multipleServiceForm">
            @csrf
            @method('PUT')

            <!-- Select Services -->
            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Select Services</legend>
                <div class="form-group">
                    <label for="multipleservice" class="form_label">Choose from List of Single Services</label>
                    <ol id="servicesList">
                        @foreach (json_decode($multipleService->services) as $serviceName)
                            <li class="py-1">
                                <select class="form-control multipleservice-dropdown" name="multipleservice[]">
                                    <option value="" disabled selected>Select a service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->service_name }}"
                                            {{ $service->service_name == $serviceName ? 'selected' : '' }}>
                                            {{ $service->service_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        @endforeach
                    </ol>

                    @error('multipleservice')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>

            <!-- Other form fields for cost and description -->

            <button type="submit" class="btn btn-primary form_button">Update Multiple Services</button>
        </form>

    </section>

    <!-- JavaScript for Dropdown Selections -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.multipleservice-dropdown');
            const selectedCountSpan = document.getElementById('selectedCount');

            // Function to update selected count and disable duplicate options
            const updateSelections = () => {
                const selectedValues = Array.from(dropdowns)
                    .map(d => d.value)
                    .filter(v => v !== '');

                // Update the selected count
                selectedCountSpan.textContent =
                    `${selectedValues.length} service${selectedValues.length === 1 ? '' : 's'} selected`;

                // Disable duplicate options in all dropdowns
                dropdowns.forEach(dropdown => {
                    Array.from(dropdown.options).forEach(option => {
                        if (selectedValues.includes(option.value) && dropdown.value !== option
                            .value) {
                            option.disabled = true;
                        } else {
                            option.disabled = false;
                        }
                    });
                });
            };

            // Attach change event listeners to all dropdowns
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', updateSelections);
            });
        });
    </script>
@endsection --}}
