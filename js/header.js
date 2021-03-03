$(document).ready(function(){
    $("#notification-bell").on('mouseover', function(){
        $(this).removeClass("far fa-bell");
        $(this).addClass("fas fa-bell")
    });
    $("#notification-bell").on('mouseout', function(){
        $(this).removeClass("fas fa-bell");
        $(this).addClass("far fa-bell")
    });
    let navElements = document.querySelectorAll(".light-background a");
    //console.log(navElements);
    for(let i =0; i < navElements.length; i++) {
        navElements[i].addEventListener('click', function(){
            let current = document.getElementsByClassName("active");
            current[0].className = current[0].className.replace(" active", "");
            this.className += " active";
        });
    }
});
