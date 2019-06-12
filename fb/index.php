<?php
require_once('vendor/autoload.php');
require_once('../google/settings.php');

session_start();
?>
<!DOCTYPE HTML5>
<html>
<head>
<title>LOGIN</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href='https://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Rock+Salt' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Schoolbell' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Indie+Flower' rel='stylesheet' type='text/css'>

<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="stylesheet" type="text/css" href="css/util.css">
<link rel="stylesheet" type="text/css" href="css/main.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
<div id="fb-root"></div>
<?php

$fb = new Facebook\Facebook([
  'app_id' => '472713029935315', // Replace {app-id} with your app id
  'app_secret' => '535907ebbb12e0b2297ef875cf803fd1',
  'default_graph_version' => 'v3.3',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://cs06.2y.cc/fb/fb-callback.php', $permissions);

echo '<script type="text/javascript">function reload(){location.href="'.$loginUrl.'"}</script>';
echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
?>

</body>
</html>