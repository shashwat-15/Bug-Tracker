<link rel="stylesheet" href="css/attachments.css">
<link rel="stylesheet" href="css/modal.css">
<link rel="stylesheet" href="css/ticket-attachments.css">
<?php include "database/ticket-attachments-database.php"; ?>
<div class="row">
    <div class="col sliding-effect"></div>
</div>
<form action="" enctype="multipart/form-data" id="attachment-form">
    <div class="col add-attachment">
        <span class="text-center dot attachment-container" data-ticket-id="<?= $_GET['ticketid'] ?>" style="padding-top: 12px; border-color: rgb(0, 123, 255); color: rgb(0, 123, 255);"><i class="far fa-plus fa-lg"></i></span>
        <input id="file-upload" type="file" name="attachment">
    </div>
</form>
<div class="row" style="width:100%;">
    <div class="col" style="height:530px; overflow-y:scroll;">
        <?php
        for ($i = 0; $i < count($allAttachmentsInfo); $i++) { ?>
            <div class="row" style="width: 100%; padding-left: 39px;">
                <div class="row">
                    <div class="col sliding-effect"></div>
                </div>
                <div class="col">
                    <div class="vl" style="border-left: 1px solid rgb(208,208,208); height: 50px;"></div>
                </div>
            </div>
            <div class="row" style="padding-left:13px;">
                <div class="row">
                    <div class="col sliding-effect"></div>
                </div>

                <div class="col">
                    <div class="row">
                        <div class="pl-3 pt-1" style="width:70px;"><span class="text-center dot" style="padding-top: 12px;"><i class="far fa-file-<?= $allAttachmentsInfo[$i][0] ?> fa-lg"></i></span></div>
                        <div style="padding-top:12px;"><span style="color: rgb(208,208,208);">. . . . .</span></div>
                        <div class="col pl-4">
                            <div class="row pb-2 text-secondary" style="font-size: 12px;"><?= $allAttachmentsInfo[$i][3] ?></div>
                            <div class="row text-dark <?php if ($allAttachmentsInfo[$i][0] === "pdf" || $allAttachmentsInfo[$i][0] === "word") { ?>file-name<?php } else { ?>image-name<?php } ?>" style="cursor:pointer;">
                                <span data-attachment-url="<?= $allAttachmentsInfo[$i][1] ?>" style="margin-top:-10px">
                                    <a href="#" target="_blank" class="text-dark" style="text-decoration:none;"><?= $allAttachmentsInfo[$i][1] ?></a>
                                </span>
                            </div>
                            <div class="row text-secondary" style="font-size: 12px;">Attached by <?= $allAttachmentsInfo[$i][4] ?> &nbsp&nbsp.&nbsp&nbsp <?= $allAttachmentsInfo[$i][2] ?> KB</div>
                        </div>
                    </div>
                </div>
                <div class="col-1 delete-attachment" data-attachment-id="<?= $allAttachmentsInfo[$i][5] ?>" style="cursor:pointer; width:30px;">
                    <div class="row justify-content-end">
                        <div class="col">
                            <i class="fas fa-trash fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

        <div class="row" style="width: 100%; padding-left: 39px;">
            <div class="row">
                <div class="col sliding-effect"></div>
            </div>
            <div class="col">
                <div class="vl" style="border-left: 1px solid rgb(208,208,208); height: 50px;"></div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal">
    <div class="modal-content" style=" border:none; background-color:transparent; left:150px; top:0px;">
        <div class="row pb-4">
            <div class="col">
                <span class="close">&times;</span>
            </div>
        </div>
        <image src="" style="width:100%; height: 100%;"></image>
    </div>
</div>

<script src="js/sliding-effect-button.js"></script>
<script src="js/modal.js"></script>
<script src="js/ticket-attachments.js"></script>