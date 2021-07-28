<?php
session_start();

require_once "pdo.php";

$deal = $_GET['deal'];
$refer =$_GET['refer'];
if(isset($_SESSION['user_id']))
$sharer = $_SESSION['user_id'];
else
$sharer = $refer;

$referLink = "localhost/dealshare/referral.php?refer=$sharer%26deal=$deal";

$stmts = $pdo->query("SELECT * FROM deal d WHERE d.deal_id='$deal'");
$dealresults = $stmts->fetchAll(PDO::FETCH_ASSOC);

$stmts = $pdo->query("SELECT * FROM users u WHERE u.user_id='$refer'");
$userresults = $stmts->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dealresults as $row) {
    foreach($userresults as $user) {
    echo'
    <div class="row center">
        <div class="col-lg-6 d-flex justify-content-center" style="border-right:solid 1px; position:relative">
            <div class="d-flex justify-content-center" style="position:absolute">
                <img height=350 width=300 src="data:image/jpeg;base64,'.base64_encode($row['deal_logo']).'"/>
            </div>
            <img height=350 width=300 src="savedDeal/Icon/frame.png" style="position:absolute;">
        </div>
        <div class="col-lg-6" style="text-align:center">
            <h1>Here\'s your '.htmlentities($row['deal_name']).' coupon</h1>
            <p>Use the following promocode at '.htmlentities($row['landing_page']).' to get <strong>'.htmlentities($row['reward']). htmlentities($row['reward_unit']).'</strong> on your purchase</p>

            <h2>Shared by:</h2>
            <h5>'. htmlentities($user['user_name']) . '</h5>
            <h2>Promocode:</h2>
            <h5><strong>'.htmlentities($row['promo_code']).'</strong></h5>
            <!-- redeem button -->

            <div class=" imagesdeal" data-toggle="modal" data-target="#'.htmlentities($row['promo_code']).'">
                        <button class="btn col-lg-3 btn-info" style="margin-bottom:20px;">Redeem</button>';
            //modal
            echo
            '<div id="'.htmlentities($row['promo_code']).'" class="modal fade" role="dialog">
                <div class="modal-dialog">';
            //modal content
            echo
                '<div class="modal-content">
                <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">'.htmlentities($row['deal_name']).'</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="d-flex justify-content-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data='.htmlentities($row['landing_page']).'&amp;size=200x200"/>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>scan me to go to company website</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="http://'.htmlentities($row['landing_page']).'" target="_blank"><button class="btn btn-primary">Bring me to company website</button></a>
                        </div>
                    </div>
                <!-- Modal footer -->
                    <div class="modal-footer d-flex justify-content-center">
                        <img src="https://www.cognex.com/api/Sitecore/Barcode/Get?data='.htmlentities($row['promo_code']).'&code=BCL_CODE128&width=300&imageType=JPG&foreColor=%23000000&backColor=%23FFFFFF&rotation=RotateNoneFlipNone" width="300" />
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!-- share button -->
            <div class=" imagesdeal" data-toggle="modal" data-target="#'.htmlentities($row['deal_id']).'">
                        <button class="btn col-lg-3" style="margin-bottom:20px; background:none; color:darkcyan; border:solid 1px darkcyan">Share</button>';
                //modal
                echo
                '<div id="'.htmlentities($row['deal_id']).'" class="modal fade" role="dialog">
                    <div class="modal-dialog">';
                //modal content
                echo

                        '<div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">'.htmlentities($row['deal_name']).'</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <h1 style="font-size:30px">Tagline:</h1>
                                <p class="card-text">'. htmlentities($row['tagline']) . '</p>
                                <h1 style="font-size:30px">Description:</h1>
                                <p class="card-text">'. htmlentities($row['description']) . '</p>
                                <h1 style="font-size:30px">Reward:</h1>
                                <p class="card-text">'. htmlentities($row['reward']). htmlentities($row['reward_unit']) . '</p>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <div data-href="http://localhost/deal%20application/homepage.php" data-layout="button" data-size="large">
                                    <a target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?u='.$referLink.'&amp;src=sdkpreparse"
                                        class="fb-xfbml-parse-ignore"><img src="savedDeal/Icon/fb.png" style="height:50px; margin:10px"></a>
                                </div>
                                <a href="http://www.twitter.com/share?url='.$referLink.'"><img src="savedDeal/Icon/twittericon.png" style="height:50px; margin:10px"></a>
                                <a href="whatsapp://send?text='.$referLink.'" data-action="share/whatsapp/share"><img src="savedDeal/Icon/wa.png" style="height:50px; margin:10px"></a>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        <form action="" name="photoForm" method="POST" enctype="multipart/form-data">
                <label for="receipt">Attach your Receipt here : </label>
                <input id="receipt" name="receipt" type="file" accept="image/*" required />

                <input type="submit" class="btn btn-info btn-rounded nextBtn float-center" name="submit" id="submit" value="Submit">
                </form>
    </div>';
}
}
if(isset($_POST['submit'])) {
    $sql = "INSERT INTO referrals (sender_id,receiver_id,deal_id) VALUES (:sender,:userid,:dealid)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':sender'=> $refer,
                    ':userid' => $_SESSION['user_id'],
                    ':dealid' => $deal
                ));
    $tmpName = $_FILES['receipt']['tmp_name'];
        $fp = fopen($tmpName,'rb');

        $stmt = $pdo->prepare("UPDATE referrals SET receipt = ( ? ) WHERE receiver_id='$sharer' and deal_id='$deal'");
        $stmt->bindParam(1, $fp, PDO::PARAM_LOB);
        $pdo->errorInfo();
        $stmt->execute();
}
?>
<!DOCTYPE html>
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
    <title>Landing</title>

    <style>
    .center {
        padding: 150px 0;
    }
    </style>
</head>

<body>
    <?php
    if($sharer==$refer){
        echo '<!--navigation-->
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="#">DealShare</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main-navigation">

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../dealshare/index.php"><button class="btn btn-success">Login</button></a>
                    </li>
                </ul>
        </nav>';
    }
    else
    ?>
</body>

</html>
