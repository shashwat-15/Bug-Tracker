let deleteIcons = document.querySelectorAll(".delete");
for(let i = 0; i<deleteIcons.length; i++){
    
    deleteIcons[i].addEventListener('click', function(){
        let projectID = this.getAttribute("data-delete-item");
        //console.log(projectID);
        let iconID = this.getAttribute("id");
        let id = iconID.substr(9);
        //console.log(id);
        let type = iconID.substr(7,1);
        //console.log(type);
        fetch("database/project-details-delete.php?type="+type+"&id="+id+"&projectid="+projectID)
        .then(response => response.text())
        .then((result) =>{
            location.reload();
        });
    });
}
//dustbin js
// $("button.centerMe").click(function(){
//  if($(this).hasClass("confirm")){
//  $(this).addClass("done");
//  $("span").text("Deleted");
//  } else {
//  $(this).addClass("confirm");
//  $("span").text("Are you sure?");
//  }
// });
 
// // Reset
// $("button.centerMe").on('mouseout', function(){
//  if($(this).hasClass("confirm") || $(this).hasClass("done")){
//  setTimeout(function(){
//  $("button").removeClass("confirm").removeClass("done");
//  $("span").text("Delete");
//  }, 3000);
//  }
// });
