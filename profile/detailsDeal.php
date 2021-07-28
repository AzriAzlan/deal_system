<html>

<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="mycss.css">
    <!-- Load icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous">
    </script>
</head>

<body class="bg">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ms_MY/sdk.js#xfbml=1&version=v11.0"
        nonce="4R0xQADw"></script>
<!--navigation-->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="#">DealShare</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../home/homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../newDeal/userIntReg1.php">Register Deal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../savedDeal/dealShare.php">Saved Deals</a>
                </li>
                 <li class="nav-item">

                     <div class="dropdown show">
                    <a href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img width=35; src="https://icon-library.com/images/profile-icon-white/profile-icon-white-3.jpg"></a>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="../profile/profilePage.php">My Profile</a>
                    <a class="dropdown-item" href="../logout.php">Logout</a>
                    </div>
                    </div>
                    
                </li>

            </ul>
          
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-link active fs-5 fw-bold" aria-current="page" href="profilepage.php">Profile Page</a>
                    <?php
          
                      echo '<div class="align-self-center"><img src="https://image.flaticon.com/icons/png/512/271/271228.png" style="width: 15px; height: 15px;"/></div>';
                      echo '<a class="nav-link active fs-5 fw-bold"  href="postedDeals.php">Posted Deals</a>';
                      echo '<div class="align-self-center"><img src="https://image.flaticon.com/icons/png/512/271/271228.png" style="width: 15px; height: 15px;"/></div>';
                      echo '<a class="nav-link active fs-5 fw-bold" aria-current="page">Deal</a>';

                
                     ?>
                  </div>
                </div>
              </div>
            </nav>

    <!--Content-->
    <div class="container-fluid">
        <div class="d-flex justify-content-center">
            <div class="row content" style="border-top:solid darkcyan 10px; width:80%; border-radius:10px; background-color:white">
                <?php
                    include "detailsDeallogic.php"
                ?>
            </div>
        </div>
    </div>
</body>
</html>