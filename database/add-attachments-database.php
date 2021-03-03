<?php
include "connect.php";
date_default_timezone_set('America/Toronto');

$loggedInUserId = 1; //this will be the id of logged in user
$ticketID = $_GET['ticket_id'];

if (!empty($_FILES['attachment']['name'])) {

    $target_dir = "../uploads/ticket-attachments/";
    $target_file = $target_dir . basename($_FILES["attachment"]["name"]);
    $uploadOk = 1;

    // Select file type
    $attachmentFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $fileSize = filesize($_FILES["attachment"]["tmp_name"]);
    if ($fileSize) {
        $size = round($fileSize / 1024, 1);
        $uploadOk = 1;
    } else {
        echo "Not a valid file";
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
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "pdf", "doc", "docx");


    // Check extension
    if (!in_array($attachmentFileType, $extensions_arr)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, DOC & DOCX files are allowed.";
        $uploadOk = 0;
    } else {
        if ($attachmentFileType == "doc" || $attachmentFileType == "docx") {
            $fileType = "word";
        } else if ($attachmentFileType == "jpg" || $attachmentFileType == "jpeg" || $attachmentFileType == "png" || $attachmentFileType == "gif") {
            $fileType = "image";
        } else {
            $fileType = $attachmentFileType;
        }
    }
    if ($uploadOk == 0) {
        echo "Sorry, your attachment was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $userID = 1; //this will be the user id of logged in user
            $attachmentName =  htmlspecialchars(basename($_FILES["attachment"]["name"]));
            $attachmentTime = date("Y-m-d H:i:s");

            // Insert record
            $createNewAttachment = "INSERT INTO attachments(ticket_id, user_id, attachment_type, attachment_name, attachment_size, attachment_time) VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($createNewAttachment);
            $params = [$ticketID, $userID, $fileType, $attachmentName, $size, $attachmentTime];
            $success = $stmt->execute($params);

            //for ticket activity
            $selectNewAttachment = "SELECT attachment_id FROM attachments ORDER BY attachment_id DESC LIMIT 1";
            $stmt = $dbh->prepare($selectNewAttachment);
            $success = $stmt->execute();
            if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
                $insertNewActivity = "INSERT INTO activities(ticket_id, user_id, prop_name, attachment_id , activity_time) VALUES(?, ?, ?, ?, ?)";
                $stmt = $dbh->prepare($insertNewActivity);
                $params = [$ticketID, $loggedInUserId, "Attachment", $row['attachment_id'], date('Y-m-d H:i:s')];
                $success = $stmt->execute($params);
            }
            //end
        } else {
            echo "Sorry, there was an error uploading your profile picture.";
        }
    }
}
