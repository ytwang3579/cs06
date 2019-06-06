<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

	date_default_timezone_set('Asia/Taipei');
	echo "Hello, ".$_SESSION['name'].'<br>';
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
