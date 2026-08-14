<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap 5.3.3 + FontAwesome 6, self-hosted. This layout previously
         loaded Bootstrap 5.3.1 AND 4.5.2 at the same time from two CDNs. --}}
    @vite(['resources/css/ui.css', 'resources/js/ui.js'])

    {{-- PWA: installable app manifest + theme colour --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2A2D5A">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">








    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Get house helps to do cleanings">
    <meta name="keywords" content="house, help, cleaning, house chores">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Request::segment(1) }}</title>

    <link rel="icon" href="{{ asset('build/assets/image/logo/ohh-log.png') }}">

    <link href="{{ asset('build/assets/css/adminstyle.css') }}" rel="stylesheet">

    <script src="https://cdn.tiny.cloud/1/xpfdp7y05zsq4fz84hcgoalq4wp42owz76kw6wlroa4m77rv/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

</head>



<body>

    <!-- Preloader -->
    <div id="preloader">
        <div>
            <div class="loader"></div>
            <div class="loading-text">Loading... Please wait</div>
        </div>
    </div>


    <!-- Sidebar -->
    @include('partials.admin._sidebar')



    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <span class="burger-icon" id="burger-icon">
                    <i class="fas fa-bars"></i>
                </span>
                <a class="navbar-brand" href="/"><x-img width="130px"
                        src="build/assets/image/logo/ohh-log-b.png" loading="eager" alt="Logo"/></a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            @if (in_array(Auth::user()->role, [1, 2]))
                                <!-- Check if the user role is 1 (Admin) or 2 (Super Admin) -->
                                <a class="nav-link" href="{{ route('contactmessage') }}">
                                    <i class="fas fa-envelope" style="color: #333"></i>
                                    @if ($unreadMessagesCount > 0)
                                        <span id="message-badge"
                                            class="badge bg-danger">{{ $unreadMessagesCount }}</span>
                                    @else
                                        <span id="message-badge" class="badge bg-danger">0</span>
                                    @endif
                                    <!-- Badge for new messages -->
                                </a>
                            @else
                                <!-- New messages for individual clients -->
                                <a class="nav-link" href="{{ route('clientmessage') }}">
                                    <i class="fas fa-envelope" style="color: #333"></i>
                                    @if ($unreadMessagesCount > 0)
                                        <span id="message-badge"
                                            class="badge bg-danger">{{ $unreadMessagesCount }}</span>
                                    @else
                                        <span id="message-badge" class="badge bg-danger">0</span>
                                    @endif
                                    <!-- Badge for new messages -->
                                </a>
                            @endif
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" style="color: #333" href="#">Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



        <div class="container mt-4 content-container">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @include('partials._adminpages')
        </div>

    </div>

    {{-- Bootstrap JS now ships via @vite in <head>. jQuery slim, Popper and the
         second Bootstrap (4.5.2) bundle were removed: no view under this layout
         uses jQuery, and Bootstrap 5 bundles Popper itself. --}}
    <script src="{{ asset('build/assets/js/adminscript.js') }}"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        });
    </script>

</body>

</html>
