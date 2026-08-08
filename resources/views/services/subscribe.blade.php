@extends('layouts.main')
@section('services')
    <section id="nanny-housekeeping" class="my-5">


        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">


                        <h2>{{ $service->service_name }}</h2>
                        <span class="b-line"></span>
                        <p>
                            {{ $service->about_service }}
                        </p>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center">
                <div class="col-md-6">
                    <!-- Application Form -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="text-center">Order for {{ $service->service_name }}</h4>

                            @include('partials._serviceRequest')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

