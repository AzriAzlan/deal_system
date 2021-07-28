<?php 
require_once "../pdo.php";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="mycss.css">
    <!-- Load icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
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
                       echo '<a class="nav-link active fs-5 fw-bold" aria-current="page" href="rewardpage.php">Reward Page</a>';
                
                     ?>
                  </div>
                </div>
              </div>
            </nav>
    <!--Content-->
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="row content d-flex justify-content-center"
                style="border-top:solid aqua 10px; width:50%; background-color:white; border-radius:10px">
                <div class="col-lg-9" style="margin-top:10px">
                    <h1
                        style="color:black; text-align:center; font-size:50px; text-transform: uppercase; border-bottom:solid 5px">
                        Reward</h1>
                    <h5>Referral point: </h5>
                    <ul>
                        <li>1st generation referrer 5 points. </li>
                        <li>2nd generation referrer 2 points</li>
                        <li>3rd generation referrer 1 point. </li>
                    </ul>
                    <h5>Point:</h5>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" 
                    value="<?php 
                            $total = 0;
                            $check = "SELECT COUNT(sender_id) from referrals where sender_id = :uid ";
                            $statement = $pdo->prepare($check);
                            $statement -> execute(array(
                                'uid' => $_SESSION['user_id'],
                            ));
                            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                            // user id and deal id for 1st generation
                            $dl2nd = "SELECT receiver_id, deal_id from referrals where sender_id = :uid ";
                            $statedl2nd = $pdo->prepare($dl2nd);
                            $statedl2nd -> execute(array(
                                'uid' => $_SESSION['user_id'],
                            ));
                            $resultdl2nd = $statedl2nd->fetchAll(PDO::FETCH_ASSOC);
                            // calculate 1st generation point
                            foreach($results as $rows){
                                $count1 = (int) $rows['COUNT(sender_id)'];
                                $total = $total + 5 * $count1;
                            }
                            // calculate 2nd generation point
                            for($cnt=0;$cnt<count($resultdl2nd);$cnt++){
                                $details=$resultdl2nd[$cnt];
                                $check2 = "SELECT COUNT(A.receiver_id)
                                FROM referrals A, referrals B
                                WHERE A.sender_id = B.receiver_id AND A.deal_id = B.deal_id 
                                and B.receiver_id=:downUID and B.deal_id =:downDID";
                                $statement2 = $pdo->prepare($check2);
                                $statement2 -> execute(array(
                                    'downUID' => htmlentities($details['receiver_id']),
                                    'downDID' => htmlentities($details['deal_id'])
                                ));
                                $results2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
                                foreach($results2 as $rows2){
                                    $count2 = (int) $rows2['COUNT(A.receiver_id)'];
                                    $total = $total + 2 * $count2;
                                }
                                // 
                                $dl3="SELECT A.receiver_id, A.deal_id
                                FROM referrals A, referrals B
                                WHERE A.sender_id = B.receiver_id AND A.deal_id = B.deal_id 
                                and B.receiver_id=:downUID and B.deal_id =:downDID";
                                $statedl3rd = $pdo->prepare($dl3);
                                $statedl3rd -> execute(array(
                                    'downUID' => htmlentities($details['receiver_id']),
                                    'downDID' => htmlentities($details['deal_id'])
                                ));
                                $resultdl3rd = $statedl3rd->fetchAll(PDO::FETCH_ASSOC);
                                    for($cnt=0;$cnt<count($resultdl3rd);$cnt++){
                                    $details3=$resultdl3rd[$cnt];
                                    $check3 = "SELECT COUNT(A.receiver_id)
                                    FROM referrals A, referrals B
                                    WHERE A.sender_id = B.receiver_id AND A.deal_id = B.deal_id 
                                    and B.receiver_id=:downUID and B.deal_id =:downDID";
                                    $statement3 = $pdo->prepare($check3);
                                    $statement3 -> execute(array(
                                        'downUID' => htmlentities($details3['receiver_id']),
                                        'downDID' => htmlentities($details3['deal_id'])
                                    ));
                                    $results3 = $statement3->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($results3 as $rows3){
                                        $count3 = (int) $rows3['COUNT(A.receiver_id)'];
                                        $total = $total + 1 * $count3;
                                    }
                                }
                            }
                            echo $total
                    ?>" style="border:solid 1px; border-radius:10px; background-color:gainsboro">
                    <h5>Total Reward:</h5>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" 
                    value="<?php 
                        $totalredeem = $total * 0.05;
                        echo 'RM'.$totalredeem.'';
                    ?>" 
                    style="border:solid 1px; border-radius:10px; background-color:gainsboro">
                </div>
                <div class="col-lg-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="button" name="redeem" style="margin:5px">claim</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>