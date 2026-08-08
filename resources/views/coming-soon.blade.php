@extends('layouts.main')

@section('comingsoon')
    <div class="container mt-5">
        <!-- Coming Soon Section -->
        <div class="card">

            <div class="card-header text-white recentapp">
                <h5>Coming Soon: Our Premium Services</h5>
            </div>
            <div class="row text-center mb-40 mb-xs-30 py-3">
                <div class="col-lg-8 offset-lg-2">
                    <div class="section-title_home">
                        <h2>Exciting New Features Are On the Way!</h2>
                        <span class="b-line"></span>
                        <p>
                            We’re working hard to enhance your experience with Our House Help by introducing premium services
                            tailored to meet your specific needs. Stay tuned for updates as we roll out these exciting new options.
                        </p>
                        <h3>In the Meantime</h3>
                        <span class="b-line"></span>
                        <p>
                            Until the multiple-service is ready, interested clients can contact Our House Help to explore
                            this option or learn more about how we can help meet your household needs.
                        </p>
                        <a href="mailto:info@ourhousehelp.com" class="btn btn-success" style="background: #28a745;">
                            Contact Us Now
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <style>

                    .container {
                        text-align: center;
                        padding: 20px;
                    }
                    .container h1 {
                        font-size: 3rem;
                        color: #444;
                        margin-bottom: 10px;
                    }
                    .container p {
                        font-size: 1.2rem;
                        margin-bottom: 30px;
                        color: #666;
                    }
                    .container img {
                        max-width: 200px;
                        margin-bottom: 20px;
                    }
                    .container .countdown {
                        font-size: 2rem;
                        font-weight: bold;
                        margin-top: 20px;
                        color: #ff5722;
                    }
                </style>
                <div>
                    <!-- Card 1: Single Monthly Services -->
                    <div class="container">
                        {{-- <img src="{{ asset('build/assets/image/coming-soon.png') }}" alt="Coming Soon"> --}}
                        <h1>Coming Soon!</h1>
                        <p>We’re working hard to bring you something amazing. <br> Stay tuned for updates.</p>
                        <p class="countdown" id="countdown"></p>
                        <p>If you have questions, contact us at:</p>
                        <ul class="list-unstyled">
                            <li>Email: <a href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a></li>
                            <li>Phone 1: <a href="tel:+2349167696525">+234 916 7696 525</a></li>
                            <li>Phone 2: <a href="tel:+2347066663868">+234 706 6663 868</a></li>
                        </ul>
                    </div>
                    <script>
                        // Countdown Timer
                        const countdownElement = document.getElementById('countdown');
                        const targetDate = new Date('2024-12-31T00:00:00').getTime(); // Set your target date

                        const updateCountdown = () => {
                            const now = new Date().getTime();
                            const distance = targetDate - now;

                            if (distance <= 0) {
                                countdownElement.innerHTML = "We have launched!";
                                return;
                            }

                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            countdownElement.innerHTML = `${days} Days, ${hours} Hours, ${minutes} Minutes, ${seconds} Seconds`;
                        };

                        setInterval(updateCountdown, 1000);
                        updateCountdown();
                    </script>
                </div>
            </div>

        </div>
    </div>
@endsection
