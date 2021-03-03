<?php
include "connect.php";
$commentID = $_GET['commentid'];
$ticketID = $_GET['ticketid'];
//echo $commentID;
$deleteActivityRelatedToComment = "DELETE FROM activities WHERE comment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($deleteActivityRelatedToComment);
$params = [$commentID,  $ticketID];
$success = $stmt->execute($params);

$deleteComment = "DELETE FROM comments WHERE comment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($deleteComment);
$params = [$commentID, $ticketID];
$success = $stmt->execute($params);
