//filling values in assign to checkbox
let selectedProjects = [];
let allTicketProjectCheckboxes = document.querySelectorAll(".for-ticket .project-checkbox");

$('.for-ticket .project-checkbox:checked').each(function() {
    let selectedProject = $(this).val();
    selectedProject = selectedProject.trim();
    if(this.checked === true){
        selectedProjects.push(selectedProject);
    }
    else{
        console.log('never entered');
        if(selectedProjects.includes(selectedProject)){
            selectedProjects.splice(selectedProjects.indexOf(selectedProject), 1);
        }
    }
    //console.log(selectedProjects);
    let assignedToSelectOptions = $("#assigned-to > .select > ul.select-options").empty();
    
    let assignedToSelect = $("#assigned-to > .select > .select-hidden").empty();
    
    fetch("database/assignedTo-custom-select.php?projects="+selectedProjects)
    .then(res => res.json())
    .then((result) => {
        for(let i =0; i<result.length; i++) {
            $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(assignedToSelect);
            $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(assignedToSelectOptions);
        }
    });
});

for(let i =0; i<allTicketProjectCheckboxes.length; i++) {
     allTicketProjectCheckboxes[i].addEventListener('click', function(){
        //$('.for-ticket .project-checkbox:checked').each(function() {
            let selectedProject = $(this).val();
            selectedProject = selectedProject.trim();
            if(this.checked === true){
                selectedProjects.push(selectedProject);
            }
            else{
                //console.log('never entered');
                if(selectedProjects.includes(selectedProject)){
                    selectedProjects.splice(selectedProjects.indexOf(selectedProject), 1);
                }
            }
            //console.log(selectedProjects);
            let assignedToSelectOptions = $("#assigned-to > .select > ul.select-options").empty();
            
            let assignedToSelect = $("#assigned-to > .select > .select-hidden").empty();
            
            fetch("database/assignedTo-custom-select.php?projects="+selectedProjects)
            .then(res => res.json())
            .then((result) => {
                for(let i =0; i<result.length; i++) {
                    $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(assignedToSelect);
                    $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(assignedToSelectOptions);
                }
            });
         //});
        
     });
 }
//-------------------Combobox------------------------------------------------
let $styledSelectValues = [];
$('select').each(function(){
   
    var $this = $(this), numberOfOptions = $(this).children('option').length;
    
    $this.addClass('select-hidden'); 
    $this.wrap('<div class="select"></div>');
    $this.after('<div class="select-styled"></div>');
   
    var $styledSelect = $this.next('div.select-styled');
    
    $styledSelect.click(function(){
        $(".select").css("border-color", "rgb(208, 208, 208)");
        $this.parent().css("border-color", "inherit");
    });
    
    $styledSelect.text($this.children('option').eq(0).text());

    $styledSelectValues.push($styledSelect.text());

    if($styledSelectValues[0] === "Manager" || $styledSelectValues[0] === "Employee" && $styledSelectValues.length === 2){
        $(".hide-for-admin").css("display", "block");
        $(".change-on-role").css("height", "30px");
    }
    else{
        $(".hide-for-admin").css("display", "none");
        $(".change-on-role").css("height", "30px");
    }
  
    var $list = $('<ul />', {
        'class': 'select-options'
    }).insertAfter($styledSelect);
  
    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    $list.css("height", "100px");
    $list.css("overflow-y", "scroll");
  
    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').not(this).each(function(){
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
        $list.children('li').click(function(e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            $this.parent().css("border-color", "rgb(208, 208, 208)");
        });
    });


    // fetch("database/allProjects-database.php")
    // .then(res => res.text())
    // .then((result1) => {
    //     for(let j=0; j<result1; j++){
    //         if($this.attr("name") === "assigned-team-"+j){
    //             let ownerSelectOptions = $("#assigned-owner-"+j+" > .select > ul.select-options").empty();
            
    //             let ownerSelect = $("#assigned-owner-"+j+" > .select > .select-hidden").empty();
        
                
    //             fetch("database/owner-custom-select.php?team="+$this.find(">:first-child").text())
    //             .then(res => res.json())
    //             .then((result) => {
    //                 for(let i =0; i<result.length; i++) {
    //                     $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(ownerSelect);
    //                     $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(ownerSelectOptions);
    //                 }
    //             });
    //         }
    //     }
    // });

    

    $list.children('li').click(function(e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.hide();
        $this.parent().css("border-color", "rgb(208, 208, 208)");
        if($this.attr("name") === "role"){

            if($this.val() === "Manager" || $this.val() === "Employee"){
                $(".hide-for-admin").css("display", "block");
                $(".change-on-role").css("height", "30px");
            }
            else if($this.val() === "Admin"){
                $(".hide-for-admin").css("display", "none");
                $(".change-on-role").css("height", "30px");
            }
        }

        if($this.attr("name") === "team"){
            let ownerSelectOptions = $("#owner > .select > ul.select-options").empty();
        
            let ownerSelect = $("#owner > .select > .select-hidden").empty();
            
            fetch("database/owner-custom-select.php?team="+$this.val())
            .then(res => res.json())
            .then((result) => {
                for(let i =0; i<result.length; i++) {
                    $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(ownerSelect);
                    $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(ownerSelectOptions);
                }
            });
        }

        // if($this.attr("name") === "project"){
        //     let assignedToSelectOptions = $("#assigned-to > .select > ul.select-options").empty();
        
        //     let assignedToSelect = $("#assigned-to > .select > .select-hidden").empty();
            
        //     fetch("database/assignedTo-custom-select.php?project="+$this.val())
        //     .then(res => res.json())
        //     .then((result) => {
        //         for(let i =0; i<result.length; i++) {
        //             $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(assignedToSelect);
        //             $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(assignedToSelectOptions);
        //         }
        //     });
        // }

        

        fetch("database/allProjects-database.php")
        .then(res => res.text())
        .then((result1) => {
            for(let j=0; j<result1; j++){
                if($this.attr("name") === "assigned-team-"+j){
                    let ownerSelectOptions = $("#assigned-owner-"+j+" > .select > ul.select-options").empty();
                
                    let ownerSelect = $("#assigned-owner-"+j+" > .select > .select-hidden").empty();
            
                    
                    fetch("database/owner-custom-select.php?team="+$this.val())
                    .then(res => res.json())
                    .then((result) => {
                        for(let i =0; i<result.length; i++) {
                            $('<option value="' + result[i] + '">' + result[i] + '</option>').appendTo(ownerSelect);
                            $('<li rel="' + result[i] + '">' + result[i] + '</li>').appendTo(ownerSelectOptions);
                        }
                    });
                }
            }
        });
    });
  
  
    $(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
        $(".select").css("border-color", "rgb(208, 208, 208)");
    });

    

});



