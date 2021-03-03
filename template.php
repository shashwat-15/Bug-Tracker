<link rel="stylesheet" href="css/combobox.css">
<link rel="stylesheet" href="css/sliding-effect-button.css">
<link rel="stylesheet" href="css/template.css">
<?php
include "database/template-database.php";
$buttonText = ["Create New Project", "Create New Ticket", "Create New User"];
$col1Text = ["Team", "Status", "Role"];
$col2Text = ["Owner", "Assigned To", "Department"];
$idsArray = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($idsArray[0], $allProjectsInfo[$i][7]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($idsArray[1], $allTicketsInfo[$i][7]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($idsArray[2], $allUsersInfo[$i][5]);
}
$numberTextArray = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($numberTextArray[0], $allProjectsInfo[$i][0]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($numberTextArray[1], $allTicketsInfo[$i][0]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($numberTextArray[2], $allUsersInfo[$i][0]);
}
$titleTextArray = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($titleTextArray[0], $allProjectsInfo[$i][1]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($titleTextArray[1], $allTicketsInfo[$i][1]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($titleTextArray[2], $allUsersInfo[$i][1]);
}
$firstSubTextArray = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($firstSubTextArray[0], $allProjectsInfo[$i][4]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($firstSubTextArray[1], $allTicketsInfo[$i][4]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($firstSubTextArray[2], $allUsersInfo[$i][4]);
}
$firstSubIcon = ["users", "user", "envelope"];
$secondSubTextArray = [[], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($secondSubTextArray[0], $allProjectsInfo[$i][5]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($secondSubTextArray[1], $allTicketsInfo[$i][5]);
}
$secondSubIcon = ["calendar", "calendar"];
$thirdSubTextArray = [[], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($thirdSubTextArray[0], $allProjectsInfo[$i][6]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($thirdSubTextArray[1], $allTicketsInfo[$i][6]);
}
$thirdSubIcon = ["bug", "suitcase"];
$firstColumnSelectOptionsArray = [[], ["Open", "On Hold", "Close"], ["Admin", "Manager", "Employee"]];
for ($i = 0; $i < count($selectTeams); $i++) {
    array_push($firstColumnSelectOptionsArray[0], $selectTeams[$i]);
}
$secondColumnSelectOptionsArray = [[], [], ["Sales", "HR", "IT"]];
for ($i = 0; $i < count($allProjectsTeamMembers); $i++) {
    for ($j = 0; $j < count($allProjectsTeamMembers[$i]); $j++) {
        $secondColumnSelectOptionsArray[0][$i][] =  $allProjectsTeamMembers[$i][$j];
    }
}
for ($i = 0; $i < count($allTicketProjectTeamMembers); $i++) {
    for ($j = 0; $j < count($allTicketProjectTeamMembers[$i]); $j++) {
        $secondColumnSelectOptionsArray[1][$i][] =  $allTicketProjectTeamMembers[$i][$j];
    }
}
$firstColumnSelectDisplayValue = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($firstColumnSelectDisplayValue[0], $allProjectsInfo[$i][2]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($firstColumnSelectDisplayValue[1], $allTicketsInfo[$i][2]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($firstColumnSelectDisplayValue[2], $allUsersInfo[$i][2]);
}
$secondColumnSelectDisplayValue = [[], [], []];
for ($i = 0; $i < count($allProjectsInfo); $i++) {
    array_push($secondColumnSelectDisplayValue[0], $allProjectsInfo[$i][3]);
}
for ($i = 0; $i < count($allTicketsInfo); $i++) {
    array_push($secondColumnSelectDisplayValue[1], $allTicketsInfo[$i][3]);
}
for ($i = 0; $i < count($allUsersInfo); $i++) {
    array_push($secondColumnSelectDisplayValue[2], $allUsersInfo[$i][3]);
}

$paginationTotalRecords = [6, 11, 16];
$paginationTotalRecords[0] = $totalProjectsCount;
$paginationTotalRecords[1] = $totalTicketsCount;
$paginationTotalRecords[2] = $totalUsersCount;
$maxRecordsonPage = 6;

if ($page === "projects") {
    $index = 0;
} else if ($page === "tickets") {
    $index = 1;
} else if ($page === "users") {
    $index = 2;
}
?>

<div class="not-container pl-5 pr-3">
    <div class="row">
        <div class="row">
            <div class="col sliding-effect"></div>
        </div>
        <div class="col">
            <button class="add-new btn btn-dark"><?= $buttonText[$index] ?></button>
        </div>
    </div>
    <div class="row">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col" style="width:494.2px; position:absolute; right: 80px; top: 15px;">
            <div class="row text-dark text-center pl-4">
                <div class="col">
                    <h5 id="attr1"><?= $col1Text[$index] ?></h5>
                </div>
                <div class="col">
                    <h5 id="attr2"><?= $col2Text[$index] ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="height:35px;"></div>
    <?php for ($i = 0; $i < count($numberTextArray[$index]); $i++) { ?>
        <div class="row border-top border-bottom pt-3 mt-3 hover-background">
            <div class="row">
                <div class="col sliding-effect"></div>
            </div>
            <div class="col-1">
                <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $numberTextArray[$index][$i] ?></span>
            </div>
            <div class="col-5">
                <div class="row clickable">
                    <h2 class="title text-dark"><a href="details-template.php?page=<?= $page ?>-details&id=<?= $idsArray[$index][$i] ?>&index=<?= $i + 1 ?>" class="text-dark titles" style="text-decoration: none;"><?= $titleTextArray[$index][$i] ?></a></h2>
                </div>
                <div class="row text-secondary clickable" style="font-size: 12px;">
                    <p class="totalMembers"><i class="fas fa-<?= $firstSubIcon[$index] ?>" style="font-size: 11px;"></i> <?= $firstSubTextArray[$index][$i] ?></p>
                    <?php if ($page !== "users") { ?>
                        <span class="pl-2 pr-2">.</span>
                        <p class="dateCreated"><i class="fas fa-<?= $secondSubIcon[$index] ?>" style="font-size: 11px;"></i> <?= $secondSubTextArray[$index][$i] ?></p>
                        <span class="pl-2 pr-2">.</span>
                        <p class="totalTickets"><i class="fas fa-<?= $thirdSubIcon[$index] ?>" style="font-size: 11px;"></i> <?= $thirdSubTextArray[$index][$i] ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col" style="width:494.2px; position:absolute; right: 80px">
                <div class="row pl-5">
                    <div class="col select-div">
                        <select class="firstSelect <?php if ($index === 0) { ?>dont-repeat<?php } ?>" name="<?php if ($index === 0) { ?>assigned-team-<?= $i ?><?php } else if ($index === 1) { ?>assigned-status<?php } else if ($index === 2) { ?>assigned-role<?php } ?>">
                            <option value="hide"><?= $firstColumnSelectDisplayValue[$index][$i] ?></option>
                            <?php for ($j = 0; $j < count($firstColumnSelectOptionsArray[$index]); $j++) { ?>
                                <option value="<?= $firstColumnSelectOptionsArray[$index][$j] ?>"><?= $firstColumnSelectOptionsArray[$index][$j] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col select-div" id="<?php if ($index === 0) { ?>assigned-owner-<?= $i ?><?php } else if ($index === 1) { ?>assigned-assignedTo<?php } else if ($index === 2) { ?>assigned-department<?php } ?>">
                        <select class="secondSelect">
                            <option value="hide"><?= $secondColumnSelectDisplayValue[$index][$i] ?></option>
                            <?php if ($index === 0 || $index === 1) {
                                for ($j = 0; $j < count($secondColumnSelectOptionsArray[$index][$i]); $j++) { ?>
                                    <option value="<?= $secondColumnSelectOptionsArray[$index][$i][$j] ?>"><?= $secondColumnSelectOptionsArray[$index][$i][$j] ?></option>
                                <?php }
                            } else if ($index === 2) {
                                for ($j = 0; $j < count($secondColumnSelectOptionsArray[$index]); $j++) { ?>
                                    <option value="<?= $secondColumnSelectOptionsArray[$index][$j] ?>"><?= $secondColumnSelectOptionsArray[$index][$j] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col pt-2 pl-5 text-dark template-delete" style="width: 112px; position:absolute; right: 0px; display:none">
                <i class="fas fa-trash fa-lg"></i>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row border-top fixed-bottom pl-5 pr-3 pt-3 move-left-on-btnClick" style="left: 370px; height: 71px">
    <div class="col text-secondary">
        <h5>1 - <?php if ($paginationTotalRecords[$index] < $maxRecordsonPage) {
                    echo $paginationTotalRecords[$index];
                } else {
                    echo $maxRecordsonPage;
                } ?> of <?= $paginationTotalRecords[$index] ?></h5>
    </div>
    <div class="col pagination-container" style="height: 71px; width:761.5px; position:absolute; right: 10px;">
        <ul class="pagination justify-content-end">
            <li class="page-item prev">
                <a class="page-link text-dark disabled" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item page-number active" tabindex="1"><a class="page-link" href="#">1</a></li>
            <?php for ($i = 2; $i <= ceil((int)$paginationTotalRecords[$index] / $maxRecordsonPage); $i++) { ?>
                <li class="page-item page-number" tabindex="<?= $i ?>"><a class="page-link text-dark" href="#"><?= $i ?></a></li>
            <?php } ?>
            <li class="page-item next">
                <a class="page-link text-dark" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/sliding-effect-button.js"></script>
<script src="js/template.js"></script>