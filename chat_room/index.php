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

    $sth=$dbh->prepare('select * from ?_friend order by name desc;');
	$sth->execute( array($_SESSION['id']) );
	while($row = $sth->fetch()){
		echo "<tr><td>".$row['friend_name']."</td></tr>";
	}

	$dsn = null;
?>
</table>
<br>
<b>Chat room list</b>
<br>
<table>
<?php
//list chat room
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
			$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOExpection $e) {
            echo 'connection failed: '.$e->getmessage();
        }

        $sth=$dbh->prepare('select * from ?_chatlist order by name desc;');
		$sth->execute( array($_SESSION['id']) );
		while($friend = $sth->fetch()){
			echo "<tr><td>".$row['chat_room_name']."</td><td>".$row['chat_room_displayname']."</td></tr>";
		}

        $dsn = null;
?>
</table>

<button onclick="location.href='./add_friend.php';" >Add Friend</button>
