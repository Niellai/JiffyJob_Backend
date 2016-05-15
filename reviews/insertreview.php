<?php
	include '../config.php';
	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
		$data = json_decode($_SERVER['HTTP_JSON']);
		var_dump($data);
		
		//add array into sql.
		$sql = "
		INSERT INTO  `nimblyla_jiffyjobs`.`Reviews` (
		`JobID` ,
		`CompanyID` ,
		`UserID` ,
		`Rating` ,
		`Description`
		)
		VALUES (
		'{$data->JobID}',
		'{$data->CompanyID}',
		'{$data->UserID}',
		'{$data->Rating}',
		'{$data->Description}',
		);
		";
	
		$result = mysql_query($sql, $con);
		
		if  ($result){
		echo "New record created successfully";
		} else {
	    echo "Error: " . $sql . "<br>" . mysql_error($con);
		}
	}
	else{
		echo "FAIL";
	}
	
include '../close.php';

?>