<?php
    // Start the session
    session_start();
    if($_SESSION['id']=="" | $_SESSION['name']==""){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
?>
<b>Friend list</b>
<br>
<table>
<?php
    require("../config.php");
    $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
    try {
    	$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
    }catch (pdoexception $e) {
    	echo 'connection failed: ' . $e->getmessage();
    }

    $sth=$dbh->prepare('select * from ?_friend order by name desc;');
	$sth->execute( array($_SESSION['id'])) );
	while($row = $sth->fetch()){
		echo "<tr><td>".$row['friend_name']."</td>";
		if(!$row['confirm_friend']){
			echo "<td>nf</td>";
		}
		echo "</tr>";
	}

	$dsn = null;
?>
</table>
<br>
<b>Chat room list</b>
<br>
<table>
<?php
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
			$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOExpection $e) {
            echo 'connection failed: ' . $e->getmessage();
        }

        $sth=$dbh->prepare('select * from ?_chatlist order by name desc;');
		$sth->execute( array($_SESSION['id'])) );
		while($friend = $sth->fetch()){
			echo "<tr><td>".$row['chat_room_name']."</td><td>".$row['chat_room_displayname']."</td></tr>";
		}

        $dsn = null;
?>
</table>
