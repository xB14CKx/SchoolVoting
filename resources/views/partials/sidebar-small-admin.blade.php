<style>
    .sidebar-small {
      width: 90px;
      height: 100vh;
      background-color: rgba(30, 30, 30, 0.71);
      overflow: hidden;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 999;
      transition: transform 0.3s ease;
    }

    .sidebar-small.hidden {
      transform: translateX(-100%);
    }


        .sidebar-content {
          display: flex; /*do this*/
          width: 115%;
          padding: 20px 19px 493px;
          flex-direction: column;
          align-items: center;
        }

        .logo {
          object-fit: contain;
          object-position: center;
          width: 150px;
          height: 74px;
          margin-right: 15px;
          overflow: hidden;
        }

        .largesb-icon {
          color: white;
          font-size: 25px;
          margin-top: 22px;
          margin-right: 20px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .largesb-icon:hover {
          color: #ffd700;
        }


        .admin-icon {
          color: white;
          font-size: 25px;
          margin-top: 80px;
          margin-right: 20px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .admin-icon:hover {
          color: #ffd700;
        }

        .count-icon {
          font-size: 25px;
          color: white;
          margin-right: 20px;
          margin-top: 50px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .count-icon:hover {
          color: #ffd700;
        }

        .result-icon {
          font-size: 25px;
          color: white;
          margin-right: 20px;
          margin-top: 50px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .result-icon:hover {
          color: #ffd700;
        }

        .reports-icon {
          font-size: 25px;
          color: white;
          margin-right: 20px;
          margin-top: 50px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .reports-icon:hover {
          color: #ffd700;
        }

        .upload-icon {
          font-size: 25px;
          color: white;
          margin-right: 20px;
          margin-top: 50px;
          margin-bottom: -10px;
          transition: color 0.2s ease;
        }

        .upload-icon:hover {
          color: #ffd700;
        }


        .logout-icon {
        color: white;
        font-size: 25px;
        margin-right: 25px;
        margin-top: 200px;
        }


        </style>

            <link
              rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            />

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

             <a href= "{{ url('/login') }}"><i class="fa-solid fa-arrow-right-from-bracket logout-icon"
              aria-hidden= "true"></i>
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

