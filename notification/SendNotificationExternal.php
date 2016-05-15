<?php

$toStr;
$msgStr;

class SendNotificationExternal {

    function send($jsonString) {
        require 'NotificationHelper.php';
        global $toStr, $msgStr;

        //extracting information from json        
        $sendExternal = new SendNotificationExternal();
        $sendExternal->extractInfo($jsonString);

        if (!empty($toStr) && !empty($msgStr)) {
            $notif = new Notification();
            $result = $notif->send($toStr, $msgStr);
            return $result;
        }
    }

    function extractInfo($jsonString) {
        global $toStr, $msgStr;
        $jsonArray = json_decode($jsonString, true);

        $toStr = $jsonArray ['toStr'];
        $msgStr = $jsonArray ['msgStr'];
    }

}
