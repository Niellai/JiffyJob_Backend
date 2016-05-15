<!DOCTYPE html>
<html>
    <body>
        <form action="http://www.nimblylabs.com/jjws/notification/SendNotificationInternal.php" method="post">
            "To user" can be of single user token ID or topic<br>
            To: <textarea type="text" name="toStr" rows="1" cols="26"></textarea><br>
            Message:<br> <textarea type="text" name="msgStr" rows="10" cols="30"></textarea><br>
            <input type="submit">
        </form>

        <?php
        require 'NotificationHelper.php';
        $toStr = filter_input(INPUT_POST, 'toStr');
        $msgStr = filter_input(INPUT_POST, 'msgStr');

        if (!empty($toStr) && !empty($msgStr)) {
            $notif = new Notification();
            $result = $notif->send($toStr, $msgStr);
            echo $result;
        }
        ?>
    </body>
</html>


