<?php
include "connect.php";

$ticketID = $_GET['ticketid'];
$allAttachmentsInfo = [];
$getAttachments = "SELECT attachment_id, attachment_type, attachment_name, attachment_size, attachment_time, first_name 
FROM attachments a JOIN users u ON u.user_id = a.user_id WHERE ticket_id = ? ORDER BY attachment_id DESC";
$stmt = $dbh->prepare($getAttachments);
$params = [$ticketID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleAttachmentInfo = [];
        $attachmentID = $row['attachment_id'];
        $attachmentType = $row['attachment_type'];
        array_push($singleAttachmentInfo, $attachmentType);
        $attachmentName = $row['attachment_name'];
        array_push($singleAttachmentInfo, $attachmentName);
        $attachmentSize = $row['attachment_size'];
        array_push($singleAttachmentInfo, $attachmentSize);
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $attachmentCreatedTime = $row['attachment_time'];
        $attachmentDate = substr($attachmentCreatedTime, 8, 2);
        $attachmentMonthNumber = substr($attachmentCreatedTime, 5, 2);
        $attachmentHour = substr($attachmentCreatedTime, 11, 2);
        $attachmentMinute = substr($attachmentCreatedTime, 14, 2);
        if ((int)$attachmentHour < 12) {
            $attachmentPeriod = "AM";
        } else {
            $attachmentHour = (int)$attachmentHour - 12;
            if ((int)$attachmentHour < 10) {
                $attachmentHour = "0" . $attachmentHour;
            }
            $attachmentPeriod = "PM";
        }
        for ($i = 0; $i < count($monthNumber); $i++) {
            if ($attachmentMonthNumber == $monthNumber[$i]) {
                $attachmentMonthName = $monthName[$i];
            }
        }
        $attachmentDisplayDateString = $attachmentDate . " " . $attachmentMonthName . " " . $attachmentHour . ":" . $attachmentMinute . " " . $attachmentPeriod;
        array_push($singleAttachmentInfo, $attachmentDisplayDateString);
        $creatorFirstName = $row['first_name'];
        array_push($singleAttachmentInfo, $creatorFirstName);
        array_push($singleAttachmentInfo, $attachmentID);
        array_push($allAttachmentsInfo, $singleAttachmentInfo);
    }
} 
//echo $_SERVER['REQUEST_METHOD'];
