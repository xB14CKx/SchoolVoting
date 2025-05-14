<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<nav class="navbar">
    <!-- Logo Container -->
<div class="logo-container">
    <a href="{{ route('home') }}" hx-get="{{ route('home') }}" hx-target="body" hx-swap="outerHTML"
        hx-push-url="true">
        <i class="fa-solid fa-house logo-icon"></i>
    </a>
</div>

    <ul class="nav-links">
        <li class="nav-item">
            <a href="{{ route('about') }}" hx-get="{{ route('about') }}" hx-target="body" hx-swap="outerHTML"
                hx-push-url="true">
                <i class="fa-solid fa-question"></i> &nbsp; About
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contact') }}" hx-get="{{ route('contact') }}" hx-target="body" hx-swap="outerHTML"
                hx-push-url="true">
                <i class="fa-solid fa-address-book"></i> &nbsp; Contact
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('login') }}" hx-get="{{ route('login') }}" hx-target="body" hx-swap="outerHTML"
                hx-push-url="true">
                <i class="fa-solid fa-right-to-bracket"></i> &nbsp; Login</span>
            </a>
        </li>
    </ul>
    <!-- Hamburger Menu Button -->
    <button class="hamburger">☰</button>
</nav>