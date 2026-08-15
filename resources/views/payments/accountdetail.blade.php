@extends('layouts.main')
@section('accoundetail')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h3>Account Details for Payment</h3>
                    </div>
                    <div class="card-body">
                        <!-- Payment Instructions -->
                        <div class="mb-4 text-center">
                            <p class="text-muted">
                                Please find the account details below. Make the payment and upload the receipt for
                                confirmation.
                            </p>
                        </div>

                        <!-- Account Information -->
                        <div class="account-info mb-4">
                            <div class="row">
                                <div class="col-md-6 text-center mb-3">
                                    <i class="fas fa-university fa-2x"></i>
                                    <h5 class="mt-2">Bank Name</h5>
                                    <p class="text-muted">GT Bank</p>
                                </div>
                                <div class="col-md-6 text-center mb-3">
                                    <i class="fas fa-user-tie fa-2x"></i>
                                    <h5 class="mt-2">Account Name</h5>
                                    <p class="text-muted">Pik and Crest Dynamic Concepts LTD</p>
                                </div>
                                <div class="col-md-6 text-center mb-3">
                                    <i class="fas fa-wallet fa-2x"></i>
                                    <h5 class="mt-2">Account Number</h5>
                                    <p class="text-muted">0051480410</p>
                                </div>
                                <div class="col-md-6 text-center mb-3">
                                    <i class="fas fa-coins fa-2x"></i>
                                    <h5 class="mt-2">Payment Amount</h5>
                                    <p class="text-muted">₦{{ number_format($plan->cost, 2) }}</p>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Payment Frequency and Purpose -->

                        <!-- Existing Uploaded Receipt -->
                        @if ($plan->receipt_path)
                            <div class="mt-4">
                                <h5>Uploaded Receipt:</h5>
                                <a href="{{ \App\Support\SecureDocument::url('receipts', $plan->receipt_path) }}"
                                    target="_blank">View Receipt</a>
                            </div>
                        @endif

                        <!-- Receipt Upload Section with Preview -->
                        <div class="receipt-upload mt-4">
                        
                            <form action="{{ route('payments.handle') }}" method="POST" enctype="multipart/form-data">

                                @csrf

                                {{-- Only the plan id is submitted. Amount, search quota, plan
                                     type and the owning user are resolved server-side from
                                     the PaymentPlan row - never trusted from the browser. --}}
                                <input type="hidden" name="payment_plan_id" value="{{ $plan->id }}">
                            
                                <!-- Plan Details -->
                                <h4 class="fw-bold">Subscription type: {{ $plan->plan_name }}</h4>
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="payment-details mb-3 w-50">
                                        <p class="mb-1"><strong>Amount:</strong> ₦{{ number_format($plan->cost, 2) }}</p>
                                        <p class="mb-1"><strong>Description:</strong> {{ $plan->about }}</p>
                                    </div>
                                    <div class="form-group mb-3 w-50">
                                        <p class="mb-1"><strong>Duration:</strong>
                                            @switch($plan->duration)
                                                @case(1)
                                                    One Day
                                                @break
                            
                                                @case(7)
                                                    One Week
                                                @break
                            
                                                @case(30)
                                                    One Month
                                                @break
                            
                                                @case(90)
                                                    Three Months
                                                @break
                            
                                                @case(180)
                                                    Six Months
                                                @break
                            
                                                @case(365)
                                                    One Year
                                                @break
                            
                                                @default
                                                    {{ $plan->duration }} Days
                                            @endswitch
                                        </p>
                                        <p><strong>Number of Search Views:</strong> {{ $plan->num_searches }}</p>
                                    </div>
                                </div>
                            
                                <!-- Receipt Upload -->
                                <h5 class="text-center">Upload Payment Receipt</h5>
                                <div class="form-group mb-3">
                                    <label for="receiptInput">Upload Receipt</label>
                                    <input type="file" name="receipt" class="form-control" id="receiptInput" required>
                                </div>
                            
                                <!-- Preview Section -->
                                <div id="previewContainer" class="mb-3 text-center" style="display: none;">
                                    <h6>Receipt Preview:</h6>
                                    <img id="receiptPreview" src="#" alt="Receipt Preview" class="img-fluid img-thumbnail" style="max-width: 300px;" loading="lazy" decoding="async">
                                </div>
                            
                                <!-- Submit Button -->
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">Upload Receipt of Payment</button>
                                </div>
                            </form>
                            
                            <!-- JavaScript for Receipt Preview -->
                            <script>
                                document.getElementById('receiptInput').addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            document.getElementById('receiptPreview').src = e.target.result;
                                            document.getElementById('previewContainer').style.display = 'block';
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        document.getElementById('previewContainer').style.display = 'none';
                                    }
                                });
                            </script>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
