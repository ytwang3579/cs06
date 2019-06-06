<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../fb/index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');
    echo "Hello, ".$_SESSION['name'].'<br>';
    var_dump($_SESSION['picture']);
?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<style type="text/css">

#information-container {
	width: 400px;
	margin: 50px auto;
	padding: 20px;
	border: 1px solid #cccccc;
}

.information {
	margin: 0 0 30px 0;
}

.information label {
	display: inline-block;
	vertical-align: middle;
	width: 150px;
	font-weight: 700;
}

.information span {
	display: inline-block;
	vertical-align: middle;
}

.information img {
	display: inline-block;
	vertical-align: middle;
	width: 100px;
}

</style>

</head>

<body>

<div id="information-container">
	<div class="information">
		<label>Name</label><span><?= $_SESSION['name'] ?></span>
	</div>
	<div class="information">
		<label>ID</label><span><?= $_SESSION['id'] ?></span>
	</div>
	<div class="information">
		<label>Picture</label><img src="<?= $_SESSION['picture'] ?>" />
	</div>
</div>

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
<br>
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
			echo "<tr><td> <button onclick = \"open_chat_window(' ".$friend['chat_room_name']."')\">".$friend['chat_room_displayname']."</button></td></tr>";//need to set onclick function to go into a chat room
		}

        $dsn = null;
?>
</table>
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
		chat_window.attr("src", "http://cs06.2y.cc:25565");
	}
	
</script>
</body>
</html>

















