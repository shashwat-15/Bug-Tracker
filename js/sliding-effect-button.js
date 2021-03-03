//-------------------Button-------------------------

document.querySelector("button.add-new").addEventListener('click', function(){
    document.querySelector(".new-form-container").style.display = "block";
    document.querySelector(".new-form").style.display = "block";
    document.querySelector(".new-form-container").classList.add("slide");
    let allShiftRightDivs = document.querySelectorAll(".sliding-effect");
    for(let i =0; i< allShiftRightDivs.length; i++){
        allShiftRightDivs[i].classList.add("shift-slide");
    }
    document.querySelector(".move-left-on-btnClick").style.left = "620px";
});

document.querySelector(".cancel-form").addEventListener('click', function(e){
    e.preventDefault();
    document.querySelector(".new-form-container").style.display = "none";
    document.querySelector(".new-form").style.display = "none";
    document.querySelector(".new-form-container").classList.remove("slide");
    let allShiftRightDivs = document.querySelectorAll(".sliding-effect");
    for(let i =0; i< allShiftRightDivs.length; i++){
        allShiftRightDivs[i].classList.remove("shift-slide");
    }
    document.querySelector(".move-left-on-btnClick").style.left = "370px";

    $(".extra").remove();
    $(".canvas-line").removeClass("col-11");
    $(".canvas-line").addClass("col-12");
    $(".canvas-line").css("right", "0");
});