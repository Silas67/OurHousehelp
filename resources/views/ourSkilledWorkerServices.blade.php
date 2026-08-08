@extends('layouts.main')

@section('ourSkilledWorkerServices')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">

            <div class="card-header text-white recentapp">
                <h5>Our Skilled Services</h5>
            </div>
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <h2>Connect with Skilled Professionals</h2>
                        <span class="b-line"></span>
                        <p>
                            Our platform connects you with skilled professionals—including electricians, plumbers,
                            artisans, and more—available for one-time or ongoing tasks. Clients are solely responsible
                            for vetting and verifying the qualifications of the professionals they choose to engage.
                        </p>
                        <p>
                            All arrangements, including scope of work, payment, and timelines, are independently
                            handled between the client and the service provider, offering complete flexibility.
                        </p>
                        <p>
                            This platform simply facilitates connection. We do not mediate or oversee service delivery.
                        </p>
                    </div>
                </div>
            </div>

            @include('partials._searchForHousehelpsorskills')

        </div>
    </div>
@endsection
