<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200 @yield('body_class')">

<div class="min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    @auth
        @if(auth()->user()->role === 'admin')
            @include('layouts.navigation-admin')
        @else
            @include('layouts.navigation-user')
        @endif
    @else
        @include('layouts.navigation-guest')
    @endauth

    {{-- CONTENT --}}
    <main class="flex-1">
        @yield('content')
    </main>


</div>

</body>

</html>
