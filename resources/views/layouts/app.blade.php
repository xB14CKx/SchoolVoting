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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


  {{-- Vite, etc. --}}
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/sidebar.css', 'resources/css/sidebar-large.css'], 'resources/js/sidebar.js')
  @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">

  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
