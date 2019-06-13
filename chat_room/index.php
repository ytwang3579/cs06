<?php
    // Start the session
    session_start();
    if( !isset($_SESSION['id']) | empty($_SESSION['id']) | !isset($_SESSION['name']) | empty($_SESSION['name']) ){
        echo "<script> location.href='../login'; </script>";
        exit();
    }
	  //if admin redirection to admin page
	  else if($_SESSION['id']=='admin'&&$_SESSION['name']=='Admin'){
        echo "<script> location.href='../admin';</script>";
    }
    date_default_timezone_set('Asia/Taipei');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CHATROOM-index</title>
<link rel="icon" href="../image.png">
<link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
<link rel="stylesheet" href="./style.css">
<script src="https://kit.fontawesome.com/df3e0e4f87.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
</head>
<body>
<div class="slack">
  <div class="sidebar">
    <div class="titlebtn">
      <div class="team-menu-info">
        <button class="pagetitle" onclick="location.reload();">CS 0.6</button> <!--reload page-->
        <span class="username"><?= $_SESSION['name'] ?></span>
      </div>
      <span onclick="location.href ='logout.php'" 
            style="cursor:pointer;"> <!--logout to login page-->
         <i class="fas fa-sign-out-alt"></i>
      </span>
  </div>
	<div class="profile">
	  <a href="https://ani.gamer.com.tw" target="_blank">
	  <img src="<?= $_SESSION['picture'] ?>" style="width:200px; height:200px;"/> <!--user's picture'-->
	  </a>
    </div>
    
    <div class="chatroomlist">
      <h2 class="chatroomlist-title">
        <span>
          PUBLIC CHATROOM
        </span>
        <button class="ion-ios-plus-outline chatroomlist-add" onclick="create_public_chatroom();"></button>
      </h2>
      <ul class="chatroomlist-list">
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
          if($friend['private']==0){
            echo '<li class="chatroomlist-item"><button class="chatroomlist-button" onclick="OpenChatRoom(\''.$friend['chat_room_name'].'\',\''.$friend['chat_room_displayname'].'\')"><span>'.$friend['chat_room_displayname'].'</span></button></li>';
          }
        }

        $dsn = null;
?>
        <!-- ONLY FOR DEBUG
        <li class="chatroomlist-item">
          <button class="chatroomlist-button">
            <span>general public</span>
          </button>
        </li> 
        -->
      </ul>
    </div>

    <div class="chatroomlist">
      <h2 class="chatroomlist-title">
        <span>
          PRIVATE CHATROOM
        </span>
        <button class="ion-ios-plus-outline chatroomlist-add" onclick="create_private_chatroom();"></button>
      </h2>
      <ul class="chatroomlist-list">
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
          if($friend['private']==1){
            echo "<li class='chatroomlist-item'><button class='chatroomlist-button' onclick=\"OpenChatRoom('".$friend['chat_room_name']."','".$friend['chat_room_displayname']."')\"><span>".$friend['chat_room_displayname']."</span></button></li>";
          }
        }

        $dsn = null;
?>
        <!-- ONLY FOR DEBUG 
        <li class="chatroomlist-item">
          <button class="chatroomlist-button">
            <span>general</span>
          </button>
        </li>
        -->
      </ul>
    </div>

    <div class="friendlist">
      <h2 class="friendlist-heading">
        <span>
          FRIEND
        </span>
        <button class="ion-ios-plus-outline friendlist-add" onclick="addfriend();"></button>
      </h2>
      <ul class="friendlist-list">
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
          echo '<li class="friendlist-item"><button class="friendlist-button"><span>'.$row['friend_name'].'</span></button></li>';
        }

        $dsn = null;
?>
        <!-- ONLY FOR DEBUG 
        <li class="friendlist-item">
          <button class="friendlist-button friendlist-button-online">
            <span>Harry Potter</span>
          </button>
        </li>
        <li class="friendlist-item">
            <button class="friendlist-button">
              <span>Ginny Weasley</span>
            </button>
        </li>
        -->
      </ul>
    </div>
  </div>
  <div class="main">

  <iframe id = "chat_window"></iframe>

  </div>
</div>

<!-- script for iframe -->
<script>
	//init variable
	var chat_list = $('#chat_list');
	var friend_list = $("#friend_list");
	var now_room = "";
	var now_room_name = "";
	var chat_window = $("#chat_window");
	var name = <?php echo json_encode($_SESSION['name']);?>;
  var flag = false;

  function OpenChatRoom(chatroomid, chatroomname){
     now_room = chatroomid;
	 now_room_name = chatroomname;
		 chat_window.attr("src", "http://cs06.2y.cc/node_server/index.php");
     flag = true;
	}

  function addfriend(){
    $("#chat_window").attr("src", "http://cs06.2y.cc/chat_room/add_friend.php");
    flag = true;
  }

  function create_private_chatroom(){
    $("#chat_window").attr("src", "http://cs06.2y.cc/chat_room/create_private_chatroom.php");
    flag = true;
  }

  function create_public_chatroom(){
    $("#chat_window").attr("src", "http://cs06.2y.cc/chat_room/create_public_chatroom.php");
    flag = true;
  }

  

</script>

</body>
</html>
