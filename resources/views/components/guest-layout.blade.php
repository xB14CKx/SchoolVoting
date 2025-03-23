{{-- resources/views/components/guest-layout.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
/>
  @stack('styles')  <!-- Add this line -->


  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- If you have a separate topbar.css or any other custom CSS, reference it here -->
  <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">

  <!-- Scripts (Vite, etc.) -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

  <!-- custom topbar for Guest Pages -->
  @include('partials.topbar')

  <!-- Child content (your page HTML) goes here -->
  {{ $slot }}

  <!-- Optional: if you still need Bootstrap JS or other scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
