<?php
session_start();
require_once('settings.php');
require_once('google-login-api.php');

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$gapi = new GoogleLoginApi();
		
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
		
		// Get user information
		$user_info = $gapi->GetUserProfileInfo($data['access_token']);
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
	
	require_once("../config.php");

	try {
		require_once('../config.php');
		$dsn='mysql:host=localhost;dbname=cs06';
		$dbh=new PDO($dsn,$CFG['mysql_username'],$CFG['mysql_password']);
    
		$_SESSION['id']=$user_info['id'];
		$_SESSION['name']=$user_info['name'];
		$_SESSION['picture']=$user_info['picture'];
    
		$sql = "CREATE TABLE IF NOT EXISTS ".$user_info['id']."_friend (
			friend_name VARCHAR(45) PRIMARY KEY NOT NULL,
			confirm_friend VARCHAR(45) NOT NULL,
			friend_id VARCHAR(45) NOT NULL
			)";
    
		$sql2 = "CREATE TABLE IF NOT EXISTS ".$user_info['id']."_chatlist (
			chat_room_name VARCHAR(100) PRIMARY KEY NOT NULL,
			chat_room_displayname VARCHAR(100) NOT NULL,
			private INT NOT NULL
			)";
    
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$sth2 = $dbh->prepare($sql2);
		$sth2->execute();

		$sth=$dbh->prepare('insert into user_list (id,name,picture,silence) values (?,?,?,?) ');
		$sth->execute(array($user_info['id'],$user_info['name'],$user_info['picture'],0));
     
    
		echo '<br><br><script>location.href="http://cs06.2y.cc/chat_room/index.php"</script>';
	}
	catch(PDOException $e)
	{
		echo $sql . "<br>" . $e->getMessage();
	}

}
?>
<head>
<style type="text/css">

#information-container {
	width: 400px;
	margin: 50px auto;
	padding: 20px;
	border: 1px solid #cccccc;
}

.information {
	margin: 0 0 30px 0;
}

.information label {
	display: inline-block;
	vertical-align: middle;
	width: 150px;
	font-weight: 700;
}

.information span {
	display: inline-block;
	vertical-align: middle;
}

.information img {
	display: inline-block;
	vertical-align: middle;
	width: 100px;
}

</style>
</head>

<body>

<div id="information-container">
	<div class="information">
		<label>Name</label><span><?= $user_info['name'] ?></span>
	</div>
	<div class="information">
		<label>ID</label><span><?= $user_info['id'] ?></span>
	</div>
	<div class="information">
		<label>Email</label><span><?= $user_info['email'] ?></span>
	</div>
	<div class="information">
		<label>Email Verified</label><span><?= $user_info['verified_email'] == true ? 'Yes' : 'No' ?></span>
	</div>
	<div class="information">
		<label>Picture</label><img src="<?= $user_info['picture'] ?>" />
	</div>
</div>

</body>
</html>
<?php

?>