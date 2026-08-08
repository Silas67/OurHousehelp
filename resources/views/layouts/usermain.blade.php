<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha384-3SHg1prTkIYHnBOxLDp3OhZQwD+ge87kJ8kB9z5kgW1p0RTGzLTA5GmFHRGACF7d" crossorigin="anonymous">



    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Get house helps to do cleanings">
    <meta name="keywords" content="house, help, cleaning, house chores">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Request::segment(1) }}</title>

    <link rel="icon" href="{{ asset('build/assets/image/logo/ohh-log.png') }}">

    <link href="{{ asset('build/assets/css/userstyle.css') }}" rel="stylesheet">

    <!-- Add in your main layout file (head section) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



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
    @include('partials.user._usersidebar')



    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <span class="burger-icon" id="burger-icon">
                    <i class="fas fa-bars"></i>
                </span>
                <a class="navbar-brand" href="/"><img width="130px"
                        src="{{ asset('build/assets/image/logo/ohh-log-b.png') }}" alt="Logo"></a>
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
                        <li class="nav-item">
                            <a class="nav-link" style="color: #333" href="{{ route('clientprofile') }}">Profile</a>
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
            @include('partials.user._userpages')
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('build/assets/js/userscript.js') }}"></script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-eMN3yD9DuvXcO1dRCmbdq3opmKHoApIdX3d4e/ee5bnDQYB6Z5M8YVM1yLAp9nZz" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"
        integrity="sha384-1nEJ7My9VmRDubOWnSh0T+gOHMxnWquyLR+Bk6wDlhqTTqBoE4OazSK7+cG6BejH" crossorigin="anonymous">
    </script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        });
    </script>
</body>

</html>
