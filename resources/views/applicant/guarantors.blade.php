@extends('layouts.main')
@section('guarantors')
    <div class="container mt-5 py-5">
        <div class="row justify-content-center py-2">
            <div class="col-md-8">
                <div class="card">
                    <br>
                    <div class="container">
                        <h2>Guarantors Form for Applicant: <br>{{ $name }}</h2>
                        <div class="container">
                            <h5>Guarantors Information</h5>
                            @include('partials.applicant._guarantors')
                            <script>
                                function previewImage(inputId, imgPreviewId) {
                                    const input = document.getElementById(inputId);
                                    const preview = document.getElementById(imgPreviewId);

                                    input.addEventListener('change', function() {
                                        const file = input.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                preview.src = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });
                                }
                            </script>

                            @if (Auth::user()->guarantors)
                                <strong>Step 4: Please fill out the Next Guarantors' form.</strong><br>
                                <a href="{{ route('applicant.guarantors_two', ['user_id' => Auth::user()->id, 'name' => Auth::user()->name]) }}"
                                    class="btn btn-primary">
                                    Fill Next Guarantors' Forms
                                </a>
                            @else
                            <strong class="mt-3">Please complete the guarantors' forms to move on to the next
                                step.</strong>
                            @endif


                            <br>

                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(inputId, previewId) {
            const file = document.getElementById(inputId).files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
