@extends('layouts.main')

@section('ourLiveInServices')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">

            <div class="card-header text-white recentapp">
                <h5>Our Live-In Services</h5>
            </div>
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <h2>Tailored Live-In Support for Your Home</h2>
                        <span class="b-line"></span>
                        <p>
                            Our House Help offers live-in services designed to meet your household needs. Clients are
                            responsible for the complete vetting process, including verification with two guarantors,
                            ensuring flexibility and personalized support. Applicants and clients operate independently,
                            allowing for a customized arrangement.
                        </p>
                        <p>
                            Clients also have the right to train their staff to better suit their preferences and
                            requirements, ensuring an enhanced service experience.
                        </p>
                    </div>
                </div>
            </div>

            @include('partials._searchForHousehelpsorskills')

        </div>
    </div>
@endsection
