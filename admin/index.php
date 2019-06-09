<?php
    // Start the session
    session_start();
?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<link rel="stylesheet" href="main.css">
</head>

<body>
<div class="friend_list">
<b>Friend list</b>
<br>
<table id = "friend_list">
<?php
//list friend list
    require("../config.php");
    $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
    try {
    	$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
    }catch (pdoexception $e) {
    	echo 'connection failed: '.$e->getmessage();
    }

    $sth=$dbh->prepare('select * from user_list order by id desc;');
	$sth->execute();
	while($row = $sth->fetch()){
		echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td></tr>";
	}

	$dsn = null;
?>
</table>
</div>

<br>

<div class="room_list">
<b>Chat room list</b>
<br>
<table id= "chat_list">
<?php
//list chat room
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
			$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOExpection $e) {
            echo 'connection failed: '.$e->getmessage();
        }

        $sth=$dbh->prepare('select * from chatlist order by name desc;');
		$sth->execute();
		while($info = $sth->fetch()){
			echo "<tr><td>".$info['name']."</td><td>".$info['id']."</td><td>".$info['private']."</td></tr>";
		}

        $dsn = null;
?>
</table> 
</body>
</html>