@extends('layouts.adminmain')

@section('newpackage')
    <div class="container pb-3 py-5">
        <h1>Create New Package</h1>
    </div>

    <section class="container py-5 bg-white">
        <form action="{{ route('packages.store') }}" method="POST" class="form-style" id="packageForm">
            @csrf

            <h2 class="form-title">Create Package</h2>

            <fieldset class="form_fieldset">
                <legend><span class="number">1</span> Package Details</legend>

                <div class="form-group">
                    <label class="form_label" for="name">Package Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" hidden>
                    <label class="form_label" for="cost">Cost</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" value=0
                        required>
                    @error('cost')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="days_per_week">How many days per week</label>
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
            </fieldset>
            <br>



            <fieldset class="form_fieldset">
                <legend><span class="number">2</span> Working Hours</legend>

                @for ($i = 1; $i <= 5; $i++)
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="working_hours_start_{{ $i }}">Working Hour {{ $i }}
                                Start</label>
                            <input type="time" class="form-control working-hour-start"
                                id="working_hours_start_{{ $i }}" name="working_hours_start_{{ $i }}">
                        </div>


                        <div class="form-group col-md-6">
                            <label for="working_hours_end_{{ $i }}">Working Hour {{ $i }}
                                End</label>
                            <input type="time" class="form-control working-hour-end"
                                id="working_hours_end_{{ $i }}" name="working_hours_end_{{ $i }}">
                        </div>
                    </div>
                    <small class="text-danger" id="error_{{ $i }}" style="display:none;">End time must be later
                        than start time.</small>
                @endfor
            </fieldset>

            <br>

            <fieldset class="form_fieldset">
                <legend class="form_legend"><span class="number">3</span> Additional Information</legend>

                <div class="form-group">
                    <label class="form_label" for="point1">Hint 1</label>
                    <input class="form-control" id="point1" type="text" name="point1">
                    @error('point1')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="point2">Hint 2</label>
                    <input class="form-control" id="point2" type="text" name="point2">
                    @error('point2')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="point3">Hint 3</label>
                    <input class="form-control" id="point3" type="text" name="point3">
                    @error('point3')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form_label" for="description">Description</label>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all pairs of start and end time fields
            const workingHourPairs = Array.from({
                length: 5
            }, (_, i) => ({
                start: document.getElementById(`working_hours_start_${i + 1}`),
                end: document.getElementById(`working_hours_end_${i + 1}`),
                error: document.getElementById(`error_${i + 1}`)
            }));

            workingHourPairs.forEach(pair => {
                // Add event listeners for start and end time input fields
                pair.start.addEventListener('input', validateTime);
                pair.end.addEventListener('input', validateTime);

                function validateTime() {
                    // Get the values of start and end times
                    const startTime = pair.start.value;
                    const endTime = pair.end.value;

                    // Check if both times are set and validate
                    if (startTime && endTime && endTime <= startTime) {
                        // Show error message if end time is earlier than or equal to start time
                        pair.error.style.display = 'inline';
                        pair.end.setCustomValidity('End time must be later than start time.');
                    } else {
                        // Hide error message and reset validation
                        pair.error.style.display = 'none';
                        pair.end.setCustomValidity('');
                    }
                }
            });
        });
    </script>
@endsection
