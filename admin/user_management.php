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
    if($update[$x][1] == 1 || $update[$x][1] == 0){
      $sql = "UPDATE management SET blocked = :blocked WHERE user_id = :userid;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':blocked' => $update[$x][1],
        ':userid' => $update[$x][0]));
    } else {
      $sql = "UPDATE management SET category = :category WHERE user_id = :userid;";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':category' => $update[$x][1],
        ':userid' => $update[$x][0]));
    }
  }
  $changed = false;
  header('Location: user_management.php');
  return;
}

$stmt = $pdo->prepare("SELECT users.user_id, user_name, category, blocked FROM users JOIN management where users.user_id = management.user_id;");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ( $rows === false ) {
    header('Location: user_management.php');
    return;
}

$users = array();
foreach ($rows as $row) {
  $user = new User($row['user_id'], $row['user_name'], $row['category'], $row['blocked']);
  $users[] = $user;
}

class User {
  public $id;
  public $name;
  public $category;
  public $blocked;

  function __construct($id, $name, $category, $blocked) {
    $this->id = $id;
    $this->name = $name;
    $this->category = $category;
    $this->blocked = $blocked;
  }
}




?>
<html>
  <head>
    <title>User Management</title>
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
                <button type="button" class="btn btn-light btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='#'">User Management</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='deal_management.php'">Deal Management</button>
                <button type="button" class="btn btn-secondary btn-lg btn-block mb-2" style="width: 100%;" onclick="window.location.href='reward_rate.php'">Reward Rate</button>
                <button type="button" class="btn btn-danger btn-lg btn-block mb-2" style="width: 100%;"onclick="window.location.href='../logout.php'">Logout</button>
              </div>
          </div>
          <div class="col-10">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
              <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-link active fs-5 fw-bold" aria-current="page" href="user_management.php">User Management</a>
                  </div>
                </div>
              </div>
            </nav>

            <div id="showusers">
               <div class="card"  id="usercontent">
                 <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">No.</th>
                      <th scope="col">Name</th>
                      <th scope="col">Category</th>
                      <th scope="col">Block Account</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $index = 0;
                    foreach($users as $u){
                      echo '<tr height="60px">';
                      echo '<th scope="row">'.($index+1).'</th>';
                      echo '<td>'.htmlentities($u->name).'</td>';
                      echo '<td>';
                      echo '<select class="form-select" aria-label="Default select example" style="width: 200px" onchange="categorizeUser(this, '.($u->id).')">';
                      echo '<option value="Beginner" '.(str_contains($u->category, "Beginner") ? "selected" : "").'>Beginner</option>';
                      echo '<option value="Amateur" '.(str_contains($u->category, "Amateur") ? "selected" : "").'>Amateur</option>';
                      echo '<option value="Expert" '.(str_contains($u->category, "Expert") ? "selected" : "").'>Expert</option>';
                      echo '</select>';
                      echo '</td>';
                      echo '<td><div class="form-check form-switch"><input class="form-check-input" type="checkbox" onclick="blockUser(this, '.($u->id).')" id="flexSwitchCheckChecked" '.($u->blocked == 1 ? "checked" : "").'></div></td>';
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
    var blockUser;
    var categorizeUser;
    var applyChanges;

    var users = [];

    $(document).ready(function() {

      categorizeUser = function(selector, userid){
        var update = [userid,selector.value];
        users.push(update);
      }

      blockUser = function(checkbox, userid){
        var update = [userid,checkbox.checked ? 1 : 0];
        users.push(update);
      }

      applyChanges = function(){
        var count = users.length;

        $.post( 'user_management.php',
					 $('#updatechange').val(JSON.stringify(users))).done(function() { alert('Changes applied!')});
      }
    });

    </script>
  </body>
</html>
