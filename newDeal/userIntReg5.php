<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}
require_once "../pdo.php";
?>

<!DOCTYPE html>
<html>
   <head>
      <title>Bootstrap Example</title>
      <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="style.css">

      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src = "https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

   </head>
   <body>
   <div class="container-fluid h-100">
    <div class="row h-100">

           <!--Left panel with name and phone number-->
         <div class="col-md-2" id="leftPanel">
            <img src="profileIcon.png" width="50" height="50">
            <h3 id="userName"><?php echo($_SESSION["username"]); ?></h3>
            <p id="userPhone">019-7548963</p>
        </div>


     <div class="col-md-10">
            <h3 style="padding: 20px">Got it!</h3>

            <div class="container-fluid">
            <div class="row h-100">




  <div class="col-md-12">
<img src="https://cdn3.iconfinder.com/data/icons/flat-actions-icons-9/792/Tick_Mark_Circle-512.png" height="auto" width="65px" alt="Success" style=" display: block; margin-left: auto; margin-right: auto;">

    <h3 style="text-align: center;">Submission Received!</h3>
    <hr>



  <div class="row setup-content" id="step-9">

        <div class="col-md-2">
        </div>

        <div class="col-md-8">
          <div>
      <br>
       <h5>Your deal is pending approval. We'll get back to you within three business days.</h5>

       <a href="../index.php" style="margin: 0 auto; display: table;">
        <img src="https://cdn0.iconfinder.com/data/icons/typicons-2/24/home-4096.png" height="auto" width="65px" style=" display: block; margin-left: auto; margin-right: auto;">
       </a>

       <br>

      <div style="background-color: #f1f1f1; border-radius: 25px; position: relative;">

      <?php
      $promoCode = $_SESSION['promoCode'];
      $stmt = $pdo->prepare("select deal_logo from deal where promo_code = '$promoCode'");
      $stmt->execute();
      $imagelist = $stmt->fetchAll();

      foreach($imagelist as $image) {

      echo '<img src="data:image/jpeg;base64,'.base64_encode( $image['deal_logo'] ).'" height="auto" width="120px" style="float:right; padding:5px;"/>';

    }
        ?>

<table style="  border-collapse: separate;
                border-spacing: 50px;">
  <tr>
    <td><b>COMPANY INFORMATION</b></td>

    <td><?php   echo ($_SESSION['companyName']);
            echo("<br>");
            echo ($_SESSION['companyAddress']);
            echo("<br>");
            echo ($_SESSION['companyPostcode']);
            echo("<br>");
            echo ($_SESSION['companyCountry']);
            echo("<br>"); ?></td>
  </tr>

  <tr>
    <td><b>DEAL INFORMATION</b></td>
     <td><?php  echo ("Name : ".$_SESSION['dealName']);
             echo("<br>");
             echo ("Description : ".$_SESSION['dealDescription']);
             echo("<br>");
             echo ("Tag Line : ".$_SESSION['tagLine']);
             echo("<br>");
             echo ("Landing Page : ".$_SESSION['landingPage']);
             echo("<br>");?></td>
  </tr>

  <tr>
    <td><b>REWARD INFORMATION</b></td>
     <td><?php  echo ("Reward Unit : ".$_SESSION['rewardUnit']);
            echo("<br>");
            echo ("Reward Amount : ".$_SESSION['rewardAmount']);
            echo("<br>");
            echo ("Country List : ".$_SESSION['countryList']);
            echo("<br>");
            echo ("Valid until : ".$_SESSION['validity']);
            echo("<br>");?></td>
  </tr>

</table>

          </div>


          </div>


        </div>
      </div>




  </div>
</div>
</div>

</div>
</div>
</div>



   </body>
</html>
