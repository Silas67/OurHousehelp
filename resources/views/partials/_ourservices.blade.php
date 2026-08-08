<section id="special-services" class="py-5 mb-5">
    @include('partials.modals._ModalforOne-timeServices')
    @include('partials.modals._Modalforlive-inServices')
    @include('partials.modals._Modalforskilled-workersServices')
    @include('partials._registration_benefits')
    @include('partials.modals._semiLiveInModal')

    <div class="container" id="services">
        <h2>These are our Types of Services</h2>
        <hr>

        <p class="text-dark" style="font-size: 18pt">
            <span style="color: #ffc107; cursor: pointer;" data-bs-toggle="modal"
                data-bs-target="#OneTimeServices">Live-Out</span>,
            <span style="color: #054385; cursor: pointer;" data-bs-toggle="modal"
                data-bs-target="#liveInServices">Live-In</span>,
            <span style="color: #ff6b35; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#semiLiveInModal">Semi
                Live-In</span>,
            <span style="color: #6c757d; cursor: pointer;" data-bs-toggle="modal"
                data-bs-target="#skilledWorkers">Skilled Workers</span>, and
            <span style="color: #28a745; cursor: pointer;" data-bs-toggle="modal"
                data-bs-target="#ourcontact">Industrial
                Cleaning</span>
        </p>

        <div class="row justify-content-center d-flex align-items-stretch">
            <!-- Live-out -->
            <div class="col-md-4 mt-2">
                @include('partials.live_out_service')
            </div>

            <!-- Live-in -->
            <div class="col-md-4 mt-2">
                <div class="card text-center shadow-sm h-100"
                    style="border: 2px solid #054385; background-color: #e9f7ff;">
                    <div class="card-body">
                        <i class="fas fa-home fa-3x mb-3" style="color: #054385;"></i>
                        <h5 class="card-title" style="color: #054385;">Live-in Househelp</h5>
                        <p class="card-text text-dark">
                            Our live-in househelp service offers full-time, 24/7 home support — from cleaning and
                            laundry to nanny care and cooking. You're in full control: find, call, verify, and negotiate
                            directly with the staff that best suits your needs. We simply provide the platform — no
                            middlemen, no interference.
                        </p>
                        <span class="btn btn-primary" style="background: #054385" data-bs-toggle="modal"
                            data-bs-target="#liveInServices">Get Service</span>
                    </div>
                </div>
            </div>

            <!-- Semi Live-in -->
            <div class="col-md-4 mt-2">
                <div class="card text-center shadow-sm h-100"
                    style="border: 2px solid #ff6b35; background-color: #fff5f0;">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #ff6b35;"></i>
                        <h5 class="card-title" style="color: #ff6b35;">Semi Live-in Househelp</h5>
                        <p class="card-text text-dark">
                            Designed for clients who want the consistency and dedication of
                            a live-in staff, without the challenges of having someone to permanently stay in their
                            homes.
                            Our House Help Verifies both the staff and Gurantors, manage them and yet, clients
                            enjoys the <b style="cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#reasonsModal"> 10 benefits of
                                our Live-Out service</b>.
                        </p>
                        <span class="btn btn-warning" style="background: #ff6b35; border-color: #ff6b35;"
                            data-bs-toggle="modal" data-bs-target="#semiLiveInModal">
                            See Details
                        </span>
                    </div>
                </div>
            </div>

            <!-- Skilled Workers -->
            <div class="col-md-4 mt-2">
                <div class="card text-center shadow-sm h-100"
                    style="border: 2px solid #6c757d; background-color: #f8f9fa;">
                    <div class="card-body">
                        <i class="fas fa-tools fa-3x mb-3 text-secondary"></i>
                        <h5 class="card-title text-secondary">Skilled Workers</h5>
                        <p class="card-text text-dark">
                            From electricians and plumbers to artisans and technicians, hire skilled workers for
                            one-time or recurring needs. Our platform connects you with professionals who are
                            experienced in various trades. Whether it's a quick fix or a major project, we ensure you
                            get access to experts who deliver quality work.
                        </p>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#skilledWorkers">Get Service</button>
                    </div>
                </div>
            </div>

            <!-- Industrial Cleaning -->
            <div class="col-md-4 mt-2">
                <div class="card text-center shadow-sm h-100"
                    style="border: 2px solid #28a745; background-color: #edf9f0;">
                    <div class="card-body">
                        <i class="fas fa-industry fa-3x mb-3 text-success"></i>
                        <h5 class="card-title text-success">Industrial Cleaning</h5>
                        <p class="card-text text-dark">
                            Our industrial cleaning service is designed for large spaces and specialized needs,
                            including warehouses, factories, and other commercial environments. Our team uses top-grade
                            equipment and techniques to ensure a deep, effective clean that meets industry standards.
                        </p>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#ourcontact">Get Service</button>

                        <div class="modal fade" id="ourcontact" tabindex="-1" role="dialog"
                            aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="mySmallModalLabel">Contact us on</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="contact-info">
                                            <p><strong>Email:</strong> <a
                                                    href="mailto:info@ourhousehelp.com">info@ourhousehelp.com</a></p>
                                            <p><strong>Phone:</strong> <br><a style="font-size: 17pt"
                                                    href="tel:+2349167696525">+234 916 7696 525</a></p>
                                            <p><strong>Phone:</strong> <br><a style="font-size: 17pt"
                                                    href="tel:+2347066663868">+234 706 6663 868</a></p>
                                            <p><strong>Address:</strong> Block A, House 4, Plot 822, Da Akun Adawel
                                                Street, Akhnaton Estate,
                                                After Dunamis Church, Area 1 Durumi District, Abuja.</p>
                                        </div>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
