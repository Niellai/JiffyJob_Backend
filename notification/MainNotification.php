<?php

require_once '../SqliConn.php';
$sqliConn = new Sqli();

/* add or update user token, add if not found, GCM tokenStr can expire ` */

function updateUserToken($userID, $tokenStr) {
    require '../users/checkExistUserV2.php';

    if ($userExist) {
        $user = checkForExistingUserToken($userID);
        if (!is_null($user)) {
            //update token in NotifiUserToken
            updateToken($userID, $tokenStr);
        } else {
            //add new user token in NotifiUserToken
            addNewUserToken($userID, $tokenStr);
        }
    }
}

/* add new user to db, this function doesnt do any checks to not call this method direclty use updateUserToken instead */

function addNewUserToken($userID, $tokenStr) {
    global $sqliConn;
    $conn = $sqliConn->conn();

    $conn->select_db("NotifiUserToken");

    //echo "<br>".$userID."  ".$tokenStr;		

    $stmt = $conn->prepare("INSERT INTO NotifiUserToken (UserID, Token) VALUES (?,?)");
    $stmt->bind_param("ss", $userID, $tokenStr);
    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        $conn->close();
        echo "Error at insert user token query ";
        die('Invalid insert query: ' . mysql_error());
    } else {
        echo "<br>Token added successfully";
    }

    $stmt->close();
    $conn->close();
}

/* update user token in db, this function doesnt do any checks to not call this method direclty use updateUserToken instead */

function updateToken($userID, $tokenStr) {
    global $sqliConn;
    $conn = $sqliConn->conn();

    //UPDATE `NotifiUserToken` SET `Token`=[value-2] WHERE `UserID` =

    $conn->select_db("NotifiUserToken");
    $stmt = $conn->prepare("UPDATE NotifiUserToken SET Token = ? WHERE UserID = ?");
    $stmt->bind_param("ss", $tokenStr, $userID);
    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        $conn->close();
        echo "Error at update user token query ";
        die('Invalid update query: ' . mysql_error());
    } else {
        echo "<br>Token updated successfully";
    }

    $stmt->close();
    $conn->close();
}

/* User define topic, topic will expire */

function registerNewTopic($topicName) {
    global $sqliConn;

    $topic = checkForExistingTopic($topicName);
    if (is_null($topic)) {
        //add new topic
        $conn = $sqliConn->conn();
        $conn->select_db("NotifiTopic");
        $stmt = $conn->prepare("INSERT INTO NotifiTopic (TopicID, TopicName, TopicNeverExpire) VALUES (?,?,?)");

        $topicID = uniqid("topic_");
        $topicExist = 0;

        $stmt->bind_param("ssi", $topicID, $topicName, $topicExist);
        $result = $stmt->execute();

        if (!$result) {
            $stmt->close();
            $conn->close();
            echo "Error at inserting new topic ";
            die('Invalid insert query: ' . mysql_error());
        } else {
            $stmt->close();
            $conn->close();
            echo "New topic added";
        }
    } else {
        echo "Topic already existed <br>";
    }
    $conn->close();
}

/* Add user or users to respective topic */

function addUsersToTopic($userID, $topicName) {
    require '../users/checkExistUserV2.php';
    global $sqliConn;

    //$topic = array($topicID, $topicName, $createdDateTime, $topicNeverExpire);    
    $topic = checkForExistingTopic($topicName);
    if (!is_null($topic) && $userExist) {
        $topicID = $topic[0];
        $userTopic = checkIfUserInTopic($userID, $topicID); //prevent repeated record
        if (is_null($userTopic)) {
            echo "userTopic is null, " . $userID . " : " . $topicID . "<br>";
            $conn = $sqliConn->conn();
            $conn->select_db("NotifiUserTopic");
            $stmt = $conn->prepare("INSERT INTO NotifiUserTopic (UserID, TopicID) VALUES (?,?)");

            $stmt->bind_param("ss", $userID, $topicID);
            $result = $stmt->execute();
            if ($result) {
                echo "New user add to topic";
            } else {
                $stmt->close();
                $conn->close();
                echo "Error adding user to topic";
                die('Invalid insert query: ' . mysql_error());
            }
            $stmt->close();
            $conn->close();
        } else {
            echo "User had already added in topic";
        }
    } else {
        echo "No such topic found";
    }
}

/* Send msg to topic, will store message in NotifiTopicMsg db */

function insertTopicMsg($userID, $message) {       
    
}

/* Send msg to single user, will store message in NotifiSingleMsg db */

function insertSingleMsg($userID, $message) {
    
}

//CHECKS FUNCTIONS
//Check for existing user token
function checkForExistingUserToken($userID) {
    global $sqliConn;
    $conn = $sqliConn->conn();
    $conn->select_db("NotifiUserToken");

    //    SELECT * FROM `NotifiUserToken` WHERE `UserID` ="u1"
    //    OR (1=1)    
    $stmt = $conn->prepare("SELECT * FROM NotifiUserToken WHERE UserID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->store_result();

    $noOfRecords = $stmt->num_rows;
    echo "Existing user token, number of records found: " . $noOfRecords . "<br>";

    $stmt->bind_result($resUserID, $resToken);

    if ($noOfRecords > 0) {
        //Fetch all result
//        while ($stmt->fetch()) {
//            echo $resUserID . " : " . $resToken . "<br>";
//        }
//        
        //Fetch one result
        $stmt->fetch();
        echo $resUserID . " : " . $resToken . "<br>";
        $result = array($resUserID, $resToken);
    } else {
        echo "User token not found. <br>" . $stmt->error;
        $result = null;
    }

    $stmt->close();
    $conn->close();
    return $result;
}

//Check for existing topics
function checkForExistingTopic($topicName) {
    global $sqliConn;
    $conn = $sqliConn->conn();
    $conn->select_db("NotifiTopic");

    $stmt = $conn->prepare("SELECT * FROM NotifiTopic WHERE TopicName = ?");
    $stmt->bind_param("s", $topicName);
    $stmt->execute();
    $stmt->store_result();

    $noOfRecords = $stmt->num_rows;
    echo "Existing of topic number of records found: " . $noOfRecords . "<br>";

    $stmt->bind_result($topicID, $topicName, $createdDateTime, $topicNeverExpire);
    if ($noOfRecords > 0) {
        $stmt->fetch();
        echo $topicID . " : " . $topicName . "<br>";
        $result = array($topicID, $topicName, $createdDateTime, $topicNeverExpire);
    } else {
        echo "No topic found. <br>" . $stmt->error;
        $result = null;
    }
    $stmt->close();
    $conn->close();
    return $result;
}

//Check if user in topic
function checkIfUserInTopic($userID, $topicID) {
    global $sqliConn;
    $conn = $sqliConn->conn();
    $conn->select_db("NotifiUserTopic");

    $stmt = $conn->prepare("SELECT * FROM NotifiUserTopic WHERE UserID = ? AND TopicID = ?");
    $stmt->bind_param("ss", $userID, $topicID);
    $stmt->execute();
    $stmt->store_result();

    $noOfRecords = $stmt->num_rows;
    echo "User in topic, number of records found: " . $noOfRecords . "<br>";

    $stmt->bind_result($UserID, $TopicID);
    if ($noOfRecords > 0) {
        $stmt->fetch();
        echo $UserID . " : " . $TopicID . "<br>";
        $result = array($UserID, $TopicID);
    } else {
        echo "User not in topic. <br>" . $stmt->error;
        $result = null;
    }
    $stmt->close();
    $conn->close();
    return $result;
}

//TEST FUNCTION
addUsersToTopic("u2", "USA/generalTalk");
//checkForExistingUserToken("u1");
//updateUserToken("u1", "tokenStr");