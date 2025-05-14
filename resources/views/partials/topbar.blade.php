<nav class="navbar">
    <!-- Logo Container -->
    <div class="logo-container">
        <a href="{{ route('home') }}" hx-get="{{ route('home') }}" hx-target="body" hx-swap="outerHTML" hx-push-url="true" style="color: white;">
            HOME
        </a>
    </div>

    <ul class="nav-links">
        <li class="nav-item">
            <a href="{{ route('about') }}" hx-get="{{ route('about') }}" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="fa-solid fa-question"></i> About
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contact') }}" hx-get="{{ route('contact') }}" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="fa-solid fa-address-book"></i> Contact
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('login') }}" hx-get="{{ route('login') }}" hx-target="body" hx-swap="outerHTML" hx-push-url="true">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span style="color: #FFdF61;">Login</span>
            </a>
        </li>
    </ul>
    <!-- Hamburger Menu Button -->
    <button class="hamburger">☰</button>
</nav>
