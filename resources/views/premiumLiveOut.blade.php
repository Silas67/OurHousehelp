@extends('layouts.main')

@section('premiumLiveOut')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">

            <div class="card-header text-white recentapp">
                <h5>Our Live-Out Service</h5>
            </div>
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <h2>Our Live-Out Services are of two categories.</h2>
                        <!-- <span class="b-line"></span>
                                        <p>
                                            Our House Help offers premium services designed to enhance the quality of home support. These
                                            services include more experienced house helps, specialized skills, flexible schedules, and
                                            customized service packages that cater to unique household needs. Each premium package ensures
                                            reliability, professionalism, and tailored assistance for an elevated home experience.
                                        </p>
                                        <hr> -->
                        <p class="text-dark" style="font-size: 18pt">Please choose from our </p>
                        <p class="text-dark" style="font-size: 18pt">
                            <a href="{{ route('single') }}"><span style="color: #007bff;">Single Service</span></a> or
                            <a href="{{ route('multipleSelectedServices') }}"><span style="color: #ffc107;">Multiple
                                    Services</span></a>
                        </p>

                        <span class="b-line"></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Card 1: Single Monthly Services -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 d-flex flex-column"
                            style="background-color: #f0f8ff; border-color: #add8e6;">
                            <div class="card-body d-flex flex-column align-items-center justify-content-between">
                                <i class="fas fa-check-circle fa-3x mb-3" style="color: #007bff;"></i>
                                <h3 style="color: #004080;">Single Monthly Services</h3>
                                <p>
                                    Single Services let clients choose one specific service from our available options—like Live-In Help, Live-Out Help, or Industrial Cleaning. Clients also select how many days per week our staff will work for one month. It’s a simple, flexible option for short-term or specialized needs.
                                </p>
                                <a href="{{ route('single') }}" style="background: #007bff;" class="btn btn-primary">Get
                                    Service</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Multiple Monthly Services -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 d-flex flex-column"
                            style="background-color: #fef9e7; border-color: #f7dc6f;">
                            <div class="card-body d-flex flex-column align-items-center justify-content-between">
                                <i class="fas fa-tasks fa-3x mb-3" style="color: #ffa500;"></i>
                                <h3 style="color: #b8860b;">Multiple Monthly Services</h3>
                                <p>
                                    Multiple Services allow clients to select more than one service from our list of
                                    offerings. This option is perfect for those who need a combination of assistance all at
                                    once. It provides
                                    flexibility and comprehensive support tailored to diverse household or business needs.
                                </p>
                                <div class="d-flex">

                                    <a href="{{ route('multipleSelectedServices') }}" style="background: #ffa500;"
                                        class="btn btn-primary">Get
                                        Service</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
