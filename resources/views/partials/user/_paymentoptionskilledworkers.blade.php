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

                                <!-- button for selecting this plan -->
                                {{-- @if (auth()->check()) --}}
                                    <!-- User is logged in, proceed to payment -->
                                <!-- this uses the same payment system with the househelp -->
                                    <a class="mt-2 btn btn-primary"
                                        href="{{ route('payments.accountdetail', ['plan_id' => $plan->id]) }}">
                                        Select {{ $plan->plan_name }}
                                    </a>

                                <!-- Do not delete this uses a separte payment system from the househelp -->
                                <!-- Also go to SkillController.php and active public function ourSkilledWorkerServices(Request $request) -->
                                    {{-- <a class="mt-2 btn btn-primary"
                                        href="{{ route('payments.accountdetailskill', ['plan_id' => $plan->id]) }}">
                                        Select {{ $plan->plan_name }}
                                    </a> --}}
                                    
                                {{-- @else
                                    <!-- User is NOT logged in, show the registration modal -->
                                    <button class="mt-2 btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#registerModal">
                                        Select {{ $plan->plan_name }}
                                    </button>
                                @endif --}}

                                <!-- Registration Modal -->
                                <div class="modal fade" id="registerModal" tabindex="-1"
                                    aria-labelledby="registerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="registerModalLabel">
                                                    <!-- Toggle Buttons -->
                                                    <div class="text-center mb-3">
                                                        <button class="btn btn-primary" style="width: 170px"
                                                            onclick="showRegister()">Register</button>
                                                        <button class="btn btn-secondary" style="width: 170px"
                                                            onclick="showLogin()">Login</button>
                                                    </div>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- REGISTER SECTION -->
                                                <div id="register-section">
                                                    <h5 class="display-6">REGISTER</h5>
                                                    <form method="POST" action="{{ route('register') }}">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <label for="name"
                                                                class="col-md-4 col-form-label text-md-end">Name</label>
                                                            <div class="col-md-8">
                                                                <input id="name" type="text"
                                                                    class="form-control" name="name"
                                                                    value="{{ old('name') }}" required autofocus>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="email"
                                                                class="col-md-4 col-form-label text-md-end">Email
                                                                Address</label>
                                                            <div class="col-md-8">
                                                                <input id="email" type="email"
                                                                    class="form-control" name="email"
                                                                    value="{{ old('email') }}" required>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="account_type" value="client">

                                                        <div class="form-group row">
                                                            <label for="password"
                                                                class="col-md-4 col-form-label text-md-end">Password</label>
                                                            <div class="col-md-8">
                                                                <input id="password" type="password"
                                                                    class="form-control" name="password" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="password-confirm"
                                                                class="col-md-4 col-form-label text-md-end">Confirm
                                                                Password</label>
                                                            <div class="col-md-8">
                                                                <input id="password-confirm" type="password"
                                                                    class="form-control" name="password_confirmation"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-8 offset-md-4">
                                                                <button type="submit" class="btn btn-primary"
                                                                    style="width: 250px">Register</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- LOGIN SECTION (Initially Hidden) -->
                                                <div id="login-section" style="display: none;">
                                                    <h5 class="display-6">LOGIN</h5>
                                                    <form method="POST" action="{{ route('login') }}">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <label for="email-login"
                                                                class="col-md-4 col-form-label text-md-end">Email
                                                                Address</label>
                                                            <div class="col-md-8">
                                                                <input id="email-login" type="email"
                                                                    class="form-control" name="email"
                                                                    value="{{ old('email') }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="password-login"
                                                                class="col-md-4 col-form-label text-md-end">Password</label>
                                                            <div class="col-md-8">
                                                                <input id="password-login" type="password"
                                                                    class="form-control" name="password" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-8 offset-md-4">
                                                                <button type="submit" class="btn btn-primary"
                                                                    style="width: 250px">Login</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <script>
                                                function showRegister() {
                                                    document.getElementById("register-section").style.display = "block";
                                                    document.getElementById("login-section").style.display = "none";
                                                }

                                                function showLogin() {
                                                    document.getElementById("register-section").style.display = "none";
                                                    document.getElementById("login-section").style.display = "block";
                                                }
                                            </script>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</section>
