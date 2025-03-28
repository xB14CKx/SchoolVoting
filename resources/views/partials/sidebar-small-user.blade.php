<style>
    .sidebar-small {
      max-width: 90px;
      max-height: 737px;
      background-color: rgba(30, 30, 30, 0.71);
      overflow: hidden;
      position: fixed;  
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
      margin-right: 20px;
      overflow: hidden;
    }
    
    .nav-icon {
      aspect-ratio: 1;
      object-fit: contain;
      object-position: center;
      width: 29px;
      margin-top: 22px;
      margin-right: 20px;
      overflow: hidden;
    }
    
    .utility-icon {
      aspect-ratio: 1;
      object-fit: contain;
      object-position: center;
      width: 25px;
      margin-right: 20px;
      margin-top: 62px;
      overflow: hidden;
    }
    
    .utility-icon:first-of-type {
      margin-top: 162px;
      margin-right: 20px;

    }
    
    .utility-icon:last-of-type {
      margin-top: 70px;
      margin-right: 20px;

    }
    
    .settings-icon {
      aspect-ratio: 1;
      object-fit: contain;
      object-position: center;
      width: 25px;
      margin-top: 66px;
      margin-right: 20px;
      margin-bottom: -99px;
      overflow: hidden;
    }
    
    .heart-icon {
      color: white;
      font-size: 25px;
      margin-top: 75px;
      margin-right: 20px;
      margin-bottom: -10px;
    }

   /* .about-icon {
    color: white;
    font-size: 18px;
    margin-right: 25px;
    margin-top: 170px;
    }

    .contact-icon {
    color: white;
    font-size: 18px;
    margin-right: 25px;
    margin-top: 64px;
    } */

    .logout-icon {
    color: white;
    font-size: 25px;
    margin-right: 25px;
    margin-top: 240px;
    } 
    
    
    </style>
    
        <link
          rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
 
        <aside class="sidebar-small">
          <nav class="sidebar-content">
    
            
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/7f4561b0b97736936427a02cc5505f499830f0d7?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
              class="logo"
              alt="Logo"
            />

            <img
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/15d0910f1802cc48b2e90b0a1b75216907fe9bd6?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
            class="nav-icon"
            alt="Navigation icon"
          />
    
          <a href="{{ url('/elect') }}">
            <i class="fa-solid fa-heart-circle-check heart-icon" aria-hidden="true"></i>
          </a>
            
          <a href="{{ url('/vote-counting') }}">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/57708c2b7bf18a80cfb5c049ee5efbdd794c2ae8?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
              class="utility-icon"
              alt="Utility icon"
            />
          </a>
          
          <a href="{{ url('/result') }}">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/6e88b81a2cf713f126372d0a85ff0787f282d218?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
              class="utility-icon"
              alt="Utility icon"
            />
          </a>
          <a href="{{ url('/userinfo') }}">
            <img
              src="https://cdn.builder.io/api/v1/image/assets/TEMP/85809c2382e35266a17338b3a5c79ad01ffb9ee6?placeholderIfAbsent=true&apiKey=ddaccfec04494b429f8d3267955938e3"
              class="settings-icon"
              alt="Settings icon"
            />
          </a>

           <!-- <i
            class="fa-solid fa-circle-info about-icon"
            aria-hidden="true"
          ></i>
  
          <i
            class="fa-solid fa-address-book contact-icon"
            aria-hidden="true"
          ></i> -->

          <i class="fa-solid fa-arrow-right-from-bracket logout-icon"
          aria-hidden= "true"></i>
  
          </nav>
        </aside>
