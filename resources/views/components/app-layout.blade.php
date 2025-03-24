<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  
  
  <!-- Vite assets and global CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  
  @stack('styles')
</head>
<body class="font-sans antialiased">

  <!-- Flex container: sidebar on the left, main content on the right -->
  <div class="flex min-h-screen bg-gray-100">
    {{-- Include the sidebar partial from resources/views/partials/sidebar-small-user.blade.php --}}
    @include('partials.sidebar-small-user')

    <!-- custom topbar for authenticated pages -->
     @include('partials.topbar-user')
    
    <!-- Main Content Area -->
    <div class="flex-1">
      {{ $slot }}
    </div>
  </div>
  
  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
