<!DOCTYPE html>
<html>
    <body>
        <form action="http://www.nimblylabs.com/jjws/notification/SendNotification.php" method="post">
            "To user" can be of single user token ID or topic<br>
            To: <textarea type="text" name="toStr" rows="1" cols="26"></textarea><br>
            Message:<br> <textarea type="text" name="msgStr" rows="10" cols="30"></textarea><br>
            <input type="submit">
        </form>

        <?php
        require 'GCM.php';

        /* Actual code for deployment */
        $jsonString = file_get_contents('php://input');

        /* Debug code - $_POST can only be use for debugging */
        //$toStr = $_POST['toStr'];
        //$msgStr = $_POST['msgStr'];
        $toStr = filter_input(INPUT_POST, 'toStr');
        $msgStr = filter_input(INPUT_POST, 'msgStr');

        echo "Received jsonStr:" . $jsonString . "<br>";

        if (empty($jsonString)) {
            sendByLocalPost();
        } else {
            sendByDevicePost();
        }

        //Send using local form post
        function sendByLocalPost() {
            global $toStr, $msgStr;
            if (empty($toStr) == false && empty($msgStr) == false) {
                $gcm = new GCM();

                if (strpos($toStr, ',') !== false) {
                    //detected multiple IDs
                    $result = $gcm->sendMultiple($toStr, $msgStr);
                    echo "Result: " . $result;
                } else if (strlen($toStr) > 100) {
                    //send to single ID
                    $result = $gcm->send($toStr, $msgStr);
                    echo "Result: " . $result;
                } else {
                    //send by topic
                    $result = $gcm->sendToTopic($toStr, $msgStr);
                    echo "Result: " . $result;
                }
            }
        }

        //Send by device post
        function sendByDevicePost() {
            global $jsonString;
            extractInfo($jsonString);
        }

        function extractInfo($jsonString) {
            global $toStr, $msgStr;
            $jsonArray = json_decode($jsonString, true);
            $toStr = $jsonArray ['toString'];
            $msgStr = $jsonArray ['msgString'];
            echo "toStr: " . $toStr . "<br>";
            echo "msgStr: " . $msgStr . "<br>";
        }
        ?>
    </body>
</html>