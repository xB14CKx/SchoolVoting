<!-- resources/views/votings/partials/sidebar-small.blade.php -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<aside id="sidebarSmall" class="sidebar-small">
  <nav class="sidebar-content">
    <header class="sidebar-header">
      <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/7f4561b0b97736936427a02cc5505f499830f0d7?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
           alt="Logo" class="logo" />
      <button id="openLargeSidebar" style="font-weight: 900; font-size: 1.3em; margin-left: 10px; color:white; cursor:pointer;">
        <i class="fa-solid fa-chevron-right chevron-icon"></i>
      </button>
    </header>

    @auth
      @if ($isAdmin)
        <!-- Admin-specific links -->
        <a href="{{ url('/admin') }}" class="admin-button">
          <i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Admin
        </a>
        <a href="{{ url('/file-upload') }}" class="upload-button">
          <i class="fa-solid fa-file-arrow-up" aria-hidden="true"></i> File Upload
        </a>
      @endif

      <!-- Shared links for both admin and user -->
      <a href="{{ url('/vote-counting') }}" class="count-button">
        <i class="fa-solid fa-square-poll-horizontal" aria-hidden="true"></i> Vote Counting
      </a>
      <a href="{{ url('/result') }}" class="results-button">
        <i class="fa-solid fa-check-to-slot" aria-hidden="true"></i> Results
      </a>

      @if (!$isAdmin)
        <!-- User-specific links -->
        <a href="{{ url('/elect') }}" class="elect-button">
          <i class="fa-solid fa-heart-circle-check" aria-hidden="true"></i> Elect
        </a>
        <a href="{{ url('/userinfo') }}" class="userinfo-button">
          <i class="fa-solid fa-user" aria-hidden="true"></i> User Info
        </a>
      @endif

    @endauth

    <!-- Logout link (visible to both admin and user when authenticated) -->
    <a href="{{ url('/logout') }}" class="logout-button">
      <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> Log Out
    </a>
  </nav>
</aside>

<script>
  document.getElementById('openLargeSidebar').addEventListener('click', function () {
    document.getElementById('sidebarSmall').style.display = 'none';
    document.getElementById('sidebarLarge').style.display = 'flex';
    document.getElementById('mainContent').style.marginLeft = '300px'; // match large sidebar width
  });
</script>
