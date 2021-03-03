<?php
include "connect.php";

$projectCount = 0;
$getProjectsCount = "SELECT COUNT(*) FROM projects";
$stmt = $dbh->prepare($getProjectsCount);
$success = $stmt->execute();

if ($success && $stmt->rowCount() === 1 && $row = $stmt->fetch()) {
    $projectCount = $row[0];
}

echo $projectCount;
