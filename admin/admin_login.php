<?php
//session start
session_start();
if( isset($_SESSION['id']) && isset($_SESSION['name']) ){
	echo "<script> location.href='../chat_room'; </script>";
}

$user_err = $password_err = "";
	
function test_input($data) {//check input to avoid pwn
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

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
			echo "<script> location.href='./index.php'; </script>";
		}
	}

}
?>

<html>
<head>
    <title>Admin Login</title>
    <link rel="icon" href="../image.png">
	<!-- Bootstrap core CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<!--<form method="POST">
		Admin:<input type="text" name="user">
		<br>
		Password:<input type="password" name="password">
		<br>
		<input type="submit" value="Login">
	</form>-->

  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Sign In</h5>
            <form class="form-signin" method="POST">
              <div class="form-label-group">
			    Admin Name
                <input type="text" id="inputEmail" name="user" class="form-control" placeholder="Admin Name" required autofocus>
              </div>
              <br>
              <div class="form-label-group">
			    Password
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
			  </div>
			  <br>
              <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="Login">
              <hr class="my-4">

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
