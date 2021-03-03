<?php
include "connect.php";

$ticketCount = 0;
$getTicketsCount = "SELECT COUNT(*) FROM tickets";
$stmt = $dbh->prepare($getTicketsCount);
$success = $stmt->execute();

if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $ticketCount = $row[0];
}

echo $ticketCount;

//echo "hola";
