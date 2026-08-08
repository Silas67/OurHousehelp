        <!-- Modal for live-in Services -->
        <div class="modal fade" id="errandServices" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notice of Agreement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section>
                            <div class="notes-section">
                                <!-- Note I -->
                                <div class="note">
                                    <h4>NOTE I</h4>
                                    <p>
                                        Our staff are trained to deliver services effectively and efficiently within <strong>4 hours per service</strong>, so clients have nothing to worry about.
                                        For <strong>2 services, the allocated time is 6 hours</strong>, and for <strong>3 or more services, it is 9 hours or more</strong>.
                                    </p>
                                </div>

                                <!-- Note II -->
                                <div class="note">
                                    <h4>NOTE II</h4>
                                    <p>
                                        Our staff should be allowed to leave the client's premises as soon as they complete the house or office chores.
                                        Clients are not permitted to split the allocated hours for a day into two or more days.
                                    </p>
                                </div>

                                <!-- Note III -->
                                <div class="note">
                                    <h4>NOTE III</h4>
                                    <p>
                                        We kindly ask our clients to be patient with us when managing their service days, as some days may be busier than others.
                                        For clients requesting service on Saturdays, please note that <strong>only 2 Saturdays are available per month</strong>.
                                        <strong>We cannot clean all clients' homes on Saturdays</strong>. You will be asked to select two Saturdays per month.
                                    </p>
                                    <p>
                                        Additionally, we request clients give us <strong>24 hours after payment</strong> to assign the most suitable staff for their needs.
                                    </p>
                                </div>

                                <!-- Note IV -->
                                <div class="note">
                                    <h4>NOTE IV</h4>
                                    <p>
                                        Clients should carefully choose their service days. If a client misses a scheduled day and notifies the office in advance,
                                        the service can be carried over to another week.
                                        <strong>If no notification is provided</strong> and our staff are present at the location, the service will be counted as delivered.
                                    </p>
                                </div>

                                <!-- Note V -->
                                <div class="note">
                                    <h4>NOTE V</h4>
                                    <p>
                                        Clients must not make any agreements (written or oral) with our staff without the company's knowledge. Any such agreement will be at the client's own risk.
                                    </p>
                                    <p>
                                        Any disciplinary actions will be handled solely by the company. Clients should report any issues to us directly, and we will address them.
                                    </p>
                                </div>

                                <!-- Note VI -->
                                <div class="note">
                                    <h4>NOTE VI</h4>
                                    <p>
                                        Clients wishing to terminate their service should indicate this during the current service period. Any additional services beyond this will require full payment.
                                    </p>
                                </div>
                            </div>
                        </section>

                    </div>
                    <div class="modal-footer">
                        @if (!Route::is('multiple'))
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('errand') }}">I Agree, continue</a>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal for live-in Services -->
