<section id="special-services" class="py-5 mb-5 svc-section">

    @include('partials.modals._ModalforOne-timeServices')

    @include('partials.modals._Modalforlive-inServices')

    @include('partials.modals._ModalforCorporateInquiry')
    
    @include('partials.modals._ModalforIndustrialCleaning')

    @include('partials.modals._Modalforskilled-workersServices')

    @include('partials._registration_benefits')

    @include('partials.modals._semiLiveInModal')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,560;0,9..144,680&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="container" id="services">

        <div class="svc-header">
            <p class="svc-kicker">Our Services</p>
            <h2 class="svc-heading">Ways to get the help you need</h2>
            <p class="svc-sub">
                Every option below is staffed by people we've NIN-verified,
                background-checked, and confirmed guarantors for — choose the
                arrangement that fits your household.
            </p>
        </div>

        <div class="row justify-content-center d-flex align-items-stretch g-4">

            <!-- Live-out -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card svc-card--gold h-100">
                    <div class="svc-badge">Popular</div>
                    <div class="svc-icon">
                        <i class="fas fa-house"></i>
                    </div>
                    <h5>Live-out (Premium)</h5>
                    <p>
                        Our premium live-out service offers skilled professionals who visit your home daily but do
                        not reside on the premises. Perfect for those who need reliable, top-quality support with
                        cleaning, meal preparation, childcare, and more, without the commitment of live-in
                        assistance.
                    </p>
                    
                    <a href="{{ route('service-request.create') }}" class="svc-cta svc-cta--gold" >
                        <span>
                            Get Service
                        </span>
                    </a>
                </div>
            </div>

            <!-- Live-in -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card h-100">
                    <div class="svc-icon">
                        <i class="fas fa-house"></i>
                    </div>
                    <h5>Live-in Househelp</h5>
                    <p>
                        Full-time, 24/7 home support — cleaning, laundry,
                        nanny care and cooking. You find, call, verify and
                        negotiate directly with the staff who suit you best.
                        We provide the platform, not the middlemen.
                    </p>
                    <span class="svc-cta" data-bs-toggle="modal" data-bs-target="#liveInServices">
                        Get Service
                    </span>
                </div>
            </div>

            <!-- Semi Live-in -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card h-100">
                    
                    <div class="svc-icon">
                        <i class="fas fa-calendar-days"></i>
                    </div>
                    <h5>Semi Live-in Househelp</h5>
                    <p>
                        The consistency of live-in staff, without someone
                        permanently in your home. We verify both staff and
                        guarantors and manage them for you, while you still
                        enjoy the
                        <button type="button" class="svc-inline-link" data-bs-toggle="modal" data-bs-target="#reasonsModal">
                            10 benefits of our Live-Out service
                        </button>.
                    </p>
                    <span class="svc-cta " data-bs-toggle="modal" data-bs-target="#semiLiveInModal">
                        See Details
                    </span>
                </div>
            </div>

            <!-- Skilled Workers -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card svc-card--slate h-100">
                    <div class="svc-icon svc-icon--slate">
                        <i class="fas fa-screwdriver-wrench"></i>
                    </div>
                    <h5>Skilled Workers</h5>
                    <p>
                        Electricians, plumbers, artisans and technicians for
                        one-time or recurring needs. Whether it's a quick fix
                        or a major project, get access to experienced
                        professionals who deliver quality work.
                    </p>
                    <button type="button" class="svc-cta svc-cta--slate" data-bs-toggle="modal" data-bs-target="#skilledWorkers">
                        Get Service
                    </button>
                </div>
            </div>


            <!-- Issues -->

            <!-- Industrial Cleaning -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card h-100">
                    <div class="svc-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h5>Industrial Cleaning</h5>
                    <p>
                        Warehouses, factories and other commercial spaces,
                        handled with top-grade equipment and technique for a
                        deep, effective clean that meets industry standards.
                    </p>
                    <button type="button" class="svc-cta" data-bs-toggle="modal" data-bs-target="#ourcontact">
                        Get Service
                    </button>

                </div>
            </div>

            <!-- Corporate / Executive Staffing -->
            <div class="col-md-6 col-lg-4">
                <div class="svc-card svc-card--gold h-100">
                    <div class="svc-badge">Executive Staffing</div>
                    <div class="svc-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h5>Corporate & Executive Staffing</h5>
                    <p>
                        Staff your executives with vetted drivers, personal
                        assistants, housekeepers or chefs. We handle the
                        search and verification — you approve the hire.
                    </p>
                    <button type="button" class="svc-cta svc-cta--gold" data-bs-toggle="modal" data-bs-target="#corporateInquiry">
                        Get Service
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .svc-section {
        font-family: 'Work Sans', sans-serif;
        background: #FAF6EF;
    }

    .svc-header {
        max-width: 620px;
        margin: 0 auto 48px;
        text-align: center;
    }

    .svc-kicker {
        font-size: 13px;
        font-weight: 600;
        color: #0F6E56;
        margin-bottom: 14px;
    }

    .svc-heading {
        font-family: 'Fraunces', serif;
        font-weight: 560;
        font-size: clamp(1.7rem, 3.2vw, 2.3rem);
        color: #0B4A3B;
        margin-bottom: 16px;
    }

    .svc-sub {
        font-size: 16px;
        line-height: 1.6;
        color: #4B5A54;
    }

    .svc-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #E4DCC8;
        border-radius: 4px;
        padding: 32px 28px;
        transition: all 0.50s ease, transform 0.50s ease;
    }

    .svc-card:hover {
        border-color: #0F6E56;
        scale: 1.02;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .svc-card--gold:hover {
        border-color: #A9782E;
    }

    .svc-card--slate:hover {
        border-color: #5B6660;
    }

    .svc-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #E7F1EC;
        color: #0F6E56;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 20px;
    }

    .svc-icon--slate {
        background: #EDEFEC;
        color: #5B6660;
    }

    .svc-card--gold .svc-icon {
        background: #F3EADA;
        color: #A9782E;
    }

    .svc-badge {
        position: absolute;
        top: 22px;
        right: 22px;
        font-size: 11px;
        font-weight: 600;
        color: #A9782E;
        background: #F3EADA;
        padding: 4px 10px;
        border-radius: 3px;
    }

    .svc-card h5 {
        font-family: 'Fraunces', serif;
        font-weight: 560;
        font-size: 19px;
        color: #0B4A3B;
        margin-bottom: 10px;
    }

    .svc-card p {
        font-size: 14px;
        line-height: 1.65;
        color: #556158;
        flex-grow: 1;
        margin-bottom: 22px;
    }

    .svc-inline-link {
        background: none;
        border: none;
        padding: 0;
        font: inherit;
        font-weight: 600;
        color: #0F6E56;
        text-decoration: underline;
        cursor: pointer;
    }

    .svc-cta {
        display: inline-block;
        align-self: flex-start;
        background: #0B4A3B;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Work Sans', sans-serif;
        cursor: pointer;
        text-decoration: none;
    }

    .svc-cta:hover {
        background: #0F6E56;
        color: #fff;
    }

    .svc-cta--gold {
        background: #A9782E;
    }

    .svc-cta--gold:hover {
        background: #C79445;
    }

    .svc-cta--slate {
        background: #5B6660;
    }

    .svc-cta--slate:hover {
        background: #707D76;
    }

    .svc-modal-content {
        border-radius: 6px;
        border: none;
    }

    .svc-contact-line {
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .svc-contact-line a {
        color: #0F6E56;
        font-weight: 600;
        text-decoration: none;
    }

    .svc-corp-points {
        list-style: none;
        padding: 0;
        margin: 0 0 18px;
    }

    .svc-corp-points li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: #4B5A54;
        padding: 8px 0;
        border-top: 1px solid #EEE7D6;
    }

    .svc-corp-points li:first-child {
        border-top: none;
    }

    .svc-corp-points i {
        color: #A9782E;
        font-size: 14px;
        margin-top: 3px;
        flex-shrink: 0;
        width: 16px;
    }
</style>