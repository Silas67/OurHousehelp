@extends('layouts.main')
@section('terms')
    <div class="container mt-5 py-5">
        <div class="row justify-content-center py-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header recentapp">{{ __('Terms and Conditions') }}</div>


                    <div class="card-body text-center">
                        @include('partials.applicant._terms_and_conditions')


                        <hr>

                        @include('partials.applicant._prefrence')



                        <br>

                        <form action="{{ route('terms.accept') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">I Accept the Terms and Conditions</button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
