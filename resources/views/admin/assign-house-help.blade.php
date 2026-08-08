@extends('layouts.adminmain')
@section('assign-house-help')
    <div class="container">
        <h3 class="row justify-content-center">Assign House Help</h3>

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Card for Assign House Help -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5>Assign House Help</h5>
                        </div>
                        <div class="card-body">
                            <form id="assignForm" action="{{ route('active.assign', $confirmation->id) }}" method="POST">
                                @csrf

                                <!-- Select House Help -->
                                <div class="form-group mt-3">
                                    <label for="house_help_id" style="font-weight: bold">Select House Help</label>
                                    <select class="form-control" id="house_help_id" name="house_help_id" required>
                                        <option value="">Select House Help</option>
                                        @foreach ($houseHelps as $houseHelp)
                                            <option value="{{ $houseHelp->id }}"
                                                {{ old('house_help_id', $confirmation->house_help_id ?? '') == $houseHelp->id ? 'selected' : '' }}>
                                                {{ $houseHelp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Select House Help Assistant -->
                                <div class="form-group mt-4">
                                    <label for="house_help_assist_id" style="font-weight: bold">Select House Help
                                        Assistant</label>
                                    <select class="form-control" id="house_help_assist_id" name="house_help_assist_id">
                                        <option value="">Select Assistant (Optional)</option>
                                        @foreach ($assistants as $assistant)
                                            <option value="{{ $assistant->id }}"
                                                {{ old('house_help_assist_id', $confirmation->house_help_assist_id ?? '') == $assistant->id ? 'selected' : '' }}>
                                                {{ $assistant->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Hidden Input for Status -->
                                <input type="hidden" name="status" value="active">

                                <!-- Submit Button -->
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-block w-100">Assign House Help &
                                        Activate Service Request</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('assignForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Perform the normal form submission to assign the house help
                const form = event.target;
                const formData = new FormData(form);
                const actionUrl = form.getAttribute('action');

                fetch(actionUrl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // If house help was successfully assigned, update service request status
                        updateServiceRequestStatus({{ $confirmation->service_request_id }});
                    })
                    .catch(error => {
                        console.error('Error assigning house help:', error);
                    });

            });

            // Function to update service request status via AJAX
            function updateServiceRequestStatus(serviceRequestId) {
                fetch(`/admin/service-request/${serviceRequestId}/update-active`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content') // Include CSRF token
                        },
                        body: JSON.stringify({
                            status: 'active'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle success response for service request status update
                        alert('Service request status updated successfully');
                    })
                    .catch(error => {
                        console.error('Error updating service request status:', error);
                    });
            }
        </script>
    </div>
@endsection
