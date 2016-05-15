<?php
	
	//post a json : currently uses sessions instead of post
	//$input_json = $HTTP_RAW_POST_DATA;
	//convert json into array
	//$input_array = json_decode($input_json, true);


	
	if(isset($_SERVER['HTTP_JSON']) == TRUE){
	
		$data = json_decode($_SERVER['HTTP_JSON']);
		var_dump($data);
		
		$email = $data->Email;
		include '../users/checkExistEmail.php';
		include '../config.php';
		if ($emailexists!=1) {
		
			//add array into sql.
			$insertQuery = "
			INSERT INTO  `nimblyla_jiffyjobs`.`user` (
			`Username`,
			`Email`,
			`Password` ,
			`DOB` ,
			`Name` ,
			`FacebookID` ,
			`Gender`
			)
			VALUES (
			'NULL',
			'{$data->Email}',
			'{$data->Password}',
			'NULL',
			'NULL',
			'NULL',
			'NULL'
			);";
		
			$result = mysql_query($insertQuery, $con);
			
			if  ($result){
				echo "New record created successfully";
			} else {
		    		echo "Error: " . $sql . "<br>" . mysql_error($con);
			}
		} else echo "email exist: ".$email." !";
	
	}
	else{
		echo "FAIL";
	}
		

	
	
	include '../close.php';
?>