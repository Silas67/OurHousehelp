@extends('layouts.applicantmain')
@section('applicantprofile')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Applicant Profile</h5>
            </div>
            @include('partials._profileupdate')
        </div>
    </div>

    <div class="container mt-5" hidden>
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Application preference</h5>
            </div>
            <!-- Profile Form -->
            <div class="row justify-content-center card-body">
                <div class="col-md-8">
                    @include('partials.applicant._prefrence')
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Bank Account Details</h5>
            </div>
            <div class="row justify-content-center card-body">
                <div class="col-md-8">
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="account_name" class="form-label">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name"
                                value="{{ old('account_name', $user->account_name) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bank" class="form-label">Bank</label>
                            <input type="text" class="form-control" id="bank" name="bank"
                                value="{{ old('bank', $user->bank) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                value="{{ old('account_number', $user->account_number) }}" required>
                        </div>

                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-success">Save Account Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <div class="container mt-5">
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Guarantors</h5>
            </div>
            <!-- Profile Form -->
            <div class="row justify-content-center card-body  py-2">
                <div class="container">
                    <div class="col-md-12">
                        @include('partials.applicant._guarantors')
                    </div>
                    <div class="col-md-12">
                        @include('partials.applicant._guarantors_two')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._changepassword')

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection
