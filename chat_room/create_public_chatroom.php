<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

	date_default_timezone_set('Asia/Taipei');
	echo "Hello, ".$_SESSION['name'].'<br>';
	
	//if post create public chat room
	if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['chatroom_name']) && !empty($_POST['chatroom_friend']) ){	
		$valid = 1;
    	if (empty($_POST['chatroom_name'])) {//check chatroom name
    	    $chatroom_name_err = "<br>chatroom name is required";
    	    $valid = 0;
    	}
    	else{
    	    $chatroom_name = test_input($_POST["chatroom_name"]);
    	    // check if name only contains letters
    	    if (!preg_match("/^[0-9A-Za-z]*$/",$chatroom_name)) {
    	    	$chatroom_name_err = "<br>Only number or alphabet is allowed";
				$valid = 0;
       	 	}
		}

		if( $valid ){
			//dbchatroom_name
			$dbchatroom_name = $_SESSION['id'].time();

			//add to chat_list	
			$sth=$dbh->prepare('insert into chat_list (name , private) value (? ,0)');
			$sth->execute( array($dbchatroom_name) );

			//add to each user_chatlist
			$member = $_POST['chatroom_friend'];
			for($i=0; $i< count($member); $i++ ){
				$sth=$dbh->prepare('insert into '.$member[$i].'_chatlist (chat_room_name, chat_room_displayname, private) value ( ?, ?, 0)');
				$sth->execute( array($dbchatroom_name, $chatroom_name) );
			}


			//create chatroom database
			$sth=$dbh->prepare('CREATE TABLE '.$dbchatroom_name.'(
				idx INT NOT NULL AUTO_INCREMENT,
				sender VARCHAR(100) NOT NULL,
				content VARCHAR(1000) NOT NULL,
				time VARCHAR(20) NOT NULL,
				PRIMARY KEY (idx)
				)');
			$sth->execute();

			//go back to chat_room.php			
			echo "<script> location.href='./index.php'; </script>";
		}
	}


	function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
?>	
	
<b>Friend list</b>
<br>
<table>
<?php
//list friend list
    require("../config.php");
    $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
    try {
    	$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
    }catch (pdoexception $e) {
    	echo 'connection failed: '.$e->getmessage();
    }

    $sth=$dbh->prepare('select * from '.$_SESSION['id'].'_friend order by friend_name desc;');
	$sth->execute();
	echo "<form method=POST>";
	echo "<input type=text name=chatroom_name placeholder='type chat room name'>";
	while($row = $sth->fetch()){//create chat room button to each friend
		echo "<tr><td>".
			"<input type=checkbox name=chatroom_friend[] value=".$row['friend_id'].">".$row['friend_name'].
			" </td></tr>";
	}
	echo "<input type=submit value='Chat'>"
	echo "</form>"

	$dsn = null;
?>
</table>
<br>
