<?php
    // Start the session
    session_start();
    if($_SESSION['id']=="" | $_SESSION['name']==""){
        echo "<script> location.href='./index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');

    if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['message']) ){
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
            $dbh = new PDO($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        $sth=$dbh->prepare('');
		$sth->execute( );
		$return = $sth->fetch();

        $dsn = null;
    }
?>
