<?php
    session_start();
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['ban']))
    {
        func();
    }
    function func()
    {
        require("../config.php");
        $dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
        try {
            $dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
        } catch (PDOExpection $e) {
            echo 'connection failed: '.$e->getmessage();
        }
        if($_POST['ban']=="ban")
          $sth=$dbh->prepare(' update user_list set silence=1  where id = '.$_POST['id'].';');
        else
          $sth=$dbh->prepare(' update user_list set silence=0  where id = '.$_POST['id'].';');
        $sth->execute();
    }
    echo "<script> location.href='../admin'; </script>";
       
?>