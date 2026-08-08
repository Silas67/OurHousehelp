@extends('layouts.adminmain')
@section('confirm-request')
    <div class="container">
        <h4>Service Request Confirmation</h4>

        <!-- Client Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Client Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $serviceRequest->name }} {{ $serviceRequest->last_name }}</p>
                <p><strong>Email:</strong> {{ $serviceRequest->email }}</p>
                <p><strong>Phone:</strong> {{ $serviceRequest->phone }}</p>
                <p><strong>NIN:</strong> {{ $serviceRequest->nin }}</p>
                <p><strong>Package:</strong> {{ $serviceRequest->package }}</p>
            </div>
        </div>

        <!-- Confirmation Switches -->
        <form id="confirmation-form">
            @csrf
            <input type="hidden" id="confirmation_id" value="{{ $confirmation->id }}">
            <input type="hidden" id="service_request_id" value="{{ $serviceRequest->id }}">

            <!-- Toggle Switches for Confirmation -->
            @foreach (['nin_verified', 'payment_verified', 'client_contacted', 'other_checks_completed'] as $field)
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" id="{{ $field }}" name="{{ $field }}"
                        {{ $confirmation->$field ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $field }}">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                </div>
            @endforeach

            <!-- Action Buttons -->
            <div class="mt-4">
                <button type="button" id="confirmBtn" class="btn btn-success" disabled>Confirm Order</button>
                <button type="button" id="pendingBtn" class="btn btn-warning">Pending</button>
                <button type="button" id="cancelBtn" class="btn btn-danger">Cancel</button>
            </div>
        </form>

        <!-- Assign House Help Button -->
        <div class="mt-4">
            <button type="button" id="assignHouseHelpBtn" class="btn btn-primary" disabled>Assign House Help</button>
        </div>
    </div>

    <!-- JavaScript for Managing Confirmations -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize elements
            const switches = ['nin_verified', 'payment_verified', 'client_contacted', 'other_checks_completed'];
            const confirmBtn = document.getElementById('confirmBtn');
            const assignHouseHelpBtn = document.getElementById('assignHouseHelpBtn');
            const confirmationId = document.getElementById('confirmation_id').value;
            const serviceRequestId = document.getElementById('service_request_id').value;

            // Toggle Confirmation Button
            function toggleConfirmButton() {
                confirmBtn.disabled = !switches.every(id => document.getElementById(id).checked);
            }

            // AJAX Request for Switch Status Update
            function saveSwitchStatus(switchId, value) {
                fetch(`/admin/confirmation/${confirmationId}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        switch: switchId,
                        value: value,
                        service_request_id: serviceRequestId
                    })
                }).then(response => response.json())
                .then(data => console.log(data.message))
                .catch(error => console.error('Error:', error));
            }

            // Update Status (Confirm, Pending, Cancel) Function
            function updateServiceRequestStatus(status) {
                fetch(`/admin/service-request/${serviceRequestId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: status })
                }).then(response => response.json())
                .then(data => console.log(data.message))
                .catch(error => console.error('Error:', error));
            }

            // Event Listeners for Switches
            switches.forEach(id => {
                document.getElementById(id).addEventListener('change', function () {
                    saveSwitchStatus(id, this.checked);
                    toggleConfirmButton();
                });
            });

            // Confirm Button Click
            confirmBtn.addEventListener('click', function () {
                updateServiceRequestStatus('confirmed');
                assignHouseHelpBtn.disabled = false;
                alert('Service request confirmed');
            });

            // Pending Button Click
            document.getElementById('pendingBtn').addEventListener('click', function () {
                updateServiceRequestStatus('pending');
                assignHouseHelpBtn.disabled = true;
                alert('Service request set to pending');
            });

            // Cancel Button Click
            document.getElementById('cancelBtn').addEventListener('click', function () {
                updateServiceRequestStatus('cancelled');
                assignHouseHelpBtn.disabled = true;
                alert('Service request cancelled');
            });

            // Assign House Help Button Click
            assignHouseHelpBtn.addEventListener('click', function () {
                const url = `{{ route('show.assignForm', ':id') }}`.replace(':id', serviceRequestId);
                window.location.href = url;
            });


            // Initial Check on Page Load
            toggleConfirmButton();
        });
    </script>
@endsection
