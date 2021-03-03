<?php
include "connect.php";
date_default_timezone_set('America/Toronto');
$loggedInUserId = 1; //this will be the user id of logged in user

$getTotalTickets = "SELECT COUNT(*) FROM tickets";
$stmt = $dbh->prepare($getTotalTickets);
$success = $stmt->execute();
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $totalTickets = $row[0];
}

$getTotalComments = "SELECT COUNT(*) FROM comments";
$stmt = $dbh->prepare($getTotalComments);
$success = $stmt->execute();
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $totalComments = $row[0];
}

$getTotalAttachments = "SELECT COUNT(*) FROM attachments";
$stmt = $dbh->prepare($getTotalAttachments);
$success = $stmt->execute();
if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $totalAttachments = $row[0];
}


$ticketID = $id;
$ticketNumber = 100 + $ticketID;
$ticketInfo = [];
$allCommentsInfo = [];
$ticketProjectsString = "";
array_push($ticketInfo, $ticketNumber);
$getTicketInfo = "SELECT * FROM tickets WHERE ticket_id = ?";
$stmt = $dbh->prepare($getTicketInfo);
$params = [$ticketID];
$success = $stmt->execute($params);
if ($success && $stmt->rowCount()) {
    while ($row = $stmt->fetch()) {
        $ticketName = $row['ticket_name'];
        array_push($ticketInfo, $ticketName);
        $getCreatedBy = "SELECT first_name, last_name from user_tickets ut JOIN users u ON u.user_id = ut.created_by WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getCreatedBy);
        $params1 = [$ticketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
            $firstName = $row1['first_name'];
            $firstInitial = substr($firstName, 0, 1);
            $lastName = $row1['last_name'];
            $lastInitial = substr($lastName, 0, 1);
            $creatorName = $firstName . " " . $lastName;
            $creatorInitials = strtoupper($firstInitial) . strtoupper($lastInitial);
            array_push($ticketInfo, $creatorInitials);
            array_push($ticketInfo, $creatorName);
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
        array_push($ticketInfo, $displayDateString);
        $getProject = "SELECT project_title FROM project_tickets pt JOIN projects p ON p.project_id = pt.project_id WHERE ticket_id = ?";
        $stmt1 = $dbh->prepare($getProject);
        $params1 = [$ticketID];
        $success1 = $stmt1->execute($params1);
        if ($success1 && $stmt1->rowCount()) {
            while ($row1 = $stmt1->fetch()) {
                $ticketProjectTitle = $row1['project_title'];
                $ticketProjectsString .= trim($ticketProjectTitle) . ", ";
            }
            array_push($ticketInfo, rtrim($ticketProjectsString, ", "));
        }
        $ticketDescription = $row['ticket_details'];
        array_push($ticketInfo, $ticketDescription);
        if (strlen($ticketDescription) !== 0) {
            $getCreatorProfilePic = "SELECT profile_image FROM user_tickets ut JOIN users u ON u.user_id = ut.created_by WHERE ticket_id = ?";
            $stmt1 = $dbh->prepare($getCreatorProfilePic);
            $params1 = [$ticketID];
            $success1 = $stmt1->execute($params1);
            if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
                $creatorProfilePic = $row1['profile_image'];
            }
            if (strlen($creatorProfilePic) !== 0) {
                array_push($ticketInfo, $creatorProfilePic);
            } else {
                array_push($ticketInfo, "");
            }
            $calculateNoOfDays = date_diff(date_create($ticketCreatedDateTime), date_create(date("Y-m-d H:i:s")));
            $noOfDays = $calculateNoOfDays->format("%a");
            if ((int)$noOfDays === 1) {
                $noOfDaysString = $noOfDays . " day ago";
            } else {
                $noOfDaysString = $noOfDays . " days ago";
            }
            array_push($ticketInfo, $noOfDaysString);
        }
    }
    //comments
    $userID = 1; //this will be the user id of logged in user
    $getCurrentProfilePic = "SELECT profile_image, first_name, last_name FROM users WHERE user_id = ?";
    $stmt1 = $dbh->prepare($getCurrentProfilePic);
    $params1 = [$userID];
    $success1 = $stmt1->execute($params1);
    if ($success1 && $stmt1->rowCount() === 1 && $row1 = $stmt1->fetch()) {
        $currentProfilePic = $row1['profile_image'];
        if (strlen($currentProfilePic) !== 0) {
            $displayPic = $currentProfilePic;
        } else {
            $currentFirstName = $row1['first_name'];
            $currentLastName = $row1['last_name'];
            $currentFirstInitial = substr($currentFirstName, 0, 1);
            $currentLastInitial = substr($currentLastName, 0, 1);
            $currentInitials = strtoupper($currentFirstInitial) . strtoupper($currentLastInitial);
            $displayPic = $currentInitials;
        }
    }
    $getComments = "SELECT comment_id, comment_body, profile_image, first_name, last_name, comment_time FROM comments c JOIN users u ON u.user_id = c.user_id WHERE ticket_id = ? ORDER BY comment_id DESC";
    $stmt1 = $dbh->prepare($getComments);
    $params1 = [$ticketID];
    $success1 = $stmt1->execute($params1);
    if ($success1 && $stmt1->rowCount()) {
        while ($row1 = $stmt1->fetch()) {
            $singleCommentInfo = [];
            $commentID = $row1['comment_id'];
            $profilePic = $row1['profile_image'];
            $firstName = $row1['first_name'];
            if (strlen($profilePic) !== 0) {
                array_push($singleCommentInfo, $profilePic);
            } else {
                $lastName = $row1['last_name'];
                $firstInitial = substr($firstName, 0, 1);
                $lastInitial = substr($lastName, 0, 1);
                $initials = strtoupper($firstInitial) . strtoupper($lastInitial);
                array_push($singleCommentInfo, $initials);
            }
            array_push($singleCommentInfo, $firstName);
            $monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
            $monthNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $commentCreatedTime = $row1['comment_time'];
            $currentCommentDate = substr($commentCreatedTime, 8, 2);
            $currentCommentMonthNumber = substr($commentCreatedTime, 5, 2);
            $currentCommentHour = substr($commentCreatedTime, 11, 2);
            $currentCommentMinute = substr($commentCreatedTime, 14, 2);
            if ((int)$currentCommentHour < 12) {
                $commentPeriod = "AM";
            } else {
                $currentCommentHour = (int)$currentCommentHour - 12;
                if ((int)$currentCommentHour < 10) {
                    $currentCommentHour = "0" . $currentCommentHour;
                }
                $commentPeriod = "PM";
            }
            for ($i = 0; $i < count($monthNumber); $i++) {
                if ($currentCommentMonthNumber == $monthNumber[$i]) {
                    $currentCommentMonthName = $monthName[$i];
                }
            }
            $commentDisplayDateString = $currentCommentDate . " " . $currentCommentMonthName . " " . $currentCommentHour . ":" . $currentCommentMinute . " " . $commentPeriod;
            array_push($singleCommentInfo, $commentDisplayDateString);
            $commentBody = $row1['comment_body'];
            array_push($singleCommentInfo, $commentBody);
            array_push($singleCommentInfo, $commentID);
            array_push($allCommentsInfo, $singleCommentInfo);
        }
    }
    if (isset($_POST['add-comment'])) {
        $comment = filter_input(INPUT_POST, "comment", FILTER_SANITIZE_STRING);
        $commentTime = date("Y-m-d H:i:s");
        $addComment = "INSERT INTO comments(ticket_id, user_id, comment_body, comment_time) VALUES (?,?,?,?)";
        echo "<meta http-equiv='refresh' content='0'>";
        $stmt1 = $dbh->prepare($addComment);
        $params1 = [$ticketID, $userID, trim($comment), $commentTime];
        $success1 = $stmt1->execute($params1);

        //for ticket activity
        $selectNewComment = "SELECT comment_id FROM comments ORDER BY comment_id DESC LIMIT 1";
        $stmt = $dbh->prepare($selectNewComment);
        $success = $stmt->execute();
        if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
            $insertNewActivity = "INSERT INTO activities(ticket_id, user_id, prop_name, comment_id , activity_time) VALUES(?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($insertNewActivity);
            $params = [$ticketID, $loggedInUserId, "Comment", $row['comment_id'], date('Y-m-d H:i:s')];
            $success = $stmt->execute($params);
        }
        //end
    }
}
