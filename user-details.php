<?php
include "database/user-details-database.php";
$userDetailsArray = array(
    [
        ["users", "calendar", "bug"],
        []
    ],
    [
        ["user", "calendar", "suitcase"],
        []
    ]
);
for ($i = 0; $i < count($allAssignedProjects); $i++) {
    array_push($userDetailsArray[0][1], $allAssignedProjects[$i]);
}
for ($i = 0; $i < count($allAssignedTickets); $i++) {
    array_push($userDetailsArray[1][1], $allAssignedTickets[$i]);
}
?>
<div class="row pb-3">
    <div class="row">
        <div class="col sliding-effect"></div>
    </div>
    <div class="col">
        <button class="add-new btn btn-dark">Edit User</button>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row canvas-container also-in-user" data-user-id="<?= $id ?>" style="width:100%;">
            <div class="col-12 canvas-line">
                <canvas id="lineChart" width="100%" height="22%"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="row">
                <div class="col sliding-effect"></div>
            </div>
            <div class="col">
                <div class="row justify-content-center" id="lineLegend"></div>
            </div>
        </div>

        <div class="row pt-2 pb-4"></div>
        <div class="row">
            <div class="row pl-3">
                <div class="col sliding-effect"></div>
            </div>
            <?php for ($index = 0; $index < count($userDetailsArray); $index++) { ?>
                <div class="col pt-2 border border-secondary border-secondary rounded" style="height: 320px;">

                    <div class="col">
                        <div class="row">
                            <div class="col text-center">
                                <h2>Assigned <?php if ($index === 0) { ?>Projects<?php } else { ?>Tickets<?php } ?></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col" style="height: 260px; overflow-y: scroll;">
                                <?php for ($i = 0; $i < count($userDetailsArray[$index][1]); $i++) { ?>
                                    <div class="row border-top border-bottom pt-3 mt-3 mr-2 hover-background">
                                        <div class="col-2">
                                            <?php if ($index === 0) { ?>
                                                <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $userDetailsArray[$index][1][$i][0] ?></span>
                                            <?php } else { ?>
                                                <i class="fas fa-exclamation-circle fa-3x <?= $userDetailsArray[$index][1][$i][0] ?>"></i>
                                            <?php } ?>
                                        </div>

                                        <div class="col">
                                            <div class="row clickable">
                                                <h2 class="title text-dark"><a href="#" class="text-dark" style="text-decoration: none;"><?= $userDetailsArray[$index][1][$i][1] ?></a></h2>
                                            </div>
                                            <div class="row text-secondary clickable" style="font-size: 12px; ">
                                                <p class="totalMembers"><i class="fas fa-<?= $userDetailsArray[$index][0][0] ?>" style="font-size: 11px;"></i> <?= $userDetailsArray[$index][1][$i][2] ?></p>
                                                <span class="pl-2 pr-2">.</span>
                                                <p class="dateCreated"><i class="fas fa-<?= $userDetailsArray[$index][0][1] ?>" style="font-size: 11px;"></i> <?= $userDetailsArray[$index][1][$i][3] ?></p>
                                                <span class="pl-2 pr-2">.</span>
                                                <p class="totalTickets"><i class="fas fa-<?= $userDetailsArray[$index][0][2] ?>" style="font-size: 11px;"></i> <?= $userDetailsArray[$index][1][$i][4] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($index === 0) { ?>
                    <div class="row" style="width: 100px;"></div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
<script src="js/graphs/line-graph.js"></script>
<script src="js/user-details.js"></script>