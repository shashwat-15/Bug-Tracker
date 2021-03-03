<?php
include "connect.php";
if (isset($_GET["team"])) {

    $teamMembers = [];
    $team = filter_input(INPUT_GET, "team", FILTER_SANITIZE_STRING);
    $getTeamID = "SELECT team_id from teams where team_name = ?";
    $stmt = $dbh->prepare($getTeamID);
    $params = [$team];
    $success = $stmt->execute($params);

    if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
        $teamID = $row['team_id'];
        $getTeamMembers = "SELECT first_name, last_name FROM team_members tm JOIN users u on u.user_id = tm.member where team_id = ?";
        $stmt2 = $dbh->prepare($getTeamMembers);
        $params2 = [$teamID];
        $success2 = $stmt2->execute($params2);

        if ($success2 && $stmt2->rowCount()) {
            while ($row2 = $stmt2->fetch()) {
                $teamMember = $row2['first_name'] . " " . $row2['last_name'];
                array_push($teamMembers, $teamMember);
            }
        }
    }
    echo json_encode($teamMembers);
}
