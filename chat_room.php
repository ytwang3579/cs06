<?php
    // Start the session
    session_start();
    if($_SESSION['id']=="" | $_SESSION['name']==""){
        echo "<script> location.href='./index.php'; </script>";
        exit();
    }

    date_default_timezone_set('Asia/Taipei');

    if( ($_SERVER['REQUEST_METHOD']=="POST") && !empty($_POST['message']) ){
        require("./config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
            $dbh = new PDO($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        $sth=$dbh->prepare('insert into message (`content`,`nickname`, `gender`, `date`) VALUES ( ? , ? , ? , ? );');
        $sth->execute( array( htmlentities($_POST['message']), htmlentities($_SESSION['nickname']), $_SESSION['gender'], date("m/d H:i:s") ) );

        $dsn = null;
    }
?>
