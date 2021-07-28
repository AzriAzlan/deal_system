<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}

?>
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>


</head>

<body class="bg">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ms_MY/sdk.js#xfbml=1&version=v11.0"
        nonce="4R0xQADw"></script>
    <!--navigation-->
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="#">DealShare</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../newDeal/userIntReg1.php">Register Deal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Saved Deals</a>
                </li>
                <li class="nav-item">
                    <div class="dropdown show">
                        <a href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><img width=35;
                                src="https://icon-library.com/images/profile-icon-white/profile-icon-white-3.jpg"></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="../profile/profilePage.php">My Profile</a>
                            <a class="dropdown-item" href="../logout.php">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>

    </nav>

    <header class="page-header header container-fluid">

        <div class="description">
            <h1>DealShare</h1>
            <p>Browse , Share & Earn Points <span id="datetime"></span></p>

            <form style="display:inline-block;" class="form-inline my-2 my-lg-0" method="POST">
                <input style="width: 50vw;" class="form-control mr-sm-2" type="search" placeholder="Search saved deals"
                    aria-label="Search" name="dealID">
                <button class="btn my-2 my-sm-0" type="submit"
                    style="border-radius:10px; height:35px; background-color:white"><i
                        class="fa fa-search"></i></button>
            </form>

        </div>

    </header>

    <!--Content-->
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="dropdown col-lg-12 d-flex justify-content-end" style="margin-top:5px;">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort by:
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <form method="POST">
                        <input type="submit" class="dropdown-item" name="DealName" value="Name"></input>
                        <input type="submit" class="dropdown-item" name="DealID" value="ID"></input>
                    </form>
                </div>
            </div>
            <?php
                include "dealsharelogic.php"
            ?>
        </div>
    </div>

</body>

</html>
