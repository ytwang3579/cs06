<?php
require_once('vendor/autoload.php');

session_start();
$fb = new Facebook\Facebook([
  'app_id' => '472713029935315', // Replace {app-id} with your app id
  'app_secret' => '535907ebbb12e0b2297ef875cf803fd1',
  'default_graph_version' => 'v3.3',
  ]);
$helper = $fb->getRedirectLoginHelper();

if(isset($_GET['state'])) {
      if($_SESSION['FBRLH_' . 'state']) {
          $_SESSION['FBRLH_' . 'state'] = $_GET['state'];
      }
}

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  //echo 'Graph returned an error: ' . $e->getMessage();
  //exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  //echo 'Facebook SDK returned an error: ' . $e->getMessage();
  //exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('472713029935315'); // Replace {app-id} with your app id

$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
 // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }
}


// User is logged in with a long-lived access token.


// Followings fetch users' information and redirect to main page
try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,picture.width(100).height(100).as(picture_small)', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

require_once("../../config.php");

try {
    require_once('../../config.php');
    $dsn='mysql:host=localhost;dbname=cs06';
    $dbh=new PDO($dsn,$CFG['mysql_username'],$CFG['mysql_password']);
    
    $_SESSION['id']=$user['id'];
    $_SESSION['name']=$user['name'];
    $_SESSION['picture']='https://graph.facebook.com/'.$user['id'].'/picture?type=large';

    
    $sql = "CREATE TABLE IF NOT EXISTS ".$user['id']."_friend (
            friend_name VARCHAR(45) PRIMARY KEY NOT NULL,
            confirm_friend VARCHAR(45) NOT NULL,
            friend_id VARCHAR(45) NOT NULL
    )";
    
    $sql2 = "CREATE TABLE IF NOT EXISTS ".$user['id']."_chatlist (
            chat_room_name VARCHAR(100) PRIMARY KEY NOT NULL,
            chat_room_displayname VARCHAR(100) NOT NULL,
            private INT NOT NULL
    )";
    
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $sth2 = $dbh->prepare($sql2);
    $sth2->execute();


    $sth=$dbh->prepare('insert into user_list (id,name,picture,silence) values (?,?,?,?) ');
    $sth->execute(array($user['id'],$user['name'],'https://graph.facebook.com/'.$user['id'].'/picture?type=large',0));
     
    // redirect to main page
    echo '<br><br><<script>location.href="http://cs06.2y.cc/chat_room/index.php"</script>';
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }


