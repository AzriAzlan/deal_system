<?php
session_start();
require_once "../pdo.php";
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}


      if(isset($_POST['submit'])) {

      $_SESSION['companyName'] = $_POST['companyName'];  
      $_SESSION['companyAddress'] = $_POST['companyAddress']; 
      $_SESSION['companyPostcode'] = $_POST['companyPostcode']; 
      $_SESSION['companyCountry'] = $_POST['companyCountry'];  

      $_SESSION['dealName'] = $_POST['dealName'];  
      $_SESSION['dealDescription'] = $_POST['dealDescription'];  
      $_SESSION['tagLine'] = $_POST['tagLine'];  
      $_SESSION['landingPage'] = $_POST['landingPage'];  

      $_SESSION['rewardUnit'] = $_POST['rewardUnit'];  
      $_SESSION['rewardAmount'] = $_POST['rewardAmount'];  
      $_SESSION['countryList'] = $_POST['countryList'];  
      $_SESSION['validity'] = $_POST['validity']; 

      header('Location: userIntReg4.php');

      }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Deal Registration Page</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.7/firebase-firestore.js"></script>

</head>

<body>


    <div>

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
                        <a class="nav-link" href="#">Register Deal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../savedDeal/dealShare.php">Saved Deals</a>
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

    </div>


    <div class="container-fluid h-100">
        <div class="row h-100">

            <!--Left panel with name and phone number-->
            <div class="col-md-2" id="leftPanel">
                <img src="profileIcon.png" width="50" height="50">
                <h3 id="userName"><?php echo($_SESSION["username"]); ?></h3>
                <?php 
                  $stmts = $pdo->query('SELECT user_number from users_information where user_id='.$_SESSION['user_id'].'');
                  $result = $stmts->fetchAll(PDO::FETCH_ASSOC);
                  foreach($result as $row){
                    echo '<p id="userPhone">'.htmlentities($row['user_number']).'</p>';
                  }
                ?>
            </div>


            <div class="col-md-10">
                <h3 style="padding: 20px">Deal registration page</h3>

                <div class="container-fluid">
                    <div class="row h-100">

                        <div class="col-md-3">

                            <!--Stepper for progress-->
                            <div class="stepper d-flex flex-column mt-5 ml-2">

                                <!--Step 1-->
                                <div class="d-flex mb-1">
                                    <div class="d-flex flex-column pr-4 align-items-center">
                                        <div class="img-thumbnail py-2 px-3 mb-1 boxProgress">1</div>
                                        <div class="line h-100"></div>
                                    </div>
                                    <div>
                                        <h5 class="text-dark">Company Information</h5>
                                        <p class="lead text-muted pb-3">Tell us about your company!</p>
                                    </div>
                                </div>

                                <!--Step 2-->
                                <div class="d-flex mb-1">
                                    <div class="d-flex flex-column pr-4 align-items-center">
                                        <div class="img-thumbnail py-2 px-3 mb-1 boxProgress">2</div>
                                        <div class="line h-100"></div>
                                    </div>
                                    <div>
                                        <h5 class="text-dark">Deal Information</h5>
                                        <p class="lead text-muted pb-3">Tell us about the deal!</p>
                                    </div>
                                </div>

                                <!--Step 3-->
                                <div class="d-flex mb-1">
                                    <div class="d-flex flex-column pr-4 align-items-center">
                                        <div class="img-thumbnail py-2 px-3 mb-1 boxProgress">3</div>
                                        <div class="line h-100"></div>
                                    </div>
                                    <div>
                                        <h5 class="text-dark">Reward & validity information</h5>
                                        <p class="lead text-muted pb-3">Tell us about the rewards!</p>
                                    </div>
                                </div>

                                <!--Step 4-->

                                <div class="d-flex mb-1">
                                    <div class="d-flex flex-column pr-4 align-items-center">
                                        <div class="img-thumbnail py-2 px-3 mb-1 boxProgress">4</div>
                                        <div class="line h-100 d-none"></div>
                                    </div>
                                    <div>
                                        <h5 class="text-dark">Review and confirm</h5>
                                        <p class="lead text-muted pb-3">Submit</p>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!--Registration Form-->
                        <div class="col-md-9">

                            <form id="registrationForm" action="userIntReg1.php" method="POST"
                                enctype="multipart/form-data">


                                <!--Company information tab-->
                                <div class="row tab" id="step-1">

                                    <div class="col-md-12">

                                        <h3 style="text-align: center;" id="registrationHead">Tell us about your company
                                            !</h3>

                                        <div class="form-group">
                                            <label for="companyName">Company name</label>
                                            <input id="companyName" name="companyName" type="text" class="form-control"
                                                oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="companyAddress">Company address</label>
                                            <input id="companyAddress" name="companyAddress" type="text"
                                                class="form-control" oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="companyPostcode">Company postcode</label>
                                            <input id="companyPostcode" name="companyPostcode" type="text"
                                                class="form-control" oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="companyCountry">Company country</label>
                                            <select id="companyCountry" name="companyCountry">
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Indonesia">Indonesia</option>
                                            </select>
                                        </div>

                                    </div>

                                </div>


                                <!--Deal information tab-->
                                <div class="row tab" id="step-2">

                                    <div class="col-md-12">

                                        <h3 style="text-align: center;" id="registrationHead">Tell us about the deal !
                                        </h3>

                                        <div class="form-group">
                                            <label for="dealName">Deal name</label>
                                            <input id="dealName" type="text" name="dealName" class="form-control"
                                                oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="dealDescription">Deal description</label>
                                            <input id="dealDescription" type="text" name="dealDescription"
                                                class="form-control" oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="tagLine">Catchy tag line</label>
                                            <input id="tagLine" type="text" name="tagLine" class="form-control"
                                                oninput="this.className = 'form-control'">
                                        </div>

                                        <div class="form-group">
                                            <label for="landingPage">Landing page link</label>
                                            <input id="landingPage" type="text" name="landingPage" class="form-control"
                                                oninput="this.className = 'form-control'">
                                        </div>

                                    </div>
                                </div>


                                <!--Reward information tab-->
                                <div class="row tab" id="step-3">

                                    <div class="col-md-12">

                                        <h3 style="text-align: center;" id="registrationHead">Tell us about the REWARDS
                                            !</h3>

                                        <div class="form-group">
                                            <label for="rewardUnit">Reward unit</label>
                                            <select name="rewardUnit" id="rewardUnit">
                                                <option value="Money">Money</option>
                                                <option value="Percentage">Percentage</option>
                                                <option value="PromoCode">Promo code</option>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="rewardAmount">Reward amount</label>
                                            <input id="rewardAmount" name="rewardAmount" type="text"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="countryList">Country list</label>
                                            <input id="countryList" name="countryList" type="text" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="validity">Valid until</label>
                                            <input id="validity" name="validity" type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>


                                <!--Previous and next buttons-->
                                <div style="overflow: auto;">
                                    <button class="btn btn-indigo btn-rounded nextBtn float-left" type="button"
                                        id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                    <button class="btn btn-indigo btn-rounded nextBtn float-right" type="button"
                                        id="nextBtn" onclick="nextPrev(1)">Next</button>
                                    <input type="submit" class="btn btn-indigo btn-rounded nextBtn float-right"
                                        name="submit" id="checkBtn" value="Review">
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="dealRegistration.js"></script>
    <script src="save.js"></script>


</body>

</html>