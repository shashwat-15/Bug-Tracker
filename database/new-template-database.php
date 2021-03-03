<?php
include "connect.php";
date_default_timezone_set('America/Toronto');
//users
$roles = ["Admin", "Manager", "Employee"];
$departments = ["Sales", "HR", "IT"];
$allTheProjects = [];

$getAllProjects = "SELECT project_title FROM projects";
$stmt = $dbh->prepare($getAllProjects);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        array_push($allTheProjects, $row['project_title']);
    }
}

if (isset($_POST['user-submit'])) {

    $first_name = filter_input(INPUT_POST, "new-first-name", FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, "new-last-name", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "new-email", FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, "new-phone", FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST, "role", FILTER_SANITIZE_STRING);
    $department = filter_input(INPUT_POST, "department", FILTER_SANITIZE_STRING);
    // $profile_img = filter_input(INPUT_POST, "profile-photo", FILTER_SANITIZE_STRING);

    if ($first_name !== null && $first_name !== "" && $last_name !== null && $last_name !== "" && $email !== null && $email !== "") {

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
                    $createNewUser = "INSERT INTO users(first_name, last_name, email, phone, role, department, profile_image) VALUES(?, ?, ?, ?, ?, ?, ?)";
                    echo "<meta http-equiv='refresh' content='0'>";
                    $stmt = $dbh->prepare($createNewUser);
                    $params = [$first_name, $last_name, $email, $phone, $role, $department, $profile_image_name];
                    $success = $stmt->execute($params);
                } else {

                    echo "Sorry, there was an error uploading your profile picture.";
                }
            }
        } else {
            $createNewUser = "INSERT INTO users(first_name, last_name, email, phone, role, department) VALUES(?, ?, ?, ?, ?, ?)";
            echo "<meta http-equiv='refresh' content='0'>";
            $stmt = $dbh->prepare($createNewUser);
            $params = [$first_name, $last_name, $email, $phone, $role, $department];
            $success = $stmt->execute($params);
        }

        if ($role === "Manager" || $role === "Employee") {
            $getLastUserID = "SELECT MAX(user_id) FROM users";
            $stmt = $dbh->prepare($getLastUserID);
            $success = $stmt->execute();

            if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
                $newUserID = $row[0];


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

                foreach ($selectedProjectsTeamIDs as $selectedProjectTeamID) {
                    $addUserToTeam = "INSERT INTO team_members(team_id, member) VALUES(?,?)";
                    echo "<meta http-equiv='refresh' content='0'>";
                    $stmt = $dbh->prepare($addUserToTeam);
                    $params = [$selectedProjectTeamID, $newUserID];
                    $success = $stmt->execute($params);
                }
            }
        }
    }
}

//projects
$teams = [];
$getAllTeams = "SELECT team_name FROM teams";
$stmt = $dbh->prepare($getAllTeams);
$success = $stmt->execute();

if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        array_push($teams, $row['team_name']);
    }
}

//$teamMembers = [];
// $getAllUsers = "SELECT first_name, last_name FROM users";
// $stmt = $dbh->prepare($getAllUsers);
// $success = $stmt->execute();

// if ($success && $stmt->rowCount()) {
//     while ($row = $stmt->fetch()) {
//         $fullName = $row['first_name'] . " " . $row['last_name'];
//         array_push($teamMembers, $fullName);
//     }
// }

if (isset($_POST['project-submit'])) {

    $projectTitle = filter_input(INPUT_POST, "new-title-0", FILTER_SANITIZE_STRING);
    $projectDescription = filter_input(INPUT_POST, "new-description-0", FILTER_SANITIZE_STRING);
    $team = filter_input(INPUT_POST, "team", FILTER_SANITIZE_STRING);
    $owner = filter_input(INPUT_POST, "owner", FILTER_SANITIZE_STRING);
    $startDate = filter_input(INPUT_POST, "start-date", FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, "end-date-0", FILTER_SANITIZE_STRING);
    $currentDateTime = date("Y-m-d H:i:s");

    if ($projectTitle !== null && $projectTitle !== "" && $team !== null && $team !== "" && $owner !== null && $owner !== "") {
        $getTeamID = "SELECT team_id FROM teams WHERE team_name = ?";
        $stmt1 = $dbh->prepare($getTeamID);
        $params1 = [$team];
        $success1 = $stmt1->execute($params1);

        $ownerFullNameArray = explode(" ", $owner);
        $ownerFirstName = $ownerFullNameArray[0];
        $ownerLastName = $ownerFullNameArray[1];

        $getOwnerID = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
        $stmt2 = $dbh->prepare($getOwnerID);
        $params2 = [$ownerFirstName, $ownerLastName];
        $success2 = $stmt2->execute($params2);

        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $teamID = $row1['team_id'];
            if ($success2 && $stmt2->rowCount() === 1 && $row2 = $stmt2->fetch()) {
                $ownerID = $row2['user_id'];

                $createNewProject = "INSERT INTO projects (project_title, project_description, team, owner, start_date, end_date, created_on) VALUES (?,?,?,?,?,?,?)";
                echo "<meta http-equiv='refresh' content='0'>";
                $stmt = $dbh->prepare($createNewProject);
                $params = [trim($projectTitle), trim($projectDescription), $teamID, $ownerID, $startDate, $endDate, $currentDateTime];
                $success = $stmt->execute($params);
            }
        }
    }
}

//tickets
if (isset($_POST['ticket-submit'])) {

    $ticketName = filter_input(INPUT_POST, "new-title-1", FILTER_SANITIZE_STRING);
    $ticketDetails = filter_input(INPUT_POST, "new-description-1", FILTER_SANITIZE_STRING);
    $selectedProjects = $_POST['projects'];
    $assignedTo = filter_input(INPUT_POST, "assigned-to", FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, "status", FILTER_SANITIZE_STRING);
    $dueDate = filter_input(INPUT_POST, "end-date-1", FILTER_SANITIZE_STRING);
    $priority = filter_input(INPUT_POST, "priority", FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
    $currentDateTime = date("Y-m-d H:i:s");

    if (
        $ticketName !== null && $ticketName !== "" && count($selectedProjects) !== 0 && $assignedTo !== null && $assignedTo !== ""
        && $status !== null && $status !== "" && $priority !== null && $priority !== "" && $type !== null && $type !== ""
    ) {

        $createNewTicket = "INSERT INTO tickets (ticket_name, ticket_details, status, priority, type, due_date, created_on) VALUES (?,?,?,?,?,?,?)";
        echo "<meta http-equiv='refresh' content='0'>";
        $stmt = $dbh->prepare($createNewTicket);
        $params = [$ticketName, trim($ticketDetails), $status, $priority, $type, $dueDate, $currentDateTime];
        $success = $stmt->execute($params);


        $getLastTicketID = "SELECT MAX(ticket_id) FROM tickets";
        $stmt = $dbh->prepare($getLastTicketID);
        $success = $stmt->execute();

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

        // $getTicketProjectID = "SELECT project_id FROM projects WHERE project_title = ?";
        // $stmt1 = $dbh->prepare($getTicketProjectID);
        // $params1 = [trim($project)];
        // $success1 = $stmt1->execute($params1);

        $assignedToFullNameArray = explode(" ", $assignedTo);
        $assignedToFirstName = $assignedToFullNameArray[0];
        $assignedToLastName = $assignedToFullNameArray[1];

        $getassignedToID = "SELECT user_id FROM users WHERE first_name = ? AND last_name = ?";
        $stmt2 = $dbh->prepare($getassignedToID);
        $params2 = [$assignedToFirstName, $assignedToLastName];
        $success2 = $stmt2->execute($params2);

        if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
            $newTicketID = $row[0];
            //if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            //$ticketProjectID = $row1['project_id'];
            if ($success2 && $stmt2->rowCount() === 1 && $row2 = $stmt2->fetch()) {
                $assignedToID = $row2['user_id'];
                $createdByID = 1; //this will be the id of the logged in user
                $assignUserToTicket = "INSERT INTO user_tickets (created_by, ticket_id, assigned_to) VALUES (?, ?, ?)";
                $stmt3 = $dbh->prepare($assignUserToTicket);
                $params3 = [$createdByID, $newTicketID, $assignedToID];
                $success3 = $stmt3->execute($params3);

                foreach ($selectedProjectsIDs as $selectedProjectsID) {
                    $addProjectToTicket = "INSERT INTO project_tickets(project_id, ticket_id) VALUES (?,?)";
                    $stmt3 = $dbh->prepare($addProjectToTicket);
                    $params3 = [$selectedProjectsID, $newTicketID];
                    $success3 = $stmt3->execute($params3);
                }
                // $assignProjectToTicket = "INSERT INTO project_tickets (project_id, ticket_id) VALUES (?,?)";
                // echo "<meta http-equiv='refresh' content='0'>";
                // $stmt3 = $dbh->prepare($assignProjectToTicket);
                // $params3 = [$ticketProjectID, $newTicketID];
                // $success3 = $stmt3->execute($params3);
            }
            //}
        }
    }
}
