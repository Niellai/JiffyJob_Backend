<!DOCTYPE html>
<html>
<body>

<form action="http://www.nimblylabs.com/jjws/jobs/appliedjobs_fetchbyid.php" method="post">
Search input <input type="text" name="jsonstring"><br>
<input type="submit">
</form>
<br><h2>Result:</h2>

<?php
include '../config.php';

    $result;
    $jsonString;
    $userID;
    $jobStatus;

	/*Actual code for deployment*/
	$jsonString = file_get_contents('php://input');
	
	/*Debug code - $_POST can only be use for debugging */
 	//$jsonString = $_POST['jsonstring'];
	
	extractInfo($jsonString);

	switch ($jobStatus) {
        /* Pending*/
	case 'Pending':
	fetchRelatedJobs($userID, 0, 'NULL', $con);
	break;
        
        /* Cancelled 0/1 */
	case 'Rejected':
        fetchRelatedJobs($userID, 1, 2, $con);
	break;
        
        /* Accepted */
        case 'Confirmed':
        fetchRelatedJobs($userID, 3, 4, $con);
        break;
        
        /* Completed */
        case 'Completed':
        fetchRelatedJobs($userID, 5, 'NULL', $con);
        break;
        
        default:
        break;
	}


/* Functions Starts Here */
    function fetchRelatedJobs($userID, $jobStatus1, $jobStatus2, $con){

		$getJobs = "SELECT a.JobID, a.Status, b.JobID, b.UserType, b.CreatorUserID, b.DatePosted, b.JobTitle, b.IsGenericPhoto, b.UPhotoDetails, b.GPhotoDetails, b.MinAge, b.MaxAge, b.TotalPax, b.ReqMinEduLevel, b.Country, b.State, b.City, b.Lat, b.Lng, b.StartDateTime, b.EndDateTime, b.TotalWorkDays, b.IsSalaryDaily, b.SalaryCurrencyCode, b.Payout, b.CurrentlyRecruited, b.JobStatus, b.PostBoosted, b.LastModify FROM `UserJobApplication` a, `Jobs` b WHERE a.JobID = b.JobID AND a.UserID = " . $userID . " AND (a.Status = " . $jobStatus1 . " OR a.Status = " . $jobStatus2 . ")";
        
		$searchResults = mysql_query($getJobs, $con);
		
        if (!$searchResults) {
	            die('Invalid query: ' . mysql_error());
        }
        
        echo "Records retrieved: ".mysql_num_rows($searchResults)."<br>";
        
        convertJson($searchResults);
    }

	function extractInfo($jsonString){
		global $userID, $jobStatus;
		$jsonArray = json_decode($jsonString, true);
		$userID = $jsonArray ['UserID'];
		$jobStatus = $jsonArray ['Status'];
		echo "UserID: " . $userID . "<br>";
		echo "Job Status: " . $jobStatus . "<br>";
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