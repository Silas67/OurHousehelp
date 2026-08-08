 <div class="modal fade" id="reasonsModal" tabindex="-1" aria-labelledby="reasonsModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl">
         <div class="modal-content">
             <div class="modal-header text-white" style="background: #479c18">
                 <h5 class="modal-title" id="reasonsModalLabel">
                     <i class="fas fa-star me-2"></i>10 Strong Reasons to Register With Our House Help Services
                 </h5>
                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-info">
                     <i class="fas fa-info-circle me-2"></i>
                     @if (request()->route()->getName() !== 'get-started')
                         <strong>Registration Fee:</strong> ₦10,000.00 per year |
                     @endif
                     <strong>Benefits:</strong> Complete peace of mind and professional home support
                 </div>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">1</span>
                                 <div>
                                     <h6 class="mb-1">Staff Verification & Background Checks</h6>
                                     <p class="small mb-0 text-muted">We do full identity verification and require two
                                         guarantors before assigning any staff to you. So you can relax knowing your
                                         home
                                         is secure.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">2</span>
                                 <div>
                                     <h6 class="mb-1">Verified House-Help & Artisan Database</h6>
                                     <p class="small mb-0 text-muted">Your membership gives you full access to our
                                         constantly updated database of house helps, nannies, cooks, and domestic staff
                                         sourced through our nationwide recruitment system.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">3</span>
                                 <div>
                                     <h6 class="mb-1">Choose Live-In or Live-Out Staff</h6>
                                     <p class="small mb-0 text-muted">You are free to select the exact type of house
                                         help you need — live-in, Live-Out, Semi-Live Out or Daily service — based on
                                         your lifestyle and personal comfort.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">4</span>
                                 <div>
                                     <h6 class="mb-1">Fast Staff Assignment</h6>
                                     <p class="small mb-0 text-muted">Within 24 hours to 96 hours we assign a suitable
                                         and qualified staff close to your location — fast and stress-free.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">5</span>
                                 <div>
                                     <h6 class="mb-1">Free Staff Replacement</h6>
                                     <p class="small mb-0 text-muted">If your assigned staff is not delivering value,
                                         you have the right to request a replacement at no extra charge.</p>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">6</span>
                                 <div>
                                     <h6 class="mb-1">Backup Support Staff</h6>
                                     <p class="small mb-0 text-muted">If your primary staff is unavailable, we send a
                                         backup assistant to step in — ensuring your home remains organized without
                                         interruption. You have up to 3 changes within a year.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">7</span>
                                 <div>
                                     <h6 class="mb-1">Free Monthly Deep Cleaning</h6>
                                     <p class="small mb-0 text-muted">Members who choose cleaning receive one
                                         professional deep cleaning monthly — worth more than the membership itself.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">8</span>
                                 <div>
                                     <h6 class="mb-1">Priority Customer Service</h6>
                                     <p class="small mb-0 text-muted">Registered members receive faster support,
                                         priority replacements, and rapid response to domestic emergencies.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">9</span>
                                 <div>
                                     <h6 class="mb-1">Peace of Mind & Professional Handling</h6>
                                     <p class="small mb-0 text-muted">We manage the staff, their performance,
                                         discipline, welfare, and accountability — you only enjoy the service.</p>
                                 </div>
                             </div>
                         </div>

                         <div class="reason-item mb-3">
                             <div class="d-flex align-items-start">
                                 <span class="badge bg-success me-3 mt-1">10</span>
                                 <div>
                                     <h6 class="mb-1">House Help Family Network</h6>
                                     <p class="small mb-0 text-muted">Access to discounts, domestic care education, home
                                         support consults, and member-only programs. You are a valued member of a
                                         structured home-support ecosystem.</p>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Value Proposition -->
                 <div class="alert alert-success mt-3">
                     <div class="d-flex align-items-center">
                         <i class="fas fa-gem fa-2x me-3 text-success"></i>
                         <div>
                             @if (request()->route()->getName() !== 'get-started')
                                 <h6 class="mb-1">For just ₦10,000 per year, you are buying:</h6>
                             @endif
                             <p class="mb-1"><strong>Safety, Convenience, Reliability, Backup Support, Clean,
                                     Organized Home Life</strong></p>
                             <p class="mb-0 small">Zero stress in managing staff. This is not a fee — it is peace of
                                 mind.</p>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                     <i class="fas fa-check me-2"></i>I Understand - Continue to Payment
                 </button>
             </div>
         </div>
     </div>
 </div>
