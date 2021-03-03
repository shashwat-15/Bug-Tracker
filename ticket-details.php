<?php
include "database/ticket-details-database.php";

?>
<link rel="stylesheet" href="css/ticket-details.css">
<div class="row">
    <div class="row">
        <div class="col sliding-effect"></div>
    </div>
    <div class="col">
        <button class="add-new btn btn-dark">Edit Ticket</button>
    </div>
    <div class="col">
        <div class="row justify-content-end">
            <button class="add-new-comment btn btn-dark">Add Comment</button>
        </div>
    </div>
</div>
<div class="row pt-3 mt-3">
    <div class="row">
        <div class="col sliding-effect"></div>
    </div>
    <div class="col-1">
        <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $ticketInfo[0] ?></span>
    </div>
    <div class="col-5">
        <div class="row">
            <h2 class="title text-dark"><?= $ticketInfo[1] ?></h2>
        </div>
        <div class="row text-secondary" style="font-size: 12px;">
            <p class="totalMembers"><i class="fas fa-user" style="font-size: 11px;"></i> <?= $ticketInfo[3] ?></p>
            <span class="pl-2 pr-2">.</span>
            <p class="dateCreated"><i class="fas fa-calendar" style="font-size: 11px;"></i> <?= $ticketInfo[4] ?></p>
            <span class="pl-2 pr-2">.</span>
            <p class="totalTickets"><i class="fas fa-suitcase" style="font-size: 11px;"></i> <?= $ticketInfo[5] ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="row">
        <div class="col sliding-effect"></div>
    </div>
    <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="details-template.php?page=tickets-details&id=<?= $id ?>&index=<?= $paginationIndex ?>"><?= $totalComments ?> Conversation <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item attachments">
                        <a class="nav-link" href="#"><?= $totalAttachments ?> Attachments</a> <span id="attachments-ticket-id" data-ticket-id="<?= $id ?>" style="display:none;"></span>
                    </li>
                    <li class="nav-item history">
                        <a class="nav-link " href="#">Activity</a> <span id="activity-ticket-id" data-ticket-id="<?= $id ?>" style="display:none;"></span>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<?php if (strlen($ticketInfo[6]) !== 0) { ?>
    <div class="row pt-3 ticket-info">
        <div class="row">
            <div class="col sliding-effect"></div>
        </div>

        <div class="col-1">
            <div style="height:50px; width:50px; border-radius:50%; overflow:hidden">
                <?php if (strlen($ticketInfo[7]) === 0) { ?>
                    <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $ticketInfo[2] ?></span>
                <?php } else { ?>
                    <image class="profile-img" src="uploads/profile-pictures/<?= $ticketInfo[7] ?>" style="width:50px; height:50px;"></image>
                <?php } ?>
            </div>
        </div>
        <div class="col text-dark pl-0">
            <h6><strong><?= $ticketInfo[3] ?></strong><span class="text-secondary pl-2"><?= $ticketInfo[4] ?> (<?= $ticketInfo[8] ?>)</span></h6>
            <p><?= $ticketInfo[6] ?></p>
        </div>
    </div>
<?php } ?>
<div class="row comments-container">
    <div class="col" style="height: 238px; overflow-y: scroll;">
        <?php for ($i = 0; $i < count($allCommentsInfo); $i++) { ?>
            <div class="row pt-3 ticket-info hover-background">
                <div class="row">
                    <div class="col sliding-effect"></div>
                </div>

                <div class="col-1">
                    <div style="height:50px; width:50px; border-radius:50%; overflow:hidden">
                        <?php if (strlen($allCommentsInfo[$i][0]) === 2) { ?>
                            <span class="text-dark text-center dot" style="padding-top: 12px;"><?= $allCommentsInfo[$i][0] ?></span>
                        <?php } else { ?>
                            <image class="profile-img" src="uploads/profile-pictures/<?= $allCommentsInfo[$i][0] ?>" style="width:50px; height:50px;"></image>
                        <?php } ?>
                    </div>
                </div>
                <div class="col text-dark pl-0">
                    <h6><strong><?= $allCommentsInfo[$i][1] ?></strong><span class="text-secondary pl-2"><?= $allCommentsInfo[$i][2] ?></span></h6>
                    <p><?= $allCommentsInfo[$i][3] ?></p>
                </div>
                <div class="col-1 edit-delete" style="display:none;">
                    <div class="row justify-content-end">
                        <div class="col update-comment" data-updateTicket-id=<?= $id ?> id="update-<?= $allCommentsInfo[$i][4] ?>" style="cursor:pointer;">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <div class="col delete-comment" data-deleteTicket-id=<?= $id ?> id="delete-<?= $allCommentsInfo[$i][4] ?>" style="cursor:pointer;">
                            <i class="fas fa-trash fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<form action="" method="post">
    <div class="row pt-4 comment-section" style="display:none;">
        <div class="row">
            <div class="col sliding-effect"></div>
        </div>
        <div class="col-1">
            <div style="height:50px; width:50px; border-radius:50%; overflow:hidden">
                <?php if (strlen($displayPic) === 2) { ?>
                    <span class="text-dark text-center dot editable" style="padding-top: 12px;"><?= $displayPic ?></span>
                <?php } else { ?>
                    <image class="profile-img editable" src="uploads/profile-pictures/<?= $displayPic ?>" style="width:50px; height:50px;"></image>
                <?php } ?>
            </div>
        </div>
        <div class="col pl-0" style="position: relative;">
            <textarea class="editable" name="comment" id="editor2" rows="3" style="width: 100%; box-sizing: border-box;"></textarea>
            <button type="submit" name="add-comment" class="btn btn-primary editable" style="position:absolute; right: 105px; bottom: 10px;">Comment</button>
            <button class="btn btn-secondary cancel-comment" style="position:absolute; right: 25px; bottom: 10px;">Cancel</button>
        </div>
    </div>
</form>

<script src="js/ticket-attachments.js"></script>