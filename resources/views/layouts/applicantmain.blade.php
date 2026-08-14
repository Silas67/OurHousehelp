<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    {{-- Bootstrap 5.3.3 + FontAwesome 6, self-hosted. This layout previously
         loaded Bootstrap 5.3.1 and 5.3.3 plus two FontAwesome builds. --}}
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
    <meta name="vapid-public-key" content="{{ config('services.vapid.public_key') }}">
    <title>{{ Request::segment(1) }}</title>

    <link rel="icon" href="{{ asset('build/assets/image/logo/ohh-log.png') }}">

    <link href="{{ asset('build/assets/css/applicantstyle.css') }}" rel="stylesheet">

</head>

<body>

    {{-- @include('partials.modals._419') --}}


    <!-- Preloader -->
    <div id="preloader">
        <div>
            <div class="loader"></div>
            <div class="loading-text">Loading... Please wait</div>
        </div>
    </div>

    <!-- Sidebar -->
    @include('partials.applicant._applicantsidebar')



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
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-envelope" style="color: #333"></i>
                                <span id="message-badge" class="badge bg-danger">0</span> <!-- Badge for new messages -->
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell" style="color: #333"></i>
                                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                    <span class="badge bg-danger">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </a>
                        </li>
                        {{-- Was a bare "Profile" text link sitting oddly beside the
                             icon-only nav items. Now an avatar + name, matching the
                             visual weight of the bell. --}}
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2 navbar-profile"
                                href="{{ route('applicantprofile', ['id' => Auth::user()->id]) }}"
                                title="View your profile">
                                <img src="{{ Auth::user()->profile_photo_url }}"
                                    alt="" width="30" height="30"
                                    class="rounded-circle"
                                    style="object-fit:cover;border:1px solid #dee2e6;"
                                    loading="lazy" decoding="async">
                                <span class="navbar-profile-name d-none d-sm-inline">
                                    {{ Str::limit(Auth::user()->name, 14) }}
                                </span>
                            </a>
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
            @include('partials.applicant._applicantpages')
        </div>

    </div>

    {{-- Bootstrap JS ships via @vite in <head>. Removed here: three further
         Bootstrap bundles (5.3.1, 5.1.3, 5.3.3). --}}
    <script src="{{ asset('build/assets/js/applicantscript.js') }}"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        });
    </script>

</body>

</html>
