        <!-- Modal for live-in Services -->
        <div class="modal fade" id="nannyServices" tabindex="-1">
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
                                    Our staff are trained to deliver effectively and efficiently within 4 hours per
                                    service, so our clients have nothing to worry about. The allocated time for 2
                                    services is 6 hours, and for 3 or more services is 9 hours or more.
                                </p>

                                <h4>NOTE II</h4>
                                <p>
                                    Our staff should be allowed to leave the client's premises as soon as they are done
                                    with the house or office chores. Clients cannot split the allocated hours for a day
                                    into two or more days.
                                </p>

                                <h4>NOTE III</h4>
                                <p>
                                    We kindly request our clients to be patient when scheduling their service days. Some
                                    days tend to be busier for us than others. For clients requesting Saturday service,
                                    only 2 Saturdays are available per month. <strong>We cannot clean all clients' homes
                                        on Saturdays.</strong> Additionally, after payment is made, please allow us 24
                                    hours to assign the perfect staff to you.
                                </p>

                                <h4>NOTE IV</h4>
                                <p>
                                    Clients should be cautious when selecting service days. Any missed day must be
                                    rescheduled to another week if the office is notified in advance. If no notification
                                    is given and our staff is present at the scheduled time, the service will be counted
                                    as delivered.
                                </p>

                                <h4>NOTE V</h4>
                                <p>
                                    Clients must not make any agreements (written or verbal) with our staff without the
                                    company's knowledge. Any such agreement is at the client's risk. Disciplinary
                                    actions, if needed, will be handled solely by the company, and clients are
                                    encouraged to report any issues directly to us.
                                </p>

                                <h4>NOTE VI</h4>
                                <p>
                                    If a client wishes to end their service, they must indicate this during the current
                                    service. Any additional services will be charged in full.
                                </p>
                            </div>


                        </section>

                    </div>
                    <div class="modal-footer">
                        @if (!Route::is('multiple'))
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('nanny') }}">I Agree, continue</a>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal for live-in Services -->
