<?php

include '../config.php';

if (isset($_SERVER['HTTP_JSON']) == TRUE) {
    $data = json_decode($_SERVER['HTTP_JSON']);
    var_dump($data);

    //add array into sql.
    $sql = "DELETE FROM `jobs` WHERE `JobID` = {data->JobID}";


    $result = mysql_query($sql, $con);

    if ($result) {
        echo "Record removed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysql_error($con);
    }
} else {
    echo "FAIL";
}

include '../close.php';
?>