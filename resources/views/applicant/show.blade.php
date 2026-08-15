@extends('layouts.main')
@section('showapplicant')
    <div class="container py-5">
        <div class="row">
            <!-- Applicant Photo -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <img src="{{ $househelp->profile_photo_path ? asset('build/assets/image/profile_photo/' . $househelp->profile_photo_path) : asset('build/assets/image/default-profile.png') }}"
                        alt="{{ $househelp->name }}'s Photo" class="img-fluid rounded mb-3"
                        style="width: 100%; object-fit: cover;" loading="lazy" decoding="async">
                    <h5 class="text-center">{{ $househelp->name }} {{ $househelp->last_name }}</h5>
                </div>
            </div>

            <!-- Applicant Details -->
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h3 class="card-title">Applicant Details</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Full Name:</strong> {{ $househelp->name }}
                            {{ $househelp->last_name }}</li>
                        <li class="list-group-item"><strong>Gender:</strong> {{ ucfirst($househelp->gender) }}</li>
                        <li class="list-group-item"><strong>Years of Experience:</strong>
                            {{ $househelp->years_of_experience }} years</li>
                        @if ($househelp->activeRequest)
                            <li class="list-group-item"><strong>Date of Birth:</strong> {{ $househelp->dob }}</li>
                            <li class="list-group-item"><strong>State of Origin:</strong> {{ $househelp->state_of_origin }}
                            </li>
                            <li class="list-group-item"><strong>State of Residence:</strong>
                                {{ $househelp->state_of_residence }}</li>
                            <li class="list-group-item"><strong>Phone Number:</strong> {{ $househelp->phone }}</li>
                            <li class="list-group-item"><strong>Email:</strong> <a
                                    href="mailto:{{ $househelp->email }}">{{ $househelp->email }}</a></li>
                            <li class="list-group-item"><strong>Residential Address:</strong> {{ $househelp->address }}
                            </li>
                            <li class="list-group-item"><strong>Landmark:</strong> {{ $househelp->landmark }}</li>
                        @else
                            <li class="list-group-item text-danger"><strong>🔒 Click the See Full Deatail button to view
                                    Contact.</strong></li>
                        @endif
                        <li class="list-group-item"><strong>Availability:</strong>
                            {{ $househelp->availability ? 'Available' : 'Not Available' }}
                        </li>
                        <li class="list-group-item"><strong>Rating:</strong>
                            @for ($i = 0; $i < $househelp->rating; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for ($i = 0; $i < 5 - $househelp->rating; $i++)
                                <i class="far fa-star text-warning"></i>
                            @endfor
                        </li>
                    </ul>

                    <!-- Display the click count for Request button -->
                    @auth
                        <div class="mt-3 text-center">
                            <p class="text-danger fw-bold">
                                You have requested service <strong>{{ $clickCount }}</strong> times.
                            </p>
                        </div>
                    @endauth


                    <div class="text-center">
                        @if (!$househelp->activeRequest)
                            @auth
                                @if ($househelp->isSubscriptionExpired || $remainingSearches <= 0)
                                    <span>
                                        <strong>For LiveIn Services, Please Subscribe to View More Detail</strong><br>
                                        <small class="mb-5">
                                            @if ($househelp->isSubscriptionExpired)
                                                Your subscription has expired.
                                            @else
                                                You have used all your allowed views.
                                            @endif
                                            <a href="{{ route('payments.paymentoption') }}" class="btn btn-sm btn-primary">
                                                <i class="fa-solid fa-lock"></i> Subscribe Now
                                            </a>
                                        </small>
                                    </span>
                                @else
                                    <!-- Show form only if subscription is valid and searches are available -->
                                    <form action="{{ route('liveinservice.request') }}" method="POST" id="requestServiceForm">
                                        @csrf
                                        <input type="hidden" name="househelp_id" value="{{ $househelp->id }}">

                                        <button type="submit" onclick="return countClickAndConfirm({{ $househelp->id }});"
                                            class="btn btn-primary">
                                            See Full Detail ({{ $remainingSearches }} remaining)
                                        </button>

                                    </form>
                                @endif
                            @else
                                <!-- Non-authenticated users: trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#guestInfoModal">
                                    See Full Detail
                                </button>
                            @endauth
                        @else
                            <button type="button" class="btn btn-secondary" disabled>View Request Successful</button>
                        @endif
                    </div>


                    <!-- Modal for Guest Information -->
                    <div class="modal fade" id="guestInfoModal" tabindex="-1" aria-labelledby="guestInfoModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('saveUserData') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="househelp_id" value="{{ $househelp->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="guestInfoModalLabel">Provide Your Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <button onclick="history.back()" class="floating-back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </button>

        {{-- <a class="floating-back-btn btn" href=""><i class="fas fa-arrow-left"></i> Back</a> --}}

        <style>
            .floating-back-btn {
                position: fixed;
                bottom: 20px;
                left: 20px;
                background-color: #285722;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 50px;
                font-size: 16px;
                cursor: pointer;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: background 0.3s ease;
            }

            .floating-back-btn:hover {
                background-color: #12490b;
            }
        </style>

    </div>

    <script>
        // Function to log the click via AJAX and show confirmation dialog.
        function countClickAndConfirm(househelpId) {
            if (confirm('Are you sure you want to request this service?')) {
                fetch("{{ route('request.click') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            househelp_id: househelpId,
                            payment_id: {{ $payment->id ?? 'null' }}
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Click logged:', data);
                        // Optionally, update the click counter on the page (if you want to update without a full page reload).
                        // For example, you could increment an element's innerText here.
                    })
                    .catch(error => {
                        console.error('Error logging click:', error);
                    });
                return true; // allow form submission
            }
            return false; // cancel form submission if not confirmed
        }
    </script>
@endsection
