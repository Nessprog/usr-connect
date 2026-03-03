<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'USR Connect') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo.png">
    <!-- Iphone -->
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">
    <!-- Android -->
    <link rel="icon" type="image/png" sizes="192x192" href="/apple-touch-icon.png">

    <link rel="icon" type="image/png" href="/apple-touch-icon.png">

    <meta name="apple-mobile-web-app-title" content="USR Connect">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <meta name="theme-color" content="#6B21A8">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>