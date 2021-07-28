<?php
echo '<script>function details(dealnum){
    var dealid =dealnum;
    location.href="home/detailsDeal.php?deal_id="+dealnum;
  };</script>';
if(!isset($_SESSION['user_id'])) {
    die("Please Login");
}
//pdo
require_once "pdo.php";

if(!(isset($_POST['dealID']))){
    $stmts = $pdo->query('SELECT d.validity,d.deal_id,d.deal_name, d.deal_logo, d.promo_code, d.tagLine, d.reward, d.reward_unit, d.description,r.deal_status FROM deal d join deal_page p on d.deal_id=p.deal_id join deal_review r on d.deal_id=r.deal_id where p.enabled=1 and r.deal_status="approved"');
    $result = $stmts->fetchAll(PDO::FETCH_ASSOC);
    if(isset($_POST['DealName'])){
        usort($result, function($a, $b) {
            return $a['deal_name'] <=> $b['deal_name'];
        });
    }
    else if(isset($_POST['DealID'])){
        usort($result, function($a, $b) {
            return $a['deal_id'] <=> $b['deal_id'];
        });
    }
    foreach ($result as $row ) {
        $dealnum=htmlentities($row['deal_id']);
        echo
        '<div class="homecontent col-lg-2 card" style="background-color:white; border-bottom:solid blue 5px" onclick="details(\''.$dealnum.'\')">
                <img height=120 width=110 src="data:image/jpeg;base64,'.base64_encode($row['deal_logo']).'"/ class="mx-auto d-block">
                <div class="card-body" style="height:10rem;">  
                    <h5 class="card-title" style="color:black; text-transform:uppercase; text-align:center; border-top-style:solid;border-bottom-style:solid;">'. htmlentities($row['deal_name']) . '</h5>
                    <p class="card-text" style="">'. htmlentities($row['tagLine']) . '</p>
                </div>
                <div class="card-footer" style="margin-top:0px; background:none">
                    <p class="card-text" style=""> Valid until:<br>'. htmlentities($row['validity']) . '</p>
                </div>
        </div>';  
    } 
}
else if(isset($_POST['dealID']) && $_POST['dealID']>=0 ){
    echo '<script type="text/javascript"> details('.$_POST['dealID'].'); </script>';
}
?>
