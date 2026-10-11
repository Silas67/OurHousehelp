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
    {{-- Bootstrap 5.3.3 + FontAwesome 6, self-hosted. jQuery slim was removed:
         Bootstrap 5 dropped jQuery plugin support, so the only jQuery call under
         this layout (the feedback modal in main.blade.php) could never have
         worked. It is now driven by the native Bootstrap 5 API. --}}
    @vite(['resources/css/ui.css', 'resources/js/ui.js'])

    {{-- PWA: installable app manifest + theme colour --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2A2D5A">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <link href="{{ asset('build/assets/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
