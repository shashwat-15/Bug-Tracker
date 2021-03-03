<?php
include "connect.php";
$ticketTypesCount = [];
$getTicketTypes = "SELECT COUNT(*), type FROM tickets GROUP BY type";
$stmt = $dbh->prepare($getTicketTypes);
$success = $stmt->execute();
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        array_push($ticketTypesCount, $row);
    }
}
echo json_encode($ticketTypesCount);
