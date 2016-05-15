<!DOCTYPE html>
<html>
<body>

<form action="http://www.nimblylabs.com/jjws/jobs/postedjobs_fetchbyid.php" method="post">
Search input <input type="text" name="jsonstring"><br>
<input type="submit">
</form>
<br><h2>Result:</h2>

<?php
include '../config.php';

    $result;
    $jsonString;
    $userID;
    $postedJobStatus;

	/*Actual code for deployment*/
	$jsonString = file_get_contents('php://input');
	
	/*Debug code - $_POST can only be use for debugging */
 	//$jsonString = $_POST['jsonstring'];
	
	extractInfo($jsonString);

	switch ($postedJobStatus) {
        /* Pending*/
		case 'Active':
		fetchPostedJobs($userID, 0, 'NULL', $con);
		break;
        
        /* Cancelled 0/1 */
		case 'History':
        fetchPostedJobs($userID, 1, 2, $con);
		break;
        
        default:
        break;
	}

/* Functions Starts Here */
    function fetchPostedJobs($userID, $jobStatus1, $jobStatus2, $con){

		$getJobs = "SELECT JobID, UserType, CreatorUserID, DatePosted, JobTitle, IsGenericPhoto, UPhotoDetails, GPhotoDetails, MinAge, MaxAge, TotalPax, ReqMinEduLevel, Country, State, City, Lat, Lng, StartDateTime, EndDateTime, TotalWorkDays, IsSalaryDaily, SalaryCurrencyCode, Payout, CurrentlyRecruited, JobStatus, PostBoosted, LastModify FROM `Jobs` WHERE `CreatorUserID` = '" . $userID . "' AND (JobStatus = " . $jobStatus1 . " OR JobStatus = " . $jobStatus2 . ")";
        
		$searchResults = mysql_query($getJobs, $con);
		
        if (!$searchResults) {
	            die('Invalid query: ' . mysql_error());
        }
        
        echo "Records retrieved: ".mysql_num_rows($searchResults)."<br>";
        
        convertJson($searchResults);
    }

	function extractInfo($jsonString){
		global $userID, $postedJobStatus;
		$jsonArray = json_decode($jsonString, true);
		$userID = $jsonArray ['UserID'];
		$postedJobStatus = $jsonArray ['Status'];
		echo "UserID: " . $userID . "<br>";
		echo "Job Status: " . $postedJobStatus . "<br>";
	}

	function printTable($result){
		echo "<b>Records retrieved: ".mysql_num_rows($result)."</b><br>";		
		while ($tableRow = mysql_fetch_assoc($result)) { // Loops 3 times if there are 3 returned rows... etc
		//echo json_encode($tableRow);
		
		$columnIndex = 0;
   			foreach ($tableRow as $key => $value) { // Loops 4 times because there are 4 column
	   			$fieldName = mysql_field_name($result , $columnIndex);
	   			echo $fieldName.": ";
			        echo $value."<br>";       
			        $columnIndex ++;
		    	}
		    echo "<br>";	
		}		
	}

	function convertJson($resultSearch){
		global $result;
		$results = array();
		while($row = mysql_fetch_assoc($resultSearch))
		{
			$results[] = $row;
		}
		$result = json_encode($results);
		echo "Result: ".$result;
	}

include '../close.php';
?>
</body>
</html>