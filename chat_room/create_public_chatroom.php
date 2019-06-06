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
	if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['chatroom_name']) ){

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
