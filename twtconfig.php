<?php
require_once 'Twitter/autoload.php';
require_once 'pdo.php';

$config = [
    'callback' => 'http://localhost/dealshare/twtconfig.php',
    'keys'     => ['key' => 'D0BNMSOa89gLLyplNp93J7wZo', 'secret' => 'cFdSDXLg9LJCGjx7lIYDAmRvIEujgQXHppUVNmqYrr7sGYTLCG'],
    'authorize' => true
];

$adapter = new Hybridauth\Provider\Twitter($config);


try {

  $adapter->authenticate();
  $userProfile = $adapter->getUserProfile();

  if(isset($_GET['oauth_token'])) {

    $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where twt_id = :twtid");
    $stmt->execute(array(':twtid' => $userProfile->identifier));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row == false ) {
      try {
        $sql = "INSERT INTO users (user_name, user_email, user_type, twt_id) VALUES (:name, :email, :type, :twtid)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':name' => $userProfile->firstName,
          ':email' => $userProfile->email,
          ':type' => 'common',
          ':twtid' => $userProfile->identifier)
        );


      $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where twt_id = :twtid");
      $stmt->execute(array(':twtid' => $userProfile->identifier));
      $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

      $sql = "INSERT INTO users_information (user_id, user_gender, user_number, user_address, user_postal, user_country) VALUES (:id, :gender, :phone, :address, :postal, :country)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':id' => $row2['user_id'],
        ':gender' => $userProfile->gender,
        ':phone' => $userProfile->phone,
        ':address' => $userProfile->address,
        ':postal' => $userProfile->zip,
        ':country' => $userProfile->country)
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

    } else {
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['username'] = $row['user_name'];
      $_SESSION['type'] = $row['user_type'];

      header('Location: index.php');
    }

  }
}
catch( Exception $e ){
    echo $e->getMessage() ;
}

?>
