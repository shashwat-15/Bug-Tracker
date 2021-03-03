<?php
include "connect.php";
$commentID = $_GET['commentid'];
$ticketID = $_GET['ticketid'];
$commentDetails = [];
$updateComment = "SELECT profile_image, first_name, last_name, comment_body FROM comments c JOIN users u ON u.user_id = c.user_id WHERE comment_id = ? AND ticket_id = ?";
$stmt = $dbh->prepare($updateComment);
$params = [$commentID, $ticketID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $profileImage = $row['profile_image'];
    $firstName = $row['first_name'];
    if (strlen($profileImage) !== 0) {
        array_push($commentDetails, $profileImage);
    } else {
        $lastName = $row['last_name'];
        $firstInitial = substr($firstName, 0, 1);
        $lastInitial = substr($lastName, 0, 1);
        $initials = strtoupper($firstInitial) . strtoupper($lastInitial);
        array_push($commentDetails, $initials);
    }
    $commentBody = $row['comment_body'];
    array_push($commentDetails, $commentBody);
}
echo json_encode($commentDetails);
