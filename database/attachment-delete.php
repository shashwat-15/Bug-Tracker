<?php
include "connect.php";

$ticketID = $_GET['ticketid'];
$attachmentID = $_GET['attachmentid'];
$getAttachmentName = "SELECT attachment_name FROM attachments WHERE attachment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($getAttachmentName);
$params = [$attachmentID, $ticketID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    echo "yes";
    $attachmentName = $row['attachment_name'];
    unlink("../uploads/ticket-attachments/" . $attachmentName);
}

$deleteActivityRelatedToAttachment = "DELETE FROM activities WHERE attachment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($deleteActivityRelatedToAttachment);
$params = [$attachmentID,  $ticketID];
$success = $stmt->execute($params);

$deleteAttachment = "DELETE FROM attachments WHERE attachment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($deleteAttachment);
$params = [$attachmentID, $ticketID];
$success = $stmt->execute($params);
