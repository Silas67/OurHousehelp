@extends('layouts.usermain')

@section('premium')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">

            <div class="card-header text-white recentapp">
                <h5>Premium Services</h5>
            </div>
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <h2>Premium Services at Our House Help</h2>
                        <span class="b-line"></span>
                        <p>
                            Our House Help offers premium services designed to enhance the quality of home support. These
                            services include more experienced house helps, specialized skills, flexible schedules, and
                            customized service packages that cater to unique household needs. Each premium package ensures
                            reliability, professionalism, and tailored assistance for an elevated home experience.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row d-flex align-items-stretch">
                <!-- Single Monthly Services Card -->
                {{-- <div class="col-md-6 mt-2">
                    <div class="card text-center shadow-sm h-100" style="border: 2px solid #007bff; background-color: #e9f7ff;">
                        <div class="card-body">
                            <i class="fas fa-check-circle fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title text-primary">Single Monthly Services</h5>
                            <p class="card-text text-dark">
                                Single Services let clients choose one specific service from our available options—like Live-In Help, Live-Out Help, or Industrial Cleaning. Clients also select how many days per week our staff will work for one month. It’s a simple, flexible option for short-term or specialized needs.
                            </p>
                            <a href="{{ route('single') }}" class="btn btn-primary" style="background: #007bff">Get Service</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Multiple Monthly Services Card -->
                {{-- <div class="col-md-6 mt-2">
                    <div class="card text-center shadow-sm h-100" style="border: 2px solid #28a745; background-color: #edf9f0;">
                        <div class="card-body">
                            <i class="fas fa-tasks fa-3x mb-3 text-success"></i>
                            <h5 class="card-title text-success">Multiple Monthly Services</h5>
                            <p class="card-text text-dark">
                                Our Multiple Monthly Services package offers a comprehensive solution, combining various services
                                such as cleaning, laundry, and errands. Ideal for those who require ongoing support in multiple
                                areas, this flexible package ensures that all your household needs are met efficiently and professionally.
                            </p>
                            <a href="{{ route('multiple') }}" class="btn btn-success" style="background: #28a745">Get Service</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Live-out -->
                <div class="mt-2">
                    @include('partials.live_out_service')
                </div>
            </div>



        </div>
    </div>
@endsection
