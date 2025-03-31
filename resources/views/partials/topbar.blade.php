<nav class="navbar">
    <!-- Logo Container -->
    <div class="logo-container">
        <a href="{{ route('home') }}"
           hx-get="{{ route('home') }}"
           hx-target="body"
           hx-swap="outerHTML"
           hx-push-url="true">
            <img src="{{ asset('images/csglogo_nobg.ico') }}" alt="CSG Logo" class="logo">
        </a>
    </div>

    <ul class="nav-links">
        @if (auth()->check())
            <!-- Authenticated User Links -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   hx-get="{{ route('dashboard') }}"
                   hx-target="body"
                   hx-swap="outerHTML"
                   hx-push-url="true">
                    <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('elect') }}"
                   hx-get="{{ route('elect') }}"
                   hx-target="body"
                   hx-swap="outerHTML"
                   hx-push-url="true">
                    <i class="fa-solid fa-vote-yea"></i> Elect
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <span style="color: #FFdF61;">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        @else
            <!-- Guest User Links -->
            <li class="nav-item">
                <a href="{{ route('about') }}"
                   hx-get="{{ route('about') }}"
                   hx-target="body"
                   hx-swap="outerHTML"
                   hx-push-url="true">
                    <i class="fa-solid fa-question"></i> About
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('contact') }}"
                   hx-get="{{ route('contact') }}"
                   hx-target="body"
                   hx-swap="outerHTML"
                   hx-push-url="true">
                    <i class="fa-solid fa-address-book"></i> Contact
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('login') }}"
                   hx-get="{{ route('login') }}"
                   hx-target="body"
                   hx-swap="outerHTML"
                   hx-push-url="true">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span style="color: #FFdF61;">Login</span>
                </a>
            </li>
        @endif
    </ul>
    <!-- Hamburger Menu Button -->
    <button class="hamburger">☰</button>
</nav>