document.querySelector(".add-new-comment").addEventListener('click', function(){
    document.querySelector(".comment-section").style.display = "flex";
});

document.querySelector(".cancel-comment").addEventListener('click', function(){
    document.querySelector(".comment-section").style.display = "none";
});

document.querySelector(".attachments").addEventListener('click', function(e){
    if(document.querySelector(".new-form-container").style.display === "block"){
        document.querySelector(".new-form").style.display = "none";
        document.querySelector(".new-form-container").classList.remove("slide");
        let allShiftRightDivs = document.querySelectorAll(".sliding-effect");
        for(let i =0; i< allShiftRightDivs.length; i++){
            allShiftRightDivs[i].classList.remove("shift-slide");
        }
    }
    document.querySelector(".add-new-comment").style.display = "none";
    if(document.querySelector(".comment-section").style.display !== "none"){
        document.querySelector(".comment-section").style.display = "none";
    }
    if(document.querySelector(".comments-container").style.display !== "none"){
        document.querySelector(".comments-container").style.display = "none";
    }
    let ticketID = $("span#attachments-ticket-id").attr("data-ticket-id");
    $(".ticket-info").load("ticket-attachments.php?ticketid="+ticketID);
    let current = document.querySelector("li.active");
    current.classList.remove("active");
    if(!this.classList.contains("active")){
        this.classList.add("active");
    } 
});

document.querySelector(".history").addEventListener('click', function(e){
    if(document.querySelector(".new-form-container").style.display === "block"){
        document.querySelector(".new-form").style.display = "none";
        document.querySelector(".new-form-container").classList.remove("slide");
        let allShiftRightDivs = document.querySelectorAll(".sliding-effect");
        for(let i =0; i< allShiftRightDivs.length; i++){
            allShiftRightDivs[i].classList.remove("shift-slide");
        }
    }
    document.querySelector(".add-new-comment").style.display = "none";
    if(document.querySelector(".comment-section").style.display !== "none"){
        document.querySelector(".comment-section").style.display = "none";
    }
    if(document.querySelector(".comments-container").style.display !== "none"){
        document.querySelector(".comments-container").style.display = "none";
    }
    let ticketID = $("span#activity-ticket-id").attr("data-ticket-id");
    $(".ticket-info").load("ticket-history.php?ticketid="+ticketID);
    let current = document.querySelector("li.active");
    current.classList.remove("active");
    if(!this.classList.contains("active")){
        this.classList.add("active");
    } 
});

let allNavItems = document.querySelectorAll("#navbarNav nav-item");
for(let i=0; i< allNavItems.length; i++) {
    allNavItems[i].addEventListener('click', function(){
        
    });
}

//on hover show edit and delete buttons
let allComments = document.querySelectorAll(".hover-background");
for(let i =0; i<allComments.length; i++){
    allComments[i].addEventListener('mouseover', function(){
        $(this).find(".edit-delete").show();
    });
    allComments[i].addEventListener('mouseout', function(){
        $(this).find(".edit-delete").hide();
    });
}

//delete comment
let deleteCommentIcons = document.querySelectorAll(".delete-comment");
for(let i = 0; i<deleteCommentIcons.length; i++){
    
    deleteCommentIcons[i].addEventListener('click', function(){
        let ticketID = this.getAttribute("data-deleteTicket-id");
        let iconID = this.getAttribute("id");
        let id = iconID.substr(7);
        fetch("database/comment-delete.php?commentid="+id + "&ticketid=" + ticketID)
        .then(response => response.text())
        .then((result) =>{
            location.reload();
        });
    });
}

//edit comment
let editCommentIcons = document.querySelectorAll(".update-comment");
for(let i = 0; i<editCommentIcons.length; i++){
    editCommentIcons[i].addEventListener('click', function(){
        let ticketID = this.getAttribute("data-updateTicket-id");
        let iconID = this.getAttribute("id");
        let id = iconID.substr(7);
        fetch("database/comment-edit.php?commentid="+id + "&ticketid=" + ticketID)
        .then(response => response.json())
        .then((result) =>{
            //console.log(result);
            document.querySelector(".comment-section").style.display = "flex";
            if(result[0].length === 2){
                document.querySelector("span.editable").innerText = result[0];
            }
            else{
                document.querySelector("img.editable").setAttribute("src", "uploads/profile-pictures/"+ result[0]);
            }
            tinymce.activeEditor.setContent(result[1]);

            console.log(document.querySelector("textarea.editable"));
            document.querySelector("button.editable").innerText = "Save";
            document.querySelector("button.editable").addEventListener('click', function(e){
                e.preventDefault();
                let comment = tinymce.activeEditor.getContent({format: 'text'});
                fetch("database/comment-update.php?comment="+comment+"&commentid="+id+"&ticketid="+ticketID)
                .then(res =>res.text())
                .then((result) => {
                    location.reload(); 
                });
            })
            //location.reload();
        });
    });
}

