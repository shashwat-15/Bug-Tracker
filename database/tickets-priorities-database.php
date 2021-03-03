<?php
include "connect.php";

$ticketPrioritiesCount = [];
$getTicketPriorities = "SELECT COUNT(*), priority FROM tickets GROUP BY priority";
$stmt = $dbh->prepare($getTicketPriorities);
$success = $stmt->execute();
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        array_push($ticketPrioritiesCount, $row);
    }
}
echo json_encode($ticketPrioritiesCount);
