<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../login/fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
	echo "<div class='card'><div class='card-header'>Hello, ".$_SESSION['name']."<div class='card-body'>";
	echo "<h5 class='card-title'>Your ID is ".$_SESSION['id']."</h5>";
?>
<!DOCTYPE html>
<head>
	<link rel="stylesheet" type="text/css" href="style4php.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?php
	$user_err = ' ';
	//add friend to both people's friend list'
	if($_SERVER['REQUEST_METHOD']=="POST"){
		$valid = 1;
    	if (empty($_POST['friend_id'])) {//check user id
    	    $user_err = "Friend ID is required<br>";
    	    $valid = 0;
    	}
    	else{
    	    $user_id = test_input($_POST["friend_id"]);
    	    // check if name only contains letters and whitespace
    	    if (!preg_match("/^[0-9]*$/",$user_id)) {
    	    	$user_err = "Only number allowed<br>";
				$valid = 0;
       	 	}
		}

		if( $valid && ($_SESSION['id']!=$_POST['friend_id']) ){
			//friend id is valid start communicate with db
        	require("../config.php");
        	$dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        	try {
        	    $dbh = new PDO($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        	} catch (PDOException $e) {
        	    echo 'Connection failed: ' . $e->getMessage();
        	}

        	$sth=$dbh->prepare('select * from user_list where id = ?  ;');//check if user exist
        	$sth->execute( array( htmlentities($_POST['friend_id']) ) );
			
			if( $check_result = $sth->fetch() ){//if exist
				//add both to both friend list db
        		$sth=$dbh->prepare('insert into '.$_SESSION['id'].'_friend (`friend_id`,`friend_name`,`confirm_friend`) VALUES ( ? , ? , ? ) ;');
				$sth->execute( array( $_POST['friend_id'], $check_result['name'], true ) );
				$sth=$dbh->prepare('insert into '.$_POST['friend_id'].'_friend (`friend_id`,`friend_name`,`confirm_friend`) VALUES ( ? , ? , ? ) ;');
				$sth->execute( array( $_SESSION['id'], $_SESSION['name'], true ) );
				echo "add successful<br>";
				echo "<script>window.parent.location.reload();</script>";
			}
			else {
				echo "Friend not found<br>";
			}

			$dsn = null;
		}
	}
   
	function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
	}
	echo $user_err;//print error message
?>
<form method="POST">
	<input type="text" name="friend_id" class="form-control" placeholder="type friend id">
	<br>
	<input type="submit" class="btn btn-secondary" value="add friend" style="right:35px;position:fixed;">
</form>
</div>
</div>
</div>
</body>
