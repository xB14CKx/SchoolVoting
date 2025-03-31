@vite(['resources/css/topbar.css'])

<nav class="navbar">
  <ul class="nav-links">
    <li class="nav-item">
      <a href="{{ route('about')}}"><i class="fa-solid fa-question"></i> &nbsp; About</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('contact')}}"><i class="fa-solid fa-address-book"></i> &nbsp; Contact</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('login') }}">
        <i class="fa-solid fa-right-to-bracket"></i> &nbsp;
        <span style="color: #FFdF61;">Login</span>
      </a>
    </li>
  </ul>
</nav>
