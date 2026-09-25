@extends('layouts.usermain')

@section('booking-service-selection')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                {{-- Header --}}
                <div class="mb-4">
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-coins me-2 text-warning"></i>Booking Credits
                    </h3>
                    <p class="text-muted mb-0">Purchase credits to view staff profiles and complete bookings.</p>
                </div>

                {{-- Current Balance --}}
                <div class="card border-0 shadow-sm mb-4"
                    style="background: linear-gradient(135deg, #0F6E56, #1D9E75);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-size:13px; opacity:0.8; text-transform:uppercase; letter-spacing:0.06em;">
                                    Current Balance
                                </div>
                                <div style="font-size:48px; font-weight:600; line-height:1.1;">
                                    {{ Auth::user()->getCreditBalance() }}
                                </div>
                                <div style="font-size:14px; opacity:0.8;">
                                    {{ Auth::user()->getCreditBalance() === 1 ? 'credit' : 'credits' }} available
                                </div>
                            </div>
                            <i class="fas fa-coins" style="font-size:64px; opacity:0.15;"></i>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Credit Plans --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border h-100 text-center p-3 plan-card"
                            data-credits="5" data-amount="10000">
                            <div class="fw-bold fs-4 text-success">5</div>
                            <div class="text-muted small">credits</div>
                            <div class="fw-bold mt-2">₦10,000</div>
                            <div class="text-muted" style="font-size:12px;">Starter</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success border-2 h-100 text-center p-3 plan-card"
                            data-credits="8" data-amount="15000" style="background:#f0fff4;">
                            <div class="badge bg-success mb-1" style="font-size:10px;">Most Popular</div>
                            <div class="fw-bold fs-4 text-success">8</div>
                            <div class="text-muted small">credits</div>
                            <div class="fw-bold mt-2">₦15,000</div>
                            <div class="text-muted" style="font-size:12px;">Standard</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border h-100 text-center p-3 plan-card"
                            data-credits="12" data-amount="20000">
                            <div class="fw-bold fs-4 text-success">12</div>
                            <div class="text-muted small">credits</div>
                            <div class="fw-bold mt-2">₦20,000</div>
                            <div class="text-muted" style="font-size:12px;">Premium</div>
                        </div>
                    </div>
                </div>

                {{-- Bank Transfer + Receipt Upload --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-university me-2"></i>How to Purchase Credits
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <p class="text-muted mb-4">
                            Transfer your chosen plan amount to our account below, then upload your receipt.
                            Credits are added within <strong>5 minutes</strong> during business hours.
                        </p>

                        {{-- Bank Details --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 text-center">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <i class="fas fa-university fa-2x text-muted mb-2"></i>
                                    <div class="small text-muted">Bank Name</div>
                                    <div class="fw-bold">GT Bank</div>
                                </div>
                            </div>
                            <div class="col-md-6 text-center">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <i class="fas fa-user-tie fa-2x text-muted mb-2"></i>
                                    <div class="small text-muted">Account Name</div>
                                    <div class="fw-bold" style="font-size:13px;">
                                        Pik and Crest Dynamic Concepts
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <i class="fas fa-wallet fa-2x text-muted mb-2"></i>
                                    <div class="small text-muted">Account Number</div>
                                    <div class="fw-bold fs-4" style="letter-spacing:0.1em;">
                                        0051480410
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                        onclick="navigator.clipboard.writeText('0051480410');
                                                this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Steps --}}
                        <div class="border rounded p-3 mb-4"
                            style="background:#f0fff4; border-color:#9FE1CB !important;">
                            <div class="fw-semibold mb-3" style="color:#0F6E56;">
                                <i class="fas fa-list-ol me-2"></i>Steps:
                            </div>
                            @foreach(['Select your plan above', 'Transfer the amount to the account above', 'Upload your payment receipt below', 'Credits added within 5 minutes — WhatsApp us for faster processing'] as $i => $step)
                            <div class="d-flex gap-3 {{ !$loop->last ? 'mb-2' : '' }}">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:24px; height:24px; font-size:12px; font-weight:600;">
                                    {{ $i + 1 }}
                                </div>
                                <div style="font-size:14px;">{{ $step }}</div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Fix #2: Show pending state or upload form --}}
                        @if($receiptPending)
                            {{-- Receipt already submitted — show pending state --}}
                            <div class="card border-warning mb-4">
                                <div class="card-body p-4 text-center">
                                    <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                                    <h5 class="fw-bold">Receipt Under Review</h5>
                                    <p class="text-muted mb-3">
                                        Your payment receipt has been submitted and is being reviewed by our team.
                                        Credits will be added within <strong>30 minutes</strong> during business hours.
                                    </p>
                                    <a href="https://wa.me/2348126237436?text=Hi%2C%20I%20submitted%20a%20credit%20receipt%20and%20want%20to%20follow%20up.%20My%20email%20is%20{{ urlencode(Auth::user()->email) }}"
                                        target="_blank" class="btn btn-success">
                                        <i class="fab fa-whatsapp me-2"></i>Follow up on WhatsApp
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Receipt Upload Form --}}
                            <div class="card border mb-4" style="border-color:#dee2e6 !important;">
                                <div class="card-header" style="background:#f8f9fa;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-upload me-2 text-success"></i>Upload Payment Receipt
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <form action="{{ route('credits.submit-receipt') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Plan Purchased <span class="text-danger">*</span>
                                            </label>
                                            <select name="credits" class="form-select" id="planSelect" required>
                                                <option value="">— Select plan —</option>
                                                <option value="5">Starter — 5 credits (₦10,000)</option>
                                                <option value="8">Standard — 8 credits (₦15,000)</option>
                                                <option value="12">Premium — 12 credits (₦20,000)</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Amount Paid <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">₦</span>
                                                <input type="text" name="amount_paid" id="amountPaid"
                                                    class="form-control" placeholder="e.g. 10000" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Payment Receipt <span class="text-danger">*</span>
                                            </label>
                                            <div class="border rounded p-4 text-center" id="uploadArea"
                                                style="border-style:dashed !important; cursor:pointer; background:#fafafa;"
                                                onclick="document.getElementById('receiptFile').click()">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                                <div class="text-muted small" id="uploadText">
                                                    Click to upload receipt<br>
                                                    <span style="font-size:11px;">JPG, PNG or PDF — max 5MB</span>
                                                </div>
                                            </div>
                                            <input type="file" name="receipt" id="receiptFile"
                                                accept=".jpg,.jpeg,.png,.pdf" class="d-none" required>
                                            @error('receipt')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                Note <span class="text-muted fw-normal">(optional)</span>
                                            </label>
                                            <input type="text" name="note" class="form-control"
                                                placeholder="e.g. Transfer from Access Bank">
                                        </div>

                                        <button type="submit" class="btn btn-success w-100 py-3"
                                            style="font-size:15px;">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Receipt for Review
                                        </button>
                                        <p class="text-muted text-center mt-2 mb-0" style="font-size:12px;">
                                            <i class="fas fa-clock me-1"></i>
                                            Credits added within 5 minutes during business hours
                                        </p>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- WhatsApp fallback --}}
                        <div class="text-center mb-3">
                            <p class="text-muted small mb-2">Or notify us directly for faster processing:</p>
                            <a href="https://wa.me/2348126237436?text=Hi%2C%20I%20just%20made%20a%20credit%20payment.%20My%20email%20is%20{{ urlencode(Auth::user()->email) }}"
                                target="_blank" class="btn btn-outline-success">
                                <i class="fab fa-whatsapp me-2" style="font-size:16px;"></i>
                                Contact on WhatsApp
                            </a>
                        </div>

                        {{-- Paystack disabled --}}
                        @if(config('app.paystack_enabled', false))
                            <button type="button" class="btn btn-primary w-100 py-3" style="font-size:15px;">
                                <i class="fas fa-credit-card me-2"></i>Pay with Card (Paystack)
                            </button>
                        @else
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-secondary w-100 py-3"
                                    disabled style="font-size:15px; opacity:0.5; cursor:not-allowed;">
                                    <i class="fas fa-credit-card me-2"></i>Pay with Card — Coming Soon
                                </button>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Card payments will be available shortly.
                                </small>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Transaction History --}}
                @if(Auth::user()->creditTransactions()->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Transaction History</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Auth::user()->creditTransactions()->latest()->take(10)->get() as $tx)
                                    <tr>
                                        <td class="small text-muted">
                                            {{ $tx->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="small">{{ $tx->description }}</td>
                                        <td>
                                            <span class="badge {{ $tx->type === 'purchase' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $tx->type === 'purchase' ? '+' : '-' }}{{ $tx->amount }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        // Plan card click auto-fills form
        document.querySelectorAll('.plan-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.plan-card').forEach(c => c.style.outline = 'none');
                this.style.outline = '3px solid #0F6E56';
                document.getElementById('planSelect').value = this.dataset.credits;
                document.getElementById('amountPaid').value  = this.dataset.amount;
            });
        });

        // Show filename when receipt selected
        const fileInput = document.getElementById('receiptFile');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    document.getElementById('uploadText').innerHTML =
                        `<i class="fas fa-check-circle text-success"></i> <strong>${file.name}</strong><br>
                        <span style="font-size:11px; color:#6c757d;">${(file.size/1024).toFixed(0)} KB</span>`;
                    document.getElementById('uploadArea').style.borderColor = '#0F6E56';
                }
            });
        }
    </script>
@endsection