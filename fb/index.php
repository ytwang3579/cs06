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
<div class="container">
    <div class="login-space p-l-110 p-r-110 p-t-62 p-b-33">
        <span class="page-title">
        CS 0.6
        </span>
        <br>
        <span class="page-subtitle">
        blah blah blah
        </span>
        <form class="login-form flex-sb flex-w">
            <span class="login-title">
            SIGN IN WITH
            </span>
            <a href="#" class="fb-login m-t-50 m-b-20" onclick="reload();">
                <i class="fa fa-facebook-official"></i>
                FACEBOOK
            </a>
            <br>
            <a href="<?= 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>" class="gg-login m-b-20">
                <img src="./css/icon-google.webp" alt="GOOGLE">
                GOOGLE
            </a>

            <div class="login-footer">
            
            </div>
        </form>
    </div>
</div>




</body>
</html>