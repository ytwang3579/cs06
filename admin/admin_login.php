<?php
//session start
session_start();

$user_err = $password_err = "";

if($_SERVER['REQUEST_METHOD']=="POST"){//if post check information
	$valid = 1;
    if (empty($_POST["user"])) {//check user id
        $user_err = "<br>Name is required";
        $valid = 0;
    } 
    else{
        $user = test_input($_POST["user"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z0-9 ]*$/",$user)) {
          $user_err = "<br>Only letters and white space allowed"; 
          $valid = 0;
        }
	}

	if (empty($_POST["password"])) {//check password
        $password_err = "<br>Password is required";
        $valid = 0;
    }
    else{
        $password = test_input($_POST["password"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z0-9 ]*$/",$password)) {
          $password_err = "<br>Only letters and white space allowed";
          $valid = 0;
        }
	}
	
	if($valid){
		require("../config.php");
		if( $CFG['admin'] == $user && $CFG['admin_pw'] == $password ){
			$_SESSION['id']='admin';
			$_SESSION['name']='Admin';
			echo "<script> location.href='./admin.php'; </script>";
		}
	}
	
	function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
   	}
}
?>

<html>
<head>
<title>Admin Login</title>
</head>
<body>
	<form method="POST">
		Admin:<input type="text" name="user">
		<br>
		Password:<input type="password" name="password">
		<br>
		<input type="submit" value="Login">
	</form>
</body>
</html>
