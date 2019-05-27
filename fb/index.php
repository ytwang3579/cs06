<?php
require_once('vendor/autoload.php');

session_start();
?>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v3.3&appId=2035014616793247&autoLogAppEvents=1"></script>
<?php

$fb = new Facebook\Facebook([
  'app_id' => '472713029935315', // Replace {app-id} with your app id
  'app_secret' => '535907ebbb12e0b2297ef875cf803fd1',
  'default_graph_version' => 'v3.3',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://cs06.2y.cc/fb/fb-callback.php', $permissions);

echo '<div class="fb-login-button" data-width="" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>';
echo '<button class="btn btn-facebook" onclick="reload();"><i class="fab fa-facebook-f"></i> | Log In</button>';
echo '<script type="text/javascript">function reload(){location.href="'.$loginUrl.'"}</script>';
echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
echo '<meta name="google-signin-client_id" content="1018850453433-5jd0vekqvccss7ggjmm8vnp5nfboakiu.apps.googleusercontent.com">';
echo '<div class="g-signin2" data-onsuccess="onSignIn"></div>';
?>
</body>