<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="Find reliable house helps for cleaning, laundry, and skilled workers for home maintenance. Book trusted professionals near you for hassle-free home services.">
    <meta name="keywords" content="house, help, cleaning, house chores">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Request::segment(1) }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('build/assets/image/logo/ohh-log.png') }}">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title"
        content="{{ Request::segment(1) ? Request::segment(1) : 'Get house helps to do cleanings' }}">
    <meta property="og:description"
        content="Find reliable house helps for cleaning, laundry, and skilled workers for home maintenance. Book trusted professionals near you for hassle-free home services.">
    <meta property="og:image" content="{{ asset('build/assets/image/logo/ohh-log.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title"
        content="{{ Request::segment(1) ? Request::segment(1) : 'Get house helps to do cleanings' }}">
    <meta name="twitter:description"
        content="Find reliable house helps for cleaning, laundry, and skilled workers for home maintenance. Book trusted professionals near you for hassle-free home services.">
    <meta name="twitter:image" content="{{ asset('build/assets/image/logo/ohh-log.png') }}">

    <!-- CSS & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('build/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</head>
