<?php

include '../config.php';

$username = $_GET["username"];

$sql = "SELECT username FROM UserProfile WHERE UserID = '".$username."';";

$result = mysql_query($sql, $con);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

$userexists = "does not exist";
if (mysql_num_rows($result) >=1) {
	$userexists = "User exists<br>"; 
	
}

echo $userexists;
	include '../close.php';

?>