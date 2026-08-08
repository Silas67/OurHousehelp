@extends('layouts.main')
@section('one-time')
    <section id="onetime" class="py-5 mb-5">
        <div class="container">
            <h2 class="text-center mb-4">One Time Service</h2>
            <h2 class="text-center mb-4">Select your apartment type</h2>
            <div class="row">
                @foreach ($services as $service)
                    <!-- Modal form for apartment types -->
                    <div class="modal fade" id="onetime{{ $service->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $service->apartment_size }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="contact-form" action="{{ route('apartment-orders.store') }}" method="POST">
                                    @csrf <!-- Laravel CSRF token -->
                                    <div class="modal-body">
                                        <div class="d-flex">
                                            <h4>Price Range: <span>{{ number_format($service->price_min, 2) }} - {{ number_format($service->price_max, 2) }}</span></h4>
                                        </div>
                                        <hr>
                                        <!-- User Details Form -->
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">First Name</label>
                                                    <input type="text" class="form-control" id="name" name="first_name" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- State and Address -->
                                        <div class="form-group">
                                            <label for="residence">State of Residence</label>
                                            <select class="form-control" id="residence" name="state_of_residence" required>
                                                <option value="" disabled selected>Select your state</option>
                                                <!-- Add state options dynamically if needed -->
                                                @foreach(['Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'] as $state)
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="landmark">Landmark</label>
                                            <textarea class="form-control" id="landmark" name="landmark" rows="2"></textarea>
                                        </div>

                                        <!-- Hidden Fields -->
                                        <input type="hidden" name="price" value="{{ number_format($service->price_min, 2) }} - {{ number_format($service->price_max, 2) }}" required>
                                        <input type="hidden" name="service_type" value="One Time" required>
                                        <input type="hidden" name="apartment_type" value="{{ $service->apartment_size }}" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Place Order</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- Apartment Card -->
                    <div class="col-md-3 mt-2">
                        <div class="card text-center">
                            <div class="card-body">
                                @if ($service->id == 1)
                                    <i class="fas fa-home fa-3x mb-3"></i>
                                @elseif ($service->id == 2)
                                    <i class="fas fa-house-chimney fa-3x mb-3"></i>
                                @elseif ($service->id == 3)
                                    <i class="fas fa-house-user fa-3x mb-3"></i>
                                @else
                                    <i class="fas fa-house-flag fa-3x mb-3"></i>
                                @endif

                                <h5 class="card-title">{{ $service->apartment_size }}</h5>
                                <p class="card-text">{{ $service->details }}</p>
                                <hr>
                                <h4>Price Range</h4>
                                <h5>{{ number_format($service->price_min, 2) }} - {{ number_format($service->price_max, 2) }}</h5>
                                <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#onetime{{ $service->id }}">
                                    Get Service
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- End Apartment Card -->
                @endforeach
            </div>
        </div>
    </section>
@endsection
