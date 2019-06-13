<?php
    // Start the session
    session_start();
    if( isset($_SESSION['id']) && isset($_SESSION['name']) ){
      if($_SESSION['id']=='admin' &&  $_SESSION['name']=='Admin'){
?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script src="resource/socket.io.js"></script>
<script src="https://kit.fontawesome.com/df3e0e4f87.js"></script>
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="main.css">
<link rel="icon" href="../image.png">
</head>

<body>
<div onclick="location.href ='../chat_room/logout.php'" class="btn btn-outline-success my-2 my-sm-0" style="cursor:pointer;">
         <i class="fas fa-sign-out-alt"></i>
         <span>Logout </span>
    </div>
<div class="friend_list">

<table id = "friend_list" class="table table-striped table-light">
<thead>User list</thead>
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
		echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td>";
		if($row['silence']==0){
                  echo "<td><form action='ban.php' method='post'>
                          <input type='hidden' name='id' value='".$row['id']."'>
                          <input type='submit' name='ban' value='ban'>
                        </form></td></tr>";
                }
                else{
                  echo "<td><form action='ban.php' method='post'>
                          <input type='hidden' name='id' value='".$row['id']."'>
                          <input type='submit' name='ban' value='unban'>
                        </form></td></tr>";
                
                }
	}

	$dsn = null;
?>
</table>
</div>
<br>
<form id="broadcast" method="post">
         <i class="fas fa-bullhorn"></i>
         <i class="fas fa-bullhorn"></i>
         <i class="fas fa-bullhorn"></i>
         <input id="bmsg"  class="form-control" type="text" placeholder="broadcast"/>
         <button class="btn btn-outline-success my-2 my-sm-0">Broadcast</button>
</form>
<br>
<div class="room_list">
<br>
<table id= "chat_list" class="table table-striped table-light">
<thead>Chat room list</thead>
<?php
//list chat room
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
			$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOExpection $e) {
            echo 'connection failed: '.$e->getmessage();
        }

        $sth=$dbh->prepare('select * from chat_list order by name desc;');
		$sth->execute();
		$privacy="";
		while($info = $sth->fetch()){
		       $privacy="";
		       if($info['private']==1)$privacy="private";
                       else if($info['private']==0)$privacy="public";
		
                       echo "<tr><td>".$info['name']."</td><td>".$info['idx']."</td><td>".$privacy."</td></tr>";
		}

        $dsn = null;
?>
</table>
<script>
var socket = io('http://cs06.2y.cc:25565');
 $(function (){
	 $('#broadcast').submit(function(e){
		  e.preventDefault(); // prevents page reloading
		  socket.emit('broadcast message', $('#bmsg').val());//emit broadcast message
		  $('#bmsg').val('');
		  return false;
		});
 });
 
</script>
</body>
</html>
<?php
 }
 else{
    echo 'curiosity kills the cat';
 }
}
else{
  echo 'curiosity kills the cat';
}
?>