<?php
	include '../config.php';
	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
		$data = json_decode($_SERVER['HTTP_JSON']);
		var_dump($data);
			
	
		$sql = "UPDATE `user` SET 
		`Password`='{$data->Password}',
		`DOB`='{$data->DOB}',
		`Name`='{$data->Name}',
		`FacebookID`='{$data->FacebookID}',
		`Gender`= '{$data->Gender}' WHERE 
		`UserID`='{$data->UserID}';
		";
		
		$result = mysql_query($sql, $con);
		
		if  ($result){
		echo "Record updated";
		} else {
		echo "Error: " . $sql . "<br>" . mysql_error($con);
		}
	}
	else{
		echo "FAIL";
	}
include '../close.php';

?>