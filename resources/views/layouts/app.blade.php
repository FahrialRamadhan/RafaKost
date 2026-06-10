<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rafakost') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}?v=2" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2" type="image/x-icon">
	
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- PAGE LOADER STYLE --}}
    <style>
        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .35s ease, visibility .35s ease;
        }

        .page-loader.hide {
            opacity: 0;
            visibility: hidden;
        }

        .loader-logo {
            width: 86px;
            height: 86px;
            object-fit: contain;
            animation: loaderPulse 1.2s ease-in-out infinite;
        }

        @keyframes loaderPulse {
            0% {
                transform: scale(1);
                opacity: .65;
            }

            50% {
                transform: scale(1.08);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: .65;
            }
        }
    </style>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="@yield('body_class', 'bg-gray-200')">

{{-- PAGE LOADER --}}
<div id="pageLoader" class="page-loader">
    <img src="{{ asset('images/logo123.png') }}" alt="Rafa Kost" class="loader-logo">
</div>

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

{{-- PAGE LOADER SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('pageLoader');

        if (loader) {
            setTimeout(function () {
                loader.classList.add('hide');
            }, 400);
        }
    });
</script>

</body>

</html>