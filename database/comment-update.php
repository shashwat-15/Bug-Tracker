<?php
include "connect.php";
$commentID = $_GET['commentid'];
$ticketID = $_GET['ticketid'];
$comment = $_GET['comment'];
$updateComment = "UPDATE comments SET comment_body = ? WHERE comment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($updateComment);
$params = [$comment, $commentID, $ticketID];
$success - $stmt->execute($params);
