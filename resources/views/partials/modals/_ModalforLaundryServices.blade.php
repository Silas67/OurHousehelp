        <!-- Modal for live-in Services -->
        <div class="modal fade" id="laundryServices" tabindex="-1">
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
                                        Our staff are trained to deliver services effectively and efficiently within <strong>4 hours per service</strong>, so our clients have nothing to worry about.
                                        For <strong>2 services, the allocated time is 6 hours</strong>, and for <strong>3 or more services, it is 9 hours or more</strong>.
                                    </p>
                                </div>

                                <!-- Note II -->
                                <div class="note">
                                    <h4>NOTE II</h4>
                                    <p>
                                        Our staff should be allowed to leave the client's premises once the house or office chores are completed.
                                        Clients are not permitted to split the allocated hours for a day into two or more days.
                                    </p>
                                </div>

                                <!-- Note III -->
                                <div class="note">
                                    <h4>NOTE III</h4>
                                    <p>
                                        We kindly ask our clients to be patient with us when scheduling service days, as some days may be busier than others.
                                        For clients who prefer service on Saturdays, please note that only <strong>2 Saturdays are available per month</strong>.
                                        <strong>We cannot service all clients on Saturdays</strong>, and you will be asked to select two Saturdays per month.
                                    </p>
                                    <p>
                                        We also request that clients allow us <strong>24 hours after payment</strong> to assign the best-suited staff to their needs.
                                    </p>
                                </div>

                                <!-- Note IV -->
                                <div class="note">
                                    <h4>NOTE IV</h4>
                                    <p>
                                        Clients should choose their service days carefully. Any missed day will need to be carried over to another week only if the client notifies the office in advance.
                                        <strong>If no notification</strong> is sent and the staff was present at the location, the service will be counted as delivered.
                                    </p>
                                </div>

                                <!-- Note V -->
                                <div class="note">
                                    <h4>NOTE V</h4>
                                    <p>
                                        Clients must not make any agreements (written or oral) with our staff without the company’s knowledge. Any such agreement is made at the client’s risk.
                                    </p>
                                    <p>
                                        Disciplinary actions will be handled solely by the company. Clients are encouraged to report any issues directly to us.
                                    </p>
                                </div>

                                <!-- Note VI -->
                                <div class="note">
                                    <h4>NOTE VI</h4>
                                    <p>
                                        Clients wishing to end their service should indicate their intent during the current service. Any additional services beyond the agreed period will require full payment.
                                    </p>
                                    <p><strong>Attention:</strong> Our laundry services do not include the cleaning of underwear.</p>
                                </div>
                            </div>

                        </section>

                    </div>
                    <div class="modal-footer">
                        @if (!Route::is('multiple'))
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('laundry') }}">I Agree, continue</a>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal for live-in Services -->
