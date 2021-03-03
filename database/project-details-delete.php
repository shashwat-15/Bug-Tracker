<?php
include "connect.php";
$type = $_GET["type"];
$deleteID = $_GET["id"];
$forDeleteCurrentProjectID = $_GET["projectid"];
$forDeleteGetAssignedTeamID = "SELECT team FROM projects WHERE project_id = ?";
$stmt = $dbh->prepare($forDeleteGetAssignedTeamID);
$params = [$forDeleteCurrentProjectID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $forDeleteAssignedTeamID = $row['team'];
}

if ($type === "u") {
    $removeUserFromProject = "DELETE FROM team_members WHERE team_id = ? AND member = ?";
    $stmt = $dbh->prepare($removeUserFromProject);
    $params = [$forDeleteAssignedTeamID, $deleteID];
    $success = $stmt->execute($params);
    if ($success && $stmt->rowCount() === 1) {
        echo "yes";
    } else {
        echo "fail";
    }
} else if ($type === "t") {
    $removeTicketFromProject = "DELETE FROM project_tickets WHERE project_id = ? AND ticket_id = ?";
    $stmt = $dbh->prepare($removeTicketFromProject);
    $params = [$forDeleteCurrentProjectID, $deleteID];
    $success = $stmt->execute($params);
}
