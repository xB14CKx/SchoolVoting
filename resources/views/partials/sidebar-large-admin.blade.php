<head>
    <!-- Include Vite to load the sidebar-large.css -->
    @vite('resources/css/sidebar-large.css')

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
</head>

<body>
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

        <a href="{{ url('/admin') }}" class="admin-button"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> &nbsp;Admin</a>
        <a href="{{ url('/vote-counting') }}" class="count-button"><i class="fa-solid fa-square-poll-horizontal" aria-hidden="true"></i> &nbsp;Vote Counting</a>
        <a href="{{ url('/result') }}" class="results-button"><i class="fa-solid fa-check-to-slot" aria-hidden="true"></i> &nbsp;Results</a>
        <a href="{{ url('/file-upload') }}" class="upload-button"><i class="fa-solid fa-file-arrow-up"></i> &nbsp;File Upload</a>
        <form action="{{ route('logout') }}" method="post" style="display: inline;  margin-top: -70px;">
            @csrf
            <button type="submit" class="logout-button">
                <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> Log out
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
</body>
