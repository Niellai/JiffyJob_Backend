<?php
//Set result search limit
$searchLimit = 300;
$JJServerKey = "AIzaSyB-EX-arCxM0tZFpsXmjBehsF8v-iNLT8A";

// Connection's Parameters
 $db_host= "localhost";
 $db_name="nimblyla_jiffyjobs";
 $username="nimblyla_jjadmin";
 $password="j!ffyj0bs";
 $con = mysql_connect($db_host, $username, $password);
 
    if(! $con )
    {
      die('Could not connect: ' . mysql_error(). '.');
    }
 
   $connection_string = mysql_select_db($db_name);
 
    if(! $con )
    {
      die('Could not connect: ' . mysql_error());
    }
	
?>