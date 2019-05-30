<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
	echo "Hello, ".$_SESSION['name'].'<br>';
	echo "Your ID is ".$_SESSION['id'].'<br>';
?>

<?php
	//add friend to both people's friend list'
	if($_SERVER['REQUEST_METHOD']=="POST"){
		$valid = 1;
    	if (empty($_POST['friend_id'])) {//check user id
    	    $user_err = "<br>Friend ID is required";
    	    $valid = 0;
    	}
    	else{
    	    $user_id = test_input($_POST["friend_id"]);
    	    // check if name only contains letters and whitespace
    	    if (!preg_match("/^[0-9]*$/",$user_id)) {
    	    	$user_err = "<br>Only number allowed";
				$valid = 0;
       	 	}
		}

		if( $valid ){
			//friend id is valid start communicate with db
        	require("../config.php");
        	$dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_db_name'].';';
        	try {
        	    $dbh = new PDO($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        	} catch (PDOException $e) {
        	    echo 'Connection failed: ' . $e->getMessage();
        	}

        	$sth=$dbh->prepare('select * from user_list where id = ?  ;');//check if user exist
        	$sth->execute( array( htmlentities($_POST['friend_id']) ) );
			
			$check_result = $sth->fetch();

			if( count($check_result) != 0){//if exist
				//add both to both friend list db
        		$sth=$dbh->prepare('insert into ?_friend (`friend_id`,`friend_name`,`confirm_friend`) VALUES ( ? , ? , ? ) ;');
				$sth->execute( array($_SESSION['id'], $_POST['friend_id'], $check_result['name'], true ) );
				$sth=$dbh->prepare('insert into ?_friend (`friend_id`,`friend_name`,`confirm_friend`) VALUES ( ? , ? , ? ) ;');
				$sth->execute( array($check_result['id'], $_SESSION['id'], $_SESSION['name'], false ) );
				echo "add successful";
			}

			$dsn = null;
		}
    }
?>
<form method=POST>
	<input type=text name=friend_id placeholder='type friend id'>
	<input type=submit value="add friend">
</form>
