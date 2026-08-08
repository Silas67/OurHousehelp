@extends('layouts.main')
@section('404')


<div class="error-container-404 py-5 mt-5">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>Sorry, the page you are looking for doesn't exist or has been moved.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Go Back to Home</a>
</div>
@endsection
