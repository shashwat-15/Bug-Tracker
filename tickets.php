<?php
$page = "tickets";
include "./partials/header.php";

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<link rel="stylesheet" href="css/style.css">
<section id="main-content">
    <div id="guts">
        <?php include "template.php"; ?>
    </div>
    <div class="new-form-container pr-5 pt-4">
        <?php include "new-template.php" ?>
    </div>
</section>
</div>
<script src="js/combobox.js"></script>
<?php include "./partials/footer.php" ?>