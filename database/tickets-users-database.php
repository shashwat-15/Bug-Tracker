<?php
include "connect.php";

$top3TicketUsers = [];
$top3Users = [];
$ticketUsersCount = [];
$getTicketTop3Users = "SELECT COUNT(*), first_name, last_name FROM user_tickets ut
JOIN users u ON u.user_id = ut.assigned_to GROUP BY first_name, last_name ORDER BY COUNT(*) DESC LIMIT 3";
$stmt = $dbh->prepare($getTicketTop3Users);
$success = $stmt->execute();
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $user = $firstName . " " . $lastName;
        array_push($top3Users, $firstName);
        array_push($ticketUsersCount, $row[0]);
    }
    array_push($top3TicketUsers, $top3Users);
    array_push($top3TicketUsers, $ticketUsersCount);
}
echo json_encode($top3TicketUsers);
