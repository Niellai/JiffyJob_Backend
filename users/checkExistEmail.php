<?php

include '../config.php';

$sql = "SELECT `Email` FROM UserProfile WHERE `Email` = '". $results['email'] . "';";

$result = mysql_query($sql, $con);
if (!$result) {
    die('Invalid query: ' . mysql_error());
}

$emailexists = 0;
if (mysql_num_rows($result) >0) {
	$emailexists = 1; 
}
echo $emailexists;
include '../close.php';
?>