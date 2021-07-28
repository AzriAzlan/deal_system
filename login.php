<?php
require_once 'pdo.php';
session_start();

$email_error = $pwd_error = '';
$noerror = true;
$salt = 'dealshare123';

$e = $p = '';

if(isset($_POST['email']) && isset($_POST['pwd'])){
  $e = htmlentities($_POST['email']);
  $p = htmlentities($_POST['pwd']);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

  if(empty($_POST["email"])){
    $email_error = "Email can't be empty";
    $noerror = false;
    } else {
    $email = $_POST["email"];
    // check that the e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        $noerror = false;
    }
  }

  if(empty($_POST["pwd"])){
    $pwd_error = "Password can't be empty";
    $noerror = false;
  } else {
    $pwd = $_POST['pwd'];
  }

  if($noerror){
      $stmt = $pdo->prepare("SELECT user_id, user_name, user_type, user_email, user_password FROM users where user_email = :email");
      $stmt->execute(array(':email' => $email));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row == false){
        $_SESSION['error'] = 'User is not registered!';
        $e = $p = '';
      } else {
        if(hash('md5', $salt.$pwd) == $row['user_password']){
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['user_name'];
          $_SESSION['type'] = $row['user_type'];

          header('Location: index.php');
        } else {
          $pwd_error = 'Password is incorrect!';
          $p = '';
        }
      }
  }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
     <style>
     body {
       text-align: center;
       margin: auto;
     }

     .error {
       color: red;
       font-size: 12px;
     }
     </style>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 </head>

<body>
<div class="inner">
    <h2>DealShare</h2>
    <div class="card container border border-info border-3 mt-5 py-5">
      <div class="d-grid gap-2 col-4 mx-auto mb-3">
        <h4>Login</h4>
        <?php
        if (isset($_SESSION['error'])) {
          echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
          unset($_SESSION['error']);
        } ?>
        <?php
        if (isset($_SESSION['info'])) {
          echo '<p style="color:green">'.$_SESSION['info']."</p>\n";
          unset($_SESSION['info']);
        } ?>
        <button onclick="window.location.href='fbconfig.php'" type="button" class="btn btn-lg w-100" style="background-color: #4267B2; color: white;"><div class=""><i class="bi bi-facebook me-3"></i><label>Login with Facebook</label></div></button>
        <button onclick="window.location.href='twtconfig.php'" type="button" class="btn btn-lg w-100" style="background-color: #1DA1F2; color: white;"><div class=""><i class="bi bi-twitter me-3"></i><label>Login with Twitter</label></div></button>
        <button onclick="window.location.href='ggconfig.php'" type="button" class="btn btn-lg w-100" style="background-color: #DB4437; color: white;"><div class=""><i class="bi bi-google me-3"></i><label>Login with Google</label></div></button>
      </div>
      <form id="login" method="POST" action="login.php">
          <div class="d-grid gap-2 col-4 mx-auto">

            <div class="col"><label><b>Email</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-envelope"></i></span>
              <input value="<?= $e ?>" type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="addon-wrapping" name="email">
            </div>
            <span class="error mb-3"><?php echo $email_error; ?></span>

            <div class="col"><label><b>Password</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
              <input value="<?= $p ?>" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="addon-wrapping" name="pwd">
            </div>
            <span class="error mb-3"><?php echo $pwd_error; ?></span>
          </div>
          <input type="checkbox" name="check" id="check" class="mt-2">
          <span style="color:black">Remember me</span><br><br>
      </form>
      <div class="d-flex justify-content-center">
        <button form="login" class="btn btn-primary btn-lg mx-2" type="submit">Log In</button>
        <button onclick="window.location.href='register.php'" class="btn btn-outline-primary btn-lg mx-2">Register</button>
      </div>
    </div>
  </div>

</body>

</html>
