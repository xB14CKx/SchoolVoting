<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> CSG Election System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/Landing.css" />
    <link rel="stylesheet" type="text/css" href="./css/componentsLanding.css" />
    <link rel="stylesheet" type="text/css" href="./css/fontLanding.css" />
    <link rel="stylesheet" type="text/css" href="./css/stylesLanding.css" />
    <link rel="stylesheet" type="text/css" href="./css/indexLanding.css" />
    <link rel="stylesheet" href="{{ asset('css/Landing.css') }}">

</head>
<body>
    <!--bg image-->
    <div class="landing">
        <!--<img src="yellowlines1.png" class="yellowbg_one" />-->
       <!-- <img src="yellowlines2.png" class="second_bg" /> -->
    </div>

    <div class="row_one">
        <div class="second_bg"></div>
        <div class="row_one">
            <div class="columnabout">
                <!--nav bar-->
                <header class="header">
                    <ul class="header_menu">  
                        <li><a href="#"><p class="ui text size-textxs">About</p></a></li>
                        <li><a href="#"><p class="ui text size-textxs">Contact</p></a></li>
                        <li><a href="#"><p class="ui text size-textxs">Login</p></a></li>
                    </ul>
                </header>

                <div class="row">
                    <!--front-->
                    <div class="landing_row">
                        <img src="{{ asset('images/csg_logo.png') }}" alt="My Logo" class="csg-logo" />
                        <div class="landing_column">
                        <h1 class="landing_title ui heading size-headingxs">
                            CSG ELECTION<br><span class="system_word">SYSTEM</span></h1>
                            <div class="landing_registration-row">
                                <p class="landing_registration-text ui text size-textxs">No account yet?</p>
                                <p class="landing_registration-link ui text size-textxs">Register Here!</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of row -->
            </div>
        </div> <!-- End of row_one -->
    </div>    

    <!-- Bootstrap JS (for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    
