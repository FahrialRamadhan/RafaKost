<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    @include('layouts.navigation-admin')
    <div class="container mx-auto py-10">
        @yield('content')
    </div>

</body>
</html>