@extends('layouts.main')
@section('welcome')
    <!--hero section-->
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="about-section mt-5" id="about">
        <div class="container">

            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>About Us</h2>
                        <span class="b-line"></span>
                        <p>
                            At Our House Help, we provide trusted, professional domestic services tailored to your
                            needs. From housekeeping to child and elderly care, we ensure reliable support so you can
                            enjoy peace of mind and more quality time with your family.
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <!-- Card 1: About Our House Help -->
                <div class="about-card">
                    <h3>About Our House Help</h3>
                    <p>
                        Our house help services are designed to make your life easier by providing reliable,
                        trustworthy, and well-trained staff who can handle your daily household tasks with efficiency
                        and care.
                    </p>
                </div>
                <!-- Card 2: About Our Workers -->
                <div class="about-card">
                    <h3>About Our Workers</h3>
                    <p>
                        Our workers are skilled professionals who have undergone extensive training to ensure they
                        provide the highest quality of service. We value their dedication and commitment to ensuring
                        customer satisfaction.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('partials._ourservices')

    <section class="about-section" id="about">
        <div class="container">



        </div>
    </section>

    <!-- Work Gallery -->
    <section id="gallery" class="padding ptb-xs-40 mt-5">
        <div class="container">
            <div class="text-center mb-40">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Gallery</h2>
                        <p>
                            Explore our stunning collection of images that showcase our dedication to work.
                            Each piece in the gallery tells a unique story, capturing moments that resonate with
                            passion. Dive in and let the visuals speak for themselves.
                        </p>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/cleaning 3.jpg" alt="Project 1" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Cleaning Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/cleaning 2.jpg" alt="Project 2" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Cleaning Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/laundry 2.jpg" alt="Project 3" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Laundry Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/nanny 2.jpg" alt="Project 4" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Nanny Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/driver 2.jpg" alt="Project 5" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Driving Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/driver 3.jpg" alt="Project 6" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Driving Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/errand 5.jpg" alt="Project 6" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Errand Services</a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/cook 2.jpg" alt="Project 4" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Cooking Services</a></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="item-box">
                        <a href="#" class="lightbox">
                            <x-img src="build/assets/image/gallery/plumber.jpg" alt="Project 5" class="img-fluid" loading="lazy" decoding="async" />
                        </a>
                        <div class="gallery-heading text-center mt-2">
                            <span><a href="#">Plumbing Services</a></span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <!-- End Work Gallery -->

    <!-- Contact Section -->
    <section id="contact" class="padding ptb-xs-40 mt-5">
        <div class="container">
            <div class="row text-center mb-40 mb-xs-30">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-title_home">
                        <h2>Contact Us</h2>
                        <span class="b-line"></span>
                        <p>If you have any questions, feel free to reach out!</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="contact-info">
                        <h3>Get in Touch</h3>
                        <p><strong>Email:</strong> <a href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a></p>
                        <p><strong>Phone:</strong> <a href="tel:+2349167696525">+234 916 7696 525</a></p>
                        <p><strong>Phone:</strong> <a href="tel:+2347066663868">+234 706 6663 868</a></p>
                        <p><strong>Address:</strong> Block A, House 4, Plot 822, Da Akun Adawel Street, Akhnaton Estate,
                            After Dunamis Church, Area 1 Durumi District, Abuja.</p>
                    </div>
                </div>

                <div class="col-md-6">


                    <form id="contact-form" action="{{ route('contact-message.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="name">First Name</label>
                                    <input type="text" class="form-control" id="name" name="first_name"
                                        value="{{ Auth::user()->name ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="{{ Auth::user()->last_name ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ Auth::user()->email ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ Auth::user()->phone ?? '' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>



                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Section -->
@endsection
