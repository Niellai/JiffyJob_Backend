<?php
    include '../config.php';
	
	
	//select
	$sql = "SELECT * from Reviews";
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
	echo $valueJson;
	
	include '../close.php'
?>