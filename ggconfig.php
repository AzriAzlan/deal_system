<?php
require_once 'Google/autoload.php';
require_once 'pdo.php';
session_start();

$clientID = '373309691621-46guabuceformrdchoai7b3j43qr6nql.apps.googleusercontent.com';
$clientSecret = 'Yd8YndGko0iLxYXFyaJzwflZ';
$redirectUri = 'http://localhost/dealshare/ggconfig.php';

//Client request
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('profile');
$client->addScope('email');

if(isset($_GET['code'])){
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  $gauth = new Google_Service_Oauth2($client);
  $google_info = $gauth->userinfo->get();
  $id = $google_info->id;
  $name = $google_info->name;
  $gender = $google_info->gender;
  $email = $google_info->email;

  $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where gg_id = :ggid");
  $stmt->execute(array(':ggid' => $id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ( $row == false ) {
    try {
      $sql = "INSERT INTO users (user_name, user_email, user_type, gg_id) VALUES (:name, :email, :type, :ggid)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':name' => $name,
        ':email' => $email,
        ':type' => 'common',
        ':ggid' => $id));


    $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where gg_id = :ggid");
    $stmt->execute(array(':ggid' => $id));
    $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "INSERT INTO users_information (user_id, user_gender, user_number, user_address, user_postal, user_country) VALUES (:id, :gender, :phone, :address, :postal, :country)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':id' => $row2['user_id'],
      ':gender' => $gender,
      ':phone' => '',
      ':address' => '',
      ':postal' => '',
      ':country' => '')
    );

    $sql = "INSERT INTO management (user_id, category, blocked) VALUES (:id, :category, :blocked)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':id' => $row2['user_id'],
      ':category' => 'Beginner',
      ':blocked' => 0)
    );

  } catch (Exception $e){
    $_SESSION['error'] = 'Email already registered!';
    header('Location: login.php');
  }

    $_SESSION['user_id'] = $row2['user_id'];
    $_SESSION['username'] = $row2['user_name'];
    $_SESSION['type'] = $row2['user_type'];

    header('Location: index.php');


  }

  $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where gg_id = :ggid");
  $stmt->execute(array(':ggid' => $id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $_SESSION['user_id'] = $row['user_id'];
  $_SESSION['username'] = $row['user_name'];
  $_SESSION['type'] = $row['user_type'];

  header('Location: index.php');

} else {
  header('Location: '.$client->createAuthUrl());
}

?>
