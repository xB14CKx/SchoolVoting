<!-- resources/views/votings/partials/sidebar-large.blade.php
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

    @if ($isAdmin)
        <a href="{{ url('/admin') }}" class="admin-button">
            <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>  Admin
        </a>
    @endif

    <a href="{{ url('/vote-counting') }}" class="count-button">
        <i class="fa-solid fa-square-poll-horizontal" aria-hidden="true"></i>  Vote Counting
    </a>
    <a href="{{ url('/result') }}" class="results-button">
        <i class="fa-solid fa-check-to-slot" aria-hidden="true"></i>  Results
    </a>
    <a href="{{ url('/reports') }}" class="reports-button">
        <i class="fa-solid fa-chart-pie"></i>  Reports
    </a>
    @if ($isAdmin)
        <a href="{{ url('/file-upload') }}" class="upload-button">
            <i class="fa-solid fa-file-arrow-up"></i>  File Upload
        </a>
    @endif
    <a href="{{ url('/login') }}" class="logout-button">
        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>  Log Out
    </a>
</nav>
