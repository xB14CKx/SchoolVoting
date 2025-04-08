<a!-- resources/views/components/app-layout.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getlocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'laravel') }}</title>

    <!-- include font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-dtoqo9rwch3ppgqcwaea1bizoc6xxalwesw9c2qqeaiftl+vegovlnee1c9qx4tctnwmn13tzye+gimm8e2lw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=istok+web&family=inria+sans:wght@300;400;700&family=inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=roboto:wght@200;400;700&display=swap" rel="stylesheet">

    <!-- vite assets -->
    @vite([ 'resources/js/app.js', 'resources/css/sidebar.css', 'resources/css/sidebar-large.css', 'resources/js/sidebar.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- flex container: sidebar on the left, main content on the right -->
    <div class="flex min-h-screen bg-gray-100">
        <!-- include the sidebar partial -->
        @include('partials.sidebar-small')

        <!-- main content area -->
        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
