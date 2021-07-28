<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}
require_once "../pdo.php";

 if(isset($_POST['submit'])) {    

  function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

$a = random_str(6);

$select = $pdo->prepare('SELECT promo_code FROM deal WHERE promo_code = ?');
$select->execute([$a]);
if ($select->rowCount() > 0) {
    $promoCode = random_str(6);
    $_SESSION['promoCode'] = $promoCode;
} else {
    $promoCode = $a;
    $_SESSION['promoCode'] = $promoCode;
}

        
        $stmt = $pdo->prepare('INSERT INTO deal
          (deal_name, deal_company,company_address,company_postcode,company_country,promo_code,landing_page,tagline,description,reward,reward_unit,country_listvalidity,validity) VALUES (:dnm,  :dcp, :cad,:cpc, :cty, :pmo, :lpg, :tg, :de, :re, :ru, :cova,:val)');
        
        $stmt->execute(array(
          ':dnm' => $_SESSION['dealName'],
          ':dcp' => $_SESSION['companyName'],
          ':cad' => $_SESSION['companyAddress'],
          ':cpc' => $_SESSION['companyPostcode'],
          ':cty' => $_SESSION['companyCountry'],
          ':pmo' => $promoCode,
          ':lpg' => $_SESSION['landingPage'],
          ':tg' => $_SESSION['tagLine'],
          ':de' => $_SESSION['dealDescription'],
          ':re' => $_SESSION['rewardAmount'],
          ':ru' => $_SESSION['rewardUnit'],
          ':cova' => $_SESSION['countryList'],
          ':val' => $_SESSION['validity'])
        );

        $tmpName = $_FILES['dealLogo']['tmp_name'];
        $fp = fopen($tmpName,'rb');
      
        $stmt = $pdo->prepare("UPDATE deal SET deal_logo = ( ? ) WHERE promo_code='$promoCode'");
        $stmt->bindParam(1, $fp, PDO::PARAM_LOB);
        $pdo->errorInfo();
        $stmt->execute();

        $resultid = $pdo->query("SELECT deal_id FROM deal WHERE promo_code='$promoCode'")->fetchAll();

foreach ($resultid as $row) {
    $dealId = $row['deal_id'];
}
  
        $stmt = $pdo->prepare('INSERT INTO deal_review (deal_id,deal_status,deal_comment) VALUES ('.$dealId.',"pending","checkOut")');
        $stmt->execute();

        $uid = $_SESSION['user_id'];

        $stmt = $pdo->prepare("INSERT INTO deal_page (deal_id,user_id,enabled) VALUES ('$dealId','$uid',0)");
        $stmt->execute();
    
        header('Location: userIntReg5.php');

    }

?>

<!DOCTYPE html>
<html>
   <head>
      <title>Deal Registration Page</title>
      <link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="style.css">

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
            <h3 style="padding: 20px; ">DEAL REGISTRATION PAGE</h3>

        
            <div class="container-fluid">
            <div class="row h-100">

              <div class="col-md-3">

                <!--Stepper for progress-->
                <div class="stepper d-flex flex-column mt-5 ml-2">

                <!--Step 1-->   

                <div class="d-flex mb-1">
            <div class="d-flex flex-column pr-4 align-items-center">
            <div class="img-thumbnail py-2 px-3 mb-1" style="background-color: #f5f5f5;">1</div>
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
            <div class="img-thumbnail py-2 px-3 mb-1" style="background-color: #f5f5f5;">2</div>
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
            <div class="img-thumbnail py-2 px-3 mb-1" style="background-color: #f5f5f5;">3</div>
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
            <div class="img-thumbnail py-2 px-3 mb-1" style="background-color: #f5f5f5;">4</div>
            <div class="line h-100 d-none"></div>
            </div>
            <div>
            <h5 class="text-dark">Review and confirm</h5>
            <p class="lead text-muted pb-3">Submit</p>
            </div>
          </div>

          </div>

          </div>

      <div class="col-md-9">

      <b>REVIEW , ATTACH AND SUBMIT</b>
      <hr>


  <div class="row setup-content">
        <div class="col-md-12">
          <div style="background-color: #f1f1f1; border-radius: 25px;">


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
         
            <p>
            <p>
     
          
          <form action="" name="photoForm" method="POST" enctype="multipart/form-data">
            <label for="dealLogo">Attach your company/deal Logo : </label>
            <input id="dealLogo" name="dealLogo" type="file" accept="image/*" required />
            <p>
            <p>

          <input type="button" value="Edit" name="back" class="btn btn-indigo btn-rounded nextBtn float-left" onclick="history.back()">
          <input type="submit" class="btn btn-indigo btn-rounded nextBtn float-right" name="submit" id="submit" value="Submit">
          </form> 
         
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