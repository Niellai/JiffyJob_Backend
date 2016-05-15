<?php
	include '../config.php';
	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
		$data = json_decode($_SERVER['HTTP_JSON']);
		var_dump($data);
		
		$sql = "UPDATE `Reviews` SET 
		JobID = '{$data->JobID}',
		CompanyID = '{$data->CompanyID}',
		UserID = '{$data->UserID}',
		Rating = '{$data->Rating}',
		Description= '{$data->Description}', WHERE 
		ReviewID='{$input_array['ReviewID']}';
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