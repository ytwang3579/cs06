<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/'; </script>";
        exit();
    }
    else if($_SESSION['id']=='admin'&&$_SESSION['name']=='Admin'){
        echo "<script> location.href='../admin';</script>";
    }

    date_default_timezone_set('Asia/Taipei');
?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<link rel="stylesheet" href="main.css">
</head>

<body>

<div class="nav">
	<div class="information">
		<label>Hello</label><span><?= $_SESSION['name'] ?></span>
		<label style="overflow:hidden;">ID</label><span style="overflow:hidden;"><?= $_SESSION['id'] ?></span>
		<label style="overflow:hidden;">Picture</label><img src="<?= $_SESSION['picture'] ?>" />
	</div>
</div>

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

    $sth=$dbh->prepare('select * from '.$_SESSION['id'].'_friend order by friend_name desc;');
	$sth->execute();
	while($row = $sth->fetch()){
		echo "<tr><td>".$row['friend_name']."</td></tr>";
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

        $sth=$dbh->prepare('select * from '.$_SESSION['id'].'_chatlist order by chat_room_name desc;');
		$sth->execute();
		while($friend = $sth->fetch()){
			echo "<tr><td>".$friend['chat_room_name']."</td><td>".$friend['chat_room_displayname']."</td></tr>";
			echo "<tr><td> <button onclick = \"open_chat_window('".$friend['chat_room_name']."')\">".$friend['chat_room_displayname']."</button></td></tr>";//need to set onclick function to go into a chat room
		}

        $dsn = null;
?>
</table>
</div>
<button onclick="location.href='./create_private_chatroom.php';">Create Private Chat Room</button>
<button onclick="location.href='./create_public_chatroom.php';">Create Public Chat Room</button>
<button onclick="location.href='./add_friend.php';" >Add Friend</button>
<iframe id = "chat_window"></iframe>


<script>
	//init variable
	var chat_list = $('#chat_list');
	var friend_list = $("#friend_list");
	var now_room = "";
	var chat_window = $("#chat_window");
	var name = <?php echo json_encode($_SESSION['name']);?>;
	
	//click to open chat window (put in iframe)
	function open_chat_window(chat_name){
		now_room = chat_name;
		chat_window.attr("src", "https://cs06.2y.cc/node_server/index.html");
	}
	
</script>
</body>
</html>
