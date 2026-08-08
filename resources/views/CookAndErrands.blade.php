@extends('layouts.main')
@section('Cook&Errands')
    <section id="nanny-housekeeping" class="my-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Cook & Errands Services</h2>
                        <span class="b-line"></span>
                        <p>
                            Our Cook & Errands service is a premium tailored for individuals and families who need
                            assistance with
                            meal preparation and everyday tasks. Our skilled cooks prepare healthy, delicious meals, while
                            our staff handles errands like grocery shopping, deliveries, and more, ensuring your daily
                            routine runs smoothly and efficiently.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h2>A Premium Service</h2>

                    <!-- Application Form -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="text-center">Order for Cook & Errands</h4>

                            @include('partials._serviceRequest')


                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection
