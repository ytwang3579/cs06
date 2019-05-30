<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
	echo "Hello, ".$_SESSION['name'].'<br>';

	if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['chatroom_friend_id']) && !empty($_POST['chatroom_friend_name']) ){//create chat room	
    	require("../config.php");
    	$dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
    	try {
    		$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
    	}catch (pdoexception $e) {
    		echo 'connection failed: '.$e->getmessage();
    	}

    	$sth=$dbh->prepare('select form chat_list');//check if there is another private chat room created
		$sth->execute( array($_SESSION['id']) );

		//check chat_list db largest auto increment number
		
		//create chat room name
		
		//add chat room to both userid_chatlist
		
		//go back to chat_room.php
		
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

    $sth=$dbh->prepare('select * from ?_friend order by name desc;');
	$sth->execute( array($_SESSION['id']) );
	while($row = $sth->fetch()){//create chat room button to each friend
		echo "<tr><td>".$row['friend_name']."</td><td>".
			"<form method=POST>".
				"<input type=hidden name=chatroom_friend_id value=".$row['friend_id']." style='display:none'>".
				"<input type=hidden name=chatroom_friend_name value=".$row['friend_name']." style='display:none'>".
				"<input type=submit value='Chat' >".
			"</form>".
			" </td></tr>";
	}

	$dsn = null;
?>
</table>
<br>
