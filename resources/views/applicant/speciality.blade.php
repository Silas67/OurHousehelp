@extends('layouts.main')
@section('speciality')
    <section class="about-section" id="about">
        <div class="container">
            <div class="text-center mb-40">
                <div class="col-lg-6 offset-lg-3">
                    <h2>Select Your Specialty</h2>

                    <p class="mt-2">
                        <small><strong>
                                Tip: Select more specialties to increase your visibility in searches. The more skills you
                                offer, the greater your chances of being noticed by potential clients!
                            </strong></small>
                    </p>

                </div>
            </div>

            <form action="{{ route('user.specialtiesupdate', $user->id) }}" method="POST">
                @csrf
                <div class="row">
                    {{-- <!-- Card 1: Cleaning & Housekeeping (Checked by Default) -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-center p-3">
                            <input type="checkbox" name="specialties[]" value="Cleaning & Housekeeping" id="cleaning"
                                class="mb-2"
                                {{ in_array('Cleaning & Housekeeping', $user->specialties ?? []) ? 'checked' : '' }}>
                            <label for="cleaning" class="font-weight-bold">
                                Cleaning & Housekeeping
                            </label>
                            <i class="fas fa-spray-can fa-2x mb-2"></i>
                            <p>Professional cleaning for a spotless, organized home.</p>
                        </div>
                    </div>

                    <!-- Card 2: Laundry (Checked by Default) -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-center p-3">
                            <input type="checkbox" name="specialties[]" value="Laundry" id="laundry" class="mb-2"
                                {{ in_array('Laundry', $user->specialties ?? []) ? 'checked' : '' }}>
                            <label for="laundry" class="font-weight-bold">
                                Laundry
                            </label>
                            <i class="fas fa-tshirt fa-2x mb-2"></i>
                            <p>Fresh, clean laundry with attention to every detail.</p>
                        </div>
                    </div>

                    <!-- Card 3: Errand & Housekeeping (Checked by Default) -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-center p-3">
                            <input type="checkbox" name="specialties[]" value="Errand & Housekeeping" id="errands"
                                class="mb-2"
                                {{ in_array('Errand & Housekeeping', $user->specialties ?? []) ? 'checked' : '' }}>
                            <label for="errands" class="font-weight-bold">
                                Errand & Housekeeping
                            </label>
                            <i class="fas fa-running fa-2x mb-2"></i>
                            <p>Help with daily errands and keeping your home tidy.</p>
                        </div>
                    </div>

                    <!-- Card 4: Nanny & Housekeeping -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-center p-3">
                            <input type="checkbox" name="specialties[]" value="Nanny & Housekeeping" id="nanny"
                                class="mb-2"
                                {{ in_array('Nanny & Housekeeping', $user->specialties ?? []) ? 'checked' : '' }}>
                            <label for="nanny" class="font-weight-bold">Nanny & Housekeeping</label>
                            <i class="fas fa-child fa-2x mb-2"></i>
                            <p>Babysitting, elderly care, and family support services.</p>
                        </div>
                    </div>

                    <!-- Card 5: Cook & Errands -->
                    <div class="col-md-4 mb-4">
                        <div class="card text-center p-3">
                            <input type="checkbox" name="specialties[]" value="Cook & Errands" id="cook" class="mb-2"
                                {{ in_array('Cook & Errands', $user->specialties ?? []) ? 'checked' : '' }}>
                            <label for="cook" class="font-weight-bold">Cook & Errands</label>
                            <i class="fas fa-utensils fa-2x mb-2"></i>
                            <p>Meal preparation and related errands for your culinary needs.</p>
                        </div>
                    </div> --}}
                    @foreach ($services as $service)
                        <div class="col-md-4 mb-4">
                            <div class="card text-center p-3">
                                <input type="checkbox" name="specialties[]" value="{{ ucfirst($service->service_name) }}"
                                    id="{{ ucfirst($service->service_name) }}" class="mb-2"
                                    {{ in_array(ucfirst($service->service_name), is_array($applicant->specialties) ? $applicant->specialties : json_decode($applicant->specialties ?? '[]', true) ?? []) ? 'checked' : '' }}>
                                <label for="{{ ucfirst($service->service_name) }}" class="font-weight-bold">
                                    {{ ucfirst($service->service_name) }}
                                </label>
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <p>{{ ucfirst($service->about_service) }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="text-center mt-4">
                    @if ($user->specialties)
                        <button type="submit" class="btn btn-secondary">Selection Submitted</button>
                    @else
                        <button type="submit" class="btn btn-primary">Submit Selection</button>
                    @endif
                </div>
            </form>

            <hr>

            <div class="mt-4">
                @if (Auth::user()->specialties)
                    <strong>Step 3: Please fill out the Guarantors' form.</strong><br>
                    <a href="{{ route('applicant.guarantors', ['user_id' => Auth::user()->id, 'name' => Auth::user()->name]) }}"
                        class="btn btn-primary">
                        Fill Guarantors' Forms
                    </a>
                @else
                    <strong>Please make your selection to proceed to the guarantors' form.</strong>
                @endif
            </div>

            <script>
                document.querySelector("form").addEventListener("submit", function(event) {
                    let checkboxes = document.querySelectorAll("input[name='specialties[]']");
                    let checkedOne = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    if (!checkedOne) {
                        event.preventDefault();
                        alert("Please select at least one specialty.");
                    }
                });
            </script>


        </div>
    </section>
@endsection
