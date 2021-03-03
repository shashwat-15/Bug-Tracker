<?php
include "database/project-details-database.php";
$projectDetailsArray = array(
    [
        ["Role", "Department", ["Admin", "Manager", "Employee"], ["Sales", "HR", "IT"],  "envelope"],
        []
    ],
    [
        ["Status", "Assigned To", ["Open", "On Hold", "Close"], [], "user", "calendar", "suitcase"],
        []
    ]
);
for ($i = 0; $i < count($allAssignedToMembers); $i++) {
    array_push($projectDetailsArray[1][0][3], $allAssignedToMembers[$i][0]);
}
for ($i = 0; $i < count($allAssignedUsers); $i++) {
    array_push($projectDetailsArray[0][1], $allAssignedUsers[$i]);
}
for ($i = 0; $i < count($allAssignedTickets); $i++) {
    array_push($projectDetailsArray[1][1], $allAssignedTickets[$i]);
}

?>
<link rel="stylesheet" href="css/modal.css">
<div class="row pb-3">
    <div class="row">
        <div class="col sliding-effect"></div>
    </div>
    <div class="col">
        <button class="add-new btn btn-dark">Edit Project</button>
    </div>
</div>
<?php for ($index = 0; $index < count($projectDetailsArray); $index++) { ?>
    <div class="row  pt-2 border border-secondary rounded" style="height: 350px;">
        <div class="row">
            <div class="col sliding-effect"></div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col text-center">
                    <h2>Assigned <?php if ($index === 0) { ?>Users<?php } else { ?>Tickets<?php } ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col"></div>
                <div class="col"></div>
                <div class="col" style="width:494.2px; position:absolute; right: 80px; top: 15px;">
                    <div class="row text-dark text-center pl-4">
                        <div class="col">
                            <h5 id="attr1"><?= $projectDetailsArray[$index][0][0] ?></h5>
                        </div>
                        <div class="col">
                            <h5 id="attr2"><?= $projectDetailsArray[$index][0][1] ?></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="height:70%;">
                <div class="col-1">
                    <button class="btn btn-primary add-btn" style="height:100%; width: 74px;">Add <?php if ($index === 0) { ?>User<?php } else { ?>Ticket<?php } ?></button>
                </div>
                <div class="col" style="height: 238px; overflow-y: scroll;">
                    <?php for ($i = 0; $i < count($projectDetailsArray[$index][1]); $i++) { ?>
                        <div class="row border-top border-bottom pt-3 mt-3 mr-2 hover-background" style="padding-right: 10px;">
                            <div class="col-1">
                                <?php if ($index === 0) { ?>
                                    <div style="height:50px; width:50px; border-radius:50%; overflow:hidden">
                                        <?php if (strlen($projectDetailsArray[$index][1][$i][0]) === 2) { ?>
                                            <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $projectDetailsArray[$index][1][$i][0] ?></span>
                                        <?php } else { ?>
                                            <image class="profile-img" src="uploads/profile-pictures/<?= $projectDetailsArray[$index][1][$i][0] ?>" style="width:50px; height:50px;"></image>
                                        <?php } ?>
                                    </div>
                                <?php  } else { ?>
                                    <i class="fas fa-exclamation-circle fa-3x <?= $projectDetailsArray[$index][1][$i][0] ?>"></i>
                                <?php } ?>
                            </div>
                            <div class="col-5">
                                <div class="row clickable">
                                    <h2 class="title text-dark"><a href="" class="text-dark" style="text-decoration: none;"><?= $projectDetailsArray[$index][1][$i][1] ?></a></h2>
                                </div>
                                <div class="row text-secondary clickable" style="font-size: 12px;">
                                    <p class="totalMembers"><i class="fas fa-<?= $projectDetailsArray[$index][0][4] ?>" style="font-size: 11px;"></i> <?= $projectDetailsArray[$index][1][$i][2] ?></p>
                                    <?php if ($index === 1) { ?>
                                        <span class="pl-2 pr-2">.</span>
                                        <p class="dateCreated"><i class="fas fa-<?= $projectDetailsArray[$index][0][5] ?>" style="font-size: 11px;"></i> <?= $projectDetailsArray[$index][1][$i][6] ?></p>
                                        <span class="pl-2 pr-2">.</span>
                                        <p class="totalTickets"><i class="fas fa-<?= $projectDetailsArray[$index][0][6] ?>" style="font-size: 11px;"></i> <?= $projectDetailsArray[$index][1][$i][7] ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col" style="width:494.2px; position:absolute; right: 80px;">
                                <div class="row pl-5">
                                    <div class="col select-div">
                                        <select class="firstSelect">
                                            <option value="hide"><?= $projectDetailsArray[$index][1][$i][3] ?></option>
                                            <?php for ($j = 0; $j < count($projectDetailsArray[$index][0][2]); $j++) { ?>
                                                <option value="<?= $projectDetailsArray[$index][0][2][$j] ?>"><?= $projectDetailsArray[$index][0][2][$j] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col select-div">
                                        <select class="secondSelect">
                                            <option value="hide"><?= $projectDetailsArray[$index][1][$i][4] ?></option>
                                            <?php for ($j = 0; $j < count($projectDetailsArray[$index][0][3]); $j++) { ?>
                                                <option value="<?= $projectDetailsArray[$index][0][3][$j] ?>"><?= $projectDetailsArray[$index][0][3][$j] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col pt-2 pl-5 text-dark delete" data-delete-item="<?= $id ?>" id="<?php if ($index === 0) { ?>delete-u-<?= $projectDetailsArray[$index][1][$i][5] ?> <?php } else if ($index === 1) { ?>delete-t-<?= $projectDetailsArray[$index][1][$i][5] ?> <?php } ?>" style="width: 112px; position:absolute; right: 0px; cursor:pointer">
                                <i class="fas fa-trash fa-lg"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($index === 0) { ?>
        <div class="row" style="height:50px;"></div>
    <?php } ?>
<?php } ?>

<!-- modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <span class="close">&times;</span>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <h5>All Users</h5>
                </div>
            </div>
            <div class="box-model pt-1">
                <div class="row" style="height: 15px;">
                    <div class="col input-icons">
                        <input class="form-check-input" type="checkbox" value="" style="left: 46px;" onclick="toggle(this)">
                        <i class="fas fa-search search-icon" style="right: 10px;"></i>
                        <input type="search" class="form-control project-search" name="search-bar" placeholder="Type to search...">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <hr class="change-color-on-focus">
                    </div>
                </div>
                <div class="form-check">
                    <?php for ($i = 0; $i < count($allNonTeamMembers); $i++) { ?>
                        <div class="row" style="padding-left: 33px;">
                            <input class="form-check-input project-checkbox" name="users[]" type="checkbox" value="<?= $allNonTeamMembers[$i] ?>" id="defaultCheckUser<?= $i ?>" style="left: 30px;">
                            <label class="form-check-label" for="defaultCheck<?= $i ?>">
                                <?= $allNonTeamMembers[$i] ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-4 ">
                <div class="col">
                    <button type="submit" name="add-user" class="btn btn-primary" style="float:right;">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- tightly coupled - can be improved -->
<div id="myModal2" class="modal">
    <div class="modal-content">
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <span class="close">&times;</span>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <h5>All Tickets</h5>
                </div>
            </div>
            <div class="box-model pt-1">
                <div class="row" style="height: 15px;">
                    <div class="col input-icons">
                        <input class="form-check-input" type="checkbox" value="" style="left: 46px;" onclick="toggle(this)">
                        <i class="fas fa-search search-icon" style="right: 10px;"></i>
                        <input type="search" class="form-control project-search" name="search-bar" id="project-search-bar" placeholder="Type to search...">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <hr class="change-color-on-focus">
                    </div>
                </div>
                <div class="form-check">
                    <?php for ($i = 0; $i < count($allNonAssignedTickets); $i++) { ?>
                        <div class="row" style="padding-left: 33px;">
                            <input class="form-check-input project-checkbox" name="tickets[]" type="checkbox" value="<?= $allNonAssignedTickets[$i] ?>" id="defaultCheckTicket<?= $i ?>" style="left: 30px;">
                            <label class="form-check-label" for="defaultCheck<?= $i ?>">
                                <?= $allNonAssignedTickets[$i] ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-4 ">
                <div class="col">
                    <button type="submit" class="btn btn-primary" name="add-ticket" style="float:right;">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="js/modal.js"></script>
<script src="js/new-template.js"></script>
<script src="js/project-details.js"></script>