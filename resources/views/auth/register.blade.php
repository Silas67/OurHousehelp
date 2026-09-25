@extends('layouts.main')

@section('signup')
    <section id="register" class="py-5 mb-5">
        <div class="container py-5">
            <div class="row justify-content-center">

                {{-- ── REGISTRATION FORM ── --}}
                <div class="col-md-7">
                    <div class="card shadow-lg border-0 rounded-4">

                        <div class="card-header text-center text-white py-3 rounded-top-4" style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
                            <h5 class="mb-0">Create a New Account</h5>
                        </div>

                        <div class="card-body px-4 py-4 ">

                            {{-- Account Type Toggle --}}
                            <div class="mb-4" x-data="{ accountType: 'client' }">
                                <label class="form-label fw-semibold d-block text-center mb-3">Register:</label>
                                
                                <div class="justify-content-center d-flex">
                                    <!-- Main Toggle Track container -->
                                    <div class="position-relative d-flex bg-light rounded-pill p-1 border flex-fill" style="width: 280px; height: 50px;">
                                        
                                        <!-- Sliding Background Element -->
                                        <div class="position-absolute top-0 bottom-0 start-0 m-1 rounded-pill bg-success shadow-sm"
                                            style="transition: transform 0.3s ease-in-out, width 0.3s ease-in-out; border-color: #0F6E56 !important; background-color: #0F6E56 !important;"
                                            :style="accountType === 'client' ? 'width: 134px; transform: translateX(0);' : 'width: 134px; transform: translateX(136px);'">
                                        </div>

                                        <!-- Client Option Button -->
                                        <button type="button" 
                                                id="card-client"
                                                onclick="selectType('client')"
                                                class="btn position-relative z-1 flex-fill rounded-pill d-flex align-items-center justify-content-center gap-2 border-0 fw-semibold"
                                                :class="accountType === 'client' ? 'text-white' : 'text-muted'"
                                                style="font-size: 14px; transition: color 0.3s;">
                                            <i class="fas fa-user-tie" :class="accountType === 'client' ? 'text-white' : 'text-muted'"></i>
                                            As a Client
                                        </button>

                                        <!-- Staff Option Button -->
                                        <button type="button" 
                                                id="card-applicant"
                                                onclick="selectType('applicant')"
                                                class="btn position-relative z-1 flex-fill rounded-pill d-flex align-items-center justify-content-center gap-2 border-0 fw-semibold"
                                                :class="accountType === 'applicant' ? 'text-white' : 'text-muted'"
                                                style="font-size: 14px; transition: color 0.3s;">
                                            <i class="fas fa-broom" :class="accountType === 'applicant' ? 'text-white' : 'text-muted'"></i>
                                            As a Staff
                                        </button>
                                    </div>
                                </div>

                                <!-- Hidden native input bound natively to form submissions -->
                                <input type="hidden" name="account_type_selected" id="account_type_selected" :value="accountType">
                            </div>

                            {{-- Client Form --}}
                            <div id="client-form">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <input type="hidden" name="account_type" value="client">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus
                                            placeholder="Enter your full name">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="Enter your email address">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" required
                                            placeholder="e.g. 08012345678" maxlength="11"
                                            oninput="this.value=this.value.replace(/\D/g,'')">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" name="password" id="client-password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required autocomplete="new-password" placeholder="Min. 8 characters">
                                            <i class="fas fa-eye position-absolute"
                                                style="right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#adb5bd;"
                                                onclick="togglePwd('client-password')"></i>
                                        </div>
                                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation" id="client-password-confirm"
                                                class="form-control" required autocomplete="new-password"
                                                placeholder="Repeat your password">
                                            <i class="fas fa-eye position-absolute"
                                                style="right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#adb5bd;"
                                                onclick="togglePwd('client-password-confirm')"></i>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn w-100 py-2 fw-semibold"
                                        style="background: linear-gradient(135deg, #0F6E56, #1D9E75); color:white; border-radius:8px;">
                                        <i class="fas fa-user-plus me-2"></i>Create Client Account
                                    </button>
                                </form>
                            </div>

                            {{-- Staff Redirect --}}
                            <div id="applicant-form" style="display:none;">
                                <div class="text-center py-3">
                                    <div class="mb-3" style="font-size:48px;">🏠</div>
                                    <h6 class="fw-bold mb-2">Ready to join our team?</h6>
                                    <p class="text-muted small mb-4">
                                        Staff registration requires additional details including your professional background, guarantors, and identity verification. Click below to start your full application.
                                    </p>
                                    <a href="{{ route('househelpapply') }}"
                                        class="btn w-100 py-2 fw-semibold"
                                        style="background: linear-gradient(135deg, #0F6E56, #1D9E75); color:white; border-radius:8px;">
                                        <i class="fas fa-broom me-2"></i>Start Staff Application
                                    </a>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted fw-bold">
                                    Already registered? <a href="{{ route('login') }}" style="color:#0F6E56; font-size: 14px;">Login here</a>
                                </small>
                            </div>

                        </div>
                    </div>

                    {{-- ── WHY REGISTER — Collapsible ──
                         Client-facing only: it describes staff vetting and the client
                         qualification fee, which is confusing for someone registering
                         AS staff. Hidden when the Staff option is selected. --}}
                    <div class="mt-3" id="why-register-block">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center"
                                style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#whyRegister">
                                <span class="fw-semibold" style="color:#0F6E56;">
                                    <i class="fas fa-info-circle me-2"></i>Why Register With Us First?
                                </span>
                                <i class="fas fa-chevron-down text-muted"></i>
                            </div>
                            <div class="collapse" id="whyRegister">
                                <div class="card-body px-4 py-3">
                                    <p class="text-muted text-center mb-3" style="font-size: 1rem;">
                                        We make your <strong>safety</strong>, <strong>convenience</strong>, and <strong>quality</strong> our priority.
                                    </p>

                                    <div class="mb-3">
                                        <h6 style="color:#0F6E56;"><i class="fas fa-shield-alt me-2"></i><strong>1. Security & Trust First</strong></h6>
                                        <p class="small text-muted mb-1">Before any staff is assigned to you, we:</p>
                                        <ul class="small text-muted">
                                            <li>Verify the staff thoroughly</li>
                                            <li>Verify their guarantors</li>
                                            <li>Confirm their identity and background</li>
                                            <li>Ensure they meet our company standards</li>
                                        </ul>
                                        <p class="small"><strong>Your safety is not optional — it is our foundation.</strong></p>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <h6 style="color:#0F6E56;"><i class="fas fa-network-wired me-2"></i><strong>2. Access to Our Verified Network</strong></h6>
                                        <p class="small text-muted mb-1">After registration and a <strong>₦10,000 qualification fee</strong>, you gain:</p>
                                        <ul class="small text-muted">
                                            <li>Access to our growing database of trusted artisans and staff</li>
                                            <li>Pre-screened and tested personnel</li>
                                            <li>Organized service selection tailored to your needs</li>
                                            <li>Professional support and structured engagement</li>
                                        </ul>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <h6 style="color:#0F6E56;"><i class="fas fa-tasks me-2"></i><strong>3. Accountability & Professional Structure</strong></h6>
                                        <ul class="small text-muted">
                                            <li>A secure client profile</li>
                                            <li>Proper documentation</li>
                                            <li>Clear service tracking</li>
                                            <li>Easy communication with our office</li>
                                            <li>A system that protects both you and the staff</li>
                                        </ul>
                                    </div>

                                    <div class="text-center p-3 rounded mt-2" style="background:#f0fff4;">
                                        <p class="mb-1 small"><strong>Registering with us means:</strong></p>
                                        <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
                                            @foreach(['Safety', 'Verification', 'Professionalism', 'Trusted Access', 'Peace of Mind'] as $point)
                                                <span class="badge" style="background:#0F6E56; font-size:12px;">{{ $point }}</span>
                                            @endforeach
                                        </div>
                                        <p class="mt-3 mb-0 small" style="color:#0F6E56;">
                                            <em><strong>Register first. Choose confidently. Hire safely.</strong></em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <script>
        function selectType(type) {
            const clientCard = document.getElementById('card-client');
            const applicantCard = document.getElementById('card-applicant');
            const clientForm = document.getElementById('client-form');
            const applicantForm = document.getElementById('applicant-form');
            const whyBlock = document.getElementById('why-register-block');

            if (type === 'client') {
                clientCard.style.background = '#f0fff4';
                clientCard.style.borderColor = '#0F6E56';
                clientCard.querySelector('i').style.color = '#0F6E56';
                applicantCard.style.background = 'white';
                applicantCard.style.borderColor = '#dee2e6';
                applicantCard.querySelector('i').className = 'fas fa-broom fa-2x mb-2 text-muted';
                clientForm.style.display = 'block';
                applicantForm.style.display = 'none';
                if (whyBlock) whyBlock.style.display = 'block';
            } else {
                applicantCard.style.background = '#f0fff4';
                applicantCard.style.borderColor = '#0F6E56';
                applicantCard.querySelector('i').style.color = '#0F6E56';
                applicantCard.querySelector('i').className = 'fas fa-broom fa-2x mb-2';
                clientCard.style.background = 'white';
                clientCard.style.borderColor = '#dee2e6';
                clientCard.querySelector('i').style.color = '#adb5bd';
                clientForm.style.display = 'none';
                applicantForm.style.display = 'block';
                // Client-oriented copy; irrelevant to a staff applicant.
                if (whyBlock) whyBlock.style.display = 'none';
            }
        }

        function togglePwd(id) {
            const f = document.getElementById(id);
            const i = f.nextElementSibling;
            f.type = f.type === 'password' ? 'text' : 'password';
            i.classList.toggle('fa-eye');
            i.classList.toggle('fa-eye-slash');
        }

        // If there are errors, keep client form visible
        @if($errors->any())
            selectType('client');
        @endif
    </script>
@endsection