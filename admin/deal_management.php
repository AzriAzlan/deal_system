<?php
session_start();
require_once "../pdo.php";

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
} else {
  if($_SESSION['type'] != 'admin'){
    header('Location: index.php');
  }
}

$all = true;
if(isset($_GET['deal'])){
  $stmt = $pdo->prepare("SELECT deal.deal_id,deal_name,deal_logo, deal_company, company_address, company_postcode, company_country, promo_code,landing_page, tagline, description, deal_status,deal_comment,country_listvalidity,validity,reward, reward_unit
    FROM deal_review JOIN deal ON deal_review.deal_id = deal.deal_id where deal.deal_id = :id");
  $stmt->execute(array(":id" => $_GET['deal']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $all = false;
  if ( $row === false ) {
      header('Location: deal_management.php');
      return;
  }

} else {
  $stmt = $pdo->prepare("SELECT *,deal_status,enabled,deal_comment FROM deal a join deal_review b on a.deal_id=b.deal_id join deal_page c on b.deal_id=c.deal_id where b.deal_status='approved'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if ( $rows === false ) {
      header('Location: deal_management.php');
      return;
  }

}

  if(isset($_POST['enable'])) {
  $id = $_GET['deal'];
  $stmt = $pdo->prepare("UPDATE deal_page SET enabled ='1' WHERE deal_id='$id'");
  $stmt->execute();
  header('Location: deal_management.php');

}

if(isset($_POST['disable'])) {
  $id = $_GET['deal'];
  $stmt = $pdo->prepare("UPDATE deal_page SET enabled ='0' WHERE deal_id='$id'");
  $stmt->execute();
  header('Location: deal_management.php');

}


?>
<html>
  <head>
    <title>Deal Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<style type="text/css">

.nav-link,
.navbar-brand {
    color: #fff;
    cursor: pointer;
}

</style>

  </head>
  <body>

    <div class="container-fluid bg-light mh-100" style="height: 100%;">
        <div class="row" style="height: 100%;">
          <div class="col-2 text-white" style="height: 100%; background-color: #CAF0F8; display: flex; flex-direction: column; justify-content: center;">
              <div class="d-flex align-items-center flex-column" style="width: 100%;">
                <img src="https://i.pinimg.com/originals/0c/3b/3a/0c3b3adb1a7530892e55ef36d3be6cb8.png" height="100px" width="100px"/>
                <h3 style="color: black;">Admin</h3>
              </div>
              <div class="d-flex align-items-start flex-column" style="width: 100%;">
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;"onclick="window.location.href='deal_review.php'">Deal Review</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='user_management.php'">User Management</button>
                <button type="button" class="btn btn-light btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='#'">Deal Management</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;"onclick="window.location.href='reward_rate.php'">Reward Rate</button>
                <button type="button" class="btn btn-danger btn-lg btn-block mb-2" style="width: 100%;"onclick="window.location.href='../logout.php'">Logout</button>
              </div>
          </div>
          <div class="col-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-link active fs-5 fw-bold" aria-current="page" href="deal_management.php">Home</a>
                    <?php
                    if(!$all){
                      echo '<div class="align-self-center"><img src="https://image.flaticon.com/icons/png/512/271/271228.png" style="width: 15px; height: 15px;"/></div>';
                      echo '<a class="nav-link active fs-5 fw-bold" aria-current="page" href="">'.htmlentities($row['deal_name']).'</a>';
                    }
                     ?>
                  </div>
                </div>
              </div>
            </nav>

            <div id="showdeals">
              <?php
                if($all){

                  foreach($rows as $row){
                    $id = "deal_management.php?deal=".$row['deal_id'];
                    $_SESSION['dealID']=$row['deal_id'];
                    echo '<a href='.htmlentities($id).' style="text-decoration: none;">';
                    echo '<div class="card text-center">';
                    echo '<div class="card-body d-flex bd-highlight">';
                    echo '<div class="p-2 w-100 bd-highlight"><h4 class="card-title d-flex align-items-center">'.htmlentities($row['deal_name']).'</h4>';
                    echo '<p class="card-text d-flex align-items-center">by '.htmlentities($row['deal_company']).'</p></div>';
                    echo '<div class="bg-primary p-2 d-flex bd-highlight"><img src="https://image.flaticon.com/icons/png/128/3179/3179668.png" style="width: 30px; height:30px;" class="align-self-center"/><h4 class="align-self-center ms-2" style="color: white;">'.htmlentities($row['reward_unit'].$row['reward']).'</h4></div></div>';
                    echo '<div class="card-footer text-muted">Valid until '.htmlentities($row['validity']).' Status: '.htmlentities($row['deal_status']).' Visibility: '.htmlentities($row['enabled']).'</div></div>';
                    echo '</a>';
                    echo '<br/>';
                  }
                } else {
                    echo '<div class="card text-center">';
                    echo '<div class="w-100 mt-3"><img src="data:image/jpeg;base64,'.base64_encode($row['deal_logo']).'" style="height: 100px; width: 100px;"/></div>';
                    echo '<div class="card-body d-flex bd-highlight">';
                    echo '<div class="p-2 w-100 bd-highlight"><h4 class="card-title d-flex align-items-center">'.htmlentities($row['deal_name']).'</h4>';
                    echo '<p class="text-start card-text d-flex align-items-center ">Company details: <br/>'.htmlentities($row['deal_company']).','.htmlentities($row['company_address']).','.htmlentities($row['company_postcode']).','.htmlentities($row['company_country']).'</p>';
                    echo '<p class="text-start card-text d-flex align-items-center">Description: <br/>'.htmlentities($row['description']).'</p>';
                    echo '<p class="text-start card-text d-flex align-items-center">Tagline: <br/>'.htmlentities($row['tagline']).'</p>';
                    echo '<p class="text-start card-text d-flex align-items-center">Comment: <br/>'.htmlentities($row['deal_comment']).'</p>';
                    echo '<p class="text-start card-text d-flex align-items-center">Status: <br/>'.htmlentities($row['deal_status']).'</p></div>';
                    echo '<div class="bg-primary p-2 d-flex bd-highlight"><img src="https://image.flaticon.com/icons/png/128/3179/3179668.png" style="width: 30px; height:30px;" class="align-self-center"/><h4 class="align-self-center ms-2" style="color: white;">'.htmlentities($row['reward_unit'].$row['reward']).'</h4></div></div>';

                    echo '
                    <form action="" method="POST">
                    <div class="d-flex align-items-end flex-column bd-highlight mb-3 pe-3">
                    <input name="enable" id="approve" class="btn btn-success p-2 bd-highlight col-4 mb-2" type="submit" value="Enable">
                    <input name="disable" id="reject" class="btn btn-danger p-2 bd-highlight col-4 mb-2" type="submit" value="Disable">
                    </div>
                    </form>';

                    //echo '<div class="card-footer text-muted">Valid until '.htmlentities($row['validity']).' Status: '.htmlentities($row['deal_status']).' Visibility: '.htmlentities($row['enabled']).'</div></div>';
                    echo '<br/>';
                }
               ?>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>
