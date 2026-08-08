@extends('layouts.main')
@section('Nanny&Housekeeping')
    <section id="nanny-housekeeping" class="my-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Nanny & Housekeeping Services</h2>
                        <span class="b-line"></span>
                        <p>
                            Our premium Nanny & Housekeeping service is designed to meet the needs of families who require
                            professional help with child care and elderly care. Our experienced staff ensures that your
                            loved ones receive top-notch attention and care.
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
                            <h4 class="text-center">Order for Nanny & Housekeeping</h4>

                            @include('partials._serviceRequest')


                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection
