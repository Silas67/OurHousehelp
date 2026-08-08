<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



    <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">







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
                <a class="navbar-brand" href="/"><img width="130px"
                        src="{{ asset('build/assets/image/logo/ohh-log-b.png') }}" alt="Logo"></a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('build/assets/js/adminscript.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        });
    </script>

</body>

</html>
