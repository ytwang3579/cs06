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
  $response = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
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
    echo "<script>alert('Acount Created Successfully XD')</script>";
    echo '<br><br><script>location.href="../chat_room/chat_room.php"</script>';
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
