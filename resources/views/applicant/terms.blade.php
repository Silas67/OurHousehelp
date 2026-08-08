@extends('layouts.applicantmain')
@section('terms')
    <div class="container mt-5">
        <!-- _terms_and_conditions Section -->
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Applicant Terms & Conditions</h5>
            </div>
            <div class="container mt-3">
                @include('partials.applicant._terms_and_conditions')
            </div>
        </div>
    </div>
@endsection
