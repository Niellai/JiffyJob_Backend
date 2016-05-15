<?php
    include '../config.php';

    $content = file_get_contents('php://input');

    if($content){
        $data = json_decode($content, true);
        
        $profileData = file_get_contents("https://graph.facebook.com/v2.5/" . $data['userID'] . "?fields=id,birthday,first_name,last_name,email,gender,education,location,picture&access_token=" . $data['tokenString']); 

        $results = json_decode($profileData, true);
        
        //Format Gender
        if($results['gender'] == 'male'){
            $gender = 'M'; }
        else{
            $gender = 'F';
        }
        
        //Format Birthday
        $dob = date("Y-m-d" , strtotime($results['birthday']));
        
        $sql = "SELECT `Email` FROM UserProfile WHERE `Email` = '". $results['email'] . "';";

        $result = mysql_query($sql, $con);
        if (!$result) {
            die('Invalid query: ' . mysql_error());
        }

        $emailexists = 0;
        if (mysql_num_rows($result) > 0) {
            $emailexists = 1; 
        }
        
        if ($emailexists != 1) {
			
		//Add array into SQL
		$insertQuery = "INSERT INTO `nimblyla_jiffyjobs`.`UserProfile` (`FacebookID`, `Email`, `DOB`,`FirstName`,`LastName`,`Gender`,`Country`)
				VALUES ('{$results['id']}', '{$results['email']}', '{$dob}', '{$results['first_name']}', '{$results['last_name']}', '{$gender}', '{$results['location']['name']}');";
			
			$dbresult = mysql_query($insertQuery, $con);
			
			if  ($dbresult){
				echo "New record created successfully";
				
				//Return User ID to Android - implement in future
			} else {
		    		echo "Error: " . $sql . "<br>" . mysql_error($con);
			}
		} else echo "Email Exist: " . $results['email'] . "! Just Login";
	
	}
	else{
		echo "Login Failed";
	}
	
	include '../close.php';
?>