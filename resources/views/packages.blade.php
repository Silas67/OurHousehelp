@extends('layouts.main')
@section('packages')
    <section id="service-packages" class="py-5 mb-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2 class="text-center mb-4">Packages</h2>
                        <span class="b-line"></span>
                        <p>
                            At Our House Help, we offer flexible cleaning packages to suit your unique needs. Whether you're
                            looking for
                            light weekly cleaning, consistent service throughout the week, or comprehensive coverage for
                            larger homes,
                            our packages are designed to provide the right level of care. Choose from the Cheryl, Jaden, or
                            Rex package
                            and enjoy a spotless home, no matter your schedule or household size.
                        </p>
                    </div>
                </div>
            </div>


            <div class="row">
                <!-- Package -->
                @foreach ($packages as $package)
                    <div class="col-md-4 mt-3">
                        <div class="card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-calendar-week fa-3x mb-3"></i>
                                <h5 class="card-title">{{ $package->name }}</h5>
                                <p class="card-text">{{ $package->description }}</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check"></i> {{ $package->point1 }}</li>
                                    <li><i class="fas fa-check"></i> {{ $package->point2 }}</li>
                                    <li><i class="fas fa-check"></i> {{ $package->point3 }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection
