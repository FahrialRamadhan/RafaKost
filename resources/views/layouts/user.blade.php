<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-00">
    @include('layouts.navigation-user')
    <div class="pt-10 container mx-auto">
        @yield('content')
    </div>

</body>
</html>