<?php

include 'config.php';


//populate session for jobs
//select
$sql = "SELECT * from jobs";
$result = mysql_query($sql, $con);
$i = 0;
$value = array();
//put value into array
if (mysql_num_rows($result) > 0) {
// output data of each row
while($row = mysql_fetch_assoc($result)) {
	$value[$i] = $row;
	$i ++;
}
} else {
    echo "0 results";
}
echo"<br>";

//encode value into json
$valueJson = json_encode ($value);
$testUpdateJson = json_encode($value[0]);
unset($value[0]["JobID"]);
$testInsertJson = json_encode($value[0]);

$_SESSION["insert"] = $testInsertJson;
$_SESSION["update"] = $testUpdateJson;

//populate session for users
$sql = "SELECT * from user";
$result = mysql_query($sql, $con);
$i = 0;
$value = array();
//put value into array
if (mysql_num_rows($result) > 0) {
// output data of each row
while($row = mysql_fetch_assoc($result)) {
	$value[$i] = $row;
	$i ++;
}
} else {
    echo "0 results";
}
echo"<br>";

//encode value into json
$valueJson = json_encode ($value);
$testUpdateJson = json_encode($value[0]);
unset($value[0]["UserID"]);
$testInsertJson = json_encode($value[0]);
$_SESSION["insertUser"] = $testInsertJson;
$_SESSION["updateUser"] = $testUpdateJson;

//include 'close.php';
?>

<html>
	<h1> Test - JOBS </h1>
	<a href="jobs/showjob.php">Show</a>
	<a href="jobs/insertjob.php">Insert</a>
	<a href="jobs/editjob.php">Edit</a>
	<form action="jobs/deletejob.php" method="post">
  Delete Job ID: <input type="text" name="delete_id">
  <input type="submit" value="Submit">
</form>

<h1> Test - USERS </h1>
	<a href="users/showuser.php">Show</a>
	<a href="users/insertuser.php">Insert</a>
	<a href="users/edituser.php">Edit</a>
	<form action=" users/deleteuser.php" method="post">
  Delete Job ID: <input type="text" name="delete_user">
  <input type="submit" value="Submit">
</form>
</html>