<?php

require 'GCM.php';

/**
 * NOTE: Topics cannot contain ","
 *       Topics cannot be more than 100 chars
 *       ID should not contain ","       
 */
class Notification {

    //Send using local form post
    function send($toStr, $msgStr) {
        if (empty($toStr) == false && empty($msgStr) == false) {
            $gcm = new GCM();

            if (strpos($toStr, ',') !== false) {
                //detected multiple IDs
                $result = $gcm->sendMultiple($toStr, $msgStr);
                echo "Send to multiple. Result: " . $result;
            } else if (strlen($toStr) > 100) {
                //send to single ID
                $result = $gcm->send($toStr, $msgStr);
                echo "Send to single. Result: " . $result;
            } else {
                //send by topic
                $result = $gcm->sendToTopic($toStr, $msgStr);
                echo "Send to topic. Result: " . $result;
            }
        }
        return $result;
    }

}
