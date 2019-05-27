

<?php
	require_once("./config.php");

	try {
    $conn = new PDO("mysql:host=".$CFG['mysql_host'].";dbname=".$CFG['mysql_dbname'], $CFG['mysql_username'], $CFG['mysql_password']);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // sql to create table
    $sql = "CREATE TABLE user_list (
		id VARCHAR(45) PRIMARY KEY NOT NULL,
		name VARCHAR(45) NOT NULL
    )"; //may add something else
	
	$sql2 = "CREATE TABLE chat_list (
		name VARCHAR(45) PRIMARY KEY NOT NULL,
		index INT UNSIGNED AUTO_INCREMENT
    )"; //may add something else
    // use exec() because no results are returned
    $conn->exec($sql);
	$conn->exec($sql2);
	
    echo "Table MyGuests created successfully";
	}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
