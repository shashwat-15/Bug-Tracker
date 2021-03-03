<?php
$page = $_GET["page"];
$id = $_GET["id"];
$paginationIndex = $_GET["index"];
include "partials/header.php";
?>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/sliding-effect-button.css">
<link rel="stylesheet" href="css/combobox.css">
<link rel="stylesheet" href="css/details-template.css">

<section id="main-content">
    <div id="guts">
        <div class="not-container pl-5 pr-3">
            <?php
            if ($page === "projects-details") {
                include "project-details.php";
                $totalRecords = $totalProjects;
            } else if ($page === "tickets-details") {
                include "ticket-details.php";
                $totalRecords = $totalTickets;
            } else if ($page === "users-details") {
                include "user-details.php";
                $totalRecords = $totalUsers;
            }
            ?>
        </div>
    </div>
    <div class="new-form-container pr-5 pt-4">
        <?php include "new-template.php" ?>
    </div>
    <div class="row border-top fixed-bottom pl-4 pr-3 pt-3 move-left-on-btnClick" style="left: 370px; height: 71px">
        <div class="col text-secondary">
            <h5><?= $paginationIndex ?> of <?= $totalRecords ?></h5>
        </div>
        <div class="col" style="height: 71px; width:761.5px; position:absolute; right: -12px;">
            <div class="row justify-content-end">
                <div class="col-2" style="right: -45px;">
                    <button class="btn btn-dark">&laquo; Prev</button>
                </div>
                <div class="col-2">
                    <button class="btn btn-dark">Next &raquo;</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="js/sliding-effect-button.js"></script>
<script src="js/text-editor.js"></script>
<script src="js/combobox.js"></script>

<script src="js/ticket-details.js"></script>