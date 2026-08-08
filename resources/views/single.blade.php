@extends('layouts.main')

@section('single')
    {{-- Service Modals --}}
    @include('partials.modals._ModalforNannyServices')
    @include('partials.modals._ModalforCookServices')
    @include('partials.modals._ModalforCleaningServices')
    @include('partials.modals._ModalforLaundryServices')
    @include('partials.modals._ModalforErrandServices')

    <div class="container mt-5">

        <!-- Profile Section -->
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Single Services</h5>
            </div>

            <!-- Service Selection -->
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <p class="text-dark" style="font-size: 18pt; line-height: 1.8;">
                            At Our House Help, we offer a range of monthly services tailored to meet your household needs:
                        </p>
                        <p class="text-dark" style="font-size: 18pt">
                            Please choose the one that best suits your needs.
                        </p>
                        <span class="b-line"></span>
                    </div>
                </div>
            </div>


            <!-- Services List -->
            <div class="card-body">
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm h-100 d-flex flex-column"
                                style="background-color: #f0f8ff; border-color: #add8e6;">
                                <div class="card-body d-flex flex-column align-items-center justify-content-between">
                                    <div class="text-center p-3 {{ $service->premium }}">
                                        <!-- Service Icon -->
                                        <i class="{{ $service->icon_class ?? 'fas fa-cogs' }} fa-3x mb-3"></i>

                                        <!-- Service Name -->
                                        <h3 class="{{ $service->premium ? 'text-danger' : 'text-success' }}">
                                            {{ ucfirst($service->service_name) }}
                                        </h3>

                                        <!-- About Service -->
                                        <p>
                                            {{ ucfirst($service->about_service) }}
                                            <br>
                                            <span>Frequency: {{ $service->hours_of_work }} hours</span>
                                            <br>
                                            <span>
                                                As low as up to: 
                                                {{ isset($service->base_cost) ? $service->base_cost + 5000 : 'Not Specific' }} per month
                                            </span>
                                            

                                        </p>

                                        <!-- Service Button -->
                                        <span class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#serviceModal-{{ $service->id }}">
                                            Get Service
                                        </span>
                                    </div>

                                    <!-- Service Modal -->
                                    <div class="modal fade" id="serviceModal-{{ $service->id }}" tabindex="-1"
                                        aria-labelledby="serviceModalLabel-{{ $service->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="serviceModalLabel-{{ $service->id }}">
                                                        {{ ucfirst($service->service_name) }} - Notice of Agreement
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body text-start">
                                                    {!! $service->dos_and_donts ?? 'No additional details available for this service.' !!}
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <a href="{{ route('services.request', ['id' => $service->id]) }}"
                                                        class="btn btn-primary">
                                                        I Agree, Continue
                                                    </a>
                                                </div>
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
    </div>
@endsection
