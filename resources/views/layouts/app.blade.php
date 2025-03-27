{{-- resources/views/components/app-layout.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  {{-- Include your partial-based CSS, if any --}}
  <link rel="stylesheet" href="{{ asset('css/sidebar-small-user.css') }}">
  <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
  
  {{-- Vite, etc. --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">

  {{-- 1) Topbar up top --}}
{{--  @include('partials.topbar-user')--}}

  {{-- 2) Flex container for sidebar + main content --}}
  <div class="flex min-h-screen">
    {{-- The sidebar on the left --}}
    @include('partials.sidebar-small-user')

    {{-- The main content area takes remaining space --}}
    <div class="flex-1">
      {{ $slot }}
    </div>
  </div>

  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/htmx.org@2.0.4"></script>
</body>
</html>
