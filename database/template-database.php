<?php
include "connect.php";

//projects
$selectTeams = [];
$getAllTeams = "SELECT team_name FROM teams";
$stmt = $dbh->prepare($getAllTeams);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        array_push($selectTeams, $row['team_name']);
    }
}

$allProjectsTeamMembers = [];
$getProjectsCount = "SELECT COUNT(*) FROM projects";
$stmt = $dbh->prepare($getProjectsCount);
$success = $stmt->execute();
if ($success && $row = $stmt->fetch()) {
    $totalProjectsCount = (int)$row[0];
    //$projectNumber = 100 + $totalProjectsCount;
}


$allProjectsInfo = [];
$getAllProjects = "SELECT * FROM projects ORDER BY project_id DESC";
$stmt = $dbh->prepare($getAllProjects);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleProjectInfo = [];
        $singleProjectTeamMembers = [];
        $projectNumber = 100;
        $currentProjectID = $row['project_id'];
        $projectNumber = $projectNumber + (int)$currentProjectID;
        $currentProjectNumber = $projectNumber;
        array_push($singleProjectInfo, $currentProjectNumber);
        $projectNumber--;
        $currentProjectTitle = $row['project_title'];
        array_push($singleProjectInfo, $currentProjectTitle);
        $currentTeamID = $row['team'];
        $getCurrentTeamName = "SELECT team_name FROM teams WHERE team_id = ?";
        $stmt1 = $dbh->prepare($getCurrentTeamName);
        $params1 = [$currentTeamID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $currentTeamName = $row1['team_name'];
            array_push($singleProjectInfo, $currentTeamName);
        }
        $currentOwnerID = $row['owner'];
        $getCurrentOwnerName = "SELECT first_name, last_name FROM users WHERE user_id = ?";
        $stmt1 = $dbh->prepare($getCurrentOwnerName);
        $params1 = [$currentOwnerID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $currentOwnerName = $firstName . " " . $lastName;
            array_push($singleProjectInfo, $currentOwnerName);
        }
        $getCurrentTotalTeamMembers = "SELECT COUNT(*) FROM team_members WHERE team_id = ?";
        $stmt1 = $dbh->prepare($getCurrentTotalTeamMembers);
        $params1 = [$currentTeamID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $currentTotalTeamMembers = $row1[0];
            array_push($singleProjectInfo, $currentTotalTeamMembers);
        }
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $currentProjectCreatedDateTime = $row['created_on'];
        $currentDate = substr($currentProjectCreatedDateTime, 8, 2);
        $currentMonthNumber = substr($currentProjectCreatedDateTime, 5, 2);
        $currentHour = substr($currentProjectCreatedDateTime, 11, 2);
        $currentMinute = substr($currentProjectCreatedDateTime, 14, 2);
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
        array_push($singleProjectInfo, $displayDateString);
        $getCurrentTotalTickets = "SELECT COUNT(*) FROM project_tickets WHERE project_id = ?";
        $stmt1 = $dbh->prepare($getCurrentTotalTickets);
        $params1 = [$currentProjectID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $currentTotalTickets = $row1[0];
            array_push($singleProjectInfo, $currentTotalTickets);
        }
        array_push($singleProjectInfo, $currentProjectID);
        array_push($allProjectsInfo, $singleProjectInfo);

        $getTeamMembers = "SELECT first_name, last_name FROM team_members tm JOIN users u on u.user_id = tm.member where team_id = ?";
        $stmt2 = $dbh->prepare($getTeamMembers);
        $params2 = [$currentTeamID];
        $success2 = $stmt2->execute($params2);

        if ($success2 && $stmt2->rowCount()) {
            while ($row2 = $stmt2->fetch()) {
                $currentTeamMember = $row2['first_name'] . " " . $row2['last_name'];
                array_push($singleProjectTeamMembers, $currentTeamMember);
            }
        }
        array_push($allProjectsTeamMembers, $singleProjectTeamMembers);
    }
}


//tickets
$allTicketProjectTeamMembers = [];

$getTicketsCount = "SELECT COUNT(*) FROM tickets";
$stmt = $dbh->prepare($getTicketsCount);
$success = $stmt->execute();
if ($success && $row = $stmt->fetch()) {
    $totalTicketsCount = (int)$row[0];
    //$ticketNumber = 100 + $totalTicketsCount;
}



$allTicketsInfo = [];
$getAllTickets = "SELECT * FROM tickets ORDER BY ticket_id DESC";
$stmt = $dbh->prepare($getAllTickets);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleTicketInfo = [];
        $singleTicketProjectTeamMembers = [];
        $currentTicketProjectsString = "";
        $ticketNumber = 100;
        $currentTicketID = $row['ticket_id'];
        $ticketNumber = $ticketNumber + (int)$currentTicketID;
        $currentTicketNumber = $ticketNumber;
        array_push($singleTicketInfo, $currentTicketNumber);
        $ticketNumber--;
        $currentTicketName = $row['ticket_name'];
        array_push($singleTicketInfo, $currentTicketName);
        $currentTicketStatus = $row['status'];
        array_push($singleTicketInfo, $currentTicketStatus);
        $getCurrentAssignedTo = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.assigned_to WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCurrentAssignedTo);
        $params1 = [$currentTicketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $currentAssignedTo = $firstName . " " . $lastName;
            array_push($singleTicketInfo, $currentAssignedTo);
        }
        $getCurrentCreatedBy = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.created_by WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCurrentCreatedBy);
        $params1 = [$currentTicketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $currentCreatedBy = $firstName . " " . $lastName;
            array_push($singleTicketInfo, $currentCreatedBy);
        }
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $currentTicketCreatedDateTime = $row['created_on'];
        $currentDate = substr($currentTicketCreatedDateTime, 8, 2);
        $currentMonthNumber = substr($currentTicketCreatedDateTime, 5, 2);
        $currentHour = substr($currentTicketCreatedDateTime, 11, 2);
        $currentMinute = substr($currentTicketCreatedDateTime, 14, 2);
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
        array_push($singleTicketInfo, $displayDateString);
        $getCurrentTicketProject = "SELECT project_title FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCurrentTicketProject);
        $params1 = [$currentTicketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount()) {
            while ($row1 = $stmt1->fetch()) {
                $currentTicketProjectTitle = $row1['project_title'];
                $currentTicketProjectsString .= trim($currentTicketProjectTitle) . ", ";
            }
            array_push($singleTicketInfo, rtrim($currentTicketProjectsString, ", "));
        }
        array_push($singleTicketInfo, $currentTicketID);
        array_push($allTicketsInfo, $singleTicketInfo);

        $getCurrentTicketProjectTeamMembers = "SELECT first_name, last_name FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id
        JOIN team_members tm ON tm.team_id = p.team JOIN users u ON u.user_id = tm.member WHERE ticket_id = ?";
        $stmt2 = $dbh->prepare($getCurrentTicketProjectTeamMembers);
        $params2 = [$currentTicketID];
        $success2 = $stmt2->execute($params2);
        if ($success2 && $stmt2->rowCount()) {
            while ($row2 = $stmt2->fetch()) {
                $currentTicketProjectTeamMember = $row2['first_name'] . " " . $row2['last_name'];
                array_push($singleTicketProjectTeamMembers, $currentTicketProjectTeamMember);
            }
        }

        array_push($allTicketProjectTeamMembers, $singleTicketProjectTeamMembers);
    }
}

//users
$getUsersCount = "SELECT COUNT(*) FROM users";
$stmt = $dbh->prepare($getUsersCount);
$success = $stmt->execute();
if ($success && $row = $stmt->fetch()) {
    $totalUsersCount = (int)$row[0];
    //$userNumber = 100 + $totalUsersCount;
}

$allUsersInfo = [];
$getAllUsers = "SELECT * FROM users ORDER BY user_id DESC";
$stmt = $dbh->prepare($getAllUsers);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleUserInfo = [];
        $userNumber = 100;
        $currentUserID = $row['user_id'];
        $userNumber = $userNumber + (int)$currentUserID;
        $currentUserNumber = $userNumber;
        array_push($singleUserInfo, $currentUserNumber);
        $userNumber--;
        $currentUserFirstName = $row['first_name'];
        $currentUserLastName = $row['last_name'];
        $currentUserFullName = $currentUserFirstName . " " . $currentUserLastName;
        array_push($singleUserInfo, $currentUserFullName);
        $currentUserRole = $row['role'];
        array_push($singleUserInfo, $currentUserRole);
        $currentUserDepartment = $row['department'];
        array_push($singleUserInfo, $currentUserDepartment);
        $currentUserEmail = $row['email'];
        array_push($singleUserInfo, $currentUserEmail);
        array_push($singleUserInfo, $currentUserID);
        array_push($allUsersInfo, $singleUserInfo);
    }
}
