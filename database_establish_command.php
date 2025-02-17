

<?php
	require_once("./config.php");

	try {
    $conn = new PDO("mysql:host=".$CFG['mysql_host'].";dbname=".$CFG['mysql_dbname'], $CFG['mysql_username'], $CFG['mysql_password']);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE user_list (
		id VARCHAR(45) PRIMARY KEY NOT NULL,
		name VARCHAR(45) NOT NULL,
        picture VARCHAR(500) NOT NULL,
        silence INT(10) NOT NULL
    )"; //may add something else
	
	$sql2 = "CREATE TABLE chat_list (
		name VARCHAR(45) NOT NULL,
		idx INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		private INT NOT NULL
    )"; //may add something else
    // use exec() because no results are returned
    $sth = $conn->prepare($sql);
	$sth->execute();
	$sth2 = $conn->prepare($sql2);
	$sth2->execute();
	
    echo "Table user_list, chat_list created successfully";
	}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
