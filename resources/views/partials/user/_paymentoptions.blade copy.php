<section class="about-section" id="about">
    <div class="container">
        <div class="row text-center mb-40 mb-xs-30">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-title_home">
                    <h2>Select a Payment Plan</h2>
                    <span class="b-line"></span>
                    <p>
                        Choose a payment plan that best suits your needs. We offer flexible payment options to ensure
                        you can enjoy our services conveniently.
                    </p>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>


        <div class="payment-plan-section mt-5">
            <form action="{{ route('payments.process') }}" method="POST">
                @csrf
                @if (Auth::check())
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                @endif
                <input type="hidden" name="payment_plan_id" id="selectedPlanId" required>
                <input type="hidden" name="amount" id="selectedAmount">
                <input type="hidden" name="about_payment" id="selectedAbout">
                <input type="hidden" name="num_searches" id="selectedNumSearches">
                <!-- Ensure num_searches is updated correctly -->

                <div class="row justify-content-center">
                    @foreach ($plans as $plan)
                        <div class="col-md-4">
                            <div class="card p-3 mb-4 text-center"
                                style="border: 2px solid #28a745; background-color: #edf9f0;">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-2x mb-3"></i>
                                    <h5 style="margin-bottom: 1px" class="card-title">{{ $plan->plan_name }}</h5>
                                    <p style="margin-bottom: 1px" class="card-text">₦{{ number_format($plan->cost, 2) }}
                                    </p>
                                    <p style="margin-bottom: 1px" class="card-text text-muted">{{ $plan->about }}</p>
                                    <p style="margin-bottom: 1px" class="card-text text-muted">Duration:
                                        @switch($plan->duration)
                                            @case(1)
                                                One Day
                                            @break

                                            @case(7)
                                                One Week
                                            @break

                                            @case(30)
                                                One Month
                                            @break

                                            @case(90)
                                                Three Months
                                            @break

                                            @case(180)
                                                Six Months
                                            @break

                                            @case(365)
                                                One Year
                                            @break

                                            @default
                                                {{ $plan->duration }} Days
                                        @endswitch
                                    </p>
                                    <p style="margin-bottom: 1px" class="card-text text-muted">Number of Search Views:
                                        {{ $plan->num_searches }}</p>

                                    <!-- Radio button for selecting this plan -->
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input plan-option" type="radio"
                                            name="payment_frequency" id="plan_{{ $plan->id }}"
                                            style="border: 2px solid #28a745" value="{{ $plan->plan_name }}"
                                            data-amount="{{ $plan->cost }}" data-about="{{ $plan->about }}"
                                            data-plan-id="{{ $plan->id }}"
                                            data-num-searches="{{ $plan->num_searches }}" required>
                                        <label class="form-check-label" for="plan_{{ $plan->id }}">Select
                                            {{ $plan->plan_name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                </div>
            </form>
        </div>

        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".plan-option").forEach(function(radio) {
                    radio.addEventListener("change", function() {
                        document.getElementById("selectedPlanId").value = this.getAttribute(
                            "data-plan-id");
                        document.getElementById("selectedAmount").value = this.getAttribute(
                            "data-amount");
                        document.getElementById("selectedAbout").value = this.getAttribute(
                            "data-about");
                        document.getElementById("selectedNumSearches").value = this.getAttribute(
                            "data-num-searches");
                    });
                });
            });
        </script> --}}

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let planOptions = document.querySelectorAll(".plan-option");

                planOptions.forEach((option) => {
                    option.addEventListener("change", function() {
                        document.getElementById("selectedPlanId").value = this.dataset.planId;
                        document.getElementById("selectedAmount").value = this.dataset.amount;
                        document.getElementById("selectedAbout").value = this.dataset.about;
                        document.getElementById("selectedNumSearches").value = this.dataset.numSearches;
                    });
                });
            });
        </script>

    </div>
</section>
