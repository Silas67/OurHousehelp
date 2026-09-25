<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    {{-- Bootstrap 5.3.3 + FontAwesome 6, self-hosted. This layout previously
         loaded Bootstrap 5.3.1, 5.3.0 AND 4.6.2 together from three CDNs. --}}
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

    <link href="{{ asset('build/assets/css/userstyle.css') }}" rel="stylesheet">




</head>

<body>

    {{-- @include('partials.modals._419') --}}
    @include('partials.modals._ModalforLogout')


    <!-- Preloader -->
    <div id="preloader">
        <div>
            <div class="loader"></div>
            <div class="loading-text">Loading...</div>
        </div>
    </div>


    <!-- Sidebar -->
    @include('partials.user._usersidebar')



    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <x-img width="130" src="build/assets/image/logo/ohh-log-b.png" loading="eager" alt="Logo"/>
                </a>
                
                <span class="burger-icon" id="burger-icon">
                    <i class="fas fa-bars"></i>
                </span>

                

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            @if (Auth::user())
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
                            @endif
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell" style="color: #333"></i>
                                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                    <span class="badge bg-danger">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" style="color: #333" href="{{ route('clientprofile') }}">Profile</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </nav>



        <div class="container mt-4 content-container">
            @include('partials._flash')

            @include('partials.user._userpages')
        </div>

    </div>

    {{-- Bootstrap JS ships via @vite in <head>. Removed here: a second and third
         Bootstrap bundle (5.3.1, 5.3.0), two jQuery builds and a standalone
         Popper. No view under this layout uses jQuery, and Bootstrap 5 bundles
         Popper itself. --}}
    <script src="{{ asset('build/assets/js/userscript.js') }}"></script>
    <script src="{{ asset('js/pwa.js') }}" defer></script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        });
    </script>
</body>

</html>
