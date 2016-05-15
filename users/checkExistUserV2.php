<?php

global $sqliConn;

$conn = $sqliConn->conn();
$conn->select_db("UserProfile");

$stmt = $conn->prepare("SELECT * FROM UserProfile WHERE UserID = ?");
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->store_result();

$noOfRecords = $stmt->num_rows;
$userExist = false;
if ($noOfRecords > 0) {
    $userExist = true;
    echo "userExists = true <br>";
} else {
    echo "userExists = false <br>";
}

$stmt->close();
$conn->close();