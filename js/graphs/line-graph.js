
let today = new Date();

let todayWeek = [];
for(let i = 6; i>0; i--){
    let dateBefore = new Date(Date.now() - i * 24 * 60 * 60 * 1000);
    todayWeek.push(dateBefore.toString().substr(4,6));
}
todayWeek.push(today.toString().substr(4,6))


const colorClasses = ["text-primary", "text-warning", "text-success"];

const bgcolorClasses = ["bg-primary", "bg-warning", "bg-success"];





let userID = document.querySelector(".also-in-user").getAttribute("data-user-id");
//if(userID.length > 0){
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
    const monthNumbers = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    allTicketsStatusData = [];
    let openTickets = [];
    let openDates = [];
    let openCounts = [];
    let onHoldTickets = [];
    let onHoldDates = [];
    let onHoldCounts = [];
    let closedTickets = [];
    let closedDates = [];
    let closedCounts = [];
    fetch("../../bug-tracker/database/user-ticket-stats.php?userid="+userID)
    .then(res => res.json())
    .then((result) => {
        for(let j = 0; j<result[0].length; j++){
            for(let i =0; i< monthNames.length; i++){
                if(result[0][j][1].substr(5, 2) === monthNumbers[i]){
                    let date = monthNames[i] + " " + result[0][j][1].substr(8, 2);
                    openDates.push(date);
                    openCounts.push(result[0][j][0]);
                }
            }
        }

        for(let j = 0; j<result[1].length; j++){
            for(let i =0; i< monthNames.length; i++){
                if(result[1][j][1].substr(5, 2) === monthNumbers[i]){
                    let date = monthNames[i] + " " + result[1][j][1].substr(8, 2);
                    onHoldDates.push(date);
                    onHoldCounts.push(result[1][j][0]);
                }
            }
        }

        for(let j = 0; j<result[2].length; j++){
            for(let i =0; i< monthNames.length; i++){
                if(result[2][j][1].substr(5, 2) === monthNumbers[i]){
                    let date = monthNames[i] + " " + result[2][j][1].substr(8, 2);
                    closedDates.push(date);
                    closedCounts.push(result[2][j][0]);
                }
            }
        }

        let openIndex = 0;
        let onHoldIndex = 0;
        let closeIndex = 0;
        for(let k =0; k<todayWeek.length; k++){
            
            if(openDates[openIndex] === todayWeek[k]){
                openTickets.push(parseInt(openCounts[openIndex]));
                openIndex++;
            }
            else{
                openTickets.push(0);
            }

            if(onHoldDates[onHoldIndex] === todayWeek[k]){
                onHoldTickets.push(parseInt(onHoldCounts[onHoldIndex]));
                onHoldIndex++;
            }
            else{
                onHoldTickets.push(0);
            }

            if(closedDates[closeIndex] === todayWeek[k]){
                closedTickets.push(parseInt(closedCounts[closeIndex]));
                closeIndex++;
            }
            else{
                closedTickets.push(0);
            }
            
        }
        allTicketsStatusData.push(openTickets);
        allTicketsStatusData.push(onHoldTickets);
        allTicketsStatusData.push(closedTickets);
        useLineChart(todayWeek, allTicketsStatusData);
        
    });


    function useLineChart(label, data){
        var ctx = document.getElementById('lineChart').getContext('2d');

        //var defaultLegendClickHandler = Chart.defaults.global.legend.onClick;

        Chart.defaults.global.elements.point.radius = 4;
        Chart.defaults.global.elements.point.hoverRadius = 5;
        Chart.defaults.global.elements.line.borderWidth = 2;

        window.ticketChart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: label,
                datasets: [{
                    label: 'Open Tickets (total)',
                    borderColor: 'rgb(0, 123, 255)',
                    fill: false,
                    data: data[0]
                }, {
                    label: 'On Hold Tickets (total)',
                    borderColor: 'rgb(255, 193, 7)',
                    fill: false,
                    data: data[1]
                }, {
                    label: 'Closed Tickets (total)',
                    borderColor: 'rgb(40, 167, 69)',
                    fill: false,
                    data: data[2]
                }],
            },

            // Configuration options go here
            options: {
                layout: {
                    padding: {
                        left: 50,
                        right: 50,
                        top: 0,
                        bottom: 0
                    }
                },
                hover:{
                    mode: 'nearest'
                },
                legend: {
                    display: false
                },
                legendCallback: function(chart) {
                    //console.log(chart.data.datasets[0]);
                    var text = [];
                    let total1 = 0;
                    let total2 = 0;
                    let total3 = 0;
                    
                    // let total4 = 0;
                    //text.push('<div class = "row">');
                    for (var i=0; i<chart.data.datasets.length; i++) {
                        text.push('<div class="col-3 pt-1 pb-1 ml-5 bg-light text-dark border legend" onclick="updateDataset(event, ' + '\'' + chart.legend.legendItems[i].datasetIndex + '\'' + '); active('+i+')">');
                        
                        for(var j=0; j<chart.data.datasets[i].data.length; j++) {
                            if(i === 0){
                                total1 += chart.data.datasets[i].data[j];
                            }
                            else if(i === 1) {
                                total2 += chart.data.datasets[i].data[j];
                            }
                            else if(i === 2) {
                                total3 += chart.data.datasets[i].data[j];
                            }
                            // else if(i === 3) {
                            //     total4 += chart.data.datasets[i].data[j];
                            // }
                        }
                        text.push('<div class="row justify-content-center">');
                        if(i === 0)
                            text.push('<span class="h5 total '+colorClasses[i]+'">'+total1 + "</span>");
                        if(i === 1)
                            text.push('<span class="h5 total '+colorClasses[i]+'">'+total2 + "</span>");
                        if(i === 2)
                            text.push('<span class="h5 total '+colorClasses[i]+'">'+total3 + "</span>");
                        text.push("</div>");
                        text.push('<div class="row justify-content-center">');
                        text.push('<p class="text-secondary label">' + chart.data.datasets[i].label + '</p>');
                        text.push('</div>');
                        text.push('</div>');
                    }
                    return text.join("");
                }
            }
        });


        let text = ticketChart.generateLegend();
        document.getElementById("lineLegend").innerHTML = text;

        updateDataset = function(e, datasetIndex) {
            var index = datasetIndex;
            var ci = e.view. ticketChart;
            var meta = ci.getDatasetMeta(index);

            // See controller.isDatasetVisible comment
            meta.hidden = meta.hidden === null? !ci.data.datasets[index].hidden : null;

            // We hid a dataset ... rerender the chart
            ci.update();
        };

        allLegends = document.querySelectorAll(".legend");
        for(let i =0; i< allLegends.length; i++){
            allLegends[i].addEventListener('mouseover', function(){
                document.querySelectorAll(".total")[i].classList.remove(colorClasses[i]);
                document.querySelectorAll(".total")[i].classList.add("text-secondary");
                document.querySelectorAll(".label")[i].classList.remove("text-secondary");
                document.querySelectorAll(".label")[i].classList.add(colorClasses[i]);
            });
            allLegends[i].addEventListener('mouseleave', function(){
                document.querySelectorAll(".label")[i].classList.remove(colorClasses[i]);
                document.querySelectorAll(".label")[i].classList.add("text-secondary");
                document.querySelectorAll(".total")[i].classList.remove("text-secondary");
                document.querySelectorAll(".total")[i].classList.add(colorClasses[i]);
            });
        }

        active = function(index){
            allLegends[index].classList.toggle("bg-light");
            allLegends[index].classList.toggle(bgcolorClasses[index]);
            document.querySelectorAll(".label")[index].classList.toggle("text-light");
            document.querySelectorAll(".total")[index].classList.toggle("text-light");
        }
    }

//}


// if(userID.length === 0){
//     window.ticketChart = new Chart(ctx, {
//         // The type of chart we want to create
//         type: 'line',

//         // The data for our dataset
//         data: {
//             labels: todayWeek,
//             datasets: [{
//                 label: 'Open Tickets (total)',
//                 borderColor: 'rgb(0, 123, 255)',
//                 fill: false,
//                 data: [0,0,1,2,0,3,1]
//             }, {
//                 label: 'On Hold Tickets (total)',
//                 borderColor: 'rgb(255, 193, 7)',
//                 fill: false,
//                 data: [1,3,0,0,2,1,1]
//             }, {
//                 label: 'Closed Tickets (total)',
//                 borderColor: 'rgb(40, 167, 69)',
//                 fill: false,
//                 data: [1,1,1,4,3,4,0]
//             }],
//         },

//         // Configuration options go here
//         options: {
//             layout: {
//                 padding: {
//                     left: 50,
//                     right: 50,
//                     top: 0,
//                     bottom: 0
//                 }
//             },
//             hover:{
//                 mode: 'nearest'
//             },
//             legend: {
//                 display: false
//             },
//             legendCallback: function(chart) {
//                 //console.log(chart.data.datasets[0]);
//                 var text = [];
//                 let total1 = 0;
//                 let total2 = 0;
//                 let total3 = 0;
                
//                 // let total4 = 0;
//                 //text.push('<div class = "row">');
//                 for (var i=0; i<chart.data.datasets.length; i++) {
//                     text.push('<div class="col-3 pt-1 pb-1 ml-5 bg-light text-dark border legend" onclick="updateDataset(event, ' + '\'' + chart.legend.legendItems[i].datasetIndex + '\'' + '); active('+i+')">');
                    
//                     for(var j=0; j<chart.data.datasets[i].data.length; j++) {
//                         if(i === 0){
//                             total1 += chart.data.datasets[i].data[j];
//                         }
//                         else if(i === 1) {
//                             total2 += chart.data.datasets[i].data[j];
//                         }
//                         else if(i === 2) {
//                             total3 += chart.data.datasets[i].data[j];
//                         }
//                         // else if(i === 3) {
//                         //     total4 += chart.data.datasets[i].data[j];
//                         // }
//                     }
//                     text.push('<div class="row justify-content-center">');
//                     if(i === 0)
//                         text.push('<span class="h5 total '+colorClasses[i]+'">'+total1 + "</span>");
//                     if(i === 1)
//                         text.push('<span class="h5 total '+colorClasses[i]+'">'+total2 + "</span>");
//                     if(i === 2)
//                         text.push('<span class="h5 total '+colorClasses[i]+'">'+total3 + "</span>");
//                     text.push("</div>");
//                     text.push('<div class="row justify-content-center">');
//                     text.push('<p class="text-secondary label">' + chart.data.datasets[i].label + '</p>');
//                     text.push('</div>');
//                     text.push('</div>');
//                 }
//                 return text.join("");
//             }
//         }
//     });


//     let text = ticketChart.generateLegend();
//     document.getElementById("lineLegend").innerHTML = text;

//     updateDataset = function(e, datasetIndex) {
//         var index = datasetIndex;
//         var ci = e.view. ticketChart;
//         var meta = ci.getDatasetMeta(index);

//         // See controller.isDatasetVisible comment
//         meta.hidden = meta.hidden === null? !ci.data.datasets[index].hidden : null;

//         // We hid a dataset ... rerender the chart
//         ci.update();
//     };

//     allLegends = document.querySelectorAll(".legend");
//     for(let i =0; i< allLegends.length; i++){
//         allLegends[i].addEventListener('mouseover', function(){
//             document.querySelectorAll(".total")[i].classList.remove(colorClasses[i]);
//             document.querySelectorAll(".total")[i].classList.add("text-secondary");
//             document.querySelectorAll(".label")[i].classList.remove("text-secondary");
//             document.querySelectorAll(".label")[i].classList.add(colorClasses[i]);
//         });
//         allLegends[i].addEventListener('mouseleave', function(){
//             document.querySelectorAll(".label")[i].classList.remove(colorClasses[i]);
//             document.querySelectorAll(".label")[i].classList.add("text-secondary");
//             document.querySelectorAll(".total")[i].classList.remove("text-secondary");
//             document.querySelectorAll(".total")[i].classList.add(colorClasses[i]);
//         });
//     }

//     active = function(index){
//         allLegends[index].classList.toggle("bg-light");
//         allLegends[index].classList.toggle(bgcolorClasses[index]);
//         document.querySelectorAll(".label")[index].classList.toggle("text-light");
//         document.querySelectorAll(".total")[index].classList.toggle("text-light");
//     }
// }
