<link rel="stylesheet" href="css/attachments.css">
<?php include "database/ticket-history-database.php";
$propertyNames = ["Projects", "Assigned To", "Due Date", "Priority", "Status", "Type", "Ticket Description", "Ticket Name", "Comment", "Attachment"];
$icons = ["suitcase", "user", "calendar-alt", "exclamation", "wifi", "list", "th", "tag", "comment", "paperclip"];
?>
<div class="row" style="width:100%;">
    <div class="col" style="height: 600px; overflow-y:scroll;">
        <?php for ($i = 0; $i < count($allActivitiesInfo); $i++) {
            for ($j = 0; $j < count($propertyNames); $j++) {
                if ($allActivitiesInfo[$i][0] === $propertyNames[$j]) {
                    $properyIcon = $icons[$j];
                    break;
                }
            }
        ?>
            <div class="row" style="padding-left:13px;">
                <div class="row">
                    <div class="col sliding-effect"></div>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="pl-3 pt-1" style="width:70px;"><span class="text-center dot" style="padding-top: 12px;"><i class="fas fa-<?= $properyIcon ?> fa-lg"></i></span></div>
                        <div style="padding-top:12px;"><span style="color: rgb(208,208,208);">. . . . .</span></div>
                        <div class="col pl-4">
                            <div class="row pb-2 text-secondary" style="font-size: 12px;"><?= $allActivitiesInfo[$i][3] ?></div>
                            <?php if ($allActivitiesInfo[$i][0] === "Comment" || $allActivitiesInfo[$i][0] === "Attachment") { ?>
                                <div class="row text-dark"><span style="margin-top:-10px"><?= $allActivitiesInfo[$i][4] ?> added <?= $allActivitiesInfo[$i][0] ?></span></div>
                                <div class="row text-secondary" style="font-size: 12px;"><?= $allActivitiesInfo[$i][5] ?></div>
                            <?php } else { ?>
                                <div class="row text-dark"><span style="margin-top:-10px"><?= $allActivitiesInfo[$i][4] ?> updated the ticket</span></div>
                                <div class="row text-secondary" style="font-size: 12px;"><strong><?= $allActivitiesInfo[$i][0] ?></strong>&nbsp changed from &nbsp<strong><?= $allActivitiesInfo[$i][1] ?></strong>&nbsp to &nbsp<strong><?= $allActivitiesInfo[$i][2] ?></strong></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row" style="width: 100%; padding-left: 39px;">
                    <div class="row">
                        <div class="col sliding-effect"></div>
                    </div>
                    <div class="col">
                        <div class="vl" style="border-left: 1px solid rgb(208,208,208); height: 50px;"></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="js/sliding-effect-button.js"></script>