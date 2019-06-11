<?php
require_once('vendor/autoload.php');

session_start();
$fb = new Facebook\Facebook([
  'app_id' => '472713029935315', // Replace {app-id} with your app id
  'app_secret' => '535907ebbb12e0b2297ef875cf803fd1',
  'default_graph_version' => 'v3.3',
  ]);
  
try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,picture.width(100).height(100).as(picture_small)', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

var_dump($user);
echo '<br><br>Hello ' . $user['name'];

require_once("../config.php");

try {
    require_once('../config.php');
    $dsn='mysql:host=localhost;dbname=cs06';
    $dbh=new PDO($dsn,$CFG['mysql_username'],$CFG['mysql_password']);
    $sth=$dbh->prepare('insert into user_list (id,name) values (?,?) ');
    $sth->execute(array($user['id'],$user['name']));
    
    $_SESSION['id']=$user['id'];
    $_SESSION['name']=$user['name'];
    $_SESSION['picture']='https://graph.facebook.com/'.$user['id'].'/picture?type=large';

    
    $sql = "CREATE TABLE IF NOT EXISTS ".$user['id']."_friend (
            friend_name VARCHAR(45) PRIMARY KEY NOT NULL,
            confirm_friend VARCHAR(45) NOT NULL,
            friend_id VARCHAR(45) NOT NULL,
            picture VARCHAR(500)
    )";
    
    $sql2 = "CREATE TABLE IF NOT EXISTS ".$user['id']."_chatlist (
            chat_room_name VARCHAR(100) PRIMARY KEY NOT NULL,
            chat_room_displayname VARCHAR(100) NOT NULL,
            private INT NOT NULL,
            picture VARCHAR(500)
    )";
    
    $sth = $dbh->prepare($sql);
    $sth->execute();
    $sth2 = $dbh->prepare($sql2);
    $sth2->execute();
     
    
    echo '<br><br><<script>location.href="http://cs06.2y.cc/chat_room/index.php"</script>';
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
