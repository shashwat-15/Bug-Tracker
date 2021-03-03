<?php
include "connect.php";

$ticketID = $_GET['ticketid'];
$allActivitiesInfo = [];
$getActivity = "SELECT comment_body, attachment_name, prop_name, old_value, new_value, activity_time, first_name FROM activities a 
JOIN users u ON u.user_id = a.user_id 
LEFT JOIN comments c ON c.comment_id = a.comment_id 
LEFT JOIN attachments at ON at.attachment_id = a.attachment_id
WHERE a.ticket_id = ? ORDER BY activity_id DESC";
$stmt = $dbh->prepare($getActivity);
$params = [$ticketID];
$success = $stmt->execute($params);

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleActivityInfo = [];
        $propertyName = $row['prop_name'];
        array_push($singleActivityInfo, $propertyName);
        $oldValue = $row['old_value'];
        array_push($singleActivityInfo, $oldValue);
        $newValue = $row['new_value'];
        array_push($singleActivityInfo, $newValue);
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $activityCreatedTime = $row['activity_time'];
        $activityDate = substr($activityCreatedTime, 8, 2);
        $activityMonthNumber = substr($activityCreatedTime, 5, 2);
        $activityHour = substr($activityCreatedTime, 11, 2);
        $activityMinute = substr($activityCreatedTime, 14, 2);
        if ((int)$activityHour < 12) {
            $activityPeriod = "AM";
        } else {
            $activityHour = (int)$activityHour - 12;
            if ((int)$activityHour < 10) {
                $activityHour = "0" . $activityHour;
            }
            $activityPeriod = "PM";
        }
        for ($i = 0; $i < count($monthNumber); $i++) {
            if ($activityMonthNumber == $monthNumber[$i]) {
                $activityMonthName = $monthName[$i];
            }
        }
        $activityDisplayDateString = $activityDate . " " . $activityMonthName . " " . $activityHour . ":" . $activityMinute . " " . $activityPeriod;
        array_push($singleActivityInfo, $activityDisplayDateString);
        $firstName = $row['first_name'];
        array_push($singleActivityInfo, $firstName);
        $comment = $row['comment_body'];
        $attachment = $row['attachment_name'];
        if ($propertyName === "Comment") {
            array_push($singleActivityInfo, $comment);
        } else if ($propertyName === "Attachment") {
            array_push($singleActivityInfo, $attachment);
        }
        array_push($allActivitiesInfo, $singleActivityInfo);
    }
}
