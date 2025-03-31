<!-- resources/views/layouts/guest-layout.blade.php -->
<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2Lw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@vite(['resources/css/topbar.css', 'resources/js/app.js', 'resources/js/topbar.js'])

<nav class="navbar">
    <ul class="nav-links">
        <li class="nav-item">
            <a href="{{ route('about') }}"><i class="fa-solid fa-question"></i> About</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contact') }}"><i class="fa-solid fa-address-book"></i> Contact</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('login') }}">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span style="color: #FFdF61;">Login</span>
            </a>
        </li>
    </ul>
    <!-- Hamburger Menu Button -->
    <button class="hamburger">☰</button>
</nav>