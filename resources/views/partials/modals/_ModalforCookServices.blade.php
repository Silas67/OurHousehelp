        <!-- Modal for live-in Services -->
        <div class="modal fade" id="cookServices" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notice of Agreement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section>
                            <div class="service-notes">
                                <h4>NOTE I</h4>
                                <p>
                                    Our staff are professionally trained to deliver effective and efficient service within 4 hours per visit. For clients requesting two services, the total allocated time is 6 hours, while three or more services will require 9 hours or more. You can rest assured that the service quality will not be compromised.
                                </p>

                                <h4>NOTE II</h4>
                                <p>
                                    Once our staff complete their assigned tasks, they should be allowed to leave the premises. Clients are not permitted to divide the allocated hours for one day across multiple days.
                                </p>

                                <h4>NOTE III</h4>
                                <p>
                                    We kindly ask for patience when scheduling service days. Some days are busier for us, and we cannot accommodate everyone at once. If you prefer service on a Saturday, please note that we offer availability on only two Saturdays per month. We cannot service all clients' homes on Saturdays. Additionally, after payment, please allow 24 hours for us to assign the ideal staff to your home.
                                </p>

                                <h4>NOTE IV</h4>
                                <p>
                                    Please be mindful when selecting your service days. If you need to reschedule, notify the office in advance so we can carry over the missed day to another week. If no notification is given and our staff is present, the service will be considered delivered.
                                </p>

                                <h4>NOTE V</h4>
                                <p>
                                    Any agreements made with our staff, whether written or verbal, without the company's approval are considered at the client's own risk. The company reserves the right to handle any disciplinary actions related to staff. Clients are encouraged to report any concerns directly to the company.
                                </p>

                                <h4>NOTE VI</h4>
                                <p>
                                    If you wish to terminate your service, please inform us during your current service. Any additional services beyond the current arrangement will be billed accordingly.
                                </p>
                            </div>

                        </section>

                    </div>

                    <div class="modal-footer">
                        @if (!Route::is('multiple'))
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('cook') }}">I Agree, continue</a>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal for live-in Services -->
