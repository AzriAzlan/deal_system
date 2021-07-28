<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}

require_once "../pdo.php";
$uid=$_SESSION['user_id'];
$user = $pdo->query("SELECT * FROM users a inner join users_information b on a.user_id=b.user_id WHERE a.user_id = $uid");
$user_row = $user->fetch(PDO::FETCH_ASSOC);

$useremail = $user_row['user_email'];
$usertype = $user_row['user_type'];
$username = $user_row['user_name'];
$userage = $user_row['user_age'];
$usernumber = $user_row['user_number'];
$useraddress = $user_row['user_address'];
$usergender = $user_row['user_gender'];
$userpostcode = $user_row['user_postal'];
$usercountry = $user_row['user_country'];


if(isset($_POST['submit'])) {

	$username=$_POST['newUsername'];
	$email=$_POST['newUseremail'];
	$age=$_POST['newUserage'];
	$number=$_POST['newUsernumber'];
	$address=$_POST['newUseraddress'];
	$postcode=$_POST['newUserpostcode'];
	$country=$_POST['newUsercountry'];


 	$stmt = $pdo->prepare("UPDATE users SET user_name='$username',user_email='$email' WHERE user_id = $uid");
 	$stmt->execute();

    $stmt = $pdo->prepare("UPDATE users_information SET user_age='$age',user_number='$number',user_address='$address',user_postal='$postcode',user_country='$country' WHERE user_id = $uid");
 	$stmt->execute();

 	$_SESSION['username']=$username;

	header("Refresh:0");
}
if(isset($_POST['redeem'])){
  header("Location:rewardpage.php");
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <!-- Load icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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


    <div class="container">
        <div style="margin-top: 60px;" class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="../newDeal/profileIcon.png" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4><?php echo($username); ?></h4>
                                    <p class="text-secondary mb-1"><?php echo($uid); ?></p>
                                    <a href="postedDeals.php"><input type="button" class="btn btn-outline-primary"
                                            value="My Posted Deals"></input></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                                <p><i class="fa fa-gift" aria-hidden="true"></i>
                                    Point:
                                    <?php 
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
                                        ?>
                                <form method="POST"><button class="btn btn-info" type="submit" name="redeem"
                                        style="margin:5px">redeem</button></form>
                                </p>

                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                            </li>

                        </ul>
                    </div>


                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <form action="profilePage.php" method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="username">
                                        <?php echo($username); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Age</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="userage">
                                        <?php echo($userage) ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="useremail">
                                        <?php echo($useremail) ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Mobile</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="usernumber">
                                        <?php echo($usernumber); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="useraddress">
                                        <?php echo($useraddress); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Postcode</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="userpostcode">
                                        <?php echo($userpostcode); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Country</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary" id="usercountry">
                                        <?php echo($usercountry); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Gender</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?php echo($usergender); ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div id="go" class="col-sm-2">
                                        <a class="btn btn-info" onclick="editDetails();">Edit</a>
                                    </div>
                                    <div class="col-sm-2">
                                        <a class="btn btn-info" id="cancel" onclick="window.location.reload();"
                                            style="display: none;">Cancel</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

        </div>
    </div>

    <script type="text/JavaScript">


        function editDetails() {

  document.getElementById("cancel").style.display="block";

  var button = document.getElementById("go");
  var newbutton = document.createElement("input");
  newbutton.setAttribute("type", "submit");
  newbutton.setAttribute("name", "submit");
  newbutton.setAttribute("class", "btn btn-info");
  newbutton.setAttribute("value", "Submit");
  button.parentNode.replaceChild(newbutton, button);

  var username = document.getElementById("username");
  var newusername = document.createElement("input");
  newusername.setAttribute("type", "text");
  newusername.setAttribute("name", "newUsername");
  newusername.setAttribute("value", "<?php echo($username)?>");
  username.parentNode.replaceChild(newusername, username);

  var userage = document.getElementById("userage");
  var newuserage = document.createElement("input");
  newuserage.setAttribute("type", "text");
  newuserage.setAttribute("name", "newUserage");
  newuserage.setAttribute("value", "<?php echo($userage)?>");
  userage.parentNode.replaceChild(newuserage, userage);

  var useremail = document.getElementById("useremail");
  var newuseremail = document.createElement("input");
  newuseremail.setAttribute("type", "text");
  newuseremail.setAttribute("name", "newUseremail");
  newuseremail.setAttribute("value", "<?php echo($useremail)?>");
  useremail.parentNode.replaceChild(newuseremail, useremail);

  var usernumber = document.getElementById("usernumber");
  var newusernumber = document.createElement("input");
  newusernumber.setAttribute("type", "text");
  newusernumber.setAttribute("name", "newUsernumber");
  newusernumber.setAttribute("value", "<?php echo($usernumber)?>");
  usernumber.parentNode.replaceChild(newusernumber, usernumber);

  var useraddress = document.getElementById("useraddress");
  var newuseraddress = document.createElement("input");
  newuseraddress.setAttribute("type", "text");
  newuseraddress.setAttribute("name", "newUseraddress");
  newuseraddress.setAttribute("value", "<?php echo($useraddress)?>");
  useraddress.parentNode.replaceChild(newuseraddress, useraddress);

  var userpostcode = document.getElementById("userpostcode");
  var newuserpostcode = document.createElement("input");
  newuserpostcode.setAttribute("type", "text");
  newuserpostcode.setAttribute("name", "newUserpostcode");
  newuserpostcode.setAttribute("value", "<?php echo($userpostcode)?>");
  userpostcode.parentNode.replaceChild(newuserpostcode, userpostcode);


  var usercountry = document.getElementById("usercountry");
  var newusercountry = document.createElement("input");
  newusercountry.setAttribute("type", "text");
  newusercountry.setAttribute("name", "newUsercountry");
  newusercountry.setAttribute("value", "<?php echo($usercountry)?>");
  usercountry.parentNode.replaceChild(newusercountry, usercountry);

  }

</script>


</body>

</html>