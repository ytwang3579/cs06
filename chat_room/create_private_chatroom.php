<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
	echo "<div class='card'><div class='card-header'>Hello, ".$_SESSION['name']."<div class='card-body'>";
	//if post create private chat room
	if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['chatroom_friend_id']) && !empty($_POST['chatroom_friend_name']) ){//create chat room	
    	require("../config.php");
    	$dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
    	try {
    		$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
    	}catch (pdoexception $e) {
    		echo 'connection failed: '.$e->getmessage();
    	}

		
		//check if there is another private chat room created
		$sth=$dbh->prepare('select * from chat_list where name="'.$_SESSION['id'].'_'.$_POST['chatroom_friend_id'].'" or name="'.$_POST['chatroom_friend_id'].'_'.$_SESSION['id'].'"');
		$sth->execute();
		$count = $sth->fetch();
		
		if( !($count) ){//if no other chat room created
		
			//create chat room name
			$chatroom_name=$_SESSION['id'].'_'.$_POST['chatroom_friend_id'];

			//add chat room to both userid_chatlist and chat_list
			$sth=$dbh->prepare('insert into chat_list (name , private) value (? , 1)');
			$sth->execute( array($chatroom_name) );
			$sth=$dbh->prepare('insert into '.$_SESSION['id'].'_chatlist (chat_room_name, chat_room_displayname, private) value ( ?, ?, 1)');
			$sth->execute( array($chatroom_name, $_POST['chatroom_friend_name']) );
			$sth=$dbh->prepare('insert into '.$_POST['chatroom_friend_id'].'_chatlist (chat_room_name, chat_room_displayname, private) value ( ?, ?, 1)');
			$sth->execute( array($chatroom_name, $_SESSION['name']) );
			
			//create chat room data base
			$sth=$dbh->prepare('CREATE TABLE '.$chatroom_name.'(
				idx INT NOT NULL AUTO_INCREMENT,
				sender VARCHAR(100) NOT NULL,
				content VARCHAR(1000) NOT NULL,
				time VARCHAR(20) NOT NULL,
				vote JSON NOT NULL,
				PRIMARY KEY (idx)
				)');
			$sth->execute();
			
			echo "Create Successfully!!";
			echo "<script>window.parent.location.reload();</script>";	
		}
		else{
			echo "Chat room has already exist!!";
		}
	}
?>
<head>
	<link rel="stylesheet" type="text/css" href="style4php.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<b>Friend list</b>
<br>
<table class="table">
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
				"<input type='hidden' name=chatroom_friend_id value=".$row['friend_id']." style='display:none'>".
				"<input type='hidden' name=chatroom_friend_name value=".$row['friend_name']." style='display:none'>".
				"<input type='submit' class='btn btn-secondary' value='Chat' >".
			"</form>".
			" </td></tr>";
	}

	$dsn = null;
?>
</table>
</div>
</div>
</div>

</body>