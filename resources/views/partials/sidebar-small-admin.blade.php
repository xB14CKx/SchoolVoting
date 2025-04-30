<head>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
</head>

<body>
    <aside id="sidebarSmall" class="sidebar-small">
        <nav class="sidebar-content">
            <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/7f4561b0b97736936427a02cc5505f499830f0d7?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
                class="logo"
                alt="Logo"
            />

            <button id="openLargeSidebar" class="largesb-icon" aria-label="Open large sidebar">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <a href="{{ url('/admin') }}">
                <i class="fa-solid fa-shield-halved admin-icon" aria-hidden="true"></i>
            </a>

            <a href="{{ url('/vote-counting') }}">
                <i class="fa-solid fa-square-poll-horizontal count-icon" aria-hidden="true"></i>
            </a>

            <a href="{{ url('/result') }}">
                <i class="fa-solid fa-check-to-slot result-icon" aria-hidden="true"></i>
            </a>

            <a href="{{ url('/reports') }}">
                <i class="fa-solid fa-chart-pie reports-icon" aria-hidden="true"></i>
            </a>

            <a href="{{ url('/file-upload') }}">
                <i class="fa-solid fa-file-arrow-up upload-icon" aria-hidden="true"></i>
            </a>

            <form action="{{ route('logout') }}" method="post" style="display: inline; margin-top: -50px;">
                @csrf
                <button type="submit" class="logout-icon" style="border: none; background: none; padding: 0; cursor: pointer;">
                    <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                </button>
            </form>
        </nav>
    </aside>

    <script>
    document.getElementById('openLargeSidebar').addEventListener('click', function () {
        document.getElementById('sidebarSmall').style.display = 'none';
        document.getElementById('sidebarLarge').style.display = 'flex';
        document.getElementById('mainContent').style.marginLeft = '300px'; // match large sidebar width
    });
    </script>
</body>
