<?php
include "connect.php";

$status = ["Open", "On Hold", "Closed"];
$allStatusCount = [];
$statusCount = [];
$sevenDaysBefore = date('Y-m-d', strtotime('-7 days'));
$sevenDaysBefore = str_replace('-', '', $sevenDaysBefore);

if ($_GET['userid'] !== "") {


    $userID = $_GET['userid'];
    for ($i = 0; $i < count($status); $i++) {
        $statusCount = [];
        $getTicketStatusCount = "SELECT COUNT(*), created_on FROM user_tickets ut JOIN tickets t ON t.ticket_id = ut.ticket_id 
    AND t.created_on > ? AND status = ? WHERE assigned_to = ? GROUP BY created_on";
        $stmt = $dbh->prepare($getTicketStatusCount);
        $params = [$sevenDaysBefore, $status[$i], $userID];
        $success = $stmt->execute($params);
        if ($success && $stmt->rowCount()) {
            while ($row = $stmt->fetch()) {
                array_push($statusCount, $row);
            }
        }
        array_push($allStatusCount, $statusCount);
    }

    echo json_encode($allStatusCount);
} else {
    for ($i = 0; $i < count($status); $i++) {
        $statusCount = [];
        $getTicketStatusCount = "SELECT COUNT(*), created_on FROM tickets t WHERE t.created_on > ? AND status = ? GROUP BY created_on";
        $stmt = $dbh->prepare($getTicketStatusCount);
        $params = [$sevenDaysBefore, $status[$i]];
        $success = $stmt->execute($params);
        if ($success && $stmt->rowCount()) {
            while ($row = $stmt->fetch()) {
                array_push($statusCount, $row);
            }
        }
        array_push($allStatusCount, $statusCount);
    }

    echo json_encode($allStatusCount);
}
