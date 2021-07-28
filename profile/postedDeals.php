<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}
require_once "../pdo.php";

$uid=$_SESSION['user_id'];

$stmts = $pdo->query("SELECT d.deal_id,d.deal_name, d.deal_logo, d.promo_code, d.tagLine, d.reward, d.reward_unit, d.description FROM deal d inner join deal_page p on d.deal_id=p.deal_id where p.user_id=$uid");
    $result = $stmts->fetchAll(PDO::FETCH_ASSOC);
?>

<head>

	  <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
      <script src="../home/home.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

</head>
<body>


  <!--navigation-->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
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
                       echo '<a class="nav-link active fs-5 fw-bold" aria-current="page" href="postedDeals.php">Posted Deals</a>';
                
                     ?>
                  </div>
                </div>
              </div>
            </nav>

    <div class="row">
        <?php
        foreach ($result as $row ) {

                $dealnum=htmlentities($row['deal_id']);
                echo
                '<div class="col-lg-2 card content" style="background-color:white; border-bottom:solid blue 5px" onclick="details(\''.$dealnum.'\')">
                        <img height=120 width=110 src="data:image/jpeg;base64,'.base64_encode($row['deal_logo']).'"/ class="mx-auto d-block">
                        <div class="card-body" style="height:11rem;">  
                            <h5 class="card-title" style="color:black; text-transform:uppercase; text-align:center; border-top-style:solid;border-bottom-style:solid;">'. htmlentities($row['deal_name']) . '</h5>
                            <p class="card-text" style="">'. htmlentities($row['tagLine']) . '</p>
                        </div>
                </div>';  
            } 
        ?>
    </div>


</body>