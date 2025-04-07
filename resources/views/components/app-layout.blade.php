<!-- resources/views/components/app-layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2Lw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&family=Inria+Sans:wght@300;400;700&family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/sidebar.css', 'resources/css/sidebar-large.css'])

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- Flex container for layout -->
    <div class="flex min-h-screen bg-gray-100">
        <!-- Small Sidebar -->
        @auth
            @include('votings.partials.sidebar-small', ['isAdmin' => auth()->user()->role === 'admin'])
        @else
            @include('votings.partials.sidebar-small', ['isAdmin' => false])
        @endauth

        <!-- Large Sidebar -->
        @auth
            @include('votings.partials.sidebar-large', ['isAdmin' => auth()->user()->role === 'admin'])
        @else
            @include('votings.partials.sidebar-large', ['isAdmin' => false])
        @endauth

        <!-- Main Content Area -->
        <div id="mainContent" class="flex-1 transition-all duration-300">
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn = document.querySelector('.largesb-icon');
            const closeBtn = document.getElementById('closeSidebar');
            const smallSidebar = document.querySelector('.sidebar-small');
            const largeSidebar = document.getElementById('sidebarLarge');
            const mainContent = document.getElementById('mainContent');

            openBtn?.addEventListener('click', () => {
                smallSidebar.style.display = 'none';
                largeSidebar.classList.remove('hidden');
                largeSidebar.classList.add('show');
                mainContent.style.marginLeft = '250px'; // Match sidebar-large width
            });

            closeBtn?.addEventListener('click', () => {
                smallSidebar.style.display = 'block';
                largeSidebar.classList.remove('show');
                largeSidebar.classList.add('hidden');
                mainContent.style.marginLeft = '0px';
            });
        });
    </script>
</body>
</html>
