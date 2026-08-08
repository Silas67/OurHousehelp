@extends('layouts.main')
@section('guarantors_two')
    <div class="container mt-5 py-5">
        <div class="row justify-content-center py-2">
            <div class="col-md-8">
                <div class="card">
                    <br>
                    <div class="container">
                        <h2>Guarantors Form for Applicant: <br>{{ $name }}</h2>
                        <div class="container">
                            <h5>Guarantors Information</h5>
                            @include('partials.applicant._guarantors_two')
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

                            @if ($user->guarantors_forms_uploaded && $user->guarantors_forms_uploaded_2)
                                <strong class="mt-3">Final step, please read and accept our terms and
                                    conditions.</strong><br>
                                <a href="{{ route('terms.conditions', ['user_id' => $user->id, 'name' => $user->name]) }}"
                                    class="btn btn-primary mb-3">
                                    Terms and Conditions
                                </a>
                            @else
                                <strong class="mt-3">Please complete the guarantors' forms to move on to the final
                                    step.</strong>
                            @endif


                        </div> <br>
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
