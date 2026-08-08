@extends('layouts.main')
@section('Cleaning&Housekeeping')
    <section id="nanny-housekeeping" class="my-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Cleaning & Housekeeping Services</h2>
                        <span class="b-line"></span>
                        <p>
                            Our Cleaning & Housekeeping service provides professional and thorough cleaning solutions tailored to keep your home or office spotless. Our trained staff handles everything from routine cleaning tasks to more intensive housekeeping duties, ensuring a clean, organized, and welcoming environment for you and your family.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <!-- Application Form -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="text-center">Order for Cleaning & Housekeeping</h4>

                            @include('partials._serviceRequest')


                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection
