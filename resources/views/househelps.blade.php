@extends('layouts.usermain')
@section('househelps')
    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2>Available Live-In House Helps</h2>
            <p class="lead">Browse through our list of trusted house helps and select the one that suits your needs.</p>
        </div>

        {{-- @include('partials._searchForHouseHelp') --}}
        @include('partials._searchForHousehelpsorskills')

</div>

@endsection
