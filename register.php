<?php
require_once 'pdo.php';
session_start();

$name_error = $gender_error = $phone_error = $address_error = $postal_error = $country_error = $email_error = $pwd_error = "";
$name = $gender = $phone = $address = $postal = $country = $email = $pwd = '';
$noerror = true;

$salt = 'dealshare123';


//validation
if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST["name"])) {
    $name_error = "Name can't be empty";
    $noerror = false;
  } else {
    $name = $_POST["name"];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $name_error = "Only alphabets and white space are allowed";
        $noerror = false;
    }
  }

  if(empty($_POST["gender"])){
    $gender_error = "Please select gender";
    $noerror = false;
  } else {
    $gender = $_POST['gender'];
  }

  if(empty($_POST["phone"])) {
    $phone_error = "Phone number can't be empty";
    $noerror = false;
  } else {
    $phone = $_POST['phone'];
  }

  if(empty($_POST["address"])) {
    $address_error = "Address can't be empty";
    $noerror = false;
  } else {
    $address = $_POST['address'];
  }

  if(empty($_POST["postal"])) {
    $postal_error = "Enter postal code";
    $noerror = false;
  } else {
    $postal = $_POST['postal'];
  }

  if(empty($_POST["country"])) {
    $country_error = "Country can't be empty";
    $noerror = false;
  } else {
    $country = $_POST['country'];
  }

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
    try {
      $sql = "INSERT INTO users (user_name, user_type, user_email, user_password) VALUES (:name, :type, :email, :password)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':name' => $name,
        ':type' => 'common',
        ':email' => $email,
        ':password' => hash('md5', $salt.$pwd)));

        $stmt = $pdo->prepare("SELECT user_id FROM users where user_email = :email");
        $stmt->execute(array(":email" => $email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO users_information (user_id, user_gender, user_number, user_address, user_postal, user_country) VALUES (:id, :gender, :number, :address, :postal, :country)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':id' => $row['user_id'],
          ':gender' => $gender,
          ':number' => $phone,
          ':address' => $address,
          ':postal' => $postal,
          ':country' => $country));

        $sql = "INSERT INTO management (user_id, category, blocked) VALUES (:id, :category, :blocked)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':id' => $row['user_id'],
          ':category' => 'Beginner',
          ':blocked' => 0)
        );

        $_SESSION['info'] = 'Registered successfully! Please login to continue';
        header('Location: login.php');

    } catch (PDOException $e) {
      $_SESSION['error'] = 'Email entered already registered!';
      echo $e->getMessage();
    }
  }


}


?>


<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
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
        <h4>Register</h4>
        <?php
        if ( isset($_SESSION['error']) ) {
          echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
          unset($_SESSION['error']);
        } ?>
      </div>
      <form id="register" method="POST" action="register.php">
          <div class="d-grid gap-2 col-4 mx-auto">
            <div class="col" style="text-align: left;"><label><b>Name</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person"></i></span>
              <input value="<?= $name ?>" type="text" class="form-control" placeholder="Full Name" aria-label="Email" aria-describedby="addon-wrapping" name="name">
            </div>
            <span class="error"><?php echo $name_error; ?> </span>

            <div class="col" style="text-align: left;"><label><b>Gender</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <div class="form-check form-check-inline">
                <input class="form-check-input" <?= $gender=='Male' ? 'checked="true"' : ''?> type="radio" name="gender" value="Male">
                <label class="form-check-label" for="Male">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" <?= $gender=='Female' ? 'checked="true"' : ''?> type="radio" name="gender" value="Female">
                <label class="form-check-label" for="Female">Female</label>
              </div>
            </div>
            <span class="error"><?php echo $gender_error; ?> </span>

            <div class="col" style="text-align: left;"><label><b>Phone Number</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-telephone"></i></span>
              <input value="<?= $phone ?>" type="text" class="form-control" placeholder="Phone Number" aria-label="Email" aria-describedby="addon-wrapping" name="phone">
            </div>
            <span class="error"><?php echo $phone_error; ?> </span>

            <div class="col" style="text-align: left;"><label><b>Address</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-geo-alt"></i></span>
              <input value="<?= $address ?>" type="text" class="form-control" placeholder="Address" aria-label="Email" aria-describedby="addon-wrapping" name="address">
            </div>
            <span class="error"><?php echo $address_error; ?> </span>

            <div class="row d-flex justify-content-center">
              <div class="col">
                <div class="col w-50" style="text-align: left;"><label><b>Postal</b></label></div>
                <div class="input-group flex-nowrap w-100">
                  <span class="input-group-text" id="addon-wrapping"><i class="bi bi-signpost-2"></i></span>
                  <input value="<?= $postal ?>" type="text" class="form-control" placeholder="Postal" aria-label="Email" aria-describedby="addon-wrapping" name="postal">
                </div>
                <span class="error"><?php echo $postal_error; ?> </span>
              </div>
              <div class="col">
                <div class="col" style="text-align: left;"><label><b>Country</b></label></div>
                <div class="input-group flex-nowrap w-100">
                  <span class="input-group-text" id="addon-wrapping"><i class="bi bi-geo"></i></span>
                  <input value="<?= $country ?>" type="text" class="form-control" placeholder="Country" aria-label="Email" aria-describedby="addon-wrapping" name="country">
                </div>
                <span class="error"><?php echo $country_error; ?> </span>
              </div>
            </div>

            <div class="col" style="text-align: left;"><label><b>Email</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-envelope"></i></span>
              <input value="<?= $email ?>" type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="addon-wrapping" name="email">
            </div>
            <span class="error"><?php echo $email_error; ?> </span>

            <div class="col" style="text-align: left;"><label><b>Password</b></label></div>
            <div class="input-group flex-nowrap w-100">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-key"></i></span>
              <input value="<?= $pwd ?>" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="addon-wrapping" name="pwd">
            </div>
            <span class="error mb-3"><?php echo $pwd_error; ?></span>
          </div>
      </form>
      <div class="d-flex justify-content-center">
        <button form="register" class="btn btn-primary btn-lg mx-2" type="submit">Register</button>
        <button onclick="window.location.href='login.php'" class="btn btn-outline-primary btn-lg mx-2">Log In</button>
      </div>
    </div>
  </div>

</body>

</html>
