        <!-- Modal for live-in Services -->
        <div class="modal fade" id="cleaningServices" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notice of Agreement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <section>
                            <div class="cleaning-housekeeping-notice">

                                <h3>Note I</h3>
                                <p>
                                    Our staff are trained to deliver services effectively and efficiently within 4 hours per service, ensuring our clients have nothing to worry about.
                                    The allocated time for 2 services is 6 hours, and for 3 or more services, it is 9 hours or more.
                                </p>

                                <h3>Note II</h3>
                                <p>
                                    Our staff should be allowed to leave the client’s premises as soon as they have completed the assigned house or office chores.
                                    Clients cannot split the allocated hours for a day into two or more days.
                                </p>

                                <h3>Note III</h3>
                                <p>
                                    We kindly request our clients to be patient when managing their days of service, as some days are busier than others.
                                    For clients who prefer services on Saturdays, only 2 Saturdays are available per month. We cannot service all clients on Saturdays, so you will be asked to select two Saturdays per month.
                                </p>
                                <p>
                                    Additionally, we ask clients to allow us 72 hours after payment to assign the most suitable staff to you.
                                </p>

                                <h3>Note IV</h3>
                                <p>
                                    Clients should be mindful when choosing days for their services. If a client misses a scheduled day, it will be carried over to another week only if the client contacts the office to notify us.
                                    If no notification is sent to the office or staff, and the staff was present, it will be counted as delivered.
                                </p>

                                <h3>Note V</h3>
                                <p>
                                    Clients must not make any agreements (written or oral) with our staff without the company’s knowledge.
                                    Any such agreement is made at the client’s risk.
                                </p>
                                <p>
                                    Any disciplinary action will be taken by the company and not by the client. Clients should report any issues to us, and we will take the necessary action.
                                </p>

                                <h3>Note VI</h3>
                                <p>
                                    Clients wishing to end their service should indicate this during their current service.
                                    Any extra service beyond the agreed terms will be fully paid for.
                                </p>
                            </div>

                        </section>

                    </div>
                    <div class="modal-footer">
                        @if (!Route::is('multiple'))
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('cleaning') }}">I Agree, continue</a>
                        @else
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal for live-in Services -->
