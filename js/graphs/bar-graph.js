const priorities = ["High", "Medium", "Low", "None"];
const prioritiesCount = [];
const availablePriorities = [];
fetch("../../bug-tracker/database/tickets-priorities-database.php")
.then(res => res.json())
.then((result) => {
    for(let i =0; i<result.length; i++){
        availablePriorities.push(result[i][1]);
        if(result[i][1] === priorities[0]){
            prioritiesCount[0] = parseInt(result[i][0]);
        }
        else if(result[i][1] === priorities[1]){
            prioritiesCount[1] = parseInt(result[i][0]);
        }
        else if(result[i][1] === priorities[2]){
            prioritiesCount[2] = parseInt(result[i][0]);
        }
        else if(result[i][1] === priorities[3]){
            prioritiesCount[3] = parseInt(result[i][0]);
        }

    }
    let f1 = false;
    if(availablePriorities.length < priorities.length){
        for(let i=0; i<priorities.length; i++){
            f1 = false;
            for(let j = 0; j <availablePriorities.length; j++){
                if(availablePriorities[j] === priorities[i]){ 
                    f1 = true;
                }
            }
            if(!f1){
                prioritiesCount[i] = 0;
            }
        }
    }
    
     ticketPriorityBarChart(priorities, prioritiesCount);
    //console.log(prioritiesCount);
});

function ticketPriorityBarChart(labels, data){
    var ctx = document.getElementById('barChart').getContext('2d');

    //Chart.defaults.global.hover.mode = 'point';
    // categoryAxis.renderer.labels.template.paddingBottom = 0;
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: [{
                label: labels[0],
                backgroundColor: 'rgb(220, 53, 69)',
                hoverBackgroundColor: 'rgb(255, 53, 0)',
                data: [data[0]]
            }, {
                label: labels[1],
                backgroundColor: 'rgb(255, 193, 7)',
                hoverBackgroundColor: 'rgb(255, 255, 0)',
                data: [data[1]]
            }, {
                label: labels[2],
                backgroundColor: 'rgb(23, 162, 184)',
                hoverBackgroundColor: 'rgb(23, 255, 184)',
                data: [data[2]]
            }, {
                label: labels[3],
                backgroundColor: 'rgb(108, 117, 125)',
                hoverBackgroundColor: 'rgb(52, 58, 64)',
                data: [data[3]]
            }]
        },
        options: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Tickets by Priority',
                position: 'top',
                fontColor: '#343a40',
                fontSize: 16
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            hover:{
                mode: 'nearest'
            },
            tooltips: {
                callbacks: {
                title: function() {}
                }
            }
        }
    });

}