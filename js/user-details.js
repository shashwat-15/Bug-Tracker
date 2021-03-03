//--------------user-details-----------------//
$(".add-new").on('click', function(){
    $(".canvas-container").prepend("<div class='extra col'></div>");
    $(".canvas-line").removeClass("col-12");
    $(".canvas-line").addClass("col-11");
    $(".canvas-line").css("right", "-60px");
    $(".canvas-container").css("width", "99%");
}); 
