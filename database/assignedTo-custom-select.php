<?php
include "connect.php";
if (isset($_GET["projects"])) {

    $projectTeamMembers = [];
    $projects = filter_input(INPUT_GET, "projects", FILTER_SANITIZE_STRING);
    $projectsArray = explode(",", $projects);
    //echo $projectsArray[0];
    for ($i = 0; $i < count($projectsArray); $i++) {
        $getProjectTeamID = "SELECT team FROM projects WHERE project_title = ?";
        $stmt = $dbh->prepare($getProjectTeamID);
        $params = [trim($projectsArray[$i])];
        $success = $stmt->execute($params);

        if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
            $projectTeamID = $row['team'];
            $getProjectTeamMembers = "SELECT first_name, last_name FROM team_members tm JOIN users u ON u.user_id = tm.member WHERE team_id = ?";
            $stmt2 = $dbh->prepare($getProjectTeamMembers);
            $params2 = [$projectTeamID];
            $success2 = $stmt2->execute($params2);

            if ($success2 && $stmt2->rowCount()) {
                while ($row2 = $stmt2->fetch()) {
                    $projectTeamMember = $row2['first_name'] . " " . $row2['last_name'];
                    array_push($projectTeamMembers, $projectTeamMember);
                }
            }
        }
    }
    echo json_encode($projectTeamMembers);
}
