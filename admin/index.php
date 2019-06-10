<?php
    // Start the session
    session_start();
    if( $_SESSION['id']=='admin' &&  $_SESSION['name']=='Admin'){
?>

<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script src="resource/socket.io.js"></script>
<link rel="stylesheet" href="main.css">
</head>

<body>
<div class="friend_list">
<b>User list</b>
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
<form id="broadcast" method="post">
broadcast<input id="bmsg"  type="text" placeholder="broadcast"/>
</form>
<script>
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
?>