<!-- resources/views/votings/partials/sidebar-large-user.blade.php -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<nav class="sidebar-large hidden" id="sidebarLarge">
    <header class="sidebar-header">
        <img
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/b0dd60e75b1b4e65199070c0a7e8a301213f6820"
            alt="CSG Logo"
            class="logo"
        />
        <h1 class="system-title">
            CSG ELECTION<br />SYSTEM
        </h1>
        <button id="closeSidebar" style="font-weight: 900; font-size: 1.3em; margin-left: 10px; color:white; cursor:pointer;">
            <i class="fa-solid fa-chevron-left chevron-icon"></i>
        </button>
    </header>

    @auth
        @if (auth()->user()->role === 'admin')
            <!-- Admin-specific link -->
            <a href="{{ url('/admin') }}" class="admin-button">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Admin
            </a>
        @else
            <!-- User-specific links -->
            <a href="{{ url('/elect') }}" class="elect-button">
                <i class="fa-solid fa-heart-circle-check" aria-hidden="true"></i> Elect
            </a>
            <a href="{{ url('/userinfo') }}" class="userinfo-button">
                <i class="fa-solid fa-user" aria-hidden="true"></i> User Info
            </a>
        @endif

        <!-- Shared links for both admin and user -->
        <a href="{{ url('/vote-counting') }}" class="count-button">
            <i class="fa-solid fa-square-poll-horizontal" aria-hidden="true"></i> Vote Counting
        </a>
        <a href="{{ url('/result') }}" class="results-button">
            <i class="fa-solid fa-check-to-slot" aria-hidden="true"></i> Results
        </a>
    @endauth

    <!-- Logout button -->
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="logout-button">
            <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> Log Out
        </button>
    </form>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.querySelector('.largesb-icon'); // small sidebar's chevron
        const closeBtn = document.getElementById('closeSidebar');
        const smallSidebar = document.querySelector('.sidebar-small');
        const largeSidebar = document.getElementById('sidebarLarge');
        const mainContent = document.getElementById('mainContent');

        openBtn?.addEventListener('click', () => {
            smallSidebar.style.display = 'none';
            largeSidebar.classList.add('show');
            mainContent.style.marginLeft = '0px';
        });

        closeBtn?.addEventListener('click', () => {
            smallSidebar.style.display = 'block';
            largeSidebar.classList.remove('show');
            mainContent.style.marginLeft = '0px';
        });
    });
</script>