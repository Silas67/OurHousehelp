@extends('layouts.adminmain')

@section('newpackage')
    <div class="container pb-3 py-5">
        <h1>Create New Package</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('packages.store') }}" method="POST" class="form-style" id="packageForm">
            @csrf

            <h2 class="form-title">Create Package</h2>

            <!-- Package Details -->
            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Package Details</legend>

                <div class="form-group">
                    <label for="name" class="form_label">Package Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cost" class="form_label">Cost</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" value="0" required>
                    @error('cost')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="days_per_week" class="form_label">How many days per week</label>
                    <select class="form-control" id="days_per_week" name="days_per_week" required>
                        <option value="" disabled selected>Select number of days</option>
                        @for ($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('days_per_week')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Percentage Field -->
                <div class="form-group">
                    <label for="percentage" class="form_label">Percentage (%)</label>
                    <input type="number" step="0.01" class="form-control" id="percentage" name="percentage" placeholder="Enter percentage (e.g., 20)" required>
                    @error('percentage')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <!-- Working Hours -->
            <fieldset class="form_fieldset">
                <legend><span class="number">2</span> Working Hours</legend>
                @for ($i = 1; $i <= 5; $i++)
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="working_hours_start_{{ $i }}">Working Hour {{ $i }} Start</label>
                            <input type="time" class="form-control working-hour-start"
                                   id="working_hours_start_{{ $i }}" name="working_hours_start_{{ $i }}">
                        </div>
                        <div class="col-md-6" hidden>
                            <label for="working_hours_end_{{ $i }}">Working Hour {{ $i }} End</label>
                            <input type="time" class="form-control working-hour-end"
                                   id="working_hours_end_{{ $i }}" name="working_hours_end_{{ $i }}">
                        </div>
                        <small class="text-danger" id="error_{{ $i }}" style="display: none;">
                            End time must be later than start time.
                        </small>
                    </div>
                @endfor
            </fieldset>
            <br>

            <!-- Additional Information -->
            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">3</span> Additional Information</legend>

                <div class="form-group">
                    <label for="point1" class="form_label">Hint 1</label>
                    <input class="form-control" id="point1" type="text" name="point1">
                    @error('point1')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="point2" class="form_label">Hint 2</label>
                    <input class="form-control" id="point2" type="text" name="point2">
                    @error('point2')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="point3" class="form_label">Hint 3</label>
                    <input class="form-control" id="point3" type="text" name="point3">
                    @error('point3')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form_label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </fieldset>
            <br>

            <button type="submit" class="btn btn-primary form_button">Create Package</button>
        </form>
    </section>

    <!-- JavaScript for Time Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const validateTime = (startInput, endInput, errorElement) => {
                const startTime = startInput.value;
                const endTime = endInput.value;

                if (startTime && endTime && endTime <= startTime) {
                    errorElement.style.display = 'inline';
                    endInput.setCustomValidity('End time must be later than start time.');
                } else {
                    errorElement.style.display = 'none';
                    endInput.setCustomValidity('');
                }
            };

            Array.from({ length: 5 }, (_, i) => {
                const startInput = document.getElementById(`working_hours_start_${i + 1}`);
                const endInput = document.getElementById(`working_hours_end_${i + 1}`);
                const errorElement = document.getElementById(`error_${i + 1}`);

                [startInput, endInput].forEach(input => input.addEventListener('input', () => validateTime(startInput, endInput, errorElement)));
            });
        });
    </script>
@endsection
