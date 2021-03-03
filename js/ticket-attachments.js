

$(".attachment-container").click(function(e) {
    $("#file-upload").click();
});

$(document).ready(function(){
    let allImagesNames = document.querySelectorAll(".image-name");
    for(let i = 0; i <allImagesNames.length; i++){
        allImagesNames[i].addEventListener('click', function(){
        let imageURL = $(this).find("span").attr("data-attachment-url");
        $(this).find("span > a").attr("target", "");
        $("#myModal img").attr("src", "uploads/ticket-attachments/"+imageURL);
        $("#myModal").show();
        });
    }
    let allFileNames = document.querySelectorAll(".file-name");
    for(let i = 0; i <allFileNames.length; i++){
        allFileNames[i].addEventListener('click', function(){
            let fileURL = $(this).find("span").attr("data-attachment-url");
            $(this).find("span > a").attr("href", "uploads/ticket-attachments/"+fileURL);
        });
    }
    $("span.close").on('click',function(){
        $("#myModal").hide();
    });

    document.getElementById("attachment-form").onchange = function() {
        let ticketID = $(".attachment-container").attr("data-ticket-id");
        var formData = new FormData(this);
        $.ajax({
            url  : "database/add-attachments-database.php?ticket_id="+ticketID,
            type : "POST",
            cache: false,
            contentType : false, // you can also use multipart/form-data replace of false
            processData: false,
            data: formData,
            success:function(response){
                $(".ticket-info").load("ticket-attachments.php?ticketid="+ticketID);
            //alert("Image uploaded Successfully");
            }
        });
    };

    

    let allDeleteIcons = document.querySelectorAll(".delete-attachment");
    //console.log(allDeleteIcons);
    for(let i = 0; i <allDeleteIcons.length; i++){
        allDeleteIcons[i].addEventListener('click', function(){
            let attachmentID = $(this).attr("data-attachment-id");
            let ticketID = $(".attachment-container").attr("data-ticket-id");
            fetch("database/attachment-delete.php?attachmentid="+attachmentID+"&ticketid="+ticketID)
            .then(res => res.text())
            .then((result) =>{
                //console.log(result);
                $(".ticket-info").load("ticket-attachments.php?ticketid="+ticketID);
            });
        });
    }

    // let allAttachments = document.querySelectorAll(".hover-background");
    // for(let i =0; i<allAttachments.length; i++){
    //     allAttachments[i].addEventListener('mouseover', function(){
    //         $(this).next(".delete-attachment").show();
    //     });
    //     allAttachments[i].addEventListener('mouseout', function(){
    //         $(this).next(".delete-attachment").hide();
    //     });
    // }
});