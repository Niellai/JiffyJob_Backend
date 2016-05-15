<!DOCTYPE html>
<html>
<body>

<form action="http://www.nimblylabs.com/jjws/applications/newJobApplication.php" method="post">
Search input <input type="text" name="jsonstring"><br>
<input type="submit">
</form>

<?php	
	include '../config.php';
	
    $userID;
    $jobID;
    $shortcv;

	/*Actual code for deployment*/
	$jsonString = file_get_contents('php://input');
	
	/*Debug code - $_POST can only be use for debugging */
 	//$jsonString = $_POST['jsonstring'];

    if(isset($jsonString)){
        extractInfo($jsonString);

        if(checkApplication($userID, $jobID) == false){
            //add array into sql.
            $insertQuery = "
            INSERT INTO `UserJobApplication` (
            `UserID`,
            `JobID`,
            `Status` ,
            `ShortCV`
            )
            VALUES (
            '{$userID}',
            '{$jobID}',
            0,
            '{$shortcv}'
            );";

            $result = mysql_query($insertQuery, $con);

            if($result){
                echo "Status 200 - Job Application successfully added to DB!";
            } else {
                    echo "Status 406 - Error: " . $sql . "<br>" . mysql_error($con);
            }
        }
        else 
            echo "Status 304 - You have already applied for this job!";
    }
	function extractInfo($jsonString){
		global $userID, $jobID, $shortcv;
		$jsonArray = json_decode($jsonString, true);
		$userID = $jsonArray ['UserID'];
        $jobID = $jsonArray['JobID'];
        $shortcv = $jsonArray['ShortCV'];
		echo "UserID: " . $userID . "<br>";
		echo "JobID: " . $jobID . "<br>";
		echo "ShortCV: " . $shortcv . "<br>";
	}

	function checkApplication($uid, $jid){

		$check = "SELECT * FROM `UserJobApplication` WHERE `UserID` = '" . $uid . "' AND `JobID` = '" . $jid . "';";
		
		include '../config.php';	
		
		$r = mysql_query($check, $con);
		
		if (!$r) {
		    die('Invalid query: ' . mysql_error());
		}
	
		$applicationExist = false;
	
		if (mysql_num_rows($r) >=1) {
			$applicationExist = true;
		}
	
		return $applicationExist;
	}
	
	
	include '../close.php';
?>

</body>
</html>