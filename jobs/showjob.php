<!DOCTYPE html>
<html>
<body>

	<form action="http://www.nimblylabs.com/jjws/jobs/showjob.php" method="post">
Search input <input type="text" name="jsonstring"><br>
<input type="submit">
</form>
<br><h2>Result:</h2>

<?php
 include '../config.php'; 
$result;
$jsonString;
$searchType;
$searchString;
	/*Actual code for deployment*/
	$jsonString = file_get_contents('php://input');	
	
	/*Debug code - $_POST can only be use for debugging */
 	//$jsonString = $_POST['jsonstring'];
	
	extractInfo($jsonString);
	switch ($searchType) {
		case "LocationSearch":
		locationSearch($con);
		break;
		case "Time":
		break;
		case "Payout":
		break;
		case "Category":
		break;
	}
	
	function extractInfo($jsonString){
		global $searchType, $searchString;
		$jsonArray = json_decode($jsonString, true);
		$searchType = $jsonArray ['searchType'];
		$searchString = $jsonArray ['searchString'];
		echo "searchType: ".$searchType."<br>";
		echo "searchString: ".$searchString."<br>";
	}
	
	function locationSearch($con){
		global  $searchLimit; //from config.php
		global $result, $jsonString, $searchString;
		$searchString = trim($searchString," ");		
		$searchArray = explode(",",$searchString);
		
		if(!empty($searchArray[0])){
			$searchCity = "%".$searchArray[0]."%";
		}
		else{
			$searchCity = "NULL";
		}
		if(!empty($searchArray[1])){
			$searchState = "%".$searchArray[1]."%";
		}
		else{
			$searchState = "NULL";
		}
		if(!empty($searchArray[2])){
			$searchCountry = "%".$searchArray[2]."%";
		}
		else{
			$searchCountry = "NULL";
		}
		$datePosted = gmdate('Y-m-d H:i:s');

		
		$searchQuery = "SELECT t.* FROM (SELECT DISTINCT ID as ID1, Jobs.JobID as ID2, Jobs.CreatorUserID as ID3,  NULL as CorporateProfile, NULL as CompanyName, COALESCE(UserProfile.FirstName,'-'), COALESCE(UserProfile.LastName,'-'), UserType, Scope.ScopesJson, Jobs.DatePosted, JobTitle, UPhotoDetails, GPhotoDetails, MinAge, MaxAge, TotalPax, ReqMinEduLevel, Jobs.Country as Country, State, City, Lat, Lng, StartDateTime, EndDateTime, TotalWorkDays, IsSalaryDaily, SalaryCurrencyCode, Payout, CurrentlyRecruited, JobStatus, PostBoosted, LastModify FROM `Jobs`, `Scope`, `UserProfile` WHERE (Jobs.UserType = '0' AND Jobs.CreatorUserID = UserProfile.UserID AND Jobs.JobID = Scope.JobID) AND (`City` LIKE '$searchCity' OR `State` LIKE '$searchState' OR Jobs.Country LIKE '$searchCountry') UNION ALL SELECT DISTINCT ID as ID1, Jobs.JobID as ID2, Jobs.CreatorUserID as ID3, COALESCE(CorporateProfile.CUserID,'-'), COALESCE(CorporateProfile.CompanyName,'-'), NULL as FirstName, NULL as LastName, UserType, Scope.ScopesJson, Jobs.DatePosted, JobTitle, UPhotoDetails, GPhotoDetails, MinAge, MaxAge, TotalPax, ReqMinEduLevel, Jobs.Country as Country, State, City, Lat, Lng, StartDateTime, EndDateTime, TotalWorkDays, IsSalaryDaily, SalaryCurrencyCode, Payout, CurrentlyRecruited, JobStatus, PostBoosted, LastModify FROM `Jobs`, `Scope`, `CorporateProfile` WHERE (Jobs.UserType = '1' AND Jobs.CreatorUserID = CorporateProfile.CUserID AND Jobs.JobID = Scope.JobID) AND (`City` LIKE '$searchCity' OR `State` LIKE '$searchState' OR Jobs.Country LIKE '$searchCountry')) t ORDER BY `DatePosted` DESC LIMIT $searchLimit";
		
		$resultSearch = mysql_query($searchQuery, $con);

		if (!$resultSearch) {
	            die('Invalid query: ' . mysql_error());
	        }
		
		echo "Records retrieved: ".mysql_num_rows($resultSearch)."<br>";
		//convert to json before sending out
		
		convertJson($resultSearch);
 		//printTable($resultSearch);
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