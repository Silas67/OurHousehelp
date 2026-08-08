@extends('layouts.main')
@section('Errand&Housekeeping')
    <section id="nanny-housekeeping" class="my-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Errand & Housekeeping Services</h2>
                        <span class="b-line"></span>
                        <p>
                            Our Laundry service ensures that your clothes are always fresh, clean, and well cared for. From washing and drying to folding and ironing, we handle all your laundry needs with professional attention to detail. Whether it's daily wear or special garments, our service helps you maintain crisp, clean clothing without the hassle, so you can focus on what matters most.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <!-- Application Form -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="text-center">Order for Errand & Housekeeping</h4>

                            @include('partials._serviceRequest')


                        </div>
                    </div>



                </div>
            </div>
        </div>
    </section>
@endsection
