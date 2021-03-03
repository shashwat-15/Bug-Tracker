<link rel="stylesheet" href="css/bootstrap-datepicker.css">
<link rel="stylesheet" href="css/new-template-combobox.css">
<link rel="stylesheet" href="css/new-template.css">

<?php
include "database/new-template-database.php";
if ($page === "projects-details" || $page === "tickets-details" || $page === "users-details") {
    include "database/edit-template-database.php";
}
$title = ["Project Title", "Ticket Name"];
$description = ["Project Description", "Ticket Details"];
$firstSelectLabel = ["Team", "Projects"];
$firstSelectOptionsArray = [[], []];
for ($i = 0; $i < count($teams); $i++) {
    array_push($firstSelectOptionsArray[0], $teams[$i]);
}
for ($i = 0; $i < count($allTheProjects); $i++) {
    array_push($firstSelectOptionsArray[1], $allTheProjects[$i]);
}
$secondSelectLabel = ["Owner", "Status"];
$secondSelectOptionsArray = [[], ["Open", "On Hold", "Closed"]];
if ($page === "projects-details") {
    for ($i = 0; $i < count($teamMembers); $i++) {
        array_push($secondSelectOptionsArray[0], $teamMembers[$i]);
    }
}
$thirdDateLabel = "Start Date";
$thirdSelectLabel = "Assigned To";
$thirdSelectOptionsArray = [];
if ($page === "tickets-details") {
    for ($i = 0; $i < count($assignedToTeamMembers); $i++) {
        $thirdSelectOptionsArray[] = $assignedToTeamMembers[$i];
    }
}
$fourthDateLabel = ["End Date", "Due Date"];
$fifthSelectLabel = "Priority";
$fifthSelectOptionsArray = ["High", "Low", "Medium", "None"];
$sixthSelectLabel = "Type";
$sixthSelectOptionsArray = ["Bug/Error", "Feature", "Question"];
$roleSelectOptionsArray = [];
for ($i = 0; $i < count($roles); $i++) {
    array_push($roleSelectOptionsArray, $roles[$i]);
}
$departmentSelectOptionsArray = [];
for ($i = 0; $i < count($departments); $i++) {
    array_push($departmentSelectOptionsArray, $departments[$i]);
}

if ($page === "projects" || $page === "projects-details") {
    $index = 0;
} else if ($page === "tickets" || $page === "tickets-details") {
    $index = 1;
}
?>

<form action="" method="post" enctype="multipart/form-data" class="new-form pl-5 pt-5">
    <?php if ($page === "projects" || $page === "tickets" || $page === "tickets-details" || $page === "projects-details") { ?>
        <div class="row">
            <div class="col form-group">
                <label class="text-danger" for="new-title"><?= $title[$index] ?></label>
                <input type="text" class="form-control" name="new-title-<?= $index ?>" value="<?php if ($page === "projects-details") {
                                                                                                    echo $projectInfo[0];
                                                                                                } else if ($page === "tickets-details") {
                                                                                                    echo $ticketInfo[0];
                                                                                                } ?>">
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col form-group">
                <label class="text-dark" for="new-description"><?= $description[$index] ?></label>
                <textarea class="form-control" name="new-description-<?= $index ?>" id="editor" rows="3">
                <?php if ($page === "projects-details") {
                    echo $projectInfo[1];
                } else if ($page === "tickets-details") {
                    echo $ticketInfo[1];
                } ?>
                </textarea>
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col">
                <hr>
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <?php if ($index === 0) { ?>
                <div class="col form-group">
                    <div class="row">
                        <div class="col">
                            <label class="text-dark" for=""><?= $firstSelectLabel[$index] ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col new-select-div">
                            <select class="new-select" name="<?php if ($index === 0) { ?>team<?php } else if ($index === 1) { ?>project<?php } ?>">
                                <?php if ($page === "projects-details") { ?>
                                    <option value="hide"><?= $projectInfo[2] ?></option>
                                <?php } else { ?>
                                    <option value="hide">-- Select <?php if ($index === 0) { ?>Team<?php } else if ($index === 1) { ?>Project<?php } ?> --</option>
                                <?php } ?>
                                <?php for ($i = 0; $i < count($firstSelectOptionsArray[$index]); $i++) { ?>
                                    <option value="<?= $firstSelectOptionsArray[$index][$i] ?>"><?= $firstSelectOptionsArray[$index][$i] ?></option>
                                <?php } ?>
                            </select>
                            <?php if ($page === "projects-details") { ?>
                                <input type="hidden" name="default-team" value="<?= $projectInfo[2] ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } else if ($index === 1) { ?>
                <div class="col form-group">
                    <div class="row">
                        <div class="col">
                            <label class="text-dark" for=""><?= $firstSelectLabel[$index] ?></label>
                        </div>
                    </div>
                    <div class="box-model pt-1" style="height:138px;">
                        <div class="row" style="height: 15px;">
                            <div class="col input-icons for-all-ticket">
                                <input class="form-check-input all-checkbox" type="checkbox" value="" style="left: 46px;" onclick="toggle(this)">
                                <i class="fas fa-search search-icon " style="right: 2px;"></i>
                                <input type="search" class="form-control project-search pr-0" name="search-bar" placeholder="Type to search..." style="width:155px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <hr class="change-color-on-focus">
                            </div>
                        </div>
                        <?php $allProjects = [];
                        for ($i = 0; $i < count($allTheProjects); $i++) {
                            array_push($allProjects, $allTheProjects[$i]);
                        }
                        ?>
                        <div class="form-check for-ticket" style="height:78px; overflow-y:scroll;">
                            <?php for ($i = 0; $i < count($allProjects); $i++) { ?>
                                <div class="row" style="padding-left: 33px; width:90%;">
                                    <?php if ($page === "tickets-details" && count($ticketInfo[2]) > 0) { ?>
                                        <?php for ($j = 0; $j < count($ticketInfo[2]); $j++) {
                                            if ($ticketInfo[2][$j] === $allProjects[$i]) { ?>
                                                <input class="form-check-input project-checkbox" type="checkbox" checked name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                                            <?php break;
                                            } else { ?>
                                                <input class="form-check-input project-checkbox" type="checkbox" name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                                        <?php
                                            }
                                        }
                                    } else { ?>
                                        <input class="form-check-input project-checkbox" type="checkbox" name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                                    <?php } ?>
                                    <label class="form-check-label" for="defaultCheck<?= $i ?>">
                                        <?= $allProjects[$i] ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="col-1"></div>
            <div class="col form-group">
                <div class="row">
                    <div class="col">
                        <label class="text-dark" for=""><?= $secondSelectLabel[$index] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col new-select-div" id="<?php if ($index === 0) { ?>owner<?php } else if ($index === 1) { ?>status<?php } ?>">
                        <select class="new-select" name="<?php if ($index === 0) { ?>owner<?php } else if ($index === 1) { ?>status<?php } ?>">
                            <?php if ($page === "projects-details") { ?>
                                <option value="hide"><?= $projectInfo[3] ?></option>
                            <?php } else if ($page === "tickets-details") { ?>
                                <option value="hide"><?= $ticketInfo[3] ?></option>
                            <?php } else { ?>
                                <option value="hide">-- Select <?php if ($index === 0) { ?>Owner<?php } else if ($index === 1) { ?>Status<?php } ?> --</option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($secondSelectOptionsArray[$index]); $i++) { ?>
                                <option value="<?= $secondSelectOptionsArray[$index][$i] ?>"><?= $secondSelectOptionsArray[$index][$i] ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($page === "projects-details") { ?>
                            <input type="hidden" name="default-owner" value="<?= $projectInfo[3] ?>">
                        <?php } else if ($page === "tickets-details") { ?>
                            <input type="hidden" name="default-status" value="<?= $ticketInfo[3] ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col form-group date-picker-div">
                <?php if ($page === "projects" || $page === "projects-details") { ?>
                    <label class="text-dark" for="new-startDate"><?= $thirdDateLabel ?></label>
                    <i class="far fa-calendar-alt icon2"></i>
                    <input class="datepicker form-control text-dark input-field" autocomplete="off" data-date-format="yyyy-mm-dd" name="start-date" value="<?php if ($page === "projects-details") {
                                                                                                                                                                echo $projectInfo[4];
                                                                                                                                                            } ?>">
                <?php } else if ($page === "tickets" || $page === "tickets-details") { ?>
                    <div class="row">
                        <div class="col">
                            <label class="text-dark" for="new-status"><?= $thirdSelectLabel ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col new-select-div" id="assigned-to">
                            <select class="new-select" name="assigned-to">
                                <?php if ($page === "tickets-details") { ?>
                                    <option value="hide"><?= $ticketInfo[4] ?></option>
                                <?php } else { ?>
                                    <option value="hide">-- Select Agent --</option>
                                <?php } ?>
                                <?php for ($i = 0; $i < count($thirdSelectOptionsArray); $i++) { ?>
                                    <option value="<?= $thirdSelectOptionsArray[$i] ?>"><?= $thirdSelectOptionsArray[$i] ?></option>
                                <?php } ?>
                            </select>
                            <?php if ($page === "tickets-details") { ?>
                                <input type="hidden" name="default-agent" value="<?= $ticketInfo[4] ?>">
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-1"></div>
            <div class="col form-group date-picker-div">
                <label class="text-dark" for="new-endDate"><?= $fourthDateLabel[$index] ?></label>
                <i class="far fa-calendar-alt icon2"></i>
                <input class="datepicker form-control input-field" autocomplete="off" data-date-format="yyyy-mm-dd" name="end-date-<?= $index ?>" value="<?php if ($page === "projects-details") {
                                                                                                                                                                echo $projectInfo[5];
                                                                                                                                                            } else if ($page === "tickets-details") {
                                                                                                                                                                echo $ticketInfo[5];
                                                                                                                                                            } ?>">
            </div>
        </div>
        <?php if ($page === "projects" || $page === "projects-details") { ?>
            <div class="row" style="height:30px;"></div>
        <?php } else if ($page === "tickets" || $page === "tickets-details") { ?>
            <div class="row" style="height:20px;"></div>
            <div class="row">
                <div class="col form-group">
                    <div class="row">
                        <div class="col">
                            <label class="text-dark" for="new-owner"><?= $fifthSelectLabel ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col new-select-div">
                            <select class="new-select" name="priority">
                                <?php if ($page === "tickets-details") { ?>
                                    <option value="hide"><?= $ticketInfo[6] ?></option>
                                <?php } else { ?>
                                    <option value="hide">-- Select Priority --</option>
                                <?php } ?>
                                <?php for ($i = 0; $i < count($fifthSelectOptionsArray); $i++) { ?>
                                    <option value="<?= $fifthSelectOptionsArray[$i] ?>"><?= $fifthSelectOptionsArray[$i] ?></option>
                                <?php } ?>
                            </select>
                            <?php if ($page === "tickets-details") { ?>
                                <input type="hidden" name="default-priority" value="<?= $ticketInfo[6] ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
                <div class="col form-group">
                    <div class="row">
                        <div class="col">
                            <label class="text-dark" for="new-status"><?= $sixthSelectLabel ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col new-select-div">
                            <select class="new-select" name="type">
                                <?php if ($page === "tickets-details") { ?>
                                    <option value="hide"><?= $ticketInfo[7] ?></option>
                                <?php } else { ?>
                                    <option value="hide">-- Select Type --</option>
                                <?php } ?>
                                <?php for ($i = 0; $i < count($sixthSelectOptionsArray); $i++) { ?>
                                    <option value="<?= $sixthSelectOptionsArray[$i] ?>"><?= $sixthSelectOptionsArray[$i] ?></option>
                                <?php } ?>
                            </select>
                            <?php if ($page === "tickets-details") { ?>
                                <input type="hidden" name="default-type" value="<?= $ticketInfo[7] ?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="height: 30px;"></div>
        <?php } ?>
        <div class="row pb-3">
            <div class="col-2">
                <button type="submit" class="btn btn-primary" name="<?php if ($page === "projects") { ?>project-submit<?php } else if ($page === "tickets") { ?>ticket-submit<?php } else if ($page === "projects-details") { ?>project-update<?php } else if ($page === "tickets-details") { ?>ticket-update<?php } ?>">
                    <?php if ($page === "projects" || $page === "tickets") { ?>Create<?php } else { ?>Update<?php } ?>
                </button>
            </div>
            <div class="col">
                <button class="btn btn-secondary cancel-form" name="cancel-form-<?= $index ?>">Cancel</button>
            </div>
        </div>
    <?php } ?>
    <?php if ($page === "users" || $page === "users-details") { ?>
        <div class="row">
            <div id="profile-container">
                <?php if ($page === "users-details" && strlen($userInfo[0]) === 2) { ?>
                    <span class="text-dark text-center dot pt-4 not-image" style="padding-top: 12px; width:100px; height:100px; font-size:28px; cursor:pointer;">
                        <?= $userInfo[0] ?>
                    </span>
                    <image id="profile-image" src="" style="display:none" ;></image>
                <?php } else { ?>
                    <image id="profile-image" src="<?php if ($page === "users") { ?>https://www.computerhope.com/jargon/g/guest-user.jpg<?php } else { ?>uploads/profile-pictures/<?php echo $userInfo[0];
                                                                                                                                                                                } ?>" />
                <?php } ?>
            </div>
            <input id="image-upload" type="file" name="profile-photo" placeholder="Photo">
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col form-group">
                <label class="text-danger" for="new-first-name">First Name</label>
                <input type="text" class="form-control" name="new-first-name" value="<?php if ($page === "users-details") {
                                                                                            echo $userInfo[1];
                                                                                        } ?>">
            </div>
            <div class="col form-group">
                <label class="text-danger" for="new-last-name">Last Name</label>
                <input type="text" class="form-control" name="new-last-name" value="<?php if ($page === "users-details") {
                                                                                        echo $userInfo[2];
                                                                                    } ?>">
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col form-group">
                <label class="text-danger" for="new-email">Email</label>
                <input type="email" class="form-control" name="new-email" value="<?php if ($page === "users-details") {
                                                                                        echo $userInfo[3];
                                                                                    } ?>">
            </div>
            <div class="col form-group">
                <label class="text-dark" for="new-phone">Phone</label>
                <input type="text" class="form-control" name="new-phone" value="<?php if ($page === "users-details") {
                                                                                    echo $userInfo[4];
                                                                                } ?>">
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row">
            <div class="col form-group">
                <div class="row">
                    <div class="col">
                        <label class="text-dark" for="new-role">Role</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col new-select-div">
                        <select class="new-select role-select" name="role">
                            <?php if ($page === "users") { ?>
                                <option value="hide">-- Select Role --</option>
                            <?php } else { ?>
                                <option value="hide"><?= $userInfo[5] ?></option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($roleSelectOptionsArray); $i++) { ?>
                                <option value="<?= $roleSelectOptionsArray[$i] ?>"><?= $roleSelectOptionsArray[$i] ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($page === "users-details") { ?>
                            <input type="hidden" name="default-role" value="<?= $userInfo[5] ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col form-group">
                <div class="row">
                    <div class="col">
                        <label class="text-dark" for="new-status">Department</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col new-select-div">
                        <select class="new-select" name="department">
                            <?php if ($page === "users") { ?>
                                <option value="hide">-- Select Department --</option>
                            <?php } else { ?>
                                <option value="hide"><?= $userInfo[6] ?></option>
                            <?php } ?>
                            <?php for ($i = 0; $i < count($departmentSelectOptionsArray); $i++) { ?>
                                <option value="<?= $departmentSelectOptionsArray[$i] ?>"><?= $departmentSelectOptionsArray[$i] ?></option>
                            <?php } ?>
                        </select>
                        <?php if ($page === "users-details") { ?>
                            <input type="hidden" name="default-department" value="<?= $userInfo[6] ?>">
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="height:20px;"></div>
        <div class="row hide-for-admin" style="display:none;">
            <div class="col">
                <h6>All Projects</h6>
            </div>
        </div>
        <div class="row hide-for-admin" style="height:10px; display:none;"></div>
        <div class="box-model pt-1 hide-for-admin" style="display:none;">
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
            <?php $allProjects = [];
            for ($i = 0; $i < count($allTheProjects); $i++) {
                array_push($allProjects, $allTheProjects[$i]);
            }
            ?>
            <div class="form-check">
                <?php for ($i = 0; $i < count($allProjects); $i++) { ?>
                    <div class="row" style="padding-left: 33px;">
                        <?php if ($page === "users-details" && count($userInfo[7]) > 0) { ?>
                            <?php for ($j = 0; $j < count($userInfo[7]); $j++) {
                                if ($userInfo[7][$j] === $allProjects[$i]) { ?>
                                    <input class="form-check-input project-checkbox" type="checkbox" checked name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                                <?php break;
                                } else { ?>
                                    <input class="form-check-input project-checkbox" type="checkbox" name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                            <?php
                                }
                            }
                        } else { ?>
                            <input class="form-check-input project-checkbox" type="checkbox" name="projects[]" value=" <?= $allProjects[$i] ?>" style="left: 30px;">
                        <?php } ?>
                        <label class="form-check-label" for="defaultCheck<?= $i ?>">
                            <?= $allProjects[$i] ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row change-on-role" style="height:30px;"></div>
        <div class="row pb-3">
            <div class="col-2">
                <button type="submit" name="<?php if ($page === "users") { ?>user-submit<?php } else { ?>user-update<?php } ?>" class="btn btn-primary"><?php if ($page === "users") { ?>Create<?php } else { ?>Update<?php } ?></button>
            </div>
            <div class="col">
                <button class="btn btn-secondary cancel-form">Cancel</button>
            </div>
        </div>
    <?php } ?>
</form>
<script src="https://cdn.tiny.cloud/1/sc9d2yvo53x7o4v3r01ld9uvs91jru96w8erwbkkt9g1bgyb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/datepicker-select.js"></script>
<script src="js/text-editor.js"></script>
<script src="js/new-template.js"></script>