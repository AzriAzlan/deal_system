<?php
ob_start();
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}
//pdo
require_once "../pdo.php";

if(!(isset($_GET['dealID'])) && !(isset($_POST['promocode'])) && !(isset($_POST['name']))){
    $deal = $_GET['deal_id'];
    $sharer = $_SESSION['user_id'];
    $referLink = "localhost/dealshare/referral.php?refer=$sharer%26deal=$deal";
    //Display all registered deal
    $stmts = $pdo->prepare('SELECT d.landing_page,d.deal_id,d.deal_name,d.deal_logo,d.promo_code,d.tagline,d.reward,d.reward_unit,d.description,d.validity,d.company_address,d.company_postcode,d.company_country
    FROM deal d inner join deal_review dr on d.deal_id=dr.deal_id
    where d.deal_id=:dealID AND dr.deal_status="approved"');
    $stmts -> execute(array(
        ':dealID' => $_GET['deal_id']
    ));
    $result = $stmts->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $check = "SELECT COUNT(deal_id) from saved_deals where user_id = :uid and deal_id = :did";
        $statement = $pdo->prepare($check);
        $statement -> execute(array(
            'uid' => $_SESSION['user_id'],
            'did' => $_GET['deal_id']
        ));
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($results as $rows){
            $count = $rows['COUNT(deal_id)'];
            $default = "0";

        $dealID=htmlentities($row['deal_id']);
        echo
            '<img class="col-lg-3" src="data:image/jpeg;base64,'.base64_encode($row['deal_logo']).'"/ style="margin-top:10px; height:300;">
            <div class="col-lg-9" style="margin-top:10px">';
            if(!strcmp($count,$default)){
                echo '<form method="POST">
                        <button class="btn float-right" type="submit" name="save" style="background:none; border:none; " >
                            <i class="fa fa-heart-o fa-3x" aria-hidden="true"></i>
                        </button>
                </form>';
            }
            else{
                echo'
                <form method="POST">
                        <button class="btn float-right" type="submit" name="unsave" style="background:none; border:none; " >
                        <i class="fa fa-heart fa-3x float-right" aria-hidden="true" style="color:red"></i>
                        </button>
                </form>';
            }
            echo'<h1 style="color:black; text-align:center; font-size:50px; text-transform: uppercase;">'. htmlentities($row['deal_name']) . '</h1>
                <div class="row" style="border-top-style:solid; border-bottom-style:solid;">
                    <p class="col-lg-6" style="text-align:left;">Promo code: <strong>'. htmlentities($row['promo_code']) . '</strong></p>
                    <p class="col-lg-6" style="text-align:right;"> Expired: '. htmlentities($row['validity']) . '</p>
                </div>
                <h5>Description:</h5>
                <ul>'. htmlentities($row['description']) . '</ul>
                <h5>Tagline:</h5>
                <ul>'. htmlentities($row['tagline']) . '</ul>
                <h5>Reward Redeem:</h5>
                <ul>'. htmlentities($row['reward']). htmlentities($row['reward_unit']) . '</ul>
                <h5>Address:</h5>
                <ul>'. htmlentities($row['company_address']).'</br>'. htmlentities($row['company_postcode']) .'</br>'. htmlentities($row['company_country']) .'</ul>';
                //trigger modal share
                echo'<div class=" imagesdeal" data-toggle="modal" data-target="#'.htmlentities($row['deal_id']).'">
                        <button class="btn btn-info col-lg-12" style="margin-bottom:20px">Share</button>';
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
                                        class="fb-xfbml-parse-ignore"><img src="../savedDeal/Icon/fb.png" style="height:50px; margin:10px"></a>
                                </div>
                                <a href="http://www.twitter.com/share?url='.$referLink.'"><img src="../savedDeal/Icon/twittericon.png" style="height:50px; margin:10px"></a>
                                <a href="whatsapp://send?text='.$referLink.'" data-action="share/whatsapp/share"><img src="../savedDeal/Icon/wa.png" style="height:50px; margin:10px"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            //trigger modal redeem
            echo'<div class=" imagesdeal" data-toggle="modal" data-target="#'.htmlentities($row['promo_code']).'">
                        <button class="btn col-lg-12" style="margin-bottom:20px;background:none; color:darkcyan; border:solid 1px darkcyan">Redeem</button>';
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
            </div>';
            if (isset($_POST['save'])){
                $sql = "INSERT INTO saved_deals (user_id,deal_id) VALUES (:userid,:dealid)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':userid' => $_SESSION['user_id'],
                    ':dealid' => $dealID
                ));
                header("Refresh:0");
                }
            if (isset($_POST['unsave'])){
                $sql = "DELETE FROM saved_deals WHERE user_id=:userid AND deal_id=:dealid";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':userid' => $_SESSION['user_id'],
                    ':dealid' => $dealID
                ));
                header("Refresh:0");
            }
            }
    }
}
if(isset($_POST['submit'])) { 
    $dealNum=$_GET['deal_id'];  
    $sql = "INSERT INTO referrals (receiver_id,deal_id) VALUES (:userid,:dealid)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':userid' => $_SESSION['user_id'],
                    ':dealid' => $_GET['deal_id']
                ));
    $tmpName = $_FILES['receipt']['tmp_name'];
        $fp = fopen($tmpName,'rb');
      
        $stmt = $pdo->prepare("UPDATE referrals SET receipt = ( ? ) WHERE receiver_id='$sharer' and deal_id='$dealNum'");
        $stmt->bindParam(1, $fp, PDO::PARAM_LOB);
        $pdo->errorInfo();
        $stmt->execute();
}
echo '<form action="" name="photoForm" method="POST" enctype="multipart/form-data">
<label for="receipt">Attach your Receipt here : </label>
<input id="receipt" name="receipt" type="file" accept="image/*" required />

<input type="submit" class="btn btn-info btn-rounded nextBtn float-right" name="submit" id="submit" value="Submit">
</form> '

?>