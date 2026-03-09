<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laundry POS System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        <!-- Main Content -->
        <main class="w-full">
            @yield('content')
        </main>
    </div>
</body>
</html>
