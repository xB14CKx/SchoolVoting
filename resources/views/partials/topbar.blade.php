<!-- resources/views/partials/topbar.blade.php -->
<style>
    /* Navigation Bar Styles */
    .navbar {
      width: 100%;
      height: 60px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      background-color: rgba(0, 0, 0, 70);
    }
    
    .nav-links {
      display: flex;
      gap: 95px;
      margin-right: 36px;
      list-style: none;
      padding: 0;
    }
    
    .nav-item a {
      color: #fff;
      font-family: "Istok Web", sans-serif;
      font-size: 20px;
      font-weight: 400;
      cursor: pointer;
      text-decoration: none;
    }
    
    /* Responsive Styles */
    @media (max-width: 991px) {
      .nav-links {
        gap: 40px;
        margin-right: 20px;
      }
    }
    
    @media (max-width: 640px) {
      .nav-links {
        display: none;
      }
    }
    </style>
    
    <nav class="navbar">
      <ul class="nav-links">
        <li class="nav-item">
          <a href="#about"><i class="fa-solid fa-question"></i> &nbsp; About</a>
        </li>
        <li class="nav-item">
          <a href="#contact"><i class="fa-solid fa-address-book"></i> &nbsp; Contact</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> &nbsp; Login</a>
        </li>
      </ul>
    </nav>
    