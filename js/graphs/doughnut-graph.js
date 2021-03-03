let top3Users = [];
let userTicketsCount = [];

fetch("../../bug-tracker/database/tickets-users-database.php")
.then(res => res.json())
.then((result) => {
    for(let i =0; i <result[0].length; i++){
        top3Users.push(result[0][i].trim());
    }
    for(let i =0; i <result[1].length; i++){
        userTicketsCount.push(parseInt(result[1][i]));
    }
    //console.log(result);
    top3UserTickets(top3Users, userTicketsCount);
});

function top3UserTickets(label, data){
    var ctx = document.getElementById('doughnutChart').getContext('2d');

    var doughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                backgroundColor: ['rgb(0, 123, 255)', 'rgb(23, 162, 184)', 'rgb(255, 193, 7)'],
                hoverBackgroundColor:['rgb(0,239,255)','rgb(23, 255, 184)','rgb(255, 255, 0)'],
                data: data,
                borderAlign: 'inner'
            }],
            labels: label
        },
        options: {
            cutoutPercentage: 70,
            title: {
                display: true,
                text: 'Tickets by Users',
                position: 'top',
                fontColor: '#343a40',
                fontSize: 16
            },
            legend: {
                display: false,
            },
            legendCallback: function(chart) {
                var legends = [];
                var legend = [];
                
                for(var i = 0; i<chart.data.datasets[0].data.length; i++){
                    //legend.push('<div class="row"');
                    legend.push('<div class="col p-0 ">');
                    legend.push('<p class="mt-2" style="background-color: '+chart.data.datasets[0].backgroundColor[i]+';border-radius: 3px; height: 11px; width: 11px;"></p>')
                    legend.push('</div>');
                    
                    legend.push('<div class="col pl-2 text-secondary">');
                    legend.push('<span class="text-secondary">' + chart.data.labels[i] + '</span>');
                    // legend.push('</div>');
                    // legend.push('<div class="col">');
                    legend.push('<span class="text-secondary pl-1">('+chart.data.datasets[0].data[i]+')</span>');
                    legend.push('</div>');
                    //legend.push('</div>');
                }
                legends.push(legend.join(""));
                var text = [];
                var total = 0;
                text.push('<div class="col">')
                for (var i=0; i<chart.data.datasets[0].data.length; i++) {
                    total += chart.data.datasets[0].data[i];
                }
                text.push('<div class="row justify-content-center">');
                text.push('<span class="h4">'+ total + "</span>");
                text.push("</div>");
                text.push('<div class="row justify-content-center">');
                text.push('<p class="text-secondary" style="font-size: 26px;">Tickets</p>');
                text.push('</div>');
                text.push('</div>');
                legends.push(text.join(""));
                return legends;
            }
        }
    });

    let customDoughnutLegend = doughnutChart.generateLegend()[0];
    let doughnutLegend = document.getElementById("doughnutLegend"); 
    doughnutLegend.innerHTML = customDoughnutLegend;

    let legendInfo = doughnutChart.generateLegend()[1];
    let doughnutInfo = document.getElementById("doughnutInfo"); 
    doughnutInfo.innerHTML = legendInfo;
}