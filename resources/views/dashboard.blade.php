@extends('layouts.usermain')
@section('userdashboard')
    <div class="container mt-5">
        <!-- Welcome Section -->
        <div class="text-center mb-5">
            <h1>Welcome, {{ Auth::user()->name }}</h1>
            <p class="lead">We're glad to have you. Here's a summary of what you can do.</p>
            
            <!-- Contact Us Button -->
            <p type="button" data-bs-toggle="modal" data-bs-target="#contactModal">
                <i class="fas fa-headset me-2"></i> Contact Us
            </p>
        </div>

        {{-- In-progress bookings. Sits above the "book a service" CTA so a
             client who navigated away from their shortlist lands back on it
             rather than starting a second booking. --}}
        <x-resume-booking />

        <!-- Book a Service CTA -->
        <div class="card mb-4 border-primary">
            <div class="card-body text-center py-4">
                <h4 class="fw-bold mb-2">Need a verified house help?</h4>
                <p class="text-muted mb-3">Book a service and get matched with trusted, verified staff in your area.</p>
                <a href="{{ route('booking.service-selection') }}" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-clipboard-list me-2"></i>Book a Service Now
                </a>
            </div>
        </div>

        <!-- Recent Bookings Section -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5>Dashboard</h5>
            </div>
        </div>

        <!-- Dashboard Quick Links -->
        <div class="row">
            <!-- Live-Out House Helps -->
            <div class="thiscard col-md-4">
                <a href="{{ route('service-request.create') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-crown fa-3x mb-3" style="color: #3498db;"></i>
                        <h5 class="card-title">Live-Out House Helps</h5>
                        <p class="card-text flex-grow-1">Professional house helps who work on a schedule and return home
                            daily.</p>
                        <span class="badge bg-primary mt-auto" hidden>Book Now</span>
                    </div>
                </a>
            </div>

            <!-- Live-in House Helps -->
            <div class="thiscard col-md-4">
                <a href="{{ route('househelps') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-home fa-3x mb-3" style="color: #2ecc71;"></i>
                        <h5 class="card-title">Live-in House Helps</h5>
                        <p class="card-text flex-grow-1">Full-time residential staff who stay in your home for comprehensive
                            support.</p>
                        <span class="badge bg-success mt-auto" hidden>Browse</span>
                    </div>
                </a>
            </div>

            <!-- Semi Live-in House Helps -->
            <div class="thiscard col-md-4" data-bs-toggle="modal" data-bs-target="#semiLiveInModal">
                <div class="card text-center shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #ff6b35;"></i>
                        <h5 class="card-title">Semi Live-in House Helps</h5>
                        <p class="card-text flex-grow-1 text-dark">
                            Perfect balance: Staff work weekdays, go home weekends. Privacy + flexibility.
                        </p>
                        <button class="btn btn-sm mt-auto" style="background: #ff6b35; color: white; border-color: #ff6b35;"
                            data-bs-toggle="modal" data-bs-target="#semiLiveInModal" hidden>
                            <i class="fas fa-info-circle me-1"></i> Learn More
                        </button>
                    </div>
                </div>
            </div>

            <!-- Skilled Workers -->
            <div class="thiscard col-md-4">
                <a href="{{ route('ourSkilledWorkerServices') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-tools fa-3x mb-3" style="color: #9b59b6;"></i>
                        <h5 class="card-title">Skilled Workers</h5>
                        <p class="card-text flex-grow-1">Professional artisans and technicians for specialized home
                            services.</p>
                        <span class="badge bg-purple mt-auto" hidden>Find Experts</span>
                    </div>
                </a>
            </div>

            <!-- Payment History -->
            <div class="thiscard col-md-4">
                <a href="{{ route('paymenthistory') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-history fa-3x mb-3" style="color: #f39c12;"></i>
                        <h5 class="card-title">Payment History</h5>
                        <p class="card-text flex-grow-1">Track all your past payments, invoices, and transactions.</p>
                        <span class="badge bg-warning text-dark mt-auto" hidden>View History</span>
                    </div>
                </a>
            </div>

            <!-- Profile -->
            <div class="thiscard col-md-4">
                <a href="{{ route('clientprofile') }}"
                    class="card text-center text-decoration-none text-dark shadow-sm mb-4 h-100 hover-effect">
                    <div class="card-body d-flex flex-column">
                        <i class="fas fa-user-cog fa-3x mb-3" style="color: #e74c3c;"></i>
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text flex-grow-1">Manage your personal information, preferences, and account
                            settings.</p>
                        <span class="badge bg-danger mt-auto" hidden>Manage</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Add CSS for hover effects -->
        <style>
            .thiscard {
                min-height: 220px;
                margin-bottom: 20px;
                cursor: pointer;
            }
        </style>

    </div>

    <!-- Semi Live-in Modal -->
    @include('partials.modals._semiLiveInModal')

    <!-- Contact Us Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="fas fa-headset me-2"></i>Contact Our Support Team
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Support Hours -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">24/7 Support Available</h5>
                                <p class="mb-0">Our support team is available round the clock to assist you with any queries or issues.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone Numbers -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="icon-wrapper bg-success bg-opacity-10 d-inline-flex p-3 rounded-circle">
                                            <i class="fas fa-phone-alt fa-2x text-success"></i>
                                        </div>
                                    </div>
                                    <h4 class="card-title mb-3">Call Us</h4>
                                    
                                    <div class="mb-4">
                                        <h5 class="text-success mb-2">
                                            <i class="fas fa-star me-2"></i>Primary Number
                                        </h5>
                                        <a href="tel:+2349167696525" class="text-decoration-none">
                                            <h3 class="fw-bold text-dark">+234 916 7696 525</h3>
                                        </a>
                                        <div class="mt-3">
                                            <a href="https://wa.me/2349167696525?text=Hello%2C%20I%20need%20assistance%20with..."
                                                target="_blank" class="btn btn-success btn-lg w-100">
                                                <i class="fab fa-whatsapp me-2"></i>Chat on WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-top">
                                        <h5 class="text-primary mb-2">
                                            <i class="fas fa-phone me-2"></i>Secondary Number
                                        </h5>
                                        <a href="tel:+2347066663868" class="text-decoration-none">
                                            <h4 class="fw-bold text-dark">+234 706 6663 868</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email & Support -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="icon-wrapper bg-info bg-opacity-10 d-inline-flex p-3 rounded-circle">
                                            <i class="fas fa-envelope fa-2x text-info"></i>
                                        </div>
                                    </div>
                                    <h4 class="card-title mb-3">Email & Support</h4>
                                    
                                    <div class="mb-4">
                                        <h5 class="text-info mb-2">
                                            <i class="fas fa-envelope-open-text me-2"></i>Email Support
                                        </h5>
                                        <a href="mailto:info@ourhousehelp.com" class="text-decoration-none">
                                            <h4 class="fw-bold text-dark">info@ourhousehelp.com</h4>
                                        </a>
                                        <div class="mt-3">
                                            <a href="mailto:info@ourhousehelp.com" class="btn btn-info btn-lg w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Send Email
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-3 border-top">
                                        <h5 class="text-dark mb-2">
                                            <i class="fas fa-question-circle me-2"></i>Response Time
                                        </h5>
                                        <div class="alert alert-light">
                                            <p class="mb-0">
                                                <i class="fas fa-bolt text-warning me-1"></i>
                                                <strong>Phone:</strong> Immediate response
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-clock text-warning me-1"></i>
                                                <strong>Email:</strong> Within 2 hours
                                            </p>
                                            <p class="mb-0">
                                                <i class="fab fa-whatsapp text-warning me-1"></i>
                                                <strong>WhatsApp:</strong> Within 15 minutes
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle text-success me-2"></i>
                                What can we help you with?
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Service Booking Assistance</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Payment Issues</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Staff Scheduling</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Account Management</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Complaints & Feedback</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>General Inquiries</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="tel:+2349167696525" class="btn btn-success">
                        <i class="fas fa-phone me-2"></i>Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .thiscard {
            min-height: 220px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        
        /* Contact modal styles */
        .icon-wrapper {
            transition: transform 0.3s ease;
        }
        
        .card:hover .icon-wrapper {
            transform: scale(1.1);
        }
        
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: none;
        }
        
        .btn-close-white {
            filter: brightness(0) invert(1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to modal when opened
            $('#contactModal').on('shown.bs.modal', function () {
                $('.icon-wrapper').addClass('animate__animated animate__pulse');
            });
        });
    </script>
@endsection