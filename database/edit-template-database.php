<?php
include "connect.php";
date_default_timezone_set('America/Toronto');
$loggedInUserId = 1; //this will be the user id of logged in user

if ($page === "projects-details") {
    $projectID = $id;
    $teamMembers = [];
    $projectInfo = [];
    $getProjectInfo = "SELECT * FROM projects WHERE project_id = ?";
    $stmt = $dbh->prepare($getProjectInfo);
    $params = [$projectID];
    $success = $stmt->execute($params);
    if ($success && $stmt->rowCount()) {
        while ($row = $stmt->fetch()) {
            $projectTitle = $row['project_title'];
            array_push($projectInfo, $projectTitle);
            $projectDescription = $row['project_description'];
            array_push($projectInfo, $projectDescription);
            $teamID = $row['team'];
            $getTeam = "SELECT team_name FROM teams WHERE team_id = ?";
            $stmt1 = $dbh->prepare($getTeam);
            $params1 = [$teamID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $team = $row1['team_name'];
                array_push($projectInfo, $team);
            }
            $ownerID = $row['owner'];
            $getOwner = "SELECT first_name, last_name FROM users WHERE user_id = ?";
            $stmt1 = $dbh->prepare($getOwner);
            $params1 = [$ownerID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $firstName = $row1['first_name'];
                $lastName = $row1['last_name'];
                $owner = $firstName . " " . $lastName;
                array_push($projectInfo, $owner);
            }
            $startDate = $row['start_date'];
            array_push($projectInfo, $startDate);
            $endDate = $row['end_date'];
            array_push($projectInfo, $endDate);
            $getTeamMembers = "SELECT first_name, last_name FROM team_members tm JOIN users u ON u.user_id = tm.member WHERE team_id = ?";
            $stmt1 = $dbh->prepare($getTeamMembers);
            $params1 = [$teamID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount()) {
                while ($row1 = $stmt1->fetch()) {
                    $firstName = $row1['first_name'];
                    $lastName = $row1['last_name'];
                    $teamMember = $firstName . " " . $lastName;
                    array_push($teamMembers, $teamMember);
                }
            }
        }
    }

    if (isset($_POST['project-update'])) {
        $projectTitle = filter_input(INPUT_POST, "new-title-0", FILTER_SANITIZE_STRING);
        $projectDescription = filter_input(INPUT_POST, "new-description-0", FILTER_SANITIZE_STRING);
        $team = filter_input(INPUT_POST, "team", FILTER_SANITIZE_STRING);
        if (trim($team) === "hide") {
            $team = filter_input(INPUT_POST, "default-team", FILTER_SANITIZE_STRING);
        }
        $owner = filter_input(INPUT_POST, "owner", FILTER_SANITIZE_STRING);
        if (trim($owner) === "hide") {
            $owner = filter_input(INPUT_POST, "default-owner", FILTER_SANITIZE_STRING);
        }
        $startDate = filter_input(INPUT_POST, "start-date", FILTER_SANITIZE_STRING);
        $endDate = filter_input(INPUT_POST, "end-date-0", FILTER_SANITIZE_STRING);

        if ($projectTitle !== null && $projectTitle !== "" && $team !== null && $team !== "" && $owner !== null && $owner !== "") {
            $getTeamID = "SELECT team_id FROM teams WHERE team_name = ?";
            $stmt1 = $dbh->prepare($getTeamID);
            $params1 = [trim($team)];
            $success1 = $stmt1->execute($params1);

            $ownerFullNameArray = explode(" ", $owner);
            $ownerFirstName = $ownerFullNameArray[0];
            $ownerLastName = $ownerFullNameArray[1];

            $getOwnerID = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
            $stmt2 = $dbh->prepare($getOwnerID);
            $params2 = [trim($ownerFirstName), trim($ownerLastName)];
            $success2 = $stmt2->execute($params2);

            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $teamID = $row1['team_id'];
                if ($success2 && $stmt2->rowCount() === 1 && $row2 = $stmt2->fetch()) {
                    $ownerID = $row2['user_id'];

                    $updateProject = "UPDATE projects SET project_title = ?, project_description = ?, team = ?, owner = ?, start_date = ?, end_date = ? WHERE project_id = ?";
                    echo "<meta http-equiv='refresh' content='0'>";
                    $stmt = $dbh->prepare($updateProject);
                    $params = [trim($projectTitle), trim($projectDescription), $teamID, $ownerID, $startDate, $endDate, $projectID];
                    $success = $stmt->execute($params);
                }
            }
        }
    }
} else if ($page === "tickets-details") {
    $ticketID = $id;
    $assignedToTeamMembers = [];
    $ticketInfo = [];
    $ticketProjects = [];
    $getTicketInfo = "SELECT * FROM tickets WHERE ticket_id = ?";
    $stmt = $dbh->prepare($getTicketInfo);
    $params = [$ticketID];
    $success = $stmt->execute($params);
    if ($success && $stmt->rowCount()) {
        while ($row = $stmt->fetch()) {
            $ticketName = $row['ticket_name'];
            array_push($ticketInfo, $ticketName);
            $ticketDescription = $row['ticket_details'];
            array_push($ticketInfo, $ticketDescription);
            $getProject = "SELECT project_title FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id WHERE ticket_id = ?";
            $stmt1 = $dbh->prepare($getProject);
            $params1 = [$ticketID]; //stopped here
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount()) {
                while ($row1 = $stmt1->fetch()) {
                    $projectTitle = $row1['project_title'];
                    array_push($ticketProjects, $projectTitle);
                }
                array_push($ticketInfo, $ticketProjects);
            }
            $status = $row['status'];
            array_push($ticketInfo, $status);
            $getAssignedTo = "SELECT first_name, last_name FROM user_tickets ut JOIN users u ON u.user_id = ut.assigned_to WHERE ticket_id = ?";
            $stmt1 = $dbh->prepare($getAssignedTo);
            $params1 = [$ticketID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $firstName = $row1['first_name'];
                $lastName = $row1['last_name'];
                $assignedTo = $firstName . " " . $lastName;
                array_push($ticketInfo, $assignedTo);
            }
            $dueDate = $row['due_date'];
            array_push($ticketInfo, $dueDate);
            $priority = $row['priority'];
            array_push($ticketInfo, $priority);
            $type = $row['type'];
            array_push($ticketInfo, $type);
            $getAssignedToTeamMembers = "SELECT first_name, last_name FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id 
            JOIN team_members tm ON tm.team_id = p.team JOIN users u ON u.user_id = tm.member WHERE ticket_id = ?";
            $stmt1 = $dbh->prepare($getAssignedToTeamMembers);
            $params1 = [$ticketID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount()) {
                while ($row1 = $stmt1->fetch()) {
                    $firstName = $row1['first_name'];
                    $lastName = $row1['last_name'];
                    $assignedToTeamMember = $firstName . " " . $lastName;
                    array_push($assignedToTeamMembers, $assignedToTeamMember);
                }
            }
        }
    }

    if (isset($_POST['ticket-update'])) {
        $ticketName = filter_input(INPUT_POST, "new-title-1", FILTER_SANITIZE_STRING);
        $ticketDescription = filter_input(INPUT_POST, "new-description-1", FILTER_SANITIZE_STRING);
        $selectedProjects = $_POST['projects'];
        $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_STRING);
        if (trim($status) === "hide") {
            $status = filter_input(INPUT_POST, "default-status", FILTER_SANITIZE_STRING);
        }
        $assignedTo = filter_input(INPUT_POST, "assigned-to", FILTER_SANITIZE_STRING);
        if (trim($assignedTo) === "hide") {
            $assignedTo = filter_input(INPUT_POST, "default-agent", FILTER_SANITIZE_STRING);
        }
        $dueDate = filter_input(INPUT_POST, "end-date-1", FILTER_SANITIZE_STRING);
        $priority = filter_input(INPUT_POST, "priority", FILTER_SANITIZE_STRING);
        if (trim($priority) === "hide") {
            $priority = filter_input(INPUT_POST, "default-priority", FILTER_SANITIZE_STRING);
        }
        $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
        if (trim($type) === "hide") {
            $type = filter_input(INPUT_POST, "default-type", FILTER_SANITIZE_STRING);
        }

        if (
            $ticketName !== null && $ticketName !== "" && count($selectedProjects) !== 0 && $status !== null && $status !== ""
            && $assignedTo !== null && $assignedTo !== "" && $priority !== null && $priority !== "" && $type !== null && $type !== ""
        ) {

            $selectedProjectsIDs = [];
            foreach ($selectedProjects as $selectedProject) {
                $getSelectedProjectID = "SELECT project_id FROM projects WHERE project_title = ?";
                $stmt1 = $dbh->prepare($getSelectedProjectID);
                $params1 = [trim($selectedProject)];
                $success1 = $stmt1->execute($params1);
                if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                    $selectedProjectID = $row1['project_id'];
                    array_push($selectedProjectsIDs, $selectedProjectID);
                } else {
                    echo "get project id fail";
                }
            }

            $assignedProjectsIDs = [];
            $getAssignedProjectsIDs = "SELECT project_id FROM project_tickets WHERE ticket_id = ?";
            $stmt1 = $dbh->prepare($getAssignedProjectsIDs);
            $params1 = [$ticketID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount()) {
                while ($row1 = $stmt1->fetch()) {
                    $assignedProjectID = $row1['project_id'];
                    array_push($assignedProjectsIDs, $assignedProjectID);
                }
            }

            $projectsToBeAdded = array_diff($selectedProjectsIDs, $assignedProjectsIDs);
            foreach ($projectsToBeAdded as $projectToBeAdded) {
                $addProjectToTicket = "INSERT INTO project_tickets(project_id, ticket_id) VALUES (?,?)";
                $stmt1 = $dbh->prepare($addProjectToTicket);
                $params1 = [$projectToBeAdded, $ticketID];
                $success1 = $stmt1->execute($params1);
            }

            $projectsToBeDeleted = array_diff($assignedProjectsIDs, $selectedProjectsIDs);
            foreach ($projectsToBeDeleted as $projectToBeDeleted) {
                $deleteProjectFromTicket = "DELETE FROM project_tickets WHERE project_id = ? AND ticket_id = ?";
                $stmt1 = $dbh->prepare($deleteProjectFromTicket);
                $params1 = [$projectToBeDeleted, $ticketID];
                $success1 = $stmt1->execute($params1);
            }

            //for ticket activity, check if previous projects are different than selected projects, if yes then insert into activities
            if ($assignedProjectsIDs !== $selectedProjectsIDs) {
                $oldProjectsString = "";
                foreach ($assignedProjectsIDs as $assignedProjectsID) {
                    $getOldProjectsTitles = "SELECT project_title FROM projects WHERE project_id = ?";
                    $stmt1 = $dbh->prepare($getOldProjectsTitles);
                    $params1 = [$assignedProjectsID];
                    $success1 = $stmt1->execute($params1);
                    if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                        $oldProjectsString .= $row1['project_title'] . ", ";
                    }
                }
                $oldProjectsString = rtrim($oldProjectsString, ", ");
                $newProjectsString = "";
                foreach ($selectedProjectsIDs as $selectedProjectsID) {
                    $getNewProjectsTitles = "SELECT project_title FROM projects WHERE project_id = ?";
                    $stmt1 = $dbh->prepare($getNewProjectsTitles);
                    $params1 = [$selectedProjectsID];
                    $success1 = $stmt1->execute($params1);
                    if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                        $newProjectsString .= $row1['project_title'] . ", ";
                    }
                }
                $newProjectsString = rtrim($newProjectsString, ", ");
                $insertNewActivity = "INSERT INTO activities(ticket_id, user_id, prop_name, old_value, new_value, activity_time) VALUES (?,?,?,?,?,?)";
                $stmt1 = $dbh->prepare($insertNewActivity);
                $params1 = [$ticketID, $loggedInUserId, "Projects", trim($oldProjectsString), trim($newProjectsString), date('Y-m-d H:i:s')];
                $success1 = $stmt1->execute($params1);
            }
            //end

            $assignedToFullNameArray = explode(" ", $assignedTo);
            $assignedToFirstName = $assignedToFullNameArray[0];
            $assignedToLastName = $assignedToFullNameArray[1];

            $getAssignedToID = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
            $stmt1 = $dbh->prepare($getAssignedToID);
            $params1 = [trim($assignedToFirstName), trim($assignedToLastName)];
            $success1 = $stmt1->execute($params1);

            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $assignedToID = $row1['user_id'];

                //for ticket activity before update
                $oldAssignedToInfo = [];
                $getOldAssignedTo = "SELECT first_name, last_name, assigned_to FROM user_tickets ut JOIN users u ON u.user_id = ut.assigned_to WHERE ticket_id = ?";
                $stmt2 = $dbh->prepare($getOldAssignedTo);
                $params2 = [$ticketID];
                $success2 = $stmt2->execute($params2);
                if ($success2 && $stmt2->rowCount() === 1 && $row2 = $stmt2->fetch()) {
                    $firstName = $row2['first_name'];
                    $lastName = $row2['last_name'];
                    $oldAssignedTo = $firstName . " " . $lastName;
                    $oldAssignedToID = $row2['assigned_to'];
                    array_push($oldAssignedToInfo, $oldAssignedToID);
                    array_push($oldAssignedToInfo, $oldAssignedTo);
                }
                //end

                $updateassignedTo = "UPDATE user_tickets SET assigned_to = ? WHERE ticket_id = ?";
                $stmt2 = $dbh->prepare($updateassignedTo);
                $params2 = [$assignedToID, $ticketID];
                $success2 = $stmt2->execute($params2);

                //for ticket activity after update
                $newAssignedToInfo = [];
                $getNewAssignedTo = "SELECT first_name, last_name, assigned_to FROM user_tickets ut JOIN users u ON u.user_id = ut.assigned_to WHERE ticket_id = ?";
                $stmt2 = $dbh->prepare($getNewAssignedTo);
                $params2 = [$ticketID];
                $success2 = $stmt2->execute($params2);
                if ($success2 && $stmt2->rowCount() === 1 && $row2 = $stmt2->fetch()) {
                    $firstName = $row2['first_name'];
                    $lastName = $row2['last_name'];
                    $newAssignedTo = $firstName . " " . $lastName;
                    $newAssignedToID = $row2['assigned_to'];
                    array_push($newAssignedToInfo, $newAssignedToID);
                    array_push($newAssignedToInfo, $newAssignedTo);
                }
                //end

                //check if old value is different than new value, if yes then insert into activities table
                if ($newAssignedToInfo[0] !== $oldAssignedToInfo[0]) {
                    $insertNewActivity = "INSERT INTO activities(ticket_id, user_id, prop_name, old_value, new_value, activity_time) VALUES(?, ?, ?, ?, ?, ?)";
                    $stmt2 = $dbh->prepare($insertNewActivity);
                    $params2 = [$ticketID, $loggedInUserId, "Assigned To", trim($oldAssignedToInfo[1]), trim($newAssignedToInfo[1]), date('Y-m-d H:i:s')];
                    $success2 = $stmt2->execute($params2);
                }
                //end
            }


            //for ticket activity before update
            $oldTicketInfo = [];
            $getOldTicket = "SELECT * FROM tickets WHERE ticket_id = ?";
            $stmt2 = $dbh->prepare($getOldTicket);
            $params2 = [$ticketID];
            $success2 = $stmt2->execute($params2);
            if ($success2 && $stmt2->rowCount()) {
                while ($row2 = $stmt2->fetch()) {
                    $oldTicketName = $row2['ticket_name'];
                    array_push($oldTicketInfo, $oldTicketName);
                    $oldTicketDescription = $row2['ticket_details'];
                    array_push($oldTicketInfo, $oldTicketDescription);
                    $oldStatus = $row2['status'];
                    array_push($oldTicketInfo, $oldStatus);
                    $oldPriority = $row2['priority'];
                    array_push($oldTicketInfo, $oldPriority);
                    $oldType = $row2['type'];
                    array_push($oldTicketInfo, $oldType);
                    $oldDueDate = $row2['due_date'];
                    array_push($oldTicketInfo, $oldDueDate);
                }
            }
            //end

            $updateTicket = "UPDATE tickets SET ticket_name = ?, ticket_details = ?, status = ?, priority = ?, type=?, due_date = ? WHERE ticket_id = ?";
            echo "<meta http-equiv='refresh' content='0'>";
            $stmt = $dbh->prepare($updateTicket);
            $params = [$ticketName, $ticketDescription, $status, $priority, $type, $dueDate, $ticketID];
            $success = $stmt->execute($params);

            //for ticket activity after update
            $updatedTicketInfo = [];
            $getUpdatedTicket = "SELECT * FROM tickets WHERE ticket_id = ?";
            $stmt2 = $dbh->prepare($getUpdatedTicket);
            $params2 = [$ticketID];
            $success2 = $stmt2->execute($params2);
            if ($success2 && $stmt2->rowCount()) {
                while ($row2 = $stmt2->fetch()) {
                    $updatedTicketName = $row2['ticket_name'];
                    array_push($updatedTicketInfo, $updatedTicketName);
                    $updatedTicketDescription = $row2['ticket_details'];
                    array_push($updatedTicketInfo, $updatedTicketDescription);
                    $updatedStatus = $row2['status'];
                    array_push($updatedTicketInfo, $updatedStatus);
                    $updatedPriority = $row2['priority'];
                    array_push($updatedTicketInfo, $updatedPriority);
                    $updatedType = $row2['type'];
                    array_push($updatedTicketInfo, $updatedType);
                    $updatedDueDate = $row2['due_date'];
                    array_push($updatedTicketInfo, $updatedDueDate);
                }
            }
            //end

            //check if old value is different than new value, if yes then insert into activities table
            $propNames = ["Ticket Name", "Ticket Description", "Status", "Priority", "Type", "Due Date"];
            for ($i = 0; $i < count($oldTicketInfo); $i++) {
                if ($oldTicketInfo[$i] !== $updatedTicketInfo[$i]) {
                    $propName = $propNames[$i];
                    $oldValue = $oldTicketInfo[$i];
                    $newValue = $updatedTicketInfo[$i];
                    $insertNewActivity = "INSERT INTO activities(ticket_id, user_id, prop_name, old_value, new_value, activity_time) VALUES(?, ?, ?, ?, ?, ?)";
                    $stmt2 = $dbh->prepare($insertNewActivity);
                    $params2 = [$ticketID, $loggedInUserId, $propName, trim($oldValue), trim($newValue), date('Y-m-d H:i:s')];
                    $success2 = $stmt2->execute($params2);
                }
            }
        }
    }
} else if ($page === "users-details") {
    $userID = $id;
    $userInfo = [];
    $userProjects = [];
    $getUser = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $dbh->prepare($getUser);
    $params = [$userID];
    $success = $stmt->execute($params);
    if ($success && $stmt->rowCount()) {
        while ($row = $stmt->fetch()) {
            $profileImage = $row['profile_image'];
            $userFirstName = $row['first_name'];
            $userLastName = $row['last_name'];
            if (strlen($profileImage) !== 0) {
                array_push($userInfo, $profileImage);
            } else {
                $firstInitial = substr($userFirstName, 0, 1);
                $lastInitial = substr($userLastName, 0, 1);
                $initials = strtoupper($firstInitial) . strtoupper($lastInitial);
                array_push($userInfo, $initials);
            }
            array_push($userInfo, $userFirstName);
            array_push($userInfo, $userLastName);
            $userEmail = $row['email'];
            array_push($userInfo, $userEmail);
            $userPhone = $row['phone'];
            array_push($userInfo, $userPhone);
            $userRole = $row['role'];
            array_push($userInfo, $userRole);
            $userDepartment = $row['department'];
            array_push($userInfo, $userDepartment);
            if ($userRole === "Manager" || $userRole === "Employee") {
                $getProject = "SELECT project_title FROM team_members tm JOIN projects p ON p.team = tm.team_id WHERE member = ?";
                $stmt1 = $dbh->prepare($getProject);
                $params1 = [$userID]; //stopped here
                $success1 = $stmt1->execute($params1);
                if ($success1 && $stmt1->rowCount()) {
                    while ($row1 = $stmt1->fetch()) {
                        $projectTitle = $row1['project_title'];
                        array_push($userProjects, $projectTitle);
                    }
                }
                array_push($userInfo, $userProjects);
            }
        }
    }

    if (isset($_POST['user-update'])) {
        $first_name = filter_input(INPUT_POST, "new-first-name", FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, "new-last-name", FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, "new-email", FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, "new-phone", FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_STRING);
        if (trim($role) === "hide") {
            $role = filter_input(INPUT_POST, "default-role", FILTER_SANITIZE_STRING);
        }
        $department = filter_input(INPUT_POST, "department", FILTER_SANITIZE_STRING);
        if (trim($department) === "hide") {
            $department = filter_input(INPUT_POST, "default-department", FILTER_SANITIZE_STRING);
        }

        if ($first_name !== null && $first_name !== "" && $last_name !== null && $last_name !== "" && $email !== null && $email !== "") {

            if ($role === "Manager" || $role === "Employee") {

                $selectedProjects = $_POST['projects'];
                $selectedProjectsTeamIDs = [];
                foreach ($selectedProjects as $selectedProject) {
                    $getSelectedProjectTeamID = "SELECT team FROM projects WHERE project_title = ?";
                    $stmt = $dbh->prepare($getSelectedProjectTeamID);
                    $params = [trim($selectedProject)];
                    $success = $stmt->execute($params);
                    if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
                        $selectedTeamID = $row['team'];
                        array_push($selectedProjectsTeamIDs, $selectedTeamID);
                    } else {
                        echo "get team id fail";
                    }
                }

                $assignedProjectsTeamIDs = [];
                $getAssignedProjectsTeamIDs = "SELECT team_id FROM team_members WHERE member = ?";
                $stmt1 = $dbh->prepare($getAssignedProjectsTeamIDs);
                $params1 = [$userID];
                $success1 = $stmt1->execute($params1);
                if ($success1 && $stmt1->rowCount()) {
                    while ($row1 = $stmt1->fetch()) {
                        $assignedProjectTeamID = $row1['team_id'];
                        array_push($assignedProjectsTeamIDs, $assignedProjectTeamID);
                    }
                }

                $projectTeamsToBeAdded = array_diff($selectedProjectsTeamIDs, $assignedProjectsTeamIDs);
                foreach ($projectTeamsToBeAdded as $projectTeamToBeAdded) {
                    $addUserToProjectTeam = "INSERT INTO team_members(team_id, member) VALUES (?,?)";
                    $stmt1 = $dbh->prepare($addUserToProjectTeam);
                    $params1 = [$projectTeamToBeAdded, $userID];
                    $success1 = $stmt1->execute($params1);
                }

                $projectsTeamsToBeDeleted = array_diff($assignedProjectsTeamIDs, $selectedProjectsTeamIDs);
                foreach ($projectsTeamsToBeDeleted as $projectTeamToBeDeleted) {
                    $deleteUserFromProjectTeam = "DELETE FROM team_members WHERE team_id = ? AND member = ?";
                    $stmt1 = $dbh->prepare($deleteUserFromProjectTeam);
                    $params1 = [$projectTeamToBeDeleted, $userID];
                    $success1 = $stmt1->execute($params1);
                }
            }

            if ($_FILES["profile-photo"]["name"] !== null && $_FILES["profile-photo"]["name"] !== "") {

                $target_dir = "uploads/profile-pictures/";
                $target_file = $target_dir . basename($_FILES["profile-photo"]["name"]);
                $uploadOk = 1;

                // Select file type
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                //check if image file is an actual image or fake
                $check = getimagesize($_FILES["profile-photo"]["tmp_name"]);
                if ($check !== false) {
                    //echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }

                // Check file size
                // if ($_FILES["fileToUpload"]["size"] > 500000) {
                //   echo "Sorry, your file is too large.";
                //   $uploadOk = 0;
                // }

                // Valid file extensions
                $extensions_arr = array("jpg", "jpeg", "png", "gif");


                // Check extension
                if (!in_array($imageFileType, $extensions_arr)) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                    echo "Sorry, your profile picture was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["profile-photo"]["tmp_name"], $target_file)) {
                        $profile_image_name =  htmlspecialchars(basename($_FILES["profile-photo"]["name"]));
                        // Insert record
                        $updateUser = "UPDATE users SET first_name =?, last_name = ?, email = ?, phone = ?, role = ?, department = ?, profile_image = ? WHERE user_id = ?";
                        echo "<meta http-equiv='refresh' content='0'>";
                        $stmt = $dbh->prepare($updateUser);
                        $params = [$first_name, $last_name, $email, $phone, $role, $department, $profile_image_name, $userID];
                        $success = $stmt->execute($params);
                    } else {

                        echo "Sorry, there was an error uploading your profile picture.";
                    }
                }
            } else {
                $updateUser = "UPDATE users SET first_name =?, last_name = ?, email = ?, phone = ?, role = ?, department = ? WHERE user_id = ?";
                echo "<meta http-equiv='refresh' content='0'>";
                $stmt = $dbh->prepare($updateUser);
                $params = [$first_name, $last_name, $email, $phone, $role, $department, $userID];
                $success = $stmt->execute($params);
            }
        }
    }
}
