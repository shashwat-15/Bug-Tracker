// if($("input.project-search").is(":focus")){
//     $(".change-color-on-focus").css("background-color", "black");
//    // console.log($(".change-color-on-focus").css("background-color"));
//     console.log("yo");
// }

$(document).ready(function() {

    $('input.project-search').blur(function() {
        $(".change-color-on-focus").css("background-color", "rgba(0,0,0,0.1)");
      })
      .focus(function() {
        $(".change-color-on-focus").css("background-color", "black");
      });

      $("#profile-container").click(function(e) {
        $("#image-upload").click();
    });
    
    function fasterPreview( uploader ) {
        if ( uploader.files && uploader.files[0] ){
              $('#profile-image').attr('src', 
                 window.URL.createObjectURL(uploader.files[0]) );
        }
    }
    
    $("#image-upload").change(function(){
        if($("#profile-image").css("display", "none")){
            $('#profile-image').show();
            $(".not-image").hide();
        }
        fasterPreview( this );
    });

    // let selectedProjects = [];
    // allProjectCheckboxes = document.querySelectorAll(".project-checkbox");
    // for(let i =0; i<allProjectCheckboxes.length; i++) {
    //     allProjectCheckboxes[i].addEventListener('click', function(){
    //         let selectedProject = $(this).next("label").text();
    //         selectedProject = selectedProject.trim();
    //         if(this.checked === true){
    //             selectedProjects.push(selectedProject);
    //         }
    //         else{
    //             if(selectedProjects.includes(selectedProject)){
    //                 selectedProjects.splice(selectedProjects.indexOf(selectedProject), 1);
    //             }
    //         }
    //     });
        
    // }

    

});

function toggle(source) {
    checkboxes = document.getElementsByClassName('project-checkbox');
    for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
    }
}

