<?php
    include '../config.php';
	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
		$data = json_decode($_SERVER['HTTP_JSON']);
		//var_dump($data);
	
		//select
		$sql = "SELECT * from jobs j, jobs_cat jc
			WHERE jc.JobID = j.JobID
			AND Category = '{$data->Category}' ";
			
		$result = mysql_query($sql, $con);
		if($result === FALSE) { 
		    die(mysql_error()); // TODO: better error handling
		}
		$i = 0;
		$value = array();
		//put value into array
		if (mysql_num_rows($result) > 0) {
		    // output data of each row
		    while($row = mysql_fetch_assoc($result)) {
		    	$value[$i] = $row;
				$i ++;
		    }
		} 
		//encode value into json
		$valueJson = json_encode ($value);
		echo $valueJson;
	} else {
		echo "No HTTP JSON";
	}
	include '../close.php'
?>