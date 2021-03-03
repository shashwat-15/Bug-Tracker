
//----------------------Pagination--------------------------------------------
// Get the container element
var paginationContainer = document.querySelector(".pagination-container");

// Get all li with class="page-number" inside the container
var pageNumbers = paginationContainer.getElementsByClassName("page-number");

// Loop through the li and add the active class to the current/clicked li
for (var i = 0; i < pageNumbers.length; i++) {
    pageNumbers[i].addEventListener("click", function() {
        var current = document.querySelector("li.active");
        current.classList.remove("active");
        current.querySelector("a").classList.add("text-dark");
        if(!this.classList.contains("active")){
            this.querySelector("a").classList.remove("text-dark");
            this.classList.add("active");
        }
        let allPages = document.querySelectorAll(".page-number");
        let lastPage = document.querySelectorAll(".page-number")[allPages.length-1];
        if(this === lastPage){
            if(!document.querySelector("li.next a").classList.contains("disabled")){
                document.querySelector("li.next a").classList.add("disabled");
            }
        }
        else{
            if(document.querySelector("li.next a").classList.contains("disabled")){
                document.querySelector("li.next a").classList.remove("disabled");
            }
        }

        let firstPage = document.querySelectorAll(".page-number")[0];
        if(this === firstPage){
            if(!document.querySelector("li.prev a").classList.contains("disabled")){
                document.querySelector("li.prev a").classList.add("disabled");
            }
        }
        else{
            if(document.querySelector("li.prev a").classList.contains("disabled")){
                document.querySelector("li.prev a").classList.remove("disabled");
            }
        }
  });
}

let allPages = document.querySelectorAll(".page-number");
if(allPages.length === 1) {
    document.querySelector("li.next a").classList.add("disabled");
}

document.querySelector("li.next a").addEventListener('click', function(){
    if(document.querySelector("li.prev a").classList.contains("disabled")){
        document.querySelector("li.prev a").classList.remove("disabled");
    }
    let current = document.querySelector("li.active");
    let allPages = document.querySelectorAll(".page-number");
    
    let lastPage = document.querySelectorAll(".page-number")[allPages.length-1];
    let lastElementTab = lastPage.getAttribute("tabindex");
    
    let currentTab = current.getAttribute("tabindex");
    if(parseInt(currentTab) + 1 === parseInt(lastElementTab)){
        this.classList.add("disabled");
    }
    let nextElementTab = parseInt(currentTab) + 1;
    current.classList.remove("active");
    current.querySelector("a").classList.add("text-dark");
    let nextPage = document.querySelector("li[tabindex='"+nextElementTab+"']");
    nextPage.querySelector("a").classList.remove("text-dark");
    nextPage.classList.add("active");
});

document.querySelector("li.prev a").addEventListener('click', function(){
    if(document.querySelector("li.next a").classList.contains("disabled")){
        document.querySelector("li.next a").classList.remove("disabled");
    }
    let current = document.querySelector("li.active");
    let firstPage = document.querySelectorAll(".page-number")[0];
    let firstElementTab = firstPage.getAttribute("tabindex");
    
    let currentTab = current.getAttribute("tabindex");
    if(parseInt(currentTab) - 1 === parseInt(firstElementTab)){
        this.classList.add("disabled");
    }
    let prevElementTab = parseInt(currentTab) - 1;
    current.classList.remove("active");
    current.querySelector("a").classList.add("text-dark");
    let prevPage = document.querySelector("li[tabindex='"+prevElementTab+"']");
    prevPage.querySelector("a").classList.remove("text-dark");
    prevPage.classList.add("active");
});

let allItems = document.querySelectorAll(".hover-background");
for(let i =0; i<allItems.length; i++){
    allItems[i].addEventListener('mouseover', function(){
        $(this).find(".template-delete").show();
    });
    allItems[i].addEventListener('mouseout', function(){
        $(this).find(".template-delete").hide();
    });
}