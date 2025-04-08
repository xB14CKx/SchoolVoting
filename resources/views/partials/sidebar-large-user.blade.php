<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Inter", sans-serif;
  }

  /* Sidebar container */
  .sidebar-large {
    width: 250px;
    height: 100vh;
    padding: 36px 24px;
    display: flex;
    flex-direction: column;
    gap: 50px;
    background-color: rgba(30, 30, 30, 0.71);
    position: fixed;
    left: 0;
    top: 0;
    z-index: 999;
    transition: transform 0.3s ease;
  }

  .sidebar-large.hidden {
    transform: translateX(-100%);
  }

  .sidebar-large.show {
    transform: translateX(0);
  }

  .sidebar-header {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: -10px;
  }

  .logo {
    width: 60px;
    height: 74px;
    object-fit: contain;
    margin-left: -10px;
  }

  .system-title {
    color: white;
    font-family: "Inter", sans-serif;
    font-size: 15.5px;
    font-weight: 700;
    text-align: center;
    margin-left: -11px;
  }

  .chevron-icon:hover {
    color: #ffd700;
  }

  /* Navigation menu */
  .elect-button,
  .count-button,
  .results-button,
  .userinfo-button,
  .logout-button {
    display: inline-block;
    margin-top: -15px;
    padding: 10px 24px;
    background-color: white;
    color: black;
    font-family: 'Inter', sans-serif;
    font-size: 16px;
    font-weight: bold;
    border: 1px solid black;
    border-radius: 6px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s, color 0.3s;
  }

  .elect-button {
    margin-top: 40px;
  }

  .logout-button {
    margin-top: 140px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px 24px;
    background-color: white;
    color: black;
    font-family: 'Inter', sans-serif;
    font-size: 16px;
    font-weight: bold;
    border: 1px solid black;
    border-radius: 6px;
    text-align: center;
    transition: background-color 0.3s, color 0.3s;
  }

  .elect-button:hover,
  .count-button:hover,
  .results-button:hover,
  .userinfo-button:hover,
  .logout-button:hover {
    background-color: gold;
    color: black;
  }

  /* Responsive styles */
  @media (max-width: 991px) {
    .sidebar {
      width: 220px;
      padding: 24px 16px;
    }

    .logo {
      width: 50px;
      height: 50px;
    }

    .system-title {
      font-size: 16px;
    }

    .nav-item {
      width: 180px;
    }
  }

  @media (max-width: 640px) {
    .sidebar {
      width: 100%;
      height: auto;
      padding: 16px;
    }

    .nav-menu {
      width: 100%;
    }

    .nav-item {
      width: 100%;
    }
  }
</style>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<nav class="sidebar-large hidden" id="sidebarLarge">
  <header class="sidebar-header">
    <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/b0dd60e75b1b4e65199070c0a7e8a301213f6820" alt="CSG Logo" class="logo" />
    <h1 class="system-title">
      CSG ELECTION<br />SYSTEM
    </h1>
    <button id="closeSidebar" style="font-weight: 900; font-size: 1.3em; margin-left: 10px; color:white; cursor:pointer;">
      <i class="fa-solid fa-chevron-left chevron-icon"></i>
    </button>
  </header>

  <a href="{{ url('/elect') }}" class="elect-button"><i class="fa-solid fa-heart-circle-check" aria-hidden="true"></i> Elect</a>
  <a href="{{ url('/vote-counting') }}" class="count-button"><i class="fa-solid fa-square-poll-horizontal" aria-hidden="true"></i> Vote Counting</a>
  <a href="{{ url('/result') }}" class="results-button"><i class="fa-solid fa-check-to-slot" aria-hidden="true"></i> Results</a>
  <a href="{{ url('/userinfo') }}" class="userinfo-button"><i class="fa-solid fa-user" aria-hidden="true"></i> User Info</a>

  <!-- Replace the <a> tag with a form for logout -->
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
