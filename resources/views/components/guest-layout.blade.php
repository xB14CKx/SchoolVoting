<!-- resources/views/components/guest-layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Include Font Awesome (match version with app-layout.blade.php) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2Lw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Additional Fonts (centralized for all guest pages) -->
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&family=Inria+Sans:wght@300;400;700&family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">

    <!-- Vite assets (include topbar.css and topbar.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/topbar.css', 'resources/js/topbar.js'])

    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased">
    <!-- Custom top bar for Guest Pages -->
    @include('partials.topbar')

    <!-- Child content (your page HTML) goes here -->
    <main>
        {{ $slot }}
    </main>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>