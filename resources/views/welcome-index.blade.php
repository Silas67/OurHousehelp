@extends('layouts.main')
@section('welcome')

@include('partials._ohh_homestyles')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="ohh-page">

    {{-- ── ABOUT ── --}}
    <section class="ohh-about" id="about">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ohh-section-tag">Who We Are</span>
                <h2 class="ohh-section-heading">About Us</h2>
                <p class="ohh-section-body mx-auto" style="max-width:600px;">
                    At Our House Help, we provide trusted, professional domestic services tailored to your
                    needs. From housekeeping to child and elderly care, we ensure reliable support so you can
                    enjoy peace of mind and more quality time with your family.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="ohh-about-card">
                        <div class="ohh-about-icon"><i class="fas fa-house-user"></i></div>
                        <h4>About Our House Help</h4>
                        <p>Our house help services are designed to make your life easier by providing reliable, trustworthy, and well-trained staff who can handle your daily household tasks with efficiency and care.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ohh-about-card">
                        <div class="ohh-about-icon"><i class="fas fa-user-check"></i></div>
                        <h4>About Our Workers</h4>
                        <p>Our workers are skilled professionals who have undergone extensive training to ensure they provide the highest quality of service. We value their dedication and commitment to customer satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials._ourservices')

    {{-- ── GALLERY ── --}}
    <section class="ohh-gallery" id="gallery">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ohh-section-tag">Our Work</span>
                <h2 class="ohh-section-heading">Gallery</h2>
                <p class="ohh-section-body mx-auto" style="max-width:600px;">
                    Explore our collection of images that showcase our dedication to work — each piece tells a story of care and professionalism.
                </p>
            </div>
            <div class="ohh-gallery-grid">
                <div class="ohh-gallery-item tall">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/cleaning 3.jpg" alt="Cleaning Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Cleaning Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/cleaning 2.jpg" alt="Cleaning Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Cleaning Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/laundry 2.jpg" alt="Laundry Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Laundry Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/nanny 2.jpg" alt="Nanny Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Nanny Services</div>
                </div>
                <div class="ohh-gallery-item tall">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/driver 2.jpg" alt="Driving Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Driving Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/driver 3.jpg" alt="Driving Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Driving Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/errand 5.jpg" alt="Errand Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Errand Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/cook 2.jpg" alt="Cooking Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Cooking Services</div>
                </div>
                <div class="ohh-gallery-item">
                    <a href="#" class="lightbox">
                        <x-img src="build/assets/image/gallery/plumber.jpg" alt="Plumbing Services" loading="lazy" decoding="async" />
                    </a>
                    <div class="ohh-gallery-caption">Plumbing Services</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CONTACT ── --}}
    <section class="ohh-contact" id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-5">
                    <div class="ohh-contact-info">
                        <span class="ohh-section-tag">Get in Touch</span>
                        <h3>Contact Us</h3>
                        <p class="ohh-section-body mb-4">If you have any questions, feel free to reach out!</p>

                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-envelope"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Email</strong>
                                <a href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a>
                            </div>
                        </div>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-phone"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Phone</strong>
                                <a href="tel:+2349167696525">+234 916 7696 525</a><br>
                                <a href="tel:+2347066663868">+234 706 6663 868</a>
                            </div>
                        </div>
                        <div class="ohh-contact-row">
                            <div class="ohh-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="ohh-contact-detail">
                                <strong>Address</strong>
                                Block A, House 4, Plot 822, Da Akun Adawel Street, Akhnaton Estate, After Dunamis Church, Area 1 Durumi District, Abuja.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="ohh-form-card">
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
                            <button type="submit" class="ohh-btn-primary" style="border:none;width:100%;">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection