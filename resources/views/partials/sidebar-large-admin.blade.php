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
  
    .admin-button {
      display: inline-block;
      margin-top: 40px;
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
    
    .admin-button:hover {
      background-color: gold;
      color: black;
    }
  
    .count-button {
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
    
    .count-button:hover {
      background-color: gold;
      color: black;
    }
  
    .results-button {
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
    
    .results-button:hover {
      background-color: gold;
      color: black;
    }
    
    .logout-button {
      display: inline-block;
      margin-top: 140px;
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
       <links
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
        rel="stylesheet"
      />
      <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      />
  
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
      <a href="{{ url('/login') }}" class="logout-button"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i> &nbsp;Log Out</a>
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
    