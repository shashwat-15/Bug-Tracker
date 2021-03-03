<?php
$page = "index";
include "./partials/header.php";

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<link rel="stylesheet" href="./css/style.css">

<!-- <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'></script>
<script type='text/javascript' src='js/jquery.ba-hashchange.min.js'></script>
<script type='text/javascript' src='js/dynamicpage.js'></script> -->
<section id="main-content">
    <div id="guts">
        <div class="row also-in-user" data-user-id="" style="width:100%;">
            <canvas id="lineChart" width="100%" height="22%"></canvas>
        </div>
        <div class="row justify-content-center" id="lineLegend" style="width:100%;"></div>
        <div class="row pt-4 pb-4" style="width:100%;"></div>
        <div class="row" style="width:100%;">
            <div class="col" style="width:100%;">
                <canvas id="pieChart" width="100%" height="70%"></canvas>
            </div>
            <div class="row justify-content-center" id="pieLegend"></div>
            <div class="col" style="width:100%;">
                <canvas id="barChart" width="100%" height="80%"></canvas>
            </div>
            <div class="col" style="width:100%;">
                <canvas id="doughnutChart" width="100%" height="70%"></canvas>
            </div>
            <div class="row justify-content-center pr-4" id="doughnutLegend"></div>
            <div class="row justify-content-center pr-4" id="doughnutInfo"></div>
        </div>
    </div>
</section>
</div>
</div>
<script src="js/graphs/line-graph.js"></script>
<script src="js/graphs/pie-graph.js"></script>
<script src="js/graphs/bar-graph.js"></script>
<script src="js/graphs/doughnut-graph.js"></script>
<!-- <script type="text/javascript" src="js/dynamicpage.js"></script> -->

<?php include "./partials/footer.php" ?>

<!-- <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-hashchange/1.3/jquery.ba-hashchange.min.js" integrity="sha512-mw9mo/7+oCkK7v/olb32W+dkZR7PoZ2nXwHR50p7JAFMGDSwmtNfOklZvTxQPPQxhTbI4zP4dvbVrNKfU/rtNg==" crossorigin="anonymous"></script>
<script type='text/javascript' src='js/dynamicpage.js'></script>
</body>
</html> -->