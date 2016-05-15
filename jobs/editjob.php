<?php
	include '../config.php';
	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
		$data = json_decode($_SERVER['HTTP_JSON']);
		var_dump($data);

	$sql = "UPDATE `jobs` SET 
	`CreatorUserID`='{$data->CreatorUserID}',
	`JobDescription`='{$data->JobDescription}',
	`LocationID`='{$data->LocationID}',
	`SalaryUpperRange`='{$data->SalaryUpperRange}',
	`SalaryLowerRange`='{$data->SalaryLowerRange}',
	`SalaryType`= '{$data->SalaryType}',
	`SalaryCurrency`= '{$data->SalaryCurrency}',
	`JobStatus`= '{$data->JobStatus}' WHERE 
	`JobID`='{$data->JobID}';
	";
	
	$result = mysql_query($sql, $con);
	
	if  ($result){
	echo "Record updated";
	} else {
	    echo "Error: " . $sql . "<br>" . mysql_error($con);
	}
	}
	else echo "FAIL";
	
include '../close.php';

?>