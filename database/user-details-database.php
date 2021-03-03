<?php
include "connect.php";

$getTotalUsers = "SELECT COUNT(*) FROM users";
$stmt = $dbh->prepare($getTotalUsers);
$success = $stmt->execute();
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $totalUsers = $row[0];
}

$userID = $id;

//assigned Projects
$allAssignedProjects = [];
$getAssignedProjects = "SELECT * FROM team_members tm JOIN projects p ON p.team = tm.team_id WHERE member = ?";
$stmt = $dbh->prepare($getAssignedProjects);
$params = [$userID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleAssignedProject = [];
        $projectID = $row['project_id'];
        $teamID = $row['team'];
        $getTeamName = "SELECT team_name FROM teams WHERE team_id = ?";
        $stmt1 = $dbh->prepare($getTeamName);
        $params1 = [$teamID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $teamName = $row1['team_name'];
            $teamNameArray = explode(" ", $teamName);
            $firstInitial = substr($teamNameArray[0], 0, 1);
            $sencondInitial = substr($teamNameArray[1], 0, 1);
            $initals = strtoupper($firstInitial) . strtoupper($sencondInitial);
            array_push($singleAssignedProject, $initals);
        }
        $projectTitle = $row['project_title'];
        array_push($singleAssignedProject, $projectTitle);
        $getcurrentProjectTeamMembersCount = "SELECT COUNT(*) FROM team_members WHERE team_id = ?";
        $stmt1 = $dbh->prepare($getcurrentProjectTeamMembersCount);
        $params1 = [$teamID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $totalMembers = $row1[0];
            array_push($singleAssignedProject, $totalMembers);
        }
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $projectCreatedDateTime = $row['created_on'];
        $currentDate = substr($projectCreatedDateTime, 8, 2);
        $currentMonthNumber = substr($projectCreatedDateTime, 5, 2);
        $currentHour = substr($projectCreatedDateTime, 11, 2);
        $currentMinute = substr($projectCreatedDateTime, 14, 2);
        if ((int)$currentHour < 12) {
            $period = "AM";
        } else {
            $currentHour = (int)$currentHour - 12;
            if ((int)$currentHour < 10) {
                $currentHour = "0" . $currentHour;
            }
            $period = "PM";
        }
        for ($i = 0; $i < count($monthNumber); $i++) {
            if ($currentMonthNumber == $monthNumber[$i]) {
                $currentMonthName = $monthName[$i];
            }
        }
        $displayDateString = $currentDate . " " . $currentMonthName . " " . $currentHour . ":" . $currentMinute . " " . $period;
        array_push($singleAssignedProject, $displayDateString);
        $getProjectTicketsCount = "SELECT COUNT(*) FROM project_tickets WHERE project_id = ?";
        $stmt1 = $dbh->prepare($getProjectTicketsCount);
        $params1 = [$projectID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $totalTickets = $row1[0];
            array_push($singleAssignedProject, $totalTickets);
        }
        array_push($singleAssignedProject, $projectID);
        array_push($allAssignedProjects, $singleAssignedProject);
    }
}

//assigned Tickets
$allAssignedTickets = [];
$getAssignedTickets = "SELECT * FROM user_tickets ut JOIN tickets t ON t.ticket_id = ut.ticket_id WHERE assigned_to = ?";
$stmt = $dbh->prepare($getAssignedTickets);
$params = [$userID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleAssignedTicket = [];
        $ticketProjectsString = "";
        $ticketID = $row['ticket_id'];
        $currentAssignedTicketPriority = $row['priority'];
        if ($currentAssignedTicketPriority === "High") {
            $currentColor = "text-danger";
        } else if ($currentAssignedTicketPriority === "Medium") {
            $currentColor = "text-warning";
        } else if ($currentAssignedTicketPriority === "Low") {
            $currentColor = "text-info";
        } else if ($currentAssignedTicketPriority === "None") {
            $currentColor = "text-secondary";
        }
        array_push($singleAssignedTicket, $currentColor);
        $ticketName = $row['ticket_name'];
        array_push($singleAssignedTicket, $ticketName);
        $getCreatedBy = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.created_by WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCreatedBy);
        $params1 = [$ticketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $createdBy = $firstName . " " . $lastName;
            array_push($singleAssignedTicket, $createdBy);
        }
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $ticketCreatedDateTime = $row['created_on'];
        $currentDate = substr($ticketCreatedDateTime, 8, 2);
        $currentMonthNumber = substr($ticketCreatedDateTime, 5, 2);
        $currentHour = substr($ticketCreatedDateTime, 11, 2);
        $currentMinute = substr($ticketCreatedDateTime, 14, 2);
        if ((int)$currentHour < 12) {
            $period = "AM";
        } else {
            $currentHour = (int)$currentHour - 12;
            if ((int)$currentHour < 10) {
                $currentHour = "0" . $currentHour;
            }
            $period = "PM";
        }
        for ($i = 0; $i < count($monthNumber); $i++) {
            if ($currentMonthNumber == $monthNumber[$i]) {
                $currentMonthName = $monthName[$i];
            }
        }
        $displayDateString = $currentDate . " " . $currentMonthName . " " . $currentHour . ":" . $currentMinute . " " . $period;
        array_push($singleAssignedTicket, $displayDateString);
        $getProject = "SELECT project_title FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getProject);
        $params1 = [$ticketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount()) {
            while ($row1 = $stmt1->fetch()) {
                $ticketProjectTitle = $row1['project_title'];
                $ticketProjectsString .= trim($ticketProjectTitle) . ", ";
            }
            array_push($singleAssignedTicket, rtrim($ticketProjectsString, ", "));
        }
        array_push($singleAssignedTicket, $ticketID);
        array_push($allAssignedTickets, $singleAssignedTicket);
    }
}
