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

$changed = false;

if(isset($_POST['updatechange'])){
  $update = json_decode($_POST['updatechange'], true);
  for($x = 0; $x < count($update); $x++){
    $sql = "UPDATE reward_rate SET point_conversion = :point_conversion WHERE category = :category;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':point_conversion' => $update[$x][1],
      ':category' => $update[$x][0]));
  }
  $changed = false;
  header('Location: reward_rate.php');
  return;
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

$stmt = $pdo->prepare("SELECT category, point_conversion FROM reward_rate;");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ( $rows === false ) {
  header('Location: user_management.php');
  return;
}

?>
<html>
  <head>
    <title>Reward Rate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <style>
      td, th {
        vertical-align: middle;
      }

      #flexSwitchCheckChecked:checked {
        border-color: red;
        background-color: red;
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
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='deal_review.php'">Deal Review</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='user_management.php'">User Management</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='deal_management.php'">Deal Management</button>
                <button type="button" class="btn btn-light btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='#'">Reward Rate</button>
                <button type="button" class="btn btn-danger btn-lg btn-block mb-2" style="width: 100%;"onclick="window.location.href='../logout.php'">Logout</button>
              </div>
          </div>
          <div class="col-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-link active fs-5 fw-bold" aria-current="page" href="reward_rate.php">Reward Rate</a>
                  </div>
                </div>
              </div>
            </nav>

            <div id="showusers">
               <div class="card"  id="usercontent">
                 <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Category</th>
                      <th scope="col">Point Conversion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $index = 0;
                    foreach($rows as $row){
                      echo '<tr height="60px">';
                      echo '<td>'.htmlentities($row['category']).'</td>';
                      echo '<td>';
                      echo '<input type="number" step="0.01" value="'.htmlentities($row['point_conversion']).'" onchange="changePoint(this, \''.($row['category']).'\')">';
                      echo '</td>';
                      echo '</tr>';
                      ++$index;
                    }
                    ?>
                  </tbody>
                </table>
               </div>
            </div>
            <input type="hidden" id="updatechange" name="updatechange" value="" />
            <span id="result"></span>
            <div style="position: absolute; bottom: 20px; height: 5%; width: 81%">
              <div class="card w-100 h-100">
                <button type="button" class="btn btn-primary" onclick="applyChanges()">Apply Changes</button>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script type="text/javascript">
    var applyChanges;
    var changePoint;
    var categories = [];

    $(document).ready(function() {
      changePoint = function(input, category){
        var cat = [category, input.value];
        categories.push(cat);
      }

      applyChanges = function(){
        $.post( "reward_rate.php",
					 $('#updatechange').val(JSON.stringify(categories))).done(function() { alert('Changes applied!')});
      }
    });

    </script>
  </body>
</html>
