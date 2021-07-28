<?php
require_once 'pdo.php';

define('APP_ID','431099181320371');
define('APP_SECRET','4d0096f863bf05f68db0abc467cd2450');
define('API_VERSION','v2.5');
define('FB_BASE_URL','https://localhost/dealshare/fbconfig.php');


session_start();


require_once(__DIR__.'/Facebook/autoload.php');

$fb = new Facebook\Facebook([
  'app_id' => APP_ID,
  'app_secret' => APP_SECRET,
  'default_graph_version' => API_VERSION,
]);

$fb_helper = $fb->getRedirectLoginHelper();

//Get access token
try {
  if(isset($_SESSION['facebook_access_token'])){
    $accessToken = $_SESSION['facebook_access_token'];
  } else {
    $accessToken = $fb_helper->getAccessToken();
  }
} catch(FacebookResponseException $e){
  echo 'Facebook API Error: '.$e->getMessage();
  exit;
} catch(FacebookSDKException $e){
  echo 'Facebook SDK Error: '.$e->getMessage();
  exit;
}

$fb_user = '';
$picture = '';
$permissions = ['email','user_location'];
if(isset($accessToken)){
  if(!isset($_SESSION['facebook_access_token'])){

    //short-lived
    $_SESSION['facebook_access_token'] = (string) $accessToken;

    //OAuth 2.0
    $oAuth2Client = $fb->getOAuth2Client();

    //exchange short-lived for long-lived
    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

    //setting default access $accessToken
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  } else {
    $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
  }




  try {

    $response = $fb->get('/me?fields=name,email,location', $accessToken);
    $user = $response->getGraphUser();


    $stmt = $pdo->prepare("SELECT user_name,user_id,user_type FROM users where fb_id = :fbid");
    $stmt->execute(array(':fbid' => $user->getId()));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row == false ) {
      try {
        $sql = "INSERT INTO users (user_name, user_email, user_type, fb_id) VALUES (:name, :email, :type, :fbid)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':name' => $user->getName(),
          ':email' => $user->getEmail(),
          ':type' => 'common',
          ':fbid' => $user->getId())
        );


      $stmt = $pdo->prepare("SELECT user_id FROM users where fb_id = :fbid");
      $stmt->execute(array(':fbid' => $user->getId()));
      $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

      $sql = "INSERT INTO users_information (user_id, user_gender, user_number, user_address, user_postal, user_country) VALUES (:id, :gender, :phone, :address, :postal, :country)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':id' => $row2['user_id'],
        ':gender' => $user->getGender(),
        ':phone' => '',
        ':address' => $user->getLocation()['name'],
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

    }

    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['username'] = $row['user_name'];
    $_SESSION['type'] = $row['user_type'];

    header('Location: index.php');

  } catch (Facebook\Exceptions\FacebookResponseException $e){
    echo 'Facebook API Error: '.$e->getMessage();
    session_destroy();
    header('Location: ./');
    exit;
  } catch (Facebook\Exceptions\FacebookSDKException $e){
    echo 'Facebook SDK Error: '.$e->getMessage();
    exit;
  }

  if(isset($_GET['code'])){
    header('Location: fbconfig.php');
  }

} else {
  $fb_login_url = $fb_helper->getLoginUrl('http://localhost/dealshare/fbconfig.php', $permissions);
  header('Location: '.$fb_login_url);
}

?>
