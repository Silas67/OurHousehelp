@extends('layouts.main')
@section('419')
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; text-align: center;">
        <h1>So sorry this page expired</h1>
        <p>It seems this page has expired. Please click the button below to refresh the page.</p>

        <!-- Conditional Redirect Based on Authentication -->
        <a class="btn btn-sm btn-primary"
            style="padding: 10px 20px; background-color: green; color: white; border: none; cursor: pointer; border-radius: 5px;"
            href="{{ auth()->check() ? route('home') : route('login') }}">
            Refresh Page
        </a>
    </div>
@endsection

