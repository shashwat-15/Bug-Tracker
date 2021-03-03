<?php
include "connect.php";
date_default_timezone_set('America/Toronto');

$getTotalProjects = "SELECT COUNT(*) FROM projects";
$stmt = $dbh->prepare($getTotalProjects);
$success = $stmt->execute();
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $totalProjects = $row[0];
}
$projectID = $id;
$allAssignedToMembers = [];
//project users
$allAssignedUsers = [];
$getAssignedTeamID = "SELECT team FROM projects WHERE project_id = ?";
$stmt = $dbh->prepare($getAssignedTeamID);
$params = [$projectID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $assignedTeamID = $row['team'];
    $getAssignedUsers = "SELECT * FROM team_members tm JOIN users u ON u.user_id = tm.member WHERE team_id = ?";
    $stmt1 = $dbh->prepare($getAssignedUsers);
    $params1 = [$assignedTeamID];
    $success1 = $stmt1->execute($params1);
    if ($success1 && $stmt1->rowCount()) {
        while ($row1 = $stmt1->fetch()) {
            $singleAssignedUser = [];
            $singleAssignedToMember = [];
            $currentProfileImage = $row1['profile_image'];
            $currentFirstName = $row1['first_name'];
            $currentLastName = $row1['last_name'];
            if (strlen($currentProfileImage) !== 0) {
                array_push($singleAssignedUser, $currentProfileImage);
            } else {
                $firstInitial = substr($currentFirstName, 0, 1);
                $lastInitial = substr($currentLastName, 0, 1);
                $initials = strtoupper($firstInitial) . strtoupper($lastInitial);
                array_push($singleAssignedUser, $initials);
            }
            $currentFullName = $currentFirstName . " " . $currentLastName;
            array_push($singleAssignedUser, $currentFullName);
            array_push($singleAssignedToMember, $currentFullName);
            $currentEmail = $row1['email'];
            array_push($singleAssignedUser, $currentEmail);
            $currentRole = $row1['role'];
            array_push($singleAssignedUser, $currentRole);
            $currentDepartment = $row1['department'];
            array_push($singleAssignedUser, $currentDepartment);
            $currentID = $row1['user_id'];
            array_push($singleAssignedUser, $currentID);
            array_push($allAssignedUsers, $singleAssignedUser);
            array_push($allAssignedToMembers, $singleAssignedToMember);
        }
    }
}

//project tickets
$assignedTicketNumber = 100;

$allAssignedTickets = [];
$getAssignedTickets = "SELECT * FROM project_tickets pt JOIN tickets t ON t.ticket_id = pt.ticket_id WHERE project_id = ?";
$stmt = $dbh->prepare($getAssignedTickets);
$params = [$projectID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $singleAssignedTicket = [];
        // $assignedTicketNumber++;
        // $currentAssignedTicketNumber = $assignedTicketNumber;
        // array_push($singleAssignedTicket, $currentAssignedTicketNumber);
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
        $currentAssignedTicketName = $row['ticket_name'];
        array_push($singleAssignedTicket, $currentAssignedTicketName);
        $currentAssignedTicketID = $row['ticket_id'];
        $getCurrentCreatedBy = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.created_by WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCurrentCreatedBy);
        $params1 = [$currentAssignedTicketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $currentCreatedBy = $firstName . " " . $lastName;
            array_push($singleAssignedTicket, $currentCreatedBy);
        }
        $currentAssignedTicketStatus = $row['status'];
        array_push($singleAssignedTicket, $currentAssignedTicketStatus);
        $getCurrentAssignedTo = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.assigned_to WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCurrentAssignedTo);
        $params1 = [$currentAssignedTicketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $lastName = $row1['last_name'];
            $currentAssignedTo = $firstName . " " . $lastName;
            array_push($singleAssignedTicket, $currentAssignedTo);
        }
        array_push($singleAssignedTicket, $currentAssignedTicketID);
        $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $currentTicketCreatedDateTime = $row['created_on'];
        $currentDate = substr($currentTicketCreatedDateTime, 8, 2);
        $currentMonthNumber = substr($currentTicketCreatedDateTime, 5, 2);
        $currentHour = substr($currentTicketCreatedDateTime, 11, 2);
        $currentMinute = substr($currentTicketCreatedDateTime, 14, 2);
        if ($currentHour < "12") {
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
        $getCurrentProjectTitle = "SELECT project_title FROM projects WHERE project_id = ?";
        $stmt1 = $dbh->prepare($getCurrentProjectTitle);
        $params1 = [$projectID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $currentProjectTitle = $row1['project_title'];
            array_push($singleAssignedTicket, $currentProjectTitle);
        }

        array_push($allAssignedTickets, $singleAssignedTicket);
    }
}


//add user
$allNonTeamMembers = [];
$getAllNonTeamMembers = "SELECT first_name, last_name FROM team_members tm JOIN users u ON u.user_id = tm.member where member NOT IN (Select member from team_members where team_id = ?)
 UNION SELECT first_name, last_name FROM users WHERE user_id NOT IN (Select member from team_members)";
$stmt = $dbh->prepare($getAllNonTeamMembers);
$params = [$assignedTeamID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $fullName = $firstName . " " . $lastName;
        array_push($allNonTeamMembers, $fullName);
    }
}

if (isset($_POST['add-user'])) {
    $selectedUsers = $_POST['users'];
    $selectedUserIDs = [];
    foreach ($selectedUsers as $selectedUser) {
        $selectedUserFullNameArray = explode(" ", $selectedUser);
        $selectedUserFirstName = $selectedUserFullNameArray[0];
        $selectedUserLastName = $selectedUserFullNameArray[1];
        $getSelectedUserID = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
        $stmt = $dbh->prepare($getSelectedUserID);
        $params = [trim($selectedUserFirstName), trim($selectedUserLastName)];
        $success = $stmt->execute($params);
        if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
            $selectedUserID = $row['user_id'];
            array_push($selectedUserIDs, $selectedUserID);
        } else {
            echo "get user id fail";
        }
    }

    foreach ($selectedUserIDs as $selectedID) {
        $addUserToTeam = "INSERT INTO team_members(team_id, member) VALUES(?,?)";
        echo "<meta http-equiv='refresh' content='0'>";
        $stmt = $dbh->prepare($addUserToTeam);
        $params = [$assignedTeamID, $selectedID];
        $success = $stmt->execute($params);
    }
}


//add ticket
$allNonAssignedTickets = [];
$getAllNonAssignedTickets = "SELECT ticket_name FROM project_tickets pt JOIN tickets t ON t.ticket_id = pt.ticket_id WHERE pt.ticket_id NOT IN (SELECT ticket_id FROM project_tickets WHERE project_id = ?)
UNION SELECT ticket_name FROM tickets WHERE ticket_id NOT IN (SELECT ticket_id FROM project_tickets)";
$stmt = $dbh->prepare($getAllNonAssignedTickets);
$params = [$projectID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $ticketName = $row['ticket_name'];
        array_push($allNonAssignedTickets, $ticketName);
    }
}

if (isset($_POST['add-ticket'])) {
    $selectedTickets = $_POST['tickets'];
    $selectedTicketIDs = [];
    foreach ($selectedTickets as $selectedTicket) {
        $getSelectedTicketID = "SELECT ticket_id FROM tickets WHERE ticket_name = ?";
        $stmt = $dbh->prepare($getSelectedTicketID);
        $params = [trim($selectedTicket)];
        $success = $stmt->execute($params);
        if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
            $selectedTicketID = $row['ticket_id'];
            array_push($selectedTicketIDs, $selectedTicketID);
        } else {
            echo "get ticket id fail";
        }
    }

    foreach ($selectedTicketIDs as $selectedID) {
        $addTicketToProject = "INSERT INTO project_tickets (project_id, ticket_id) VALUES(?,?)";
        echo "<meta http-equiv='refresh' content='0'>";
        $stmt = $dbh->prepare($addTicketToProject);
        $params = [$projectID, $selectedID];
        $success = $stmt->execute($params);
    }
}
